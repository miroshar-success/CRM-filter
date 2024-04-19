<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = ['name'];

$sIndexColumn = 'id';
$sTable       = db_prefix().'call_logs_directions';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id','is_default']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        if($aRow['is_default']){
            $_data = '<a href="#" >' . $aRow[$aColumns[$i]] . '</a>';
        }else {
            $_data = '<a href="#" onclick="edit_call_direction(this,' . $aRow['id'] . '); return false" data-name="' . $aRow['name'] . '"  data-id="' . $aRow['id'] . '">' . $aRow[$aColumns[$i]] . '</a>';
        }
        $row[] = $_data;
    }
    //if($aRow['is_default']){
        //$options = '';
        //$row[] = $options;
    //}else {
        $options = icon_btn('call_logs/call_direction/' . $aRow['id'], 'pencil-square-o', 'btn-default', [
            'onclick' => 'edit_call_direction(this,' . $aRow['id'] . '); return false', 'data-name' => $aRow['name'],
        ]);
        $row[] = $options .= icon_btn('call_logs/delete_call_direction/' . $aRow['id'], 'remove', 'btn-danger _delete');
    //}
    $output['aaData'][] = $row;
}
