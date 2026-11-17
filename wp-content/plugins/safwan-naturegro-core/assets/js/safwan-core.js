/**
 * Safwan Nature-Gro Farming - Core Scripts
 * Handles Header, Navigation, Hero Slider, Cart Count Refresh, and Mobile Interactions
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // 1. Mobile Menu Drawer Controls
        var $drawer = $('#safwan-mobile-drawer');
        var $overlay = $('#safwan-drawer-overlay');

        function openDrawer() {
            $drawer.addClass('open');
            $overlay.addClass('active');
            $('body').css('overflow', 'hidden');
        }

        function closeDrawer() {
            $drawer.removeClass('open');
            $overlay.removeClass('active');
            $('body').css('overflow', '');
        }

        $('#safwan-mobile-toggle').on('click', function(e) {
            e.preventDefault();
            openDrawer();
        });

        $('#safwan-drawer-close, #safwan-drawer-overlay').on('click', function(e) {
            e.preventDefault();
            closeDrawer();
        });

        $(document).on('keyup', function(e) {
            if (e.key === 'Escape' && $drawer.hasClass('open')) {
                closeDrawer();
            }
        });

        // 2. Hero Carousel Slider Controls
        var $carousel = $('#safwan-hero-carousel');
        if ($carousel.length) {
            var $track = $carousel.find('.safwan-slides-track');
            var $slides = $carousel.find('.safwan-slide-item');
            var $dots = $carousel.find('.safwan-dot');
            var totalSlides = $slides.length;
            var currentSlide = 0;
            var slideInterval = null;

            function goToSlide(index) {
                if (index < 0) {
                    index = totalSlides - 1;
                } else if (index >= totalSlides) {
                    index = 0;
                }
                currentSlide = index;
                $track.css('transform', 'translateX(-' + (currentSlide * 100) + '%)');
                $dots.removeClass('active').eq(currentSlide).addClass('active');
            }

            function nextSlide() {
                goToSlide(currentSlide + 1);
            }

            function prevSlide() {
                goToSlide(currentSlide - 1);
            }

            function startAutoPlay() {
                stopAutoPlay();
                slideInterval = setInterval(nextSlide, 4500);
            }

            function stopAutoPlay() {
                if (slideInterval) {
                    clearInterval(slideInterval);
                    slideInterval = null;
                }
            }

            $('#safwan-slide-next').on('click', function(e) {
                e.preventDefault();
                nextSlide();
                startAutoPlay();
            });

            $('#safwan-slide-prev').on('click', function(e) {
                e.preventDefault();
                prevSlide();
                startAutoPlay();
            });

            $dots.on('click', function() {
                var idx = parseInt($(this).data('index'), 10);
                goToSlide(idx);
                startAutoPlay();
            });

            $carousel.on('mouseenter', stopAutoPlay).on('mouseleave', startAutoPlay);

            // Touch swipe support for mobile
            var touchStartX = 0;
            var touchEndX = 0;

            $carousel.on('touchstart', function(e) {
                touchStartX = e.originalEvent.changedTouches[0].screenX;
            });

            $carousel.on('touchend', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 50) {
                    nextSlide();
                } else if (touchEndX - touchStartX > 50) {
                    prevSlide();
                }
                startAutoPlay();
            });

            startAutoPlay();
        }

        // 3. Refresh cart fragment on WooCommerce Ajax Add to Cart
        $(document.body).on('added_to_cart removed_from_cart', function(event, fragments, cart_hash) {
            if (typeof wc_cart_fragments_params !== 'undefined') {
                $.ajax({
                    url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
                    type: 'POST',
                    success: function(data) {
                        if (data && data.fragments) {
                            $.each(data.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                    }
                });
            }
        });

        // 4. Buy Now direct checkout click handler
        $(document).on('click', '.safwan-buy-now-btn', function(e) {
            var $this = $(this);
            var productId = $this.data('product-id');
            var qty = $this.data('quantity') || 1;

            if (productId) {
                e.preventDefault();
                $this.addClass('loading').css('opacity', '0.7');
                
                $.ajax({
                    type: 'POST',
                    url: (typeof safwan_vars !== 'undefined') ? safwan_vars.ajax_url : '/wp-admin/admin-ajax.php',
                    data: {
                        action: 'safwan_buy_now',
                        product_id: productId,
                        quantity: qty,
                        nonce: (typeof safwan_vars !== 'undefined') ? safwan_vars.nonce : ''
                    },
                    success: function(response) {
                        if (response && response.success && response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        } else {
                            window.location.href = '/checkout/?add-to-cart=' + productId;
                        }
                    },
                    error: function() {
                        window.location.href = '/checkout/?add-to-cart=' + productId;
                    }
                });
            }
        });
    });

})(jQuery);
