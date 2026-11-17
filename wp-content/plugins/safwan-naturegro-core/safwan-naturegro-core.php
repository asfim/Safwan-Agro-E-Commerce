<?php
/**
 * Plugin Name: Safwan Nature-Gro Core
 * Plugin URI: https://safwanagro.com/
 * Description: Core business logic, header/footer components, homepage sections, WhatsApp ordering, and WooCommerce enhancements for Safwan Nature-Gro Farming.
 * Version: 1.0.1
 * Author: Safwan Nature-Gro Team
 * Author URI: https://safwanagro.com/
 * Text Domain: safwan-naturegro-core
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SAFWAN_CORE_VERSION', '1.0.2' );
define( 'SAFWAN_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SAFWAN_CORE_URL', plugin_dir_url( __FILE__ ) );

// Load Module Classes
require_once SAFWAN_CORE_PATH . 'includes/class-elementor-compatibility.php';
require_once SAFWAN_CORE_PATH . 'includes/class-header-footer.php';
require_once SAFWAN_CORE_PATH . 'includes/class-homepage-sections.php';
require_once SAFWAN_CORE_PATH . 'includes/class-woocommerce-enhancements.php';

class Safwan_NatureGro_Core {

    private static $instance = null;
    public $header_footer;
    public $homepage_sections;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->header_footer = new Safwan_Header_Footer();
        $this->homepage_sections = new Safwan_Homepage_Sections();

        // Enqueue Assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 20 );

        // Buy Now AJAX Endpoint
        add_action( 'wp_ajax_safwan_buy_now', array( $this, 'handle_buy_now' ) );
        add_action( 'wp_ajax_nopriv_safwan_buy_now', array( $this, 'handle_buy_now' ) );

        // Astra Theme Header & Footer Hooks disabled in favor of Elementor-editable HFE templates (Posts 32 & 77)
        // add_action( 'astra_header_before', array( $this, 'hook_custom_header' ) );
        // add_action( 'astra_footer_before', array( $this, 'hook_custom_footer' ) );

        // Register all legacy shortcodes
        $this->register_all_shortcodes();
    }

    /**
     * Check if currently inside Elementor Editor or Preview mode
     */
    public function is_elementor_editor_mode() {
        if ( is_admin() ) {
            return true;
        }
        if ( isset( $_GET['elementor-preview'] ) || ( isset( $_GET['action'] ) && $_GET['action'] === 'elementor' ) ) {
            return true;
        }
        if ( class_exists( '\Elementor\Plugin' ) ) {
            if ( isset( \Elementor\Plugin::$instance->editor ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                return true;
            }
            if ( isset( \Elementor\Plugin::$instance->preview ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Enqueue CSS & JS
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'safwan-core-style',
            SAFWAN_CORE_URL . 'assets/css/safwan-core.css',
            array(),
            SAFWAN_CORE_VERSION
        );

        wp_enqueue_script(
            'safwan-core-script',
            SAFWAN_CORE_URL . 'assets/js/safwan-core.js',
            array( 'jquery' ),
            SAFWAN_CORE_VERSION,
            true
        );

        wp_localize_script(
            'safwan-core-script',
            'safwan_vars',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'safwan_ajax_nonce' ),
            )
        );
    }

    /**
     * Handle Buy Now Ajax Request
     */
    public function handle_buy_now() {
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        $quantity   = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

        if ( ! $product_id ) {
            wp_send_json_error( array( 'message' => 'Invalid product' ) );
        }

        if ( function_exists( 'WC' ) && WC()->cart ) {
            WC()->cart->add_to_cart( $product_id, $quantity );
            wp_send_json_success( array(
                'redirect_url' => wc_get_checkout_url(),
            ) );
        }

        wp_send_json_error( array( 'message' => 'Cart not initialized' ) );
    }

    /**
     * Hook Custom Header if on front-end (skip if in Elementor editor)
     */
    public function hook_custom_header() {
        if ( $this->is_elementor_editor_mode() ) {
            return;
        }
        remove_action( 'astra_header', 'astra_header_markup' );
        echo do_shortcode( '[safwan_header]' );
    }

    /**
     * Hook Custom Footer if on front-end (skip if in Elementor editor)
     */
    public function hook_custom_footer() {
        if ( $this->is_elementor_editor_mode() ) {
            return;
        }
        remove_action( 'astra_footer', 'astra_footer_markup' );
        echo do_shortcode( '[safwan_footer]' );
    }

    /**
     * Register legacy & custom shortcodes
     */
    private function register_all_shortcodes() {
        // [custom_grid] shortcode from snippet 337
        if ( ! shortcode_exists( 'custom_grid' ) ) {
            add_shortcode( 'custom_grid', function( $atts ) {
                $atts = shortcode_atts( array(
                    'ids' => '',
                ), $atts );

                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 8,
                    'post_status'    => 'publish',
                );

                if ( ! empty( $atts['ids'] ) ) {
                    $args['post__in'] = explode( ',', $atts['ids'] );
                    $args['orderby'] = 'post__in';
                    $args['posts_per_page'] = -1;
                } else {
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                }

                $loop = new WP_Query( $args );
                ob_start();
                if ( $loop->have_posts() ) :
                    echo '<div class="custom-product-grid">';
                    while ( $loop->have_posts() ) : $loop->the_post();
                        global $product;
                        if ( ! is_a( $product, 'WC_Product' ) ) continue;
                        $product_id = get_the_ID();
                        $title = get_the_title();
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'large' );
                        $image_src = $image ? $image[0] : wc_placeholder_img_src();
                        $price = $product->get_price_html();
                        $permalink = get_permalink( $product_id );
                        $checkout_url = wc_get_checkout_url() . '?add-to-cart=' . $product_id;
                        $is_variable = $product->is_type( 'variable' );
                        $cart_url = $is_variable ? $permalink : esc_url( $product->add_to_cart_url() );
                        ?>
                        <div class="product-card">
                            <div class="best-selling-badge"><i class="fas fa-fire"></i> Best Selling</div>
                            <div class="product-image">
                                <a href="<?php echo esc_url( $permalink ); ?>">
                                    <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                                </a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h3>
                                <div class="product-price"><?php echo $price; ?></div>
                                <div class="product-buttons">
                                    <a href="<?php echo esc_url( $cart_url ); ?>" class="btn-add-to-cart">
                                        <i class="fas fa-shopping-cart"></i> Add To Cart
                                    </a>
                                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="btn-buy-now">
                                       <i class="fas fa-bolt"></i> Buy now 
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    echo '</div>';
                endif;
                wp_reset_postdata();
                return ob_get_clean();
            });
        }
    }
}

// Initialize Plugin
add_action( 'plugins_loaded', array( 'Safwan_NatureGro_Core', 'get_instance' ) );
