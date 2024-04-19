<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stores extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
        }
        $this->load->library('woocommerce/woocommerce_module');
        $this->load->model('woocommerce/Stores_model', 'stm');
    }

    public function index()
    {
        if (!is_admin()) {
            redirect('woocommerce/orders');
        }
        $data['title'] = _l('woocommerce_stores');
        $this->load->view('stores', $data);
    }

    public function modal()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        if ($this->input->post('slug') === 'new') {
            $data['staff']    = $this->staff_model->get('', ['active' => 1]);
            $this->load->view('modals/new_store', $data);
        } elseif ($this->input->post('slug') === 'edit') {
            $data['staff']    = $this->staff_model->get('', ['active' => 1]);
            $data['store_id'] = $this->input->post('store_id');
            $data['store'] = $this->stm->get($data['store_id']);

            $staffs = $this->stm->get_assignees($data['store_id']);
            foreach ($staffs as $staff) {
                $db_selected[] = $staff['staff_id'];
            }

            $data['db_selected'] = $db_selected;
            $this->load->view('modals/edit_store', $data);
        }
    }

    public function new()
    {
        $post = $this->input->post();
        $data = [
            'name'  => $post['name'],
            'url'   => $post['url'],
            'key'   => $post['key'],
            'secret' => $post['secret'],
            'assignees' => $post['assignees'],
            'date_created' => date('c'),
            'query_auth' => $post['query_auth']
        ];

        $id = $this->stm->create($data);
        if ($id) {
            set_alert('success', 'woocommerce_store_added');
        } else {
            set_alert('success', 'woocommerce_store_failed');
        }
        redirect(admin_url('woocommerce/stores'));
    }

    public function edit()
    {
        $post = $this->input->post();
        $data = [
            'name'      => $post['name'],
            'url'       => $post['url'],
            'key'       => $post['key'],
            'secret'    => $post['secret'],
            'assignees' => $post['assignees'],
            'query_auth' => $post['query_auth']
        ];
        $updated = $this->stm->update($post['store_id'], $data);
        if ($updated) {
            set_alert('success', 'woocommerce_store_updated');
        } else {
            set_alert('success', 'woocommerce_store_failed');
        }
        redirect(admin_url('woocommerce/stores'));
    }

    public function delete($id)
    {
        if ($this->stm->delete($id)) {
            set_alert('success', 'woocommerce_store_deleted');
        } else {
            set_alert('success', 'woocommerce_store_failed');
        }
        redirect(admin_url('woocommerce/stores'));
    }

    public function refresh($store_id)
    {
        $store = $this->stm->get($store_id);
        $this->woocommerce_module->set_store($store);
        $Product_no     = $store->productPage;
        $Order_no       = $store->orderPage;
        $Customer_no    = $store->customerPage;
        summary($store);
        checkProducts($Product_no, $store);
        checkCustomers($Customer_no, $store);
        checkOrders($Order_no, $store);
    }

    public function reset($store_id)
    {
        $this->stm->empty_store($store_id);
        woo_update_pageno('productPage', 1, $store_id);
        woo_update_pageno('orderPage', 1, $store_id);
        woo_update_pageno('customerPage', 1, $store_id);
    }
}
