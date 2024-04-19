<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'sku',
    'name', 
    'category',
    'status',
    'price',
    'sales',
    'picture',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'woocommerce_products';
 
$join = [];
$where = [];

$custom_view = $this->ci->input->post('custom_view') ? $this->ci->input->post('custom_view') : '';
if ($custom_view) {
    if ($custom_view == 'draft') {
        $where[] = 'AND status = "draft"';
    }
    if ($custom_view == 'pending') {
        $where[] = 'AND status = "pending"';
    }
    if ($custom_view == 'private') {
        $where[] = 'AND status = "private"';
    }
    if ($custom_view == 'publish') {
        $where[] = 'AND status = "publish"';
    }
    if ($custom_view == 'simple') {
        $where[] = 'AND type = "simple"';
    }
    if ($custom_view == 'grouped') {
        $where[] = 'AND type = "grouped"';
    }
    if ($custom_view == 'external') {
        $where[] = 'AND type = "external"';
    }
    if ($custom_view == 'variable') {
        $where[] = 'AND type = "variable"';
    }
}

$storeId = active_store_id();
if(is_nan($storeId) || is_null($storeId)|| $storeId == '') $storeId = 00;
$where[] = 'AND '.$sTable .'.store_id = '.$storeId;

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where,['permalink','product_id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];  
    $link = '<a class="btn btn-primary" href="'.$aRow['permalink'].'" target="_BLANK">';
    $img = '<img  height="50px" width="50px" class="img-fluid" src="'.$aRow['picture'].'" ';
    $action = '<div class="row-options"><a class="add_item_perfex text-info " data-target="#add_item_perfex
    " data-id="'.$aRow["product_id"].'" data-toggle="modal">'._l('import').'</a> &#124; <a class="editProduct text-info " data-target="#updateModal" data-id="'.$aRow["product_id"].'" data-toggle="modal">'._l('edit').'</a> &#124; <a class="product_delete text-danger " data-target="#deleteModal" data-id="'.$aRow["product_id"].'" data-name="'.$aRow["name"].'" data-toggle="modal">'._l('delete').' </a></div>';    
    $row[] = '<span id="'.$aRow["product_id"].'sku">'.$aRow['sku'].'</span>'.$action;
    $row[] = '<span id="'.$aRow["product_id"].'name">'.$aRow['name'].'</span>';
    $row[] = '<span id="'.$aRow["product_id"].'status">'.$aRow['status'].'</span>';
    $row[] = '<span id="'.$aRow["product_id"].'price">'.$aRow['price'].'</span>';
    $row[] = '<span id="'.$aRow["product_id"].'sales">'.$aRow['sales'].'</span>';
    $row[] = ($aRow['picture'] !== '') ? '<span>'.$img.'</span>': '';
    $row[] = $link._l('view').'</a>';

    $output['aaData'][] = $row;
}