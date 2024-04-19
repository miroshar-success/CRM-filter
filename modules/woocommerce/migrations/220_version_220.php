<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_220 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $CI->db->query(

            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "woocommerce_stores(
        
                `store_id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
        
                `name` VARCHAR(255) NOT NULL,
        
                `url` VARCHAR(255) NOT NULL,
        
                `key` VARCHAR(255) NOT NULL,
        
                `secret` VARCHAR(255) NOT NULL,
        
                `productPage` INT(5) DEFAULT 1,
        
                `orderPage` INT(5) DEFAULT 1,
        
                `customerPage` INT(5) DEFAULT 1,
        
                `date_created` DATETIME NOT NULL,
        
                PRIMARY KEY (`store_id`)
        
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"

        );

        $CI->db->query(

            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "woocommerce_assigned(
        
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
        
                `store_id` int NOT NULL,
        
                `staff_id` int NOT NULL,
        
                PRIMARY KEY (`id`)
        
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"

        );


        if (!$CI->db->field_exists('store_id', 'staff')) {
            $CI->load->dbforge();
            $fields =  array(
                'store_id' => array(
                    'type' => 'INT',
                    'constraint' => 9,
                    'null' => TRUE
                )
            );
            $CI->dbforge->add_column('staff', $fields);
        }

        if (!$CI->db->field_exists('store_id', 'clients')) {
            $CI->load->dbforge();
            $fields =  array(
                'store_id' => array(
                    'type' => 'INT',
                    'constraint' => 9,
                    'null' => TRUE
                )
            );
            $CI->dbforge->add_column('clients', $fields);
        }

        if (!$CI->db->field_exists('store_id', 'invoices')) {
            $CI->load->dbforge();
            $fields =  array(
                'store_id' => array(
                    'type' => 'INT',
                    'constraint' => 9,
                    'null' => TRUE
                )
            );
            $CI->dbforge->add_column('invoices', $fields);
        }
        $this->create_default_store();
    }

    public function create_default_store()
    {
        $k = get_option('woocommerce_consumer_key');
        $s = get_option('woocommerce_consumer_secret');
        $u = get_option('woocommerce_client');
        $cuid = get_staff_user_id();
        $data = [
            'name'  => 'Default Store',
            'url'   => $u,
            'key'   => $k,
            'secret' => $s,
            'assignees' => [0, $cuid],
            'date_created' => date('c')
        ];

        $CI = &get_instance();
        $CI->load->model('woocommerce/stores_model', 'stm');
        $id = $CI->stm->create($data);
        if ($id) {
            $this->update_invoices($id);
            $this->update_clients($id);
            $this->update_customers($id);
            $this->update_products($id);
            $this->update_orders($id);
        }
        update_option('woocommerce_consumer_key', '');
        update_option('woocommerce_consumer_secret', '');
        update_option('woocommerce_client', '');
        update_option('woocommerce_Productpage_no', 1);
        update_option('woocommerce_Orderpage_no', 1);
        update_option('woocommerce_Customerpage_no', 1);
    }

    public function update_invoices($store_id)
    {
        $CI = &get_instance();
        $CI->db->set('store_id', $store_id);
        $CI->db->where('wco_id IS NOT NULL', null, false);
        $CI->db->update(db_prefix() . 'invoices');
    }

    public function update_clients($store_id)
    {
        $CI = &get_instance();
        $CI->db->set('store_id', $store_id);
        $CI->db->where('woo_id IS NOT NULL', null, false);
        $CI->db->update(db_prefix() . 'clients');
    }

    public function update_customers($store_id)
    {
        $CI = &get_instance();
        $CI->db->set('store_id', $store_id);
        $CI->db->where('store_id', 1);
        $CI->db->update(db_prefix() . 'woocommerce_customers');
    }

    public function update_products($store_id)
    {
        $CI = &get_instance();
        $CI->db->set('store_id', $store_id);
        $CI->db->where('store_id', 1);
        $CI->db->update(db_prefix() . 'woocommerce_products');
    }

    public function update_orders($store_id)
    {
        $CI = &get_instance();
        $CI->db->set('store_id', $store_id);
        $CI->db->where('store_id', 1);
        $CI->db->update(db_prefix() . 'woocommerce_orders');
    }
}
