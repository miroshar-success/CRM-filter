<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="call_directions-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('call_logs/call_direction'),array('id'=>'call_directions-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_call_direction'); ?></span>
                    <span class="add-title"><?php echo _l('new_call_direction'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','cl_type_add_edit_name'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    window.addEventListener('load',function(){
        appValidateForm($('#call_directions-form'),{name:'required'},manage_call_directions);
        $('#call_directions-modal').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#call_directions-modal input[name="name"]').val('');
            $('#call_directions-modal textarea').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });

        $('#call_directions-modal').on('show.bs.modal', function(e) {
            var invoker = $(e.relatedTarget);
            var type_id = $('#call_directions-modal').find('input[type="hidden"][name="id"]').val();
            if (typeof(type_id) !== 'undefined') {
                $('#call_directions-modal .add-title').addClass('hide');
                $('#call_directions-modal .edit-title').removeClass('hide');
            }else{
                $('#call_directions-modal .add-title').removeClass('hide');
                $('#call_directions-modal .edit-title').addClass('hide');
            }
        });
    });
    function manage_call_directions(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);

            if(response.success == true){
                alert_float('success',response.message);
                if($('body').hasClass('call_logs') && typeof(response.id) != 'undefined') {
                    var call_direction = $('#call_direction');
                    call_direction.find('option:first').after('<option value="'+response.id+'">'+response.name+'</option>');
                    call_direction.selectpicker('val',response.id);
                    call_direction.selectpicker('refresh');
                }
            }

            if($.fn.DataTable.isDataTable('.table-call_directions')){
                $('.table-call_directions').DataTable().ajax.reload();
            }

            $('#call_directions-modal').modal('hide');
        });
        return false;
    }

    function new_call_direction(){
        $('#call_directions-modal').modal('show');
        $('.edit-title').addClass('hide');
    }

    function edit_call_direction(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#call_directions-modal input[name="name"]').val(name);
        $('#call_directions-modal').modal('show');
        $('.add-title').addClass('hide');
    }
</script>
