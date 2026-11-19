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
    wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-theme-css'), '1.0.8' );
}
add_action( 'wp_enqueue_scripts', 'safwan_child_enqueue_styles', 15 );

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
