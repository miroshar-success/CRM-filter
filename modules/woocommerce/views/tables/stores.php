<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'store_id','name', 'url', 'date_created',
];

$sIndexColumn = 'store_id';
$sTable       = db_prefix() . 'woocommerce_stores';

$where = [];
$join = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    '(SELECT GROUP_CONCAT(CONCAT(firstname, \' \', lastname) SEPARATOR ",") FROM ' . db_prefix() . 'woocommerce_assigned JOIN ' . db_prefix() . 'staff on ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'woocommerce_assigned.staff_id WHERE '.db_prefix().'woocommerce_assigned.store_id=' . db_prefix() . 'woocommerce_stores.store_id ORDER BY staff_id) as members',
    '(SELECT GROUP_CONCAT(staff_id SEPARATOR ",") FROM ' . db_prefix() . 'woocommerce_assigned WHERE store_id=' . db_prefix() . 'woocommerce_stores.store_id ORDER BY staff_id) as members_ids',
]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    
    $membersOutput = '';
    $members       = explode(',', $aRow['members']);
    $exportMembers = '';
    foreach ($members as $key => $member) {
        if ($member != '') {
            $members_ids = explode(',', $aRow['members_ids']);
            $member_id   = $members_ids[$key];
            $membersOutput .= '<a href="' . admin_url('profile/' . $member_id) . '">' .
            staff_profile_image($member_id, [
                'staff-profile-image-small mright5',
                ], 'small', [
                'data-toggle' => 'tooltip',
                'data-title'  => $member,
                ]) . '</a>';
            $exportMembers .= $member . ', ';
        }
    }
    $membersOutput .= '<span class="hide">' . trim($exportMembers, ', ') . '</span>';

    
    $options = '';
    if (staff_can('edit', 'woocommerce')) {
        $options .= '<button class="btn btn-success btn-xs mleft5" data-toggle="tooltip" title="' . _l('test_connection') . '" data-id="' . $aRow['store_id'] . '" onclick="woo_test(this)" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i>"><i class="fa fa-play"></i></button>';
        $options .= '<button class="btn btn-primary btn-xs mleft5" data-toggle="tooltip" title="' . _l('check_updates') . '" data-id="' . $aRow['store_id'] . '" onclick="updateWooStore(this)" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i>"><i class="fa fa-refresh"></i></button>';
        $options .= '<button class="btn btn-info btn-xs mleft5" data-toggle="tooltip" title="' . _l('edit') . '" data-id="' . $aRow['store_id'] . '" onclick="editWooStore(this)" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i>"><i class="fa fa-edit"></i></button>';
        $options .= '<button class="btn btn-warning btn-xs mleft5" data-toggle="tooltip" title="' . _l('reset') . '" data-id="' . $aRow['store_id'] . '" onclick="woo_reset(this)" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i>"><i class="fa fa-recycle"></i></button>';
    }
    if (staff_can('delete', 'woocommerce')) {
        $options .= '<a class="btn btn-danger btn-xs mleft5" id="confirmDelete" data-toggle="tooltip" title="' . _l('delete') . '" href="' . admin_url('woocommerce/stores/delete/' . $aRow['store_id']) . '" onclick="return confirm(\'' . _l('delete_store_query') . '\')"><i class="fa fa-trash"></i></a>';
    }


    $row = [];
    $row[] = '<span>' . $aRow['store_id'] . '</span>';
    $row[] =  '<a href="'. $aRow['url'] .'" target="_blank">' . $aRow['name'] . '</a>';
    $row[] = $membersOutput;
    $row[] = _dt($aRow['date_created']) . '</span></a>';
    $row[] = $options ;

    $output['aaData'][] = $row;
}
