<?php

defined('BASEPATH') or exit('No direct script access allowed');

$hasPermissionDelete = has_permission('customers', '', 'delete');

$custom_fields = get_table_custom_fields('customers');
$this->ci->db->query("SET sql_mode = ''");

$aColumns = [
    '1',
    db_prefix() . 'clients.userid as userid',
    'company',
    'CONCAT(firstname, " ", lastname) as fullname',
    'email',
    db_prefix() . 'clients.phonenumber as phonenumber',
    db_prefix() . 'clients.active',
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'customer_groups JOIN ' . db_prefix() . 'customers_groups ON ' . db_prefix() . 'customer_groups.groupid = ' . db_prefix() . 'customers_groups.id WHERE customer_id = ' . db_prefix() . 'clients.userid ORDER by name ASC) as customerGroups',
    db_prefix() . 'clients.datecreated as datecreated',
];

$sIndexColumn = 'userid';
$sTable       = db_prefix() . 'clients';
$where        = [];
// Add blank where all filter can be stored
$filter = [];

$join = [
    'LEFT JOIN ' . db_prefix() . 'contacts ON ' . db_prefix() . 'contacts.userid=' . db_prefix() . 'clients.userid AND ' . db_prefix() . 'contacts.is_primary=1',
];

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'clients.userid = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$join = hooks()->apply_filters('customers_table_sql_join', $join);

// Filter by custom groups
$groups   = $this->ci->clients_model->get_groups();
$groupIds = [];
foreach ($groups as $group) {
    if ($this->ci->input->post('customer_group_' . $group['id'])) {
        array_push($groupIds, $group['id']);
    }
}
if (count($groupIds) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_groups WHERE groupid IN (' . implode(', ', $groupIds) . '))');
}

if (!empty($groups_in) && count($groups_in) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_groups WHERE groupid IN (' . implode(', ', $groups_in) . '))');
}

$countries  = $this->ci->clients_model->get_clients_distinct_countries();
$countryIds = [];
foreach ($countries as $country) {
    if ($this->ci->input->post('country_' . $country['country_id'])) {
        array_push($countryIds, $country['country_id']);
    }
}
if (count($countryIds) > 0) {
    array_push($filter, 'AND country IN (' . implode(',', $countryIds) . ')');
}


$this->ci->load->model('invoices_model');
// Filter by invoices
$invoiceStatusIds = [];
foreach ($this->ci->invoices_model->get_statuses() as $status) {
    if ($this->ci->input->post('invoices_' . $status)) {
        array_push($invoiceStatusIds, $status);
    }
}
if (count($invoiceStatusIds) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.userid IN (SELECT clientid FROM ' . db_prefix() . 'invoices WHERE status IN (' . implode(', ', $invoiceStatusIds) . '))');
}

// Filter by estimates
$estimateStatusIds = [];
$this->ci->load->model('estimates_model');
foreach ($this->ci->estimates_model->get_statuses() as $status) {
    if ($this->ci->input->post('estimates_' . $status)) {
        array_push($estimateStatusIds, $status);
    }
}
if (count($estimateStatusIds) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.userid IN (SELECT clientid FROM ' . db_prefix() . 'estimates WHERE status IN (' . implode(', ', $estimateStatusIds) . '))');
}

// Filter by projects
$projectStatusIds = [];
$this->ci->load->model('projects_model');
foreach ($this->ci->projects_model->get_project_statuses() as $status) {
    if ($this->ci->input->post('projects_' . $status['id'])) {
        array_push($projectStatusIds, $status['id']);
    }
}
if (count($projectStatusIds) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.userid IN (SELECT clientid FROM ' . db_prefix() . 'projects WHERE status IN (' . implode(', ', $projectStatusIds) . '))');
}

// Filter by proposals
$proposalStatusIds = [];
$this->ci->load->model('proposals_model');
foreach ($this->ci->proposals_model->get_statuses() as $status) {
    if ($this->ci->input->post('proposals_' . $status)) {
        array_push($proposalStatusIds, $status);
    }
}
if (count($proposalStatusIds) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.userid IN (SELECT rel_id FROM ' . db_prefix() . 'proposals WHERE status IN (' . implode(', ', $proposalStatusIds) . ') AND rel_type="customer")');
}

// Filter by having contracts by type
$this->ci->load->model('contracts_model');
$contractTypesIds = [];
$contract_types   = $this->ci->contracts_model->get_contract_types();

