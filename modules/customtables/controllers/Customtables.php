<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customtables extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->app_modules->is_inactive('customtables') ? access_denied() : '';

        $this->load->model('customtables_model');
        $this->load->helper('customtables');
        $this->load->library('customtables_lib');
        $this->config->load(CUSTOMTABLES_MODULE . '/config');
    }

    public function index()
    {
        $tab = $this->input->get('group');
        //\modules\customtables\core\Apiinit::ease_of_mind(CUSTOMTABLES_MODULE);
        if (!$tab) {
            $tab = 'leads';
        }

        $data['tabs'] = config_item('datatables_all_tabs');
        if (!in_array($tab, $data['tabs'])) {
            $data['tab'] = getFilterDatatableTab($data['tabs'], $tab);
        } else {
            $data['tab']['slug'] = $tab;
            $data['tab']['view'] = 'includes/' . $tab;
        }

        if (!$data['tab']) {
            show_404();
        }

        $data['title'] = _l('customtables');
        $this->load->view('manage', $data);
    }

    public function storeColumns()
    {
        $postedData = $this->input->post();
        foreach ($postedData as $key => $value) {
            update_option($key . '_show_columns', json_encode($value));
        }
    }

    public function resetDefaultTable($table)
    {
        if ($table == 'customers') {
            $table = 'clients';
        }
        $res = delete_option($table . '_show_columns');
        echo json_decode($res);
    }

    public function tableDesign()
    {
        $data['title'] = _l('table_design');
        $this->load->view('table_design', $data);
    }

    public function getSampleTable()
    {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        $this->app->get_table_data(module_views_path(CUSTOMTABLES_MODULE, 'tables/sample_table'));
    }

    public function saveTableStyle()
    {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        update_option('table_custom_style', $this->input->post('data'));

        $custom_css = trim($this->input->post('table_custom_css'));
        update_option('custom_css_for_table', nl2br($custom_css));
    }

    public function resetTableStyle()
    {
        update_option('table_custom_style', '[]');
        //\modules\customtables\core\Apiinit::the_da_vinci_code(CUSTOMTABLES_MODULE);
        //\modules\customtables\core\Apiinit::ease_of_mind(CUSTOMTABLES_MODULE);
        redirect(admin_url('customtables/tableDesign'));
    }
}

/* End of file Customtables.php */
