<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: WooCommerce module
Description: view WooCommerce shop orders and customer info from perfex Dashboard. click aurthor name for support
Author: Techy4m
Author URI: https://codecanyon.net/item/woocommerce-module-for-perfex-crm/25337376/support
Version: 2.2.2
Requires at least: 2.4.2
*/
require(__DIR__ . '/vendor/autoload.php');
register_language_files('woocommerce', ['woocommerce']);

$CI = &get_instance();
define('WOOCOMMERCE_MODULE_NAME', 'woocommerce');
/**
 * Register activation module hook
 */
register_activation_hook(WOOCOMMERCE_MODULE_NAME, 'woocommerce_activation_hook');

$CI->load->helper(WOOCOMMERCE_MODULE_NAME . '/woocommerce');
$CI->load->helper(WOOCOMMERCE_MODULE_NAME . '/cron');
register_cron_task('woocommerce_cron');


hooks()->add_action('admin_init', 'woo_permissions');
hooks()->add_action('app_admin_footer', 'woocommerce_load_js');
hooks()->add_action('admin_init', 'woocommerce_init_menu_items');
hooks()->add_filter('module_woocommerce_action_links', 'module_woocommerce_action_links');

/**
 * Add additional settings for this module in the module list area
 * @param  array $actions current actions
 * @return array
 */
function module_woocommerce_action_links($actions)
{
    $actions[] = '<a href="' . admin_url('woocommerce/stores') . '">' . _l('settings') . '</a>';
    $actions[] = '<a href="https://www.boxvibe.com/support?envato_item_id=25337376" target="_blank">' . _l('help') . '</a>';

    return $actions;
}
function woocommerce_activation_hook()
{
    require_once(__DIR__ . '/install.php');
}

/**
 * Injects chat Javascript
 * @return null
 */
function woocommerce_load_js()
{
    echo '<script src="' . module_dir_url('woocommerce', 'assets/js/settings.js') . '"></script>';
}

function woocommerce_init_menu_items()
{
    if (has_permission('woocommerce', '', 'view')) {

        $CI = &get_instance();
        $CI->app_menu->add_sidebar_menu_item('woocommerce-menu', [
            'name'     => 'WooCommerce', // The name if the item
            'collapse' => true, // Indicates that this item will have submitems
            'position' => 11, // The menu position
            'icon'     => 'fa fa-cart-plus', // Font awesome icon
        ]);
        $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
            'slug'     => 'woo-orders',
            'name'     => _l('orders'),
            'href'     => admin_url('woocommerce/orders'),
            'position' => 11,
        ]);

        $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
            'slug'     => 'woo-customers',
            'name'     => _l('customers'),
            'href'     => admin_url('woocommerce/customers'),
            'position' => 13,
        ]);
        $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
            'slug'     => 'woo-products',
            'name'     => _l('products'),
            'href'     => admin_url('woocommerce/products'),
            'position' => 16,
        ]);
        if (is_admin()) {
            $CI->app_menu->add_sidebar_children_item('woocommerce-menu', [
                'slug'     => 'woo-products',
                'name'     => _l('stores'),
                'href'     => admin_url('woocommerce/stores'),
                'position' => 18,
            ]);
        }
    }
}

function woo_permissions()
{
    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    register_staff_capabilities('woocommerce', $capabilities, _l('woocommerce'));
}