foreach ($contract_types as $type) {
    if ($this->ci->input->post('contract_type_' . $type['id'])) {
        array_push($contractTypesIds, $type['id']);
    }
}
if (count($contractTypesIds) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.userid IN (SELECT client FROM ' . db_prefix() . 'contracts WHERE contract_type IN (' . implode(', ', $contractTypesIds) . '))');
}

// Filter by proposals
$customAdminIds = [];
foreach ($this->ci->clients_model->get_customers_admin_unique_ids() as $cadmin) {
    if ($this->ci->input->post('responsible_admin_' . $cadmin['staff_id'])) {
        array_push($customAdminIds, $cadmin['staff_id']);
    }
}

if (count($customAdminIds) > 0) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_admins WHERE staff_id IN (' . implode(', ', $customAdminIds) . '))');
}

if ($this->ci->input->post('requires_registration_confirmation')) {
    array_push($filter, 'AND ' . db_prefix() . 'clients.registration_confirmed=0');
}

if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

if (!has_permission('customers', '', 'view')) {
    array_push($where, 'AND ' . db_prefix() . 'clients.userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_admins WHERE staff_id=' . get_staff_user_id() . ')');
}

if ($this->ci->input->post('exclude_inactive')) {
    array_push($where, 'AND (' . db_prefix() . 'clients.active = 1 OR ' . db_prefix() . 'clients.active=0 AND registration_confirmed = 0)');
}

if ($this->ci->input->post('my_customers')) {
    array_push($where, 'AND ' . db_prefix() . 'clients.userid IN (SELECT customer_id FROM ' . db_prefix() . 'customer_admins WHERE staff_id=' . get_staff_user_id() . ')');
}

//filter by country
if(!isset($countries_) || empty($countries_))
	$countries_=array('');
if ($countries_ && !in_array('',$countries_) && count($countries_) > 0) {
	if(in_array(-1,$countries_))//if country is unknown
		$countries_[]=0;
    array_push($where, 'AND country IN (' . implode(',', $countries_) . ')');
}

//filter by state
if(!isset($states) || empty($states))
	$states=array('');
if ($states && !in_array('',$states) && count($states) > 0) {
    $where_state	= 'state IN ("' . implode('","', $states).'")';
	if(in_array(-1,$states))//if state is unknown
		$where_state.= ' OR state=\'\' or state IS NULL';
	array_push($where, 'AND ('. $where_state.')');
}

//filter by city
if(!isset($cities) || empty($cities))
	$cities=array('');
if ($cities && !in_array('',$cities) && count($cities) > 0) {
    $where_city	= 'city IN ("' . implode('","', $cities).'")';
	if(in_array(-1,$cities))//if city is unknown
		$where_city.= ' OR city=\'\' or city IS NULL';
	array_push($where, 'AND ('. $where_city.')');
}

//filter by zip
if(!isset($zips) || empty($zips))
	$zips=array('');
if ($zips && !in_array('',$zips) && count($zips) > 0) {
    $where_zip	= 'zip IN ("' . implode('","', $zips).'")';
	if(in_array(-1,$zips))//if zip is unknown
		$where_zip.= ' OR zip=\'\' or zip IS NULL';
	array_push($where, 'AND ('. $where_zip.')');
}

//filter by dates
if(isset($custom_date_select) && $custom_date_select != '') {
	array_push($where, $custom_date_select);
};

//filter by custom fields
if(!empty($cf)){
	foreach($cf as $_cf=>$value){
		array_push($where, 'AND '.db_prefix() . 'clients.userid in (SELECT relid FROM '.db_prefix() . 'customfieldsvalues  where fieldid='.$_cf.' and value in ("'.implode('","',$value).'"))');
	}
}
// filter by upcoming field
if (!empty($upcoming_from) && !empty($upcoming_to) && !empty($upcoming_fieldid)) {
    $timestamp_f = strtotime(str_replace('/', '-', $upcoming_from));
    $timestamp_t = strtotime(str_replace('/', '-', $upcoming_to));
    $formattedDate_f = date('Y-m-d', $timestamp_f);
    $formattedDate_t = date('Y-m-d', $timestamp_t);
    $where[] = 'AND ' . db_prefix() . 'clients.userid IN (SELECT relid FROM ' . db_prefix() . 'customfieldsvalues WHERE fieldid = ' . $upcoming_fieldid . ' AND fieldto = "customers" AND STR_TO_DATE(value, "%Y-%m-%d") BETWEEN "' . $formattedDate_f . '" AND "' . $formattedDate_t . '")';
}
// filter by Credit Max field
if (!empty($creditMax_from) && !empty($creditMax_to && !empty($creditMax_fieldid))) {
    $where[] = 'AND ' . db_prefix() . 'clients.userid IN (
        SELECT relid
        FROM ' . db_prefix() . 'customfieldsvalues
        WHERE fieldid = ' . $creditMax_fieldid . '
            AND fieldto = "customers"
            AND CAST(SUBSTRING_INDEX(value, "€", -1) AS DECIMAL(10, 2)) BETWEEN ' . $creditMax_from . ' AND ' . $creditMax_to . '
    )';
}
// filter by Credit Score field
if (!empty($creditScore_from) && !empty($creditScore_to && !empty($creditScore_fieldid))) {
    $where[] = 'AND ' . db_prefix() . 'clients.userid IN (SELECT relid FROM ' . db_prefix() . 'customfieldsvalues WHERE fieldid=' . $creditScore_fieldid . ' AND fieldto = "customers" AND value BETWEEN ' . $creditScore_from . ' AND ' . $creditScore_to . ')';
}

