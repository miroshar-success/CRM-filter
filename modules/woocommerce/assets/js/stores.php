<script>
    $(function() {
        var opts = {
            'custom_view': '[name="custom_view"]'
        }

        initDataTable('.table-woocommerce_stores', '<?php echo admin_url('woocommerce/table/stores'); ?>', [4], [2,4], opts);

        $('#newWooStore').click(function() {
            $("#modal_wrapper").load("<?php echo admin_url('woocommerce/stores/modal'); ?>", {
                slug: 'new',
            }, function() {
                if ($('.modal-backdrop.fade').hasClass('in')) {
                    $('.modal-backdrop.fade').remove();
                }

                if ($('#newWooStoreModal').is(':hidden')) {
                    $('#newWooStoreModal').modal({
                        show: true
                    });
                }
                appValidateForm($("#new_wooStore-form"), {
                    name: "required",
                    url: {
                        required: true,
                        url: true
                    },
                    key: "required",
                    secret: "required",

                    'assignees[]': {
                        required: true,
                        minlength: 1
                    }

                }, function(form) {
                    $('button[type="submit"], button.close_btn').prop('disabled', true);
                    $('button[type="submit"]').html('<i class="fa fa-refresh fa-spin fa-fw"></i>');
                    form.submit();
                }, {
                    'assignees[]': "Please select at least 1 staff member"
                });
            })
        });
    });

    function editWooStore(el) {
        var id = $(el).data('id');

        $("#modal_wrapper").load("<?php echo admin_url('woocommerce/stores/modal'); ?>", {
            slug: 'edit',
            store_id: id
        }, function(response) {

            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#editWooStoreModal').is(':hidden')) {
                $('#editWooStoreModal').modal({
                    show: true
                });
            }
            $(el).button('reset');
        });
    }

    $('.modal').on('hidden.bs.modal', function(e) {
        $(this).removeData();
    });

    function woo_test(el) {
        "use strict";
        let woob = $(el);
        let id = woob.data('id');
        $.post(admin_url + "woocommerce/woocommerce/test_connection/"+id, {})
            .done(function(response) {
                if (response) {
                    response = JSON.parse(response);
                    if (response.success == true) {
                        alert_float('success', response.message);
                        woob.button('reset');
                    } else {
                        alert_float('warning', response.message);
                        woob.button('reset');
                    }
                }
            });

    }

    function woo_reset(el) {
        "use strict";
        let woob = $(el);
        let id = woob.data('id');
        $.post(admin_url + 'woocommerce/stores/reset/'+id, {})
            .done(function(response) {
                alert_float('success', 'woo_reset_success');
                woob.button('reset');
            });

    }

    function updateWooStore(el) {
        "use strict";
        let woob = $(el);
        let id = woob.data('id');
        $.post(admin_url + 'woocommerce/stores/refresh/'+id, {})
            .done(function() {
                alert_float('success','woo_check_successful');
                woob.button('reset');
            });

    }
</script>