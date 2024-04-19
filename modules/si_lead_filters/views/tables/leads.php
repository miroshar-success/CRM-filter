<?php

defined('BASEPATH') or exit('No direct script access allowed');

$base_currency 		   = $this->ci->currencies_model->get_base_currency();
$lockAfterConvert      = get_option('lead_lock_after_convert_to_customer');
$has_permission_view   = has_permission('leads', '', 'view');
$has_permission_delete = has_permission('leads', '', 'delete');
$custom_fields         = get_table_custom_fields('leads');
$statuses              = $this->ci->leads_model->get_status();

$aColumns = [
    '1',//bulk action
    db_prefix() . 'leads.id as id',
    db_prefix() . 'leads.name as name',
	db_prefix() . 'leads.company as company',
	db_prefix() . 'leads.email as email',
	db_prefix() . 'leads.phonenumber as phonenumber',
	db_prefix() . 'leads.city as city',
	db_prefix() . 'leads.state as state',
	db_prefix() . 'countries.short_name as country_name',
	db_prefix() . 'leads.zip as zip',
    ];
if ($perfex_version >= 250) {
    $aColumns[] = db_prefix() . 'leads.lead_value as lead_value';
}
$aColumns = array_merge($aColumns, [
    db_prefix() . 'leads_status.name as status_name',
    db_prefix() . 'leads_sources.name as source_name',
    'dateadded',
	'lastcontact',
    'is_public',
    'firstname as assigned_firstname',
	'(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'leads.id and rel_type="lead" ORDER by tag_order ASC LIMIT 1) as tags',
    
]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'leads';

$join = [
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'leads.assigned',
    'LEFT JOIN ' . db_prefix() . 'leads_status ON ' . db_prefix() . 'leads_status.id = ' . db_prefix() . 'leads.status',
    'JOIN ' . db_prefix() . 'leads_sources ON ' . db_prefix() . 'leads_sources.id = ' . db_prefix() . 'leads.source',
	'LEFT JOIN ' . db_prefix() . 'countries ON ' . db_prefix() . 'countries.country_id = ' . db_prefix() . 'leads.country',
];

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'leads.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where  = [];
$filter = false;

//filter by lead type
if (isset($type) && $type !='') {
    $filter = $type;
    if ($filter == 'lost') {
        array_push($where, 'AND lost = 1');
    } elseif ($filter == 'junk') {
        array_push($where, 'AND junk = 1');
    } elseif ($filter == 'not_assigned') {
        array_push($where, 'AND assigned = 0');
    } elseif ($filter == 'public') {
        array_push($where, 'AND is_public = 1');
    }
}
if (!$filter || ($filter && $filter != 'lost' && $filter != 'junk')) {
    array_push($where, 'AND lost = 0 AND junk = 0');
}

/*if (has_permission('leads', '', 'view') && $this->ci->input->post('assigned')) {
    array_push($where, 'AND assigned =' . $this->ci->input->post('assigned'));
}*/

//filter by staff
if (!$has_permission_view) {
	$staff_id = get_staff_user_id();
} elseif (isset($member)) {
	$staff_id = $member;
} else {
	$staff_id = '';
}
if(!$has_permission_view){
	array_push($where, 'AND (assigned =' . $staff_id . ' OR addedfrom = ' . $staff_id . ' OR is_public = 1)');
}
elseif ($has_permission_view) {
	if (is_numeric($staff_id)) {
		array_push($where, 'AND assigned =',$staff_id);
	}
}

//filter by status
if(!isset($status) || empty($status))
	$status=array('');
if ($status && !in_array('',$status)
    && count($status) > 0
    && ($filter != 'lost' && $filter != 'junk')) {
    array_push($where, 'AND status IN (' . implode(',', $status) . ')');
}

//filter by source
if(!isset($source) || empty($source))
	$source=array('');
if ($source && !in_array('',$source) && count($source) > 0) {
    array_push($where, 'AND source IN (' . implode(',', $source) . ')');
}

//filter by country
if(!isset($countries) || empty($countries))
	$countries=array('');
