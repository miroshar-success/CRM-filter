<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Reminder extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reminder_model');
        $this->load->model('currencies_model');    
    }
    public function index($reminder_id = '')
    {  
        $this->list_reminder($reminder_id);
    }
    public function list_reminder($reminder_id = '')
    { 
        if (!has_permission('reminder', '', 'view') && !has_permission('reminder', '', 'view_own')) {
            access_denied('reminder');
        }
        $this->load->model('clients_model');
        $data['clients']    = $this->reminder_model->getCustomersData();
        $data['customers']    = $this->clients_model->get();
        $data['staff']         = $this->staff_model->get('', ['active' => 1]);
        $data['reminderid']           = $reminder_id;
        $data['title']                 = _l('reminder');
        $data['reminder_sale_agents']  = $this->reminder_model->get_sale_agents();
        $data['years']                 = $this->reminder_model->get_reminder_years();
        $data['statuses']              = $this->reminder_model->get_statuses();
        $data['created_ids']              = $this->reminder_model->get_created_by_ids();
        $this->load->view('admin/reminder/manage', $data);
    }
    public function get_contact_data_values($rel_id=0, $rel_type=0)
    {
        echo json_encode($this->reminder_model->get_contact_data_values($rel_id, $rel_type));
    }
    public function reminder_new()
    {  
        $id = ($this->input->post('id')) ? $this->input->post('id') : '';
        if ($this->input->post()) {
            $reminder_data = $this->input->post();
            unset($reminder_data['file']);
            if ($id == '') {
                if (!has_permission('reminder', '', 'create')) {
                    access_denied('reminder');
                }
                $id = $this->reminder_model->add($reminder_data);

                if ($id) {
                    if($this->input->post('rel_type')=='custom_reminder'){
                        $success = handle_reminder_other_attachments_upload($id);

                    }
                    set_alert('success', _l('added_successfully', _l('Reminder')));
                    redirect(admin_url('reminder/list_reminder/'));
                }
            } else {
                if (!has_permission('reminder', '', 'edit')) {
                    access_denied('reminder');
                }
                $success = $this->reminder_model->update($reminder_data, $id);
                if($success) {
                    if($this->input->post('rel_type')=='custom_reminder'){
                        $success = handle_reminder_other_attachments_upload($id);
                    }
                    set_alert('success', _l('updated_successfully', _l('Reminder'))); 
                    redirect(admin_url('reminder/list_reminder/#'.$id));
                }
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('reminder_lowercase'));
        } else {
            $data['reminderData'] = $this->reminder_model->get($id);
            if(!empty($data['reminderData']) && !empty($data['reminderData']->customer)) {
                $this->load->model('    clients_model');
                $data['contacts'] = $this->clients_model->get_contacts($data['reminderData']->customer);
            }
            $data['is_reminder'] = true;
            $title               = _l('edit', _l('reminder_lowercase'));
        }
        $data['ajaxItems'] = false;
        if($id == '') {
            $data['open_till_date'] = _d(date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 days')));
        }
        $data['statuses']      = $this->reminder_model->get_statuses();
        $data['staff']         = $this->staff_model->get('', ['active' => 1]);
        $data['currencies']    = $this->currencies_model->get();
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['title'] = $title;
        $data['customers']    = $this->reminder_model->getCustomersData();

        $this->load->view('admin/reminder/reminder',$data);
    }
    public function table()
    {
        if (
            !has_permission('reminder', '', 'view')
            && !has_permission('reminder', '', 'view_own')
        ) {
            ajax_access_denied();
        }

        $this->app->get_table_data(module_views_path(MODULE_REMINDER, 'tables/reminder'));
        $this->app->get_table_data('reminder');
    }
    public function get_reminder_data_ajax($id, $to_return = false)
    {   
        if (!has_permission('reminder', '', 'view') && !has_permission('reminder', '', 'view_own')) {
            echo _l('access_denied');
            die;
        }
        $reminder = $this->reminder_model->getReminderData($id);
        if (!$reminder) {
            echo _l('reminder_not_found');
            die;
        }
        $merge_fields = [];

        $merge_fields[] = [
            [
                'name' => 'Items Table',
                'key'  => '{reminder_items}',
            ],
        ];
        $merge_fields = array_merge($merge_fields, $this->app_merge_fields->get_flat('reminder', 'other', '{email_signature}'));
        $this->load->model('emails_model'); 
        $data['members']      = $this->staff_model->get('', ['active' => 1]);
        $data['activity']     = $this->reminder_model->get_reminder_activity($id);
        $data['staff']        = $this->staff_model->get('', ['active' => 1]);
        $data['customers']    = $this->reminder_model->getCustomersData();
        $data['contacts']     = $this->clients_model->get_contacts($reminder->customer);
        $data['related_doc']  =$this->reminder_model->get_related_doc($reminder->rel_type,$reminder->customer);
        $data['reminder']     = $reminder;
        $data['reminderData'] = $reminder;
        $data['totalNotes']   = total_rows(db_prefix() . 'notes', ['rel_id' => $id, 'rel_type' => 'proposal']);
        if ($to_return == false) {
            $this->load->view('admin/reminder/reminder_preview_template', $data);
        } else {
            return $this->load->view('admDate to be notifiedin/reminder/reminder_preview_template', $data, true);
        }
    }

    public function delete_reminder_activity($id)
    {
        if (is_admin()) {
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'reminder_activity');
        }
    }
    /* Delete Reminder*/
    public function delete($id)
    {
        if (!has_permission('reminder', '', 'delete')) {
            access_denied('reminder data');
        }

        if (!$id) {
            redirect(admin_url('reminder'));
        }
        $response = $this->reminder_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('reminder')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('reminder_lowercase')));
        }
        redirect(admin_url('reminder'));
    }
    public function complete_reminder($id)
    {
        if (!has_permission('reminder', '', 'complete')) {
            access_denied('reminder data');
        }

        if (!$id) {
            redirect(admin_url('reminder'));
        }
        $response = $this->reminder_model->complete_reminder($id);
        if ($response == '2') {
            set_alert('warning', _l('already_closed', _l('Reminder')));
        } elseif ($response == true) {
         set_alert('success', _l('status_updated_successfully', _l('Reminder'))); 
     } elseif($response==false) {
        set_alert('warning', _l('problem_updateing', _l('reminder_lowercase')));
    }
    redirect(admin_url('reminder#'.$id));
}  
public function reopen_reminder($id)
{
    if (!has_permission('reminder', '', 'reopen')) {
        access_denied('reminder data');
    }
    if (!$id) {
        redirect(admin_url('reminder'));
    }
    $response = $this->reminder_model->reopen_reminder($id);
    if ($response == '2') {
        set_alert('warning', _l('already_reopen', _l('Reminder')));
    } elseif ($response == true) {
     set_alert('success', _l('status_updated_successfully', _l('Reminder'))); 
 } elseif($response==false) {
    set_alert('warning', _l('problem_updateing', _l('reminder_lowercase')));
}
redirect(admin_url('reminder#'.$id));
}
public function add_reminder_comment()
{
    if ($this->input->post()) {
        echo json_encode([
            'success' => $this->reminder_model->add_comment($this->input->post()),
        ]);
    }
}
public function get_reminder_activity($id){
    $data['activity'] = $this->reminder_model->get_reminder_activity($id);
    $this->load->view('admin/reminder/activity_template', $data);
}
public function reset_filters_session(){
    $this->session->unset_userdata('reminder_filter_number');
    $this->session->unset_userdata('reminder_filter_date_f');
    $this->session->unset_userdata('reminder_filter_date_t');
    $this->session->unset_userdata('reminder_filter_related');
    $this->session->unset_userdata('reminder_filter_company');
    $this->session->unset_userdata('reminder_filter_contact');
    $this->session->unset_userdata('reminder_filter_description');
    $this->session->unset_userdata('reminder_filter_assigned');
    echo json_encode(['success'=>true]);
}
public function get_related_doc($rel_type = NULL, $customer_id = NULL)
{
    $str = '<option value=""></option>';
    if($rel_type && $customer_id){
        $data = $this->reminder_model->get_related_doc($rel_type, $customer_id);
        if($rel_type == "quotes") {
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $status = $value['status'] == 1 ? '(UNPAID)' : ($value['status'] == 2 ? '(PAID)' : ($value['status'] == 3 ? '(PARTIALLY)' : ($value['status'] == 4 ? '(OVERDUE)' : ($value['status'] == 5 ? '(CANCELLED)' : ($value['status'] == 6 ? '(DRAFT)' : '')))));
                    $str.= '<option value="'.$value['id'].'">'.format_proposal_number($value['id']).$status.'</option>';
                }
            }

        }else if($rel_type == "invoice") {
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $status = $value['status'] == 1 ? '(UNPAID)' : ($value['status'] == 2 ? '(PAID)' : ($value['status'] == 3 ? '(PARTIALLY)' : ($value['status'] == 4 ? '(OVERDUE)' : ($value['status'] == 5 ? '(CANCELLED)' : ($value['status'] == 6 ? '(DRAFT)' : '')))));
                    $str.= '<option value="'.$value['id'].'">'.format_invoice_number($value['id']).$status.'</option>';
                }
            }

        }else if($rel_type == "estimate") {
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $status = $value['status'] == 1 ? _l('estimate_status_draft') : ($value['status'] == 2 ? _l('estimate_status_sent') : ($value['status'] == 3 ? _l('estimate_status_declined') : ($value['status'] == 4 ? _l('estimate_status_accepted') : ($value['status'] == 5 ? _l('estimate_status_expired') : _l('not_sent_indicator')))));
                    $str.= '<option value="'.$value['id'].'">'.format_estimate_number($value['id']).'('.$status.')</option>';
                }
            } 
        }else if($rel_type == "credit_note") {

            if(!empty($data)){
                foreach ($data as $key => $value) {
                 $status = $value['status'] == 1 ? '('._l('credit_note_status_open').')' : ($value['status'] == 2 ? '('._l('credit_note_status_closed').')' : ($value['status'] == 3 ? '('._l('credit_note_status_void').')' : ''));
                 $str.= '<option value="'.$value['id'].'">'.format_credit_note_number($value['id']).$status.'</option>';
             }
         }   
     }elseif ($rel_type == "tickets") {
         if(!empty($data)){
            foreach ($data as $key => $value) {
             $status = '('.ticket_status_translate($value['status']).')';
             $str.= '<option value="'.$value['ticketid'].'">TICK-'.$value['ticketid'].$status.'</option>';
         }
     }  
 }
}
echo $str;
}
public function getreminderViewModal(){
    $id = $this->input->post('id');
    if (!has_permission('reminder', '', 'view') && !has_permission('reminder', '', 'view_own')) {
        echo _l('access_denied');
        die;
    }
    $reminder = $this->reminder_model->getReminderData($id);
    if (!$reminder) {
        echo _l('reminder_not_found');
        die;
    }
    $data['related_doc']  =$this->reminder_model->get_related_doc($reminder->rel_type,$reminder->customer);
    $data['reminder']     = $reminder;
    return $this->load->view('admin/includes/modals/reminder_view_file', $data);  
}
}
