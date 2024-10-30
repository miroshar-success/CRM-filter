<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('estimate_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $estimate_number . '</b>';

if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . estimate_status_color_pdf($status) . ');text-transform:uppercase;">' . format_estimate_status($status, '', false) . '</span>';
}

// Add logo
$info_left_column .= pdf_logo_url();
// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(10);

$organization_info = '<div style="color:#424242;">';
    $organization_info .= format_organization_info();
$organization_info .= '</div>';

// Estimate to
$estimate_info = '<b>' . _l('estimate_to') . '</b>';
$estimate_info .= '<div style="color:#424242;">';
$estimate_info .= format_customer_info($estimate, 'estimate', 'billing');
$estimate_info .= '</div>';

// ship to to
if ($estimate->include_shipping == 1 && $estimate->show_shipping_on_estimate == 1) {
    $estimate_info .= '<br /><b>' . _l('ship_to') . '</b>';
    $estimate_info .= '<div style="color:#424242;">';
    $estimate_info .= format_customer_info($estimate, 'estimate', 'shipping');
    $estimate_info .= '</div>';
}

$estimate_info .= '<br />' . _l('estimate_data_date') . ': ' . _d($estimate->date) . '<br />';

if (!empty($estimate->expirydate)) {
    $estimate_info .= _l('estimate_data_expiry_date') . ': ' . _d($estimate->expirydate) . '<br />';
}

if (!empty($estimate->reference_no)) {
    $estimate_info .= _l('reference_no') . ': ' . $estimate->reference_no . '<br />';
}

if ($estimate->sale_agent && get_option('show_sale_agent_on_estimates') == 1) {
    $estimate_info .= _l('sale_agent_string') . ': ' . get_staff_full_name($estimate->sale_agent) . '<br />';
}

if ($estimate->project_id && get_option('show_project_on_estimate') == 1) {
    $estimate_info .= _l('project') . ': ' . get_project_name_by_id($estimate->project_id) . '<br />';
}

foreach ($pdf_custom_fields as $field) {
    $value = get_custom_field_value($estimate->id, $field['id'], 'estimate');
    if ($value == '') {
        continue;
    }
    $estimate_info .= $field['name'] . ': ' . $value . '<br />';
}

$left_info  = $swap == '1' ? $estimate_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $estimate_info;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->Ln(4);
$pdf->SetFont($font_name, '', $font_size);
$pdf->Cell(0, 0, _l(''), 0, 0, 'L', 0, '', 0);

$pdf->Ln(4);
$pdf->SetFont($font_name, '', $font_size);
$pdf->Cell(0, 1, _l(''), 0, 0, 'L', 0, '', 0);

// PAGE BREAK
$pdf->writeHTML('<br pagebreak="true"/>');
$info_right_column .= '';
$info_right_column .= '';
$estimate_for_tech = unserialize(serialize($estimate));
if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . estimate_status_color_pdf($status) . ');text-transform:uppercase;">' . format_estimate_status($status, '', false) . '</span>';
}

// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

// The Table
$pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 6));

// The items table
$filtered_items_non_technical = array_filter($estimate->items, function($item) {
    return $item['technical_item'] != 1;
});
$estimate->items = $filtered_items_non_technical;
$items = get_items_table_data($estimate, 'estimate', 'pdf');

$tblhtml = $items->table();

// Regular expression to match quantity cells with value 0 within TCPDF cell tags
$pattern = '/<td[^>]*>(0)<\/td>/i';  // Case-insensitive matching

// Replace matched cells with empty cells
$tblhtml = preg_replace($pattern, '<td></td>', $tblhtml);

$pdf->writeHTML($tblhtml, true, false, false, false, '');

$pdf->Ln(0);
$tbltotal = '';
$tbltotal .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';
$tbltotal .= '
<tr>
    <td align="right" width="85%"><strong>' . _l('estimate_subtotal') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($estimate->subtotal, $estimate->currency_name) . '</td>
