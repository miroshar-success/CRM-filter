<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="clearfix"></div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-lg-8">
                                <h4 class="no-margin">Recorded call list</h4>
                            </div>
                            <div class="col-lg-4 text-right">
                                <div class="panel_s mbot10">
                                    <div class="_buttons">
                                        <a href="<?php echo admin_url('call_logs'); ?>" class="btn btn-info pull-right display-block mright5">
                                            <?php echo _l('go_back'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <div class="row" id="call-logs-record-table">
                                    
                                <div class="col-md-12">
                                    
                                        <?php render_datatable(array(
                                            'Call Log Purpose',
                                            'Caller',
                                            'Customer/lead name',
                                            'Phone',
                                            'Created Date',
                                            'File'
                                        ),'call_logs_records'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="btn-bottom-pusher"></div>
<!-- Call Log Modal-->
<div class="modal fade call_log-modal" id="call_log-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content data">

        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    $(function(){
        var TblServerParams = {
           
        };

        var tAPI = initDataTable('.table-call_logs_records', admin_url+'call_logs/records_table', [], [], TblServerParams,[1, 'desc']);
        $.each(TblServerParams, function(i, obj) {
            $('select' + obj).on('change', function() {
                $('table.table-call_logs_records').DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
            });
        });
        
    });

    // Init modal and get data from server
function init_call_log_modal(id) {
    var $callLogModal = $('#call_log-modal');

    requestGet('call_logs/get_call_log_data/' + id).done(function(response) {
        _task_append_html(response);
    }).fail(function(data) {
        alert_float('danger', data.responseText);
    });
}


</script>
</body>
</html>
