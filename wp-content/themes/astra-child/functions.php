<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @package Astra Child - Safwan Nature-Gro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue styles and scripts
 */
function safwan_child_enqueue_styles() {
    // Enqueue Astra Parent Style
    wp_enqueue_style( 'astra-parent-theme-css', get_template_directory_uri() . '/style.css', array(), ASTRA_THEME_VERSION );
    
    // Enqueue Google Fonts (Noto Sans Bengali & Inter)
    wp_enqueue_style( 
        'safwan-google-fonts', 
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700;800&display=swap', 
        array(), 
        null 
    );
    
    // Enqueue FontAwesome for icons
    wp_enqueue_style(
        'font-awesome-5',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        array(),
        '5.15.4'
    );
    
    // Enqueue Child Theme Style with cache-busting version
    wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-theme-css'), '1.6.1' );

    // Enqueue AJAX Mini-Cart Controller Script
    wp_enqueue_script(
        'safwan-cart-js',
        get_stylesheet_directory_uri() . '/js/safwan-cart.js',
        array( 'jquery' ),
        '1.6.1',
        true
    );
    wp_localize_script(
        'safwan-cart-js',
        'safwan_cart_params',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'safwan_cart_nonce' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'safwan_child_enqueue_styles', 15 );

/**
 * Rock-Solid Sticky Menu Bar Controller in wp_head (Zero Shaking, Zero Layout Shifts)
 */
function safwan_inline_sticky_header_script() {
    ?>
    <script>
    (function() {
        var isSticky = false;
        var triggerOffset = 0;

        function getNav() {
            return document.querySelector('.safwan-navbar-wrapper, .elementor-element-sng_navbar');
        }

        function calculateTrigger() {
            if (window.innerWidth <= 1024 || document.body.classList.contains('elementor-editor-active')) return;
            var nav = getNav();
            if (nav && !isSticky) {
                var rect = nav.getBoundingClientRect();
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                triggerOffset = rect.top + scrollTop;
            }
        }

        function onScroll() {
            if (window.innerWidth <= 1024 || document.body.classList.contains('elementor-editor-active')) {
                if (isSticky) {
                    isSticky = false;
                    document.body.classList.remove('safwan-nav-is-sticky');
                    var nav = getNav();
                    if (nav) nav.classList.remove('is-sticky');
                }
                return;
            }

            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (triggerOffset <= 0) calculateTrigger();

            if (scrollTop >= triggerOffset && triggerOffset > 0) {
                if (!isSticky) {
                    isSticky = true;
                    document.body.classList.add('safwan-nav-is-sticky');
                    var nav = getNav();
                    if (nav) nav.classList.add('is-sticky');
                }
            } else {
                if (isSticky) {
                    isSticky = false;
                    document.body.classList.remove('safwan-nav-is-sticky');
                    var nav = getNav();
                    if (nav) nav.classList.remove('is-sticky');
                }
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', function() {
            if (!isSticky) calculateTrigger();
            onScroll();
        }, { passive: true });
        document.addEventListener('DOMContentLoaded', function() {
            calculateTrigger();
            onScroll();
        });
        window.addEventListener('load', function() {
            calculateTrigger();
            onScroll();
        });
    })();
    </script>
    <?php
}
add_action( 'wp_head', 'safwan_inline_sticky_header_script', 5 );

/**
 * Add Direct Header & Menu Pages Shortcuts in WordPress Admin Sidebar
 * Allows 1-click access to edit any menu page in Elementor
 */
function safwan_add_elementor_pages_admin_menu() {
    // Main Hub Menu
    add_menu_page(
        'এলিমেন্টর পেজসমূহ (Elementor Pages)',
        'এলিমেন্টর পেজসমূহ',
        'edit_posts',
        'safwan-elementor-pages',
        'safwan_render_elementor_pages_admin',
        'dashicons-layout',
        21
    );

    // Submenus
    add_submenu_page(
        'safwan-elementor-pages',
        'হেডার এডিটর (Header)',
        '👉 হেডার এডিটর (Header)',
        'edit_posts',
        'post.php?post=32&action=elementor'
    );
    add_submenu_page(
        'safwan-elementor-pages',
        'আমাদের সম্পর্কে (About Us)',
        '👉 আমাদের সম্পর্কে (About Us)',
        'edit_posts',
        'post.php?post=228&action=elementor'
    );
    add_submenu_page(
        'safwan-elementor-pages',
        'আমাদের খামার (Our Farm)',
        '👉 আমাদের খামার (Our Farm)',
        'edit_posts',
        'post.php?post=570&action=elementor'
    );
    add_submenu_page(
        'safwan-elementor-pages',
        'খাদ্য নিরাপত্তা (Food Safety)',
        '👉 খাদ্য নিরাপত্তা (Food Safety)',
        'edit_posts',
        'post.php?post=572&action=elementor'
    );
    add_submenu_page(
        'safwan-elementor-pages',
        'যোগাযোগ (Contact Us)',
        '👉 যোগাযোগ পেজ (Contact Us)',
        'edit_posts',
        'post.php?post=239&action=elementor'
    );
    add_submenu_page(
        'safwan-elementor-pages',
        'শপ পেজ (Shop)',
        '👉 শপ পেজ (Shop Archive)',
        'edit_posts',
        'post.php?post=373&action=elementor'
    );
}
add_action( 'admin_menu', 'safwan_add_elementor_pages_admin_menu' );

/**
 * Render the Admin Hub for 1-click Elementor editing
 */
function safwan_render_elementor_pages_admin() {
    $pages = [
        ['title' => 'প্রধান হেডার (Main Header)', 'id' => 32, 'url' => home_url('/'), 'icon' => 'dashicons-editor-kitchensink', 'desc' => 'ওয়েবসাইটের সম্পূর্ণ টপবার, মেইন হেডার, লোগো, সার্চ ও নেভিগেশন মেনু।'],
        ['title' => 'আমাদের সম্পর্কে (About Us)', 'id' => 228, 'url' => home_url('/about-us/'), 'icon' => 'dashicons-groups', 'desc' => 'প্রতিষ্ঠানের গল্প, মিশন, ভিশন, অর্জন ও সফলতার পরিসংখ্যান।'],
        ['title' => 'আমাদের খামার (Our Farm)', 'id' => 570, 'url' => home_url('/our-farm/'), 'icon' => 'dashicons-palmtree', 'desc' => 'মাছ চাষ, দেশি হাঁস-মুরগি, গাভী ও দুগ্ধ খামার এবং পরিবেশবান্ধব চাষাবাদ।'],
        ['title' => 'খাদ্য নিরাপত্তা (Food Safety)', 'id' => 572, 'url' => home_url('/food-safety/'), 'icon' => 'dashicons-shield', 'desc' => 'নিরাপদ খাদ্যের ৬টি আপসহীন স্তম্ভ, কোল্ড-চেইন প্যাকেজিং ও সাধারণ প্রশ্নোত্তর।'],
        ['title' => 'যোগাযোগ (Contact Us)', 'id' => 239, 'url' => home_url('/contact-us/'), 'icon' => 'dashicons-phone', 'desc' => 'হটলাইন, হোয়াটসঅ্যাপ, কাস্টমার সার্ভিস ও সরাসরি বার্তা পাঠানোর ফর্ম।'],
        ['title' => 'শপ পেজ (Shop Archive)', 'id' => 373, 'url' => home_url('/shop/'), 'icon' => 'dashicons-cart', 'desc' => 'সকল পণ্যের তালিকা, ক্যাটাগরি ফিল্টার ও প্রোডাক্ট গ্রিড লেআউট।'],
    ];
    ?>
    <div class="wrap" style="max-width: 1100px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <h1 style="font-size: 26px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">🌿 সাফওয়ান নেচার-গ্রো — এলিমেন্টর পেজ ম্যানেজার</h1>
        <p style="font-size: 15px; color: #64748b; margin-bottom: 25px;">মেনুর সকল পেজ এবং হেডার এলিমেন্টর দিয়ে ডিজাইন করা রয়েছে। নিচে যেকোনো পেজের "Edit with Elementor" বাটনে ক্লিক করে সরাসরি এডিট করতে পারবেন:</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
            <?php foreach ($pages as $p) : ?>
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <span class="dashicons <?php echo esc_attr($p['icon']); ?>" style="font-size: 24px; width: 24px; height: 24px; color: #b91c1c;"></span>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;"><?php echo esc_html($p['title']); ?></h3>
                    </div>
                    <p style="margin: 0 0 16px; font-size: 14px; color: #64748b; line-height: 1.6;"><?php echo esc_html($p['desc']); ?></p>
                </div>
                <div style="display: flex; gap: 10px; align-items: center; pt: 10px; border-top: 1px solid #f1f5f9; padding-top: 14px;">
                    <a href="<?php echo esc_url(admin_url('post.php?post=' . $p['id'] . '&action=elementor')); ?>" class="button button-primary" style="background: #b91c1c; border-color: #b91c1c; font-weight: 600; padding: 4px 16px; height: auto; border-radius: 6px;">
                        <span class="dashicons dashicons-edit" style="font-size: 16px; vertical-align: middle; margin-top: -2px;"></span> Edit with Elementor
                    </a>
                    <a href="<?php echo esc_url($p['url']); ?>" target="_blank" class="button" style="border-radius: 6px; padding: 4px 12px; height: auto;">
                        <span class="dashicons dashicons-external" style="font-size: 16px; vertical-align: middle; margin-top: -2px;"></span> View Page
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Add Top Admin Bar Shortcut to edit header or current page in Elementor
 */
function safwan_add_header_editor_admin_bar( $admin_bar ) {
    if ( current_user_can( 'edit_posts' ) ) {
        $admin_bar->add_node( array(
            'id'    => 'safwan_edit_header_elementor',
            'title' => '<span class="ab-icon dashicons dashicons-editor-kitchensink"></span> হেডার এডিট (Elementor)',
            'href'  => admin_url( 'post.php?post=32&action=elementor' ),
            'meta'  => array( 'target' => '_blank', 'title' => 'Edit Header with Elementor' )
        ) );
    }
}
add_action( 'admin_bar_menu', 'safwan_add_header_editor_admin_bar', 999 );

/**
 * Render Header Cart Dropdown & Button
 */
function safwan_render_cart_button_html( $is_mobile = false ) {
    $cart_count   = ( function_exists('WC') && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
    $cart_total   = ( function_exists('WC') && WC()->cart ) ? WC()->cart->get_cart_total() : '০.০০ ৳';
    $cart_url     = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
    $checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/checkout/');
    $shop_url     = home_url('/shop/');

    if ( $is_mobile ) {
        return '<a href="' . esc_url( $cart_url ) . '" class="safwan-mobile-cart-btn" title="শপিং কার্ট">'
            . '<span class="safwan-cart-icon-wrap">'
            . '<svg class="safwan-cart-svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>'
            . '<span class="safwan-cart-count">' . esc_html( $cart_count ) . '</span>'
            . '</span>'
            . '</a>';
    }

    $cart_items = ( function_exists('WC') && WC()->cart ) ? WC()->cart->get_cart() : array();

    ob_start();
    ?>
    <div class="safwan-cart-dropdown-wrapper">
        <a href="<?php echo esc_url( $cart_url ); ?>" class="safwan-header-cart-btn" title="শপিং কার্ট দেখুন">
            <span class="safwan-cart-icon-wrap">
                <svg class="safwan-cart-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <span class="safwan-cart-count"><?php echo esc_html( $cart_count ); ?></span>
            </span>
            <span class="safwan-cart-total"><?php echo $cart_total; ?></span>
        </a>

        <div class="safwan-mini-cart-dropdown">
            <div class="safwan-mini-cart-header">
                <span class="safwan-mini-cart-title">🛒 আপনার শপিং কার্ট</span>
                <span class="safwan-mini-cart-badge"><?php echo esc_html( $cart_count ); ?>টি পণ্য</span>
            </div>

            <?php if ( ! empty( $cart_items ) ) : ?>
                <div class="safwan-mini-cart-items-list">
                    <?php foreach ( $cart_items as $cart_item_key => $cart_item ) :
                        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                            $product_name  = $_product->get_name();
                            $thumbnail     = $_product->get_image( array( 48, 48 ) );
                            $product_price = WC()->cart->get_product_price( $_product );
                            $product_url   = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                    ?>
                        <div class="safwan-mini-cart-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <div class="safwan-mini-cart-thumb">
                                <?php if ( $product_url ) : ?>
                                    <a href="<?php echo esc_url( $product_url ); ?>"><?php echo $thumbnail; ?></a>
                                <?php else : ?>
                                    <?php echo $thumbnail; ?>
                                <?php endif; ?>
                            </div>
                            <div class="safwan-mini-cart-info">
                                <h5 class="safwan-mini-cart-item-title">
                                    <?php if ( $product_url ) : ?>
                                        <a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a>
                                    <?php else : ?>
                                        <?php echo esc_html( $product_name ); ?>
                                    <?php endif; ?>
                                </h5>
                                <div class="safwan-mini-cart-item-meta">
                                    <span class="safwan-item-qty"><?php echo esc_html( $cart_item['quantity'] ); ?></span> × <span class="safwan-item-price"><?php echo $product_price; ?></span>
                                </div>
                            </div>
                            <button type="button" class="safwan-cart-remove-btn" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" title="পণ্যটি কার্ট থেকে মুছুন" aria-label="Remove item">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    <?php endif; endforeach; ?>
                </div>

                <div class="safwan-mini-cart-footer">
                    <div class="safwan-mini-cart-subtotal-row">
                        <span class="safwan-subtotal-label">মোট প্রদেয়:</span>
                        <span class="safwan-subtotal-val"><?php echo $cart_total; ?></span>
                    </div>
                    <div class="safwan-mini-cart-actions">
                        <a href="<?php echo esc_url( $cart_url ); ?>" class="safwan-mini-cart-view-btn">কার্ট দেখুন</a>
                        <a href="<?php echo esc_url( $checkout_url ); ?>" class="safwan-mini-cart-checkout-btn">চেকআউট করুন</a>
                    </div>
                </div>
            <?php else : ?>
                <div class="safwan-mini-cart-empty">
                    <div class="safwan-empty-bag-icon">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    </div>
                    <p class="safwan-empty-msg">আপনার কার্ট বর্তমানে খালি আছে!</p>
                    <a href="<?php echo esc_url( $shop_url ); ?>" class="safwan-empty-go-shop">শপিং শুরু করুন</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Mobile Cart Drawer
 */
function safwan_render_mobile_cart_drawer_html() {
    $cart_count   = ( function_exists('WC') && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
    $cart_total   = ( function_exists('WC') && WC()->cart ) ? WC()->cart->get_cart_total() : '০.০০ ৳';
    $cart_url     = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/');
    $checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/checkout/');
    $shop_url     = home_url('/shop/');
    $cart_items   = ( function_exists('WC') && WC()->cart ) ? WC()->cart->get_cart() : array();

    ob_start();
    ?>
    <div class="safwan-mobile-cart-drawer">
        <div class="safwan-mobile-drawer-header">
            <div class="safwan-drawer-title-wrap">
                <span class="safwan-drawer-title">🛒 আপনার শপিং কার্ট</span>
                <span class="safwan-drawer-badge"><?php echo esc_html( $cart_count ); ?>টি পণ্য</span>
            </div>
            <button type="button" class="safwan-mobile-cart-close" aria-label="Close cart">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div class="safwan-drawer-body">
            <?php if ( ! empty( $cart_items ) ) : ?>
                <div class="safwan-mini-cart-items-list">
                    <?php foreach ( $cart_items as $cart_item_key => $cart_item ) :
                        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                            $product_name  = $_product->get_name();
                            $thumbnail     = $_product->get_image( array( 48, 48 ) );
                            $product_price = WC()->cart->get_product_price( $_product );
                            $product_url   = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                    ?>
                        <div class="safwan-mini-cart-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <div class="safwan-mini-cart-thumb">
                                <?php if ( $product_url ) : ?>
                                    <a href="<?php echo esc_url( $product_url ); ?>"><?php echo $thumbnail; ?></a>
                                <?php else : ?>
                                    <?php echo $thumbnail; ?>
                                <?php endif; ?>
                            </div>
                            <div class="safwan-mini-cart-info">
                                <h5 class="safwan-mini-cart-item-title">
                                    <?php if ( $product_url ) : ?>
                                        <a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a>
                                    <?php else : ?>
                                        <?php echo esc_html( $product_name ); ?>
                                    <?php endif; ?>
                                </h5>
                                <div class="safwan-mini-cart-item-meta">
                                    <span class="safwan-item-qty"><?php echo esc_html( $cart_item['quantity'] ); ?></span> × <span class="safwan-item-price"><?php echo $product_price; ?></span>
                                </div>
                            </div>
                            <button type="button" class="safwan-cart-remove-btn" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" title="পণ্যটি কার্ট থেকে মুছুন" aria-label="Remove item">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    <?php endif; endforeach; ?>
                </div>

                <div class="safwan-drawer-footer">
                    <div class="safwan-mini-cart-subtotal-row">
                        <span class="safwan-subtotal-label">মোট প্রদেয়:</span>
                        <span class="safwan-subtotal-val"><?php echo $cart_total; ?></span>
                    </div>
                    <div class="safwan-mini-cart-actions">
                        <a href="<?php echo esc_url( $cart_url ); ?>" class="safwan-mini-cart-view-btn">কার্ট দেখুন</a>
                        <a href="<?php echo esc_url( $checkout_url ); ?>" class="safwan-mini-cart-checkout-btn">চেকআউট করুন</a>
                    </div>
                </div>
            <?php else : ?>
                <div class="safwan-mini-cart-empty">
                    <div class="safwan-empty-bag-icon">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    </div>
                    <p class="safwan-empty-msg">আপনার কার্ট বর্তমানে খালি আছে!</p>
                    <a href="<?php echo esc_url( $shop_url ); ?>" class="safwan-empty-go-shop">শপিং শুরু করুন</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Output Mobile Drawer & Backdrop in Footer
 */
function safwan_render_mobile_cart_drawer_in_footer() {
    echo safwan_render_mobile_cart_drawer_html();
    echo '<div class="safwan-cart-backdrop"></div>';
}
add_action( 'wp_footer', 'safwan_render_mobile_cart_drawer_in_footer' );

function safwan_cart_button_shortcode() {
    return safwan_render_cart_button_html( false );
}
add_shortcode( 'safwan_cart_button', 'safwan_cart_button_shortcode' );

function safwan_cart_button_mobile_shortcode() {
    return safwan_render_cart_button_html( true );
}
add_shortcode( 'safwan_cart_button_mobile', 'safwan_cart_button_mobile_shortcode' );

/**
 * Update Cart Button & Dropdown via WooCommerce AJAX
 */
function safwan_add_cart_fragments( $fragments ) {
    $fragments['div.safwan-cart-dropdown-wrapper'] = safwan_render_cart_button_html( false );
    $fragments['a.safwan-mobile-cart-btn']         = safwan_render_cart_button_html( true );
    $fragments['div.safwan-mobile-cart-drawer']    = safwan_render_mobile_cart_drawer_html();
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'safwan_add_cart_fragments' );

/**
 * AJAX Remove from Cart Handler (Zero Page Reload)
 */
function safwan_ajax_remove_from_cart() {
    check_ajax_referer( 'safwan_cart_nonce', 'nonce' );
    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] ?? '' );
    
    $removed = false;
    if ( $cart_item_key && function_exists('WC') && WC()->cart ) {
        $removed = WC()->cart->remove_cart_item( $cart_item_key );
        WC()->cart->calculate_totals();
    }
    
    $fragments = apply_filters( 'woocommerce_add_to_cart_fragments', array() );
    
    wp_send_json_success( array(
        'removed'   => $removed,
        'fragments' => $fragments,
        'cart_hash' => WC()->cart ? WC()->cart->get_cart_hash() : '',
        'count'     => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
        'total'     => WC()->cart ? WC()->cart->get_cart_total() : '০.০০ ৳',
    ) );
}
add_action( 'wp_ajax_safwan_remove_from_cart', 'safwan_ajax_remove_from_cart' );
add_action( 'wp_ajax_nopriv_safwan_remove_from_cart', 'safwan_ajax_remove_from_cart' );


