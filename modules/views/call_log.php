<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
$customer_type = '';
$clientid = '';
if(isset($call_log) || ($this->input->get('clientid') && $this->input->get('customer_type'))){
    if($this->input->get('clientid')){
        $clientid = $this->input->get('clientid');
        $customer_type = $this->input->get('customer_type');
    } else {
        $clientid = $call_log->clientid;
        $customer_type = $call_log->customer_type;
    }
}

$rel_type = '';
$rel_id = '';
if(isset($call_log) || ($this->input->get('rel_id') && $this->input->get('rel_type'))){
    if($this->input->get('rel_id')){
        $rel_id = $this->input->get('rel_id');
        $rel_type = $this->input->get('rel_type');
    } else {
        $rel_id = $call_log->rel_id;
        $rel_type = (isset($cl_rel_type))?$cl_rel_type->key:$call_log->rel_type;
    }
}

$contactid = '';
if(isset($call_log)){
 $contactid = $call_log->contactid;
}

?>
<script type="text/javascript" src="<?php echo base_url() ?>modules/call_logs/assets/js/twilio.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>modules/call_logs/assets/js/WebAudioRecorder.min.js"></script>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            if(isset($call_log)){
                echo form_hidden('is_edit','true');
            }
            ?>
            <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'calllog-form')) ;?>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />

                        <div class="form-group select-placeholder">
                            <label for="customer_type" class="control-label"><?php echo _l('cl_related'); ?></label>
                            <select name="customer_type" id="customer_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <option value="lead" <?php if((isset($call_log) && $call_log->customer_type == 'lead') || $this->input->get('customer_type')){if($customer_type == 'lead'){echo 'selected';}} ?>><?php echo _l('cl_lead'); ?></option>
                                <option value="customer" <?php if((isset($call_log) &&  $call_log->customer_type == 'customer') || $this->input->get('customer_type')){if($customer_type == 'customer'){echo 'selected';}} ?>><?php echo _l('cl_customer'); ?></option>
                            </select>
                        </div>
                        <div class="form-group select-placeholder<?php if($clientid == ''){echo ' hide';} ?> " id="clientid_wrapper">
                            <label for="clientid"><span class="clientid_label"></span></label>
                            <div id="clientid_select">
                                <select name="clientid" id="clientid" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" onchange="myFunction()">
                                    <?php if($clientid != '' && $customer_type != ''){
                                        $rel_data = get_relation_data($customer_type,$clientid);
                                        $rel_val = get_relation_values($rel_data,$customer_type);
                                        echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group select-placeholder <?php if($contactid == ''){echo ' hide';} ?> " id="contactid_wrapper">
                            <label for="contactid"><span class="contactid_label">Contact</span></label>
                            <div id="contactid_select">
                                <select name="contactid" id="contactid" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" onchange="myFunction2()">
                                    <?php
                                    if($contactid != ''){
                                        echo '<option value="'.$call_log->contactid.'" selected>'.$call_log->contact_name.' - '.$call_log->contact_email.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <?php
                        $selected = (isset($call_log) ? $call_log->rel_type : '');
                        if(is_admin() || get_option('staff_members_create_inline_cl_types') == '1'){
                            echo render_select_with_input_group('rel_type',$rel_types,array('id','name'),'cl_type',$selected,'<a href="#" onclick="new_cl_type();return false;"><i class="fa fa-plus"></i></a>');
                        } else {
                            echo render_select('rel_type',$rel_types,array('id','name'),'cl_type',$selected);
                        } ?>

                        <div class="form-group select-placeholder<?php if($rel_type != 'proposal' && $rel_type != 'estimate'){echo ' hide';} ?> " id="rel_id_wrapper">
                            <label for="rel_id"><span class="rel_id_label"></span></label>
                            <div id="rel_id_select">
                                <select name="rel_id" id="rel_id" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php if($rel_id != '' && $rel_type != ''){
                                        $rel_data = get_relation_data($rel_type,$rel_id);
                                        $rel_val = get_relation_values($rel_data,$rel_type);
                                        echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <?php
                        //print_r($call_directions); exit;
                        $selected = (isset($call_log) ? $call_log->call_direction : '');
                        if(is_admin() || get_option('staff_members_create_inline_call_direction') == '1'){
                            echo render_select_with_input_group('call_direction',$call_directions,array('id','name'),'call_log_direction',$selected,'<a href="#" onclick="new_call_direction();return false;"><i class="fa fa-plus"></i></a>');
                        } else {
                            echo render_select('call_direction',$call_directions,array('id','name'),'call_log_direction',$selected);
                        }
                        ?>

                        <?php $value = (isset($call_log) ? $call_log->call_purpose : ''); ?>
                        <?php echo render_input('call_purpose','call_purpose',$value); ?>

                        <?php $value = (isset($call_log) ? $call_log->call_summary : ''); ?>
                        <?php echo render_textarea('call_summary','call_log_add_edit_call_summary',$value,array('rows'=>4),array()); ?>

                        <div class="form-group follow_up_wrapper" app-field-wrapper="has_follow_up">
                            <div class="">
                                <span><?php echo _l('cl_follow_up_requried'); ?></span>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" value="1" id="has_follow_1" name="has_follow_up" <?php if(isset($call_log) && $call_log->has_follow_up == 1){echo 'checked';} ?>>
                                    <label for="has_follow_1"><?php echo _l('cl_follow_up_yes'); ?></label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" value="0" id="has_follow_0" name="has_follow_up" <?php if(isset($call_log) && $call_log->has_follow_up == 0){echo 'checked';}else if(!isset($call_log)){echo'checked';} ?>>
                                    <label for="has_follow_0"><?php echo _l('cl_follow_up_no'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group followup-schedule <?php if(!isset($call_log) || $call_log->has_follow_up == 0){echo 'hide';}?>">
                            <?php $value = ( (isset($call_log) && $call_log->follow_up_schedule!='') ? _d($call_log->follow_up_schedule) : _d(date('Y-m-d H:i'))) ?>
                            <?php echo render_datetime_input('follow_up_schedule','cl_follow_up_schedule',$value, ['readonly' => 'readonly']); ?>
                        </div>

                        <div class="btn-bottom-toolbar text-right">
                            <button type="button" class="btn btn-info save-cl"><?php echo _l('submit'); ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="row">
                            <div class="col-lg-6">
                             <h4 class="no-margin "><?php echo _l('advanced_options'); ?></h4>
                         </div>
                         <div class="col-lg-6 text-right">

                            <button type="button" class="btn btn-sm btn-danger" id="endcall"  style="display: none;"><span><i class="fa fa-phone" style="padding-right: 3px;"></i></span><?php echo _l('end_call'); ?></button>
                            <button type="button" class="btn btn-sm btn-success" id="answer-button"  style="display: none;"><span><i class="fa fa-phone" style="padding-right: 3px;"></i></span><?php echo _l('call_answer'); ?></button>
                         </div>
                     </div>
                     <hr class="hr-panel-heading" />
                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="userphone" class="control-label"> <small class="req text-danger">* </small><?php echo _l('cl_opt_event_type'); ?></label><br>
                                <div class="radio radio-primary radio-inline">
                                   <input type="radio" value="call" id="opt_event_type_call" name="opt_event_type" checked>
                                   <label for="opt_event_type_call">Call</label>
                               </div>
                               <div class="radio radio-primary radio-inline">
                                <input type="radio" value="sms" id="opt_event_type_sms" name="opt_event_type" >
                                <label for="opt_event_type_sms">Sms</label>
                            </div>    
                        </div>
                    </div>
                    <div class="col-md-10">
                        <?php $value = (isset($call_log) ? $call_log->userphone : '') ?>
                        <?php echo render_input('userphone',_l('phone_number_with_dialing_code'), $value);?>
                        
                    </div>
                    <div class="" id="call-input">
                        <div class="col-md-2" style="padding-left:10px;">
                         <button type="button" class="btn btn-sm btn-success" id="startcall" onclick="callCustomer()" style="margin-top: 26px;"><span><i class="fa fa-phone" style="padding-right: 3px;"></i></span><?php echo _l('call'); ?></button>
                         <!-- <button type="button" class="btn btn-sm btn-danger" id="endcall"  style="margin-top: 26px;display: none;"><span><i class="fa fa-phone" style="padding-right: 3px;"></i></span><?php echo _l('end_call'); ?></button>
                         <button type="button" class="btn btn-sm btn-success" id="answer-button"  style="margin-top: 26px;display: none;"><span><i class="fa fa-phone" style="padding-right: 3px;"></i></span><?php echo _l('call_answer'); ?></button> -->
                         <button type="button" class="btn btn-sm btn-primary call-support-button" onclick="callSupport()" style="margin-top: 26px;display: none;"><span><i class="fa fa-phone" style="padding-right: 3px;"></i></span><?php echo _l('call_support'); ?></button>
                     </div>
                     <div class="col-md-12">
                        <p id="call_message" style="display: none;"></p>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-danger" id="recordcall"  style="display: none;"><span><i class="fa fa-stop-circle" style="padding-right: 3px;"></i></span>Start recording</button>
                            <button type="button" class="btn btn-sm btn-danger" id="recordstopcall"  style="display: none;"><span><i class="fa fa-stop-circle" style="padding-right: 3px;"></i></span>Stop recording</button>
                        </div>
                        
                        <label id = "lblRecordedCall" style = "display:none;">Recorded Call</label>
                        <ul  id = "record_file_list" style = "padding-left:10px;">
                        </ul>
                    </div>

                    <div class="col-md-12">
                        <?php
                        $selected = '';
                        $call_status_array = array();
                        $call_status[0] = array('status_id'=> 'Completed','status' => _l('cl_call_status_completed'));
                        $call_status[1] = array('status_id'=> 'Busy','status' => _l('cl_call_status_busy'));
                        $call_status[2] = array('status_id'=> 'No Answer','status' => _l('cl_call_status_no_answer'));
                        $call_status[3] = array('status_id'=> 'Cancelled','status' => _l('cl_call_status_cancelled'));
                        $call_status_array = $call_status;
                        if(isset($call_log) && $call_log->opt_event_type == 'call'){
                            $selected = $call_log->twilio_sms_response;
                        }
                        echo render_select('call_status',$call_status_array,array('status_id',array('status')),'cl_call_status',$selected);
                        ?>
                    </div>
                </div>
                <div id="sms-input" style="display: none;">
                    <div class="col-md-12">
                        <?php echo render_textarea('sms_content','write_your_sms_here','',array(),array(),'','form-control'); ?>
                        <span id="rchars_limit">160</span>/<span id="user_entered">0</span>
                        <div class="row">
                           <div class="col-lg-8">
                            <input type="hidden" name="twilio_sms_response" value="n/a">
                            <p id="sms_message" style="display: none;"></p>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-right">
                               <button type="button" class="btn btn-sm btn-success" id="sms-send-btn" onclick="sendsmsnew()"><span><i class="fa fa-envelope" style="padding-right: 3px;"></i></span>Send</button>
                           </div>

                       </div>
                   </div>

               </div>
           </div>

           <div class="col-md-12 start-calendar">
            <?php $value = (isset($call_log) ? _d($call_log->call_start_time) : _d(date('Y-m-d H:i'))) ?>
            <?php echo render_datetime_input('call_start_time','cl_call_start_time',$value); ?>
        </div>
        <div class="col-md-12">
            <?php $value = (isset($call_log) ? _d($call_log->call_end_time) : _d(date('Y-m-d H:i'))) ?>
            <?php echo render_datetime_input('call_end_time','cl_call_end_time',$value); ?>
        </div>
        <div class="col-md-12" id="cl_call_duration_div">
            <?php $value = (isset($call_log) ? $call_log->call_duration : '') ?>
            <?php echo render_input('call_duration','cl_call_duration',$value, 'text', ["readonly" => "readonly"]); ?>
        </div>
        <div class="col-md-12">
            <?php echo render_input('staffid','', get_staff_user_id(), 'hidden'); ?>
            <?php echo render_input('staff_email','cl_call_owner', $owner->firstname.' '.$owner->lastname, 'text', ['disabled' => 'disabled']); ?>

            <?php
            $i = 0;
            $selected = '';
            foreach($staff as $member){
                if($member['staffid'] == get_staff_user_id()) {continue;}
                if(isset($call_log)){
                    if($call_log->call_with_staffid == $member['staffid']) {
                        $selected = $member['staffid'];
                    }
                }
                $i++;
            }
            // print_r($selected); die;
            echo render_select('call_with_staffid',$staff,array('staffid',array('firstname','lastname')),'cl_call_with_staff',$selected);
            ?>
        </div>

        <div class="col-md-12">
            <div class="">
                <span><?php echo _l('cl_call_log_completed'); ?></span>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" value="1" id="is_completed_1" name="is_completed" <?php if(isset($call_log) && $call_log->is_completed == 1){echo 'checked';} ?>>
                    <label for="is_completed_1"><?php echo _l('cl_follow_up_yes'); ?></label>
                </div>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" value="0" id="is_completed_0" name="is_completed" <?php if(isset($call_log) && $call_log->is_completed == 0){echo 'checked';}else if(!isset($call_log)){echo'checked';} ?>>
                    <label for="is_completed_0"><?php echo _l('cl_follow_up_no'); ?></label>
                </div>
            </div>
        </div>
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12">
            <div class="">
                <span><?php echo _l('cl_call_log_important'); ?>&nbsp;&nbsp;</span>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" value="1" id="is_important_1" name="is_important" <?php if(isset($call_log) && $call_log->is_important == 1){echo 'checked';} ?>>
                    <label for="is_important_1"><?php echo _l('cl_follow_up_yes'); ?></label>
                </div>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" value="0" id="is_important_0" name="is_important" <?php if(isset($call_log) && $call_log->is_important == 0){echo 'checked';}else if(!isset($call_log)){echo'checked';} ?>>
                    <label for="is_important_0"><?php echo _l('cl_follow_up_no'); ?></label>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<input type="hidden" name="lead_id" id="lead_id">
<input type="hidden" id="customer_id">

<?php echo form_close(); ?>
</div>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<?php $this->load->view('call_types/type.php'); ?>
<?php $this->load->view('call_types/call_direction.php'); ?>
<?php init_tail(); ?>

<?php if($contactid != ''): ?>
	<script type="text/javascript">
		$(function(){
         init_ajax_search('contactid', $('#contactid'), {clientid: $('#clientid').val()}, admin_url+'call_logs/get_contact');
     })
 </script>
<?php endif ?>

<script>
    var base_url = "<?php echo base_url();?>";
    function myFunction2(){
      var contactid =$('#contactid option:selected').val();
      if(contactid != ''){
         $('#contact_id').val(contactid);  
         $.ajax({ 
           url: admin_url+'call_logs/get_contact_info',
           type: 'POST',
           data:$('#calllog-form').serialize(),   
           success: function (result) {
             $('#userphone').val(result);
         }
     });
     }
 }

 function myFunction()
 {
    var leadid =$('#clientid option:selected').val();
    if(leadid!='')
    {

       var _customer_type = $('#customer_type');

       if(_customer_type.val() == 'lead'){
          $('#contactid_wrapper').addClass('hide');
          $('#lead_id').val(leadid)    
          $.ajax({ 
            url: admin_url+'call_logs/get_lead_info',
            type: 'POST',
            data:$('#calllog-form').serialize(),   
            success: function (result) {
              $('#userphone').val(result);
          }
      });
      }

      if(_customer_type.val() == 'customer'){
          $('#customer_id').val($('#clientid').val());
          $('#contactid_wrapper').removeClass('hide');
          init_ajax_search('contactid', $('#contactid'),{clientid: $('#customer_id').val()}, admin_url+'call_logs/get_contact');
      }

  }
}



var _clientid = $('#clientid'),
_customer_type = $('#customer_type'),
_clientid_wrapper = $('#clientid_wrapper'),
data = {};

var _rel_id = $('#rel_id'),
_rel_type = $('#rel_type'),
_rel_id_wrapper = $('#rel_id_wrapper');

var recorded_blobs = [];

$(function(){
    $('body').on('click','button.save-cl', function() {
        $( "#call_end_time" ).trigger('blur');
        $('form#calllog-form').submit();
    });

    $('body').on('change','#clientid', function() {
        initRelIdCntrl();
    });
    validate_call_log_form();

    $('.clientid_label').html(_customer_type.find('option:selected').text());
    _customer_type.on('change', function() {

        var clonedSelect = _clientid.html('').clone();
        _clientid.selectpicker('destroy').remove();
        _clientid = clonedSelect;
        $('#clientid_select').append(clonedSelect);
        call_log_clientid_select();
        _rel_id.trigger('change');
        if($(this).val() != ''){
            _clientid_wrapper.removeClass('hide');
        } else {
            _clientid_wrapper.addClass('hide');
        }
        $('.clientid_label').html(_customer_type.find('option:selected').text());

        initRelIdCntrl();
    });
    call_log_clientid_select();

    <?php if(!isset($call_log) && $clientid != ''){ ?>
        _clientid.change();
    <?php } ?>

    $('.rel_id_label').html(_rel_type.find('option:selected').text());
    _rel_type.on('change', function() {

        var clonedSelect = _rel_id.html('').clone();
        _rel_id.selectpicker('destroy').remove();
        _rel_id = clonedSelect;
        $('#rel_id_select').append(clonedSelect);
        call_log_rel_id_select();
        if($(this).val() == '1' || $(this).val() == '2'){

            _rel_id_wrapper.removeClass('hide');
        } else {
            _rel_id_wrapper.addClass('hide');
        }
        $('.rel_id_label').html(_rel_type.find('option:selected').text());
    });
    call_log_rel_id_select();
    <?php if(!isset($call_log) && $rel_id != ''){ ?>
        _rel_id.change();
    <?php } ?>

    $( "input[type='radio'][name='has_follow_up']" ).change(function() {
        if($('input[type="radio"][name="has_follow_up"]:checked').val() == 1){
            $('div.followup-schedule').removeClass('hide');
        }else{
            $('div.followup-schedule').addClass('hide');
        }
    });

    $( "#call_start_time" ).blur(function() {
            /*
            var now = moment($(this).val()); //todays date
            var end = moment($( "#call_end_time" ).val()); // another date
            var duration = moment.duration(end.diff(now));
            var hours = end.diff(now, 'hours', true); //duration.asDays();
            var diff = convertTime(Math.floor(hours * 60 * 60));
            $("#call_duration").val(diff);
            */
            calculate_duration($( this ).val(), $('#call_end_time').val());
        });
    $( "#call_end_time" ).blur(function() {
            /*
            var now = moment($('#call_start_time').val()); //todays date
            var end = moment($( this ).val()); // another date
            var duration = moment.duration(end.diff(now));
            var hours = end.diff(now, 'hours', true); //duration.asDays();
            var diff = convertTime(Math.floor(hours * 60 * 60));
            $("#call_duration").val(diff);
            */
            calculate_duration($('#call_start_time').val(), $( this ).val());
        });
});

function calculate_duration(start_time, end_time){
    $.ajax({ 
      url: admin_url+'call_logs/calculate_duration',
      type: 'POST',
      data: {
        start_time: start_time,
        end_time: end_time
    },
    success: function (result) {
        $("#call_duration").val(result)
    }
});
}

function initRelIdCntrl() {
    var clonedSelect = _rel_id.html('').clone();
    _rel_id.selectpicker('destroy').remove();
    _rel_id = clonedSelect;
    $('#rel_id_select').append(clonedSelect);
    call_log_rel_id_select();
    if(_rel_type.find('option:selected').val() != ''){
        _rel_id_wrapper.removeClass('hide');
    } else {
        _rel_id_wrapper.addClass('hide');
    }
    $('.rel_id_label').html(_rel_type.find('option:selected').text());
}

function validate_call_log_form(){
    $( "#call_end_time" ).trigger('blur');
    appValidateForm($('#calllog-form'), {
        customer_type: 'required',
        //clientid : 'required',
        rel_type : 'required',
        rel_id : {
            required: {
                depends: function() {
                    return (rel_type == '1' || rel_type == '2')?true:false;
                }
            }
        },
        call_direction : 'required',
        userphone: 'required',
        sms_content: {
            required: {
                depends: function(){
                    return ($("input[name='opt_event_type']:checked").val() == 'sms')?true:false;
                }
            }
        },
        call_purpose : 'required',
        call_summary : 'required',
        staffid : 'required',
        call_status : {
            required: {
                depends: function() {
                    return ($("input[name='opt_event_type']:checked").val() == 'sms')?false:true;
                }
            }
        },
        call_start_time: 'required',
        call_end_time: 'required',
        call_duration: {
            required: {
                depends: function(){
                    return ($("input[name='opt_event_type']:checked").val() == 'sms')?false:true;
                }
            }
        },
        follow_up_schedule : {
            required: {
                depends: function() {
                    return ($("input[name='has_follow_up']:checked").val() == '1')?true:false;
                }
            }
        },
    }, manage_call_log);
}

function manage_call_log() {
    //$("#calllog-form").submit(function (e) {
       
    //   e.preventDefault(); // avoid to execute the actual submit of the form.

       var frm = $('#calllog-form');
       var formData = new FormData(frm[0]);
       for (var i = 0; i < recorded_blobs.length; i++) {
        formData.append("recorded_blobs[]", recorded_blobs[i]);
       }
       var url = frm.attr('action');

       $("button.save-cl").attr('disabled', true);
       $.ajax({
         type: "POST",
         url: url,
         data: formData,
         processData: false,
         contentType: false,
         //encode: true,
       }).done(function (data) {
         window.location.href = admin_url + "call_logs"; 

       });
   
    // });
    return false;
}

function call_log_clientid_select(){
    var serverData = {};
    serverData.clientid = _clientid.val();
    data.type = _customer_type.val();
    init_ajax_search(_customer_type.val(),_clientid,serverData);
}

function call_log_rel_id_select(){
    var serverData = {};
    serverData.rel_type = $('#customer_type').children("option:selected"). val();
    serverData.rel_id = _clientid.val();
    var cl_rel_type= '';

    if(_rel_type.val() ==1){
        cl_rel_type = 'proposal';
    }else if(_rel_type.val() == 2){
        cl_rel_type = 'estimate';
    }else{
        cl_rel_type = _rel_type.val();
    }

    data.type = cl_rel_type;
    init_ajax_search(cl_rel_type,_rel_id,serverData, admin_url + 'call_logs/get_relation_data');
}

function convertTime(sec) {
    var hours = Math.floor(sec/3600);
    (hours >= 1) ? sec = sec - (hours*3600) : hours = '00';
    var min = Math.floor(sec/60);
    (min >= 1) ? sec = sec - (min*60) : min = '00';
    (sec < 1) ? sec='00' : void 0;

    (min.toString().length == 1) ? min = '0'+min : void 0;
    (sec.toString().length == 1) ? sec = '0'+sec : void 0;

    return hours+':'+min+':'+sec;
}
function sendcall()
{
    var userphone = document.getElementById('userphone').value;
    if(userphone=='')
    {
        alert('please enter your phone number');
    }
    else
    {
     $.ajax({ 
      url: admin_url+'call_logs/check_call',
      type: 'POST',
      data:$('#calllog-form').serialize(),  
      success: function (result) {
         if(result=='ok')
         {
            $('#call_message').html('<p style="color:green;">The call is currently ringing.'+'<img src="'+site_url+'modules/call_logs/callingimage.gif" width="100px">');
            $('#startcall').hide();
            $('#endcall').show();
            $('#call_message').show();
            document.getElementById("call_start_time").readOnly = false;
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
            var month = dt.getMonth()+1;
            var day = dt.getDate();
            var fdate = dt.getFullYear() + '-' +
            ((''+month).length<2 ? '0' : '') + month + '-' +
            ((''+day).length<2 ? '0' : '') + day;
            var fulldate = fdate + ' '+ time;
            $('#call_start_time').val(fulldate)
        }
        else
        {
            $('#call_message').html('<p style="color:red;">Number not found!</p>');
            $('#call_message').show();
        }
    }
});

 }
}

function sendsms()
{
    console.log($('#calllog-form').serialize());
    var userphone = document.getElementById('userphone').value;
    if(userphone=='')
    {
        alert('please enter your phone number');
    }
    else
    {
     $.ajax({ 
      url: admin_url+'call_logs/check_sms',
      type: 'POST',
      data:$('#calllog-form').serialize(),  
      success: function (result) {
         if(result=='ok')
         {
            $('#call_message').html('<p style="color:green;">The call is currently ringing.'+'<img src="'+site_url+'modules/call_logs/callingimage.gif" width="100px">');
            $('#startcall').hide();
            $('#endcall').show();
            $('#call_message').show();
            document.getElementById("call_start_time").readOnly = false;
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
            var month = dt.getMonth()+1;
            var day = dt.getDate();
            var fdate = dt.getFullYear() + '-' +
            ((''+month).length<2 ? '0' : '') + month + '-' +
            ((''+day).length<2 ? '0' : '') + day;
            var fulldate = fdate + ' '+ time;
            $('#call_start_time').val(fulldate)
        }
        else
        {
            $('#call_message').html('<p style="color:red;">Number not found!</p>');
            $('#call_message').show();
        }
    }
});

 }
}
function sendsmsnew()
{
    var userphone = [];
    userphone.push(document.getElementById('userphone').value);
    var smscontent = document.getElementById('sms_content').value;
    if(userphone=='')
    {
        alert('please enter your phone number');
    }
    else if(smscontent==''){
        alert('please enter your message');   
    }
    else
    {
     $.ajax({ 
      url: admin_url+'call_logs/SendSMS/send',
      type: 'POST',
      data:{message: smscontent, phone_number: userphone},
      beforeSend: function(xhr){
        $("#sms_content").prop('readonly', true);
        $("#sms-send-btn").attr('disabled',true);
        $('#sms_message').html('<p style="color:green;">The message is sending.'+'<img src="'+site_url+'modules/call_logs/callingimage.gif" width="100px">');
    },
    success: function (result) {
        var resposne = JSON.parse(result);
        console.log(resposne);
        if(resposne.errors.length == 0)
        {
            $("#sms_content").prop('readonly', true);
            $("input[name='twilio_sms_response']").val('Sent');
            $('#sms_message').html('<p style="color:green;">The message sent.');
            $("#sms-send-btn").attr('disabled',true);
            $('#sms_message').show();
            document.getElementById("call_start_time").readOnly = false;
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
            var month = dt.getMonth()+1;
            var day = dt.getDate();
            var fdate = dt.getFullYear() + '-' +
            ((''+month).length<2 ? '0' : '') + month + '-' +
            ((''+day).length<2 ? '0' : '') + day;
            var fulldate = fdate + ' '+ time;
            $('#call_start_time').val(fulldate)
        }
        else
        {
            $("input[name='twilio_sms_response']").val('Failed');
            $("#sms_content").prop('readonly', false);
            $("#sms-send-btn").attr('disabled',false);
            $('#sms_message').html('<p style="color:red;">'+resposne.errors[0].message+'!</p>');
            $('#sms_message').show();
        }
    }
});

 }
}

// browser phone call integration

$(document).ready(function() {
    $('input[type=radio][name=opt_event_type]').change(function(event) {
        if($(this).val() == 'sms'){
            $("input[name='twilio_sms_response']").val('n/a');
            $("#userphone").closest( "div .col-md-10" ).addClass('col-md-12')
            $("#userphone").closest( "div .col-md-10" ).removeClass('col-md-10')
            $("#call-input").hide();
            $("#cl_call_duration_div").hide();
            $("label[for='call_start_time']").html('<small class="req text-danger">* </small>Sms sent date and time');
            $("label[for='call_end_time']").html('<small class="req text-danger">* </small>Sms recieved date and time');
            $("#sms-input").show();
        }else if($(this).val() == 'call'){
            $("input[name='twilio_sms_response']").val($( "#call_status option:selected" ).val());
            $("#userphone").closest( "div .col-md-12" ).addClass('col-md-10')
            $("#userphone").closest( "div .col-md-12" ).removeClass('col-md-12')
            $("#sms-input").hide();
            $("label[for='call_start_time']").html('<small class="req text-danger">* </small>Call Start Time');
            $("label[for='call_end_time']").html('<small class="req text-danger">* </small>Call End Time');
            $("#call-input").show();
            $("#cl_call_duration_div").show();
            $("#sms_content").attr('disabled',false);
            $("#sms-send-btn").attr('disabled',false);
            $('#sms_message').hide();
            $('#sms_message').html('');
        }

    })  
    var maxLength = 160;
    var enterdChar = 0;
    $('#sms_content').keyup(function(e) {
      var textlen = maxLength - $(this).val().length;
      var enteredTextLen = enterdChar + $(this).val().length
      $('#rchars_limit').text(textlen);
      $('#user_entered').text(enteredTextLen);
      if(textlen <= 0){
        $(this).css('color','red');
        $('#rchars_limit').text(0);
    }else if(textlen > 0){
        $(this).css('color','black');
        $('#rchars_limit').text(textlen);

    }

});
    $("#call_status").change(function(){
        $("input[name='twilio_sms_response']").val($(this).val());
    })
});
</script>
<script type="text/javascript" src="<?php echo base_url() ?>modules/call_logs/assets/js/custom.js"></script>
</body>
</html>