$aColumns = hooks()->apply_filters('customers_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'contacts.id as contact_id',
    'lastname',
    db_prefix() . 'clients.zip as zip',
    'registration_confirmed',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // Bulk actions
    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['userid'] . '"><label></label></div>';
    // User id
    $row[] = $aRow['userid'];

    // Company
    $company  = $aRow['company'];
    $isPerson = false;

    if ($company == '') {
        $company  = _l('no_company_view_profile');
        $isPerson = true;
    }

    $url = admin_url('clients/client/' . $aRow['userid']);

    if ($isPerson && $aRow['contact_id']) {
        $url .= '?contactid=' . $aRow['contact_id'];
    }

    $company = '<a href="' . $url . '">' . $company . '</a>';

    $company .= '<div class="row-options">';
    $company .= '<a href="' . admin_url('clients/client/' . $aRow['userid'] . ($isPerson && $aRow['contact_id'] ? '?group=contacts' : '')) . '">' . _l('view') . '</a>';

    if ($aRow['registration_confirmed'] == 0 && is_admin()) {
        $company .= ' | <a href="' . admin_url('clients/confirm_registration/' . $aRow['userid']) . '" class="text-success bold">' . _l('confirm_registration') . '</a>';
    }
    if (!$isPerson) {
        $company .= ' | <a href="' . admin_url('clients/client/' . $aRow['userid'] . '?group=contacts') . '">' . _l('customer_contacts') . '</a>';
    }
    if ($hasPermissionDelete) {
        $company .= ' | <a href="' . admin_url('clients/delete/' . $aRow['userid']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $company .= '</div>';

    $row[] = $company;

    // Primary contact
    $row[] = ($aRow['contact_id'] ? '<a href="' . admin_url('clients/client/' . $aRow['userid'] . '?contactid=' . $aRow['contact_id']) . '" target="_blank">' . trim($aRow['fullname']) . '</a>' : '');

    // Primary contact email
    $row[] = ($aRow['email'] ? '<a href="mailto:' . $aRow['email'] . '">' . $aRow['email'] . '</a>' : '');

    // Primary contact phone
    $row[] = ($aRow['phonenumber'] ? '<a href="tel:' . $aRow['phonenumber'] . '">' . $aRow['phonenumber'] . '</a>' : '');

    // Toggle active/inactive customer
    $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
    <input type="checkbox"' . ($aRow['registration_confirmed'] == 0 ? ' disabled' : '') . ' data-switch-url="' . admin_url() . 'clients/change_client_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['userid'] . '" data-id="' . $aRow['userid'] . '" ' . ($aRow[db_prefix() . 'clients.active'] == 1 ? 'checked' : '') . '>
    <label class="onoffswitch-label" for="' . $aRow['userid'] . '"></label>
    </div>';

    // For exporting
    $toggleActive .= '<span class="hide">' . ($aRow[db_prefix() . 'clients.active'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';

    $row[] = $toggleActive;

    // Customer groups parsing
    $groupsRow = '';
    if ($aRow['customerGroups']) {
        $groups = explode(',', $aRow['customerGroups']);
        foreach ($groups as $group) {
            $groupsRow .= '<span class="label label-default mleft5 customer-group-list pointer">' . $group . '</span>';
        }
    }

    $row[] = $groupsRow;

    $row[] = _dt($aRow['datecreated']);

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $row['DT_RowClass'] = 'has-row-options';

    if ($aRow['registration_confirmed'] == 0) {
        $row['DT_RowClass'] .= ' info requires-confirmation';
        $row['Data_Title']  = _l('customer_requires_registration_confirmation');
        $row['Data_Toggle'] = 'tooltip';
    }

    $row = hooks()->apply_filters('customers_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
