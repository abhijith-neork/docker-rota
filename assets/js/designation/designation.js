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
                                    //     this.api().columns(3).every(function () {
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
                                                    url: baseURL+"admin/Designation/finddesignation",
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
                                    $('#myTable tbody').on('click', 'tr.designation', function() {
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
    	  window.location = baseURL+"admin/designation/editdesignation/"+id;
     
    }
    
    function deleteFunction(id, designationname) {
        event.preventDefault(); // prevent form submit
        //var form = event.target.form; // storing the form
                swal({
          title: 'Are you sure you want to delete the designation "'+designationname+'"?', 
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
              window.location = baseURL+"admin/designation/deletedesignation/"+id
          } else {
              return false;
          }
        });
        }

    $('.sort_order').keyup(function(event) {  

        var id = $(this).data('id');  
        document.getElementById( 'sort_label_'+id ).style.display = 'block';
     });

    $(".sort_order").focusout(function(event){ 
         
      var id = $(this).data('id');  
      document.getElementById( 'sort_label_'+id ).style.display = 'none';
     
      var order = $(this).val();  

        if(order=='' || order<=0)
        {
          event.preventDefault();
          swal("Please enter valid sort order. ");
        }
        else if(order!='' && isNaN(parseInt(order)))
        {
          event.preventDefault();
          swal("Please enter valid sort order. ");
        }
        else
        {
           $.ajax({ 
                                                    type: "post",dataType: "json",
                                                    url: baseURL+"admin/Designation/add_sort_order",
                                                    data: {  order:order,id:id },
                                                    success: function(data) {  console.log(data);
                                                      swal('sort order updated successfully');   
                                                    }
                                              }); 
        }
                                       
    });