<?php

defined('BASEPATH') or exit('No direct script access allowed');


function woocommerce_cron()
{

    $CI = &get_instance();
    $CI->load->library('woocommerce/woocommerce_module');
    $CI->load->model('woocommerce/woocommerce_model', 'wooDB');
    $CI->load->model('woocommerce/stores_model', 'stm');

    $stores = $CI->stm->get_stores();

    foreach ($stores as $store) {
        $CI->woocommerce_module->set_store($store);

        $Product_no     = $store->productPage;
        $Order_no       = $store->orderPage;
        $Customer_no    = $store->customerPage;
        summary($store);
        checkProducts($Product_no, $store);
        checkCustomers($Customer_no, $store);
        checkOrders($Order_no, $store);
		log_activity( $store->name." store updated (woocommerce)");
    }
}

function checkOrders($page, $store)
{
    $storeId = $store->store_id;

    $CI = &get_instance();
    $CI->load->model('woocommerce/woocommerce_model', 'wooDB');
    $pdata['orderby'] =  'date';
    $pdata['per_page'] =  100;
    $stopPage = $page + 2;

    while ($page <= $stopPage) {
        $pdata['page'] =  $page;

        $orders = $CI->woocommerce_module->cron('orders', $pdata);
        if (is_array($orders)) {
            if (empty($orders)) {
                break;
            }
            foreach ($orders as $order) {
                $data['order_id']       = $order->id;
                $data['order_number']   = $order->number;
                $data['customer_id']    = $order->customer_id;
                $data['address']        = $order->billing->address_1 . ', ' . $order->billing->city . ', ' . $order->billing->state . ', ' . $order->billing->country;
                $data['phone']          = $order->billing->phone;
                $data['status']         = $order->status;
                $data['currency']       = $order->currency;
                $data['total']          = $order->total;
                $data['date_created']   = $order->date_created;
                $data['date_modified']  = $order->date_modified;
                if (!$CI->wooDB->is_wooorder_exist($data['order_id'], $storeId)) {
                    $CI->wooDB->cron_orders($data, $storeId);
                } else {
                    $CI->wooDB->cron_updates($data, 'orders', $storeId);
                }
            }
        } else {
            log_activity("couldnt connect to store ({$storeId}): " . $orders);
            return;
        }
        $page++;
    }

    $npage = $page - 1;
    if ($npage < 1) {
        $npage = 1;
    }

    woo_update_pageno('orderPage',$npage,$storeId);
}

function checkProducts($page, $store)
{
    $storeId = $store->store_id;
    $CI = &get_instance();
    $CI->load->model('woocommerce/woocommerce_model', 'wooDB');
    $pdata['orderby'] =  'date';
    $pdata['per_page'] =  100;
    $stopPage = $page + 2;
    $pdata['page'] =  $page;
    while ($page <= $stopPage) {
        $pdata['page'] =  $page;

        $products = $CI->woocommerce_module->cron('products', $pdata);
        if (is_array($products)) {
            if (empty($products)) {
                break;
            }
            foreach ($products as $product) {
                $data['product_id']       = $product->id;
                $data['name']   = $product->name;
                $data['sales']        = $product->total_sales;
                $data['price']          = $product->price;
                $data['sku']         = $product->sku;
                $data['status']       = $product->status;
                $data['permalink']          = $product->permalink;
                $data['picture']   = (!empty($product->images)) ? $product->images[0]->src : '';
                $data['category']   = (!empty($product->categories)) ? json_encode($product->categories) : '';
                $data['date_created']   = $product->date_created;
                $data['type']  = $product->type;
                if (!$CI->wooDB->is_wooproduct_exist($data['product_id'], $storeId)) {
                    $CI->wooDB->cron_products($data, $storeId);
                } else {
                    $CI->wooDB->cron_updates($data, 'products', $storeId);
                }
            }
        } else {
            log_activity("couldnt connect to store: " . $products);
            return;
        }
        $page++;
    }
    $npage = $page - 1;
    if ($npage < 1) {
        $npage = 1;
    }

    woo_update_pageno('productPage',$npage,$storeId);
}

function checkCustomers($page, $store)
{
    $storeId = $store->store_id;
    $CI = &get_instance();
    $CI->load->model('woocommerce/woocommerce_model', 'wooDB');
    $pdata['orderby'] =  'registered_date';
    $pdata['per_page'] =  100;
    $stopPage = $page + 2;
    $pdata['page'] =  $page;
    while ($page <= $stopPage) {
        $pdata['page'] =  $page;

        $customers = $CI->woocommerce_module->cron('customers', $pdata);
        if (is_array($customers)) {
            if (empty($customers)) {
                break;
            }
            foreach ($customers as $customer) {
                $data['woo_customer_id']    = $customer->id;
                $data['email']              = $customer->email;
                $data['first_name']         = $customer->first_name;
                $data['last_name']          = $customer->last_name;
                $data['phone']              = $customer->billing->phone;
                $data['role']               = $customer->role;
                $data['username']           = $customer->username;
                $data['avatar_url']         = $customer->avatar_url;
                if (!$CI->wooDB->is_woocustomer_exist($data['woo_customer_id'], $storeId)) {
                    $CI->wooDB->cron_customers($data, $storeId);
                } else {
                    $CI->wooDB->cron_updates($data, 'customers', $storeId);
                }
            }
        } else {
            log_activity("Couldnt connect to store({$storeId})." . $customers);
            return;
        }
        $page++;
    }
    $npage = $page - 1;
    if ($npage < 1) {
        $npage = 1;
    }

    woo_update_pageno('customerPage',$npage,$storeId);
}

function summary($store)
{
    $storeId = $store->store_id;
    $CI = &get_instance();
    $CI->load->model('woocommerce/woocommerce_model', 'wooDB');
    $customers   = $CI->woocommerce_module->cronReport('customers/totals');
    $data = [];
    if (is_array($customers)) {
        $data['customers'] = json_encode($customers);
    }

    $products    = $CI->woocommerce_module->cronReport('products/totals');
    if (is_array($products)) {
        $data['products'] = json_encode($products);
    }

    $orders      = $CI->woocommerce_module->cronReport('orders/totals');
    if (is_array($orders)) {
        $data['orders'] = json_encode($orders);
    }
    if (!empty($data)) {
        if ($CI->wooDB->is_summary_exist($storeId)) {
            $success = $CI->wooDB->update_summary($data, $storeId);
        } else {
            $success = $CI->wooDB->add_summary($data, $storeId);
        }

        if ($success) {
            log_activity('successfully updated woocommerce summary');
        }

        if (!is_array($customers)) {
            log_activity("could not connect to store.Customers ( {$storeId} ): <br> reason: " . $customers);
        } elseif (!is_array($products)) {
            log_activity("could not connect to store.Products ( {$storeId} ): <br> reason: " . $products);
        } elseif (!is_array($orders)) {
            log_activity("could not connect to store.Orders ( {$storeId} ): <br> reason: " . $orders);
        }
    } else {
        log_activity("summary ( {$storeId} ): " . $orders);
    }
}
