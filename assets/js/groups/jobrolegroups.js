$(function() {
                                
                                    $(document).ready(function() {
                                        $('.status').select2();
                                    var table = $('#myTable').DataTable({
                                   
                                    "order": [
                                    [0, 'asc']
                                    ],
                                    "displayLength": 25,
                                    "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                    // initComplete: function () {
                                    //     this.api().columns(1).every(function () {
                                    //     var column = this;
                                    //     if (column.index() !== 0) 
                                    //     {  
                                    //        $(column.header()).append("<br>")
                                    //        var select = $('<select><option value=""></option></select>')
                                    //                            .appendTo($(column.header()))
                                    //                            .on('change', function () {
                                    //              var val = $.fn.dataTable.util.escapeRegex(
                                    //                  $(this).val()
                                    //              );
                                    //             column.search(val ? '^' + val + '$' : '', true, false).draw();
                                    //          });

                                    //        column.data().unique().sort().each(function (d, j) {
                                    //          select.append('<option value="' + d + '">' + d + '</option>')
                                    //       } );
                                    //     }  
                                 
                                    //     });
                                    //     } 
                                        
                                    });

                                    // Order by the grouping
                                    $('#myTable tbody').on('click', 'tr.group', function() {
                                    var currentOrder = table.order()[0];
                                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                                    table.order([2, 'desc']).draw();
                                    } else {
                                    table.order([2, 'asc']).draw();
                                    }
                                    });
                                    });
  });


    
    function deleteFunction(id, groupname) {
    	event.preventDefault(); // prevent form submit
    	//var form = event.target.form; // storing the form
    	        swal({
    	  title: 'Are you sure you want to delete the job role group "'+groupname+'"?', 
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
    	   // form.submit();          // submitting the form when user press yes
    		  window.location = baseURL+"admin/JobRoleGroup/deletejobrolegroup/"+id
    	  } else {
    		  return false;
     	  }
    	});
    	}