<?php
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
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'call_logs';
$where        = [];
// Add blank where all filter can be stored
$filter = [];
$join = [];

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $key . ' ON '.db_prefix().'clients.userid = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$join = hooks()->apply_filters('customers_table_sql_join', $join);

// Filter by Staff
$staffs   = $CI->staff_model->get();
$staffIds = [];
foreach ($staffs as $staff) {
    if ($CI->input->post('staffid_' . $staff['staffid'])) {
        array_push($staffIds, $staff['staffid']);
    }
}
if (count($staffIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'call_logs.staffid IN (' . implode(', ', $staffIds) . ')');
}

// Filter by rel type
$rel_types   = get_related_to_types();
$rel_typeIds = [];
foreach ($rel_types as $rel_type) {
    if ($CI->input->post('rel_type_' . $rel_type['key'])) {
        array_push($rel_typeIds, '"'.$rel_type['key'].'"');
    }
}

if (count($rel_typeIds) > 0) {
    array_push($filter, 'AND '.db_prefix().'call_logs.rel_type IN (' . implode(', ', $rel_typeIds) . ')');
}


// Filter by leads
$CI->load->model('leads_model');
$leads   = $CI->leads_model->get();
$leadIds = [];
foreach ($leads as $lead) {
    if ($CI->input->post('lead_' . $lead['id'])) {
        array_push($leadIds, $lead['id']);
    }
}

if (count($leadIds) > 0) {
    array_push($filter, 'AND ('.db_prefix().'call_logs.customer_type="lead"  AND '.db_prefix().'call_logs.clientid IN (' . implode(', ', $leadIds) . '))');
}

// Filter by customers
$CI->load->model('clients_model');
$customers   = $CI->clients_model->get();
$customerIds = [];
foreach ($customers as $customer) {
    if ($CI->input->post('client_' . $customer['userid'])) {
        array_push($customerIds, $customer['userid']);
    }
}

if (count($customerIds) > 0) {
    array_push($filter, 'AND ('.db_prefix().'call_logs.customer_type="customer"  AND '.db_prefix().'call_logs.clientid IN (' . implode(', ', $customerIds) . '))');
}

if ($clientid != '' && $customer_type == 'lead') {
    array_push($filter, 'AND ('.db_prefix().'call_logs.customer_type="lead"  AND '.db_prefix().'call_logs.clientid = ' . $this->ci->db->escape_str($clientid) . ')');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}
if ($CI->input->post('my_call_logs')) {
    array_push($where, 'AND '.db_prefix().'call_logs.staffid = ' . get_staff_user_id());
}

$aColumns = hooks()->apply_filters('call_logs_table_sql_columns', $aColumns);
// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$CI->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'call_purpose') {
            $_data = '<a href="' . admin_url('call_logs/call_log/' . $aRow['id']) . '">' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('call_logs/call_log/' . $aRow['id']) . '">' . _l('view') . '</a>';

            if (has_permission('goals', '', 'delete')) {
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
                }
                $_data = $contactName.$oClient->company;
            }else{
                $oClient = $this->ci->clients_model->get($aRow['clientid']);
                $oCustomer = $this->ci->clients_model->get_contacts($oClient->userid, ['is_primary' => true]);
                $contactName = '';
                if(isset($oCustomer[0])){
                    $pic = '<img src="'.contact_profile_image_url($oCustomer[0]['id']).'" class="img img-responsive staff-profile-image-small pull-left">';

                    $contactName = $pic.'<br>'.$oCustomer[0]['firstname']. ' '.  $oCustomer[0]['lastname'].'<br>';
                }
                $_data = $contactName.$oClient->company;
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
