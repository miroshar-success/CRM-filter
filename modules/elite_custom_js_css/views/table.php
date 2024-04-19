<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'name',
    'description',
    'area_type',
    'status',
    'created_at',
];

$sIndexColumn = 'id';
$sTable = ElITE_CUSTOM_JS_CSS_TABLE_NAME;
if(is_custom_js()){
    $where = ['AND code_type="js"'];
    $customurl = 'elite_custom_js';
}else{
    $where = ['AND code_type="css"'];
    $customurl = 'elite_custom_css';
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ['id']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name') {
            $_data = '<a href="' . admin_url('elite_custom_js_css/'.$customurl.'/form/' . $aRow['id']) . '">' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('elite_custom_js_css/'.$customurl.'/form/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            $_data .= ' | <a href="' . admin_url('elite_custom_js_css/'.$customurl.'/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            $_data .= '</div>';
        } else if ($aColumns[$i] == 'status') {
//            $_data = ucfirst($aRow[$aColumns[$i]]);
            $checkbox_checked = '';
            if($aRow[$aColumns[$i]] == 'active'){
                $checkbox_checked = 'checked';
            }
            $_data = '<div class="onoffswitch">
                        <input type="checkbox" id="status'.$aRow['id'].'" class="onoffswitch-checkbox status" data-id="'.$aRow['id'].'" data-value="'.$aRow['status'].'" '.$checkbox_checked .' value="'.$aRow[$aColumns[$i]].'" >
                        <label class="onoffswitch-label" for="status'.$aRow['id'].'" data-toggle="tooltip" title="If you want to change status please click on this button"></label>
                      </div>';
        } else if ($aColumns[$i] == 'created_at') {
            $_data = date('d-m-Y H:i:s', strtotime($aRow[$aColumns[$i]]));
        } else if ($aColumns[$i] == 'area_type') {
            if($aRow[$aColumns[$i]] == 'admin') {
                $_data = 'Admin Area';
            } else if($aRow[$aColumns[$i]] == 'clients') {
                $_data = 'Clients Area';
            } else if($aRow[$aColumns[$i]] == 'both') {
                $_data = 'Admin and Clients Area';
            } else {
                $_data = '-';
            }
        }
        $row[] = $_data;
    }
    
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
