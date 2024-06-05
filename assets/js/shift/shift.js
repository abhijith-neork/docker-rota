$(function() {
                                
                                    $(document).ready(function() {
                                       $('.status').select2();
                                    var table = $('#myTable').DataTable({
                                   
                                    "order": [
                                    [0, 'asc']
                                    ],
                                    "displayLength": 25,
                                    "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                     initComplete: function() {
                                    this.api().columns('8').every(function() {
                                      var column = this;  
                                      if (column.index() !== 0) 
                                        {  
                                           $(column.header()).append("<br>");   
                                      //added class "mymsel"           
                                      var select = $('<select class="mymsel" multiple="multiple"><option value=""></option></select>')
                                        .appendTo($(column.header()))
                                        .on('change', function() {  
                                          var vals = $('option:selected', this).map(function(index, element) {
                                            return $.fn.dataTable.util.escapeRegex($(element).val());
                                          }).toArray().join('|');

                                          column
                                            .search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false)
                                            .draw();
                                        });

                                      column.data().unique().sort().each(function(d, j) {
                                        select.append('<option style="width:120px;height:2px;" value="' + d + '">' + d + '</option>')
                                      });
                                    }
                                    });
                                    //select2 init for .mymsel class
                                    $(".mymsel").select2();
                                  } 

                                    // initComplete: function () {
                                    //   this.api().columns('8').every(function () {
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
                                    $("#status").on('change', function() {
                                       var status = $(this).val();
                                       $.ajax({ 
                                                    type: "post",dataType: "json",
                                                    url: baseURL+"admin/Shift/findshift",
                                                    data: {  status:status },
                                                    success: function(data) {  
                                                        table.clear(); 
                                                        table.rows.add(data).draw(); 
                                                    } 
                                              });  
                                              $('#myInput').on( 'keyup', function () {
                                              table.search( this.value ).draw();
                                          } );
                                           

                                     });
                                    // Order by the grouping
                                    $('#myTable tbody').on('click', 'tr.unit', function() {
                                    var currentOrder = table.order()[0];
                                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                                    table.order([2, 'desc']).draw();
                                    } else {
                                    table.order([2, 'asc']).draw();
                                    }
                                    });
                                    });
  });
    function editFunction(id) {
    	event.preventDefault(); // prevent form submit
    	  window.location = baseURL+"admin/shift/editshift/"+id;
     
    }
     $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    }); 
    
    function deleteFunction(id, shiftname) {
    	event.preventDefault(); // prevent form submit
    	//var form = event.target.form; // storing the form
    	        swal({
    	  title: 'Are you sure you want to delete the shift "'+shiftname+'"?', 
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
    		  window.location = baseURL+"admin/shift/deleteshift/"+id
    	  } else {
    		  return false;
     	  }
    	});
    	}
 

