<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Woocommerce_invoice extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!has_permission('woocommerce', '', 'view')) {
            access_denied('woocommerce');
        }
        $this->load->library('woocommerce/woocommerce_module');
        $this->load->model('woocommerce/woocommerce_model');
        $this->load->model('woocommerce/stores_model', 'stm');
        $this->load->library('form_validation');
    }

    public function check_customer()
    {
        $wooId = trim($this->input->post('id'));

        $storeId = active_store_id();
        $response    = [
            'exists'  => (bool) total_rows(db_prefix() . 'clients', ['woo_id' => $wooId, 'store_id' => $storeId]) > 0,
            'message' => _l('customer_exist_not'),
        ];
        echo json_encode($response);
    }

    public function check_invoice()
    {
        $id = trim($this->input->post('wco_id'));
        $storeId = active_store_id();
        $response    = [
            'exists'  => (bool) total_rows(db_prefix() . 'invoices', ['wco_id' => $id, 'store_id' => $storeId]) > 0,
            'message' => _l('invoice_exists_info'),
        ];
        echo json_encode($response);
    }

    public function create_invoice()
    {

        if (!has_permission('invoices', '', 'create')) {
            access_denied('invoices');
        }

        $orderid = $this->input->post('orderid');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('clientid', _l('client'), 'trim|required');
        $this->form_validation->set_rules('currency', _l('currency'), 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            set_alert('warning', _l('fields_required'));
            redirect(admin_url('woocommerce/order/' . $orderid));
        } else {

            $clientid = $this->input->post('clientid');
            $storeId = active_store_id();
            $store = $this->stm->get($storeId);
            $this->woocommerce_module->set_store($store);
            $invoice                     = $this->woocommerce_module->order($orderid);
            if (!is_object($invoice)) {
                set_alert('warning', 'woocommerce store did not respond. please confirm store is live and try again');
                redirect(admin_url('woocommerce/order/' . $orderid));
            }

            $invoicedata                 = new stdClass();
            $invoicedata->subtotal         = $invoice->total;
            $invoicedata->total         = $invoice->total;
            $invoicedata->datas            = $invoice;
            $invoicedata->tax_percent   = null;
            $invoicedata->currency   = $this->input->post('currency');

            if ((isset($clientid)) && ($clientid != 0)) {
                $client = $this->clients_model->get($clientid);
                $new_invoice_data = create_woocomerce_invoice_data($client, $invoicedata);
                $this->load->model('invoices_model');
                $id = $this->invoices_model->add($new_invoice_data);
                if ($id) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'invoices', [
                        'addedfrom' => 2,
                    ]);
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'invoices', [
                        'wco_id' => $invoice->id,
                        'store_id' => $storeId,
                    ]);

                    $this->db->where('order_id', $orderid);
                    $this->db->where('store_id', $storeId);
                    $this->db->update(db_prefix() . 'woocommerce_orders', [
                        'invoice_id' => $id,
                    ]);

                    if (($invoice->status == 'processing') ||  ($invoice->status == 'çompleted')) {
                        $payment_data['paymentmode']   = 'store';
                        $payment_data['amount']        = $new_invoice_data['total'];
                        $payment_data['invoiceid']     = $id;
                        $payment_data['transactionid'] = $invoice->order_key;
                        $payment_data['paymentmethod'] = 'WC-' . $invoice->payment_method;
                        $this->load->model('payments_model');

                        $this->payments_model->add($payment_data);
                    }

                    hooks()->do_action('after_wc_invoice_imported', [
                        'invoice_data' => $new_invoice_data,
                        'invoice_id' => $id
                    ]);

                    set_alert('success', _l('added_successfully', _l('invoice')));
                    redirect(admin_url("woocommerce/order/" . $orderid));
                } else {
                    set_alert("warning", _l("invoice_add_failed"));
                    redirect(admin_url("woocommerce/order/" . $orderid));
                }
            } else {
                set_alert("warning", _l("invalid_client"));
                redirect(admin_url("woocommerce/order/" . $orderid));
            }
        }
    }
}
