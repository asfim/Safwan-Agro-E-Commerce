<?php
/**
 * Safwan Nature-Gro Farming - WooCommerce Enhancements
 * Single Product Tabs, WhatsApp Ordering, Bangladeshi Checkout, and Shipping
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Safwan_WooCommerce_Enhancements {

    private static $instance = null;
    private $whatsapp_number = '8801717874347';

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // Single Product Tabs
        add_filter( 'woocommerce_product_tabs', array( $this, 'custom_product_tabs' ), 50 );

        // Direct WhatsApp Order Button on Single Product
        add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'render_whatsapp_order_button' ), 15 );
        add_shortcode( 'safwan_whatsapp_btn', array( $this, 'render_whatsapp_order_button_shortcode' ) );

        // Checkout Fields Customization (Bangladeshi fields)
        add_filter( 'woocommerce_checkout_fields', array( $this, 'customize_checkout_fields' ), 20 );

        // BACS / Offline Payment Gateways labels & instructions (bKash & Nagad)
        add_filter( 'woocommerce_gateway_title', array( $this, 'customize_gateway_title' ), 20, 2 );
        add_filter( 'woocommerce_gateway_description', array( $this, 'customize_gateway_description' ), 20, 2 );

        // Add Transaction ID field to checkout for bKash/Nagad
        add_action( 'woocommerce_after_order_notes', array( $this, 'add_trx_id_field' ) );
        add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_trx_id_field' ) );
        add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'display_trx_id_admin_order' ) );

        // Enqueue WooCommerce specific frontend styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_woo_styles' ), 25 );
    }

    /**
     * Enqueue Custom WooCommerce styling
     */
    public function enqueue_woo_styles() {
        $custom_css = "
        /* WhatsApp Order Button on Single Product */
        .btn-safwan-whatsapp-single {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: #25d366 !important;
            color: #ffffff !important;
            font-family: 'Noto Sans Bengali', sans-serif !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            padding: 12px 24px !important;
            border-radius: 6px !important;
            text-decoration: none !important;
            margin-top: 12px !important;
            margin-left: 8px !important;
            border: none !important;
            transition: all 0.25s ease !important;
        }
        .btn-safwan-whatsapp-single:hover {
            background-color: #1ebc59 !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);
        }
        /* Custom Single Product Tabs styling */
        .woocommerce-tabs ul.tabs {
            border-bottom: 2px solid #e2e8f0 !important;
            padding-left: 0 !important;
        }
        .woocommerce-tabs ul.tabs li {
            border: none !important;
            background: transparent !important;
            border-radius: 0 !important;
            margin-right: 20px !important;
        }
        .woocommerce-tabs ul.tabs li a {
            font-family: 'Noto Sans Bengali', sans-serif !important;
            font-weight: 600 !important;
            color: #64748b !important;
            padding: 12px 4px !important;
            border-bottom: 3px solid transparent !important;
        }
        .woocommerce-tabs ul.tabs li.active a {
            color: #b91c1c !important;
            border-bottom: 3px solid #b91c1c !important;
        }
        .safwan-tab-content {
            font-family: 'Noto Sans Bengali', sans-serif;
            color: #334155;
            line-height: 1.8;
            font-size: 15px;
            padding: 16px 0;
        }
        .safwan-tab-content h4 {
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 12px;
            font-weight: 700;
        }
        .safwan-tab-content ul {
            padding-left: 20px;
            margin-bottom: 16px;
        }
        .safwan-tab-content ul li {
            margin-bottom: 6px;
        }
        /* Checkout Fields Improvements */
        .woocommerce-checkout .form-row {
            margin-bottom: 16px;
        }
        .woocommerce-checkout label {
            font-family: 'Noto Sans Bengali', sans-serif !important;
            font-weight: 600 !important;
            color: #1e293b !important;
        }
        .woocommerce-checkout input.input-text,
        .woocommerce-checkout select,
        .woocommerce-checkout textarea {
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 6px !important;
            padding: 10px 14px !important;
            font-size: 14px !important;
        }
        .woocommerce-checkout input.input-text:focus,
        .woocommerce-checkout textarea:focus {
            border-color: #b91c1c !important;
            outline: none !important;
        }
        ";
        wp_add_inline_style( 'safwan-core-style', $custom_css );
    }

    /**
     * Add 5 Custom Product Tabs as required by master prompt Section 6 & 9
     */
    public function custom_product_tabs( $tabs ) {
        global $product;
        if ( ! $product ) {
            return $tabs;
        }

        // Tab 1: Product Source (খাদ্য উৎস)
        $tabs['safwan_source_tab'] = array(
            'title'    => 'পণ্যের উৎস (Source)',
            'priority' => 15,
            'callback' => array( $this, 'render_source_tab_content' )
        );

        // Tab 2: Processing (প্রসেসিং ও হাইজিন)
        $tabs['safwan_processing_tab'] = array(
            'title'    => 'প্রসেসিং তথ্য (Processing)',
            'priority' => 20,
            'callback' => array( $this, 'render_processing_tab_content' )
        );

        // Tab 3: Packaging (প্যাকেজিং)
        $tabs['safwan_packaging_tab'] = array(
            'title'    => 'প্যাকেজিং (Packaging)',
            'priority' => 25,
            'callback' => array( $this, 'render_packaging_tab_content' )
        );

        // Tab 4: Storage Instructions (সংরক্ষণ পদ্ধতি)
        $tabs['safwan_storage_tab'] = array(
            'title'    => 'সংরক্ষণ পদ্ধতি (Storage)',
            'priority' => 30,
            'callback' => array( $this, 'render_storage_tab_content' )
        );

        // Tab 5: Delivery Information (ডেলিভারি তথ্য)
        $tabs['safwan_delivery_tab'] = array(
            'title'    => 'ডেলিভারি তথ্য (Delivery)',
            'priority' => 35,
            'callback' => array( $this, 'render_delivery_tab_content' )
        );

        return $tabs;
    }

    public function render_source_tab_content() {
        ?>
        <div class="safwan-tab-content">
            <h4><i class="fas fa-leaf" style="color:#15803d; margin-right:8px;"></i> পণ্যের উৎস ও উৎপাদন তথ্য</h4>
            <p>সাফওয়ান নেচার-গ্রো ফার্মিং-এর প্রতিটি খাদ্যপণ্য আমাদের নিজস্ব খামার এবং গাইবান্ধা ও পার্শ্ববর্তী অঞ্চলের নির্বাচিত বিশ্বস্ত খামারিদের সরাসরি তত্ত্বাবধানে উৎপাদিত ও সংগৃহীত।</p>
            <ul>
                <li><strong>উৎস:</strong> নিজস্ব খামার ও স্থানীয় বিশ্বস্ত কৃষক/খামারি।</li>
                <li><strong>পরিবেশ:</strong> প্রাকৃতিক জলাশয়, উন্মুক্ত চারণভূমি ও রাসায়নিকমুক্ত খাদ্য।</li>
                <li><strong>যাচাইকরণ:</strong> প্রতিটি লট সংগ্রহের পূর্বে গুণগত মান পরীক্ষা করা হয়।</li>
            </ul>
        </div>
        <?php
    }

    public function render_processing_tab_content() {
        ?>
        <div class="safwan-tab-content">
            <h4><i class="fas fa-pump-medical" style="color:#b91c1c; margin-right:8px;"></i> হাইজিনিক প্রসেসিং স্ট্যান্ডার্ড</h4>
            <p>আমাদের প্রসেসিং ইউনিটে খাদ্য নিরাপত্তার আন্তর্জাতিক স্বাস্থ্যবিধি কঠোরভাবে অনুসরণ করা হয়:</p>
            <ul>
                <li>মাছ ও মাংস পরিচ্ছন্ন ও জীবাণুমুক্ত ধারালো যন্ত্রপাতির সাহায্যে ড্রেসিং করা হয়।</li>
                <li>কোনোরূপ ক্ষতিকারক রাসায়নিক বা কৃত্রিম প্রিজারভেটিভ ব্যবহার করা হয় না।</li>
                <li>প্রসেসিংয়ের প্রতিটি স্তরে ফিল্টার করা বিশুদ্ধ পানি ব্যবহার করা হয়।</li>
            </ul>
        </div>
        <?php
    }

    public function render_packaging_tab_content() {
        ?>
        <div class="safwan-tab-content">
            <h4><i class="fas fa-box" style="color:#d97706; margin-right:8px;"></i> ফুড-গ্রেড প্যাকেজিং ও নিরাপত্তা</h4>
            <p>পণ্যের সতেজতা ও পুষ্টিমান অটুট রাখতে ফুড-গ্রেড বায়ুরোধী প্যাকেজিং ব্যবহার করা হয়:</p>
            <ul>
                <li>লিকুইড পণ্যের (ঘি, মধু, তেল) জন্য ভার্জিন গ্লাস জার ও বিপিএ-মুক্ত কন্টেইনার।</li>
                <li>মাছ ও মাংসের জন্য বায়ুরোধী ভ্যাকুয়াম পাউচ বা ফুড-গ্রেড বক্স।</li>
                <li>বাহ্যিক ধুলাবালি ও জীবাণু থেকে শতভাগ সুরক্ষিত প্যাকেজিং।</li>
            </ul>
        </div>
        <?php
    }

    public function render_storage_tab_content() {
        ?>
        <div class="safwan-tab-content">
            <h4><i class="fas fa-snowflake" style="color:#0284c7; margin-right:8px;"></i> সংরক্ষণ ও ব্যবহার নির্দেশিকা</h4>
            <ul>
                <li><strong>তাজা মাছ ও মাংস:</strong> হাতে পাওয়ার পর দ্রুত রান্নার পরামর্শ দেওয়া হয়। সংরক্ষণ করতে চাইলে -১৮° সেলসিয়াস তাপমাত্রায় ডিপ ফ্রিজে রাখুন।</li>
                <li><strong>খাঁটি মধু ও সরিষার তেল:</strong> সরাসরি সূর্যের আলো থেকে দূরে স্বাভাবিক ঘরের তাপমাত্রায় শুষ্ক স্থানে রাখুন। রেফ্রিজারেটরে রাখার প্রয়োজন নেই।</li>
                <li><strong>অর্গানিক ঘি:</strong> শুষ্ক স্থানে শুকনো চামচ ব্যবহার করে সংরক্ষণ করুন।</li>
            </ul>
        </div>
        <?php
    }

    public function render_delivery_tab_content() {
        ?>
        <div class="safwan-tab-content">
            <h4><i class="fas fa-shipping-fast" style="color:#15803d; margin-right:8px;"></i> ডেলিভারি ও কোল্ড চেইন তথ্য</h4>
            <ul>
                <li><strong>গাইবান্ধা সদর:</strong> একই দিনে বা অর্ডার নিশ্চিতের ২৪ ঘণ্টার মধ্যে হোম ডেলিভারি। ডেলিভারি চার্জ ৬০ টাকা।</li>
                <li><strong>অন্যান্য জেলা / সারা বাংলাদেশ:</strong> কোল্ড চেইন অথবা দ্রুতগতির কুরিয়ার সার্ভিসের মাধ্যমে ২৪ থেকে ৪৮ ঘণ্টার মধ্যে ডেলিভারি। ডেলিভারি চার্জ ১২০-১৫০ টাকা।</li>
                <li><strong>পণ্য পাওয়ার পর চেক করুন:</strong> ডেলিভারিম্যানের উপস্থিতিতে পণ্য পরীক্ষা করে রিসিভ করার অনুরোধ করা হচ্ছে। কোনো ত্রুটি পরিলক্ষিত হলে সাথে সাথে রিপ্লেসমেন্ট প্রযোজ্য।</li>
            </ul>
        </div>
        <?php
    }

    /**
     * Render direct WhatsApp Order button on Single Product Page
     */
    public function render_whatsapp_order_button() {
        global $product;
        if ( ! $product ) {
            return;
        }

        $title = $product->get_name();
        $price = $product->get_price();
        $url   = get_permalink( $product->get_id() );

        $msg = "আসসালামু আলাইকুম, আমি সাফওয়ান এগ্রো থেকে এই পণ্যটি অর্ডার করতে চাই:\n";
        $msg .= "পণ্য: {$title}\n";
        $msg .= "মূল্য: ৳{$price}\n";
        $msg .= "লিংক: {$url}\n";
        $msg .= "দয়া করে ডেলিভারি কনফার্ম করুন।";

        $wa_link = 'https://wa.me/' . $this->whatsapp_number . '?text=' . rawurlencode( $msg );
        ?>
        <a href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener noreferrer" class="btn-safwan-whatsapp-single">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/></svg>
            <span>WhatsApp-এ সরাসরি অর্ডার করুন</span>
        </a>
        <?php
    }

    public function render_whatsapp_order_button_shortcode() {
        ob_start();
        $this->render_whatsapp_order_button();
        return ob_get_clean();
    }

    /**
     * Streamline Checkout Fields for Bangladeshi Customers
     */
    public function customize_checkout_fields( $fields ) {
        // Billing fields customization
        $fields['billing']['billing_first_name']['label'] = 'আপনার পুরো নাম (Full Name)';
        $fields['billing']['billing_first_name']['placeholder'] = 'আপনার নাম লিখুন';
        $fields['billing']['billing_first_name']['class'] = array( 'form-row-wide' );

        // Hide unnecessary last name field
        unset( $fields['billing']['billing_last_name'] );

        // Hide company name
        unset( $fields['billing']['billing_company'] );

        // Mobile Phone
        $fields['billing']['billing_phone']['label'] = 'মোবাইল নম্বর (Phone Number)';
        $fields['billing']['billing_phone']['placeholder'] = '০১XXXXXXXXX (১১ ডিজিট)';
        $fields['billing']['billing_phone']['required'] = true;
        $fields['billing']['billing_phone']['class'] = array( 'form-row-wide' );

        // Email address (make optional for BD buyers)
        $fields['billing']['billing_email']['label'] = 'ইমেইল অ্যাড্রেস (Email Address - ঐচ্ছিক)';
        $fields['billing']['billing_email']['required'] = false;
        $fields['billing']['billing_email']['class'] = array( 'form-row-wide' );

        // Full Street Address
        $fields['billing']['billing_address_1']['label'] = 'পূর্ণ ঠিকানা (Full Address - বাসা/গ্রাম, রোড)';
        $fields['billing']['billing_address_1']['placeholder'] = 'বাসা/হোল্ডিং নম্বর, রাস্তা বা গ্রামের নাম লিখুন';
        unset( $fields['billing']['billing_address_2'] );

        // District (City/District)
        $fields['billing']['billing_city']['label'] = 'জেলা (District)';
        $fields['billing']['billing_city']['placeholder'] = 'যেমন: গাইবান্ধা, রংপুর, ঢাকা';
        $fields['billing']['billing_city']['required'] = true;

        // Thana / Upazila / Area
        $fields['billing']['billing_state']['label'] = 'থানা / উপজেলা / এলাকা (Thana / Area)';
        $fields['billing']['billing_state']['placeholder'] = 'যেমন: গাইবান্ধা সদর / গোবিন্দগঞ্জ';
        $fields['billing']['billing_state']['required'] = true;

        // Postcode optional
        $fields['billing']['billing_postcode']['required'] = false;
        $fields['billing']['billing_postcode']['label'] = 'পোস্টকোড (ঐচ্ছিক)';

        // Order Notes / Delivery Instructions
        $fields['order']['order_comments']['label'] = 'ডেলিভারি নির্দেশনা বা বিশেষ নোট (ঐচ্ছিক)';
        $fields['order']['order_comments']['placeholder'] = 'ডেলিভারি সময় বা পণ্য সম্পর্কিত কোনো বিশেষ নির্দেশনা থাকলে লিখুন...';

        return $fields;
    }

    /**
     * Customize Gateway Titles (bKash, Nagad, COD)
     */
    public function customize_gateway_title( $title, $gateway_id ) {
        if ( 'cod' === $gateway_id ) {
            return 'ক্যাশ অন ডেলিভারি (Cash on Delivery) — পণ্য হাতে পেয়ে মূল্য পরিশোধ';
        }
        if ( 'bacs' === $gateway_id ) {
            return 'বিকাশ / নগদ (bKash / Nagad) — পার্সোনাল সেন্ড মানি';
        }
        return $title;
    }

    public function customize_gateway_description( $desc, $gateway_id ) {
        if ( 'bacs' === $gateway_id ) {
            return '<div style="background:#f8fafc; border:1px solid #e2e8f0; padding:14px; border-radius:6px; font-size:14px; line-height:1.6; margin-top:10px;">
                <p style="margin:0 0 8px 0; font-weight:600; color:#1e293b;">বিকাশ বা নগদ-এ সেন্ড মানি করুন:</p>
                <p style="margin:0 0 4px 0;"><strong>বিকাশ (Personal):</strong> 01717-874347</p>
                <p style="margin:0 0 8px 0;"><strong>নগদ (Personal):</strong> 01717-874347</p>
                <p style="margin:0; color:#64748b; font-size:13px;">টাকা পাঠানোর পর নিচের বক্সে আপনার বিকাশ/নগদ নম্বর এবং Transaction ID (TrxID) উল্লেখ করুন।</p>
            </div>';
        }
        if ( 'cod' === $gateway_id ) {
            return 'পণ্য ডেলিভারি পাওয়ার পর ডেলিভারিম্যানের কাছে সম্পূর্ণ মূল্য পরিশোধ করুন। কোনো অগ্রিম পেমেন্টের প্রয়োজন নেই।';
        }
        return $desc;
    }

    /**
     * Add TrxID field on checkout
     */
    public function add_trx_id_field( $checkout ) {
        echo '<div id="safwan_trx_field" style="margin-top:20px; padding:16px; background:#f1f5f9; border-radius:8px;">';
        echo '<h4 style="margin:0 0 10px 0; font-size:16px; font-family:\'Noto Sans Bengali\', sans-serif;">বিকাশ / নগদ পেমেন্ট তথ্য (যদি প্রযোজ্য হয়):</h4>';
        
        woocommerce_form_field( 'safwan_payment_number', array(
            'type'        => 'text',
            'class'       => array( 'form-row-wide' ),
            'label'       => 'যে নম্বর থেকে টাকা পাঠিয়েছেন (Sender Phone)',
            'placeholder' => '০১XXXXXXXXX',
            'required'    => false,
        ), $checkout->get_value( 'safwan_payment_number' ) );

        woocommerce_form_field( 'safwan_trx_id', array(
            'type'        => 'text',
            'class'       => array( 'form-row-wide' ),
            'label'       => 'Transaction ID (TrxID)',
            'placeholder' => 'যেমন: 9J54X6Y8',
            'required'    => false,
        ), $checkout->get_value( 'safwan_trx_id' ) );

        echo '</div>';
    }

    public function save_trx_id_field( $order_id ) {
        if ( ! empty( $_POST['safwan_payment_number'] ) ) {
            update_post_meta( $order_id, '_safwan_payment_number', sanitize_text_field( $_POST['safwan_payment_number'] ) );
        }
        if ( ! empty( $_POST['safwan_trx_id'] ) ) {
            update_post_meta( $order_id, '_safwan_trx_id', sanitize_text_field( $_POST['safwan_trx_id'] ) );
        }
    }

    public function display_trx_id_admin_order( $order ) {
        $sender = get_post_meta( $order->get_id(), '_safwan_payment_number', true );
        $trx    = get_post_meta( $order->get_id(), '_safwan_trx_id', true );
        if ( $sender || $trx ) {
            echo '<p><strong>বিকাশ/নগদ প্রেরক নম্বর:</strong> ' . esc_html( $sender ) . '</p>';
            echo '<p><strong>Transaction ID (TrxID):</strong> ' . esc_html( $trx ) . '</p>';
        }
    }
}

// Initialize WooCommerce Enhancements
add_action( 'init', array( 'Safwan_WooCommerce_Enhancements', 'get_instance' ) );
