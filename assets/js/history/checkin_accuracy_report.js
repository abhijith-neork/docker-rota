$(function() {
   var table;
    $(document).ready(function () {
       var unit=$('#unitdata').val();  
        $("#start_time").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#start_time").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 
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
             url: baseURL+'admin/History/CheckinAccuracyReport',
            dataType: 'json',
            type: "POST", 
            data: function ( d ) {
               // Retrieve dynamic parameters
               var dt_params = $('#myTable').data('dt_params');
               // Add dynamic parameters to the data object sent to the server
               if(dt_params){ $.extend(d, dt_params); }
            }
          },
           columns: [
                        { "data": "ID" },
                        { "data": "Accuracy" },
                        { "data": "User" },
                        { "data": "Staff Name"},
                        { "data": "Check With UserID" },
                        { "data": "Date" },
                        { "data": "Status" }, 
                     ] 
       });               
    });
    $(document).on('click', '.search', function(e) {
      var unit=$('#unit').val(); 
      var start_time=$('#start_time').val(); 
      if(start_time=='')
      {
          swal("Please select date");
          e.preventDefault();
      }
      else{
      table.clear(); 
      // table.data('dt_params', {unit:$('#unitdata').val(),jobrole:$('#jobrole').val()} );
      $('#myTable').data('dt_params', { unit: unit, start_time: start_time});
      table.draw(); 
      }
   });
});