<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="cl-type-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('call_logs/cl_type'),array('id'=>'cl-type-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_cl_type'); ?></span>
                    <span class="add-title"><?php echo _l('new_cl_type'); ?></span>
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
        appValidateForm($('#cl-type-form'),{name:'required'},manage_cl_types);
        $('#cl-type-modal').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#cl-type-modal input[name="name"]').val('');
            $('#cl-type-modal textarea').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
        $('#cl-type-modal').on('show.bs.modal', function(e) {
            var invoker = $(e.relatedTarget);
            var type_id = $('#cl-type-modal').find('input[type="hidden"][name="id"]').val();
            if (typeof(type_id) !== 'undefined') {
                $('#cl-type-modal .add-title').addClass('hide');
                $('#cl-type-modal .edit-title').removeClass('hide');
            }else{
                $('#cl-type-modal .add-title').removeClass('hide');
                $('#cl-type-modal .edit-title').addClass('hide');
            }
        });
    });
    function manage_cl_types(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);

            if(response.success == true){
                alert_float('success',response.message);
                if($('body').hasClass('call_logs') && typeof(response.id) != 'undefined') {
                    var rel_type = $('#rel_type');
                    rel_type.find('option:first').after('<option value="'+response.id+'">'+response.name+'</option>');
                    rel_type.selectpicker('val',response.id);
                    rel_type.selectpicker('refresh');
                    rel_type.trigger('change');
                }
            }

            if($.fn.DataTable.isDataTable('.table-cl-types')){
                $('.table-cl-types').DataTable().ajax.reload();
            }

            $('#cl-type-modal').modal('hide');
        });
        return false;
    }

    function new_cl_type(){
        $('#cl-type-modal').modal('show');
        $('.edit-title').addClass('hide');
    }

    function edit_cl_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#cl-type-modal input[name="name"]').val(name);
        $('#cl-type-modal').modal('show');
        $('.add-title').addClass('hide');
    }
</script>
