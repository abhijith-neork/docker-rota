 $(function() {
                                
                                    $(document).ready(function() {  
                                    $("#start_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#start_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                    $("#end_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#end_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });

                                    $("#from-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });

                                    var table = $('#myTable').DataTable({
                                   
                                    "order": [
                                    [4, 'desc']
                                    ],
                                    "displayLength": 15,
                                    "lengthMenu": [[15, 25, 50, 75, 100, -1], [15, 25, 50, 75, 100, "All"]],
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

                            $(document).on('click', '.search', function(e) {  
                                     var from_date = $("#start_date").val();   
                                     var to_date = $("#end_date").val();  
                                     
                                     if(from_date=='' && to_date=='')
                                     { 
                                      event.preventDefault();
                                      swal("Please select dates");
         
                                     }
                                     else if(from_date=='')
                                     {
                                       event.preventDefault();
                                       swal("Please select from date");
                                     }
                                     else if(to_date=='')
                                     {
                                       event.preventDefault();
                                       swal("Please select to date");
                                     }
                                     else if(new Date(corectDateFormatbydate(from_date)).getTime() > new Date(corectDateFormatbydate(to_date)).getTime())
                                     {
                                       event.preventDefault();
                                       swal("Invalid dates.");
                                     }
                                     else
                                     {
                                           $("#frmViewdailysenses").submit();
                                    
                                     }
                                    });

 $('#unitdata').change(function(){  

    var unitID = $('#unitdata').val();
            if(unitID) { $('select[name="user"]').empty();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/Dailysenses/finduserdata",
                    data : { unit_id : unitID },
                    success:function(data) {  

                   
                        $('select[name="user"]').append('<option value="0">--Select user--</option>');
                        $.each(data, function(key, value) {  
                            $('select[name="user"]').append('<option value="'+ value.user_id +'">'+ value.fname+' '+value.lname +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="user"]').empty();
            }
        });

    function editFunction(id) {
        event.preventDefault(); // prevent form submit
          window.location = baseURL+"admin/payment/editpaymenttype/"+id;
     
    }
    function deleteFunction(id, paymenttype) {
      //console.log(id); console.log(paymenttype);
        event.preventDefault(); // prevent form submit
        //var form = event.target.form; // storing the form
                swal({
          title: 'Are you sure you want to delete this Daily Census?', 
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
              window.location = baseURL+"admin/Dailysenses/deleteddailysenses/"+id
          } else {
              return false;
          }
        });
        }
function corectDateFormatbydate(date){
      var date_array = date.split("/");
      var date_with_slash = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
      return date_with_slash;
    }

function corectDateFormat(date){
      var date_array = date.split("-");
      var date_with_slash = date_array[2]+"/"+date_array[1]+"/"+date_array[0];
      return date_with_slash;
    }
