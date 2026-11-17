<?php
/**
 * Safwan Nature-Gro Farming - Complete 10-Section Homepage Builder
 * Incorporates the original 2-Column Banner Slider Hero
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Safwan_Homepage_Sections {

    public function __construct() {
        add_shortcode( 'safwan_homepage', array( $this, 'render_homepage' ) );
    }

    /**
     * Render the complete 10-Section Homepage
     */
    public function render_homepage() {
        ob_start();
        ?>
        <div class="safwan-scope">
            <!-- SECTION 1: ORIGINAL 2-COLUMN HERO BANNER -->
            <?php $this->render_hero_banner(); ?>

            <!-- SECTION 2: PRODUCT CATEGORIES -->
            <?php $this->render_categories_section(); ?>

            <!-- SECTION 3: FEATURED PRODUCTS -->
            <?php $this->render_featured_products(); ?>

            <!-- SECTION 4: WHY CHOOSE US -->
            <?php $this->render_why_choose_us(); ?>

            <!-- SECTION 5: OUR FARM & ACTIVITIES -->
            <?php $this->render_farm_activities(); ?>

            <!-- SECTION 6: FOOD SAFETY COMMITMENT -->
            <?php $this->render_food_safety(); ?>

            <!-- SECTION 7: CUSTOMER REVIEWS -->
            <?php $this->render_customer_reviews(); ?>

            <!-- SECTION 8: OFFERS & PACKAGES -->
            <?php $this->render_offers_packages(); ?>

            <!-- SECTION 9: NEWSLETTER / CONTACT CTA -->
            <?php $this->render_newsletter_cta(); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Section 1: Original 2-Column Hero Banner (Carousel + Side Banner)
     */
    private function render_hero_banner() {
        $shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '/shop/';
        $slide1 = home_url( '/wp-content/uploads/2026/04/26q3s1771837303.jpeg' );
        $slide2 = home_url( '/wp-content/uploads/2026/04/o1uH11775363016-light.jpg' );
        $slide3 = home_url( '/wp-content/uploads/2026/04/sCUkg1774768074-dark.png' );
        $side_banner = home_url( '/wp-content/uploads/2026/04/9weyd1775362946.jpeg' );
        ?>
        <section class="safwan-original-hero">
            <div class="safwan-container">
                <!-- 2-Column Banner Row (Original Layout) -->
                <div class="safwan-hero-banner-row">
                    <!-- Left: Carousel / Slider (67% Width) -->
                    <div class="safwan-slider-container" id="safwan-hero-carousel">
                        <div class="safwan-slides-track">
                            <div class="safwan-slide-item active">
                                <a href="<?php echo esc_url( $shop_url ); ?>">
                                    <img src="<?php echo esc_url( $slide1 ); ?>" alt="Safwan Nature-Gro Farm Banner 1">
                                </a>
                            </div>
                            <div class="safwan-slide-item">
                                <a href="<?php echo esc_url( $shop_url ); ?>">
                                    <img src="<?php echo esc_url( $slide2 ); ?>" alt="Safwan Nature-Gro Farm Banner 2">
                                </a>
                            </div>
                            <div class="safwan-slide-item">
                                <a href="<?php echo esc_url( $shop_url ); ?>">
                                    <img src="<?php echo esc_url( $slide3 ); ?>" alt="Safwan Nature-Gro Farm Banner 3">
                                </a>
                            </div>
                        </div>

                        <!-- Slider Arrows -->
                        <button type="button" class="safwan-slider-arrow safwan-prev" id="safwan-slide-prev" aria-label="Previous Slide">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <button type="button" class="safwan-slider-arrow safwan-next" id="safwan-slide-next" aria-label="Next Slide">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>

                        <!-- Slider Dots -->
                        <div class="safwan-slider-dots">
                            <span class="safwan-dot active" data-index="0"></span>
                            <span class="safwan-dot" data-index="1"></span>
                            <span class="safwan-dot" data-index="2"></span>
                        </div>
                    </div>

                    <!-- Right: Static Promo Banner (33% Width) -->
                    <div class="safwan-side-banner">
                        <a href="<?php echo esc_url( $shop_url ); ?>">
                            <img src="<?php echo esc_url( $side_banner ); ?>" alt="Safwan Nature-Gro Special Offer">
                        </a>
                    </div>
                </div>

                <!-- Brand Headline & Quick CTA Bar -->
                <div class="safwan-hero-callout-bar">
                    <div class="safwan-callout-text">
                        <h1 class="safwan-callout-headline">প্রকৃতি থেকে আপনার পরিবারের জন্য নিরাপদ খাদ্যপণ্য।</h1>
                        <p class="safwan-callout-sub">নিজস্ব খামার ও নির্বাচিত বিশ্বস্ত উৎপাদকদের কাছ থেকে সংগৃহীত মানসম্মত খাদ্যপণ্য পৌঁছে দিচ্ছি আপনার পরিবারের কাছে।</p>
                    </div>
                    <div class="safwan-callout-actions">
                        <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-safwan btn-safwan-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            <span>পণ্য দেখুন</span>
                        </a>
                        <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-safwan btn-safwan-secondary">
                            <span>Shop Now</span>
                        </a>
                        <a href="https://wa.me/8801717874347?text=<?php echo urlencode('আসসালামু আলাইকুম, আমি সাফওয়ান এগ্রো থেকে পণ্য অর্ডার করতে চাই।'); ?>" target="_blank" rel="noopener" class="btn-safwan btn-safwan-whatsapp">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/></svg>
                            <span>WhatsApp অর্ডার</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Section 2: Product Categories (10 Categories)
     */
    private function render_categories_section() {
        $categories = array(
            array( 'name' => 'দেশি মাছ ও নদীর মাছ', 'slug' => 'fish', 'img' => home_url('/wp-content/uploads/2026/04/download.jpg') ),
            array( 'name' => 'হাঁস ও মুরগি', 'slug' => 'poultry', 'img' => home_url('/wp-content/uploads/2026/04/download-1.jpg') ),
            array( 'name' => 'গরু ও খাসির মাংস', 'slug' => 'meat', 'img' => home_url('/wp-content/uploads/2026/04/images.jpg') ),
            array( 'name' => 'দুধ ও দুগ্ধজাত পণ্য', 'slug' => 'dairy', 'img' => home_url('/wp-content/uploads/2026/04/6ctmE1775107173.jpg') ),
            array( 'name' => 'ঘি', 'slug' => 'ghee', 'img' => home_url('/wp-content/uploads/2026/04/OY4JB1768121526.jpg') ),
            array( 'name' => 'মধু', 'slug' => 'honey', 'img' => home_url('/wp-content/uploads/2026/04/download-2.jpg') ),
            array( 'name' => 'সরিষার তেল', 'slug' => 'mustard-oil', 'img' => home_url('/wp-content/uploads/2026/04/download-3.jpg') ),
            array( 'name' => 'আচার ও অন্যান্য খাদ্যপণ্য', 'slug' => 'pickles', 'img' => home_url('/wp-content/uploads/2026/04/jt8PG1773222875.jpeg') ),
            array( 'name' => 'চাল, ডাল ও শস্যজাত পণ্য', 'slug' => 'grains', 'img' => home_url('/wp-content/uploads/2026/04/tFQxp1767439453.jpg') ),
            array( 'name' => 'Ready To Cook (RTC)', 'slug' => 'rtc', 'img' => home_url('/wp-content/uploads/2026/04/lcmXO1767438477.jpg') ),
        );
        ?>
        <section class="safwan-categories-section">
            <div class="safwan-container">
                <div class="safwan-section-header">
                    <span class="safwan-section-tag">ক্যাটাগরি সমূহ</span>
                    <h2 class="safwan-section-title">আমাদের খাদ্যপণ্যের বিভাগ</h2>
                    <p class="safwan-section-subtitle">আপনার পরিবারের প্রাত্যহিক পুষ্টি ও নিরাপদ খাবারের নিশ্চয়তায় আমাদের প্রধান ক্যাটাগরিগুলো থেকে বেছে নিন।</p>
                </div>

                <div class="safwan-cat-grid">
                    <?php foreach ( $categories as $cat ) : ?>
                        <a href="<?php echo esc_url( home_url( '/shop/?category=' . $cat['slug'] ) ); ?>" class="safwan-cat-card">
                            <div class="safwan-cat-icon-wrapper">
                                <img src="<?php echo esc_url( $cat['img'] ); ?>" alt="<?php echo esc_attr( $cat['name'] ); ?>" loading="lazy">
                            </div>
                            <h3 class="safwan-cat-name"><?php echo esc_html( $cat['name'] ); ?></h3>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Section 3: Featured Products
     */
    private function render_featured_products() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        $query = new WP_Query( $args );
        ?>
        <section class="safwan-featured-section">
            <div class="safwan-container">
                <div class="safwan-section-header">
                    <span class="safwan-section-tag">জনপ্রিয় পণ্য</span>
                    <h2 class="safwan-section-title">খামার থেকে নির্বাচিত পণ্য</h2>
                    <p class="safwan-section-subtitle">আমাদের নিজস্ব খামার ও নির্বাচিত খামারিদের কাছ থেকে সংগৃহীত সেরা মানের ফ্রেশ খাদ্যপণ্য।</p>
                </div>

                <div class="safwan-products-grid">
                    <?php
                    if ( $query->have_posts() ) :
                        while ( $query->have_posts() ) :
                            $query->the_post();
                            global $product;
                            if ( ! is_a( $product, 'WC_Product' ) ) {
                                continue;
                            }

                            $product_id = get_the_ID();
                            $title = get_the_title();
                            if ( strtolower( trim( $title ) ) === 'dray fish' ) {
                                $title = 'Dry Fish (শুটকি মাছ)';
                            }

                            $permalink = get_permalink();
                            $image_id = $product->get_image_id();
                            $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : wc_placeholder_img_src();
                            $regular_price = $product->get_regular_price();
                            $sale_price = $product->get_sale_price();
                            $current_price = $product->get_price();
                            $is_in_stock = $product->is_in_stock();

                            $wa_message = urlencode( "আসসালামু আলাইকুম, আমি '{$title}' পণ্যটি অর্ডার করতে চাই।\nদাম: ৳{$current_price}\nলিংক: {$permalink}" );
                            $wa_url = "https://wa.me/8801717874347?text={$wa_message}";
                            ?>
                            <div class="safwan-product-card">
                                <div class="safwan-product-thumb">
                                    <span class="safwan-badge-tag">নিরাপদ পণ্য</span>
                                    <span class="safwan-stock-tag"><?php echo $is_in_stock ? 'মজুদ আছে' : 'আউট অব স্টক'; ?></span>
                                    <a href="<?php echo esc_url( $permalink ); ?>">
                                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
                                    </a>
                                </div>
                                <div class="safwan-product-details">
                                    <div class="safwan-product-cat">Safwan Nature-Gro</div>
                                    <h3 class="safwan-product-title">
                                        <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
                                    </h3>

                                    <div class="safwan-price-wrapper">
                                        <span class="safwan-current-price">৳<?php echo esc_html( $current_price ); ?></span>
                                        <?php if ( ! empty( $sale_price ) && $regular_price > $sale_price ) : ?>
                                            <span class="safwan-old-price">৳<?php echo esc_html( $regular_price ); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="safwan-card-actions">
                                        <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" class="btn-safwan btn-safwan-secondary btn-safwan-sm add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product_id ); ?>" rel="nofollow">
                                            কার্টে যোগ
                                        </a>
                                        <button type="button" class="btn-safwan btn-safwan-primary btn-safwan-sm safwan-buy-now-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                                            Buy Now
                                        </button>
                                    </div>

                                    <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener" class="btn-safwan btn-safwan-whatsapp btn-safwan-sm safwan-whatsapp-direct">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/></svg>
                                        <span>WhatsApp-এ অর্ডার</span>
                                    </a>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>

                <div style="text-align: center; margin-top: 36px;">
                    <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn-safwan btn-safwan-secondary">
                        <span>সবগুলো পণ্য ব্রাউজ করুন</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Section 4: Why Choose Us
     */
    private function render_why_choose_us() {
        $features = array(
            array(
                'title' => 'বিশ্বস্ত খাদ্য উৎস (Trusted Sources)',
                'desc'  => 'আমাদের পণ্যগুলো নিজস্ব তদারকিকৃত খামার ও নির্বাচিত বিশ্বস্ত স্থানীয় উৎপাদকদের থেকে সংগৃহীত। প্রতিটি উৎসের নিয়মিত নিরীক্ষা করা হয়।',
                'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>'
            ),
            array(
                'title' => 'নিজস্ব খামার কার্যক্রম (Own Farm Activities)',
                'desc'  => 'গাইবান্ধায় অবস্থিত আমাদের নিজস্ব মৎস্য খামার ও পোল্ট্রি ইউনিটে উন্নত ও প্রাকৃতিক পদ্ধতিতে পুষ্টিকর খাবার খাইয়ে মাছ ও হাঁস-মুরগি পালন করা হয়।',
                'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'
            ),
            array(
                'title' => 'নির্বাচিত উৎপাদক (Selected Producers)',
                'desc'  => 'প্রান্তিক চাষী ও খামারিদের সাথে সরাসরি কাজ করে খাঁটি মধু, ঘানি ভাঙা সরিষার তেল ও দুগ্ধজাত খাদ্য সরাসরি গ্রাহকের নিকট পৌঁছে দিচ্ছি।',
                'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'
            ),
            array(
                'title' => 'কঠোর মান নিয়ন্ত্রণ (Quality Control)',
                'desc'  => 'কোনো ক্ষতিকর রাসায়নিক, ফরমালিন বা কৃত্রিম রঙ ব্যবহার ছাড়া শতভাগ স্বাস্থ্যসম্মত প্রক্রিয়াকরণ ও ওজন নিশ্চিত করা হয়।',
                'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>'
            ),
            array(
                'title' => 'স্বাস্থ্যসম্মত প্রসেসিং (Hygienic Processing)',
                'desc'  => 'মাছ ও মাংস কাটিং, ওয়াশিং ও ড্রেনেজ সম্পূর্ণ পরিষ্কার-পরিচ্ছন্ন পরিবেশে ও স্বাস্থ্যবিধি মেনে সম্পন্ন করা হয়।',
                'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/></svg>'
            ),
            array(
                'title' => 'নির্ভরযোগ্য ডেলিভারি (Reliable Delivery)',
                'desc'  => 'তাপমাত্রা নিয়ন্ত্রণ ও সঠিক কোল্ড-চেইন বজায় রেখে দ্রুত সময়ে আপনার দরজায় পণ্য পৌঁছে দেওয়ার কার্যকর ব্যবস্থা।',
                'icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>'
            ),
        );
        ?>
        <section class="safwan-why-section">
            <div class="safwan-container">
                <div class="safwan-section-header">
                    <span class="safwan-section-tag">আমাদের অঙ্গীকার</span>
                    <h2 class="safwan-section-title">কেন বেছে নেবেন Safwan Nature-Gro?</h2>
                    <p class="safwan-section-subtitle">আমরা শুধু পণ্য বিক্রি করি না, খামার থেকে খাবার টেবিল পর্যন্ত স্বচ্ছতা, নিরাপত্তা ও মানসম্মত পুষ্টি পৌঁছে দিতে প্রতিজ্ঞাবদ্ধ।</p>
                </div>

                <div class="safwan-features-grid">
                    <?php foreach ( $features as $feat ) : ?>
                        <div class="safwan-feature-box">
                            <div class="safwan-feature-icon">
                                <?php echo $feat['icon']; ?>
                            </div>
                            <h3 class="safwan-feature-title"><?php echo esc_html( $feat['title'] ); ?></h3>
                            <p class="safwan-feature-desc"><?php echo esc_html( $feat['desc'] ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Section 5: Our Farm & Activities
     */
    private function render_farm_activities() {
        ?>
        <section class="safwan-farm-section">
            <div class="safwan-container">
                <div class="safwan-section-header">
                    <span class="safwan-section-tag">বাস্তব কার্যক্রম</span>
                    <h2 class="safwan-section-title">আমাদের খামার ও উৎপাদন কার্যক্রম</h2>
                    <p class="safwan-section-subtitle">প্রাকৃতিক নিয়মে ও আধুনিক ব্যবস্থাপনা অনুসরণে পরিচালিত আমাদের বিভিন্ন কৃষি ও মৎস্য কার্যক্রমের এক ঝলক।</p>
                </div>

                <div class="safwan-farm-grid">
                    <div class="safwan-farm-card">
                        <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/26q3s1771837303.jpeg' ) ); ?>" alt="দেশি ও নদীর মাছের চাষ" class="safwan-farm-card-img" loading="lazy">
                        <div class="safwan-farm-card-body">
                            <h3 class="safwan-farm-card-title">দেশি ও নদীর মাছের চাষ ও সংগ্রহ</h3>
                            <p class="safwan-farm-card-desc">আমাদের নিজস্ব জলাশয় ও তিস্তা-ব্রহ্মপুত্র নদীর অববাহিকার বিশ্বস্ত জেলেদের মাধ্যমে প্রাকৃতিক মাছ সংগ্রহ ও স্বাস্থ্যসম্মত ড্রেনেজ নিশ্চিত করা হয়।</p>
                            <a href="<?php echo esc_url( home_url( '/our-farm/' ) ); ?>" style="color:var(--safwan-primary); font-weight:700; text-decoration:none;">বিস্তারিত জানুন &rarr;</a>
                        </div>
                    </div>

                    <div class="safwan-farm-card">
                        <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/9weyd1775362946.jpeg' ) ); ?>" alt="হাঁস ও দেশি মুরগি পালন" class="safwan-farm-card-img" loading="lazy">
                        <div class="safwan-farm-card-body">
                            <h3 class="safwan-farm-card-title">হাঁস ও দেশি পোল্ট্রি ব্যবস্থাপনা</h3>
                            <p class="safwan-farm-card-desc">মুক্ত প্রাকৃতিক পরিবেশে ও স্বাস্থ্যসম্মত শেডে খামারের হাঁস ও দেশি মুরগি লালন-পালন করা হয়, যার মাধ্যমে সঠিক প্রোটিনের নিশ্চয়তা মেলে।</p>
                            <a href="<?php echo esc_url( home_url( '/our-farm/' ) ); ?>" style="color:var(--safwan-primary); font-weight:700; text-decoration:none;">বিস্তারিত জানুন &rarr;</a>
                        </div>
                    </div>

                    <div class="safwan-farm-card">
                        <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/04/o1uH11775363016-light.jpg' ) ); ?>" alt="কন্টাক্ট ফার্মিং ও শস্য সংগ্রহ" class="safwan-farm-card-img" loading="lazy">
                        <div class="safwan-farm-card-body">
                            <h3 class="safwan-farm-card-title">কন্টাক্ট ফার্মিং ও খাঁটি পণ্য সংগ্রহ</h3>
                            <p class="safwan-farm-card-desc">নির্বাচিত স্থানীয় বিশ্বস্ত কৃষকদের সহযোগিতায় খাঁটি সরিষার দানা সংগ্রহ, খাঁটি সুন্দরবনের মধু এবং ঐতিহ্যবাহী ঘানি ভাঙা তেল প্রক্রিয়াজাতকরণ।</p>
                            <a href="<?php echo esc_url( home_url( '/our-farm/' ) ); ?>" style="color:var(--safwan-primary); font-weight:700; text-decoration:none;">বিস্তারিত জানুন &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Section 6: Food Safety Commitment
     */
    private function render_food_safety() {
        $steps = array(
            array( 'no' => '১', 'title' => 'উৎস যাচাই ও স্বচ্ছতা', 'desc' => 'প্রতিটি পণ্যের উৎস স্পষ্ট ও পরীক্ষিত। কোনো অস্পষ্ট বা অবিবেচিত উৎস থেকে খাদ্য সংগ্রহ করা হয় না।' ),
            array( 'no' => '২', 'title' => 'নির্বাচিত খামারি ও কৃষক', 'desc' => 'শুধুমাত্র স্বাস্থ্যসম্মত উৎপাদন নীতি অনুসরণকারী রেজিস্টার্ড প্রান্তিক কৃষকদের সাথেই অংশীদারিত্ব।' ),
            array( 'no' => '৩', 'title' => 'হাইজিনিক প্রসেসিং', 'desc' => 'ধুয়ে-মুছে পরিচ্ছন্ন স্থানে কাটিং ও প্রক্রিয়াকরণ। প্রক্রিয়াকরণে কোনো ক্ষতিকর রাসায়নিক বা সংরক্ষক নিষিদ্ধ।' ),
            array( 'no' => '৪', 'title' => 'ফুড-গ্রেড প্যাকেজিং', 'desc' => 'খাদ্যের গুণগত মান ও সতেজতা সুরক্ষিত রাখতে উচ্চমানের ফুড-গ্রেড ভ্যাকুয়াম ও সিলড প্যাকেটের ব্যবহার।' ),
            array( 'no' => '৫', 'title' => 'তাপমাত্রা ও কোল্ড চেইন', 'desc' => 'মাছ ও মাংসের সতেজতা নিশ্চিত করতে আইস-বক্স ও কোল্ড-চেইন সাপোর্টের মাধ্যমে ডেলিভারি পরিবহন।' ),
            array( 'no' => '৬', 'title' => 'কাস্টমার সন্তুষ্টি ও রিটার্ন', 'desc' => 'পণ্য পৌঁছানোর পর যেকোনো অসঙ্গতি থাকলে তাত্ক্ষণিক পরিবর্তন বা রিফান্ডের সহজ নীতি।' ),
        );
        ?>
        <section class="safwan-safety-section">
            <div class="safwan-container">
                <div class="safwan-section-header">
                    <span class="safwan-section-tag">খাদ্য নিরাপত্তা মানদণ্ড</span>
                    <h2 class="safwan-section-title">আমাদের ফুড সেফটি ও কোয়ালিটি পলিসি</h2>
                    <p class="safwan-section-subtitle">আমরা অযৌক্তিক কোনো কাল্পনিক দাবি করি না; বাস্তবসম্মত ও স্বচ্ছ বৈজ্ঞানিক পদ্ধতিতে প্রতিটি ধাপে খাদ্য নিরাপত্তা নিশ্চিত করি।</p>
                </div>

                <div class="safwan-safety-grid">
                    <?php foreach ( $steps as $step ) : ?>
                        <div class="safwan-safety-step">
                            <div class="safwan-step-number"><?php echo esc_html( $step['no'] ); ?></div>
                            <div class="safwan-step-content">
                                <h4><?php echo esc_html( $step['title'] ); ?></h4>
                                <p><?php echo esc_html( $step['desc'] ); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="text-align: center; margin-top: 32px;">
                    <a href="<?php echo esc_url( home_url( '/food-safety/' ) ); ?>" class="btn-safwan btn-safwan-secondary">
                        <span>সম্পূর্ণ খাদ্য নিরাপত্তা নীতিমালা পড়ুন</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Section 7: Customer Reviews
     */
    private function render_customer_reviews() {
        $reviews = array(
            array(
                'name'    => 'মোহাম্মদ রফিকুল ইসলাম',
                'role'    => 'নিয়মিত ক্রেতা, ঢাকা',
                'comment' => 'সাফওয়ান এগ্রো থেকে নদীর তাজা ইলিশ ও ঘানি ভাঙা সরিষার তেল নিয়েছিলাম। মাছের সতেজতা এবং তেলের খাঁটি ঝাঁঝ সত্যিই অসাধারণ লেগেছে। প্যাকেজিং খুব পরিষ্কার ছিল।',
                'stars'   => 5,
            ),
            array(
                'name'    => 'নাসরিন সুলতানা',
                'role'    => 'গৃহিণী, গাইবান্ধা',
                'comment' => 'তাদের দেশি খামারের মুরগি ও খাঁটি মধুর মান প্রশংসনীয়। স্থানীয় খামারের তদারকির কারণে আস্থা পাই। পরিবারের জন্য নিরাপদ খাদ্য হিসেবে এটিই আমাদের পছন্দ।',
                'stars'   => 5,
            ),
            array(
                'name'    => 'মাহফুজুর রহমান',
                'role'    => 'ব্যাংকার, রংপুর',
                'comment' => 'হোয়াটসঅ্যাপে অর্ডার দিয়েছিলাম, খুব দ্রুত সাড়া পেয়েছি এবং নির্দিষ্ট সময়ে ডেলিভারি পেয়েছি। পণ্যের গুণমান ঠিক যেমন বলা হয়েছিল তেমনই ছিল। শুভকামনা রইল!',
                'stars'   => 5,
            ),
        );
        ?>
        <section class="safwan-reviews-section">
            <div class="safwan-container">
                <div class="safwan-section-header">
                    <span class="safwan-section-tag">ক্রেতাদের মতামত</span>
                    <h2 class="safwan-section-title">আমাদের ওপর ক্রেতাদের আস্থা</h2>
                    <p class="safwan-section-subtitle">প্রকৃত গ্রাহকদের অভিজ্ঞতা ও সন্তুষ্টিই আমাদের প্রতিদিনের অনুপ্রেরণা।</p>
                </div>

                <div class="safwan-reviews-grid">
                    <?php foreach ( $reviews as $rev ) : ?>
                        <div class="safwan-review-card">
                            <div>
                                <div class="safwan-stars">
                                    <?php for ( $i = 0; $i < $rev['stars']; $i++ ) : ?>★<?php endfor; ?>
                                </div>
                                <p class="safwan-review-text">"<?php echo esc_html( $rev['comment'] ); ?>"</p>
                            </div>
                            <div class="safwan-reviewer">
                                <div class="safwan-reviewer-avatar">
                                    <?php echo esc_html( mb_substr( $rev['name'], 0, 1 ) ); ?>
                                </div>
                                <div class="safwan-reviewer-info">
                                    <h5><?php echo esc_html( $rev['name'] ); ?></h5>
                                    <span class="safwan-reviewer-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> ভেরিফাইড ক্রেতা &bull; <?php echo esc_html( $rev['role'] ); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="https://www.facebook.com/safwanagro" target="_blank" rel="noopener" style="font-size: 14px; color: var(--safwan-primary); font-weight: 600; text-decoration: none;">
                        <i class="fab fa-facebook"></i> আমাদের অফিসিয়াল ফেসবুক পেজে আরও রিভিউ দেখুন &rarr;
                    </a>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Section 8: Offers & Packages
     */
    private function render_offers_packages() {
        $shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '/shop/';
        ?>
        <section class="safwan-offers-section">
            <div class="safwan-container">
                <div class="safwan-section-header">
                    <span class="safwan-section-tag">স্পেশাল প্যাকেজ</span>
                    <h2 class="safwan-section-title">চলমান অফার ও ফ্যামিলি কম্বো</h2>
                    <p class="safwan-section-subtitle">একসাথে পরিবারের নিত্যপ্রয়োজনীয় নিরাপদ খাদ্য কিনুন বিশেষ প্যাকেজ মূল্যে।</p>
                </div>

                <div class="safwan-offers-grid">
                    <div class="safwan-offer-banner">
                        <div>
                            <span class="safwan-offer-tag">ফ্যামিলি কম্বো অফার</span>
                            <h3 class="safwan-offer-title">মাসিক পুষ্টি প্যাকেজ (তেল, মধু ও ডাল)</h3>
                            <p class="safwan-offer-desc">১ লিটার ঘানি ভাঙা খাঁটি সরিষার তেল + ৫০০ গ্রাম প্রাকৃতিক সুন্দরবনের মধু + ১ কেজি প্রিমিয়াম দেশি ডাল। সীমিত সময়ের জন্য বিশেষ ছাড়।</p>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                            <div>
                                <span style="font-size:24px; font-weight:800; color:var(--safwan-primary);">৳১,৪৫০</span>
                                <span style="font-size:14px; text-decoration:line-through; color:var(--safwan-text-light); margin-left:8px;">৳১,৬৫০</span>
                            </div>
                            <a href="https://wa.me/8801717874347?text=<?php echo urlencode('মাসিক পুষ্টি প্যাকেজ অফারটি নিতে চাই।'); ?>" target="_blank" rel="noopener" class="btn-safwan btn-safwan-primary">
                                অর্ডার করুন
                            </a>
                        </div>
                    </div>

                    <div class="safwan-offer-banner">
                        <div>
                            <span class="safwan-offer-tag">সতেজ মাছের বান্ডিল</span>
                            <h3 class="safwan-offer-title">তিস্তা ও ব্রহ্মপুত্রের নদীর মাছ প্যাকেজ</h3>
                            <p class="safwan-offer-desc">নির্বাচিত তাজা দেশি মাছের কাটিং ও রেডি-টু-কুক ক্লিন প্যাক। অর্ডার অনুযায়ী খামার ও ঘাট থেকে সরাসরি সংগ্রহ করে ডেলিভারি।</p>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                            <div>
                                <span style="font-size:14px; font-weight:600; color:var(--safwan-text-muted);">দৈনিক বাজার দর অনুযায়ী</span>
                            </div>
                            <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-safwan btn-safwan-primary">
                                প্যাকেজ দেখুন
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * Section 9: Newsletter & Contact CTA
     */
    private function render_newsletter_cta() {
        ?>
        <section class="safwan-cta-section">
            <div class="safwan-container">
                <div class="safwan-cta-inner">
                    <h2 class="safwan-cta-title">আপনার পরিবারের সুস্বাস্থ্যের অংশীদার হতে চাই</h2>
                    <p class="safwan-cta-desc">
                        যেকোনো প্রশ্ন, পণ্যের অর্ডার কিংবা খামার সম্পর্কে বিস্তারিত জানতে আমাদের সাথে সরাসরি যোগাযোগ করুন। গাইবান্ধা সদরে আমাদের সাথে সরাসরি দেখা করতেও আমন্ত্রিত।
                    </p>

                    <div class="safwan-cta-buttons">
                        <a href="tel:01717874347" class="btn-safwan btn-safwan-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>সরাসরি কল: 01717-874347</span>
                        </a>
                        <a href="https://wa.me/8801717874347" target="_blank" rel="noopener" class="btn-safwan btn-safwan-whatsapp">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/></svg>
                            <span>WhatsApp মেসেজ পাঠান</span>
                        </a>
                    </div>

                    <form class="safwan-newsletter-form" onsubmit="event.preventDefault(); alert('ধন্যবাদ! নতুন অফার ও খামারের খবরের জন্য আপনার তথ্য সংরক্ষিত হয়েছে।');">
                        <input type="email" class="safwan-newsletter-input" placeholder="আপনার ইমেইল বা ফোন নম্বর দিন..." required>
                        <button type="submit" class="btn-safwan btn-safwan-primary">যুক্ত হোন</button>
                    </form>
                </div>
            </div>
        </section>
        <?php
    }
}
