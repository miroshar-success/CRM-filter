(function($) {
"use strict";

$('#report_months').on('change', function() {
	 var val = $(this).val();
	 var report_from = $('#report_from');
	 var report_to = $('#report_to');
	 var date_range = $('#date-range');
	 
	 report_to.val('');
	 report_from.val('');
	 if (val == 'custom') {
		date_range.addClass('fadeIn').removeClass('hide');
		return;
	} else {
		if (!date_range.hasClass('hide')) {
			date_range.removeClass('fadeIn').addClass('hide');
		}
		$('.table-si-leads').DataTable().ajax.reload();
	}
	if(val!='')
		$("#date_by_wrapper").removeClass('hide');
	else
		$("#date_by_wrapper").addClass('hide');	
});
$('input[name="report_from"]').on('change',function(){
	if($('input[name="report_to"]').val() !=''){
		$('.table-si-leads').DataTable().ajax.reload();
		return false;
	}	
});
$('#si_lf_save_filter').on('click',function(){
	var checked = this.checked;
	$('#si_lf_filter_name').attr('disabled',!checked);
});
$('#si_form_lead_filter').on('submit',function(){
	if($('#si_lf_save_filter').is(":checked") && $('#si_lf_filter_name').val()=='')
	{
		$('#si_lf_filter_name').focus();
		return false;
	}
});
$(document).ready(function() {
	var table = $('.dt-table').DataTable();
	var hide_view = [];
	$('.dt-table thead tr th').each(function(i,a) { 
		if( $(this).hasClass('not-export'))
			hide_view.push($(this).index());	
	});
	table.button().add( 0, 'colvis' );
	table.columns( hide_view ).visible( false );
	$('.buttons-colvis').addClass('btn-sm');//for Perfex version 3.0
});
$(".buttons-colvis").text("Columns");
})(jQuery);	