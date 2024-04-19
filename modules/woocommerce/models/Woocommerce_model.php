<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Woocommerce_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_user($id = '')
    {
        $this->db->select('*');
        if ($id != '') {
            $this->db->where('woo_id', $id);
        }
        return $this->db->get(db_prefix() . 'clients')->result_array();
    }

    public function is_wc_customer_exist($id,$storeId)
    {
        return  (bool) total_rows(db_prefix() . 'clients', ['woo_id' => $id,'woo_store'=>$storeId]) > 0;
    }

    public function is_wc_invoice_exist($id,$storeId)
    {
        return  (bool) total_rows(db_prefix() . 'invoices', ['wco_id' => $id,'woo_store'=>$storeId]) > 0;
    }

    public function customer_by_wc_id($customer_id,$storeId)
    {
        $this->db->where('woo_id', $customer_id);
        $this->db->where('woo_store', $storeId);
        return $this->db->get(db_prefix() . 'clients')->row->id;
    }

    public function update_order($data, $storeId)
    {
        $order_id = $data['orderId'];
        $status = $data['status'];
        $this->db->set('status', $status);
        $this->db->where('order_id', $order_id);
        $this->db->where('store_id', $storeId);
        $this->db->update(db_prefix() . 'woocommerce_orders');
    }

    public function delete_order($data, $storeId)
    {
        $order_id = $data['orderid'];
        $this->db->where('order_id', $order_id);
        $this->db->where('store_id', $storeId);
        $this->db->delete(db_prefix() . 'woocommerce_orders');
    }

    public function cron_orders($data, $storeId)
    {

        $data['store_id'] = $storeId;
        $this->db->insert(db_prefix() . 'woocommerce_orders', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function cron_customers($data, $storeId)
    {
        $data['store_id'] = $storeId;
        $this->db->insert(db_prefix() . 'woocommerce_customers', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function cron_products($data, $storeId)
    {
        $data['store_id'] = $storeId;
        $this->db->insert(db_prefix() . 'woocommerce_products', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function is_summary_exist($store_id)
    {
        return  (bool) total_rows(db_prefix() . 'woocommerce_summary', ['store_id' => $store_id]) > 0;
    }

    public function is_woocustomer_exist($id,$storeId)
    {
        return  (bool) total_rows(db_prefix() . 'woocommerce_customers ', ['woo_customer_id' => $id,'store_id'=>$storeId]) > 0;
    }

    public function is_wooproduct_exist($id,$storeId)
    {
        return  (bool) total_rows(db_prefix() . 'woocommerce_products ', ['product_id' => $id,'store_id'=>$storeId]) > 0;
    }

    public function is_wooorder_exist($id,$storeId)
    {
        return  (bool) total_rows(db_prefix() . 'woocommerce_orders ', ['order_id' => $id,'store_id'=>$storeId]) > 0;
    }

    public function add_summary($data, $storeId)
    {
        $data['store_id'] = $storeId;
        $this->db->insert(db_prefix() . 'woocommerce_summary', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function update_summary($data, $storeId)
    {
        $this->db->where('store_id', $storeId);
        $this->db->update(db_prefix() . 'woocommerce_summary', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_summary($storeId)
    {
        $this->db->where('store_id', $storeId);
        return $this->db->get(db_prefix() . 'woocommerce_summary')->row();
    }
 
    public function empty_all()
    {
        $this->db->empty_table(db_prefix() . 'woocommerce_summary');
        $this->db->empty_table(db_prefix() . 'woocommerce_orders');
        $this->db->empty_table(db_prefix() . 'woocommerce_customers');
        $this->db->empty_table(db_prefix() . 'woocommerce_products');
    }

    public function cron_updates($data, $scope, $storeId)
    {
        if (!$scope) {
            return false;
        } else {
            $this->db->where('store_id', $storeId);

            if (isset($data['product_id'])) {
                $this->db->where('product_id', $data['product_id']);
            } elseif (isset($data['order_id'])) {
                $this->db->where('order_id', $data['order_id']);
            } elseif (isset($data['woo_customer_id'])) {
                $this->db->where('woo_customer_id', $data['woo_customer_id']);
            } else {
                return;
            }
            $this->db->update(db_prefix() . 'woocommerce_' . $scope, $data);
            if ($this->db->affected_rows() > 0) {
                return true;
            }
        }

        return false;
    }

    public function delete($id, $scope, $storeId)
    {
        $wh = substr($scope, 0, -1);
        if($wh =='customer'){
            $wh = 'woo_'.$wh ;
        }
        $this->db->where($wh . '_id', $id);
        $this->db->where('store_id', $storeId);
        $this->db->delete(db_prefix() . 'woocommerce_' . $scope);
    }

    public function update_product($id, $data, $storeId)
    {
        $this->db->set('name', $data['name']);
        $this->db->set('status', $data['status']);
        $this->db->set('price', $data['regular_price']);
        $this->db->where('product_id', $id);
        $this->db->where('store_id', $storeId);
        $this->db->update(db_prefix() . 'woocommerce_products');
    }

    public function update_customer($id, $data, $storeId)
    {
        $this->db->set('first_name', $data['first_name']);
        $this->db->set('last_name', $data['last_name']);
        $this->db->set('email', $data['email']);
        $this->db->set('username', $data['username']);
        $this->db->where('woo_customer_id', $id);
        $this->db->where('store_id', $storeId);
        $this->db->update(db_prefix() . 'woocommerce_customers');
    }

    public function add_item_id( $id, $pid, $storeId)
    {
        $this->db->set('itemid', $id);
        $this->db->where('product_id', $pid);
        $this->db->where('store_id', $storeId);
        $this->db->update(db_prefix() . 'woocommerce_products');
    }

    public function get_item_id($id, $storeId)
    {
        $id = $id;
        $this->db->where('product_id', $id);
        $this->db->where('store_id', $storeId);
        $id = $this->db->get(db_prefix() . 'woocommerce_products')->row_object() ;
        if($id) return $id;
        return false;
    }
}


