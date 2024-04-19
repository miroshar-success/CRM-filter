<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Reminder
Description: create custom reminder and notify
Version: 1.0.3
Author: Zonvoir
Author URI: https://zonvoir.com/
Requires at least: 2.3.*
*/
if (!defined('MODULE_REMINDER')) {
    define('MODULE_REMINDER', basename(__DIR__));
}
if (!defined('APP_DISABLE_CRON_LOCK')) {
    define('APP_DISABLE_CRON_LOCK', true);
}
hooks()->add_action('admin_init', 'reminder_module_init_menu_items');
hooks()->add_action('admin_init', 'reminder_permissions');
hooks()->add_action('admin_init', 'reminder_module_activation_hook');
hooks()->add_filter('staff_reminder_merge_fields','reminder_send_with_subject', 10, 2);

hooks()->add_action('after_cron_run','contact_reminder_send');
function contact_reminder_send()
{ 
    $CI = &get_instance();
    $CI->load->library('reminder/mails/reminder_mail_template');
    $CI->db->select(db_prefix() . 'reminders.*, '.db_prefix() .'contacts.email, '.db_prefix() .'contacts.phonenumber, '.db_prefix() .'contacts.firstname, '.db_prefix() .'contacts.lastname');
    $CI->db->from(db_prefix() . 'reminders');
    $CI->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.id=' . db_prefix() . 'reminders.contact');
    $CI->db->where(db_prefix() .'reminders.notify_by_email_client', '2');
    $CI->db->or_where(db_prefix() .'reminders.notify_by_sms_client', '2');
    $reminders = $CI->db->get()->result_array();
    $notifiedUsers = [];
    if(isset($reminders) && !empty($reminders)){
        foreach ($reminders as $reminder) {
            if (strtotime(date('Y-m-d H:i:s')) >= strtotime($reminder['date'])) {
                if($reminder['notify_by_email_client'] == '2'){
                    $CI->db->where('id', $reminder['id']);
                    $CI->db->update(db_prefix() . 'reminders', [
                        'notify_by_email_client' => '1',
                    ]);    
                }if($reminder['notify_by_sms_client'] == '2'){
                    $CI->db->where('id', $reminder['id']);
                    $CI->db->update(db_prefix() . 'reminders', [
                        'notify_by_sms_client' => '1',
                    ]);
                }
                $rel_data   = get_relation_data($reminder['rel_type'], $reminder['rel_id']);
                $rel_values = get_relation_values($rel_data, $reminder['rel_type']);
                $notificationLink = str_replace(admin_url(), '', $rel_values['link']);
                $notificationLink = ltrim($notificationLink, '/');
                $notified = add_notification([
                    'fromcompany'     => true,
                    'touserid'        => $reminder['contact'],
                    'description'     => 'not_new_reminder_for',
                    'link'            => $notificationLink,
                    'additional_data' => serialize([
                        $rel_values['name'] . ' - ' . strip_tags(mb_substr($reminder['description'], 0, 50)) . '...',
                    ]),
                ]);
                if ($notified) {
                    array_push($notifiedUsers, $reminder['contact']);
                }
                if ($reminder['notify_by_email_client'] == '2') {
                    $template = reminder_mail_template('contact_send_reminder', $reminder);
                    $template->send();
                }if($reminder['notify_by_sms_client'] == '2'){
                    $resposnse = send_sms_reminder($reminder['phonenumber'], $reminder['description']);
                }
            }
        }
    }
    pusher_trigger_notification($notifiedUsers);
}
function reminder_send_with_subject($fields, $data) {
    if(!empty($data['reminder'])) {
        $reminder = $data['reminder'];
        $rel_type = !empty($reminder->rel_type) ? $reminder->rel_type : '';
        if ($rel_type != '') {
            if($rel_type == "custom_reminder"){
                $fields['{related_name}'] = 'Custom Reminder'; 
                $fields['{related_number}'] =  admin_url('reminder');
            }
            return $fields;
        }
    }
}
function reminder_permissions() {
    $capabilities = [];
    $capabilities['capabilities'] = [
        'view_own' => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    if (function_exists('register_staff_capabilities')) {
        register_staff_capabilities('reminder', $capabilities, _l('reminder'));
    }
}
/** 
 * Register activation module hook
 */
register_activation_hook(MODULE_REMINDER, 'reminder_module_activation_hook');
function reminder_module_activation_hook() {
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}
/**
 * Register uninstall module hook
 */
register_uninstall_hook(MODULE_REMINDER, 'reminder_module_uninstall_hook');
function reminder_module_uninstall_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/uninstall.php');
}
get_instance()->load->helper(MODULE_REMINDER . '/reminder');
register_language_files(MODULE_REMINDER, [MODULE_REMINDER]);
function reminder_module_init_menu_items() {
    $CI = &get_instance();
    $CI->app_menu->add_sidebar_menu_item('reminder', [
        'slug'     => 'reminder',
        'name'     => _l('reminder'),
        'position' => 6,
        'icon'     => 'fa fa-calendar menu-icon',
        'href'     => admin_url('reminder')
    ]);
}
hooks()->add_filter('available_merge_fields',function($available){
    $CI = &get_instance();
    $reminder_available = get_available_reminder_merge_fields();
    $rm_fields = [];
    foreach ($reminder_available as $rm_key => $rm_merge_fields) {
        if (array_key_exists('reminder', $rm_merge_fields)) {
            $rm_fields = $reminder_available[$rm_key];
        }
    }
    $uri = $CI->uri->uri_to_assoc(1);
    if(isset($uri) && !empty($uri)){
        if(isset($uri['admin']) && $uri['admin'] == 'emails' && is_numeric($uri['email_template'])){
            $template = get_email_template_row($uri['email_template']);
            if(isset($template) && !empty($template) && $template->slug == 'reminder-send-to-contact'){
                $key = null;
                foreach ($available as $key => $merge_fields) {
                    if (array_key_exists('client', $merge_fields)) {
                        $available[$key]['client'] = $rm_fields['reminder'];
                    }
                }
            }
        }
    }
    return $available;
});

