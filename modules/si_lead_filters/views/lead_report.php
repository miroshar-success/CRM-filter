<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
$report_heading = '';
$perfex_version = (int)$this->app->get_current_db_version();
$base_currency = $this->currencies_model->get_base_currency();
?>
<link href="<?php echo module_dir_url('si_lead_filters', 'assets/css/si_lead_filters_style.css'); ?>" rel="stylesheet" />
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<?php echo form_open($this->uri->uri_string() . ($this->input->get('filter_id') ? '?filter_id=' . $this->input->get('filter_id') : ''), "id=si_form_lead_filter"); ?>
						<h4 class="pull-left"><?php echo _l('si_lf_submenu_lead_filters'); ?> <small class="text-success"><?php echo htmlspecialchars($saved_filter_name); ?></small></h4>
						<div class="btn-group pull-right mleft4 btn-with-tooltip-group" data-toggle="tooltip" data-title="<?php echo _l('si_lf_filter_templates'); ?>" data-original-title="" title="">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-list"></i>
							</button>
							<ul class="row dropdown-menu notifications width400">
								<?php
								if (!empty($filter_templates)) {
									foreach ($filter_templates as $row) {
										echo "<li><a href='leads_filter?filter_id=$row[id]'>$row[filter_name]</a></li>";
									}
								} else
									echo '<li><a >' . _l('si_lf_no_filter_template') . '</a></li>';
								?>
							</ul>
						</div>
						<!-- <button type="submit" data-toggle="tooltip" data-title="<?php echo _l('si_lf_apply_filter'); ?>" class=" pull-right btn btn-info mleft4"><?php echo _l('filter'); ?></button> -->
						<a href="#" onclick="init_lead(); return false;" class="btn btn-primary mleft4 pull-right display-block">
							<i class="fa-regular fa-plus tw-mr-1"></i>
							<?php echo _l('new_lead'); ?>
						</a>
						<!-- <a href="leads_filter" class=" pull-right btn btn-info mleft4"><?php echo _l('si_lf_new'); ?></a> -->
						<!--lead summaray-->
						<a href="#" class="pull-right btn btn-default btn-with-tooltip" data-toggle="tooltip" data-title="<?php echo _l('leads_summary'); ?>" data-placement="bottom" onclick="slideToggle('.si-leads-overview'); return false;"><i class="fa fa-bar-chart"></i></a>
						<div class="clearfix"></div>
						<div class="row hide si-leads-overview">
							<hr />
							<div class="col-md-12">
								<h4 class="no-margin"><?php echo _l('leads_summary'); ?></h4>
							</div>
							<?php
							foreach ($summary as $status) { ?>
								<div class="col-md-2 col-xs-6 border-right">
									<h3 class="bold">
										<?php
										if (isset($status['percent'])) {
											echo $status['total'] . " <small>(" . $status['percent'] . "%)</small>";
										} else {
											// Is regular status
											echo $status['total'];
										}
										?>
									</h3>
									<span style="color:<?php echo $status['color']; ?>" class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>"><?php echo $status['name']; ?></span>
								</div>
							<?php } ?>
						</div>
						<!--end lead summaray-->
						<div class="clearfix"></div>
						<hr />
						<div class="_filters _hidden_inputs">
							<div class="row mbot15">
								<?php if (has_permission('leads', '', 'view')) { ?>
									<div class="col-md-2 border-right">
										<label for="rel_type" class="control-label"><?php echo _l('staff_members'); ?></label>
										<?php echo render_select('member', $members, array('staffid', array('firstname', 'lastname')), '', $staff_id, array('data-none-selected-text' => _l('all_staff_members'), 'onchange' => "dt_custom_view(this.value,'.table-si-leads','member'); return false;"), array(), 'no-margin'); ?>
									</div>
								<?php } ?>
								<div class="col-md-2 text-center1 border-right">
									<label for="status" class="control-label"><?php echo _l('lead_status'); ?></label>
									<?php
									echo render_select('status[]', $lead_statuses, array('id', 'name'), '', $statuses, array('data-width' => '100%', 'data-none-selected-text' => _l('leads_all'), 'multiple' => true, 'data-actions-box' => true, 'onchange' => "dt_custom_view(this.value,'.table-si-leads','status'); return false;"), array(), 'no-mbot', '', false); ?>

								</div>
								<!--start sources select -->
								<div class="col-md-2  border-right">
									<label for="rel_type" class="control-label"><?php echo _l('lead_source'); ?></label>
									<?php
									echo render_select('source[]', $lead_sources, array('id', 'name'), '', $sources, array('data-width' => '100%', 'data-none-selected-text' => _l('leads_all'), 'multiple' => true, 'data-actions-box' => true, 'onchange' => "dt_custom_view(this.value,'.table-si-leads','source'); return false;"), array(), 'no-mbot', '', false); ?>
								</div>
								<!--end sources select-->
								<!--start country select -->
								<div class="col-md-2  border-right">
									<label for="rel_type" class="control-label"><?php echo _l('lead_country'); ?></label>
									<?php
									$lead_countries[] = array('id' => -1, 'name' => _l('si_lf_unknown'));
									echo render_select('countries[]', $lead_countries, array('id', 'name'), '', $countries, array('data-width' => '100%', 'data-none-selected-text' => _l('leads_all'), 'multiple' => true, 'data-actions-box' => true, 'onchange' => "dt_custom_view(this.value,'.table-si-leads','countries'); return false;"), array(), 'no-mbot', '', false); ?>
								</div>
								<!--end counry select-->
								<!--start states -->
								<div class="col-md-2 border-right">
									<label for="states" class="control-label"><?php echo _l('lead_state'); ?></label>
									<?php
									$lead_states[] = array('id' => -1, 'name' => _l('si_lf_unknown'));
									echo render_select('states[]', $lead_states, array('id', 'name'), '', $states, array('data-width' => '100%', 'data-none-selected-text' => _l('leads_all'), 'multiple' => true, 'data-actions-box' => true, 'onchange' => "dt_custom_view(this.value,'.table-si-leads','states'); return false;"), array(), 'no-mbot', '', false); ?>
								</div>
								<!--end states-->
								<!--start city -->
								<div class="col-md-2 border-right">
									<label for="cities" class="control-label"><?php echo _l('lead_city'); ?></label>
									<?php
									$lead_cities[] = array('id' => -1, 'name' => _l('si_lf_unknown'));
									echo render_select('cities[]', $lead_cities, array('id', 'name'), '', $cities, array('data-width' => '100%', 'data-none-selected-text' => _l('leads_all'), 'multiple' => true, 'data-actions-box' => true, 'onchange' => "dt_custom_view(this.value,'.table-si-leads','cities'); return false;"), array(), 'no-mbot', '', false); ?>
								</div>
								<!--end city-->
							</div>
							<div class="row">
								<!--start zip -->
								<div class="col-md-2 border-right">
									<label for="zips" class="control-label"><?php echo _l('lead_zip'); ?></label>
									<?php
									$lead_zips[] = array('id' => -1, 'name' => _l('si_lf_unknown'));
									echo render_select('zips[]', $lead_zips, array('id', 'name'), '', $zips, array('data-width' => '100%', 'data-none-selected-text' => _l('leads_all'), 'multiple' => true, 'data-actions-box' => true, 'onchange' => "dt_custom_view(this.value,'.table-si-leads','zips'); return false;"), array(), 'no-mbot', '', false); ?>
								</div>
								<!--end zip-->
								<!--start tags -->
								<div class="col-md-2 border-right">
									<label for="rel_type" class="control-label"><?php echo _l('tags'); ?></label>
									<?php
									echo render_select('tags[]', get_tags(), array('id', 'name'), '', $tags, array('data-width' => '100%', 'data-none-selected-text' => _l('leads_all'), 'multiple' => true, 'data-actions-box' => true, 'onchange' => "dt_custom_view(this.value,'.table-si-leads','tags'); return false;"), array(), 'no-mbot', '', false); ?>
								</div>
								<!--end tags-->
								<!--start other_type select -->
								<div class="col-md-2 border-right form-group">
									<label for="date_by" class="control-label"><span class="control-label"><?php echo _l('si_lf_filter_by_type'); ?></span></label>
									<select name="type" id="type" class="selectpicker no-margin" data-width="100%" onchange="dt_custom_view(this.value,'.table-si-leads','type'); return false;">
										<option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
										<option value="lost" <?php echo ($type == 'lost' ? 'selected' : '') ?>><?php echo _l('lead_lost'); ?></option>
										<option value="junk" <?php echo ($type == 'junk' ? 'selected' : '') ?>><?php echo _l('lead_junk'); ?></option>
										<option value="public" <?php echo ($type == 'public' ? 'selected' : '') ?>><?php echo _l('lead_public'); ?></option>
										<option value="not_assigned" <?php echo ($type == 'not_assigned' ? 'selected' : '') ?>><?php echo _l('leads_not_assigned'); ?></option>
									</select>
								</div>
								<!--end other_type select-->
								<!--start filter_by select -->
								<div class="col-md-2 border-right form-group">
									<label for="date_by" class="control-label"><span class="control-label"><?php echo _l('si_lf_lead_filter_by_date'); ?></span></label>
									<select name="date_by" id="date_by" class="selectpicker no-margin" data-width="100%">
										<option value="dateadded"><?php echo _l('si_lf_created_date'); ?></option>
										<option value="lastcontact" <?php echo ($date_by != '' && $date_by == 'lastcontact' ? 'selected' : '') ?>><?php echo _l('si_lf_last_contacted_date'); ?></option>
									</select>
								</div>
								<!--end filter_by select-->
								<div class="col-md-2 form-group border-right" id="report-time">
									<label for="months-report"><?php echo _l('period_datepicker'); ?></label><br />
									<select class="selectpicker" name="report_months" id="report_months" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
										<option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
										<option value="today"><?php echo _l('today'); ?></option>
										<option value="this_week"><?php echo _l('this_week'); ?></option>
										<option value="last_week"><?php echo _l('last_week'); ?></option>
										<!-- <option value="this_month"><?php echo _l('this_month'); ?></option> -->
										<option value="1"><?php echo _l('last_month'); ?></option>
										<option value="this_year"><?php echo _l('this_year'); ?></option>
										<option value="last_year"><?php echo _l('last_year'); ?></option>
										<option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
										<option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
										<option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
										<option value="custom"><?php echo _l('period_datepicker'); ?></option>
									</select>
									<?php
									if ($report_months !== '') {
										$report_heading .= ' for ' . _l('period_datepicker') . " ";
										switch ($report_months) {
											case 'today':
												$report_heading .= _d(date('d-m-Y')) . " To " . _d(date('d-m-Y'));
												break;
											case 'this_week':
												$report_heading .= _d(date('d-m-Y', strtotime('monday this week'))) . " To " . _d(date('d-m-Y', strtotime('sunday this week')));
												break;
											case 'last_week':
												$report_heading .= _d(date('d-m-Y', strtotime('monday last week'))) . " To " . _d(date('d-m-Y', strtotime('sunday last week')));
												break;
												// case 'this_month':$report_heading.=_d(date('01-m-Y'))." To "._d(date('t-m-Y'));break;
											case '1':
												$report_heading .= _d(date('01-m-Y', strtotime('-1 month'))) . " To " . _d(date('t-m-Y', strtotime('-1 month')));
												break;
											case 'this_year':
												$report_heading .= _d(date('01-01-Y')) . " To " . _d(date('31-12-Y'));
												break;
											case 'last_year':
												$report_heading .= _d(date('01-01-Y', strtotime('-1 year'))) . " To " . _d(date('31-12-Y', strtotime('-1 year')));
												break;
											case '3':
												$report_heading .= _d(date('01-m-Y', strtotime('-2 month'))) . " To " . _d(date('t-m-Y'));
												break;
											case '6':
												$report_heading .= _d(date('01-m-Y', strtotime('-5 month'))) . " To " . _d(date('t-m-Y'));
												break;
											case '12':
												$report_heading .= _d(date('01-m-Y', strtotime('-11 month'))) . " To " . _d(date('t-m-Y'));
												break;
											case 'custom':
												$report_heading .= $report_from . " To " . $report_to;
												break;
											default:
												$report_heading .= 'All Time';
										}
									}
									?>
								</div>
								<div id="date-range" class="col-md-4 <?php echo ($report_months != 'custom' ? 'hide' : '') ?> mbot15" id="date_by_wrapper">
									<div class="row">
										<div class="col-md-6">
											<label for="report_from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
											<div class="input-group date">
												<input type="text" class="form-control datepicker" id="report_from" name="report_from" value="<?php echo htmlspecialchars($report_from); ?>" autocomplete="off">
												<div class="input-group-addon">
													<i class="fa fa-calendar calendar-icon"></i>
												</div>
											</div>
										</div>
										<div class="col-md-6 border-right">
											<label for="report_to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
											<div class="input-group date">
												<input type="text" class="form-control datepicker" id="report_to" name="report_to" autocomplete="off" onchange="dt_custom_view(this.value,'.table-si-leads','report_to'); return false;">
												<div class="input-group-addon">
													<i class="fa fa-calendar calendar-icon"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--end date time div-->
								<!--added for custom field-->
								<?php if (!empty($list_custom_field)) {
									$cfs = get_custom_fields('leads', "id in (" . implode(',', $list_custom_field) . ")");
									foreach ($cfs as $cf) { ?>
										<div class="col-md-2 border-right mbot15">
											<label class="control-label"><?php echo $cf['name']; ?></label>
											<?php
											echo render_select("cf[$cf[id]][]", si_lf_get_custom_field_values($cf['id']), array('value', 'value'), '', (isset($selected_custom_fields[$cf['id']]) ? $selected_custom_fields[$cf['id']] : ''), array('data-width' => '100%', 'data-none-selected-text' => _l('leads_all'), 'multiple' => true, 'data-actions-box' => true, 'onchange' => "dt_custom_view(this.value,'.table-si-leads','cf'); return false;"), array(), 'no-mbot', '', false); ?>
										</div>
								<?php }
								}
								?>
								<!--ended for custom field-->
								<!--start hide_export_columns select -->
								<div class="col-md-2 border-right form-group">
									<label for="hide_columns" class="control-label">
										<i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('si_lf_hide_export_columns_info'); ?>"></i>
										<span class="control-label"><?php echo _l('si_lf_hide_export_columns'); ?></span>
									</label>
									<select name="hide_columns[]" id="hide_columns" class="selectpicker no-margin" data-width="100%" multiple>
										<option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
										<option value="name" <?php echo (in_array('name', $hide_columns) ? 'selected' : '') ?>><?php echo _l('leads_dt_name'); ?></option>
										<option value="company" <?php echo (in_array('company', $hide_columns) ? 'selected' : '') ?>><?php echo _l('lead_company'); ?></option>
										<option value="email" <?php echo (in_array('email', $hide_columns) ? 'selected' : '') ?>><?php echo _l('leads_dt_email'); ?></option>
										<option value="phonenumber" <?php echo (in_array('phonenumber', $hide_columns) ? 'selected' : '') ?>><?php echo _l('leads_dt_phonenumber'); ?></option>
										<option value="city" <?php echo (in_array('city', $hide_columns) ? 'selected' : '') ?>><?php echo _l('lead_city'); ?></option>
										<option value="state" <?php echo (in_array('state', $hide_columns) ? 'selected' : '') ?>><?php echo _l('lead_state'); ?></option>
										<option value="country" <?php echo (in_array('country', $hide_columns) ? 'selected' : '') ?>><?php echo _l('lead_country'); ?></option>
										<option value="zip" <?php echo (in_array('zip', $hide_columns) ? 'selected' : '') ?>><?php echo _l('lead_zip'); ?></option>
										<?php if ($perfex_version >= 250) { ?>
											<option value="lead_value" <?php echo (in_array('lead_value', $hide_columns) ? 'selected' : '') ?>><?php echo _l('lead_value'); ?></option>
										<?php } ?>

										<?php
										$custom_fields = get_custom_fields('leads', ['show_on_table' => 1,]);
										foreach ($custom_fields as $field)
											echo "<option value='$field[slug]' " . (in_array($field['slug'], $hide_columns) ? 'selected' : '') . ">$field[name]</option>";
										?>
										<option value="status" <?php echo (in_array('status', $hide_columns) ? 'selected' : '') ?>><?php echo _l('leads_dt_status'); ?></option>
										<option value="source" <?php echo (in_array('source', $hide_columns) ? 'selected' : '') ?>><?php echo _l('lead_add_edit_source'); ?></option>
										<option value="dateadded" <?php echo (in_array('dateadded', $hide_columns) ? 'selected' : '') ?>><?php echo _l('si_lf_created_date'); ?></option>
										<option value="lastcontact" <?php echo (in_array('lastcontact', $hide_columns) ? 'selected' : '') ?>><?php echo _l('si_lf_last_contacted_date'); ?></option>
										<option value="is_public" <?php echo (in_array('is_public', $hide_columns) ? 'selected' : '') ?>><?php echo _l('lead_public'); ?></option>
										<option value="assigned" <?php echo (in_array('assigned', $hide_columns) ? 'selected' : '') ?>><?php echo _l('leads_dt_assigned'); ?></option>
										<option value="tags" <?php echo (in_array('tags', $hide_columns) ? 'selected' : '') ?>><?php echo _l('tags'); ?></option>
									</select>
								</div>
								<!--end hide_export_columns select-->
								<div class="col-md-6">
									<div class="checklist relative">
										<div class="checkbox checkbox-success checklist-checkbox" data-toggle="tooltip" title="" data-original-title="<?php echo _l('si_lf_save_filter_template'); ?>">
											<input type="checkbox" id="si_lf_save_filter" name="save_filter" value="1" title="<?php echo _l('si_lf_save_filter_template'); ?>" <?php echo ($this->input->get('filter_id') ? 'checked' : '') ?>>
											<label for=""><span class="hide"><?php echo _l('si_lf_save_filter_template'); ?></span></label>
											<textarea id="si_lf_filter_name" name="filter_name" rows="1" placeholder="<?php echo _l('si_lf_filter_template_name'); ?>" <?php echo ($this->input->get('filter_id') ? '' : 'disabled="disabled"') ?> maxlength='100'><?php echo ($this->input->get('filter_id') ? $saved_filter_name : ''); ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
				<div class="panel_s">
					<div class="panel-body">
						<div class="panel-table-full">
							<?php $this->load->view('table_html', ['bulk_actions' => false, 'hide_columns' => $hide_columns, 'perfex_version' => $perfex_version]); ?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
