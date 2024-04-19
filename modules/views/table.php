<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI          = & get_instance();
$custom_fields = get_table_custom_fields('call_logs');

$CI->db->query("SET sql_mode = ''");
$aColumns = [
    'customer_type',
    'call_purpose',
    'staffid',
    'clientid',
    'call_start_time',
    'call_end_time',
    'call_duration',
    'has_follow_up',
    'is_important',
    'is_completed',
    'opt_event_type',
    'twilio_sms_response'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'call_logs';
$where        = [];
// Add blank where all filter can be stored
$filter = [];
$join = [
    'JOIN ' . db_prefix() . 'call_logs_rel_types ON ' . db_prefix() . 'call_logs_rel_types.id = ' . db_prefix() . 'call_logs.rel_type',
    'JOIN ' . db_prefix() . 'call_logs_directions ON ' . db_prefix() . 'call_logs_directions.id = ' . db_prefix() . 'call_logs.call_direction',
];

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $key . ' ON '.db_prefix().'clients.userid = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

//$join = hooks()->apply_filters('call_logs_table_sql_join', $join);

// Filter by Staff
$staffIds = [];
if ($CI->input->post('assigned' )) {
    array_push($staffIds, $CI->input->post('assigned' ));
}

if (count($staffIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'call_logs.staffid IN (' . implode(', ', $staffIds) . ')');
}

// Filter by rel type
$rel_typeIds = [];
if ($CI->input->post('view_by_rel_type')) {
    array_push($rel_typeIds, '"'.$CI->input->post('view_by_rel_type').'"');
}

if (count($rel_typeIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'call_logs.rel_type IN (' . implode(', ', $rel_typeIds) . ')');
}

// Filter by leads
$leadIds = [];
if ($CI->input->post('view_by_lead')) {
    array_push($leadIds, $CI->input->post('view_by_lead'));
}

if (count($leadIds) > 0) {
    array_push($filter, 'AND ('.db_prefix().'call_logs.customer_type="lead"  AND '.db_prefix().'call_logs.clientid IN (' . implode(', ', $leadIds) . '))');
}

// Filter by customers
$customerIds = [];
if ($CI->input->post('view_by_customer')) {
    array_push($customerIds, $CI->input->post('view_by_customer'));
}

if (count($customerIds) > 0) {
    array_push($filter, 'AND ('.db_prefix().'call_logs.customer_type="customer"  AND '.db_prefix().'call_logs.clientid IN (' . implode(', ', $customerIds) . '))');
}

// Filter by status: complete, Incomplete
$isCallCompleteArr = [];
if ($CI->input->post('view_by_status')) {
    array_push($isCallCompleteArr, ($CI->input->post('view_by_status') ==1)?1:0);
}

if (count($isCallCompleteArr) > 0) {
    array_push($filter, 'AND ('.db_prefix().'call_logs.is_completed IN (' . implode(', ', $isCallCompleteArr) . '))');
}

if (isset($clientid) && $clientid != '') {
    array_push($filter, 'AND ('.db_prefix().'call_logs.customer_type="customer"  AND '.db_prefix().'call_logs.clientid = ' . $this->ci->db->escape_str($clientid) . ')');

}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}
if ($CI->input->post('my_call_logs')) {
    array_push($where, 'AND '.db_prefix().'call_logs.staffid = ' . get_staff_user_id());
}

if (!has_permission('call_logs', '', 'view')) {
    array_push($where, 'AND '.db_prefix().'call_logs.staffid = ' . get_staff_user_id());
}

