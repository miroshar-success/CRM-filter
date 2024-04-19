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
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            if(isset($call_log)){
                echo form_hidden('is_edit','false');
            }
            ?>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />

                        <div class="form-group select-placeholder">
                            <label for="customer_type" class="control-label"><?php echo _l('cl_related'); ?></label>
                            <select disabled name="customer_type" id="customer_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <option value="lead" <?php if((isset($call_log) && $call_log->customer_type == 'lead') || $this->input->get('customer_type')){if($customer_type == 'lead'){echo 'selected';}} ?>><?php echo _l('cl_lead'); ?></option>
                                <option value="customer" <?php if((isset($call_log) &&  $call_log->customer_type == 'customer') || $this->input->get('customer_type')){if($customer_type == 'customer'){echo 'selected';}} ?>><?php echo _l('cl_customer'); ?></option>
                            </select>
                        </div>
                        <div class="form-group select-placeholder<?php if($clientid == ''){echo ' hide';} ?> " id="clientid_wrapper">
                            <label for="clientid"><span class="clientid_label"></span></label>
                            <div id="clientid_select">
                                <select disabled name="clientid" id="clientid" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
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
                                <select disabled="" name="contactid" id="contactid" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" onchange="myFunction2()">
                                    <?php
                                    if($contactid != ''){
                                        echo '<option value="'.$call_log->contactid.'" selected>'.$call_log->contact_name.' - '.$call_log->contact_email.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group select-placeholder">
                            <label for="rel_type" class="control-label"><?php echo _l('cl_type'); ?></label>
                            <select disabled name="rel_type" id="rel_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <?php foreach($rel_types as $type){ ?>
                                    <option value="<?php echo $type['id']; ?>" <?php if((isset($call_log) && $call_log->rel_type == $type['id']) || $this->input->get('rel_type')){echo 'selected';} ?>><?php echo _l($type['name']); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group select-placeholder<?php if($rel_type != 'proposal' && $rel_type != 'estimate'){echo ' hide';} ?> " id="rel_id_wrapper">
                            <label for="rel_id"><span class="rel_id_label"></span></label>
                            <div id="rel_id_select">
                                <select disabled name="rel_id" id="rel_id" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php if($rel_id != '' && $rel_type != ''){
                                        $rel_data = get_relation_data($rel_type,$rel_id);
                                        $rel_val = get_relation_values($rel_data,$rel_type);
                                        echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group select-placeholder" app-field-wrapper="call_direction">
                            <label for="call_direction" class="control-label"><?php echo _l('call_log_direction'); ?></label>
                            <select disabled name="call_direction" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <?php foreach($call_directions as $type){ ?>
                                    <option value="<?php echo $type['id']; ?>" <?php if(isset($call_log) && $call_log->call_direction == $type['id']){echo 'selected';} ?>><?php echo _l($type['name']); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <?php $value = (isset($call_log) ? $call_log->call_purpose : ''); ?>
                        <?php echo render_input('call_purpose','call_purpose',$value, '', ['readonly' => 'readonly']); ?>

                        <?php $value = (isset($call_log) ? $call_log->call_summary : ''); ?>
                        <?php echo render_textarea('call_summary','call_log_add_edit_call_summary',$value,array('rows'=>4,'readonly' => 'readonly'),array()); ?>

                        <div class="form-group follow_up_wrapper" app-field-wrapper="has_follow_up">
                            <div class="">
                                <span><?php echo _l('cl_follow_up_requried'); ?></span>
                                <div class="radio radio-primary radio-inline">
                                    <input disabled type="radio" value="1" id="has_follow_1" name="has_follow_up" <?php if(isset($call_log) && $call_log->has_follow_up == 1){echo 'checked';} ?>>
                                    <label for="has_follow_1"><?php echo _l('cl_follow_up_yes'); ?></label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input disabled type="radio" value="0" id="has_follow_0" name="has_follow_up" <?php if(isset($call_log) && $call_log->has_follow_up == 0){echo 'checked';}else if(!isset($call_log)){echo'checked';} ?>>
                                    <label for="has_follow_0"><?php echo _l('cl_follow_up_no'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group followup-schedule <?php if(!isset($call_log) || $call_log->has_follow_up == 0){echo 'hide';}?>">
                            <?php $value = (isset($call_log) ? _d($call_log->follow_up_schedule) : _d(date('Y-m-d H:i'))) ?>
                            <?php echo render_input('follow_up_schedule','cl_follow_up_schedule', $value, 'text', ['disabled' => 'disabled']); ?>

                            <a class="btn btn-primary" href="<?php echo admin_url('call_logs/call_log/'.$call_log->id) ?>">Start new calling</a>
                        </div>

                        <div class="btn-bottom-toolbar text-right">
                            <a href="<?php echo admin_url('call_logs'); ?>" class="btn btn-info"><?php echo _l('cl_back'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('advanced_options'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="row">
                             <?php if((isset($call_log) && ($call_log->opt_event_type == 'sms' || $call_log->opt_event_type == 'call') )){ ?>
                            <div class="col-md-12">
                                <?php $value = (isset($call_log) ? $call_log->userphone : '') ?>
                                <?php echo render_input('userphone','Enter Phone', $value, 'text', ['disabled' => 'disabled']);?>
                            </div>
                            <?php } ?>
                             <?php if((isset($call_log) &&  $call_log->opt_event_type == 'call')){ ?>
                                <div class="col-md-12">
                                    <?php echo render_input('call_status','cl_call_status', _l($call_log->twilio_sms_response), 'text', ['disabled' => 'disabled']); ?>
                                </div>
                            <?php } ?>
                            <?php if((isset($call_log) && ($call_log->opt_event_type == 'sms' || $call_log->opt_event_type == 'bulk sms') )){ ?>
                                <div class="col-md-12">
                                    <?php $value = (isset($call_log) ? $call_log->sms_content : '') ?>
                                    <?php echo render_textarea('sms_content','Sms Content Sent', $value, ['disabled' => 'disabled']);?>
                                </div>
                            <?php } ?>
                            <div class="col-md-12">
                                <?php $value = (isset($call_log) ? _d($call_log->call_start_time) : _d(date('Y-m-d H:i'))) ?>
                                <?php $cl_call_start_time = ($call_log->opt_event_type == 'sms' || $call_log->opt_event_type == 'bulk sms') ? 'cl_sms_start_time' : 'cl_call_start_time' ?>
                                <?php echo render_input('call_start_time',$cl_call_start_time, $value, 'text', ['disabled' => 'disabled']); ?>
                            </div>
                            <div class="col-md-12">
                                <?php $value = (isset($call_log) ? _d($call_log->call_end_time) : _d(date('Y-m-d H:i'))) ?>
                                <?php $cl_call_end_time = ($call_log->opt_event_type == 'sms' || $call_log->opt_event_type == 'bulk sms') ? 'cl_sms_end_time' : 'cl_call_end_time' ?>
                                <?php echo render_input('call_end_time',$cl_call_end_time, $value, 'text', ['disabled' => 'disabled']); ?>
                            </div>
                            <?php if($call_log->opt_event_type == 'call'){ ?>
                                <div class="col-md-12">
                                    <?php $value = (isset($call_log) ? $call_log->call_duration : '') ?>
                                    <?php echo render_input('call_duration','cl_call_duration',$value, 'text', ["readonly" => "readonly"]); ?>
                                </div>
                            <?php } ?>
                            <div class="col-md-12">
                                <?php echo render_input('staff_email','cl_call_owner', $owner->firstname.' '.$owner->lastname, 'text', ['disabled' => 'disabled']); ?>

                                <?php
                                $staffMember = get_staff($call_log->call_with_staffid);

                                echo render_input('call_with_staffid','cl_call_with_staff', $staffMember->firstname.' '.$staffMember->lastname, 'text', ['disabled' => 'disabled']);
                                ?>
                            </div>

                            <div class="col-md-12">
                                <div class="">
                                    <span><?php echo _l('cl_call_log_completed'); ?></span>
                                    <div class="radio radio-primary radio-inline">
                                        <input disabled type="radio" value="1" id="is_completed_1" name="is_completed" <?php if(isset($call_log) && $call_log->is_completed == 1){echo 'checked';} ?>>
                                        <label for="is_completed_1"><?php echo _l('cl_follow_up_yes'); ?></label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                        <input disabled type="radio" value="0" id="is_completed_0" name="is_completed" <?php if(isset($call_log) && $call_log->is_completed == 0){echo 'checked';}else if(!isset($call_log)){echo'checked';} ?>>
                                        <label for="is_completed_0"><?php echo _l('cl_follow_up_no'); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">&nbsp;</div>
                            <div class="col-md-12">
                                <div class="">
                                    <span><?php echo _l('cl_call_log_important'); ?>&nbsp;&nbsp;</span>
                                    <div class="radio radio-primary radio-inline">
                                        <input disabled type="radio" value="1" id="is_important_1" name="is_important" <?php if(isset($call_log) && $call_log->is_important == 1){echo 'checked';} ?>>
                                        <label for="is_important_1"><?php echo _l('cl_follow_up_yes'); ?></label>
                                    </div>
                                    <div class="radio radio-primary radio-inline">
                                        <input disabled type="radio" value="0" id="is_important_0" name="is_important" <?php if(isset($call_log) && $call_log->is_important == 0){echo 'checked';}else if(!isset($call_log)){echo'checked';} ?>>
                                        <label for="is_important_0"><?php echo _l('cl_follow_up_no'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
</div>
<?php init_tail(); ?>

<?php if($contactid != ''): ?>
    <script type="text/javascript">
        $(function(){
            init_ajax_search('contactid', $('#contactid'), {clientid: $('#clientid').val()}, admin_url+'call_logs/get_contact');
        })
    </script>
<?php endif ?>

<script>
    var _clientid = $('#clientid'),
    _customer_type = $('#customer_type'),
    _clientid_wrapper = $('#clientid_wrapper'),
    data = {};

    var _rel_id = $('#rel_id'),
    _rel_type = $('#rel_type'),
    _rel_id_wrapper = $('#rel_id_wrapper');

    $(function(){
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
            if($(this).val() == 'proposal' || $(this).val() == 'estimate'){
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
            var now = moment($(this).val()); //todays date
            var end = moment($( "#call_end_time" ).val()); // another date
            var duration = moment.duration(end.diff(now));
            var hours = end.diff(now, 'hours', true); //duration.asDays();
            var diff = convertTime(Math.floor(hours * 60 * 60));
            $("#call_duration").val(diff)
        });
        $( "#call_end_time" ).blur(function() {
            var now = moment($('#call_start_time').val()); //todays date
            var end = moment($( this ).val()); // another date
            var duration = moment.duration(end.diff(now));
            var hours = end.diff(now, 'hours', true); //duration.asDays();
            var diff = convertTime(Math.floor(hours * 60 * 60));
            $("#call_duration").val(diff)
        });
    });


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
            clientid : 'required',
            rel_type : 'required',
            rel_id : {
                required: {
                    depends: function() {
                        console.log("rel_type", rel_type)
                        return (rel_type == 'proposal' || rel_type == 'estimate')?true:false;
                    }
                }
            },
            call_purpose : 'required',
            call_summary : 'required',
            staffid : 'required'
        });
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
        data.type = _rel_type.val();
        init_ajax_search(_rel_type.val(),_rel_id,serverData, admin_url + 'call_logs/get_relation_data');
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
</script>
</body>
</html>
