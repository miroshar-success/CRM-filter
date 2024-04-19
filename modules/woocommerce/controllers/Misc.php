<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Misc extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
        }
        $this->load->library('woocommerce/woocommerce_module');
        $this->load->model('woocommerce/Stores_model', 'stm');
        $this->load->model('woocommerce/stores_model','stm');

    }

    public function modal()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        if ($this->input->post('slug') === 'customer') {
            $storeId = active_store_id();
            $store = $this->stm->get($storeId);
            $this->woocommerce_module->set_store($store);
            $data['customer'] = $this->woocommerce_module->customer($this->input->post('customer_id'));
            $data['groups']   = $this->clients_model->get_groups();
            $data['customer_permissions'] = get_contact_permissions();
            $this->load->view('modals/import_customer', $data);
        }
    }
}