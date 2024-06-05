$(function() {
                                
    $(document).ready(function() {
        var selected_users_array = []; 
        $("#from-datepicker").datepicker({ 
          dateFormat: 'dd/mm/yy'
        });
        $("#from-datepicker").on("change", function () {
          var fromdate = $(this).val();
        }); 

        $("#to-datepicker").datepicker({ 
          dateFormat: 'dd/mm/yy'
        });
        $("#to-datepicker").on("change", function () {
          var fromdate = $(this).val();
        });
                                                                   
        $(".select2").select2(); 
        $('#pre-selected-options').multiSelect();

        $('#optgroup').multiSelect({   selectableOptgroup: true });  

        $('#public-methods').multiSelect();  

          var unit=$('#unit').val();   
          unitIds = [];
          parent_unit=[];
          unitIds=unit;  

        $('.select2').on('select2:select', function (e) {
          var unit_id_string  = $("#user_unitids").val();  
 
       // = $("#user_unitids").select2("data");; 
        var pid = $("#unit").select2().find(":selected").data("parent");
       //   console.log(pid);
          if(unit_id_string){  
            unitIds = unit_id_string.split(',');
          }  

        var designation=$('#hgr').val();

        var data = e.params.data;
        var parent=data.element.attributes[2].nodeValue;   
        parent_unit.push(parent);  
    
          if(data!='')
          {
          unitIds.push(data.id);} 
          unitIds = unitIds.filter(function(e){return e});   
          $subunit=$.inArray(parent, unitIds);   
          if($subunit==-1){
             $parentunit=$.inArray(data.id, parent_unit); 
             if($parentunit==-1)
             { 
                $.ajax({ 
                            type: "post",dataType: "json",
                            url: baseURL+"admin/training/finduser",
                            data: {  unit_id:data.id,selected_ids: unitIds, designation:designation  },
                            success: function(data) {  
                            //table.clear(); 
                              table.rows.add(data).draw();
                              table.columns(1).every(function () {
                                                var column = this; 
                                                if (column.index() !== 0) 
                                                {  
                                                  $("#hgr").remove();
                                                   var select = $('<select id="hgr"><option value=""></option></select>')
                                                                       .appendTo($(column.header()))
                                                                       .on('change', function () {
                                                         var val = $.fn.dataTable.util.escapeRegex(
                                                             $(this).val()
                                                         );
                                                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                                                     }); 
                                                   column.data().unique().sort().each(function (d, j) { 
                                                    if(designation==d)
                                                    {
                                                      select.append('<option selected="selected" value="' + d + '">' + d + '</option>')
                                                    }
                                                    else
                                                    {
                                                      select.append('<option value="' + d + '">' + d + '</option>')
                                                    }

                                                  } );
                                                }  
                                         
                                                });
                                                 } 
                      }); 
              }
              else
              {
                $.ajax({ 
                            type: "post",dataType: "json",
                            url: baseURL+"admin/training/finduser",
                            data: {  unit_id:data.id,selected_ids: unitIds ,designation:designation},
                            success: function(data) {  
                            //table.clear(); 
                              table.rows.add(data).draw();
                              table.columns(1).every(function () {
                                                var column = this; 
                                                if (column.index() !== 0) 
                                                {  
                                                  $("#hgr").remove();
                                                   var select = $('<select id="hgr"><option value=""></option></select>')
                                                                       .appendTo($(column.header()))
                                                                       .on('change', function () {
                                                         var val = $.fn.dataTable.util.escapeRegex(
                                                             $(this).val()
                                                         );
                                                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                                                     }); 
                                                   column.data().unique().sort().each(function (d, j) {  
                                                     if(designation==d)
                                                    {
                                                      select.append('<option selected="selected" value="' + d + '">' + d + '</option>')
                                                    }
                                                    else
                                                    {
                                                      select.append('<option value="' + d + '">' + d + '</option>')
                                                    }
                                                  } );
                                                }  
                                         
                                                });
                                                 } 
                      }); 

              }
        }
        }); 

        function findUsersOfUnselectedUnit(unit_id){
          var user_list;
          var subunits = getSubunits(unit_id);
          subunits.push(unit_id)

          $.ajax({ 
            type: "post",
            dataType: "json",
            async : false,
            url: baseURL+"manager/Notes/findUsersOfUnselectedUnit",
            data: { units : subunits },
            success: function(data){
              user_list = data
            } 
          });
          return user_list;
        }
        $('.select2').on('select2:unselect', function (e) { 
        var data = e.params.data; 
        var unit_id = data.id;
        var subunits = getSubunits(unit_id);
        /*if(subunits.length > 0){
          for(var i=0;i<subunits.length;i++){
            $('.unit_'+subunits[i]).remove();
          }
        }else{
          $('.unit_'+unit_id).remove();
        }
        removeTable();*/
        var users = findUsersOfUnselectedUnit(unit_id);
        selected_users_array = removeUnselectedUnitUsers(selected_users_array,users);
        createTable(selected_users_array);
        for( var i = 0; i < unitIds.length; i++){ 
        if ( unitIds[i] === data.id) 
          { 
            unitIds.splice(i, 1);
             i--; 
           }

        } 
        unitIds = unitIds.filter(function(e){return e}); 
        // console.log(unitIds);  
        var no_unit=[0]; //console.log(no_unit);
        if(unitIds!='')
        {
        $.ajax({ 
                    type: "post",dataType: "json",
                    url: baseURL+"admin/training/finduser",
                    data: {  unit_id:unitIds,selected_ids: no_unit },
                    success: function(data1){ 
                        if(data1=='') { 
                            table.clear().draw();
                           }
                           else
                           { 
                            table.clear(); 
                            table.rows.add(data1).draw(); 
                           }
                                            } 
              }); 
        }
        else
        {

            table.clear().draw();
        }
     
           
        });
     
        $('#add-option').on('click', function() {  
            //console.log(abc);
            $('#public-methods').multiSelect('addOption', {
                value: 42,
                text: 'test 42',
                index: 0
            });
            return false;
        });   
 

        // $('#selectall').click(function (){    
        // $(':checkbox.checkItem').prop('checked', this.checked);    
        // });

        /*var arr= $('.select2').val(); alert(arr);
        $.ajax({ 
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/training/finduser",
                    data: {  unit_id:arr },
                    success: function(data) 
                    {
                      table.rows.add(data).draw(); 
                     } 
              });   
*/
 
    
   $(".unitdata").change(function () {
  unit = $('#unitdata option:selected').val();
  $.ajax({
        type :'POST',
        dataType:'json',
        data : { unit_id : unit },
        url: baseURL+"admin/training/findunitdetails",
        success : function(result){  
         $('#contact_number').val(result['phone']); 
         $('#address').val(result['address']); 
         $('#training_unit').val(result['unit_name']);    
        }
    }); 

});


   $(".training_title").change(function () {
    training = $('#training_title option:selected').val();
  $.ajax({
        type :'POST',
        dataType:'json',
        data : { unit_id : training },
        url: baseURL+"admin/training/findtrainingtitle",
        success : function(result){  
         $('#title').val(result['title']);  
         $('#description').val(result['description']);   
        }
    }); 

});

              

              

        var table = $('#myTable1').DataTable({ 
                                   
                                    "order": [
                                    [0, 'asc']
                                    ],
                                    "bLengthChange": true,
                                    "displayLength": 25,
                                    "lengthMenu": [[ 25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
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
              .val(this.value));
         }
      //} 
   });          
});

$(document).on('click', '#try', function(e) { 

  var from_date=$("#from-datepicker").val();  
  var to_date=$("#to-datepicker").val(); 
  var hour=$("#training_hour").val();
  
   if(from_date==to_date)
   { 
     //alert(hour);
      abc=hour.split(":");
      minute=abc[1]; //alert(minute);
      var regex = /^\d{2}:\d{2}$/;
      if(regex.test(hour) == true)
      {
                    //console.log(annual);console.log(num);
                      if(minute >= 60)
                      {
                            swal('Please enter valid hour');
                            event.preventDefault();
                      }
                      else
                      {

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
                                    .val(this.value));
                               }
                            //} 
                         });   

                          
                      }
                        
      }
      else
      {
          swal('Please correct your hours format to hh:mm');
          event.preventDefault(); 
      }
   }
   else
   {    
            swal('Please select equal dates.');
            event.preventDefault(); 
   }
});
function formatDate(date){
    var date_array = date.split('/');
    var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
    return new_date;
}
$(document).on('click', '.checkItem', function(){
      var html = '';
      var user_details = $(this).val();
      var unit_shortcode = $(this).attr('data-unit');
      var user_name = $(this).attr('data-username');
      var unit_id = $(this).attr('data-unitid');
      var user_id = user_details.split('___')[0];
      var n=5;
      var object = user_id+"_"+unit_id+"_"+user_name+"_"+unit_shortcode;
      if($(this).is(":checked")) {
        $(".user-list").show();
        if(jQuery.inArray(object, selected_users_array) == -1)
        {
          selected_users_array.push(object);
        }
        // console.log(selected_users_array)
        createTable(selected_users_array);
        /*var c = $('input.checkItem:checked').length;
        
         if(c % n == 1 && c != 0)  
        {
          html += '<tr><td class="user_'+user_id+' unit_'+unit_id+'">'+user_name+'('+unit_shortcode+')'+'</td>'+'</tr>';
          $('.selected-user-data').append(html);
        }else{
           html += '<td class="user_'+user_id+' unit_'+unit_id+'">'+user_name+'('+unit_shortcode+')'+'</td>';
          $("#selected-users").find('tr:last').append(html);
        }*/
      }else{
        selected_users_array = removeUnSelectedObject(selected_users_array,user_id);
        createTable(selected_users_array);
      }
    });

    $("#myTable1 #selectall").click(function () {
        if ($("#myTable1 #selectall").is(':checked')) 
        {
            $("#myTable1 input[type=checkbox]").each(function () {
                $(this).prop("checked", true);
            });

        } 
        else 
        {  
            $("#myTable1 input[type=checkbox]").each(function () {
                $(this).prop("checked", false);
            });
        }
    });
    
     $(document).on('change', '#hgr', function(){
      var matches = document.querySelectorAll('input[type="checkbox"]:not(:checked)'); 
      if(matches.length==0)
      { 
         $('#selectall').prop('checked', true);
      }
      else
      { 
         $('#selectall').prop('checked', false);
      }
     
     });

    $(document).on('click', '.selectall', function(){
      var designation=$('#hgr').val(); //console.log(designation);
       $(".selected-users-table tr").remove();
      var user_array = [];
      var html = '';
      var test = 0; 
      if ($(this).is(':checked')) {
        $(".user-list").show();
        var checkboxes = document.querySelectorAll('#myTable1 input[type="checkbox"]');  
        var allCheckedInput = $('#myTable1 td input.checkItem:checked'); 
         //console.log(abc);
        if(designation=='')
        { 
          var abc=table.$("input[type='checkbox']").attr('checked', $(this.checked)); 
          for(var i=0;i<abc.length;i++)
          {  

            var data=abc[i]; //console.log(data);

              var id=abc[i]['id'];   //console.log(id); 
              var user_details = abc[i]['value'];  
              var new_values=user_details.split('___'); //console.log(new_values);
              var unit_shortcode =new_values[3]; 
              var user_name = new_values[4]; 
              var unit_id = new_values[2];
              var user_id = new_values[0];
              var object = user_id+"_"+unit_id+"_"+user_name+"_"+unit_shortcode;
              /*var object = {
                unit_shortcode : unit_shortcode,
                user_name : user_name,
                unit_id : unit_id,
                user_id : user_id
              }*/
              user_array.push(object);
              if(jQuery.inArray(object, selected_users_array) == -1)
              {
                selected_users_array.push(object);
              }
          }
        }
        else
        {

            checkboxes.forEach(function(checkbox) {  
              test++;
              if($(checkbox).hasClass( "checkItem" )){
                var user_details = $(checkbox).val();
                var unit_shortcode = $(checkbox).attr('data-unit');
                var user_name = $(checkbox).attr('data-username');
                var unit_id = $(checkbox).attr('data-unitid');
                var user_id = user_details.split('___')[0];
                var object = user_id+"_"+unit_id+"_"+user_name+"_"+unit_shortcode;
                /*var object = {
                  unit_shortcode : unit_shortcode,
                  user_name : user_name,
                  unit_id : unit_id,
                  user_id : user_id
                }*/
                user_array.push(object);
                if(jQuery.inArray(object, selected_users_array) == -1)
                {
                  selected_users_array.push(object);
                }
              }
            }); 

        }
       // console.log(user_array);
       createTable(selected_users_array);
      }
      else
      {
        if(designation=='')
        {  
            var abc=table.$("input[type='checkbox']").removeAttr('checked', $(this.unchecked));  
            selected_users_array = [];
            $(".selected-users-table tr").remove();
            $(".user-list").hide();
        }
        else
        {  

          $("input:checkbox:not(:checked)").each(function () {
               $users=$(this).val(); 
               $new_users=$users.split('___');console.log($new_users);
               user_id=$new_users[0];
               selected_users_array = removeUnSelectedObject(selected_users_array,user_id);
              createTable(selected_users_array);
            });

            
        }
      }
    });
    function createTable(user_array){
      // console.log(user_array)
      $(".selected-users-table tr").remove();
       $('#selected_users').val(selected_users_array);
      var max = 5;
      for( var i = 0; i < user_array.length; i++ ) {
        var user_details = user_array[i].split("_");
        var tr = $("table.selected-users-table tr:last");
        if(!tr.length || tr.find("td").length >= max)
            $("table.selected-users-table").append("<tr>");
            $("table.selected-users-table tr:last").append("<td class='user_"+user_details[0]+" unit_"+user_details[1]+"'>"+user_details[2]+'('+user_details[3]+')'+"</td>");
      }
    }
    function removeUnselectedUnitUsers(user_array,users){
      // console.log(users)
      selected_users_array = [];
      for( var i = 0; i < users.length; i++ ) {
        if(user_array.indexOf(users[i]) != -1){
          var index = user_array.indexOf(users[i]);
          user_array.splice(index,1);
        }
      }
      selected_users_array = user_array;
      return selected_users_array;
    }
    function removeUnSelectedObject(user_array,user_id){
      selected_users_array = [];
      for( var i = 0; i < user_array.length; i++){
        var user_details = user_array[i].split("_");
        // console.log(user_array[i])
        if(user_details[0] != user_id){
          selected_users_array.push(user_array[i]);
        }
      }
      return selected_users_array;
    }
function getSubunits(id){
        return unit_array[id];
      }
    function removeTable(){
      var atLeastOneIsChecked = false;
      $('input:checkbox').each(function () {
        if ($(this).is(':checked')) {
          atLeastOneIsChecked = true;
          // Stop .each from processing any more items
          return false;
        }
      });
      if(atLeastOneIsChecked == false){
        $(".user-list").hide();
      }
    }

       });

           
  });
 

 

