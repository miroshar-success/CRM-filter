<?php
/* Model for Call Log Module, it has all database related functions/calls. */
defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;

class Call_logs_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /* Get the count for staff. */
    public function get_staff_counts($staffid){
        $count = 0;

        $sql = "SELECT count(`staffid`) as total_count
                from ".db_prefix()."call_logs where staffid= '".$staffid."' AND opt_event_type='call'" ;
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)){
            $count = $row->total_count;
        }

        return $count;
    }

    /**
     * @param  integer (optional)
     * @return object
     * Get single
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $call_log = $this->db->get(db_prefix() . 'call_logs')->row();
            $call_log->contact_name = '';
            $call_log->contact_email = '';
            if($call_log->contactid != ''){
                $this->load->model('clients_model');
                $contact = $this->clients_model->get_contact($call_log->contactid);
                if($contact){
                    $call_log->contact_name = $contact->firstname.' '.$contact->lastname;
                    $call_log->contact_email = $contact->email;
                }
                
            }
            return $call_log;
        }

        return $this->db->get(db_prefix() . 'call_logs')->result_array();
    }

    /* Get all call logs by staff members. */
    public function get_staff_call_logs($staff_id, $exclude_notified = true)
    {
        $this->db->where('staff_id', $staff_id);
        if ($exclude_notified) {
            $this->db->where('notified', 0);
        }

        $this->db->order_by('end_date', 'asc');
    }

    /**
     * Add new
     * @param mixed $data All $_POST dat
     * @return mixed
     */
    public function add($data)
    {
        unset($data['lead_id']);
        unset($data['call_status']);
        $data['datestart'] = date('Y-m-d');
        $data['staffid']      = $data['staffid'] == '' ? 0 : $data['staffid'];
        $data['call_start_time']    = to_sql_date($data['call_start_time'], true);
        $data['call_end_time']      = to_sql_date($data['call_end_time'], true);

        if($data['has_follow_up'] == 1) {
            $data['follow_up_schedule'] = to_sql_date($data['follow_up_schedule'], true);
        }else{
            $data['follow_up_schedule'] = 'NULL';
        }
        $data['dateadded'] = date('Y-m-d H:i:s');

        $diff = Carbon::parse($data['call_end_time'])->diffInHours(Carbon::parse($data['call_start_time'])) . ':' .  Carbon::parse($data['call_end_time'])->diff(Carbon::parse($data['call_start_time']))->format('%I:%S');
        $data['call_duration'] = $diff;
        $this->db->insert(db_prefix() . 'call_logs', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Call Log Added [ID:' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update
     * @param  mixed $data All $_POST data
     * @param  mixed $id    id
     * @return boolean
     */
    public function update($data, $id)
    {
        unset($data['lead_id']);
        unset($data['call_status']);
        $data['staffid']      = $data['staffid'] == '' ? 0 : $data['staffid'];
        $data['call_start_time']    = to_sql_date($data['call_start_time'], true);
        $data['call_end_time']      = to_sql_date($data['call_end_time'], true);
        if($data['has_follow_up'] == 1) {
            $data['follow_up_schedule'] = to_sql_date($data['follow_up_schedule'], true);
        }else{
            $data['follow_up_schedule'] = 'NULL';
        }
        $data['dateaupdated'] = date('Y-m-d H:i:s');

        $diff = Carbon::parse($data['call_end_time'])->diffInHours(Carbon::parse($data['call_start_time'])) . ':' .  Carbon::parse($data['call_end_time'])->diff(Carbon::parse($data['call_start_time']))->format('%I:%S');
        $data['call_duration'] = $diff;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'call_logs', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Call Log Updated [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete
     * @param  mixed $id id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'call_logs');
        if ($this->db->affected_rows() > 0) {
            log_activity('Call log Deleted [ID:' . $id . ']');
            $this->db->where('call_log_id', $id);
            $this->db->delete(db_prefix() . 'call_logs_voice_records');
            return true;
        }

        return false;
    }

    /* Display all logs, which has notification as true. Notification Queue. */
    public function get_notifiable_logs(){
        $this->db->where('is_completed', 0);
        $this->db->where('has_follow_up', 1);
        $this->db->where('notified', 0);
        $this->db->where('TIMESTAMPDIFF(MINUTE, CURRENT_TIMESTAMP(), follow_up_schedule) <=', '30');
        $result = $this->db->get(db_prefix() . 'call_logs')->result_array();

        return $result;
    }

    /**
     * Notify staff members
     * @param  mixed $id           id
     * @return boolean
     */
    public function notify_staff_members($id)
    {
        $callLog = $this->get($id);
        $callLog_desc = 'cl_follow_up_notification';

        if ($callLog->call_with_staffid > 0) {
            $this->load->model('staff_model');
            $staff = $this->staff_model->get('', ['active' => 1, 'staffid' => $callLog->call_with_staffid]);
        } else if ($callLog->staffid > 0) {
            $this->db->where('active', 1)
            ->where('staffid', $callLog->staffid);
            $staff = $this->db->get(db_prefix() . 'staff')->result_array();
        }else {
            $this->load->model('staff_model');
            $staff = $this->staff_model->get('', ['active' => 1]);
        }

        if($callLog->customer_type == 'customer') {
            $oClient = $this->clients_model->get($callLog->clientid);
            if (!$oClient) {
                return false;
            }
            $oCustomer = $this->clients_model->get_contacts($oClient->userid, ['is_primary' => true]);
            $contactName = '';
            if (isset($oCustomer[0])) {
                $contactName = $oCustomer[0]['firstname'] . ' ' . $oCustomer[0]['lastname'] . '<br>';
            }
            $contactName = $contactName . ' ' . $oClient->company;
        }else{
            $this->load->model('leads_model');
            $oCustomer = $this->leads_model->get($callLog->clientid);
            $contactName = $oCustomer->name;
        }
        $notifiedUsers = [];
        foreach ($staff as $member) {
            if (is_staff_member($member['staffid'])) {
                $notified = add_notification([
                    'fromcompany'     => 1,
                    'touserid'        => $member['staffid'],
                    'description'     => $callLog_desc,
                    'additional_data' => serialize([
                        $contactName,
                        _d($callLog->follow_up_schedule),
                    ]),
                ]);
                if ($notified) {
                    array_push($notifiedUsers, $member['staffid']);
                }
            }
        }

        pusher_trigger_notification($notifiedUsers);
        $this->db->where('id', $callLog->id);
        $this->db->update(db_prefix() . 'call_logs', [
            'notified' => 1,
        ]);

        if (count($staff) > 0 && $this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }


    /**
     * Notify staff members about calls goal achieived
     * @return boolean
     */
    public function notify_staff_members_calls_goal_acheived()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $callLog_daily_goal_notification = 'cl_daily_calls_target_notification';
        $callLog_monthly_goal_notification = 'cl_monthly_calls_target_notification';

        $cl_calls_daily_goal = get_option('staff_members_daily_calls_target');
        $cl_calls_monthly_goal = get_option('staff_members_monthly_calls_target');

        $sql = "SELECT distinct staffid as staff
                from `" . db_prefix() . "call_logs` where staffid > 0 
                union 
                SELECT distinct call_with_staffid as staff
                from `" . db_prefix() . "call_logs` where call_with_staffid > 0 
                ";
        $query = $this->db->query($sql);
        $staffIds = $query->result_array();
        foreach ($staffIds as $staffId){
            $notifiedUsers = [];

            /** Daily Goal Notify */
            $todayDate =  Carbon::now()->format('Y-m-d');
            $sql = "SELECT count(`id`) as totalCalls
                from ".db_prefix()."call_logs where DATE_FORMAT(`call_start_time`,'%Y-%m-%d') between '".$todayDate."' AND '".$todayDate."' 
                AND (`staffid` = ".$staffId['staff']." || `call_with_staffid` = ".$staffId['staff'].") 
                " ;
            $query = $this->db->query($sql);
            $rowDaily = $query->row();
            if((int) $cl_calls_daily_goal > 0 && $rowDaily->totalCalls > 0 && $rowDaily->totalCalls >= $cl_calls_daily_goal)  {
                $sql = "SELECT *
                from ".db_prefix()."call_logs_goals_notified where DATE_FORMAT(`notify_date`,'%Y-%m-%d') between '".$todayDate."' AND '".$todayDate."' 
                AND `staffid` = ".$staffId['staff']." AND goal_type = 'daily' 
                " ;
                $query = $this->db->query($sql);
                $rowNotify = $query->row();
                if(!$rowNotify){
                    $data = [
                        'staffid'   => $staffId['staff'],
                        'goal_type'   => 'daily',
                        'notify_date'   => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insert(db_prefix() . 'call_logs_goals_notified', $data);

                    $notified = add_notification([
                        'fromcompany' => 1,
                        'touserid' => $staffId['staff'],
                        'description' => $callLog_daily_goal_notification,
                        'additional_data' => serialize([
                            $cl_calls_daily_goal
                        ]),
                    ]);
                    if ($notified) {
                        array_push($notifiedUsers, $staffId['staff']);
                    }
                }
            }
            /** End Daily Goal Notify */

            /** Monthly Goal Notify */
            $currentMonth = Carbon::now()->format('m');

            $sql = "SELECT count(`id`) as totalCalls
                from ".db_prefix()."call_logs where DATE_FORMAT(`call_start_time`,'%m') = '".$currentMonth."'  
                AND (`staffid` = ".$staffId['staff']." || `call_with_staffid` = ".$staffId['staff'].") 
                " ;
            $query = $this->db->query($sql);
            $rowMonthly = $query->row();
            if((int) $cl_calls_monthly_goal > 0 && $rowMonthly->totalCalls > 0 && $rowMonthly->totalCalls >= $cl_calls_monthly_goal)  {
                $sql = "SELECT *
                from ".db_prefix()."call_logs_goals_notified where DATE_FORMAT(`notify_date`,'%m') = '".$currentMonth."'  
                AND `staffid` = ".$staffId['staff']." AND goal_type = 'monthly' 
                " ;
                $query = $this->db->query($sql);
                $rowNotify = $query->row();
                if(!$rowNotify){
                    $data = [
                        'staffid'   => $staffId['staff'],
                        'goal_type'   => 'monthly',
                        'notify_date'   => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insert(db_prefix() . 'call_logs_goals_notified', $data);

                    $notified = add_notification([
                        'fromcompany' => 1,
                        'touserid' => $staffId['staff'],
                        'description' => $callLog_monthly_goal_notification,
                        'additional_data' => serialize([
                            $cl_calls_monthly_goal
                        ]),
                    ]);
                    if ($notified) {
                        array_push($notifiedUsers, $staffId['staff']);
                    }
                }
            }
            /** End Monthly Goal Notify */
            if(sizeof($notifiedUsers)) {
                pusher_trigger_notification($notifiedUsers);
            }
        }

        return true;
    }

    /* Count the total inbound and outbound calls. */
    public function count_inbound_outbound_calls($start_date, $end_date, $staffid){
        $result['inbound']  = 0;
        $result['outbound'] = 0;

        $sql = "SELECT count(`call_direction`) as inbound
                from ".db_prefix()."call_logs where call_direction = 1 AND opt_event_type='call' AND DATE_FORMAT(`call_start_time`,'%Y-%m-%d') between '".$start_date."' AND '".$end_date."' 
                AND (`staffid` = ".$staffid." || `call_with_staffid` = ".$staffid.") 
                " ;
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)){
            $result['inbound'] = $row->inbound;
        }

        $sql = "SELECT count(`call_direction`) as outbound
                from `".db_prefix()."call_logs` where call_direction = 2 AND opt_event_type='call' AND DATE_FORMAT(`call_start_time`,'%Y-%m-%d') between '".$start_date."' AND '".$end_date."' 
                AND (`staffid` = ".$staffid." || `call_with_staffid` = ".$staffid.") 
                " ;
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)){
            $result['outbound'] = $row->outbound;
        }

        return $result;
    }

    /* Get the inbound report for the overview tab. */
    public function get_inbound_outbound_report($start_date, $end_date, $staffid){
        $date_labels  = [];
        $total_inbound = [];
        $total_outbound   = [];
        $total_sms   = [];

        $i              = 0;

        $daysDiff = Carbon::parse($end_date)->diffInDays(Carbon::parse($start_date));

        for ($d = 0; $d <= $daysDiff; $d++) {
            $filterDate =  Carbon::parse($start_date)->addDays($d)->format("Y-m-d");
            array_push($date_labels, _l(Carbon::parse($filterDate)->format('d M Y')));

            $inbound = 0;
            $outbound = 0;
            $sms = 0;

            $sql = "SELECT count(`call_direction`) as inbound
                from ".db_prefix()."call_logs where call_direction = 1 AND DATE_FORMAT(`call_start_time`,'%Y-%m-%d') between '".$filterDate."' AND '".$filterDate."' 
                AND (`staffid` = ".$staffid." || `call_with_staffid` = ".$staffid.") 
                " ;
            $query = $this->db->query($sql);
            $row = $query->row();
            if (isset($row)){
                $inbound = $row->inbound;
            }

            if (!isset($total_inbound[$i])) {
                $total_inbound[$i] = [];
            }
            $total_inbound[$i] = $inbound;

            $sql = "SELECT count(`call_direction`) as outbound
                from `".db_prefix()."call_logs` where call_direction = 2 AND DATE_FORMAT(`call_start_time`,'%Y-%m-%d') between '".$filterDate."' AND '".$filterDate."' 
                AND (`staffid` = ".$staffid." || `call_with_staffid` = ".$staffid.") 
                " ;
            $query = $this->db->query($sql);
            $row = $query->row();
            if (isset($row)){
                $outbound = $row->outbound;
            }

            if (!isset($total_outbound[$i])) {
                $total_outbound[$i] = [];
            }
            $total_outbound[$i] = $outbound;


            $sql = "SELECT count(`call_direction`) as sms
                from ".db_prefix()."call_logs where (opt_event_type = 'sms' || opt_event_type = 'bulk sms') AND DATE_FORMAT(`call_start_time`,'%Y-%m-%d') between '".$filterDate."' AND '".$filterDate."' 
                AND (`staffid` = ".$staffid." || `call_with_staffid` = ".$staffid.") 
                " ;
            $query = $this->db->query($sql);
            $row = $query->row();
            if (isset($row)){
                $sms = $row->sms;
            }
            if (!isset($total_sms[$i])) {
                $total_sms[$i] = [];
            }
            $total_sms[$i] = $sms;


            $i++;
        }

        $chart = [
            'labels'   => $date_labels,
            'datasets' => [
                [
                    'label'           => _l('cl_report_inbound_calls'),
                    'backgroundColor' => 'rgba(51, 122, 183,0.8)',
                    'borderColor'     => '#337ab7',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_inbound,
                ],
                [
                    'label'           => _l('cl_report_outbound_calls'),
                    'backgroundColor' => 'rgba(60, 118, 61,0.8)',
                    'borderColor'     => '#3c763d',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_outbound,
                ],
                [
                    'label'           => _l('cl_report_sms'),
                    'backgroundColor' => 'rgba(0,0,255,1)',
                    'borderColor'     => '#3c763d',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_sms,
                ],
            ],
        ];

        return $chart;
    }


    public function _search_proposals($q, $rel_type = '', $rel_id = 0, $limit =0)
    {
        $result = [
            'result'         => [],
            'type'           => 'proposals',
            'search_heading' => _l('proposals'),
        ];

        $has_permission_view_proposals     = has_permission('proposals', '', 'view');
        $has_permission_view_proposals_own = has_permission('proposals', '', 'view_own');

        if ($has_permission_view_proposals || $has_permission_view_proposals_own || get_option('allow_staff_view_proposals_assigned') == '1') {
            if (is_numeric($q)) {
                $q = trim($q);
                $q = ltrim($q, '0');
            } elseif (startsWith($q, get_option('proposal_number_prefix'))) {
                $q = strafter($q, get_option('proposal_number_prefix'));
                $q = trim($q);
                $q = ltrim($q, '0');
            }

            $noPermissionQuery = get_proposals_sql_where_staff(get_staff_user_id());

            // Proposals
            $this->db->select('*,' . db_prefix() . 'proposals.id as id');
            $this->db->from(db_prefix() . 'proposals');
            $this->db->join(db_prefix() . 'currencies', db_prefix() . 'currencies.id = ' . db_prefix() . 'proposals.currency');

            if (!$has_permission_view_proposals) {
                $this->db->where($noPermissionQuery);
            }

            if($rel_id !=0 && $rel_type != ''){
                $this->db->where('rel_type', $rel_type);
                $this->db->where('rel_id', $rel_id);
            }

            $this->db->where('(
                ' . db_prefix() . 'proposals.id LIKE "' . $q . '%"
                OR ' . db_prefix() . 'proposals.subject LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR ' . db_prefix() . 'proposals.content LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR ' . db_prefix() . 'proposals.proposal_to LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR ' . db_prefix() . 'proposals.zip LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR ' . db_prefix() . 'proposals.state LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR ' . db_prefix() . 'proposals.city LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR ' . db_prefix() . 'proposals.address LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR ' . db_prefix() . 'proposals.email LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR ' . db_prefix() . 'proposals.phone LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                )');

            $this->db->order_by(db_prefix() . 'proposals.id', 'desc');
            if ($limit != 0) {
                $this->db->limit($limit);
            }
            $result['result'] = $this->db->get()->result_array();
        }

        return $result;
    }


    public function _search_estimates($q, $rel_type = '', $rel_id = 0, $limit =0)
    {
        $result = [
            'result'         => [],
            'type'           => 'estimates',
            'search_heading' => _l('estimates'),
        ];

        $has_permission_view_estimates     = has_permission('estimates', '', 'view');
        $has_permission_view_estimates_own = has_permission('estimates', '', 'view_own');

        if ($has_permission_view_estimates || $has_permission_view_estimates_own || get_option('allow_staff_view_estimates_assigned') == '1') {
            if (is_numeric($q)) {
                $q = trim($q);
                $q = ltrim($q, '0');
            } elseif (startsWith($q, get_option('estimate_prefix'))) {
                $q = strafter($q, get_option('estimate_prefix'));
                $q = trim($q);
                $q = ltrim($q, '0');
            }
            // Estimates
            $estimates_fields  = prefixed_table_fields_array(db_prefix() . 'estimates');
            $clients_fields    = prefixed_table_fields_array(db_prefix() . 'clients');
            $noPermissionQuery = get_estimates_where_sql_for_staff(get_staff_user_id());

            $this->db->select(implode(',', $estimates_fields) . ',' . implode(',', $clients_fields) . ',' . db_prefix() . 'estimates.id as estimateid,' . get_sql_select_client_company());
            $this->db->from(db_prefix() . 'estimates');
            $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'estimates.clientid', 'left');
            $this->db->join(db_prefix() . 'currencies', db_prefix() . 'currencies.id = ' . db_prefix() . 'estimates.currency');
            $this->db->join(db_prefix() . 'contacts', db_prefix() . 'contacts.userid = ' . db_prefix() . 'clients.userid AND is_primary = 1', 'left');

            if (!$has_permission_view_estimates) {
                $this->db->where($noPermissionQuery);
            }

            if($rel_id !=0 && $rel_type == 'customer'){
                $this->db->where('clientid', $rel_id);
            }

            $this->db->where('(
                ' . db_prefix() . 'estimates.number LIKE "' . $this->db->escape_like_str($q) . '"
                OR
                ' . db_prefix() . 'clients.company LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.clientnote LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.vat LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.phonenumber LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.city LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.state LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.zip LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                address LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.adminnote LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.billing_street LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.billing_city LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.billing_state LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.billing_zip LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.shipping_street LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.shipping_city LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.shipping_state LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'estimates.shipping_zip LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.billing_street LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.billing_city LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.billing_state LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.billing_zip LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.shipping_street LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.shipping_city LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.shipping_state LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                OR
                ' . db_prefix() . 'clients.shipping_zip LIKE "%' . $this->db->escape_like_str($q) . '%" ESCAPE \'!\'
                )');

            $this->db->order_by('number,YEAR(date)', 'desc');
            if ($limit != 0) {
                $this->db->limit($limit);
            }
            $result['result'] = $this->db->get()->result_array();
        }

        return $result;
    }

    /**
     * @param  integer (optional)
     * @return object
     * Get Call logs rel types
     */
    public function get_rel_types($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'call_logs_rel_types')->row();
        }

        return $this->db->get(db_prefix() . 'call_logs_rel_types')->result_array();
    }

    /**
     * @param  integer (optional)
     * @return object
     * Get Call logs call directions
     */
    public function get_call_directions($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'call_logs_directions')->row();
        }

        return $this->db->get(db_prefix() . 'call_logs_directions')->result_array();
    }

    /* Call Logs Type start */

    /**
     * Get type
     * @param  mixed $id id (Optional)
     * @return mixed     object or array
     */
    public function get_cl_type($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'call_logs_rel_types')->row();
        }
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'call_logs_rel_types')->result_array();
    }

    /**
     * Add new  type
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_cl_type($data)
    {
        $data['key'] = url_title($data['name'], 'underscore', true);

        $this->db->insert(db_prefix() . 'call_logs_rel_types', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Type Added [ID: ' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update Type
     * @param  mixed $data All $_POST data
     * @param  mixed $id   id to update
     * @return boolean
     */
    public function update_cl_type($data, $id)
    {
        $data['key'] = url_title($data['name'], 'underscore', true);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'call_logs_rel_types', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Type Updated [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete type from database, if used return array with key referenced
     */
    public function delete_cl_type($id)
    {
        if (is_reference_in_table('rel_type', db_prefix() . 'call_logs', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'call_logs_rel_types');
        if ($this->db->affected_rows() > 0) {
            log_activity('Type Deleted [' . $id . ']');

            return true;
        }

        return false;
    }


    /* Call Logs Call Direction start */

    /**
     * Get Call Direction
     * @param  mixed $id id (Optional)
     * @return mixed     object or array
     */
    public function get_call_direction($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'call_logs_directions')->row();
        }
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'call_logs_directions')->result_array();
    }

    /**
     * Add new  Call Direction
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_call_direction($data)
    {
        $this->db->insert(db_prefix() . 'call_logs_directions', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Call Type Added [ID: ' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update Call Direction
     * @param  mixed $data All $_POST data
     * @param  mixed $id   id to update
     * @return boolean
     */
    public function update_call_direction($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'call_logs_directions', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Call Type Updated [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete type from database, if used return array with key referenced
     */
    public function delete_call_direction($id)
    {
        if (is_reference_in_table('call_direction', db_prefix() . 'call_logs', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'call_logs_directions');
        if ($this->db->affected_rows() > 0) {
            log_activity('Call Type Deleted [' . $id . ']');

            return true;
        }

        return false;
    }
    public function udpate_sms_response($data='',$id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'call_logs', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Call Log Updated [ID:' . $id . ']');
            return true;
        }
        return false;
    }

    /* Get the count for staff. */
    public function get_staff_counts_sms($staffid){
        $count = 0;

        $sql = "SELECT count(`staffid`) as total_count
                from ".db_prefix()."call_logs where staffid= '".$staffid."' AND (opt_event_type='sms' OR opt_event_type='bulk sms')" ;
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)){
            $count = $row->total_count;
        }

        return $count;
    }

    /* Count the total sms. */
    public function count_all_sms($start_date, $end_date, $staffid){
        $sql = "SELECT count(`call_direction`) total_sms
                from ".db_prefix()."call_logs where opt_event_type = 'sms' or opt_event_type = 'bulk sms' AND DATE_FORMAT(`call_start_time`,'%Y-%m-%d') between '".$start_date."' AND '".$end_date."' 
                AND (`staffid` = ".$staffid." || `call_with_staffid` = ".$staffid.") 
                " ;
        $query = $this->db->query($sql);
        $row = $query->row();
        if (isset($row)){
            return $row->total_sms;
        }
    }

    
    public function udpate_twilio_account($data)
    {
        $data['staffid'] = get_staff_user_id();
        $this->db->where('staffid', $data['staffid']);
        $this->db->update(db_prefix() . 'call_logs_staff_twilio', $data);
        if ($this->db->affected_rows() == 0) {
            $this->db->insert(db_prefix() . 'call_logs_staff_twilio', $data);
            return true;
        }
        return false;
    }

    public function get_twilio_account( $staffid = 0)
    {
        if(empty($staffid) || !is_numeric($staffid))
            $staffid = get_staff_user_id();
        $this->db->where('staffid', $staffid);
        //$this->db->where('active', 1);
        return $this->db->get(db_prefix() . 'call_logs_staff_twilio')->row();
        
    }

    public function save_voice_records($file_name, $call_log_id){
        $data = array();
        $data["file_path"] = $file_name;
        $data["call_log_id"] = $call_log_id;
        $data["create_date"] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix() . 'call_logs_voice_records', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }

    public function get_voice_records($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'call_logs_voice_records')->row();
        }
        $this->db->order_by('create_date', 'asc');

        return $this->db->get(db_prefix() . 'call_logs_voice_records')->result_array();
    }

    public function delete_recording($recording_id){
       
        $this->db->where('id', $recording_id);
        $this->db->delete(db_prefix() . 'call_logs_voice_records');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function get_staff_twilio_phone_number($staffid){
        if (is_numeric($staffid)) {
            $this->db->where('staffid', $staffid);
            $row = $this->db->get(db_prefix() . 'call_logs_staff_twilio')->row();
            if(isset($row))
            {
                return $row->twilio_phone_number;
            }
        }
        return "";
    }

    public function get_record_path($record_id){
        if (is_numeric($record_id)) {
            $this->db->where('id', $record_id);
            $row = $this->db->get(db_prefix() . 'call_logs_voice_records')->row();
            if(isset($row))
            {
                $path = 'uploads/call_logs/'.$row->call_log_id.'/'.$row->file_path;
                return FCPATH.$path;
            }
        }
        return "";
    }


}
