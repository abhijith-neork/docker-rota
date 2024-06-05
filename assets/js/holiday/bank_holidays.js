$(document).ready(function() { 
	$("#from-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 
                                    
	var table = $('#myTable').DataTable({
                                   
                                    "order": [
                                    [0, 'asc']
                                    ],
                                    "displayLength": 25,
                                    "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                    });
});
function deleteFunction(id) {
	event.preventDefault();
	swal({
		title: 'Are you sure you want to delete holiday?', 
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "Yes, delete it!",
		cancelButtonText: "No, cancel please!",
		closeOnConfirm: true,
		closeOnCancel: true
	},
	function(isConfirm){
		if (isConfirm) {
			$.ajax({
				url:baseURL+'admin/Bankholiday/deleteHoliday',
				type:'post',
				async: false,
				dataType:'json',
				delay: 250,
				data: {
					id : id
				},
				success: function (data) {
					if(data.status == 1){
						window.location.reload();
					}
				}
			});
		}
	});
}
