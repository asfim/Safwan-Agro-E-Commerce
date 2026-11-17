<?php
/**
 * Elementor Compatibility & Safeguards
 * 
 * Resolves compatibility issues between Elementor Core (v4.x) and Elementor Pro (v3.20.x):
 * 1. Shims missing frontend handlers (e.g., NestedTabs) in the preview iframe to prevent 
 *    "Class extends value undefined is not a constructor or null".
 * 2. Safeguards ElementsManager in the editor window to prevent 
 *    "Element type already registered" fatal unhandled exceptions from halting editor initialization.
 * 3. Intercepts missing REST routes (e.g., elementor-kit-mcp) to prevent unhandled 404 rejections.
 * 4. Disables the false-alarm "Can't Edit? Enable Safe Mode" toast so slow local loads don't alarm users.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Safwan_Elementor_Compatibility {

    public static function init() {
        // Disable the false-alarm "Can't Edit? Enable Safe Mode" notice
        add_action( 'elementor/init', array( __CLASS__, 'disable_safe_mode_toast' ), 20 );

        // Frontend & Preview Iframe: Head shims + inline script after elementor-frontend-modules
        add_action( 'wp_head', array( __CLASS__, 'render_frontend_shims' ), 0 );
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_shims' ), 20 );

        // Editor Window: Safeguard registerElementType & fetch against errors
        add_action( 'elementor/editor/before_enqueue_scripts', array( __CLASS__, 'render_editor_safeguard' ), 0 );
        add_action( 'admin_head', array( __CLASS__, 'render_admin_safeguard' ), 0 );
    }

    /**
     * Disable Safe Mode Toast
     */
    public static function disable_safe_mode_toast() {
        if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->modules_manager ) ) {
            $safe_mode = \Elementor\Plugin::$instance->modules_manager->get_modules( 'safe-mode' );
            if ( $safe_mode ) {
                remove_action( 'elementor/editor/footer', array( $safe_mode, 'print_try_safe_mode' ) );
            }
        }
    }

    /**
     * Frontend / Preview Iframe Shims in <head>
     */
    public static function render_frontend_shims() {
        ?>
        <script id="safwan-elementor-frontend-shims">
        (function() {
            function ensureNestedTabs(h) {
                if (!h) return;
                if (!h.NestedTabs) {
                    var BaseClass = h.Base;
                    if (!BaseClass || typeof BaseClass !== 'function') {
                        BaseClass = class {
                            constructor() {}
                            getDefaultSettings() { return { selectors: {} }; }
                            getDefaultElements() { return {}; }
                            bindEvents() {}
                            run() {}
                        };
                    }
                    try {
                        class NestedTabsShim extends BaseClass {
                            constructor() { super(...arguments); }
                            getDefaultSettings() {
                                return (super.getDefaultSettings ? super.getDefaultSettings() : { selectors: {} });
                            }
                            getElements() { return {}; }
                        }
                        h.NestedTabs = NestedTabsShim;
                    } catch(e) {
                        h.NestedTabs = BaseClass;
                    }
                }
            }

            // Setup elementorModules interceptors
            window.elementorModules = window.elementorModules || {};
            var _em = window.elementorModules;
            _em.frontend = _em.frontend || {};
            var _fe = _em.frontend;
            var _handlers = _fe.handlers || {};
            ensureNestedTabs(_handlers);

            try {
                Object.defineProperty(_fe, 'handlers', {
                    configurable: true,
                    enumerable: true,
                    get: function() { return _handlers; },
                    set: function(val) {
                        _handlers = val;
                        ensureNestedTabs(_handlers);
                    }
                });
            } catch(e) {}

            // Polling backup
            var timer = setInterval(function() {
                if (window.elementorModules && window.elementorModules.frontend && window.elementorModules.frontend.handlers) {
                    ensureNestedTabs(window.elementorModules.frontend.handlers);
                }
            }, 10);
            setTimeout(function() { clearInterval(timer); }, 15000);
        })();
        </script>
        <?php
    }

    /**
     * Enqueue inline script after elementor-frontend-modules
     */
    public static function enqueue_frontend_shims() {
        $inline_js = '
        (function() {
            if (window.elementorModules && window.elementorModules.frontend && window.elementorModules.frontend.handlers) {
                var h = window.elementorModules.frontend.handlers;
                if (!h.NestedTabs) {
                    var Base = h.Base || class {
                        constructor() {}
                        getDefaultSettings() { return { selectors: {} }; }
                    };
                    try {
                        class NestedTabsShim extends Base {
                            constructor() { super(...arguments); }
                            getDefaultSettings() { return { selectors: {} }; }
                        }
                        h.NestedTabs = NestedTabsShim;
                    } catch(e) {
                        h.NestedTabs = Base;
                    }
                }
            }
        })();
        ';
        wp_add_inline_script( 'elementor-frontend-modules', $inline_js, 'after' );
        wp_add_inline_script( 'elementor-frontend', $inline_js, 'before' );
    }

    /**
     * Editor Safeguard: Prevent duplicate element registration & fetch 404 crashes
     */
    public static function render_editor_safeguard() {
        ?>
        <style>
        #elementor-try-safe-mode,
        .elementor-safe-mode-toast {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }
        </style>
        <script id="safwan-elementor-editor-safeguard">
        (function() {
            // Intercept window.fetch to silence 404 rest_no_route errors
            if (window.fetch) {
                var origFetch = window.fetch;
                window.fetch = async function(url, options) {
                    try {
                        var res = await origFetch.apply(this, arguments);
                        if (!res.ok && typeof url === 'string' && (url.indexOf('elementor') !== -1 || url.indexOf('wp-json') !== -1)) {
                            try {
                                var clone = res.clone();
                                var text = await clone.text();
                                if (text.indexOf('rest_no_route') !== -1) {
                                    return new Response(JSON.stringify({ success: true, data: {} }), {
                                        status: 200,
                                        headers: { 'Content-Type': 'application/json' }
                                    });
                                }
                            } catch(e) {}
                        }
                        return res;
                    } catch(err) {
                        return origFetch.apply(this, arguments);
                    }
                };
            }

            function patchMgr(mgr) {
                if (!mgr || mgr._safwan_guarded) return;
                mgr._safwan_guarded = true;

                // Patch instance method
                var orig = mgr.registerElementType;
                if (orig && typeof orig === 'function') {
                    mgr.registerElementType = function(el) {
                        try {
                            var type = (el && typeof el.getType === 'function') ? el.getType() : null;
                            if (type && this.elementTypes && this.elementTypes[type]) {
                                console.warn('[Safwan Safeguard] Duplicate element type safely ignored:', type);
                                return;
                            }
                            return orig.call(this, el);
                        } catch(err) {
                            if (err && err.message && err.message.indexOf('already registered') !== -1) {
                                console.warn('[Safwan Safeguard] Caught duplicate registration error:', err.message);
                                return;
                            }
                            throw err;
                        }
                    };
                }

                // Patch prototype method
                var proto = Object.getPrototypeOf(mgr);
                if (proto && proto.registerElementType && !proto._safwan_guarded) {
                    proto._safwan_guarded = true;
                    var origProto = proto.registerElementType;
                    proto.registerElementType = function(el) {
                        try {
                            var type = (el && typeof el.getType === 'function') ? el.getType() : null;
                            if (type && this.elementTypes && this.elementTypes[type]) {
                                console.warn('[Safwan Safeguard] Duplicate proto element type safely ignored:', type);
                                return;
                            }
                            return origProto.call(this, el);
                        } catch(err) {
                            if (err && err.message && err.message.indexOf('already registered') !== -1) {
                                console.warn('[Safwan Safeguard] Caught duplicate proto registration error:', err.message);
                                return;
                            }
                            throw err;
                        }
                    };
                }
            }

            // Intercept window.elementor property
            var _elem = window.elementor;
            try {
                Object.defineProperty(window, 'elementor', {
                    configurable: true,
                    enumerable: true,
                    get: function() { return _elem; },
                    set: function(val) {
                        _elem = val;
                        if (val) {
                            if (val.elementsManager) patchMgr(val.elementsManager);
                            var _em = val.elementsManager;
                            try {
                                Object.defineProperty(val, 'elementsManager', {
                                    configurable: true,
                                    enumerable: true,
                                    get: function() { return _em; },
                                    set: function(emVal) {
                                        _em = emVal;
                                        if (_em) patchMgr(_em);
                                    }
                                });
                            } catch(e) {}
                        }
                    }
                });
            } catch(e) {}

            // Interval safety net
            var interval = setInterval(function() {
                if (window.elementor && window.elementor.elementsManager) {
                    patchMgr(window.elementor.elementsManager);
                }
                var toast = document.getElementById('elementor-try-safe-mode');
                if (toast) toast.remove();
            }, 10);
            setTimeout(function() { clearInterval(interval); }, 15000);
        })();
        </script>
        <?php
    }

    /**
     * Admin head safeguard (for post.php?action=elementor)
     */
    public static function render_admin_safeguard() {
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'elementor' ) {
            self::render_editor_safeguard();
        }
    }
}

Safwan_Elementor_Compatibility::init();
