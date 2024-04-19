<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
init_head();
?>

<!-- codemirror css -->
<link rel="stylesheet"
    href="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/lib/codemirror.css'); ?>">
<link rel="stylesheet"
    href="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/addon/display/fullscreen.css'); ?>">
<link rel="stylesheet"
    href="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/addon/hint/show-hint.css'); ?>">

<!-- module custom css -->
<link rel="stylesheet" href="<?php echo base_url('modules/elite_custom_js_css/css/elite-custom-css.css'); ?>">

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body" id="css_form">
                        <div id="myCarousel"></div>
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />

                        <?php if (!empty(validation_errors())) { ?>
                        <div class="col-lg-12 alert alert-danger mt-2" style="margin-bottom: 10px;">
                            <?php echo validation_errors(); ?>
                        </div>
                        <?php } ?>

                        <?php
                        $value = ($id == '-1') ? $id : $form_details->id;
                        echo render_input('form_id', '', $value, 'hidden', []);
                        ?>

                        <!-- View Area -->
                        <div class="form-group" app-field-wrapper="description">
                            <label for="area_type" class="control-label"><small class="req text-danger">*
                                </small><?php echo _l('elite_view_area'); ?>
                            </label>
                            <span><i class="fa fa-question-circle" data-html="true" data-toggle="tooltip" data-title="<?php echo _l('elite_view_area_tip'); ?>"></i></span>
                            <div class="col-md-12" style="margin-bottom: 15px;">
                                <div class="row">
                                    <div class="col-md-12" style="padding-bottom: 3px;">
                                        <div class="radio radio-primary radio-inline input_radio" id="area_type">
                                            <input type="radio" id="admin_area" name="area_type"
                                                <?php if ($form_details->area_type == 'admin') echo 'checked'; ?>
                                                value="admin">
                                            <label for="admin_area">
                                                <?php echo _l('elite_admin_area'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding-bottom: 3px;">
                                        <div class="radio radio-primary radio-inline input_radio">
                                            <input type="radio" id="clients_area" name="area_type"
                                                <?php if ($form_details->area_type == 'clients') echo 'checked'; ?>
                                                value="clients">
                                            <label for="clients_area">
                                                <?php echo _l('elite_clients_area'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="radio radio-primary radio-inline input_radio">
                                            <input type="radio" id="web_to_lead_area" name="area_type"
                                                <?php if ($form_details->area_type == 'web_to_lead') echo 'checked'; ?>
                                                value="web_to_lead">
                                            <label for="web_to_lead_area">
                                                <?php echo _l('elite_web_to_lead_area'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="radio radio-primary radio-inline input_radio">
                                            <input type="radio" id="both_area" name="area_type"
                                                <?php if ($form_details->area_type == 'both') echo 'checked'; ?>
                                                value="both">
                                            <label for="both_area">
                                                <?php echo _l('elite_both_area'); ?>
                                            </label>
                                        </div>
                                    </div>                                    
                                    <span id="error-area_type" class="elite-error" style="color: red;"></span>
                                </div>
                            </div>
                        </div>
                        <!-- View Area end-->

                        <!-- Name -->
                        <div class="form-group">
                            <?php echo form_label('<small class="req text-danger">* </small>' . _l('elite_name'), 'name', array('class' => 'control-label')); ?>
                            <?php
                            $input_attributes = array(
                                'class' => 'form-control',
                                'id' => 'name',
                                'name' => 'name',
                                'value' => set_value('name', $form_details->name),
                            );
                            echo form_input($input_attributes);
                            ?>
                            <span id="error-name" class="elite-error" style="color: red;"></span>
                        </div>
                        <!-- Name end -->

                        <!-- Write code (With Script / Without Script) -->
                        <div class="form-group" app-field-wrapper="description">
                            <label for="area_type" class="control-label"><small class="req text-danger">*
                                </small><?php echo is_custom_js() ? _l('elite_code_view') : _l('elite_code_view_css'); ?></label>
                            <div class="col-md-12" style="margin-bottom: 15px;">
                                <div class="row">
                                    <div class="col-md-12" style="padding-bottom: 3px;">
                                        <div class="radio radio-primary radio-inline input_radio" id="code_view">
                                            <input type="radio" id="code_view_with_tag" class="code_view"
                                                name="code_view"
                                                <?php echo ($form_details->code_view == 'with_tag') ? 'checked' : 'checked'; ?>
                                                value="with_tag">
                                            <label for="code_view_with_tag">
                                                <?php echo is_custom_js() ? _l('elite_code_view_with_tag') : _l('elite_code_view_with_tag_css'); ?>
                                            </label>

                                            <span><i class="fa fa-question-circle" data-html="true"
                                                    data-toggle="tooltip" data-title="<?php echo is_custom_js() ? _l('elite_code_view_with_tag_ttip') : _l('elite_code_view_with_tag_css_ttip'); ?>"
                                                    data-original-title="" title=""></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="padding-bottom: 3px;">
                                        <div class="radio radio-primary radio-inline input_radio">
                                            <input type="radio" id="code_view_without_tag" class="code_view"
                                                name="code_view"
                                                <?php if ($form_details->code_view == 'without_tag') echo 'checked'; ?>
                                                value="without_tag">
                                            <label for="code_view_without_tag">
                                                <?php echo is_custom_js() ? _l('elite_code_view_without_tag') : _l('elite_code_view_without_tag_css'); ?>
                                            </label>
                                            <span><i class="fa fa-question-circle" data-html="true"
                                                    data-toggle="tooltip" data-title="<?php echo is_custom_js() ? _l('elite_code_view_without_tag_ttip') : _l('elite_code_view_without_tag_css_ttip'); ?>"
                                                    data-original-title="" title=""></i>
                                            </span>
                                        </div>
                                    </div>
                                    <span id="error-code_view" class="elite-error" style="color: red;"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Write code (With Script / Without Script) end -->

                        <!-- Code -->
                        <div class="col-md-12 mirror-main-div">
                            <div class="row">
                                <div class="form-group">
                                    <?php echo form_label('<small class="req text-danger">* </small>' . _l('elite_code'), 'code', array('class' => 'control-label')); ?>
                                    <?php
                                    if (is_custom_js()) {
                                        $code_mirror_before = '<script type="text/javascript">';
                                        $code_mirror_after = '</script>';
                                    } else {
                                        $code_mirror_before = '<style type="text/css">';
                                        $code_mirror_after = '</style>';
                                    }
                                    ?>
                                    <div class="code-mirror-buttons code-mirror-buttons-custom">
                                        <button tabindex="-1" id="elite-custom-css-beautifier" data-toggle="tooltip"
                                            data-title="Beautify Code"><i class="fa fa-list"></i></button>
                                        <button id="elite-custom-css-fullscreen-button" class="align-right"
                                            role="presentation" tabindex="-1" data-toggle="tooltip"
                                            data-title="Fullscreen" style="float: right">
                                            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="code-mirror-before">
                                                <div><?php echo htmlentities($code_mirror_before); ?></div>
                                            </div>
                                            <textarea class="form-control elite-custom-css-textarea"
                                                id="elite_custom_css_code" name="code" rows="15"
                                                style="font-size:16px;letter-spacing: 1px;"><?php echo $form_details->code; ?></textarea>
                                            <div class="code-mirror-after">
                                                <div><?php echo htmlentities($code_mirror_after); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <span id="error-code" class="elite-error" style="color: red;"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Code end -->

                        <!-- Description -->
                        <?php
                        $value = set_value('description', $form_details->description);
                        echo render_textarea('description', 'elite_description', $value, ['rows' => '5']);
                        ?>
                        <!-- Description end-->

                        <!-- Status -->
                        <div class="form-group">
                            <div class="col-md-6" style="padding-left: 0px;">
                                <?php echo form_label('<small class="req text-danger">* </small>' . _l('elite_status'), 'status', array('class' => 'control-label')); ?>
                                <?php
                                $selected = (!empty($this->input->post('status'))) ? $this->input->post('status') : ((!empty($form_details->status)) ? $form_details->status : 'active');
                                $status_array = array(
                                    '' => 'Select Status',
                                    'active' => 'Active',
                                    'inactive' => 'Inactive'
                                );
                                $input_attributes = array(
                                    'class' => 'form-control selectpicker',
                                    'id' => 'status',
                                    'name' => 'name',
                                    'value' => set_value('code', $form_details->status),
                                    'data-live-search' => 'true'
                                );
                                echo form_dropdown('status', $status_array, $selected, $input_attributes);
                                ?>
                                <span id="error-status" class="elite-error" style="color: red;"></span>
                            </div>
                        </div>
                        <!-- Status end -->

                        <div class="col-md-12">
                            <a href="<?php echo admin_url('elite_custom_js_css/' . $controller_name); ?>"
                                class="btn btn-default pull-right"
                                style="margin-left: 5px;"><?php echo _l('back'); ?></a>
                            <button type="button" class="btn btn-info pull-right" id="save_changes">
                                <?php
                                if ($id == '-1') {
                                    echo _l('submit');
                                } else {
                                    echo _l('update');
                                }
                                ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<!-- codemirror js -->
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/lib/codemirror.js'); ?>"></script>
<?php
if (is_custom_js()) {
?>
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/mode/javascript/javascript.js'); ?>">
</script>
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/addon/hint/javascript-hint.js'); ?>">
</script>
<?php
} else {
?>
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/mode/css/css.js'); ?>"></script>
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/addon/hint/css-hint.js'); ?>"></script>
<?php
}
?>
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/addon/display/fullscreen.js'); ?>">
</script>
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/addon/edit/closebrackets.js'); ?>">
</script>
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/addon/edit/matchbrackets.js'); ?>">
</script>
<script type="text/javascript"
    src="<?php echo base_url('modules/elite_custom_js_css/plugins/codemirror/lib/util/formatting.js'); ?>"></script>

<script>
$(function() {
    var is_custom_js = '<?php echo is_custom_js(); ?>';
    var editorMode = '';
    var codeType = '';
    var customUrl = '';
    if (is_custom_js) {
        editorMode = 'javascript';
        codeType = 'js';
        customUrl = 'elite_custom_js';
    } else {
        editorMode = 'css';
        codeType = 'css';
        customUrl = 'elite_custom_css';
    }

    var editor;
    var elementId = document.getElementsByClassName('elite-custom-css-textarea')[0].id;
    var textarea = document.getElementById(elementId);
    editor = CodeMirror.fromTextArea(textarea, {
        lineNumbers: true,
        mode: editorMode,
        matchBrackets: true,
        autoCloseBrackets: true,
        extraKeys: {
            "F11": function(cm) {
                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                fullscreen_buttons(true);
            },
            "Esc": function(cm) {
                if (cm.getOption("fullScreen")) {
                    cm.setOption("fullScreen", false);
                }
                fullscreen_buttons(false);
            }
        }
    });

    /* Code Beautifier */
    $(document).on('click', '#elite-custom-css-beautifier', function(e) {
        CodeMirror.commands["selectAll"](editor);
        editor.autoFormatRange(editor.getCursor(true), editor.getCursor(false));
        editor.setCursor(0);
        e.preventDefault();
    });

    /* Action for the `fullscreen` button */
    $(document).on('click', '#elite-custom-css-fullscreen-button', function(e) {
        editor.setOption("fullScreen", true);
        fullscreen_buttons(true);
    });

    /* Toggle the buttons when in fullscreen mode */
    function fullscreen_buttons(mode) {
        editor.focus();
        if (mode === true) {
            $(".CodeMirror").css({
                'position': 'fixed',
                'z-index': 100005,
            });
            $("#save_changes").css({
                'position': 'fixed',
                'right': '40px',
                'bottom': '40px',
                'z-index': 100005,
            });
        } else {
            $(".CodeMirror").css({
                'position': 'static',
                'z-index': 10,
            });
            $("#save_changes").css({
                'position': 'static',
                'right': 'initial',
                'bottom': 'initial',
                'z-index': 10,
            });
        }
    }

    $(document).on('click', '#save_changes', function() {
        "use strict";
        var custom_css = editor.getValue();
        var field_data = {
            id: $("#form_id").val(),
            name: $("#name").val(),
            description: $("#description").val(),
            area_type: $("input[name='area_type']:checked").val(),
            status: $("#status").val(),
            code: custom_css,
            code_type: codeType,
            code_view: $("input[name='code_view']:checked").val()
        };

        $.post(admin_url + 'elite_custom_js_css/' + customUrl + '/save', field_data).done(function(
            response) {
            var result = JSON.parse(response);
            $('.elite-error').html(''); /* Blank all error view */
            if (result.level == 'error') {
                if (result.message) {
                    var elite_count = 1;
                    $.each(result.message, function(index, value) {
                        if (elite_count == 1) {
                            $('#' + index).focus();
                        }
                        $('#error-' + index).html(value);
                        elite_count++;
                    });
                    alert_float('danger', '<?php echo _l('elite_form_error') ?>');
                }
            } else {
                window.location.href = admin_url + 'elite_custom_js_css/' + customUrl;
            }
        });
    });

    /* Sidbar menu active */
    if (is_custom_js) {
        $('.sub-menu-item-elite-custom-js').addClass('active');
    } else {
        $('.sub-menu-item-elite-custom-css').addClass('active');
    }
    $('.menu-item-elite_custom_js_css').addClass('active');
    $('.menu-item-elite_custom_js_css ul').addClass('in');
    $('.menu-item-elite_custom_js_css ul').attr('aria-expanded', 'true');
    /* Sidbar menu active end */


    /* "script" tag or "style" tag Hide Show */
    $(document).on('change', '.code_view', function() {
        var value = $(this).val();
        tag_enabled_disabled(value);
    });
    /* "script" tag or "style" tag Hide Show END */

    var code_view = '<?php echo $form_details->code_view; ?>';
    tag_enabled_disabled(code_view);

    function tag_enabled_disabled(value) {
        if (value == 'without_tag') {
            $('.code-mirror-before').hide();
            $('.code-mirror-after').hide();
        } else {
            $('.code-mirror-before').show();
            $('.code-mirror-after').show();
        }
    }

});
</script>
</body>

</html>