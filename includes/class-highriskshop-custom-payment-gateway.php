<?php
if (!class_exists('WC_Payment_Gateway')) {
    return;
}

class highriskshop_Custom_Payment_Gateway extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'highriskshop_custom_payment_redirect';
        $this->icon = esc_url($this->get_option('icon_url'));
        $this->method_title       = esc_html__('Custom Payment Redirect', 'woocommerce'); // Escaping title
        $this->method_description = esc_html__('Redirects users to a custom URL after checkout.', 'woocommerce'); // Escaping description
        $this->has_fields         = false;

        $this->init_form_fields();
        $this->init_settings();

        $this->title       = $this->get_option('title');
        $this->description = $this->get_option('description');

        // Use the configured settings for redirect and icon URLs
        $this->redirect_url = esc_url($this->get_option('redirect_url'));
        $this->icon_url     = esc_url($this->get_option('icon_url'));

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => esc_html__('Enable/Disable', 'woocommerce'), // Escaping title
                'type'    => 'checkbox',
                'label'   => esc_html__('Enable Custom Payment Redirect', 'woocommerce'), // Escaping label
                'default' => 'yes',
            ),
            'title' => array(
                'title'       => esc_html__('Title', 'woocommerce'), // Escaping title
                'type'        => 'text',
                'description' => esc_html__('Payment method title that users will see during checkout.', 'woocommerce'), // Escaping description
                'default'     => esc_html__('Custom Redirect', 'woocommerce'), // Escaping default value
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => esc_html__('Description', 'woocommerce'), // Escaping title
                'type'        => 'textarea',
                'description' => esc_html__('Payment method description that users will see during checkout.', 'woocommerce'), // Escaping description
                'default'     => esc_html__('Pay via Custom Redirect', 'woocommerce'), // Escaping default value
                'desc_tip'    => true,
            ),
            'redirect_url' => array(
                'title'       => esc_html__('Redirect URL', 'woocommerce'), // Escaping title
                'type'        => 'text',
                'description' => esc_html__('Enter the URL where users will be redirected after payment.', 'woocommerce'), // Escaping description
                'desc_tip'    => true,
            ),
            'icon_url' => array(
                'title'       => esc_html__('Icon URL', 'woocommerce'), // Escaping title
                'type'        => 'text',
                'description' => esc_html__('Enter the URL of the icon image for the payment method.', 'woocommerce'), // Escaping description
                'desc_tip'    => true,
            ),
        );
    }

    public function process_payment($order_id) {
        $order = wc_get_order($order_id);

        // Mark order as on-hold and add note
        $order->update_status('on-hold', esc_html__('Awaiting payment via Custom Redirect', 'woocommerce')); // Escaping status message

        // Use the configured redirect URL
        return array(
            'result'   => 'success',
            'redirect' => $this->redirect_url,
        );
    }
}

function highriskshop_add_custom_payment_gateway($gateways) {
    $gateways[] = 'highriskshop_Custom_Payment_Gateway';
    return $gateways;
}