/**
 * Safwan Nature-Gro - AJAX Cart & Dropdown Controller
 * Handles real-time item removal without page reload, dropdown animations, and mobile drawer.
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        // 1. AJAX Remove item from cart without page reload
        $(document).on('click', '.safwan-cart-remove-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            var cart_item_key = $btn.data('cart-item-key');
            if (!cart_item_key) return;

            var $itemRow = $btn.closest('.safwan-mini-cart-item');
            $itemRow.addClass('is-removing');

            // Check if wrapper was hovered or active
            var $wrapper = $('.safwan-cart-dropdown-wrapper');
            var keepOpen = $wrapper.hasClass('is-active') || $wrapper.is(':hover');
            var isMobileDrawerOpen = $('.safwan-mobile-cart-drawer').hasClass('is-open');

            $.ajax({
                type: 'POST',
                url: safwan_cart_params.ajax_url,
                data: {
                    action: 'safwan_remove_from_cart',
                    cart_item_key: cart_item_key,
                    nonce: safwan_cart_params.nonce
                },
                success: function(res) {
                    if (res && res.success && res.data && res.data.fragments) {
                        var fragments = res.data.fragments;

                        // Replace all cart fragments
                        $.each(fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });

                        // Preserve open state if was open
                        if (keepOpen) {
                            $('.safwan-cart-dropdown-wrapper').addClass('is-active');
                        }
                        if (isMobileDrawerOpen) {
                            $('.safwan-mobile-cart-drawer').addClass('is-open');
                            $('.safwan-cart-backdrop').addClass('is-open');
                        }

                        // Trigger WooCommerce standard events
                        $(document.body).trigger('wc_fragments_refreshed');
                        $(document.body).trigger('removed_from_cart', [fragments, res.data.cart_hash, $btn]);
                    } else {
                        $itemRow.removeClass('is-removing');
                    }
                },
                error: function() {
                    $itemRow.removeClass('is-removing');
                }
            });
        });

        // 2. Desktop Dropdown Click Toggle & Outside Click
        $(document).on('click', '.safwan-header-cart-btn', function(e) {
            // If viewport is desktop width
            if (window.innerWidth >= 768) {
                var $wrapper = $(this).closest('.safwan-cart-dropdown-wrapper');
                $wrapper.toggleClass('is-active');
                e.preventDefault();
            }
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.safwan-cart-dropdown-wrapper').length) {
                $('.safwan-cart-dropdown-wrapper').removeClass('is-active');
            }
        });

        // 3. Mobile Cart Drawer Toggle
        $(document).on('click', '.safwan-mobile-cart-btn', function(e) {
            e.preventDefault();
            $('.safwan-mobile-cart-drawer').addClass('is-open');
            $('.safwan-cart-backdrop').addClass('is-open');
            $('body').addClass('safwan-cart-drawer-active');
        });

        $(document).on('click', '.safwan-mobile-cart-close, .safwan-cart-backdrop', function(e) {
            e.preventDefault();
            $('.safwan-mobile-cart-drawer').removeClass('is-open');
            $('.safwan-cart-backdrop').removeClass('is-open');
            $('body').removeClass('safwan-cart-drawer-active');
        });

        // 4. Sticky Menu Bar Controller (Hide Topbar & Main Header, Keep Navbar Sticky)
        function initStickyNavbar() {
            var $masthead = $('#masthead');
            if (!$masthead.length) $masthead = $('.elementor-32');

            var $topbar = $('.safwan-topbar, .elementor-element-sng_topbar');
            var $mainHeader = $('.safwan-main-header, .elementor-element-sng_main_header');

            function updateStickyOffsets() {
                if (window.innerWidth > 921) {
                    var hideHeight = 0;
                    if ($topbar.length && $topbar.is(':visible')) {
                        hideHeight += $topbar.outerHeight() || 0;
                    }
                    if ($mainHeader.length && $mainHeader.is(':visible')) {
                        hideHeight += $mainHeader.outerHeight() || 0;
                    }

                    if (hideHeight > 0) {
                        document.documentElement.style.setProperty('--safwan-header-hide-height', hideHeight + 'px');
                    }
                } else {
                    document.documentElement.style.setProperty('--safwan-header-hide-height', '0px');
                }
            }

            function handleScroll() {
                if (window.innerWidth <= 921) return;
                var currentScroll = window.pageYOffset || document.documentElement.scrollTop;

                if (currentScroll > 50) {
                    $masthead.addClass('safwan-scrolled safwan-nav-is-sticky');
                    $('body').addClass('safwan-scrolled safwan-sticky-active');
                } else {
                    $masthead.removeClass('safwan-scrolled safwan-nav-is-sticky');
                    $('body').removeClass('safwan-scrolled safwan-sticky-active');
                }
            }

            updateStickyOffsets();
            handleScroll();

            $(window).on('scroll', handleScroll);
            $(window).on('resize orientationchange', function() {
                updateStickyOffsets();
                handleScroll();
            });

            // Re-check after images/fonts finish loading
            setTimeout(updateStickyOffsets, 300);
            setTimeout(updateStickyOffsets, 1000);
        }

        initStickyNavbar();

    });

})(jQuery);
