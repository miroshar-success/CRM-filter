<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
                    <?php echo _l('select_color_scheme'); ?>
                </h4>
                <div class="panel_s">
                    <div class="panel-body pickers">
                        <!-- Horizontal tabs : start -->
                        <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                            <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                            <div class="horizontal-tabs">
                                <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#color_scheme" aria-controls="color_scheme" role="tab" data-toggle="tab" id="color_scheme_tab">
                                            <?php echo _l('color_scheme'); ?>
                                        </a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#custom_css" aria-controls="custom_css" role="tab" data-toggle="tab">
                                            <?php echo _l('custom_css'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Horizontal tabs : over -->
                        <div class="tab-content">
                            <!-- Color scheme : start -->
                            <div role="tabpanel" class="tab-pane active" id="color_scheme">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        foreach (get_table_styling_areas() as $area) {
                                        ?>
                                            <label class="bold inline-block"><?= $area['name'] ?></label>
                                        <?php echo render_table_styling_picker($area['id'], get_table_custom_style_values($area['id']), $area['target'], $area['css'], $area['additional_selectors']);
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Color scheme : over -->
                            <!-- Custom css : start -->
                            <div role="tabpanel" class="tab-pane" id="custom_css">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <?= _l('table_custom_style_message') ?>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="bold" for="custom_css_for_table">
                                            <i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="<?php echo _l('set_custom_css_for_datatable'); ?>"></i>
                                            <?php echo _l('set_custom_css'); ?>
                                        </label>
                                        <textarea name="custom_css_for_table" id="custom_css_for_table" rows="15" class="form-control"><?php echo clear_textarea_breaks(get_option('custom_css_for_table')); ?></textarea>
                                    </div>
                                </div>
                                <div class="row tw-mt-2 pull-right">
                                    <div class="col-md-12">
                                        <a href="javascript:void(0)" onclick="setCustomPreview(); return false;" class="btn btn-primary">
                                            <?php echo _l('preview'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Custom css : over -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
                    <?php echo _l('preview_table'); ?>
                </h4>
                <div class="panel_s">
                    <div class="panel-body">
                        <table class="table table-sample_table">
                            <thead>
                                <tr>
                                    <th><?= _l('id'); ?></th>
                                    <th><?= _l('name'); ?></th>
                                    <th><?= _l('email'); ?></th>
                                    <th><?= _l('phone') ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>Sample footer</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="btn-bottom-toolbar text-right">
                <a href="<?php echo admin_url('customtables/resetTableStyle'); ?>" class="btn btn-default">
                    <?php echo _l('reset'); ?>
                </a>
                <a href="javascript:void(0)" onclick="saveTableStyle(); return false;" class="btn btn-primary">
                    <?php echo _l('save'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    "use strict";
    initDataTable('.table-sample_table', admin_url + "customtables/customtables/getSampleTable", [0, 1, 2, 3], [0, 1, 2, 3]);
</script>