<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI = & get_instance();
$start = intval($CI->input->post('start'));
$length = intval($CI->input->post('length'));
$draw = intval($CI->input->post('draw'));

$aColumns = [
    'staffid',
];


$sIndexColumn = 'id';
$sTable       = db_prefix() . 'call_logs';
$join = [];

$result = get_cl_list_query($aColumns, $sIndexColumn, $sTable, $join, [], [], 'GROUP BY staffid');
$output  = $result['output'];
$rResult = $result['rResult'];

$prevPage = (($draw - 1) < 0)?0:($draw-1);
$nextPage = $draw + 1;
$nxtStart = ($start +1 ) * $length; //($draw <= 2)?$length:($draw - 1) * $length;
$prevStart = ($start -1 ) * $length; //(($draw - 1) >= 0)?(($draw - 1) * $length):0;
$this->load->library('pagination');

$config['base_url'] = '';
$config['total_rows'] = $output['iTotalDisplayRecords'];
$config['per_page'] = $length;
$config['use_page_numbers'] = TRUE;
$config['full_tag_open'] = "<ul class='pagination pagination-sm pull-right' style='position:relative; top:-25px;'>";
$config['full_tag_close'] ="</ul>";
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='javascript:;'>";
$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
$config['next_tag_open'] = "<li>";
$config['next_tagl_close'] = "</li>";
$config['prev_tag_open'] = "<li>";
$config['prev_tagl_close'] = "</li>";
$config['first_tag_open'] = "<li>";
$config['first_tagl_close'] = "</li>";
$config['last_tag_open'] = "<li>";
$config['last_tagl_close'] = "</li>";
$config['attributes'] = array('class' => 'paginate');
$config["uri_segment"] = 4;

$this->pagination->initialize($config);

$CI->load->model('staff_model');
$CI->load->model('leads_model');
$CI->load->model('clients_model');
$CI->load->model('call_logs_model');
?>
<div class="row">
    <div id="cl-grid-view" class="container-fluid">
<?php
if($output['iTotalDisplayRecords'] > 0){
foreach ($rResult as $aRow) {
    $oStaff = $CI->staff_model->get($aRow['staffid']);
    $staffCalls = $CI->call_logs_model->get_staff_counts($aRow['staffid']);
    $staffSMS = $CI->call_logs_model->get_staff_counts_sms($aRow['staffid']);
    $twilio_phone_number = $CI->call_logs_model->get_staff_twilio_phone_number($aRow['staffid']);
    if(!empty($oStaff)):
?>
    <div class="col-md-3">
        <div class="cardbox text-center">
            <div style="background: lightgrey; height: 70px; margin-bottom: -30px;"></div>
            <img src="<?php echo staff_profile_image_url($oStaff->staffid, 'thumb'); ?>">
            <h4><a href="<?php echo admin_url('profile/'.$oStaff->staffid);?>"><?php echo $oStaff->firstname.' '. $oStaff->lastname; ?></a></h4>
            <?php echo empty($twilio_phone_number)?"":"<p>".$twilio_phone_number."</p>"; ?>
            <p>Total Calls: <?php echo $staffCalls;?></p>
             <p>Total SMS: <?php echo $staffSMS;?></p>
        </div>
    </div>
    <?php endif ?>
<?php } }else{?>
    <div class="col-md-12">
        <div class="cardbox text-center dataTables_empty" style="border: none">
            <p>No entries found</p>
        </div>
    </div>
<?php } ?>
</div></div>
<div class="row">
    <div style='margin-top: 10px;' id='pagination'>
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>
<link href="<?php echo module_dir_url('call_logs', 'assets/css/cl.css'); ?>" rel="stylesheet">