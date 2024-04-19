<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'order_id',
    db_prefix() . 'woocommerce_orders.status',
    db_prefix() . 'woocommerce_orders.total',
    'order_number',
    'date_created',
    'invoice_id',
    db_prefix() . 'woocommerce_orders.currency',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'woocommerce_orders';
$join = [];
$where = [];

$custom_view = $this->ci->input->post('custom_view') ? $this->ci->input->post('custom_view') : '';
if ($custom_view) {
    if ($custom_view == 'pending') {
        $where[] = 'AND' . db_prefix() . 'woocommerce_orders.status = "pending"';
    }
    if ($custom_view == 'processing') {
        $where[] = 'AND' . db_prefix() . 'woocommerce_orders.status = "processing"';
    }
    if ($custom_view == 'on-hold') {
        $where[] = 'AND' . db_prefix() . 'woocommerce_orders.status = "on-hold"';
    }
    if ($custom_view == 'cancelled') {
        $where[] = 'AND' . db_prefix() . 'woocommerce_orders.status = "cancelled"';
    }
    if ($custom_view == 'failed') {
        $where[] = 'AND' . db_prefix() . 'woocommerce_orders.status = "failed"';
    }
    if ($custom_view == 'completed') {
        $where[] = 'AND' . db_prefix() . 'woocommerce_orders.status = "completed"';
    }
    if ($custom_view == 'refunded') {
        $where[] = 'AND' . db_prefix() . 'woocommerce_orders.status = "refunded"';
    }
}

$storeId = active_store_id();
$join[] = 'LEFT JOIN ' . db_prefix() . 'woocommerce_customers ON ' . db_prefix() . 'woocommerce_orders.customer_id = ' . db_prefix() . 'woocommerce_customers.woo_customer_id';
$join[] = 'LEFT JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'woocommerce_orders.order_id = ' . db_prefix() . 'invoices.wco_id AND ' . db_prefix() . 'woocommerce_orders.store_id = ' . db_prefix() . 'invoices.store_id ';
if (is_nan($storeId) || is_null($storeId) || $storeId == '') $storeId = 00;
$where[] = 'AND ' . $sTable . '.store_id = ' . $storeId;

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [db_prefix() . 'woocommerce_orders.phone', 'address', db_prefix() . 'woocommerce_customers.first_name', db_prefix() . 'woocommerce_customers.last_name', db_prefix() . 'invoices.id as inv_id',]);
$output  = $result['output'];
$rResult = $result['rResult'];

$tempArr = array_unique(array_column($rResult, 'order_id'));
$rResult = array_intersect_key($rResult, $tempArr);

foreach ($rResult as $aRow) {
    $row = [];
    $content = '<div class="row-options"><a class="text-info " href="' . admin_url("woocommerce/order/" . $aRow["order_id"]) . '">' . _l('view') . '</a> &#124; <a class="order_update text-info " data-target="#updateModal" data-id="' . $aRow["order_id"] . '" data-toggle="modal">' . _l('update_status') . '</a> &#124; <a class="order_delete text-danger " data-target="#deleteModal" data-id="' . $aRow["order_id"] . '" data-toggle="modal">' . _l('delete') . ' </a></div>';
    $inv = !is_null($aRow['invoice_id']) ? $aRow['invoice_id'] : $aRow['inv_id'];
    $has_invoice = '<a href="' . admin_url() . 'invoices/invoice/' . $inv . '">' . _l('view_invoice') . '</a>';
    $name_customer = !isset($aRow['first_name']) ? "Guest" : $aRow['first_name'] . $aRow['last_name'];
    $row[] = '<span>' . $aRow['order_number'] . '</span> <br>' . $content . '</a>';
    $row[] =  '<span>' . $name_customer . '</span>';
    $row[] =  '<span "text-muted">' . $aRow['address'] . '</span>';
    $row[] =  '<span>' . $aRow['phone'] . '</span>';
    $row[] =  '<span>' . $aRow[db_prefix() . 'woocommerce_orders.status'] . '</span>';
    $row[] = '<span>' . $aRow[db_prefix() . 'woocommerce_orders.currency'] . ' ' . $aRow[db_prefix() . 'woocommerce_orders.total'] . '</span>';
    $row[] = _dt($aRow['date_created']) . '</span></a>';
    $row[] = (isset($aRow['invoice_id']) || isset($aRow['inv_id'])) ? $has_invoice : "";

    $output['aaData'][] = $row;
}
