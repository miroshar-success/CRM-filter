<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Stores_model extends App_Model
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix() . 'woocommerce_stores';
    }

    public function create($data)
    {
        if (isset($data['assignees'])) {
            $assignees = $data['assignees'];
            unset($data['assignees']);
        }

        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            if (isset($assignees)) {
                $_pm['assignees'] = $assignees;
                $this->add_edit_assignees($_pm, $insert_id);
            }

            return $insert_id;
        }

        return false;
    }

    public function get($store_id)
    {
        $this->db->where('store_id', $store_id);
        return $this->db->get($this->table)->row();
    }

    public function get_stores()
    {
        return $this->db->get($this->table)->result();
    }

    public function update($store_id, $data)
    {
        $affectedRows = 0;
        if (isset($data['assignees'])) {
            $assignees = $data['assignees'];
            unset($data['assignees']);
        }

        $this->db->where('store_id', $store_id);
        $this->db->update($this->table, $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        $_pm = [];
        if (isset($assignees)) {
            $_pm['assignees'] = $assignees;
        }
        if ($this->add_edit_assignees($_pm, $store_id)) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('store_id', $id);
        $this->db->delete($this->table);
        if ($this->db->affected_rows() > 0) {
            $this->db->where('store_id', $id);
            $this->db->delete(db_prefix() . 'woocommerce_assigned');
            return true;
        }
        return false;
    }

    public function add_edit_assignees($data, $id)
    {
        $affectedRows = 0;
        if (isset($data['assignees'])) {
            $assignees = $data['assignees'];
        }
        $this->db->select('name');
        $this->db->where('store_id', $id);
        $store      = $this->db->get(db_prefix() . 'woocommerce_stores')->row();
        $store_name = $store->name;

        $assignees_in = $this->get_assignees($id);
        if (sizeof($assignees_in) > 0) {
            foreach ($assignees_in as $store_member) {
                if (isset($assignees)) {
                    if (!in_array($store_member['staff_id'], $assignees)) {
                        $this->db->where('store_id', $id);
                        $this->db->where('staff_id', $store_member['staff_id']);
                        $this->db->delete(db_prefix() . 'woocommerce_assigned');
                        if ($this->db->affected_rows() > 0) {
                            remove_store($store_member['staff_id']);
                            log_activity('store_activity_removed_assignees ' . ($store_member['staff_id']));
                            $affectedRows++;
                        }
                    }
                } else {
                    $this->db->where('store_id', $id);
                    $this->db->delete(db_prefix() . 'woocommerce_assigned');
                    if ($this->db->affected_rows() > 0) {
                        $affectedRows++;
                    }
                }
            }
            if (isset($assignees)) {
                foreach ($assignees as $staff_id) {
                    $this->db->where('store_id', $id);
                    $this->db->where('staff_id', $staff_id);
                    $_exists = $this->db->get(db_prefix() . 'woocommerce_assigned')->row();
                    if (!$_exists) {
                        if (empty($staff_id)) {
                            continue;
                        }
                        $this->db->insert(db_prefix() . 'woocommerce_assigned', [
                            'store_id' => $id,
                            'staff_id'   => $staff_id,
                        ]);
                        if ($this->db->affected_rows() > 0) {
                            if ($staff_id != get_staff_user_id()) {
                                $notified = add_notification([
                                    'fromuserid'      => get_staff_user_id(),
                                    'description'     => 'staff_added_as_store_member',
                                    'link'            => 'woocommerce/stores',
                                    'touserid'        => $staff_id,
                                    'additional_data' => serialize([
                                        $store_name,
                                    ]),
                                ]);
                            }


                            log_activity('store_activity_added_assignees ' . ($staff_id));
                            $affectedRows++;
                        }
                    }
                }
            }
        } else {
            if (isset($assignees)) {
                foreach ($assignees as $staff_id) {
                    if (empty($staff_id)) {
                        continue;
                    }
                    $this->db->insert(db_prefix() . 'woocommerce_assigned', [
                        'store_id' => $id,
                        'staff_id'   => $staff_id,
                    ]);
                    if ($this->db->affected_rows() > 0) {
                        $this->load->helper('woocommerce/woocommerce');
                        set_store($id, $staff_id);
                        if ($staff_id != get_staff_user_id()) {
                            $notified = add_notification([
                                'fromuserid'      => get_staff_user_id(),
                                'description'     => 'staff_added_as_store_member',
                                'link'            => 'woocommerece/stores',
                                'touserid'        => $staff_id,
                                'additional_data' => serialize([
                                    $store_name,
                                ]),
                            ]);
                        }
                        log_activity('store_activity_added_staff ' . ($staff_id));
                        $affectedRows++;
                    }
                }
            }
        }

        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    public function get_assignees($id)
    {
        $this->db->select("store_id,staff_id");
        $this->db->where('store_id', $id); 
        return $this->db->get(db_prefix() . 'woocommerce_assigned')->result_array();
    }

    public function staff_stores($staff_id)
    {

        $_store_id = db_prefix() . "woocommerce_assigned.store_id";
        $woo_stores = db_prefix() . "woocommerce_stores.store_id";
        $this->db->select("$_store_id,name");
        $this->db->join($this->table, $woo_stores . '=' . $_store_id);
        $this->db->where('staff_id', $staff_id);
        return $this->db->get(db_prefix() . 'woocommerce_assigned')->result_array();
    }

    public function empty_store($id)
    {
        $this->db->where('store_id', $id);
        $this->db->delete(db_prefix() . 'woocommerce_products');
        $this->db->where('store_id', $id);
        $this->db->delete(db_prefix() . 'woocommerce_customers');
        $this->db->where('store_id', $id);
        $this->db->delete(db_prefix() . 'woocommerce_orders');
        $this->db->where('store_id', $id);
        $this->db->delete(db_prefix() . 'woocommerce_summary');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}
