$(function() {
  $("#unit_change_date").datepicker({
     minDate: 0,
    dateFormat: 'dd/mm/yy'
  });

                                    $(document).ready(function() {
                                       $('.status').select2();

                                    $("#from-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 
                                     $("#start-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#start-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });
                                     $("#end-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#end-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });


                                    var table = $('#myTable').DataTable({
                                     
                                    //  "columnDefs": [
                                    //   { "targets": 8, "type": 'date' }
                                    // ],
                                    "ordering": true, 
                                    "order": [
                                    [0, 'asc']
                                    ],
                                   "displayLength": 25,
                                    "lengthMenu": [[ 25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                    initComplete: function() {
                                    this.api().columns([5,6]).every(function() {
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
                                    //     this.api().columns([4,6,7]).every(function () {
                                    //     var column = this;
                                    //     if (column.index() !== 0) 
                                    //     {  
                                    //       console.log(column.index() );
                                    //        $(column.header()).append("<br>")
                                    //        var select = $('<select><option value=""></option></select>')
                                    //                            .appendTo($(column.header()))
                                    //                            .on('change', function () {
                                    //              var val = $.fn.dataTable.util.escapeRegex(
                                    //                  $(this).val()
                                    //              );
                                    //              console.log(val+"DDD");
                                    //             // if(val!='')
                                    //                 column.search(val ? '^' + val + '$' : '', true, false).draw();
                                    //              /* else
                                    //                 column.search( ).draw();*/

                                    //          }); 
                                    //                           // console.log(column.data());

                                    //        column.data().unique().sort().each(function (d, j) {
                                    //         if(d!=''){
                                    //          select.append('<option value="' + d + '">' + d + '</option>')
                                    //         }
                                    //       } );
                                    //     }  
                                 
                                    //     });
                                    //     } 
                                        
                                    });

                                    $("#unit").on('change', function() {
                                       var unit = $(this).val();  
                                       var status = $('#status').val();  
                                       var visa_status = $('#visastatus').val(); 
                                       $.ajax({ 
                                                    type: "post",dataType: "json",
                                                    url: baseURL+"admin/User/finduserbyunit",
                                                    data: {  unit:unit,status:status },
                                                    success: function(data) {  console.log(data);
                                                        table.clear();  console.log(table);
                                                        table.rows.add(data).draw(); 
                                                    } 
                                              });  
                                              $('#myInput').on( 'keyup', function () {
                                              table.search( this.value ).draw();
                                          } );
                                           

                                     });


                                    $("#status").on('change', function() {
                                       var status = $(this).val();  
                                       var unit = $('#unit').val(); 
                                       var visa_status = $('#visastatus').val(); 
                                       $.ajax({ 
                                                    type: "post",dataType: "json",
                                                    url: baseURL+"admin/User/finduserbyunit",
                                                    data: {  status:status ,unit:unit},
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
                                    $('#myTable tbody').on('click', 'tr.user', function() {
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
        window.location = baseURL+"admin/user/edituser/"+id;
     
    }
    
    function deleteFunction(id, fname) {
      event.preventDefault(); // prevent form submit
      //var form = event.target.form; // storing the form
              swal({
        title: 'Are you sure you want to delete the user "'+fname+'"?', 
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
          window.location = baseURL+"admin/user/deleteuser/"+id
        } else {
          return false;
        }
      });
      }

//       $(".ethnicity").change(function () {
//                 ethnicity = $('#ethnicity option:selected').val(); 
//                     $.ajax({
//                                 type :'POST',
//                                 dataType:'json',
//                                 data : { id : ethnicity },
//                                 url: baseURL+"manager/Profile/findethnicity",
//                                 success : function(result){  console.log(result);
//                                     if(result.other_status==1)
//                                     {
//                                         document.getElementById("newethnicity").readOnly = false; 
//                                          document.getElementById('other').innerHTML = result['Ethnic_group'];
//                                         $("#other").show();
//                                         $('#newethnicity').val('');
//                                     }
//                                     else
//                                     {
//                                         $('#newethnicity').val(result['Ethnic_group']);
//                                          $("#other").hide();
//                                         document.getElementById("newethnicity").readOnly = true;
//                                     }
                                    
//                                 }
//                             }); 
// });

    // function sendFunction(id, fname) {
    //   event.preventDefault(); // prevent form submit
    //   //var form = event.target.form; // storing the form
    //           swal({
    //     title: 'Are you sure you want to send a password reset link to the user "'+fname+'"?', 
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#DD6B55",
    //     confirmButtonText: "Yes, send it!",
    //     cancelButtonText: "No, cancel please!",
    //     closeOnConfirm: true,
    //     closeOnCancel: true
    //   },
    //   function(isConfirm){
    //     if (isConfirm) {
    //      // form.submit();          // submitting the form when user press yes
    //       window.location = baseURL+"admin/user/sendlink/"+id; 
    //     } else {
    //       return false;
    //     }
    //   });
    //   }

      function sendFunction(id, fname) {
      event.preventDefault(); // prevent form submit
      //var form = event.target.form; // storing the form
              swal({
        title: 'Are you sure you want to send a password reset link to the user "'+fname+'"?', 
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, send it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: true,
        closeOnCancel: true
      },

       function (isConfirm) {
      if (isConfirm) {
        $.ajax({
          type: 'POST',
          url: baseURL+"admin/user/sendlink",
          data: { id : id },
          success: function (data) {
            alert(data);
          }
        });
      } 
      else 
      {
        
      }
    });
   return false;
  }


    function changePassword(id) {
        event.preventDefault(); // prevent form submit
        var redirectWindow = window.open(baseURL+"admin/user/changepassword/"+id, '_blank');
        redirectWindow.location;
        //window.location = baseURL+"admin/user/changepassword/"+id;
     
    }

   
 //  unit = $('#unit_id').val();  
 //  if(unit==1)
 //  {
 //  //$('#waddress12').removeAttr('required');
 //   //$("input").removeClass("required");
 //   $('#waddress12').val('null');
 //   $('#waddress22').val('null');
 //   $('#wcountry2').val('uk');
 //   $('#wcity2').val('ln');
 //   $('#wstatus2').val(1);
 //   $('#wpostcode2').val('null');
 //   $('#wkin_name2').val('null');
 //   $('#wkin_phone2').val('null');
 //   $('#wkin_address2').val('null');

 //   $('#whours2').val(0);
 //   $('#wallowance2').val(0);
 //   $('#wactualallowance2').val(0);
 //   $('#wshift2').val('null');
 //   $('#wsdate2').val('0000/00/00');
 //   $('#wpayroll2').val(0);

 //  // document.getElementById("waddress12").required = false;
 //  // document.getElementById("waddress22").required = false;
 //  // }
 //  // else
 //  // {
 //  // document.getElementById("waddress12").required = true;
 //  // document.getElementById("waddress22").required = true;
 //  } 
 // 