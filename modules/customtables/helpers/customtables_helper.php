<?php

defined('BASEPATH') || exit('No direct script access allowed');

if (!function_exists('getColumnFromTable')) {
    function getColumnFromTable($tablename)
    {
        get_instance()->config->load(CUSTOMTABLES_MODULE . '/config');

        $config_columns = config_item($tablename . '_columns');
        $cfTable        = $tablename;
        if ('proposals' == $tablename || 'estimates' == $tablename || 'invoices' == $tablename) {
            $cfTable = rtrim($tablename, 's');
        }
        if ('clients' == $tablename) {
            $cfTable = 'customers';
        }
        $custom_fields = get_table_custom_fields($cfTable);

        $custom_fields_add = [];
        foreach ($custom_fields as $key => $value) {
            $selectAs            = (is_cf_date($value) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
            $acolumns            = '(SELECT value FROM ' . db_prefix() . 'customfieldsvalues WHERE ' . db_prefix() . 'customfieldsvalues.relid=' . db_prefix() . 'tasks.id AND ' . db_prefix() . 'customfieldsvalues.fieldid=' . $value['id'] . ' AND ' . db_prefix() . 'customfieldsvalues.fieldto="' . $value['fieldto'] . '" LIMIT 1) as ' . $selectAs;
            $custom_fields_add[] = [
                'column'          => ('tasks' == $tablename) ? $acolumns : 'ctable_' . $key . '.value as ' . $selectAs,
                'label'     => $value['name'],
                'initial'   => true,
                'required'     => true,
                'th_attrs'     => ['data-type' => $value['type'], 'data-custom-field' => 1],
            ];
        }
        $config_columns = array_merge($config_columns, $custom_fields_add);

        $show_columns = json_decode(get_option($tablename . '_show_columns')) ?? [];

        $data['available_options'] = array_filter($config_columns, function ($column, $key) use ($show_columns) {
            return !in_array($key, $show_columns) && !$column['required'];
        }, \ARRAY_FILTER_USE_BOTH);

        $selected_options = array_filter($config_columns, function ($column, $key) use ($show_columns) {
            return in_array($key, $show_columns) || $column['required'];
        }, \ARRAY_FILTER_USE_BOTH);

        $data['selected_options'] = array_filter(
            array_replace(array_flip($show_columns), $selected_options), function ($value, $key) {
                return (is_array($value)) ? $value : '';
        }, \ARRAY_FILTER_USE_BOTH);

        return $data;
    }
}

if (!function_exists('getFilterDatatableTab')) {
    function getFilterDatatableTab($array, $key)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        return null;
    }
}

if (!function_exists('get_table_styling_areas')) {
    function get_table_styling_areas()
    {
        $areas = [
            [
                'name'                 => _l('header_background_color'),
                'id'                   => 'table-headings-background',
                'target'               => 'table.dataTable thead tr>th, .table.dataTable>thead:first-child>tr:first-child>th',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => _l('header_border_color'),
                'id'                   => 'table-headings-border',
                'target'               => 'table.dataTable thead tr>th, .table.dataTable>thead:first-child>tr:first-child>th',
                'css'                  => 'border-color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => _l('header_text_color'),
                'id'                   => 'table-headings-text',
                'target'               => 'table.dataTable thead tr>th, .table.dataTable>thead:first-child>tr:first-child>th',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => _l('body_background_color'),
                'id'                   => 'table-body-background',
                'target'               => 'table.dataTable tbody tr>td',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => _l('body_border_color'),
                'id'                   => 'table-body-border',
                'target'               => 'table.dataTable tbody tr>td',
                'css'                  => 'border-color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => _l('body_text_color'),
                'id'                   => 'table-body-text',
                'target'               => 'table.dataTable tbody tr>td',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => _l('footer_background_color'),
                'id'                   => 'table-footer-background',
                'target'               => 'table.dataTable tfoot tr>td',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => _l('footer_border_color'),
                'id'                   => 'table-footer-border',
                'target'               => 'table.dataTable tfoot tr>td',
                'css'                  => 'border-color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => _l('footer_text_color'),
                'id'                   => 'table-footer-text',
                'target'               => 'table.dataTable tfoot tr>td',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
        ];

        return $areas;
    }
}

//Render colorpicker component for custom style
if (!function_exists('render_table_styling_picker')) {
    function render_table_styling_picker($id, $value, $target, $css, $additional = '')
    {
        // Output HTML for a styling picker input element
        echo '<div class="input-group mbot15 colorpicker-component" data-target="' . $target . '" data-css="' . $css . '" data-additional="' . $additional . '">
        <input type="text" value="' . $value . '" data-id="' . $id . '" class="form-control" />
        <span class="input-group-addon"><i></i></span>
        </div>';
    }
}

if (!function_exists('get_table_custom_style_values')) {
    function get_table_custom_style_values($selector)
    {
        $value         = '';
        // Get the applied styling area values for a given selector
        $theme_style   = get_table_applied_styling_area();
        foreach (get_table_styling_areas() as $area) {
            foreach ($theme_style as $applied_style) {
                if ($applied_style->id == $selector) {
                    $value = $applied_style->color;
                }
            }
        }

        return $value;
    }
}

if (!function_exists('get_table_applied_styling_area')) {
    function get_table_applied_styling_area()
    {
        // Get the applied custom table styling area values
        $table_style = get_option('table_custom_style');
        if ('' == $table_style) {
            return [];
        }
        $table_style = json_decode($table_style);

        return $table_style;
    }
}

// Append custom style for table based on applied styling
if (!function_exists('table_custom_style_render')) {
    function table_custom_style_render()
    {
        $theme_style   = get_table_applied_styling_area();
        $styling_areas = get_table_styling_areas();

        foreach ($styling_areas as $type => $_area) {
            foreach ($theme_style as $applied_style) {
                if ($applied_style->id == $_area['id']) {
                    // Output custom style CSS for a table element
                    echo '<style class="custom_style_' . $_area['id'] . '">';
                    echo $_area['target'] . '{';
                    echo $_area['css'] . ':' . $applied_style->color . ' !important ;';
                    echo '}';
                    echo '</style>';
                }
            }
        }
    }
}

// Append custom CSS for admin table
if (!function_exists('table_custom_css_render')) {
    function table_custom_css_render($type = '')
    {
        $table_custom_csss = get_option($type);
        if (!empty($table_custom_csss)) {
            // Output custom CSS for admin table
            echo '<style id="table_custom_css">';
            if (!empty($table_custom_csss)) {
                $table_custom_csss = clear_textarea_breaks($table_custom_csss);
                echo $table_custom_csss;
            }
            echo '</style>';
        }
    }
}

/* End of file customtables.php */
