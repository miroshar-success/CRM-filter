<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
if ($elite_area == 'elitecustomjscss_admin_area_style') {
    $area_type = 'admin';
}
if ($elite_area == 'elitecustomjscss_clients_area_style') {
    $area_type = 'clients';
}
if($elite_area == 'web_to_lead_area_css' ) {
    $area_type = 'web_to_lead';
}

$CI = &get_instance();
$CI->db->select('code, code_view')->from(ElITE_CUSTOM_JS_CSS_TABLE_NAME);
$CI->db->where('code_type', 'css');
$CI->db->group_start();
$CI->db->where('area_type', $area_type);
$CI->db->or_where('area_type', 'both');
$CI->db->group_end();
$CI->db->where('status', 'active');
$result = $CI->db->get()->result_array();

if (!empty($result)) {
    foreach ($result as $value) {
        if ($value['code_view'] == 'with_tag') {
            echo '<style type="text/css">';
            echo $value['code'] . PHP_EOL;
            echo '</style>';
        } else {
            echo $value['code'] . PHP_EOL;
        }
    }
}
?>