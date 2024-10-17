<?php

defined('BASEPATH') or exit('No direct script access allowed');
$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('estimate_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $number . '</b>';


// Add logo
$info_left_column .= pdf_logo_url();
// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(10);
$y = $pdf->getY();
$proposal_info = '<div style="color:#424242;">';
    $proposal_info .= format_organization_info();
$proposal_info .= '</div>';

$pdf->writeHTMLCell(($swap == '0' ? (($dimensions['wk'] / 2) - $dimensions['rm']) : ''), '', '', ($swap == '0' ? $y : ''), $proposal_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);

$rowcount = max([$pdf->getNumLines($proposal_info, 80)]);

// Proposal to
$client_details = '<b>' . _l('proposal_to') . '</b>';
$client_details .= '<div style="color:#424242;">';
    $client_details .= format_proposal_info($proposal, 'pdf');
$client_details .= '</div>';

$pdf->writeHTMLCell(($dimensions['wk'] / 2) - $dimensions['lm'], $rowcount * 3, '', ($swap == '1' ? $y : ''), $client_details, 0, 1, false, true, ($swap == '1' ? 'J' : 'R'), true);

$proposal_date = _l('proposal_date') . ': ' . _d($proposal->date);
$open_till     = '';

if (!empty($proposal->open_till)) {
    $open_till = _l('proposal_open_till') . ': ' . _d($proposal->open_till) . '<br />';
}


$project = '';
if ($proposal->project_id != '' && get_option('show_project_on_proposal') == 1) {
    $project .= _l('project') . ': ' . get_project_name_by_id($proposal->project_id) . '<br />';
}

$qty_heading = _l('estimate_table_quantity_heading', '', false);

if ($proposal->show_quantity_as == 2) {
    $qty_heading = _l($this->type . '_table_hours_heading', '', false);
} elseif ($proposal->show_quantity_as == 3) {
    $qty_heading = _l('estimate_table_quantity_heading', '', false) . '/' . _l('estimate_table_hours_heading', '', false);
}

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

// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

// The items table
$items = get_items_table_data($proposal, 'proposal', 'pdf')
        ->set_headings('estimate');

$items_html = $items->table();

// Regular expression to match quantity cells with value 0 within TCPDF cell tags
$pattern = '/<td[^>]*>(0)<\/td>/i';  // Case-insensitive matching

// Replace matched cells with empty cells
$items_html = preg_replace($pattern, '<td></td>', $items_html);

$items_html .= '';
$items_html .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';
$items_html .= '
<tr id="technical_total">
    <td align="right" width="85%"><strong>' . _l('technical_items_total') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($proposal->technical_items_total, $proposal->currency_name) . '</td>
</tr>';
$items_html .= '
<tr>
    <td align="right" width="85%"><strong>' . _l('estimate_subtotal') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($proposal->subtotal, $proposal->currency_name) . '</td>
</tr>';

if (is_sale_discount_applied($proposal)) {
    $items_html .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('estimate_discount');
    if (is_sale_discount($proposal, 'percent')) {
        $items_html .= ' (' . app_format_number($proposal->discount_percent, true) . '%)';
    }
    $items_html .= '</strong>';
    $items_html .= '</td>';
    $items_html .= '<td align="right" width="15%">-' . app_format_money($proposal->discount_total, $proposal->currency_name) . '</td>
    </tr>';
}

if ((int)$proposal->adjustment != 0) {
    $items_html .= '<tr>
    <td align="right" width="85%"><strong>' . _l('estimate_adjustment') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($proposal->adjustment, $proposal->currency_name) . '</td>
</tr>';
}
$items_html .= '
<tr style="background-color:#f0f0f0;">
    <td align="right" width="85%"><strong>' . _l('estimate_total') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($proposal->total, $proposal->currency_name) . '</td>
</tr>';
$items_html .= '</table>';

if (get_option('total_to_words_enabled') == 1) {
    $items_html .= '<br /><br /><br />';
    $items_html .= '<strong style="text-align:center;">' . _l('num_word') . ': ' . $CI->numberword->convert($proposal->total, $proposal->currency_name) . '</strong>';
}

$proposal->content = str_replace('{proposal_items}', $items_html, $proposal->content);

// Get the proposals css
// Theese lines should aways at the end of the document left side. Dont indent these lines
$html = <<<EOF
$proposal_date
<br />
$open_till
$project
<div style="width:675px !important;">
$proposal->content
</div>
EOF;

$pdf->writeHTML($html, true, false, true, false, '');


$pdf->Ln(4);
$pdf->SetFont($font_name, 'B', 8);
$pdf->Cell(0, 0, _l('estimate_note'), 0, 1, 'L', 0, '', 0);
$pdf->SetFont($font_name, '', 8);
$pdf->setCellHeightRatio(1); //
$pdf->Ln(2);
$pdf->writeHTML("<div>Il prezzo fa riferimento al canone mensile IVA esclusa.
La data di consegna indicativa sarà comunicata al cliente dopo l'espletamento di tutte le pratiche connesse.</div>");


$pdf->Ln(4);
$pdf->SetFont($font_name, 'B', 8);
$pdf->Cell(0, 0, _l('terms_and_conditions') . ":", 0, 1, 'L', 0, '', 0);
$pdf->SetFont($font_name, '', 8);
$pdf->setCellHeightRatio(1); //
$pdf->Ln(2);
$pdf->writeHTML("<div>Il presente Pre-Ordine, una volta firmato per accettazione, sarà convertito in ordine salvo respingimento da parte di Leaseway Italia S.R.L. per mancata corretta ricezione della documentazione necessaria per l'espletamento pratiche.
Le condizioni particolari integrano le Condizioni Generali di Leaseway Italia S.R.L., che devono essere restituite firmate per avviare l'iter di consegna dei veicoli.
La richiesta di cancellazione a seguito dell'espletamento delle pratiche prevede delle sanzioni secondo i termini delle Condizioni di Cancellazione.</div>");