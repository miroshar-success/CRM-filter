<?php

defined('BASEPATH') or exit('No direct script access allowed');

class EliteCustomJsCss extends AdminController
{

    public function __construct()
    {
        parent::__construct();

        if (!is_admin() || !defined('ElITE_CUSTOM_JS_CSS_MODULE_NAME')) {
            access_denied('elite_custom_js_css');
        }
        $this->load->helper('language');
        $this->load->helper('elite_custom_js_css/elite_custom_js_css');
        $this->load->model('elite_custom_js_css_model');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('elite_custom_js_css', 'table'));
        }

        $data['title'] = $this->title;
        $this->load->view('manage', $data);
    }

    public function form($id)
    {

        if ($id == '-1') {
            $title = _l('elite_add_new_css', $this->title);
        } else {
            $title = _l('elite_edit_css', $this->title);
        }

        $data['controller_name'] = (is_custom_js()) ? 'elite_custom_js' : 'elite_custom_css';
        $data['id'] = $id;
        $data['title'] = $title;
        $data['form_details'] = $this->elite_custom_js_css_model->get_info($id);

        $this->load->view('form', $data);
    }

    public function save()
    {
        if ($this->input->post()) {

            $id = $this->input->post('id');

            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[150]|callback_nameExist');
            $this->form_validation->set_rules('code', 'Code', 'trim|required');
            $this->form_validation->set_rules('area_type', 'Location', 'trim|required');
            $this->form_validation->set_rules('code_view', 'Script Tag', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $errormsg = [];
                if (form_error('name')) {
                    $errormsg['name'] = trim(form_error('name'));
                }
                if (form_error('code')) {
                    $errormsg['code'] = trim(form_error('code'));
                }
                if (form_error('area_type')) {
                    $errormsg['area_type'] = trim(form_error('area_type'));
                }
                if (form_error('status')) {
                    $errormsg['status'] = trim(form_error('status'));
                }
                if (form_error('code_view')) {
                    $errormsg['code_view'] = trim(form_error('code_view'));
                }

                $result = ['level' => 'error', 'message' => $errormsg];
            } else {
                unset($_POST['id']);

                if ($id == '-1') {
                    $id = $this->elite_custom_js_css_model->add();
                    if ($id) {
                        set_alert('success', _l('added_successfully', $this->title));
                    }
                } else {
                    if ($this->elite_custom_js_css_model->update($id)) {
                        set_alert('success', _l('updated_successfully', $this->title));
                    } else {
                        set_alert('error', _l('elite_update_fail'));
                    }
                }

                $result = ['level' => 'success'];
            }

            echo json_encode($result);
        }
        exit;
    }

    public function delete($id)
    {
        if (is_custom_js()) {
            $customUrl = 'elite_custom_js';
        } else {
            $customUrl = 'elite_custom_css';
        }

        if (!$id) {
            redirect(admin_url('elite_custom_js_css') . '/' . $customUrl);
        }
        $response = $this->elite_custom_js_css_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('elite_delete_message', $this->title));
        } else {
            set_alert('warning', _l('elite_global_error'));
        }
        redirect(admin_url('elite_custom_js_css') . '/' . $customUrl);
    }

    public function status()
    {
        $success = false;
        $message = _l('Some thing wrong. please refresh and try again.');

        if ($this->input->post()) {
            $id = $this->input->post('id');
            $status = $this->input->post('status');

            if ($status == 'active') {
                $status = 'inactive';
            } else {
                $status = 'active';
            }

            $result = $this->elite_custom_js_css_model->status_update(['status' => $status], $id);
            if ($result) {
                $success = true;
                $message = _l('elite_status_message');
                set_alert('success', _l('elite_status_message'));
            }
        }

        echo json_encode([
            'success' => $success,
            'message' => $message,
        ]);
        exit;
    }

    public function nameExist($name)
    {
        $id = $this->input->post('id');
        $area_type = $this->input->post('area_type');
        $code_type = $this->input->post('code_type');
        $result = $this->elite_custom_js_css_model->nameExist($name, $area_type, $code_type, $id);

        if ($result) {
            $this->form_validation->set_message(
                'nameExist',
                'This Name is already exist.'
            );
            return false;
        } else {
            return true;
        }
    }
}