$(function() {
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            "ordering": true, 
            "order": [[4, "desc"]],
            "displayLength": 25,  // Set the desired default value
            "processing": true,
            "language": {
                processing: '<div class="spinner-border text-primary" role="status"></div>'
            },
            'paging': true,
            'searching': true,
            'serverSide': true,
            'dom': 'lBfrtip',
            'buttons': [
                'copy', 'excel', 'pdf', 'print'
            ],
            "displayLength": 25,
            "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
            initComplete: function () {
                this.api().columns([]).every(function () {
                var column = this;
                if (column.index() !== 0) 
                {  
                   $(column.header()).append("<br>")
                   var select = $('<select><option value=""></option></select>')
                                       .appendTo($(column.header()))
                                       .on('change', function () {
                         var val = $.fn.dataTable.util.escapeRegex(
                             $(this).val()
                         );
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                     });

                   column.data().unique().sort().each(function (d, j) {
                     select.append('<option value="' + d + '">' + d + '</option>')
                  } );
                }  
         
                });
            },
            "ajax":{
                url: baseURL+'admin/History/get_userunitchangehistory',
                dataType: 'json',
                type: "POST", 
                data: function ( d ) {
                }
            },
            columns: 
            [ 
                { "data": "user_id" },
                { "data": "name" },
                { "data": "old_unit" },
                { "data": "new_unit" },
                { "data": "updation_date" },
                { "data": "updated_user" }
            ] 
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