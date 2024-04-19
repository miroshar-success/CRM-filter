<?php defined('BASEPATH') or exit('No direct script access allowed');
/*
$table_data = array(
    _l('cl_type'),
    _l('cl_purpose_of_call'),
    _l('cl_caller'),
    _l('cl_contact'),
    _l('cl_start_time'),
    _l('cl_end_time'),
    _l('cl_duration'),
    _l('cl_call_follow_up'),
    _l('cl_is_important'),
    _l('cl_is_completed'),
);
*/
$table_data = array(
    _l('cl_type'),
    _l('cl_purpose_of_call'),
    _l('cl_caller'),
    _l('cl_contact'),
    _l('cl_start_time'),
    _l('cl_end_time'),
    _l('cl_duration'),
    _l('cl_call_follow_up'),
    _l('cl_is_important'),
    _l('cl_is_completed'),
    _l('cl_opt_event_type'),
    _l('cl_twilio_sms_response'),
);

$table_data = hooks()->apply_filters('call_logs_table_columns', $table_data);
render_datatable($table_data,(isset($class) ? $class : 'call_logs'));

?>