$aColumns = hooks()->apply_filters('call_logs_table_sql_columns', $aColumns);
// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$CI->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'call_logs.id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'opt_event_type') {
            $_data = ucfirst($aRow[$aColumns[$i]]);
        }
        if ($aColumns[$i] == 'call_purpose') {
            $hrefAttr = 'href="' . admin_url('call_logs/call_log/index/' . $aRow['id']) . '" onclick="init_call_log_modal(' . $aRow['id'] . ');return false;"';

            $_data = '<a href="' . admin_url('call_logs/preview/' . $aRow['id']) . '" >' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a ' . $hrefAttr . '>' . _l('view') . '</a>';

            if($aRow['is_completed'] == 0){
                $_data .= ' | <a href="' . admin_url('call_logs/call_log/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }

            if (has_permission('call_logs', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('call_logs/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'call_start_time' || $aColumns[$i] == 'call_end_time') {
            $_data = _d($_data);
        } elseif ($aColumns[$i] == 'call_type') {
            $_data = format_call_directions($_data);
        }elseif ($aColumns[$i] == 'customer_type') {
            $_data = ucwords($_data);
        }elseif ($aColumns[$i] == 'has_follow_up') {
            $val = ($_data == 0)?"":"YES";
            $_data = $val.' '._l('cl_has_followup_'. $_data);
        }elseif ($aColumns[$i] == 'is_important') {
            $val = ($_data == 0)?"":"YES";
            $_data = $val. ' '. _l('cl_is_important_'. $_data);
        }elseif ($aColumns[$i] == 'is_completed') {
            $val = ($_data == 0)?"":"YES";
            $_data = $val.' '. _l('cl_is_completed_'. $_data);
        }elseif ($aColumns[$i] == 'staffid') {
            $oStaff = $this->ci->staff_model->get($_data);
            $_data =  staff_profile_image($oStaff->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')). '<a href="'.admin_url('profile/'.$oStaff->staffid).'">'.$oStaff->firstname.' '. $oStaff->lastname. '</a><br>';
        }elseif ($aColumns[$i] == 'clientid') {
            if($aRow['customer_type'] == 'lead'){
                $CI = & get_instance();
                $CI->load->model('leads_model');
                $oClient =  $CI->leads_model->get($_data);
                $contactName = '';

                if(isset($oClient)){
                    $pic ='';// '<img src="'.contact_profile_image_url($oCustomer[0]['id']).'" class="img img-responsive staff-profile-image-small pull-left">';

                    $contactName = $pic.'<a href="'.admin_url('leads/index/'.$oClient->id).'">'.$oClient->name.'</a><br>';
                    $_data = $contactName.$oClient->company;
                }
                

            }else{
                $oClient = $this->ci->clients_model->get($aRow['clientid']);
                if(!empty($oClient)){
                   $oCustomer = $this->ci->clients_model->get_contacts($oClient->userid, ['is_primary' => true]);
                    $contactName = '';
                    if(isset($oCustomer[0])){
                        $pic = '<img src="'.contact_profile_image_url($oCustomer[0]['id']).'" class="img img-responsive staff-profile-image-small pull-left">';
                        $contactName = $pic.'<br><a href="'.admin_url('clients/client/'.$aRow['clientid'].'?group=contacts&contactid='.$oCustomer[0]['id']).'" data-id="'.$oCustomer[0]['id'].'">'.$oCustomer[0]['firstname']. ' '.  $oCustomer[0]['lastname'].'</a><br>';
                    }
                    $_data = $contactName.$oClient->company; 
                }
                
            }

        }elseif($aColumns[$i] == 'twilio_sms_response'){
            if(!empty($_data)){
                if($_data == 'Failed'){
                    $_data = '<span class="label label-danger" style="background-color: #d9534f;color: white; width: 80px;
                    display: inline-block;
                    padding: 6px;">'. _l('cl_sms_status_failed') .'</span>';
                }elseif ($_data == 'Sent') {
                    $_data = '<span class="label label-success" style="background-color: #84c529;color: white; width: 80px;
                    display: inline-block;
                    padding: 6px;">'. _l('cl_sms_status_sent') .'</span>';
                }elseif ($_data == 'n/a') {
                    $_data = '<span class="label label-default" style="background-color: #777;color: white; width: 80px;
                    display: inline-block;
                    padding: 6px;">'.$_data.'</span>';
                }elseif ($_data == 'Completed') {
                    $_data = '<span class="label label-success" style="background-color: #84c529;color: white; width: 80px;
                    display: inline-block;
                    padding: 6px;">'. _l('cl_call_status_completed') .'</span>';
                }elseif ($_data == 'Busy') {
                    $_data = '<span class="label label-info" style="background-color: #5bc0de;color: white; width: 80px;
                    display: inline-block;
                    padding: 6px;">'. _l('cl_call_status_busy') .'</span>';
                }elseif ($_data == 'No Answer') {
                    $_data = '<span class="label label-default" style="background-color: #777;color: white; width: 80px;
                    display: inline-block;
                    padding: 6px;">'. _l('cl_call_status_no_answer') .'</span>';
                }elseif($_data == 'Cancelled'){
                    $_data = '<span class="label label-danger" style="background-color: #d9534f;color: white; width: 80px;
                    display: inline-block;
                    padding: 6px;">'. _l('cl_call_status_cancelled') .'</span>';
                }
            }
        }
        $row[] = $_data;
    }
    ob_start();
    ?>

    <?php
    $progress = ob_get_contents();
    ob_end_clean();
    $row[]              = $progress;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
