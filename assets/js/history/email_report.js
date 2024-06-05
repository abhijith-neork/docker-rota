$(document).ready(function () {
           $("#start_time").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#start_time").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

        $('#myTable').DataTable({ 
            "ordering":false,
            "stateSave": true, 
            "autoWidth": true,
            "dom": 'lBfrtip', 
            "buttons": ['copy', 'excel', 'pdf', 'print'],                                          
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 25,
            "aLengthMenu": [[ 25, 50, 75, 100, 0], [25, 50, 75, 100, "All"]], 
            "ajax":{
         url: baseURL+'admin/History/emailreport',
         dataType: 'json',
         type: "POST", 
         data:{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
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