</body>

</html>
<script src="<?php echo module_dir_url('si_lead_filters', 'assets/js/si_lead_filters_lead_report.js'); ?>"></script>
<script>
	(function($) {
		"use strict";
		<?php if ($report_months !== '') { ?>
			$('#report_months').val("<?php echo htmlspecialchars($report_months); ?>");
		<?php }
		if ($report_from !== '') {
		?>
			$('#report_from').val("<?php echo htmlspecialchars($report_from); ?>");
		<?php
		}
		if ($report_to !== '') {
		?>
			$('#report_to').val("<?php echo htmlspecialchars($report_to); ?>");
		<?php
		}
		?>
		var SiLeadsServerParams = {};
		$.each($('._hidden_inputs._filters input,._hidden_inputs._filters select'), function() {
			SiLeadsServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
		});
		console.log('123');
		initDataTable('.table-si-leads', admin_url + 'si_lead_filters/table', undefined, undefined,
			SiLeadsServerParams, <?php echo hooks()->apply_filters('si_leads_table_default_order', json_encode(array(1, 'desc'))); ?>);
	})(jQuery);
	//update lead status
	function si_leads_status_update(status, lead_id) {
		lead_mark_as(status, lead_id);
		setTimeout(function() {
			$.get(admin_url + 'si_lead_filters/get_lead_status/' + lead_id, function(response) {
				response = JSON.parse(response);
				if (response.success == true && response.leadHtml != 'undefined') {
					$('#si-tbl-id-' + lead_id).html(response.leadHtml);
				}
			});
		}, 300);
	}
</script>