if ($countries && !in_array('',$countries) && count($countries) > 0) {
	if(in_array(-1,$countries))//if country is unknown
		$countries[]=0;
    array_push($where, 'AND country IN (' . implode(',', $countries) . ')');
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

//filter by tags
if(!isset($tags) || empty($tags))
	$tags=array('');
if ($tags && !in_array('',$tags) && count($tags) > 0) {
	array_push($where, 'AND '.db_prefix() . 'leads.id IN (SELECT rel_id FROM '.db_prefix() . 'taggables 
						WHERE tag_id IN (' . implode(', ', $tags) . ') AND rel_type=\'lead\')');	
}

//filter by dates
if(isset($custom_date_select) && $custom_date_select != '') {
	array_push($where, $custom_date_select);
};

//filter by custom fields
if(!empty($cf)){
	foreach($cf as $_cf=>$value){
		array_push($where, 'AND '.db_prefix() . 'leads.id in (SELECT relid FROM '.db_prefix() . 'customfieldsvalues  where fieldid='.$_cf.' and value in ("'.implode('","',$value).'"))');
	}	
}	


/*if (!has_permission('leads', '', 'view')) {
    array_push($where, 'AND (assigned =' . get_staff_user_id() . ' OR addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
}*/

$aColumns = hooks()->apply_filters('si_leads_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$additionalColumns = hooks()->apply_filters('leads_table_additional_columns_sql', [
    'junk',
    'lost',
    'color',
    'status',
    'assigned',
    'lastname as assigned_lastname',
    db_prefix() . 'leads.addedfrom as addedfrom',
    '(SELECT count(leadid) FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.leadid=' . db_prefix() . 'leads.id) as is_converted',
]);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalColumns);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';

    $hrefAttr = 'href="' . admin_url('leads/index/' . $aRow['id']) . '" onclick="init_lead(' . $aRow['id'] . ');return false;"';
    $row[]    = '<a ' . $hrefAttr . '>' . $aRow['id'] . '</a>';

    $nameRow = '<a ' . $hrefAttr . '>' . $aRow['name'] . '</a>';

    $locked = false;

    if ($aRow['is_converted'] > 0) {
        $locked = ((!is_admin() && $lockAfterConvert == 1) ? true : false);
    }

    $row[] = $nameRow;
	
	$row[] = $aRow['company'];
	
	$row[] = ($aRow['email'] != '' ? '<a href="mailto:' . $aRow['email'] . '">' . $aRow['email'] . '</a>' : '');

    $row[] = ($aRow['phonenumber'] != '' ? '<a href="tel:' . $aRow['phonenumber'] . '">' . $aRow['phonenumber'] . '</a>' : '');
	
	$row[] = $aRow['city'];
	
	$row[] = $aRow['state'];
	
	$row[] = $aRow['country_name'];
	
	$row[] = $aRow['zip'];

    if ($perfex_version >= 250) {
        $row[] = app_format_money($aRow['lead_value'], $base_currency->name);
    }
	
	// Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }
	
	if ($aRow['status_name'] == null) {
        if ($aRow['lost'] == 1) {
            $outputStatus = '<span class="label label-danger inline-block">' . _l('lead_lost') . '</span>';
        } elseif ($aRow['junk'] == 1) {
            $outputStatus = '<span class="label label-warning inline-block">' . _l('lead_junk') . '</span>';
        }
    } else {
        $outputStatus = '<div id="si-tbl-id-'.$aRow['id'].'"><span class="inline-block label label-' . (empty($aRow['color']) ? 'default': '') . '" style="color:' . $aRow['color'] . ';border:1px solid ' . $aRow['color'] . '">' . $aRow['status_name'];
        if (!$locked) {
            $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
            $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableLeadsStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
            $outputStatus .= '</a>';

            $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableLeadsStatus-' . $aRow['id'] . '">';
            foreach ($statuses as $leadChangeStatus) {
                if ($aRow['status'] != $leadChangeStatus['id']) {
                    $outputStatus .= '<li>
                  <a href="#" onclick="si_leads_status_update(' . $leadChangeStatus['id'] . ',' . $aRow['id'] . '); return false;">
                     ' . $leadChangeStatus['name'] . '
                  </a>
               </li>';
                }
            }
            $outputStatus .= '</ul>';
            $outputStatus .= '</div>';
        }
        $outputStatus .= '</span></div>';
    }

    $row[] = $outputStatus;
	
	$row[] = $aRow['source_name'];
	
	$row[] = '<span style="display:none;">'.strtotime($aRow['dateadded']).'</span>' . _dt($aRow['dateadded']);
	 
	$row[] = ($aRow['lastcontact'] == '0000-00-00 00:00:00' || !is_date($aRow['lastcontact']) ? '' : '<span style="display:none;">'.strtotime($aRow['lastcontact']).'</span>'. _dt($aRow['lastcontact']));
	
	$row[] = $aRow['is_public'] ? _l('lead_is_public_yes'):_l('lead_is_public_no');

    $assignedOutput = '';
    if ($aRow['assigned'] != 0) {
        $full_name = $aRow['assigned_firstname'] . ' ' . $aRow['assigned_lastname'];

        $assignedOutput = '<a data-toggle="tooltip" data-title="' . $full_name . '" href="' . admin_url('profile/' . $aRow['assigned']) . '">' . staff_profile_image($aRow['assigned'], [
            'staff-profile-image-small',
            ]) . '</a>';

        // For exporting
        $assignedOutput .= '<span class="hide">' . $full_name . '</span>';
    }

    $row[] = $assignedOutput;
	
	$row[] = render_tags($aRow['tags']);

    $row['DT_RowId'] = 'lead_' . $aRow['id'];

    if ($aRow['assigned'] == get_staff_user_id() && $has_permission_view) {
        $row['DT_RowClass'] = 'alert-info';
    }

    if (isset($row['DT_RowClass'])) {
        $row['DT_RowClass'] .= ' has-row-options';
    } else {
        $row['DT_RowClass'] = 'has-row-options';
    }

    $row = hooks()->apply_filters('si_leads_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