</tr>';

if (is_sale_discount_applied($estimate)) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('estimate_discount');
    if (is_sale_discount($estimate, 'percent')) {
        $tbltotal .= ' (' . app_format_number($estimate->discount_percent, true) . '%)';
    }
    $tbltotal .= '</strong>';
    $tbltotal .= '</td>';
    $tbltotal .= '<td align="right" width="15%">-' . app_format_money($estimate->discount_total, $estimate->currency_name) . '</td>
    </tr>';
}

foreach ($items->taxes() as $tax) {
    $tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</strong></td>
    <td align="right" width="15%">' . app_format_money($tax['total_tax'], $estimate->currency_name) . '</td>
</tr>';
}

if ((int)$estimate->adjustment != 0) {
    $tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . _l('estimate_adjustment') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($estimate->adjustment, $estimate->currency_name) . '</td>
</tr>';
}

$tbltotal .= '
<tr style="background-color:#f0f0f0;">
    <td align="right" width="85%"><strong>' . _l('estimate_total') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($estimate->total, $estimate->currency_name) . '</td>
</tr>';

$tbltotal .= '</table>';

$pdf->writeHTML($tbltotal, true, false, false, false, '');



// PAGE BREAK
$pdf->writeHTML('<br pagebreak="true"/>');
$info_right_column .= '';
$info_right_column .= '';
if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . estimate_status_color_pdf($status) . ');text-transform:uppercase;">' . format_estimate_status($status, '', false) . '</span>';
}

// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

// The Table
$pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 6));

// The items table
$filtered_items_technical = array_filter($estimate_for_tech->items, function($item) {
    return $item['technical_item'] == 1;
});
$estimate_for_tech->items = $filtered_items_technical;
$items = get_items_table_data($estimate_for_tech, 'estimate', 'pdf');

$tbltotal = $items->table();

// Regular expression to match quantity cells with value 0 within TCPDF cell tags
$pattern = '/<td[^>]*>(0)<\/td>/i';  // Case-insensitive matching

// Replace matched cells with empty cells
$tbltotal = preg_replace($pattern, '<td></td>', $tbltotal);

$pdf->writeHTML($tbltotal, true, false, false, false, '');

$pdf->Ln(0);
$tbltotal = '';
$tbltotal .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';
if(isset($estimate_for_tech->technical_items) && $estimate_for_tech->technical_items != null){
    $tbltotal .= '
    <tr id="technical_total">
        <td align="right" width="85%"><strong>' . _l('technical_items_total') . '</strong></td>
        <td align="right" width="15%">' . app_format_money($estimate_for_tech->technical_items_total, $estimate_for_tech->currency_name) . '</td>
    </tr>';
}

$tbltotal .= '</table>';

$pdf->writeHTML($tbltotal, true, false, false, false, '');

if (get_option('total_to_words_enabled') == 1) {
    // Set the font bold
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->writeHTMLCell('', '', '', '', _l('num_word') . ': ' . $CI->numberword->convert($estimate->total, $estimate->currency_name), 0, 1, false, true, 'C', true);
    // Set the font again to normal like the rest of the pdf
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(4);
}

if (!empty($estimate->clientnote)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', 8);
    $pdf->Cell(0, 0, _l('estimate_note'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', 8);
    $pdf->setCellHeightRatio(1); //
    $pdf->Ln(1);
    $pdf->writeHTMLCell('', '', '', '', $estimate->clientnote, 0, 1, false, true, 'L', true);
}

if (!empty($estimate->terms)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', 8);
    $pdf->Cell(0, 0, _l('terms_and_conditions') . ":", 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', 8);
    $pdf->setCellHeightRatio(1); //
    $pdf->Ln(1);
    $pdf->writeHTMLCell('', '', '', '', $estimate->terms, 0, 1, false, true, 'L', true);
}
