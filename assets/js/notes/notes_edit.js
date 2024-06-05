$(function() {
  $(document).ready(function() {
    $(".select2").select2(); 
    $('#pre-selected-options').multiSelect();
    $('#optgroup').multiSelect({   selectableOptgroup: true });
    $('#public-methods').multiSelect();
    /*".checkbox" change
    uncheck "select all", if one of the listed checkbox item is unchecked
    check "select all" if all checkbox items are checked*/
    $('.checkItem').change(function(){
      if ($('.checkItem:checked').length == $('.checkItem').length ){
        $( ".selectall" ).prop( "checked", true ); //change "select all" checked status to true
      }else{
        $( ".selectall" ).prop( "checked", false );
      }
    });
    if($('.checkItem:checked').length > 0){
      if ($('.checkItem:checked').length == $('.checkItem').length) {
        $( ".selectall" ).prop( "checked", true );
      }
    }
    $('#unit').attr("disabled", true)
    var unit=$('#unit').val();  
    unitIds = [];
    unitIds=unit;  
    $('.select2').on('select2:select', function (e) {
      var unit_id_string  = $("#user_unitids").val();
      if(unit_id_string){
        unitIds = unit_id_string.split(',');
      }   
      var data = e.params.data;
      unitIds.push( data.id);
      $.ajax({ 
            type: "post",dataType: "json",
            url: baseURL+"manager/Notes/findUser",
            data: {  unit_id:data.id },
            success: function(data) { 
              table.rows.add(data).draw();

            },
            error: function(data){
            }
      }); 
    }); 


   
    $('#selectall').click(function (){    
      $(':checkbox.checkItem').prop('checked', this.checked);    
    });

    var table = $('#myTable1').DataTable({                                   
      "ordering": true, 
      "order": [[0, "asc"]],
      "displayLength": 10,
      "bLengthChange": false,
      "processing": true,
      "language": {
        processing: '<div class="spinner-border text-primary" role="status"></div>'
      },
      'paging': true,
      'searching': true,
      'serverSide': true,
      "ajax":{
        url: baseURL+'manager/Notes/get_notes_users',
        dataType: 'json',
        type: "POST", 
        data: function ( d ) {
          d.note_id = $("#id").val();
        }
      },
      columns: 
      [ 
        { "data": "employee_name" },
        { "data": "designation" }
      ] 
    });
 
    $('form').on('submit', function(e){
      var $form = $(this);
      // Iterate over all checkboxes in the table
      table.$('input[type="checkbox"]').each(function(){
        // If checkbox doesn't exist in DOM
        //if(!$.contains(document, this)){
        // If checkbox is checked
        if(this.checked){
            // Create a hidden element 
          $form.append(
            $('<input>')
              .attr('type', 'hidden')
              .attr('name', this.name)
              .val(this.value)
          );
        }
      });          
    });
  });
});