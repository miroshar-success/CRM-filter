<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
  Module Name: Elite Custom JS and CSS
  Version: 1.0.5
  Author: wpeliteplugins
  Author URI: http://wpeliteplugins.com/
  Description: This module allows admin to add multiple custom JS and CSS in admin area, client area and both with beautiful editor.
  Requires at least: 2.3.*
 */

define('ElITE_CUSTOM_JS_CSS_MODULE_NAME', 'elite_custom_js_css');
define('ElITE_CUSTOM_JS_CSS_TABLE_NAME', db_prefix() . 'elite_custom_js_css');
define('ElITE_CUSTOM_JS_CSS_PREFIX', 'elitecustomjscss');

$CI = &get_instance();

hooks()->add_action('admin_init', ElITE_CUSTOM_JS_CSS_MODULE_NAME . '_module_init_menu_items');
hooks()->add_filter('module_elite_custom_js_css_action_links', 'module_elite_custom_js_css_action_links');
hooks()->add_filter('global_search_result_query', ElITE_CUSTOM_JS_CSS_MODULE_NAME . '_global_search_result_query', 10, 3);
hooks()->add_filter('global_search_result_output', ElITE_CUSTOM_JS_CSS_MODULE_NAME . '_global_search_result_output', 10, 2);
hooks()->add_action('app_admin_head', ElITE_CUSTOM_JS_CSS_PREFIX . '_admin_area_css');
hooks()->add_action('app_customers_head', ElITE_CUSTOM_JS_CSS_PREFIX . '_clients_area_css');
hooks()->add_action('app_admin_footer', ElITE_CUSTOM_JS_CSS_PREFIX . '_admin_area_js');
hooks()->add_action('app_customers_footer', ElITE_CUSTOM_JS_CSS_PREFIX . '_clients_area_js');

// To render custom css on Web to Lead forms
hooks()->add_action('app_web_to_lead_form_head', ElITE_CUSTOM_JS_CSS_PREFIX . '_web_to_lead_area_css');
// To render custom js on Web to Lead forms
hooks()->add_action('app_web_to_lead_form_footer', ElITE_CUSTOM_JS_CSS_PREFIX . '_web_to_lead_area_js');

/**
 * Load the module helper
 */
$CI->load->helper(ElITE_CUSTOM_JS_CSS_MODULE_NAME . '/elite_custom_js_css');

/**
 * Add additional settings for this module in the module list area
 * @param  array $actions current actions
 * @return array
 */
function module_elite_custom_js_css_action_links($actions)
{
    $actions[] = '<a href="' . admin_url(ElITE_CUSTOM_JS_CSS_MODULE_NAME) . '/elite_custom_js">' . _l('settings') . '</a>';
    $actions[] = '<a href="http://documents.wpeliteplugins.com/elite-custom-js-and-css" target="_blank">' . _l('elite_document') . '</a>';
    return $actions;
}

/**
 * Register activation module hook
 */
register_activation_hook(ElITE_CUSTOM_JS_CSS_MODULE_NAME, 'elite_custom_js_css_module_activation_hook');

function elite_custom_js_css_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(ElITE_CUSTOM_JS_CSS_MODULE_NAME, [ElITE_CUSTOM_JS_CSS_MODULE_NAME]);

/**
 * Init kh custom js css module menu items in setup in admin_init hook
 * @return null
 */
