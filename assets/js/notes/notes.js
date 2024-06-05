$(function() {
  $(document).ready(function() { 
    var uniqueValues = [
        { 'key': 1, 'value': 'Both (SMS & Mail)' },
        { 'key': 2, 'value': 'SMS' },
        { 'key': 3, 'value': 'Mail' }
    ];
    var table = $('#myTable').DataTable({
      "ordering": true, 
      "order": [[3, "desc"]],
      "displayLength": 25,
      "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
      "processing": true,
      "language": {
        processing: '<div class="spinner-border text-primary" role="status"></div>'
      },
      'paging': true,
      'searching': true,
      'serverSide': true,
      "columnDefs": [ {
        "targets": 2,
        "orderable": false
      } ,{
        "targets": 4,
        "orderable": false
      },{
        "targets": 5,
        "orderable": false
      }],
      initComplete: function() {
        this.api().columns('2').every(function() {
          var column = this;  
          if (column.index() !== 0){  
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
              uniqueValues.forEach(function(item) {
                select.append('<option style="width:120px;height:2px;" value="' + item.key + '">' + item.value + '</option>')
              });
          }
        });
        $(".mymsel").select2();
      },
      "ajax":{
        url: baseURL+'manager/Notes/get_notes',
        dataType: 'json',
        type: "POST", 
        data: function ( d ) {
          // Retrieve dynamic parameters
          var dt_params = $('#myTable').data('dt_params');
          // Add dynamic parameters to the data object sent to the server
          if(dt_params){ $.extend(d, dt_params); }
          // Add selected values from dropdown as an extra parameter
          d.selectedValues = $(".mymsel").val();
        }
      },
      columns: 
      [ 
        { "data": "title" },
        { "data": "description" },
        { "data": "notification_type" },
        { "data": "date_time" },
        { "data": "added_by" },
        { "data": "action" }
      ] 
    });
  });
});