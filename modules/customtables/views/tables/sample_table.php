<?php

defined('BASEPATH') || exit('No direct script access allowed');

$this->ci->config->load(CUSTOMTABLES_MODULE . '/config');
$sample_data = config_item('sample_data');

$result  = $sample_data;
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row                = [];
    $row[]              = $aRow['id'];
    $row[]              = $aRow['name'];
    $row[]              = $aRow['email'];
    $row[]              = $aRow['phone'];
    $output['aaData'][] = $row;
}
