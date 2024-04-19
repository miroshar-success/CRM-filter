<!-- Modal import content button-->
<div class="modal fade" id="importCustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo html_escape(_l('add_customer')) ?></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(admin_url('woocommerce/add_cutomer')); ?>
                <div class="form-group">
                    <br />
                    <label><?php echo _l('woocommerce_customer_id') ?></label>
                    <input type="text" class="form-control" name="id" id="id" value="<?= $customer->id ?>" readonly>
                    <?php echo render_input('company', 'client_company', $customer->billing->company ?? '', 'text', array()); ?>
                    <div class="text-danger" id="company_exists_info"></div>
                    <?php
                    $selected = array();
                    if (isset($customer_groups)) {
                        foreach ($customer_groups as $group) {
                            array_push($selected, $group['groupid']);
                        }
                    }
                    if (is_admin() || get_option('staff_members_create_inline_customer_groups') == '1') {
                        echo render_select_with_input_group('groups_in[]', $groups, array('id', 'name'), 'customer_groups', $selected, '<a href="#" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a>', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
                    } else {
                        echo render_select('groups_in[]', $groups, array('id', 'name'), 'customer_groups', $selected, array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
                    }
                    ?>

                    <?php $countries = get_all_countries();
                    $selected = '';
                    foreach ($countries as $country) {
                        if (isset($customer->billing->country)) {
                            if ($country['iso2'] == $customer->billing->country) {
                                $selected = $country['id'];
                                break;
                            }
                        }
                    }
                    echo render_select('country', $countries, array('country_id', array('short_name')), 'clients_country', $selected, array('data-none-selected-text' => _l('dropdown_non_selected_tex')));
                    ?>
                    <p class="bold"><?php echo _l('customer_permissions'); ?></p>
                    <p class="text-danger"><?php echo _l('contact_permissions_info'); ?></p>
                    <?php
                    $default_contact_permissions = array();
                    if (!isset($contact)) {
                        $default_contact_permissions = @unserialize(get_option('default_contact_permissions'));
                    }
                    ?>
                    <?php foreach ($customer_permissions as $permission) { ?>
                        <div class="col-md-6 row">
                            <div class="row">
                                <div class="col-md-6 mtop10 border-right">
                                    <span><?php echo $permission['name']; ?></span>
                                </div>
                                <div class="col-md-6 mtop10">
                                    <div class="onoffswitch">
                                        <input type="checkbox" id="<?php echo $permission['id']; ?>" class="onoffswitch-checkbox" <?php if (isset($contact) && has_contact_permission($permission['short_name'], $contact->id) || is_array($default_contact_permissions) && in_array($permission['id'], $default_contact_permissions)) {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?> value="<?php echo $permission['id']; ?>" name="permissions[]">
                                        <label class="onoffswitch-label" for="<?php echo $permission['id']; ?>"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <div class="form-group contact-direction-option">
                        <label for="direction"><?php echo _l('document_direction'); ?></label>
                        <select class="selectpicker" data-none-selected-text="<?php echo _l('system_default_string'); ?>" data-width="100%" name="direction" id="direction">
                            <option value=""></option>
                            <option value="ltr">LTR</option>
                            <option value="rtl">RTL</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" data-form="#wooImport"><?php echo _l('add_to_crm'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- End Modal import content -->