function elite_custom_js_css_module_init_menu_items()
{
    if (is_admin()) {
        $CI = &get_instance();

        if ($CI->db->table_exists(ElITE_CUSTOM_JS_CSS_TABLE_NAME)) {
            if (!$CI->db->field_exists('code_view', ElITE_CUSTOM_JS_CSS_TABLE_NAME)) {
                $CI->db->query('ALTER TABLE `' . ElITE_CUSTOM_JS_CSS_TABLE_NAME . '` ADD code_view varchar(50) NOT NULL AFTER code_type;');
            }
        }

        $CI->app_menu->add_setup_menu_item(ElITE_CUSTOM_JS_CSS_MODULE_NAME, [
            'name' => _l('elite_custom_js_css'), // The name if the item
            'collapse' => true, // Indicates that this item will have submitems
            'position' => 65, // The menu position
        ]);

        // The first paremeter is the parent menu ID/Slug
        $CI->app_menu->add_setup_children_item(ElITE_CUSTOM_JS_CSS_MODULE_NAME, [
            'slug' => 'elite-custom-js', // Required ID/slug UNIQUE for the child menu
            'name' => _l('elite_custom_js'), // The name if the item
            'href' => admin_url(ElITE_CUSTOM_JS_CSS_MODULE_NAME . '/elite_custom_js'), // URL of the item
            'position' => 1, // The menu position
        ]);

        // The first paremeter is the parent menu ID/Slug
        $CI->app_menu->add_setup_children_item(ElITE_CUSTOM_JS_CSS_MODULE_NAME, [
            'slug' => 'elite-custom-css', // Required ID/slug UNIQUE for the child menu
            'name' => _l('elite_custom_css'), // The name if the item
            'href' => admin_url(ElITE_CUSTOM_JS_CSS_MODULE_NAME . '/elite_custom_css'), // URL of the item
            'position' => 2, // The menu position
        ]);
    }
}

/**
 * Global Search query result array
 * @return result array
 */
function elite_custom_js_css_global_search_result_query($result, $q, $limit)
{
    $CI = &get_instance();

    $CI->db->select('id,name,area_type,code_type')->from(ElITE_CUSTOM_JS_CSS_TABLE_NAME)->like('name', $q)->or_like('description', $q)->limit($limit);

    $CI->db->order_by('name', 'ASC');

    $result[] = [
        'result' => $CI->db->get()->result_array(),
        'type' => ElITE_CUSTOM_JS_CSS_MODULE_NAME,
        'search_heading' => _l(ElITE_CUSTOM_JS_CSS_MODULE_NAME),
    ];

    return $result;
}

/**
 * Global Search query result
 * @return result link
 */
function elite_custom_js_css_global_search_result_output($output, $data)
{
    if ($data['type'] == ElITE_CUSTOM_JS_CSS_MODULE_NAME) {
        if ($data['result']['code_type'] == 'js') {
            $contollername = 'elite_custom_js';
        } else {
            $contollername = 'elite_custom_css';
        }
        $output = '<a href="' . admin_url(ElITE_CUSTOM_JS_CSS_MODULE_NAME . '/' . $contollername . '/form/' . $data['result']['id']) . '">' . $data['result']['name'] . ' (' . strtoupper($data['result']['area_type']) . ')</a>';
    }

    return $output;
}

/**
 * To add css in admin area
 * @return null
 */
function elitecustomjscss_admin_area_css()
{
    elitecustomjscss_style(ElITE_CUSTOM_JS_CSS_PREFIX . '_admin_area_style');
}

/**
 * To add css in clients area
 * @return null
 */
function elitecustomjscss_clients_area_css()
{
    elitecustomjscss_style(ElITE_CUSTOM_JS_CSS_PREFIX . '_clients_area_style');
}

/**
 * Based on area type, render custom CSS
 * @param  string $elite_area clients or admin area options
 * @return null
 */
function elitecustomjscss_style($elite_area)
{
    require_once(__DIR__ . '/views/elite_custom_css_load.php');
}

/**
 * To add custom JS in admin area
 * @return null
 */
function elitecustomjscss_admin_area_js()
{
    elitecustomjscss_script(ElITE_CUSTOM_JS_CSS_PREFIX . '_admin_area_script');
}

/**
 * To add custom JS in clients area
 * @return null
 */
function elitecustomjscss_clients_area_js()
{
    elitecustomjscss_script(ElITE_CUSTOM_JS_CSS_PREFIX . '_clients_area_script');
}

/**
 * Based on area type, render custom JS
 * @param  string $main_area clients or admin area options
 * @return null
 */
function elitecustomjscss_script($elite_area)
{
    require_once(__DIR__ . '/views/elite_custom_js_load.php');
}

/**
 * To add css in clients area
 * @return null
 */
function elitecustomjscss_web_to_lead_area_css()
{
    elitecustomjscss_style('web_to_lead_area_css');
}

/**
 * To add custom JS in clients area
 * @return null
 */
function elitecustomjscss_web_to_lead_area_js()
{
    elitecustomjscss_script('web_to_lead_area_js');
}