<?php
/**
 * Safwan Nature-Gro Farming - Header & Footer Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Safwan_Header_Footer {

    public function __construct() {
        // Shortcodes
        add_shortcode( 'safwan_header', array( $this, 'render_header' ) );
        add_shortcode( 'safwan_footer', array( $this, 'render_footer' ) );
        
        // WooCommerce AJAX Cart Fragments
        add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_count_fragments' ) );
    }

    /**
     * Live WooCommerce cart count fragment
     */
    public function cart_count_fragments( $fragments ) {
        if ( function_exists( 'WC' ) && WC()->cart ) {
            $count = WC()->cart->get_cart_contents_count();
            $total = WC()->cart->get_cart_total();
            
            ob_start();
            ?>
            <span id="safwan-cart-count-badge" class="safwan-cart-count"><?php echo esc_html( $count ); ?></span>
            <?php
            $fragments['#safwan-cart-count-badge'] = ob_get_clean();

            ob_start();
            ?>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" id="safwan-cart-pill-wrap" class="safwan-cart-pill" title="কার্ট দেখুন">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <span>কার্ট</span>
                <span id="safwan-cart-count-badge" class="safwan-cart-count"><?php echo esc_html( $count ); ?></span>
            </a>
            <?php
            $fragments['#safwan-cart-pill-wrap'] = ob_get_clean();
        }
        return $fragments;
    }

    /**
     * Render Topbar and Header
     */
    public function render_header() {
        $cart_count = 0;
        $cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '/cart/';
        $account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '/my-account/';

        if ( function_exists( 'WC' ) && WC()->cart ) {
            $cart_count = WC()->cart->get_cart_contents_count();
        }

        ob_start();
        ?>
        <!-- Top Bar -->
        <div class="safwan-topbar">
            <div class="safwan-container">
                <div class="safwan-topbar-inner">
                    <div class="safwan-topbar-left">
                        <span><i class="fas fa-map-marker-alt"></i> ডিবি রোড, মালীপাড়া, গাইবান্ধা সদর</span>
                        <span><i class="fas fa-phone-alt"></i> হটলাইন: <a href="tel:01717874347">01717-874347</a></span>
                    </div>
                    <div class="safwan-topbar-right">
                        <span><i class="fab fa-whatsapp"></i> সরাসরি অর্ডার: <a href="https://wa.me/8801717874347" target="_blank" rel="noopener">WhatsApp</a></span>
                        <span>নিরাপদ খাবার, পরিবারের জন্য ভরসা</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <header class="safwan-header" id="safwan-main-header">
            <div class="safwan-container">
                <div class="safwan-header-inner">
                    <!-- Brand Logo -->
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="safwan-logo">
                        <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/cropped-ChatGPT-Image-Apr-18-2026-10_26_25-PM.png' ) ); ?>" alt="Safwan Nature-Gro Farming Logo">
                        <div class="safwan-logo-text">
                            <span class="safwan-brand-name">Safwan Nature-Gro</span>
                            <span class="safwan-brand-tagline">নিরাপদ খাদ্য ও এগ্রো ফার্মিং</span>
                        </div>
                    </a>

                    <!-- Navigation Menu -->
                    <nav>
                        <ul class="safwan-nav-menu">
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="safwan-nav-link <?php echo is_front_page() ? 'active' : ''; ?>">Home</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="safwan-nav-link">Shop</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>" class="safwan-nav-link">About Us</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/our-farm/' ) ); ?>" class="safwan-nav-link">Our Farm</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/food-safety/' ) ); ?>" class="safwan-nav-link">Food Safety</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="safwan-nav-link">Contact</a></li>
                        </ul>
                    </nav>

                    <!-- Header Actions -->
                    <div class="safwan-header-actions">
                        <!-- My Account Link -->
                        <a href="<?php echo esc_url( $account_url ); ?>" class="safwan-action-btn" title="আমার অ্যাকাউন্ট" aria-label="My Account">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </a>

                        <!-- Cart Pill -->
                        <a href="<?php echo esc_url( $cart_url ); ?>" id="safwan-cart-pill-wrap" class="safwan-cart-pill" title="কার্ট দেখুন">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            <span>কার্ট</span>
                            <span id="safwan-cart-count-badge" class="safwan-cart-count"><?php echo esc_html( $cart_count ); ?></span>
                        </a>

                        <!-- Mobile Hamburger Button -->
                        <button type="button" class="safwan-mobile-toggle" id="safwan-mobile-toggle" aria-label="মেনু খুলুন">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Offcanvas Mobile Drawer -->
        <div class="safwan-drawer-overlay" id="safwan-drawer-overlay"></div>
        <div class="safwan-mobile-drawer" id="safwan-mobile-drawer">
            <div class="safwan-drawer-header">
                <span class="safwan-brand-name">Safwan Nature-Gro</span>
                <button type="button" class="safwan-drawer-close" id="safwan-drawer-close" aria-label="বন্ধ করুন">&times;</button>
            </div>
            <ul class="safwan-mobile-nav">
                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo is_front_page() ? 'active' : ''; ?>">হোম (Home)</a></li>
                <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">শপ (Shop)</a></li>
                <li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">আমাদের সম্পর্কে (About Us)</a></li>
                <li><a href="<?php echo esc_url( home_url( '/our-farm/' ) ); ?>">আমাদের খামার (Our Farm)</a></li>
                <li><a href="<?php echo esc_url( home_url( '/food-safety/' ) ); ?>">খাদ্য নিরাপত্তা (Food Safety)</a></li>
                <li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">যোগাযোগ (Contact)</a></li>
                <li><a href="<?php echo esc_url( $account_url ); ?>">আমার অ্যাকাউন্ট (My Account)</a></li>
                <li><a href="<?php echo esc_url( $cart_url ); ?>">শপিং কার্ট (Cart)</a></li>
            </ul>

            <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--safwan-border);">
                <p style="font-size: 13px; color: var(--safwan-text-muted); margin-bottom: 10px;">জরুরি প্রয়োজনে বা সরাসরি অর্ডারে:</p>
                <a href="https://wa.me/8801717874347" class="btn-safwan btn-safwan-whatsapp" style="width: 100%; margin-bottom: 10px;" target="_blank" rel="noopener">
                    WhatsApp অর্ডার (01717-874347)
                </a>
                <a href="tel:01717874347" class="btn-safwan btn-safwan-secondary" style="width: 100%;">
                    কল করুন: 01717-874347
                </a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Footer
     */
    public function render_footer() {
        ob_start();
        ?>
        <footer class="safwan-footer">
            <div class="safwan-container">
                <div class="safwan-footer-grid">
                    <!-- Brand Column -->
                    <div class="safwan-footer-brand">
                        <h4>Safwan Nature-Gro Farming</h4>
                        <p>
                            প্রকৃতি থেকে সরাসরি সংগ্রহ করা নিরাপদ, খাঁটি ও স্বাস্থ্যসম্মত খাদ্যপণ্য পৌঁছে দিচ্ছি আপনার পরিবারের কাছে। নিজস্ব খামারের তদারকি ও নির্বাচিত বিশ্বস্ত উৎপাদকদের সমন্বয়ে নিরাপদ খাদ্য নিশ্চিত করাই আমাদের লক্ষ্য।
                        </p>
                        <div style="display: flex; gap: 10px; margin-top: 14px;">
                            <a href="https://www.facebook.com/safwanagro" target="_blank" rel="noopener" class="safwan-action-btn" style="background:#1e293b; color:#ffffff; border-color:#334155;" title="Facebook">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H7v-3h3V9.5C10 6.46 11.82 5 14.5 5c1.28 0 2.62.23 2.62.23v2.88h-1.48c-1.5 0-1.97.93-1.97 1.88V12h3.25l-.52 3h-2.73v6.8c4.56-.93 8-4.96 8-9.8z"/></svg>
                            </a>
                            <a href="https://wa.me/8801717874347" target="_blank" rel="noopener" class="safwan-action-btn" style="background:#1e293b; color:#25d366; border-color:#334155;" title="WhatsApp">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links Column -->
                    <div class="safwan-footer-col">
                        <h5>প্রয়োজনীয় লিংক</h5>
                        <ul class="safwan-footer-links">
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">হোম পেজ</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">সকল পণ্য দেখুন</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">আমাদের গল্প ও মিশন</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/our-farm/' ) ); ?>">আমাদের খামার ও কার্যক্রম</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/food-safety/' ) ); ?>">খাদ্য নিরাপত্তা প্রতিশ্রুতি</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">যোগাযোগ করুন</a></li>
                        </ul>
                    </div>

                    <!-- Categories Column -->
                    <div class="safwan-footer-col">
                        <h5>জনপ্রিয় ক্যাটাগরি</h5>
                        <ul class="safwan-footer-links">
                            <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">দেশি মাছ ও নদীর মাছ</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">হাঁস ও মুরগি</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">গরু ও খাসির মাংস</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">খাঁটি মধু ও অর্গানিক ঘি</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">ঘানি ভাঙা সরিষার তেল</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Ready To Cook (RTC) পণ্য</a></li>
                        </ul>
                    </div>

                    <!-- Contact Details Column -->
                    <div class="safwan-footer-col">
                        <h5>যোগাযোগ ও ঠিকানা</h5>
                        <ul class="safwan-footer-contact" style="list-style:none; padding:0;">
                            <li>
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>ডিবি রোড, মালীপাড়া, গাইবান্ধা সদর, গাইবান্ধা</span>
                            </li>
                            <li>
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <span>ফোন: <a href="tel:01717874347" style="color:#ffffff;">01717-874347</a></span>
                            </li>
                            <li>
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/></svg>
                                <span>WhatsApp: <a href="https://wa.me/8801717874347" target="_blank" rel="noopener" style="color:#25d366;">01717-874347</a></span>
                            </li>
                            <li>
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>সকাল ৮:০০ – রাত ১০:০০ (প্রতিদিন)</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="safwan-footer-bottom">
                    <div>
                        &copy; <?php echo date( 'Y' ); ?> <strong>Safwan Nature-Gro Farming</strong>. সর্বস্বত্ব সংরক্ষিত।
                    </div>
                    <div style="display:flex; gap:16px; flex-wrap:wrap;">
                        <a href="<?php echo esc_url( home_url( '/delivery-policy/' ) ); ?>">ডেলিভারি পলিসি</a>
                        <a href="<?php echo esc_url( home_url( '/refund-policy/' ) ); ?>">রিটার্ন ও রিফান্ড</a>
                        <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">প্রাইভেসি পলিসি</a>
                        <a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>">শর্তাবলী</a>
                        <a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">সাধারণ জিজ্ঞাসা (FAQ)</a>
                    </div>
                </div>
            </div>
        </footer>
        <?php
        return ob_get_clean();
    }
}
