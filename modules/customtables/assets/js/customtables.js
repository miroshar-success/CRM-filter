var currentRequest = null;
var pickers = $('.colorpicker-component');

$(function () {
    "use strict";
    $("#allow_columns").sortable({
        connectWith: ".connectedSortable",
        receive: function (event, ui) {
            if ($(ui.item).hasClass("disabled")) {
                $(ui.sender).sortable('cancel');
            }
        }
    }).disableSelection().on("sortstop", function (event, ui) {
        saveColumns();
    });

    $("#display_columns").sortable({
        connectWith: ".connectedSortable",
    }).disableSelection().on("sortstop", function (event, ui) {
        saveColumns();
    });

    $.each(pickers, function () {
        $(this).colorpicker({
            format: "hex"
        });
        $(this).colorpicker().on('changeColor', function (e) {
            var color = e.color.toHex();
            var _class = 'custom_style_' + $(this).find('input').data('id');
            var val = $(this).find('input').val();
            if (val == '') {
                $('.' + _class).remove();
                return false;
            }
            var append_data = '';
            var additional = $(this).data('additional');
            additional = additional.split('+');
            if (additional.length > 0 && additional[0] != '') {
                $.each(additional, function (i, add) {
                    add = add.split('|');
                    append_data += add[0] + '{' + add[1] + ':' + color + ' !important;}';
                });
            }
            append_data += $(this).data('target') + '{' + $(this).data('css') + ':' + color +
                ' !important;}';
            if ($('head').find('.' + _class).length > 0) {
                $('head').find('.' + _class).html(append_data);
            } else {
                $("<style />", {
                    class: _class,
                    type: 'text/css',
                    html: append_data
                }).appendTo("head");
            }
        });
    });
});

const saveColumns = () => {
    let columns = [];
    let table = $("#display_columns").data("table-name");
    $("#display_columns li").each(function () {
        columns.push($(this).data("column-id"));
    })
    var data = {};
    data[table] = columns;
    currentRequest = $.ajax({
        url: `${admin_url}customtables/storeColumns/`,
        type: 'post',
        data: data,
        beforeSend: function () {
            if (currentRequest != null) {
                currentRequest.abort();
            }
        },
        success: function (data) {

        }
    });
}

function resetDefaultTable(table) {
    $.ajax({
        url: `${admin_url}customtables/resetDefaultTable/` + table,
        type: 'post',
    })
        .done(function (response) {
            if (response) {
                alert_float('success', 'Reset Default Table Successfully');
            }
            setTimeout(function () {
                window.location.reload();
            }, 1000);
        });

}

function saveTableStyle() {
    var data = [];
    $.each(pickers, function () {
        var color = $(this).find('input').val();
        if (color != '') {
            var _data = {};
            _data.id = $(this).find('input').data('id');
            _data.color = color;
            data.push(_data);
        }
    });
    $.post(admin_url + 'customtables/saveTableStyle', {
        data: JSON.stringify(data),
        table_custom_css: $('#custom_css_for_table').val(),
    }).done(function () {
        window.location.reload();
    });
}

// Set custom css preview
function setCustomPreview() {
    $("#table_custom_css").remove();
    var data = $('#custom_css_for_table').val();
    var appendData = '<style id="table_custom_css">' + data + '</style>';
    $(appendData).appendTo("head");
}