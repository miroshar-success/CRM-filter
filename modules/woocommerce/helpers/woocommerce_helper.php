<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
* To be returned.
 if (staff_can('edit', 'settings')) {
     hooks()->add_action('admin_init', 'woocommerce_add_settings_tab');
 }


function woocommerce_add_settings_tab()
{
    $CI = & get_instance();
    $CI->app_tabs->add_settings_tab('woocommerce-settings', [
     'name'     => ''._l('woocommerce_settings').'',
     'view'     => 'woocommerce/settings',
     'position' => 41,
 ]);
}
*/
function woocommerce_system_currency(){
    $CI = & get_instance();
    $CI->load->model('currencies_model');
    return $CI->currencies_model->get();
}
function get_wcorder_customer($id){
    $CI = & get_instance();
    $CI->load->model('woocomerce_model');

    return $CI->woocomerce_model->customer_by_wc_id;

}
function create_woocomerce_invoice_data($client, $invoice)
{
    $new_invoice_data                    = [];
    $new_invoice_data['clientid']        = $client->userid;
    $new_invoice_data['number']          = get_option('next_invoice_number');
    $new_invoice_data['date']            = _d(date('Y-m-d'));
    $new_invoice_data['duedate']         = null;

    $new_invoice_data['show_quantity_as'] = 1;
    $new_invoice_data['currency']         = $invoice->currency;

    $new_invoice_data['subtotal']         = $invoice->subtotal ;
    $new_invoice_data['total']            = $invoice->total;
    $new_invoice_data['adjustment']       = $invoice->datas->total_tax;
    $new_invoice_data['discount_percent'] = 0;
    $new_invoice_data['discount_total']   = 0;
    $new_invoice_data['discount_type']    = '';

    $new_invoice_data['terms']      = clear_textarea_breaks(get_option('predefined_terms_invoice'));
    $new_invoice_data['sale_agent'] = 0;

    $new_invoice_data['billing_street']           = clear_textarea_breaks($client->billing_street);
    $new_invoice_data['billing_city']             = $client->billing_city;
    $new_invoice_data['billing_state']            = $client->billing_state;
    $new_invoice_data['billing_zip']              = $client->billing_zip;
    $new_invoice_data['billing_country']          = $client->billing_country;
    $new_invoice_data['shipping_street']          = clear_textarea_breaks($client->shipping_street);
    $new_invoice_data['shipping_city']            = $client->shipping_city;
    $new_invoice_data['shipping_state']           = $client->shipping_state;
    $new_invoice_data['shipping_zip']             = $client->shipping_zip;
    $new_invoice_data['shipping_country']         = $client->shipping_country;
    $new_invoice_data['show_shipping_on_invoice'] = 0;
    $new_invoice_data['status'] = 1;

    if (!empty($client->shipping_street)) {
        $new_invoice_data['show_shipping_on_invoice'] = 1;
        $new_invoice_data['include_shipping']         = 1;
    }

    $new_invoice_data['clientnote']            = clear_textarea_breaks(get_option('predefined_clientnote_invoice'));
    $new_invoice_data['adminnote']             = '';
    $new_invoice_data['allowed_payment_modes'] = ['woocommerce'];

    $new_invoice_data['newitems'] = [];
    $key                          = 1;
    $items                        =  $invoice->datas->line_items;
    $totalItems                   = 0;
    foreach ($items as $item) {
        $new_invoice_data['newitems'][$key]['rate'] =$item->total;
        $new_invoice_data['newitems'][$key]['description']      = trim($item->name);
        $new_invoice_data['newitems'][$key]['long_description'] = '';
        $new_invoice_data['newitems'][$key]['qty']     = $item->quantity;
        $new_invoice_data['newitems'][$key]['unit']    = '';
        $new_invoice_data['newitems'][$key]['taxname'] = [];
        $new_invoice_data['newitems'][$key]['order'] = $key;
        $key++;
        $totalItems++;
    }
    $new_invoice_data = hooks()->apply_filters('woocommerce_invoice_data', $new_invoice_data);
    return $new_invoice_data;

}

function active_store_id($staff_id = '')
{
    $CI = &get_instance();
    $staff_id = (!$staff_id == '') ? $staff_id : get_staff_user_id();
    // if($CI->session->userdata('store_id')){
    //     return $CI->session->userdata('store_id');
    // }
    $staff = get_staff($staff_id);
    return $staff->store_id;
}    


function get_staff_stores($staff_id='')
{
    $staff_id = (!$staff_id == '') ? $staff_id : get_staff_user_id();
    $CI = &get_instance();
    $CI->load->model('woocommwece/woocommerce_model','stm');
    return $CI->stm->staff_stores($staff_id);
}
 
function set_store($store_id , $staff_id='')
{
    $staff_id = (!$staff_id == '') ? $store_id : get_staff_user_id();
    $CI = &get_instance();
    $CI->session->set_userdata(['store_id' => $store_id]);
    $CI->db->set('store_id', $store_id);
    $CI->db->where('staffid', $staff_id);
    $CI->db->update(db_prefix() . 'staff');
}

function remove_store($staff_id)
{
    $CI = &get_instance();
    $CI->db->set('store_id', null);
    $CI->db->where('staffid', $staff_id);
    $CI->db->update(db_prefix() . 'staff');
}

function woo_update_pageno($field,$npage,$storeId)
{
    $CI = &get_instance();
    $CI->db->set($field, $npage);
    $CI->db->where('store_id', $storeId);
    $CI->db->update(db_prefix() . 'woocommerce_stores');
}
