<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Woocommerce extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
        }
        $this->load->library('woocommerce/woocommerce_module');
        $this->load->model('woocommerce/woocommerce_model');
        $this->load->model('woocommerce/stores_model','stm');
    }

    public function index()
    {
      redirect(admin_url('woocommerce/orders'));
    }

    public function table($type = 'orders')
    {
        if ($this->input->is_ajax_request()) {
            if ($type == 'products' || $type == 'draft') {
                $this->app->get_table_data(module_views_path('woocommerce', 'tables/products'), [
                    'type' => $type,
                ]);
            } elseif ($type == 'orders') {
                $this->app->get_table_data(module_views_path('woocommerce', 'tables/orders'), [
                    'type' => $type,
                ]);
            } elseif ($type == 'customers') {
                $this->app->get_table_data(module_views_path('woocommerce', 'tables/customers'), [
                    'type' => $type,
                ]);
            } elseif ($type == 'stores') {
                $this->app->get_table_data(module_views_path('woocommerce', 'tables/stores'), [
                    'type' => $type,
                ]);
            }
        }
    }

    public function order($id)
    {
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        $data['order']      = $this->get_order($id);
        $data['title']      = _l('woocommerce_order');
        $data['client']     = is_numeric($data['order']->customer_id) ? $this->db->query('select userid from '. db_prefix() .'clients where woo_id =' . $data['order']->customer_id . ' AND store_id =' . active_store_id())->row() : null;
        $this->load->view('order', $data);
    }

    public function get_order($id)
    {
        $storeId = active_store_id();
        $store = $this->stm->get($storeId);
        $this->woocommerce_module->set_store($store);
        return $this->woocommerce_module->order($id);
    }

    public function orders()
    {
        $storeId = active_store_id();
        if($this->input->post('store_id')){
            $storeId = $this->input->post('store_id');
            set_store($storeId);
        }

        $summary = $this->woocommerce_model->get_summary($storeId);
        $data['title']      = _l('woocommerce_orders');
        $data['stores'] = get_staff_stores();
        
        $data['summary'] = (is_object($summary)) ? json_decode($summary->orders) : '';
        $this->load->view('woocommerce/orders', $data);
    }

    public function products()
    {
        $storeId = active_store_id();
        if($this->input->post('store_id')){
            $storeId = $this->input->post('store_id');
            set_store($storeId);
        }

        $summary = $this->woocommerce_model->get_summary($storeId);
        $data['title']      = _l('woocommerce_products');
        $data['summary'] = (is_object($summary)) ? json_decode($summary->products) : '';
        $data['stores'] = get_staff_stores();

        $this->load->model('invoice_items_model');
        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        $data['items_groups'] = $this->invoice_items_model->get_groups();

        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();

        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $this->load->view('woocommerce/products', $data);
    }

    public function customers()
    {
        $storeId = active_store_id();
        if($this->input->post('store_id')){
            $storeId = $this->input->post('store_id');
            set_store($storeId);
        }

        $summary = $this->woocommerce_model->get_summary($storeId);
        $data['summary'] = (is_object($summary)) ? json_decode($summary->customers) : '';
        $data['stores']  = get_staff_stores();

        $data['groups']   = $this->clients_model->get_groups();
        $data['title']      = _l('woocommerce_customers');
        $data['customer_permissions'] = get_contact_permissions();
        $this->load->view('woocommerce/customers', $data);
    }

    public function get_customer($id)
    {
        $storeId = active_store_id();
        $store = $this->stm->get($storeId);
        $this->woocommerce_module->set_store($store);
        return $this->woocommerce_module->customer($id);
    }

    /**
     * Update or delete Woocommerce Order
     */
    public function update_woo()
    {
        
        $storeId = active_store_id();
        $store = $this->stm->get($storeId);
        $this->woocommerce_module->set_store($store);

        if (!has_permission('woocommerce', '', 'edit')) {
            access_denied('woocommerce');
        }
        $this->load->library('woocommerce/woocommerce_module');

        $data = $this->input->post();

        //  Update order details
        if (count($data) == 3) {
            $update =  $this->woocommerce_module->update_order($data);
            if (is_string($update)) {
                set_alert('warning', _l('Something_went_wrong') . $update);
                log_activity($update);
            } else {
                $this->woocommerce_model->update_order($data,$storeId);
                summary($store);
                set_alert('success', _l('order_update_success'));
            }
        }


        //  delete order details
        if (count($data) == 2) {
            $update = $this->woocommerce_module->delete_order($data);
            if (is_string($update)) {
                set_alert('warning', _l('order_action_failed ' . $update));
            } else {
                $this->woocommerce_model->delete_order($data,$storeId);
                summary($store);
                set_alert('success', _l('order_delete_success'));
            }
        }
        redirect(admin_url('woocommerce/orders'));
    }


    public function check_duplicate_woocomerce_customer()
    {
        if (
            has_permission('customers', '', 'create')
        ) {
            
        $storeId = active_store_id();
            $id = trim($this->input->post('id'));
            $response    =  (bool) total_rows(db_prefix() . 'clients', ['woo_id' => $id,'store_id'=> $storeId]) > 0;
            if ($response == true) {
                set_alert('warning', _l('woo_customer_exist') . $id);
                redirect('woocommerce/customers');
            }
        } else {
            access_denied('woocommerce');
        }
    }

    public function add_cutomer()
    {
        if (!has_permission('woocommerce', '', 'create')) {
            access_denied('woocommerce');
        }

        $this->check_duplicate_woocomerce_customer();
        $data = $this->input->post();

        $customer_id = $this->import_cust($data);

        if ($customer_id > 0 && !empty($customer_id)) {
            redirect(admin_url('clients/client/' . $customer_id));
        } else {
            set_alert('success', _('customer_add_failed'));
        }
        redirect(admin_url('woocommerce/customers'));
    }

    public function import_cust($data)
    {

        if (!has_permission('customers', '', 'create')) {
            access_denied('customers');
        }

        $id = (isset($data['id'])) ? $data['id'] : [];
        if ($id === '' || !isset($id)) {
            set_alert('danger', _l('invalid_customer_id'));
            redirect(admin_url('woocommerce/customers'));
        }

        $storeId = active_store_id();

        $customer = $this->get_customer($id);
        if (is_string($customer)) {
            set_alert('warning', _l('Something_went_wrong'));
            redirect(admin_url('woocommerce/customers'));
        }
        $contact_data = [

            'firstname' => $customer->first_name,
            'lastname' => $customer->last_name,
            'email' => $customer->email,
            'phonenumber' => $customer->billing->phone,
            'permissions' => (isset($data['permissions'])) ? $data['permissions'] : '',
            'title' => '',
            'send_set_password_email' => true,
            'direction' => (isset($data['direction'])) ? $data['direction'] : '',
            'invoice_emails' => 1,
            'estimate_emails' => 1,
            'credit_note_emails' => 1,
            'contract_emails' => 1,
            'task_emails' => 1,
            'project_emails' => 1,
            'ticket_emails' => 1,
            'is_primary' => 1,
            'password' => ''
        ];
        $customer_data = [
            'woo_id' => $id,
            'store_id' => $storeId,
            'company' => (isset($data['company'])) ? $data['company'] : '',
            'vat' => '',
            'website' =>  '',
            'default_currency' => '',
            'default_language' => '',
            'address' => $customer->billing->address_1,
            'city' => $customer->billing->city,
            'state' => $customer->billing->state,
            'zip' => $customer->billing->postcode,
            'country' => (isset($data['country'])) ? $data['country'] : '',
            'billing_street' => $customer->billing->state,
            'billing_city' => $this->input->post('billing_city'),
            'billing_state' => $customer->billing->state,
            'billing_zip' => $customer->billing->postcode,
            'billing_country' => (isset($data['country'])) ? $data['country'] : '',
            'shipping_street' => $customer->shipping->address_1,
            'shipping_city' => $customer->shipping->city,
            'shipping_state' => $customer->shipping->state,
            'shipping_zip' => $customer->shipping->postcode,
            'shipping_country' => (isset($data['country'])) ? $data['country'] : ''
        ];

        if (isset($data['groups_in'])) {
            $customer_data['groups_in'] =  $data['groups_in'];
        }

        
        // insert data
        $this->load->model('clients_model');

        $output = $this->clients_model->add($customer_data);

        if ($output > 0 && !empty($output)) {
            $contact = $this->clients_model->add_contact($contact_data, $output);
            if ($contact > 0 && !empty($contact)) {
                set_alert('success', _l('contact_add_success'));
            } else {
                set_alert('success', _l('contact_add_failed'));
            }
            hooks()->do_action('after_wc_import_customer', [
                'wc_customer' => $customer,
                'perfex_customer_id' => $output,
                'perfex_contact_id' => $contact ?? null,
            ]);
        }
        return $output;
    }

    public function test_connection($storeId)
    {
        $store = $this->stm->get($storeId);
        $this->woocommerce_module->set_store($store);
        $response = $this->woocommerce_module->test();
        if (is_object($response)) {
            $result['success'] = true;
            $result['message'] = _l("connect_success");
            echo json_encode($result);
        } else {
            $result['success'] = false;
            $result['message'] = $response;
            echo json_encode($result);
        }
    }

    public function manual_check()
    {
        woocommerce_cron();
        set_alert("success", _l("woo_check_successful"));
        redirect(admin_url('woocommerce/stores'));
    }

    public function reset()
    {
        if (!has_permission('woocommerce', '', 'edit')) {
            access_denied('woocommerce');
        }

        $this->woocommerce_model->empty_all();
        update_option('woocommerce_Productpage_no', 1);
        update_option('woocommerce_Orderpage_no', 1);
        update_option('woocommerce_Customerpage_no', 1);
        set_alert("success", _l("woo_reset_success"));
    }

    public function delete($scope)
    {
        if (!has_permission('woocommerce', '', 'delete')) {
            access_denied('woocommerce');
        }
        $storeId = active_store_id();
        $store = $this->stm->get($storeId);
        $this->woocommerce_module->set_store($store);

        $data = $this->input->post();
        $id = $data['productId'];

        $response = $this->woocommerce_module->delete($data, $scope);
        if (is_string($response)) {
            set_alert('warning', _l('failed') . ': ' . $response);
        } else {
            $this->woocommerce_model->delete($id, $scope ,$storeId);
            summary($store);
            set_alert('success', _l('success', 'products'));
        }
        redirect(admin_url("woocommerce/{$scope}"));
    }

    public function get_product($id)
    {
        $storeId = active_store_id();
        $store = $this->stm->get($storeId);
        $this->woocommerce_module->set_store($store);

        $product = $this->woocommerce_module->product($id);
        if (is_string($product)) {
            $resp = [
                'success' => false,
                'message' => $product,
            ];
        } else {
            $resp = [
                'success' => true,
                'message' => $product,
            ];
        }
        echo json_encode($resp);
    }

    public function update($scope)
    {
        if (!has_permission('woocommerce', '', 'edit')) {
            access_denied('woocommerce');
        }

        $this->load->library('form_validation');
        $pdata = $this->input->post();

        $storeId = active_store_id();
        $store = $this->stm->get($storeId);
        $this->woocommerce_module->set_store($store);
        if ($scope == 'product') {

            $this->form_validation->set_rules('productId', _l('order'), 'trim|required');
            $this->form_validation->set_rules('name', _l('order'), 'trim|required');
            $this->form_validation->set_rules('regular_price', _l('price'), 'trim|required|numeric');
            $this->form_validation->set_rules('xdescription', _l('short_description'), 'trim|required');
            $this->form_validation->set_message('numeric', 'enter a valid price');

            if ($this->form_validation->run() == FALSE) {
                set_alert('warning', _l('fields_required'));
                redirect(admin_url('woocommerce/products/'));
            }
            
            $id = $pdata['productId'];
            $data = [];

            $data['name'] = $pdata['name'];
            $data['regular_price'] = $pdata['regular_price'];
            $data['status'] = $pdata['status'];
            $data['short_description'] = $pdata['xdescription'];
            $response = $this->woocommerce_module->update($id, $data, $scope);
            if (is_string($response)) {
                set_alert('warning', _l('failed') . ': ' . $response);
            } else {
                $this->woocommerce_model->update_product($id, $data, $storeId);
                set_alert('success', _l('success', 'products'));
            }
            redirect(admin_url('woocommerce/products/' . $id));
        }

        if ($scope == 'customer') {
            $this->form_validation->set_rules('username', _l('username'), 'trim|required');
            $this->form_validation->set_rules('custId', _l('custId'), 'trim|required|numeric');
            $this->form_validation->set_rules('email', _l('email'), 'trim|required');
            $this->form_validation->set_rules('firstName', _l('firstName'), 'trim|required');
            $this->form_validation->set_rules('lastName', _l('lastName'), 'trim|required');

            if ( isset($pdata["change_password"]) &&($pdata["change_password"] == "1")){

                if($pdata['password'] != $pdata['passwordr']){
                    set_alert("success",_l("passwords_dont_match"));
                    redirect(admin_url('woocommerce/customers/'));
                    return false;
                }

            $this->form_validation->set_rules('password', _l('password'), 'trim|required');
            $this->form_validation->set_rules('passwordr', _l('passwordr'), 'trim|required');
            }

            $this->form_validation->set_message('numeric', 'enter a valid price');

            if ($this->form_validation->run() == FALSE) {
                set_alert('warning', _l('fields_required'));
                redirect(admin_url('woocommerce/customers/'));
                return false;
            }
            $id = $pdata['custId'];
            $data = [];

            $data['username'] = $pdata['username'];
            $data['email'] = $pdata['email'];
            $data['first_name'] = $pdata['firstName'];
            $data['last_name'] = $pdata['lastName'];

            
        $storeId = active_store_id();
        $store = $this->stm->get($storeId);
        $this->woocommerce_module->set_store($store);

            if(isset($pdata["change_password"]) && ($pdata["change_password"] == "1") ){
                $data['password'] = $pdata['password'];
            }
            $response = $this->woocommerce_module->update($id, $data, $scope);
            if (is_string($response)) {
                set_alert('warning', _l('failed') . ': ' . $response);
            } else {
                $this->woocommerce_model->update_customer($id, $data, $storeId);
                set_alert('success', _l('success', 'customer'));
            }
            summary($store);
            redirect(admin_url('woocommerce/customers/' . $id));
        }
    }

    public function setItemId($id,$pid){
        $storeId = active_store_id();
        return $this->woocommerce_model->add_item_id($id,$pid,$storeId);
    }

    public function getItemId($id){
        $storeId = active_store_id();
        echo json_encode($this->woocommerce_model->get_item_id($id,$storeId));
    }
    public function store()
    {
        echo active_store_id();
    }
}

