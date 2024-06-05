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
                                     dom: 'lBfrtip',
                                    buttons: [
                                    'copy', 'excel', 'pdf', 'print'
                                    ],
                                    "displayLength": 25,
                                    "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                    // initComplete: function () {
                                    //     this.api().columns(2).every(function () {
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
                                        "ordering": true,
                                        "displayLength": 25,
                                        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                        processing: true,
                                        language: {
                                          processing: '<div class="spinner-border text-primary" role="status"></div>'
                                        },
                                        serverSide: true,
                                        ajax: {
                                          url: baseURL + 'admin/Reports/absencereportData',
                                          dataType: 'json',
                                          type: "POST",
                                          data: function (d) {
                                              // Include custom filters in the Ajax request data
                                              d.start_date = $('#from-datepicker').val();
                                              d.unitdata = $('#unitdata').val();
                                              d.shift = $('#shift').val();
                                              d.jobrole = $('#jobrole').val();
                                              d.status = $('#status').val();
                                          },
                                        },
                                        columns: [
                                          { "data": "user_id" },
                                          { "data": "fname" },
                                          { "data": "mobile_number" },
                                          { "data": "date" },
                                        ]
                                         
                                      });
                                      $(document).on('click', '.search', function(e) {  
                                        var unitdata = $("#unitdata").val(); 
                                        var shift = $("#shift").val(); 
                                        var status = $("#status").val();
                                        // if(unitdata=='' && shift=='')
                                        // { 
                                        //  alert("Please select unit and shift values");
            
                                        // }
                                        // else 
                                        if(unitdata=='')
                                        {
                                          swal("Please select unit");
                                          event.preventDefault();
                                        }
                                        else if($('#from-datepicker').val() ==""){
                                          swal("Please select date");
                                        }
                                        else{
                                         table.draw();
                                        }
                                        // else if(shift=='')
                                        // {
                                        //   alert("Please select shift");
                                        // }
                                         
                                       });
});

                                    