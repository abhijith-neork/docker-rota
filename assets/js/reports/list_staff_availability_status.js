$(function() {                                
	$(document).ready(function() {
	  	var table = $('#myTable').DataTable({
			"displayLength": 25,
			"bFilter" : false
	  	});
	  	$(document).on('click', '.back', function(event) {
	  	  window.location=baseURL+'admin/reports/staffavailabilityreport';
	  	});
	  	$("#date_filter").datepicker({
	  	  dateFormat: 'dd/mm/yy'
	  	});
	  	$("#date_filter").on("change", function () {
	  		$("#frmlistlivestatus").submit();
	  	});
  	});
});