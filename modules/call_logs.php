<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Call Logs
Description: Default module for defining call logs
Version: 1.2.1
Author: Weeb Digital
Author URI: https://weebdigital.com/
Requires at least: 2.3.*
*/

define('CALL_LOGS_MODULE_NAME', 'call_logs');

hooks()->add_action('after_cron_run', 'call_logs_notification');
hooks()->add_action('admin_init', 'call_logs_module_init_menu_items');
hooks()->add_action('admin_init', 'call_logs_permissions');
hooks()->add_filter('global_search_result_query', 'call_logs_global_search_result_query', 10, 3);
hooks()->add_filter('global_search_result_output', 'call_logs_global_search_result_output', 10, 2);
hooks()->add_filter('migration_tables_to_replace_old_links', 'call_logs_migration_tables_to_replace_old_links');

hooks()->apply_filters('get_goal_types', 'call_logs_set_goal_types');

function call_logs_set_goal_types($types)
{
        $types = [
            [
            'key'      => 8,
            'lang_key' => 'goal_type_call_logs',
            'subtext'  => 'goal_type_call_logs_subtext',
            ]
        ];

        return $types;
}
/* This function is used to grab the call logs records from the database. */
function call_logs_global_search_result_output($output, $data)
{
    if ($data['type'] == 'call_logs') {
        $output = '<a href="' . admin_url('call_logs/preview/' . $data['result']['id']) . '">' . $data['result']['call_purpose'] . '</a>';
    }

    return $output;
}
/* This function is creating a query for global result call. */
function call_logs_global_search_result_query($result, $q, $limit)
{
    $CI = &get_instance();
    if (has_permission('call_logs', '', 'view')) {
        // Goals
        $CI->db->select()->from(db_prefix() . 'call_logs')->like('call_summary', $q)->or_like('call_purpose', $q)->limit($limit);

        $CI->db->order_by('call_purpose', 'ASC');

        $result[] = [
                'result'         => $CI->db->get()->result_array(),
                'type'           => 'call_logs',
                'search_heading' => _l('call_logs'),
            ];
    }

    return $result;
}
/* This is just a migration to the module versions */
function call_logs_migration_tables_to_replace_old_links($tables)
{
    $tables[] = [
                'table' => db_prefix() . 'call_logs'
            ];

    return $tables;
}
/* This functions tie up Perfex CRM permission system with Call Log Module. */
function call_logs_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('call_logs', $capabilities, _l('call_logs'));
}
/* This function will be used to manage the call log follow up notifications. */
function call_logs_notification()
{
    $CI = &get_instance();
    $CI->load->model('call_logs/call_logs_model');
    $callLogs = $CI->call_logs_model->get_notifiable_logs();
    foreach ($callLogs as $callLog) {
        $CI->call_logs_model->notify_staff_members($callLog['id']);
    }

    $CI->call_logs_model->notify_staff_members_calls_goal_acheived();
}

/**
* Register activation module hook
*/
register_activation_hook(CALL_LOGS_MODULE_NAME, 'call_logs_module_activation_hook');

function call_logs_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register uninstall module hook
 */
register_uninstall_hook(CALL_LOGS_MODULE_NAME, 'call_logs_module_uninstall_hook');

function call_logs_module_uninstall_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/uninstall.php');
}


/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(CALL_LOGS_MODULE_NAME, [CALL_LOGS_MODULE_NAME]);

/**
 * Init module menu items in setup in admin_init hook
 * @return null
 */
function call_logs_module_init_menu_items()
{
    $CI = &get_instance();
    $CI->app_menu->add_sidebar_menu_item('call_logs_menu', [
        'name' => _l('call_logs'), // The name if the item
        'href' => admin_url('call_logs'), // URL of the item
        'position' => 10, // The menu position, see below for default positions.
        'icon' => 'fa fa-phone', // Font awesome icon
    ]);

    $CI->app_tabs->add_customer_profile_tab('call_logs', [
        'name'     => _l('call_logs'),
        'icon'     => 'fa fa-phone',
        'view'     => '../../modules/call_logs/views/admin/clients/groups/call_logs',
        'position' => 100,
    ]);

    if (is_admin()) {
        $CI->app_menu->add_setup_menu_item('call_logs', [
            'collapse' => true,
            'name' => _l('call_logs'),
            'position' => 10,
        ]);

        $CI->app_menu->add_setup_children_item('call_logs', [
            'slug' => 'call_logs-groups',
            'name' => _l('cl_type'),
            'href' => admin_url('call_logs/cl_types'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('call_logs', [
            'slug' => 'call_logs-groups',
            'name' => _l('call_log_direction'),
            'href' => admin_url('call_logs/call_directions'),
            'position' => 5,
        ]);
    }
}


/**
 * Get types for the feature
 * @return array
 */
function get_call_directions()
{
    $types = [
        [
            'key'      => 1,
            'lang_key' => 'call_log_direction_inbound',
            'label' => 'Inbound'
        ],
        [
            'key'      => 2,
            'lang_key' => 'call_log_direction_outbound',
            'label' => 'Outbound'
        ],
    ];

    return hooks()->apply_filters('get_call_directions', $types);
}
/**
 * Translate type based on passed key
 * @param  mixed $key
 * @return string
 */
function format_call_directions($key)
{
    foreach (get_call_directions() as $type) {
        if ($type['key'] == $key) {
            return _l($type['lang_key']);
        }
    }

    return $type;
}
/* This function will be used to display the customer/lead dropdown. */
function get_customer_types()
{
    $types = [
        [
            'key'      => 'customer',
            'lang_key' => 'Customers'
        ],
        [
            'key'      => 'leads',
            'lang_key' => 'Leads'
        ],
    ];

    return hooks()->apply_filters('get_customer_types', $types);
}

/* This function will be used to display the types dropdown. */
function get_related_to_types()
{
    $types = [
        [
            'key'      => 'proposal',
            'lang_key' => 'Proposal - Related',
            'is_default' => 1
        ],
        [
            'key'      => 'estimate',
            'lang_key' => 'Estimate - Related',
            'is_default' => 1
        ],
        [
            'key'      => 'general_call',
            'lang_key' => 'General Call',
            'is_default' => 0
        ],
        [
            'key'      => 'cold_calling',
            'lang_key' => 'Cold Calling',
            'is_default' => 0
        ],
        [
            'key'      => 'satisfaction_call',
            'lang_key' => 'Satisfaction Call',
            'is_default' => 0
        ],
        [
            'key'      => 'review_call',
            'lang_key' => 'Review Call',
            'is_default' => 0
        ],
        [
            'key'      => 'referral_call',
            'lang_key' => 'Referral Call',
            'is_default' => 0
        ],
    ];

    return hooks()->apply_filters('get_related_to_types', $types);
}
/* This function will be used to match the types field. */
function format_related_to_types($key)
{
    foreach (get_related_to_types() as $type) {
        if ($type['key'] == $key) {
            return _l($type['lang_key']);
        }
    }

    return $type;
}
/**
 * Load  helper
 */
$CI = &get_instance();
$CI->load->helper( 'call_logs/call_logs');