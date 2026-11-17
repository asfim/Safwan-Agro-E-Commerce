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
    
    // Enqueue Child Theme Style with cache-busting version
    wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-theme-css'), '1.0.4' );
}
add_action( 'wp_enqueue_scripts', 'safwan_child_enqueue_styles', 15 );
