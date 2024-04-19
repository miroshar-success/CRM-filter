<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_200 extends App_module_migration
{
    public function up()
    {
        add_option('woocommerce_Productpage_no', 1);
        add_option('woocommerce_Orderpage_no', 1);
        add_option('woocommerce_Customerpage_no', 1);
        
        $CI = &get_instance();
        $CI->db->query(

            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "woocommerce_orders (

        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

        `order_id` int(11) NOT NULL,

        `order_number` varchar(50) NOT NULL,

        `customer_id` int(11) NOT NULL,

        `address` TEXT DEFAULT NULL,

        `phone` varchar(50) DEFAULT NULL,

        `status` varchar(100) DEFAULT NULL,

        `currency` varchar(10) DEFAULT NULL,

        `date_created` date DEFAULT NULL,

        `date_modified` date DEFAULT NULL,

        `total` varchar(30) DEFAULT NULL,

        `invoice_id` int(30) DEFAULT NULL,

        `store_id` int(5) DEFAULT NULL,

        PRIMARY KEY (`id`)

        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"

        );

        $CI->db->query(

            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "woocommerce_products (

        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

        `product_id` int(11) NOT NULL,

        `itemid` int(11) DEFAULT NULL,

        `name` varchar(500) DEFAULT NULL,

        `permalink` varchar(500) DEFAULT NULL,

        `type` varchar(50) DEFAULT NULL,

        `status` varchar(50) DEFAULT NULL,

        `sku` varchar(50) DEFAULT NULL,

        `price` varchar(20) DEFAULT NULL,

        `sales` varchar(20) DEFAULT NULL,

        `picture` TEXT DEFAULT NULL,

        `category` TEXT DEFAULT NULL,

        `date_created` date DEFAULT NULL,

        `date_modified` date DEFAULT NULL,

        `store_id` int(5) DEFAULT NULL,

        PRIMARY KEY (`id`)

        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"

        );

        $CI->db->query(

            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "woocommerce_customers (

        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

        `woo_customer_id` int(11) NOT NULL,

        `userid` int(11) DEFAULT NULL,

        `email` varchar(190) DEFAULT NULL,

        `first_name` varchar(100) DEFAULT NULL,

        `last_name` varchar(100) DEFAULT NULL,

        `phone` varchar(50) DEFAULT NULL,

        `role` varchar(50) DEFAULT NULL,

        `username` varchar(100) DEFAULT NULL,

        `avatar_url` TEXT DEFAULT NULL,

        `store_id` int(5) DEFAULT NULL,

        PRIMARY KEY (`id`)

        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"

        );


        $CI->db->query(

            "CREATE TABLE IF NOT EXISTS " . db_prefix() . "woocommerce_summary(

        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

        `store_id` int(5) DEFAULT NULL ,

        `customers` TEXT DEFAULT NULL,

        `orders` TEXT DEFAULT NULL,

        `products` TEXT DEFAULT NULL,

        PRIMARY KEY (`id`)

        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"

        );
    }
}
