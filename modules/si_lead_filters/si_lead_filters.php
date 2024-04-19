<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: SI Lead Filters
Description: Module will Generate Filters for Lead and save filters as Templates for future use.
Author: Sejal Infotech
Version: 1.1.5
Requires at least: 2.3.*
Author URI: http://www.sejalinfotech.com
*/

define('SI_LEAD_FILTERS_MODULE_NAME', 'si_lead_filters');
define('SI_LEAD_FILTERS_MAX_CUSTOM_FIELDS', 9);

$CI = &get_instance();

hooks()->add_action('admin_init', 'si_lead_filters_init_menu_items');
hooks()->add_action('admin_init', 'si_lead_filters_permissions');

/**
* Load the module helper
*/
$CI->load->helper(SI_LEAD_FILTERS_MODULE_NAME . '/si_lead_filters');

/**
* Load the module Model
*/
$CI->load->model(SI_LEAD_FILTERS_MODULE_NAME . '/si_lead_filter_model');

/**
* Register activation module hook
*/
register_activation_hook(SI_LEAD_FILTERS_MODULE_NAME, 'si_lead_filters_activation_hook');

function si_lead_filters_activation_hook()
{
	$CI = &get_instance();
	require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(SI_LEAD_FILTERS_MODULE_NAME, [SI_LEAD_FILTERS_MODULE_NAME]);

/**
 * Init menu setup module menu items in setup in admin_init hook
 * @return null
 */
function si_lead_filters_init_menu_items()
{
	$CI = &get_instance();
	
	/**Sidebar menu */
	if (is_admin() || has_permission('si_lead_filters', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('lead-filters', [
			'collapse'	=> true,
			'icon'		=> 'fa fa-filter',
			'name'		=> _l('si_lead_filters_menu'),
			'position'	=> 35,
		]);
		$CI->app_menu->add_sidebar_children_item('lead-filters', [
			'slug'		=> 'si-lead-filter-options',
			'name'		=> _l('si_lf_submenu_lead_filters'),
			'href'		=> admin_url('si_lead_filters/leads_filter'),
			'position'	=> 1,
		]);
		$CI->app_menu->add_sidebar_children_item('lead-filters', [
			'slug'		=> 'si-lead-tmplate-options',
			'name'		=> _l('si_lf_submenu_filter_templates'),
			'href'		=> admin_url('si_lead_filters/list_filters'),
			'position'	=> 2,
		]);
		if(has_permission('si_lead_filters', '', 'view')){
			$CI->app_menu->add_sidebar_children_item('lead-filters', [
				'slug'		=> 'si-lead-settings-options',
				'name'		=> _l('settings'),
				'href'		=> admin_url('si_lead_filters/settings'),
				'position'	=> 3,
			]);
		}
	}
}
function si_lead_filters_permissions()
{
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'settings' => _l('settings'),
	];
	register_staff_capabilities('si_lead_filters', $capabilities, _l('si_lead_filters'));
}
