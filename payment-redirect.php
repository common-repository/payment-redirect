<?php
/**
 * Plugin Name: Payment Redirect
 * Plugin URI: https://www.highriskshop.com/product/woocommerce-payment-redirect-cloaking-plugin/
 * Description: Redirect your customers during checkout to make the payment on another website using the native WooCommerce payment methods/gateways. Allowing you to create your own WooCommerce high risk payment gateway.
 * Version: 1.0.4
 * Author: HighRiskShop.COM
 * Author URI: https://www.highriskshop.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Initialize the plugin
function highriskshop_custom_payment_redirect_init() {
    if (class_exists('WC_Payment_Gateway')) {
        include_once(plugin_dir_path(__FILE__) . 'includes/class-highriskshop-custom-payment-gateway.php'); // Include the payment gateway class
        add_filter('woocommerce_payment_gateways', 'highriskshop_add_custom_payment_gateway');
    }
}
add_action('plugins_loaded', 'highriskshop_custom_payment_redirect_init');

function highriskshop_add_custom_css() {
    echo '<style>';
    echo '
        #add_payment_method #payment ul.payment_methods li img,
        .woocommerce-cart #payment ul.payment_methods li img,
        .woocommerce-checkout #payment ul.payment_methods li img {
            max-width: 45%;
			max-height: 20px;
			float:right;
        }
    ';
    echo '</style>';
}
add_action('wp_head', 'highriskshop_add_custom_css');

// Add an admin notice with a clickable URL
function highriskshop_custom_admin_notice_with_url() {
    echo '<div class="notice notice-success is-dismissible">';
    echo '<p>';
    echo esc_html__('You have installed the free version of the payment redirect plugin with limited functions, to get the full premium version with full payment redirect/cloaking that allows your customer to pay through a payment gateway installed on another WooCommerce site please purchase the premium plugin at ', 'high-risk-payment-redirect');
    echo '<a href="'.esc_url('https://www.highriskshop.com').'">'.esc_html('HighRiskShop.COM').'</a>';
    echo esc_html('.');
    echo '</p>';
    echo '</div>';
}
add_action('admin_notices', 'highriskshop_custom_admin_notice_with_url');