<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="col-md-2"><?php echo (is_custom_js()) ? _l('elite_custom_js') : _l('elite_custom_css'); ?></h4>
                        <div class="_buttons col-md-2 pull-right">
                            <?php 
                            if(is_custom_js()){
                                $customurl = 'elite_custom_js';
                            }else{
                                $customurl = 'elite_custom_css';
                            }
                            ?>
                            <a href="<?php echo admin_url('elite_custom_js_css/'.$customurl.'/form/-1'); ?>" class="btn btn-info pull-right display-block"><?php echo _l('elite_create'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <?php
                        render_datatable(
                                array(
                            array('name' => _l('elite_name'), 'th_attrs' => array('width' => '150px;')),
                            _l('elite_description'),
                            _l('elite_tags'),
                            _l('elite_status'),
                            _l('elite_created_at')
                                ), 'elite_custom_js_css');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-elite_custom_js_css', window.location.href, 'undefined', 'undefined', 'undefined', [4, 'desc']);
        
        /* Hide export button */
        $('.dt-buttons').hide();
        
        $(document).on('click', '.status', function () {
            var confirmdet = confirm(app.lang.confirm_action_prompt);
            if (confirmdet == false) {
                return false;
            } else {
                var id = $(this).data('id');
                var status = $(this).data('value');
                if (id) {
                    var data = {};
                    data.id = id;
                    data.status = status;
                    $.post(admin_url + 'elite_custom_js_css/<?php echo $customurl; ?>/status', data).done(function (response) {
                        response = JSON.parse(response);
                        if (response.success) {
                            window.location.reload();
                        } else {
                            alert_float('danger', response.message);
                            $('.table-elite_custom_js_css').DataTable().ajax.reload();
                        }
                    }).fail(function (error) {
                        var response = JSON.parse(error.responseText);
                        alert_float('danger', response.message);
                    });
                }
            }
        });
    });
</script>
</body>
</html>
