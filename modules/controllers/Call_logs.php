<?php
defined('BASEPATH') or exit('No direct script access allowed');
$check =  __dir__ ;
$str= preg_replace('/\W\w+\s*(\W*)$/', '$1', $check);
$str.'/twilio-web/src/Twilio/autoload.php';
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use Twilio\TwiML\VoiceResponse;
use Carbon\Carbon;

error_reporting(-1);
ini_set('display_errors', 1);

class Call_logs extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('call_logs_model');
    }

    public function calculate_duration(){
        $posted_data = $this->input->post();
        $start_time = strtotime($posted_data['start_time']);
        $end_time = strtotime($posted_data['end_time']);
        $duration = $end_time - $start_time;
        if($duration < 0){
            echo '00:00:00';
        }else{
            $seconds = $duration;
            $H = floor($seconds / 3600);
            $i = ($seconds / 60) % 60;
            $s = $seconds % 60;
            echo sprintf("%02d:%02d:%02d", $H, $i, $s);
        }
        die();
    }

    /* List all call_logs */
    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('call_logs', 'table'));
        }
        $data['switch_grid'] = false;

        if ($this->session->userdata('cl_grid_view') == 'true') {
            $data['switch_grid'] = true;
        }

        $this->load->model('staff_model');
        $this->load->model('clients_model');
        $this->load->model('leads_model');

        $data['staffs'] = $this->staff_model->get();
        $data['leads'] = $this->leads_model->get();
        $data['clcustomers'] = $this->clients_model->get();
        $data['rel_types'] = $this->call_logs_model->get_rel_types();
        $data['cl_filter_status'] = [
            ['id' => '1', 'name' => 'Complete'],
            ['id' => '2', 'name' => 'Incomplete'],
        ];

        $data['title']     = _l('call_logs_tracking');
        //for modal data
        $data['bulk_sms_modal_title']     = _l('cl_bulk_sms_modal_title');
        $data['call_directions'] = $this->call_logs_model->get_call_directions();
        $data['owner']         = $this->staff_model->get(get_staff_user_id());
        $data['staff']         = $this->staff_model->get();
        $data['twilio_account_info']         = $this->call_logs_model->get_twilio_account();
        $this->app_scripts->add('mindmap-js','modules/call_logs/assets/js/call_logs.js');
        $this->load->view('manage', $data);
    }

    /* Prepare the table function to display the records in table format. */
    public function table($clientid = '')
    {
        $data['clientid'] = $clientid;
        $this->app->get_table_data(module_views_path('call_logs', 'table'), $data);
    }

    
    /* Get the data ready for grid view. */
    public function grid()
    {
        echo $this->load->view('call_logs/grid', [], true);
    }

    /* Make a relationship with client and customer tables. */
    public function call_log_relations($clientid, $customer_type)
    {
        $data['clientid'] = $clientid;
        $data['customer_type'] = $customer_type;

        $this->app->get_table_data(module_views_path('call_logs', 'call_log_relations'), $data);
    }

    /**
     * Task ajax large preview request modal
     * @param  mixed $id
     * @return mixed
     */
    public function get_call_log_data($id)
    {
        $call_log = $this->call_logs_model->get($id);

        if (!$call_log) {
            header('HTTP/1.0 404 Not Found');
            echo 'Call Log not found';
            die();
        }

        $data['rel_type'] = $this->call_logs_model->get_rel_types($call_log->rel_type);
        $data['call_direction'] = $this->call_logs_model->get_call_directions($call_log->call_direction);

        $data['call_log']  = $call_log;

        $html =  $this->load->view('view_call_log_template', $data, true);
        echo $html;
    }

    /* Call log function to handle create, view, edit views. */
    public function call_log($id = '')
    {
        if ($this->input->post()) {
            $this->load->model('misc_model');
            $post_data = $this->input->post();
            
            if ($id == '') {
                if (!has_permission('call_logs', '', 'create')) {
                    access_denied('call_logs');
                }
                $id = $this->call_logs_model->add($post_data);
                if ($id) {

                    $success = false;
                    if($post_data['has_follow_up'] == 1 && $post_data['is_completed'] == 0) {
                        $params = [
                            'notify_by_email' => 1,
                            'date' => $this->input->post('follow_up_schedule'),
                            'description' => $this->input->post('call_summary'),
                            'rel_type' => $this->input->post('customer_type'),
                            'rel_id' => $this->input->post('clientid'),
                            'staff' => ((int)$this->input->post('call_with_staffid') > 0)?$post_data['call_with_staffid'] :$this->input->post('staffid') ,
                        ];
                        $success = $this->misc_model->add_reminder($params, $this->input->post('clientid'));
                    }

                    // Saving recorded blob files
                    if( !empty($_FILES) && is_array($_FILES["recorded_blobs"])) {
                        $file_count = count($_FILES["recorded_blobs"]["tmp_name"]);
                        $path     = FCPATH .'uploads/call_logs/' . $id . '/';
                        if( !is_dir(FCPATH .'uploads/call_logs/' . $id)){
                            mkdir( FCPATH .'uploads/call_logs/' . $id , 0777, true );
                            fopen(rtrim($path, '/') . '/' . 'index.html', 'w');
                        }
                       
                        $extension = ".mp3";
                        for($i = 0;$i < $file_count;$i++){
                            
                            $filename    = unique_filename($path, "record".time());
                            $newFilePath = $path . $filename.$extension;
                            // Upload the file into the company uploads dir
                            if (move_uploaded_file($_FILES["recorded_blobs"]["tmp_name"][$i], $newFilePath)) { 
                                $success = $this->call_logs_model->save_voice_records( $filename.$extension , $id);
                            }
                        }
                    }

                    echo json_encode([
                        'success'             => $success,
                        'message'             => _l('added_successfully', _l('call_log')),
                    ]);
                    die;
                    //set_alert('success', _l('added_successfully', _l('call_log')));
                    //redirect(admin_url('call_logs'));
                }
            } else {
                if (!has_permission('call_logs', '', 'edit')) {
                    access_denied('call_logs');
                }
                $success = $this->call_logs_model->update($post_data, $id);
                if ($success) {

                     // Saving recorded blob files
                     if( !empty($_FILES) && is_array($_FILES["recorded_blobs"]) && (count($_FILES["recorded_blobs"]) > 0)) {
                        $file_count = count($_FILES["recorded_blobs"]["tmp_name"]);

                        $path     = FCPATH .'uploads/call_logs/' . $id . '/';
                        if( !is_dir(FCPATH .'uploads/call_logs/' . $id)){
                            mkdir( FCPATH .'uploads/call_logs/' . $id , 0777, true );
                            fopen(rtrim($path, '/') . '/' . 'index.html', 'w');
                        }
                        $extension = ".mp3";
                        for($i = 0;$i < $file_count;$i++){
                            
                            $filename    = unique_filename($path, "record".time());
                            $newFilePath = $path . $filename.$extension;
                            // Upload the file into the company uploads dir
                            if (move_uploaded_file($_FILES["recorded_blobs"]["tmp_name"][$i], $newFilePath)) {
                                
                                $success = $this->call_logs_model->save_voice_records( $filename.$extension , $id);
                            }
                        }
                    }

                    echo json_encode([
                        'success'             => $success,
                        'message'             => _l('updated_successfully', _l('call_log')),
                    ]);
                    die;
                    
                   // set_alert('success', _l('updated_successfully', _l('call_log')));
                }
                //redirect($_SERVER['HTTP_REFERER']);
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('call_log_lowercase'));
        } else {
            $data['call_log']        = $this->call_logs_model->get($id);
            $data['cl_rel_type']        = $this->call_logs_model->get_rel_types($data['call_log']->rel_type);
            $title = _l('edit', _l('call_log_lowercase'));
        }

        $data['owner']         = $this->staff_model->get(get_staff_user_id());
        $data['staff']         = $this->staff_model->get();
        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active'=>1]);
        $data['rel_types'] = $this->call_logs_model->get_rel_types();
        $data['call_directions'] = $this->call_logs_model->get_call_directions();

        $data['title']                 = $title;

        $this->load->view('call_log', $data);
    }

    public function records(){
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('call_logs', 'records_table'));
        }
        $data['title']     = "Call Logs Record List";
        $this->load->view('records', $data);
    }
    public function records_table($clientid = '')
    {
       // $data['clientid'] = $clientid;
        $this->app->get_table_data(module_views_path('call_logs', 'records_table'), []);
    }


    /* Call log function to handle preview views. */
    public function preview($id = 0)
    {
        $data['call_log']        = $this->call_logs_model->get($id);

        if (!$data['call_log']) {
            blank_page(_l('cl_not_found'), 'danger');
        }
        $data['rel_types'] = $this->call_logs_model->get_rel_types();
        $data['call_directions'] = $this->call_logs_model->get_call_directions();
        $data['owner']         = $this->staff_model->get(get_staff_user_id());
        $data['staff']         = $this->staff_model->get('',["staffid <> " => get_staff_user_id()]);
        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active'=>1]);
        $data['cl_rel_type']        = $this->call_logs_model->get_rel_types($data['call_log']->rel_type);
        $data['title']                 = _l('preview_call_log');
        $this->load->view('preview', $data);
    }

    /* Delete from database */
    public function delete($id)
    {
        if (!has_permission('call_logs', '', 'delete')) {
            access_denied('call_logs');
        }
        if (!$id) {
            redirect(admin_url('call_logs'));
        }
        $response = $this->call_logs_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('call_log')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('call_log_lowercase')));
        }
        redirect(admin_url('call_logs'));
    }

    /* get contact data from customer id */
    public function get_contact()
    {
        $posted_data = $this->input->post();
        if($posted_data){
            $query = "SELECT * FROM ".db_prefix()."contacts WHERE userid = ".$posted_data['clientid'];
            $query = $this->db->query($query);
            $result = $query->result_array();
            $i = 0;
            foreach ($result as $res) {
                $result[$i]['name'] = $res['email'].' - '.$res['firstname'].' '.$res['lastname'];
                $i++;
            }
            die(json_encode($result));
        }
        die;
    }

    /* Get the relationship of Types. */
    public function get_relation_data()
    {
        if ($this->input->post()) {
            $type = $this->input->post('type');
            $data = get_relation_data_for_cl($type);
            if ($this->input->post('rel_id')) {
                $rel_id = $this->input->post('rel_id');
            } else {
                $rel_id = '';
            }

            $relOptions = init_relation_options($data, $type, $rel_id);
            echo json_encode($relOptions);
            die;
        }
    }
    /* Prepare Data for the Overview tab/graphs. */
    public function overview($staffid = ''){
        $now = Carbon::now();
        if($staffid == ''){
            $staffid = get_staff_user_id();
        }
        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');

        $start_of_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_of_month = Carbon::now()->endOfMonth()->format('Y-m-d');

        $data['daily_count']        = $this->call_logs_model->count_inbound_outbound_calls(Carbon::now()->format("Y-m-d"), Carbon::now()->format("Y-m-d"), $staffid);
        $data['week_count']         = $this->call_logs_model->count_inbound_outbound_calls($weekStartDate, $weekEndDate, $staffid);
        $data['month_count']        = $this->call_logs_model->count_inbound_outbound_calls($start_of_month, $end_of_month, $staffid);

        $data['daily_sms']          = $this->call_logs_model->count_all_sms(Carbon::now()->format("Y-m-d"), Carbon::now()->format("Y-m-d"), $staffid);
        $data['week_sms']          = $this->call_logs_model->count_all_sms($weekStartDate, $weekEndDate, $staffid);
        $data['month_sms']          = $this->call_logs_model->count_all_sms($start_of_month, $end_of_month, $staffid);

        $data['weekly_chart_Date']  = json_encode($this->call_logs_model->get_inbound_outbound_report($weekStartDate, $weekEndDate, $staffid));
        $data['monthly_chart_Date'] = json_encode($this->call_logs_model->get_inbound_outbound_report($start_of_month, $end_of_month, $staffid));

        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();
        $data['staffid'] = $staffid;

        $this->load->view('gantt', $data);
    }

    /* Switch functionality between list and grid view. */
    public function switch_grid($set = 0, $manual = false)
    {
        if ($set == 1) {
            $set = 'false';
        } else {
            $set = 'true';
        }

        $this->session->set_userdata([
            'cl_grid_view' => $set,
        ]);
        if ($manual == false) {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    /*
     * manage types section
     */
    public function cl_types()
    {
        if (!is_admin()) {
            access_denied('Call logs Type');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('call_logs', 'call_types/cl_types_table'));
        }
        $data['title'] = _l('call_log_type');
        $this->load->view('call_types/cl_types_manage', $data);
    }

    public function cl_type()
    {
        if (!is_admin() && get_option('staff_members_create_inline_cl_types') == '0') {
            access_denied('call_logs');
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->call_logs_model->add_cl_type($this->input->post());
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $id ? _l('added_successfully', _l('cl_type')) : '',
                    'id'      => $id,
                    'name'    => $this->input->post('name'),
                ]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->call_logs_model->update_cl_type($data, $id);
                $message = _l('updated_successfully', _l('cl_type'));
                echo json_encode(['success' => $success, 'message' => $message]);
            }
        }
    }

    public function delete_type($id)
    {
        if (!$id) {
            redirect(admin_url('call_logs'));
        }
        $response = $this->call_logs_model->delete_cl_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('call_log_type')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('call_log_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('call_log_type')));
        }
        redirect(admin_url('call_logs/cl_types'));
    }

    /*
     * end manager types section
     */


    /*
     * manage call directions
     */
    public function call_directions()
    {
        if (!is_admin()) {
            access_denied('Call Type');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('call_logs', 'call_types/call_direction_table'));
        }
        $data['title'] = _l('call_log_direction');
        $this->load->view('call_types/call_direction_manage', $data);
    }

    public function call_direction()
    {
        if (!is_admin() && get_option('staff_members_create_inline_call_direction') == '0') {
            access_denied('call_logs');
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->call_logs_model->add_call_direction($this->input->post());
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $id ? _l('added_successfully', _l('call_log_direction')) : '',
                    'id'      => $id,
                    'name'    => $this->input->post('name'),
                ]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->call_logs_model->update_call_direction($data, $id);
                $message = _l('updated_successfully', _l('call_log_direction'));
                echo json_encode(['success' => $success, 'message' => $message]);
            }
        }
    }
    public function delete_call_direction($id)
    {
        if (!$id) {
            redirect(admin_url('call_logs'));
        }
        $response = $this->call_logs_model->delete_call_direction($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('call_log_direction')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('call_log_direction')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('call_log_direction')));
        }
        redirect(admin_url('call_logs/call_directions'));
    }
    
    public function get_lead_info()
    {
        $this->load->model('Leads_model');
        $leadid = $_POST['lead_id'];
        $results = $this->Leads_model->get($leadid);

        if(isset($results))
            echo $results->phonenumber;
        else 
            echo "";
    }

    public function get_contact_info()
    {
        $this->load->model('Clients_model');
        $contactid = $_POST['contactid'];
        $results = $this->Clients_model->get_contact($contactid);

        if(isset($results))
            echo $results->phonenumber;
        else 
            echo "";
    }
    public function newToken()
    {

        if((get_option('staff_members_twilio_account_share_staff') == 1) || (get_option('staff_members_twilio_account_share_staff') == '1')) {

            $result = $this->call_logs_model->get_twilio_account(); 
            $token = "";
            if(isset($result) && (($result->active == '1') || ($result->active == 1))){ 

                update_option('loggin_user_temp_id', get_staff_user_id());

                $account_sid = $result->sms_twilio_account_sid;
                str_replace(' ', '', $account_sid);

                $auth_token = $result->sms_twilio_auth_token;
                str_replace(' ', '', $auth_token);

                $app_sid = $result->twiml_app_sid;
                str_replace(' ', '', $app_sid);

                $client = new ClientToken($account_sid, $auth_token);
                $client->allowClientOutgoing($app_sid);
                $client->allowClientIncoming('support_agent');
                $token = $client->generateToken();
            } else {
                
            }
            echo json_encode(['token' => $token]);

        } else {
            $result = twilio_setting(); 
            $token = "";
            
            if( ($result["sms_twilio_active"] == "1") || ($result["sms_twilio_active"] == 1) ) {
                $client = new ClientToken($result['account_sid'], $result['auth_token']);
                $client->allowClientOutgoing($result['twiml_app_sid']);
                $client->allowClientIncoming('support_agent');
                $token = $client->generateToken();
            }
            
            echo json_encode(['token' => $token]);
        }
        
    }
    public function udpate_sms_response($id='')
    {
        if($this->input->post()){
            $post_data['twilio_sms_response'] = $this->input->post('SmsStatus');
            return $this->call_logs_model->udpate_sms_response($post_data, $id);
        }else{
            echo "403 forbidden access!";
        }
    }

    public function save_twilio(){ 
        if($this->input->post()){
            $post_data['twiml_app_sid'] = $this->input->post('twiml_app_sid');
            $post_data['twiml_app_friendly_name'] = $this->input->post('twiml_app_friendly_name');
            $post_data['twiml_app_voice_request_url'] = $this->input->post('twiml_app_voice_request_url');
            $post_data['twilio_phone_number'] = $this->input->post('twilio_phone_number');
            $post_data['sms_twilio_account_sid'] = $this->input->post('sms_twilio_account_sid');
            $post_data['sms_twilio_auth_token'] = $this->input->post('sms_twilio_auth_token');
            $post_data['active'] = $this->input->post('active');

            $this->call_logs_model->udpate_twilio_account($post_data);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete_record($recording_id){
        if (!$recording_id) {
            redirect(admin_url('call_logs/records'));
        }
        $response = $this->call_logs_model->delete_recording($recording_id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('call_log_recording')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('call_log_recording')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('call_log_recording')));
        }
        redirect(admin_url('call_logs/records'));
    }

    public function download_record($recording_id){
        
        $this->load->helper('download');

        $path = $this->call_logs_model->get_record_path($recording_id);

        if (file_exists($path)) {
            force_download($path, null);
        } else {
            set_alert('warning', 'Could not download file.');
            redirect(admin_url('call_logs/records'));
        }
        
    }

    
}
