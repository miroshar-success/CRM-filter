<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
class Woocommerce_module
{
    private $url;
    private $key;
    private $secret;
    private $query_auth;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function connect()
    {
        $options = ['wp_api' => true, 'version' => 'wc/v3', 'query_string_auth' => $this->query_auth, 'verify_ssl'=>false];
        $connect = new Client($this->url, $this->key, $this->secret, $options);
        return $connect;
    }

    public function test(){
        try {
          return $this->connect()->get('data/currencies/current');
        } catch (HttpClientException $e) {
          return $e->getMessage();
        }

    }

    public function sales($query = ['period' => 'year'])
    {
        $sales = null;
        try {
            $sales = $this->connect()->get('reports/sales');
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $sales = $error;
        }

        return  $sales;
    }

    public function customer($id)
    {
        $customer = null;
        try {
            $customer = $this->connect()->get('customers/' . $id);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $customer = $error;
        }
        return  $customer;
    }

    public function order($id)
    {
        $order = null;
        try {
            $order = $this->connect()->get('orders/' . $id);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $order = $error;
        }
        return  $order;
        
    }

    public function update_order($data)
    {
        $update = null;
        $id = $data['orderId'];
        $status = $data['status'];
        try {
            $update = $this->connect()->put('orders/' . $id, array('status' => $status));
            log_activity(' Product ' . $id . ' has been updated as ' . $status);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $update = $error;
        }
        return $update;
    }

    public function delete_order($data)
    {
        $update = null;
        $id = $data['orderid'];
        try {
            $update = $this->connect()->delete('orders/' . $id, ['force' => true]);
            log_activity('Order: ' . $id . ' was Successfully deleted');
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $update = $error;
        }
        return $update;
    }

    public function cron($endpoint,$params)
    {
        try {
            $response = $this->connect()->get($endpoint, $params);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $response = $error;
        }   
        return $response;
    }

    public function cronReport($data)
    {
        try {
            $report = $this->connect()->get('reports/'.$data);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $report = $error;
        }   
        return $report;
    }

    public function delete($data,$scope)
    {
        $id = $data['productId'];
        $route = $scope.'/'. $id ;
        try {
            $update = $this->connect()->delete($route, ['force' => true]);
            log_activity('Product : ' . $id . ' was Successfully deleted');
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $update = $error;
        }
        return $update;
    }

    public function product($id)
    {
        $product = null;
        try {
            $product = $this->connect()->get('products/' . $id);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $product = $error;
        }
        return  $product;
    }

    public function update($id,$data,$scope)
    {
        $product = null;
        try {
            $product = $this->connect()->put($scope.'s/'.$id,$data );
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $product = $error;
        }
        return  $product;
    }

    public function set_store($store)
    {
        $this->url = $store->url;
        $this->key = $store->key;
        $this->secret = $store->secret;
        $this->query_auth = $store->query_auth;
    }
}
