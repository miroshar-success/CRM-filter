<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'woo_customer_id',    
    'username', 
    'email',
    'phone',
    'avatar_url',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'woocommerce_customers';

$join = [];
$where = [];

$storeId = active_store_id();
if(is_nan($storeId) || is_null($storeId)|| $storeId == '') $storeId = 00;

$where[] = 'AND '.$sTable .'.store_id = '.$storeId;

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,['first_name','last_name','id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $aRow['name'] = $aRow['first_name'].' '.$aRow['last_name'];
    $row = [];
    $content = "<div class='row-options'>
    <a class='viewID text-success' onclick='importCustomer(this)' data-id=".$aRow['woo_customer_id'].">". _l('add_to_crm') ."</a>
    <a class='editCust text-info' data-target='#updateModal' data-id='".$aRow['woo_customer_id']."' data-toggle='modal'>"._l('edit')."</a>
    <a class='deleteCust text-danger' data-target='#deletecustModal' data-id='".$aRow['woo_customer_id']."' data-name='".$aRow['name']."' data-toggle='modal' onclick='deleteCustomer(this)'>"._l('delete')." </a>
    </div>";
    $row[] = $aRow['woo_customer_id'].$content;
    $row[] = '<span id="'.$aRow["woo_customer_id"].'username">'.$aRow['username'].'</span>';
    $row[] = '<span id="'.$aRow["woo_customer_id"].'name" fname="'.$aRow["first_name"].'" lname="'.$aRow["last_name"].'">'.$aRow['name'].'</span>';
    $row[] = '<span id="'.$aRow["woo_customer_id"].'phone">'.$aRow['phone'].'</span>';
    $row[] = '<span id="'.$aRow["woo_customer_id"].'email">'.$aRow['email'].'</span>';
    $row[] = '<img src="'.$aRow['avatar_url'].'" width="50px" height="50px" alt="'.$aRow['username'].'">';
    $output['aaData'][] = $row;
}
 