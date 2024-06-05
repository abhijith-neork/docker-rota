$(function() {
    $(document).ready(function() {
        var table = $('#contactHoursTable').DataTable({
            "ordering": true, 
            "order": [[5, "desc"]],
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
                url: baseURL+'admin/History/get_contracthourschangehistory',
                dataType: 'json',
                type: "POST", 
                data: function ( d ) {
                }
            },
            columns: 
            [ 
                { "data": "user_id" },
                { "data": "name" },
                { "data": "prev_hour" },
                { "data": "current_hour" },
                { "data": "updated_user" },
                { "data": "updation_date" },
            ]                          
        });
        // Order by the grouping
        $('#contactHoursTable tbody').on('click', 'tr.user', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
    });
});