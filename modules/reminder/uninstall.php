<?php
defined('BASEPATH') or exit('No direct script access allowed');
delete_option('reminder_queries_run');
$CI = &get_instance();
if ($CI->db->table_exists(db_prefix() . 'reminder_activity')) {
  $CI->db->query('DROP TABLE `' . db_prefix() . 'reminder_activity`');
}
if ($CI->db->field_exists('notify_by_email_client', db_prefix() . 'reminders')) {
  $CI->db->query("ALTER TABLE `".db_prefix()."reminders` DROP COLUMN notify_by_email_client");
}
if($CI->db->field_exists('created_by_staff', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `created_by_staff`");
}
if($CI->db->field_exists('contact', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `contact`");
}
if($CI->db->field_exists('customer', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `customer`");
}
if($CI->db->field_exists('repeat_every', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `repeat_every`");
}
if($CI->db->field_exists('cycles', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `cycles`");
}
if($CI->db->field_exists('recurring_type', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `recurring_type`");
}
if($CI->db->field_exists('recurring', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `recurring`");
}
if($CI->db->field_exists('is_recurring_from', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `is_recurring_from`");
}
if($CI->db->field_exists('custom_recurring', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `custom_recurring`");
}
if($CI->db->field_exists('total_cycles', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `total_cycles`");
}
if($CI->db->field_exists('last_recurring_date', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `last_recurring_date`");
}
if($CI->db->field_exists('startdate', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `startdate`");
}
if($CI->db->field_exists('duedate', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `duedate`");
}
if($CI->db->field_exists('is_complete', db_prefix() . 'reminders')){
  $CI->db->query("ALTER TABLE `" .db_prefix() . "reminders` DROP `is_complete`");
}
$emailtemplates = $CI->db->query('SELECT * FROM '.db_prefix() . 'emailtemplates where slug = "reminder-send-to-contact";')->result_array();
if(isset($emailtemplates) && !empty($emailtemplates)){
  foreach($emailtemplates as $template){
    $CI->db->where('emailtemplateid',$template['emailtemplateid']);
    $CI->db->delete(db_prefix() . 'emailtemplates');
  }
}