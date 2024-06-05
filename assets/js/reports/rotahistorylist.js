$(function() {
	var table;
    $(document).ready(function () {
	    var unit=$('#unit').val();  
	    var year=$('#year').val(); 
	    var month=$('#month').val(); 
	    var jobrole=$('#jobrole').val(); 
		table = $('#myTable').DataTable({
			"ordering":false,
		    "stateSave": true, 
		    "autoWidth": true,
		    "dom": 'lBfrtip', 
		    "buttons": ['copy', 'excel', 'pdf', 'print'],   
		    "processing": true,
		    "serverSide": true, 
		    "iDisplayLength": 25,
		    "aLengthMenu": [[25, 50, 75, 100, 0], [25, 50, 75, 100, "All"]],  
		    "ajax":{
				url: baseURL+'admin/Reports/rotahistoryreport',
				dataType: 'json',
				type: "POST", 
				data: function ( d ) {
					// Retrieve dynamic parameters
					$('#myTable').data('dt_params', { unit: $('#unit').val(), year: $('#year').val(),month: $('#month').val(),jobrole:$('#jobrole').val()});
					var dt_params = $('#myTable').data('dt_params');
					// Add dynamic parameters to the data object sent to the server
					if(dt_params){ $.extend(d, dt_params); }
				}
		    },
	        columns: 
        	[ 
				{ "data": "user_name" },
				{ "data": "type" },
				{ "data": "rota_details" },
				{ "data": "previous_history" },
				{ "data": "updated_by" },
				{ "data": "status" },
				{ "data": "updated_datetime" }, 
				
            ] 
	    });               
    });
    $(document).on('click', '.search', function(e) {
		var unit=$('#unit').val(); 
		var jobrole=$('#jobrole').val(); 
		var year=$('#year').val(); 
		var month=$('#month').val();
		table.clear(); 
		// table.data('dt_params', {unit:$('#unitdata').val(),jobrole:$('#jobrole').val()} );
		// $('#myTable').data('dt_params', { unit: unit, year: year,month: month,jobrole:jobrole});
		table.draw(); 
	});
});