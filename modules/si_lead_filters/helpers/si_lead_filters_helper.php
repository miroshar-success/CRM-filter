<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Function that format lead status for the final user
 * @param  string  $id    status id
 * @param  boolean $text
 * @param  boolean $clean
 * @return string
 */
function si_format_lead_status($status, $text = false, $clean = false)
{
	if (!is_array($status)) {
		$status = si_get_lead_status_by_id($status);
	}

	$status_name = $status['name'];

	if ($clean == true) {
		return $status_name;
	}

	$style = '';
	$class = '';
	if ($text == false) {
		$style = 'border: 1px solid ' . $status['color'] . ';color:' . $status['color'] . ';';
		$class = 'label';
	} else {
		$style = 'color:' . $status['color'] . ';';
	}

	return '<span class="' . $class . '" style="' . $style . '">' . $status_name . '</span>';
}

/**
 * Get lead status by passed lead id
 * @param  mixed $id lead id
 * @return array
 */
function si_get_lead_status_by_id($id)
{
	$CI       = &get_instance();
	$statuses = $CI->leads_model->get_status();

	$status = [
		'id'         => 0,
		'bg_color'   => '#333',
		'text_color' => '#333',
		'name'       => '[Status Not Found]',
		'order'      => 1,
	];

	foreach ($statuses as $s) {
		if ($s['id'] == $id) {
			$status = $s;
			
		break;
		}
	}

	return $status;
}

function si_lf_get_custom_field_values($fieldid,$relid='')
{
	$CI       = &get_instance();
	$CI->db->select('distinct(value) as value',false);
	$CI->db->where('fieldid',$fieldid);
	if(is_numeric($relid))
		$CI->db->where('relid',$relid);
	$result = 	$CI->db->get(db_prefix() . 'customfieldsvalues');
	if($result)
		return $result->result_array();
	else
		return array();	
}

function si_lf_get_custom_fields_from_settings()
{
	$list_custom_field =[];
	if(get_option(SI_LEAD_FILTERS_MODULE_NAME.'_cf')!=="" )
		$list_custom_field = unserialize(get_option(SI_LEAD_FILTERS_MODULE_NAME.'_cf'));
	return $list_custom_field;
}



