<?php
/**
 * Plugin Name: WooCommerce Custom Order Status [Shipped - waiting payment]
 * Plugin URI: https://github.com/rawscripter/woocommerce-order-status-plugin
 * Description: Add new Custom Order Status on WooCommerce Order [Shipped - waiting payment]
 * Version: 1.0
 * Author: RawScripter
 * Author URI: https://www.rawscripters.dev/
 */
// Register new custom order status
add_action('init', 'register_custom_order_statuses');
function register_custom_order_statuses()
{
    register_post_status('wc-waiting-payment ', array(
        'label' => __('Shipped - waiting payment', 'woocommerce'),
        'public' => true,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Shipped - waiting payment <span class="count">(%s)</span>', 'Shipped - waiting payment <span class="count">(%s)</span>')
    ));
}

// Add new custom order status to list of WC Order statuses
add_filter('wc_order_statuses', 'add_custom_order_statuses');
function add_custom_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    // add new order status before processing
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-processing' === $key) {
            $new_order_statuses['wc-waiting-payment'] = __('Shipped - waiting payment', 'woocommerce');
        }
    }
    return $new_order_statuses;
}

// Adding new custom status to admin order list bulk dropdown
add_filter('bulk_actions-edit-shop_order', 'custom_dropdown_bulk_actions_shop_order', 50, 1);
function custom_dropdown_bulk_actions_shop_order($actions)
{
    $new_actions = array();
    // add new order status before processing
    foreach ($actions as $key => $action) {
        if ('mark_processing' === $key)
            $new_actions['mark_waiting-payment'] = __('Shipped - waiting payment', 'woocommerce');
        $new_actions[$key] = $action;
    }
    return $new_actions;
}
