<?php
defined('BASEPATH') or exit('No direct script access allowed');
$hide_class = 'not_visible not-export';
$table_data = [
	[
		'name'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="si_pm_category"><label></label></div>',
		'th_attrs' => ['class' => ((isset($bulk_actions) && $bulk_actions) ? '' : $hide_class)],
	],
	[
		'name'		=> _l('the_number_sign'),
		'th_attrs'	=> ['class' => '']
	],
	[
		'name'		=> _l('leads_dt_name'),
		'th_attrs'	=> ['class' => isset($hide_columns) && in_array('name',$hide_columns) ? $hide_class : '']
	],
	[
		'name'		=> _l('lead_company'),
		'th_attrs'	=> ['class' => isset($hide_columns) && in_array('company',$hide_columns) ? $hide_class : '']
	],
	[
		'name'		=> _l('leads_dt_email'),
		'th_attrs'	=> ['class' => isset($hide_columns) && in_array('email',$hide_columns) ? $hide_class : '']
	],
	[
		'name'		=> _l('leads_dt_phonenumber'),
		'th_attrs'	=> ['class' => isset($hide_columns) && in_array('phonenumber',$hide_columns) ? $hide_class : '']
	],
	[
		'name'		=> _l('lead_city'),
		'th_attrs'	=> ['class' => isset($hide_columns) && in_array('city',$hide_columns) ? $hide_class : '']
	],
	[
		'name'		=> _l('lead_state'),
		'th_attrs'	=> ['class' => isset($hide_columns) && in_array('state',$hide_columns) ? $hide_class : '']
	],
	[
		'name'		=> _l('lead_country'),
		'th_attrs'	=> ['class' => isset($hide_columns) && in_array('country',$hide_columns) ? $hide_class : '']
	],
	[
		'name'		=> _l('lead_zip'),
		'th_attrs'	=> ['class' => isset($hide_columns) && in_array('zip',$hide_columns) ? $hide_class : '']
	],
];
if($perfex_version >= 250){
	array_push($table_data, [
		'name'     => _l('lead_value'),
		'th_attrs' => 	['class' => isset($hide_columns) && in_array('lead_value',$hide_columns) ? $hide_class : ''],
	]);
}
$custom_fields = get_custom_fields('leads', ['show_on_table' => 1]);
foreach ($custom_fields as $field) {
	array_push($table_data, [
		'name'     => $field['name'],
		'th_attrs' => 	['data-type' => $field['type'], 'data-custom-field' => 1,
						'class' => isset($hide_columns) && in_array($field['slug'],$hide_columns) ? $hide_class : ''],
	]);
}
$table_data=array_merge($table_data, [
	[
		'name'     => _l('leads_dt_status'),
		'th_attrs' => 	['class' => isset($hide_columns) && in_array('status',$hide_columns) ? $hide_class : ''],
	],
	[
		'name'     => _l('lead_add_edit_source'),
		'th_attrs' => 	['class' => isset($hide_columns) && in_array('source',$hide_columns) ? $hide_class : ''],
	],
	[
		'name'     => _l('si_lf_created_date'),
		'th_attrs' => 	['class' => isset($hide_columns) && in_array('dateadded',$hide_columns) ? $hide_class : ''],
	],
	[
		'name'     => _l('si_lf_last_contacted_date'),
		'th_attrs' => 	['class' => isset($hide_columns) && in_array('lastcontact',$hide_columns) ? $hide_class : ''],
	],
	[
		'name'     => _l('lead_public'),
		'th_attrs' => 	['class' => isset($hide_columns) && in_array('is_public',$hide_columns) ? $hide_class : ''],
	],
	[
		'name'     => _l('leads_dt_assigned'),
		'th_attrs' => 	['class' => isset($hide_columns) && in_array('assigned',$hide_columns) ? $hide_class : ''],
	],
	[
		'name'     => _l('tags'),
		'th_attrs' => 	['class' => isset($hide_columns) && in_array('tags',$hide_columns) ? $hide_class : ''],
	],
]);
$table_data = hooks()->apply_filters('si_leads_table_columns', $table_data);
render_datatable($table_data, isset($class) ?  $class : 'si-leads scroll-responsive', ['number-index-1'], [
	'data-last-order-identifier'=> 'si-leads',
	'data-default-order'		=> get_table_last_order('si-leads'),
]);