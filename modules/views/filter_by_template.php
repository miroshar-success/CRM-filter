<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($filter_table_name)){
    $filter_table_name = '.table-call_logs';
}
?>
<div class="_filters _hidden_inputs hidden">
   <?php echo form_hidden('billable');
   echo form_hidden('non-billable');
   echo form_hidden('invoiced');
   echo form_hidden('unbilled');
   echo form_hidden('recurring');
   foreach($years as $year){
    echo form_hidden('year_'.$year['year'],$year['year']);
}
for ($m = 1; $m <= 12; $m++) {
   echo form_hidden('expenses_by_month_'.$m);
}
foreach($categories as $category){
 echo form_hidden('expenses_by_category_'.$category['id']);
}
?>
</div>
<div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-right width300">
        <li>
            <a href="#" data-cview="all" onclick="dt_custom_view('','.table-expenses',''); return false;">
                All            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#" data-cview="billable" onclick="dt_custom_view('billable','.table-expenses','billable'); return false;">
                Billable            </a>
        </li>
        <li>
            <a href="#" data-cview="non-billable" onclick="dt_custom_view('non-billable','.table-expenses','non-billable'); return false;">
                Non Billable            </a>
        </li>
        <li>
            <a href="#" data-cview="invoiced" onclick="dt_custom_view('invoiced','.table-expenses','invoiced'); return false;">
                Invoiced            </a>
        </li>
        <li>
            <a href="#" data-cview="unbilled" onclick="dt_custom_view('unbilled','.table-expenses','unbilled'); return false;">
                Not Invoiced            </a>
        </li>
        <li>
            <a href="#" data-cview="recurring" onclick="dt_custom_view('recurring','.table-expenses','recurring'); return false;">
                Recurring            </a>
        </li>
        <li class="divider years-divider"></li>
        <li class="active expenses-filter-year">
            <a href="#" data-cview="year_2020" onclick="dt_custom_view(2020,'.table-expenses','year_2020'); return false;">2020</a>
        </li>
        <div class="clearfix"></div>
        <li class="divider"></li>
        <li class="dropdown-submenu pull-left">
            <a href="#" tabindex="-1">By Categories</a>
            <ul class="dropdown-menu dropdown-menu-left">
                <li>
                    <a href="#" data-cview="expenses_by_category_2" onclick="dt_custom_view(2,'.table-expenses','expenses_by_category_2'); return false;">111</a>
                </li>
                <li>
                    <a href="#" data-cview="expenses_by_category_1" onclick="dt_custom_view(1,'.table-expenses','expenses_by_category_1'); return false;">abc</a>
                </li>
            </ul>
        </li>
        <div class="clearfix"></div>
        <li class="divider months-divider"></li>
        <li class="dropdown-submenu pull-left expenses-filter-month-wrapper">
            <a href="#" tabindex="-1">Months</a>
            <ul class="dropdown-menu dropdown-menu-left">
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_1" onclick="dt_custom_view(1,'.table-expenses','expenses_by_month_1'); return false;">January</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_2" onclick="dt_custom_view(2,'.table-expenses','expenses_by_month_2'); return false;">February</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_3" onclick="dt_custom_view(3,'.table-expenses','expenses_by_month_3'); return false;">March</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_4" onclick="dt_custom_view(4,'.table-expenses','expenses_by_month_4'); return false;">April</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_5" onclick="dt_custom_view(5,'.table-expenses','expenses_by_month_5'); return false;">May</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_6" onclick="dt_custom_view(6,'.table-expenses','expenses_by_month_6'); return false;">June</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_7" onclick="dt_custom_view(7,'.table-expenses','expenses_by_month_7'); return false;">July</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_8" onclick="dt_custom_view(8,'.table-expenses','expenses_by_month_8'); return false;">August</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_9" onclick="dt_custom_view(9,'.table-expenses','expenses_by_month_9'); return false;">September</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_10" onclick="dt_custom_view(10,'.table-expenses','expenses_by_month_10'); return false;">October</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_11" onclick="dt_custom_view(11,'.table-expenses','expenses_by_month_11'); return false;">November</a></li>
                <li class="expenses-filter-month"><a href="#" data-cview="expenses_by_month_12" onclick="dt_custom_view(12,'.table-expenses','expenses_by_month_12'); return false;">December</a></li>
            </ul>
        </li>
    </ul>
          </div>
