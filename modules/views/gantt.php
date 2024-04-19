<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<script type="text/javascript" src="<?php echo base_url() ?>modules/call_logs/assets/js/twilio.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>modules/call_logs/assets/js/WebAudioRecorder.min.js"></script>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">


                        <div class="row">
                            <div class="col-lg-8">
                                <div class="panel_s mbot10">
                                    <div class="_buttons">
                                        <a href="<?php echo admin_url('call_logs'); ?>" class="btn btn-info pull-left display-block mright5">
                                            <?php echo _l('go_back'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-right">
                                <button type="button" class="btn btn-sm btn-danger" id="endcall"  style="display: none;"><span><i class="fa fa-phone" style="padding-right: 3px;"></i></span><?php echo _l('end_call'); ?></button>
                                <button type="button" class="btn btn-sm btn-success" id="answer-button"  style="display: none;"><span><i class="fa fa-phone" style="padding-right: 3px;"></i></span><?php echo _l('call_answer'); ?></button>
                            </div> 
                        </div>



                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <div class="row" id="call-logs-table">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="bold"><?php echo _l('filter_by'); ?></p>
                                    </div>
                                    <div class="col-md-3 cl-filter-column">
                                        <?php echo render_select('view_assigned',$staffs,array('staffid',array('firstname','lastname')),'',$staffid,array('data-width'=>'100%','data-none-selected-text'=>_l('cl_filter_staff')),array(),'no-mbot'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading" />
                        </div>
                        <div class="col-md-4">
                            <div class="widget-wq">
                                <h4> <?php echo _l('cl_today_calls'); ?></h4>
                                <div class="row text-center">
                                    <div class="col-xs-4"><div class="text-primary"><?php echo $daily_count['inbound'];?></div> <span style="display: block;"><?php echo _l('cl_report_inbound_calls'); ?></span></div>
                                    <div class="col-xs-4"><div class="text-success"><?php echo $daily_count['outbound'];?></div> <span style="display: block;"><?php echo _l('cl_report_outbound_calls'); ?></span></div>
                                    <div class="col-xs-4"><div class="text-success" style="color: blue;"><?php echo $daily_sms;?></div> <span style="display: block;"><?php echo _l('cl_report_sms'); ?></span></div>
                                </div>
                            </div>
                            <div class="widget-wq">
                                <h4><?php echo _l('cl_weekly_calls'); ?></h4>
                                <div class="row text-center">
                                    <div class="col-xs-4"><div class="text-primary"><?php echo $week_count['inbound'];?></div> <span style="display: block;"><?php echo _l('cl_report_inbound_calls'); ?></span></div>
                                    <div class="col-xs-4"><div class="text-success"><?php echo $week_count['outbound'];?></div> <span style="display: block;"><?php echo _l('cl_report_outbound_calls'); ?></span></div>
                                    <div class="col-xs-4"><div class="text-success" style="color: blue;"><?php echo $week_sms;?></div> <span style="display: block;"><?php echo _l('cl_report_sms'); ?></span></div>
                                </div>
                            </div>
                            <div class="widget-wq">
                                <h4><?php echo _l('cl_monthly_calls');?></h4>
                                <div class="row text-center">
                                    <div class="col-xs-4"><div class="text-primary"><?php echo $month_count['inbound'];?></div> <span style="display: block;"><?php echo _l('cl_report_inbound_calls'); ?></span></div>
                                    <div class="col-xs-4"><div class="text-success"><?php echo $month_count['outbound'];?></div> <span style="display: block;"><?php echo _l('cl_report_outbound_calls'); ?></span></div>
                                    <div class="col-xs-4"><div class="text-success" style="color: blue;"><?php echo $month_sms;?></div> <span style="display: block;"><?php echo _l('cl_report_sms'); ?></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="widget-wq">
                                <h3><?php echo _l('cl_weekly_calls'); ?></h3>
                                <div class="relative" style="max-height:335px;">
                                    <canvas class="chart" height="335" id="report-weekly-call-logs"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="clear10px"></div>
                            <div class="widget-wq">
                                <h3><?php echo _l('cl_monthly_calls');?></h3>
                                <div class="relative" style="max-height:400px;">
                                    <canvas class="chart" height="400" id="report-monthly-call-logs"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<link href="<?php echo module_dir_url('call_logs', 'assets/css/cl.css'); ?>" rel="stylesheet">

<script>
    $(function(){
        var TblServerParams = {
            "assigned": "[name='view_assigned']",
        };
        $.each(TblServerParams, function(i, obj) {
            $('select' + obj).on('change', function() {
                let baseURL = "<?php echo admin_url('call_logs/overview'); ?>";
                window.location.href = baseURL + "/" + $(this).find('option:selected').val()
            });
        });

        chartWeeklyCallLogs = new Chart($('#report-weekly-call-logs'),{
            type:'bar',
            data:<?php echo $weekly_chart_Date; ?>,
            options:{maintainAspectRatio:false,scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            },}
        });

        chartMonthlyCallLogs = new Chart($('#report-monthly-call-logs'),{
            type:'bar',
            data:<?php echo $monthly_chart_Date; ?>,
            options:{maintainAspectRatio:false,scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            },}
        });
    });
</script>
<script type="text/javascript" src="<?php echo base_url() ?>modules/call_logs/assets/js/custom.js"></script>
</body>
</html>
