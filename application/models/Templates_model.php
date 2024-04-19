<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Templates_model extends App_Model
{
    /**
     * Create new template
     *
     * @param  array $data
     *
     * @return int|boolean
     */
    public function create($data)
    {
        $data = hooks()->apply_filters('before_template_added', $data);

        $this->db->insert('templates', $data);

        $template_id = $this->db->insert_id();

        if ($template_id) {
            log_activity('New Template Added [ID: ' . $template_id . ', ' . $data['name'] . ']');

            hooks()->do_action('new_template_added', $template_id);

            return $template_id;
        }

        return false;
    }

    /**
     * Get templates by string
     *
     * @param string $type
     * @param array $where
     *
     * @return array
     */
    public function getByType($type, $where = [])
    {
        $this->db->where('type', $type);
        $this->db->where($where);
        $this->db->order_by('name', 'asc');

        return $this->db->get('templates')->result_array();
    }

    /**
     * Find template by given id
     *
     * @return \stdClass
     */
    public function find($id)
    {
        $this->db->where('id', $id);

        return $this->db->get('templates')->row();
    }

    /**
     * Update template
     *
     * @param  int $id
     * @param  array $data
     *
     * @return boolean
     */
    public function update($id, $data)
    {
        $data = hooks()->apply_filters('before_template_updated', $data, $id);
        $name = $this->find($id)->name;

        $this->db->where('id', $id);
        $this->db->update('templates', $data);

        if ($this->db->affected_rows() > 0) {
            log_activity('Template updated [Name: ' . $name . ']');
            hooks()->do_action('after_template_updated', $id);

            return true;
        }

        return false;
    }

    /**
     * Delete template
     * @param  array $id
     *
     * @return boolean
     */
    public function delete($id)
    {
        hooks()->do_action('before_template_deleted', $id);

        $name = $this->find($id)->name;

        $this->db->where('id', $id);
        $this->db->delete('templates');

        log_activity('Template Deleted [Name: ' . $name . ']');

        hooks()->do_action('after_template_deleted', $id);

        return true;
    }
}