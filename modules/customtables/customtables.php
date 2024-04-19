<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
    Module Name: Custom Tables
    Description: Ultimate tool for customizing and optimizing Perfex CRM tables
    Version: 1.0.0
    Requires at least: 3.0.*
    Module URI: https://codecanyon.net/item/custom-data-tables-for-perfex-crm/48238609

*/

/*
 * Define module name
 * Module Name Must be in CAPITAL LETTERS
 */
define('CUSTOMTABLES_MODULE', 'customtables');
update_option('customtables_verification_id', '48238609');
update_option('customtables_last_verification', '2011038889');
update_option('customtables_product_token', true);
update_option('customtables_heartbeat', true);
 require __DIR__ . '/vendor/autoload.php';
//\modules\customtables\core\Apiinit::the_da_vinci_code(CUSTOMTABLES_MODULE);

/*
 * Register activation module hook
 */
register_activation_hook(CUSTOMTABLES_MODULE, 'customtables_module_activate_hook');
function customtables_module_activate_hook()
{
    require_once __DIR__ . '/install.php';
}

/*
 * Register deactivation module hook
 */
register_deactivation_hook(CUSTOMTABLES_MODULE, 'customtables_module_deactivate_hook');
function customtables_module_deactivate_hook()
{
    // Write your code here
}

/*
 * Register language files, must be registered if the module is using languages
 */
register_language_files(CUSTOMTABLES_MODULE, [CUSTOMTABLES_MODULE]);

/*
 * Load module helper file
 */
get_instance()->load->helper(CUSTOMTABLES_MODULE . '/customtables');

/*
 * Load module Library file
 */
get_instance()->load->library(CUSTOMTABLES_MODULE . '/customtables_lib');

get_instance()->config->load(CUSTOMTABLES_MODULE . '/config');

require_once __DIR__ . '/includes/assets.php';
require_once __DIR__ . '/includes/staff_permissions.php';
require_once __DIR__ . '/includes/sidebar_menu_links.php';

// hooks for all tables.
$tables = [
    'leads'     => 'leads',
    'customers' => 'clients',
    'proposals' => 'proposals',
    'estimates' => 'estimates',
    'projects'  => 'projects',
    'tasks'     => 'tasks',
    'invoices'  => 'invoices',
    'contracts' => 'contracts',
    'expenses'  => 'expenses',
];

// Make empty value for initial columns
hooks()->add_filter('datatables_sql_query_results', function ($result, $data) {
    $tableName = str_replace(db_prefix(), '', $data['table']);
    if ('expenses' == $tableName && 'projects' == get_instance()->uri->segment(2)) {
        return $result;
    }
    if ('reports' == get_instance()->uri->segment(2)) {
        return $result;
    }
    foreach ($result as &$row) {
        $tables = ['leads', 'clients', 'proposals', 'estimates', 'invoices', 'expenses', 'projects', 'tasks', 'contracts'];
        // Check if the current table name is in the list of tables
        if (in_array($tableName, $tables) && get_option($tableName . '_show_columns')) {
            processEmptyColumns($row, $tableName);
        }
    }

    return $result;
}, 10, 2);

foreach ($tables as $key => $table) {
    if ('expenses' == $table && 'projects' == get_instance()->uri->segment(2)) {
        return;
    }
    if ('reports' == get_instance()->uri->segment(2)) {
        return;
    }
    $option_name = "{$table}_show_columns";
    if (get_option($option_name)) {
        hooks()->add_filter("{$key}_table_columns", function ($tableData) use ($table) {
            return render_heads($table);
        });
        hooks()->add_filter("{$key}_table_sql_columns", function ($aColumns) use ($table) {
            return sql_columns($table);
        });
        hooks()->add_filter("{$key}_table_row_data", function ($parseHTML, $data) use ($table) {
            return render_columns($table, $parseHTML, $data);
        }, 10, 2);
    }
}

function processEmptyColumns(&$row, $table)
{
    $tableData    = getColumnFromTable($table)['available_options'];
    $emptyColumns = array_filter($tableData, function ($column) {
        return $column['initial'];
    });

    $emptyColumnNames = array_column($emptyColumns, 'column');

    foreach ($emptyColumnNames as $column) {
        if (false !== strpos($column, ' as ')) {
            $column = strafter($column, ' as ');
        }
        $row[$column] = '';
    }
}

// manage THEAD based on selection
function render_heads($type)
{
    $columns      = getColumnFromTable($type);
    $newTableData = [];

    foreach ($columns['selected_options'] as $column) {
        $newTableData[] = [
            'name'     => $column['label'],
            'th_attrs' => $column['th_attrs'] ?? ['class' => 'toggleable', 'id' => 'th-' . $column['column']],
        ];
    }

    return $newTableData;
}

// manage aColumns based on selection
function sql_columns($type)
{
    $columns = getColumnFromTable($type);

    foreach ($columns['selected_options'] as $column) {
        $newColumns[] = $column['column'];
    }

    return $newColumns;
}

// make data based on data and initial columns
function render_columns($type, $parseHTML, $data)
{
    // Set tableprefix
    $prefix          = db_prefix() . $type . '.';
    $columns         = getColumnFromTable($type);
    $selectedColumns = $columns['selected_options'];

    // Date field list
    $dateColumns = [
        'datefinished',
        'last_recurring_date',
        'date_finished',
        'project_created',
        'date',
        'acceptance_date',
        'invoiced_date',
        $prefix . 'datecreated',
        $prefix . 'dateadded',
    ];

    // Country field list
    $countryColumns = [
        $prefix . 'country',
        $prefix . 'billing_country',
        $prefix . 'shipping_country',
    ];

    $newhtml = [];
    foreach ($selectedColumns as $key => $column) {
        if (isset($parseHTML[$key])) {
            $row = $parseHTML[$key];
        } else {
            if (false !== strpos($column['column'], ' as ')) {
                $column['column'] = strafter($column['column'], ' as ');
            }
            switch ($column['column']) {
                case 'email':
                    $row = $data['email'] ? '<a href="mailto:' . $data['email'] . '">' . $data['email'] . '</a>' : '';
                    break;

                case 'phonenumber':
                    $row = $data['phonenumber'] ? '<a href="tel:' . $data['phonenumber'] . '">' . $data['phonenumber'] . '</a>' : '';
                    break;

                case in_array($column['column'], $dateColumns):
                    $row = _dt($data[$column['column']]);
                    break;

                case in_array($column['column'], $countryColumns):
                    $row = get_country_name($data[$column['column']]);
                    break;

                default:
                    $row = $data[$column['column']];
                    break;
            }
        }
        $newhtml[] = $row;
    }

    return $newhtml;
}
