<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Elite_custom_js_css_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->title = (is_custom_js()) ? 'Custom JS' : 'Custom CSS';
    }

    /**
     * @param  integer (optional)
     * @return object
     * Get single goal
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(ElITE_CUSTOM_JS_CSS_TABLE_NAME)->row();
        }

        return $this->db->get(ElITE_CUSTOM_JS_CSS_TABLE_NAME)->result_array();
    }

    /**
     * Add new custom css
     * @param mixed $data All $_POST dat
     * @return mixed
     */
    public function add()
    {
        $data = $this->input->post();
        $data['code'] = trim($this->input->post('code', FALSE));
        $data['staff_id'] = (!empty($this->session->staff_user_id)) ? $this->session->staff_user_id : 0;
        $data['created_by'] = (!empty($this->session->staff_user_id)) ? $this->session->staff_user_id : 0;

        $this->db->insert(ElITE_CUSTOM_JS_CSS_TABLE_NAME, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New ' . $this->title . ' Added [ID:' . $insert_id . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Update Custom CSS
     * @param  mixed $data All $_POST data
     * @param  mixed $id   custom css id
     * @return boolean
     */
    public function update($id)
    {

        $data = $this->input->post();
        $data['code'] = trim($this->input->post('code', FALSE));
        $data['staff_id'] = (!empty($this->session->staff_user_id)) ? $this->session->staff_user_id : 0;
        $data['updated_by'] = (!empty($this->session->staff_user_id)) ? $this->session->staff_user_id : 0;

        $this->db->where('id', $id);
        $this->db->update(ElITE_CUSTOM_JS_CSS_TABLE_NAME, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->title . ' Updated [ID:' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Status Update Custom CSS
     * @param  mixed $data All $_POST data
     * @param  mixed $id   custom css id
     * @return boolean
     */
    public function status_update($data, $id)
    {
        $data['staff_id'] = (!empty($this->session->staff_user_id)) ? $this->session->staff_user_id : 0;
        $data['updated_by'] = (!empty($this->session->staff_user_id)) ? $this->session->staff_user_id : 0;

        $this->db->where('id', $id);
        $this->db->update(ElITE_CUSTOM_JS_CSS_TABLE_NAME, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->title . ' Status Updated [ID:' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Delete Custom CSS
     * @param  mixed $id custom css id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(ElITE_CUSTOM_JS_CSS_TABLE_NAME);
        if ($this->db->affected_rows() > 0) {
            log_activity($this->title . ' Deleted [ID:' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Get details
     * @return data
     */
    public function get_info($id)
    {

        $this->db->from(ElITE_CUSTOM_JS_CSS_TABLE_NAME);
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //create object with empty properties.
            $fields = $this->db->list_fields(ElITE_CUSTOM_JS_CSS_TABLE_NAME);
            $person_obj = new stdClass;

            foreach ($fields as $field) {
                $person_obj->$field = '';
            }

            return $person_obj;
        }
    }

    public function nameExist($name, $area_type, $code_type, $id)
    {
        $this->db->where('name', $name);
        $this->db->where('area_type', $area_type);
        $this->db->where('code_type', $code_type);
        $query = $this->db->get(ElITE_CUSTOM_JS_CSS_TABLE_NAME);
        if ($query->num_rows() > 0) {
            $result = $query->row();
            if ($result->id == $id) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}