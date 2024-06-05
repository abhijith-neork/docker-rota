$(function() {                                
  $(document).ready(function() {
    // var year = (new Date).getFullYear();
    // $('#start_date').datepicker({
    //   minDate: 0,
    //   dateFormat: 'dd/mm/yy',
    //   // minDate: new Date(year, 0, 1),
    //   maxDate: new Date(year, 11, 31)
    // });
     $("#start_date").datepicker({ 
      dateFormat: 'dd/mm/yy'
      });
      $("#start_date").on("change", function () {
      var fromdate = $(this).val();
                                        //alert(fromdate);
     }); 
    var table = $('#myTable').DataTable({
      "order": [
        [2, 'asc']
      ],
      "displayLength": 25,
      "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
      initComplete: function () {   
                                        this.api().columns([1]).every(function () {
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
                                                              // console.log(column.data());

                                           column.data().unique().sort().each(function (d, j) {
                                             select.append('<option value="' + d + '">' + d + '</option>')
                                          } );
                                        }  
                                 
                                        });
                                        } 
    });
  });

  $(document).on('click', '.search', function(e) {  
    var unit=$('#unitdata').val();
    var shift=$('#shift').val();
    var start_date=$('#start_date').val();
    if(unit!=0)
    {
      if(shift=='')
      {
        alert("Please select shift")
      }
      if(start_date=='')
      {
        alert("Please select date");
      }
    }
    else
    {
        $( "#frmViewStaffavailAbilityReport" ).submit();
    }
  });
  $('#select_all').click(function(event) {
    if(this.checked) {
      // Iterate each checkbox
      $(':checkbox').each(function() {
        this.checked = true;
      });
    }
    else {
      $(':checkbox').each(function() {
        this.checked = false;
      });
    }
  });
  function getHtml(){
    var html = '';
    html += '<!DOCTYPE html><html><body><label for="comment">Comment : </label>';
    html += '<textarea rows="4" cols="50"></textarea><label for="request">Request Count : </label>';
    html += '<textarea rows="4" cols="50"></textarea></body></html>';
    return html;
  }
  onload();
  function onload(){
    var unit_id = $( "#unitdata option:selected" ).val();
    var unit_for_id = $( "#unitdatafor option:selected" ).val();
    var unittype = $( "#unittype option:selected" ).val();
    var date = $("#start_date").val();
    var shift_id = $( "#shift option:selected" ).val();
    var parent_shift_id = $( "#shift option:selected" ).attr('data-parent');
    $.ajax({
      url:baseURL+'admin/reports/showUserStatus',
      type:'post',
      dataType:'json',
      delay: 250,
      data: {
        unit_id : unit_id,
        shift_id : shift_id,
        unit_for_id : unit_for_id,
        date : date,
        parent_shift_id : parent_shift_id,
        unittype : unittype
      },
      success: function (data) {
        var users = data.result;
        var total_requets = 0;
        var a_count = 0;
        var r_count = 0;
        var p_count = 0;
        var d_count = 0;
        var users = data.result;
        if(users.length >0){
          var request_count = users[0].request_count;
          $(".show_status").show();
          for (var i = 0; i < users.length; i++) {
            if(users[i].status == 1){
              a_count++;           
            }else if(users[i].status == 2){
              r_count++;
            }else if(users[i].status == 0){
              p_count++;
            }else{
              d_count++;
            }
          }
          var text = "Request Count : "+request_count+" Total request : "+users.length+" ,Accepted : "+a_count+", Rejected : "+r_count+", Deleted : "+d_count+", Pending :"+p_count;
          $(".show_counts").show().html(text);
        }else{
          $(".show_status").hide();
          $(".show_counts").hide();
        }
      }
    });
  }
  $(document).on('click', '.delete_all_request', function(event) {
    var unit_id = $( "#unitdata option:selected" ).val();
    var unit_for_id = $( "#unitdatafor option:selected" ).val();
    var date = $("#start_date").val();
    var shift_id = $( "#shift option:selected" ).val();
    var parent_shift_id = $( "#shift option:selected" ).attr('data-parent');
    var user_id = event.target.id;
    var html = '<h4>Are you sure, you want to delete all requests, this will remove all requests and rota entries of accepted request of users if any</h4>';
    swal({
        title: html,
        // --------------^-- define html element with id
        html : true,
        showCancelButton: true,
        closeOnCancel: true,
        closeOnConfirm: true
    }, function (isConfirm) {
      if(isConfirm){
        $.ajax({
          url:baseURL+'admin/reports/deleteAllRequests',
          type:'post',
          dataType:'json',
          delay: 250,
          data: {
            unit_id : unit_id,
            shift_id : shift_id,
            unit_for_id : unit_for_id,
            date : date,
            parent_shift_id : parent_shift_id,
            user_id : user_id
          },
          success: function (data) {
            if(data.status == true){
              $( "#frmViewStaffavailAbilityReport" ).submit();
            }
          }
        });
      }
    });
  });
  $(document).on('click', '.delete_request', function(event) {
    var unit_id = $( "#unitdata option:selected" ).val();
    var unit_for_id = $( "#unitdatafor option:selected" ).val();
    var date = $("#start_date").val();
    var shift_id = $( "#shift option:selected" ).val();
    var parent_shift_id = $( "#shift option:selected" ).attr('data-parent');
    var user_id = event.target.id;
    var html = '<h4>Are you sure, you want to delete this request ?</h4>';
    swal({
        title: html,
        // --------------^-- define html element with id
        html : true,
        showCancelButton: true,
        closeOnCancel: true,
        closeOnConfirm: true
    }, function (isConfirm) {
      if(isConfirm){
        $.ajax({
          url:baseURL+'admin/reports/deleteRequest',
          type:'post',
          dataType:'json',
          delay: 250,
          data: {
            unit_id : unit_id,
            shift_id : shift_id,
            unit_for_id : unit_for_id,
            date : date,
            parent_shift_id : parent_shift_id,
            user_id : user_id
          },
          success: function (data) {
            if(data.status == true){
              $( "#frmViewStaffavailAbilityReport" ).submit();
            }
          }
        });
      }
    });
  });
  $(document).on('click', '.live-status', function(event) {
    window.location=baseURL+'admin/reports/showAvailabilityList';
  });
  $(document).on('click', '.show_status', function(event) {
    event.preventDefault();
    var html = '';
    var unit_id = $( "#unitdata option:selected" ).val();
    var unit_for_id = $( "#unitdatafor option:selected" ).val();
    var unittype = $( "#unittype option:selected" ).val();
    var date = $("#start_date").val();
    var shift_id = $( "#shift option:selected" ).val();
    var parent_shift_id = $( "#shift option:selected" ).attr('data-parent');
    $.ajax({
      url:baseURL+'admin/reports/showUserStatus',
      type:'post',
      dataType:'json',
      delay: 250,
      data: {
        unit_id : unit_id,
        shift_id : shift_id,
        unit_for_id : unit_for_id,
        date : date,
        parent_shift_id : parent_shift_id,
        unittype : unittype
      },
      success: function (data) {
        var users = data.result;
        console.log(users)
        var rejected_users = '';
        var accepted_users = '';
        var pending_users = '';
        var deleted_users = '';
        var total_requets = 0;
        var a_count = 0;
        var r_count = 0;
        var p_count = 0;
        var d_count = 0;
        if(users.length > 0){
          var total_requets = users.length;
          for (var i = 0; i < users.length; i++) {
            if(users[i].status == 1){
              a_count++;
              if(accepted_users ==''){
                accepted_users += users[i].fname +' '+users[i].lname; 
              }else{
                accepted_users += ','+users[i].fname +' '+users[i].lname;
              }
              if(users[i].message){
                accepted_users = accepted_users+'('+users[i].message+')';
              }
            }else if(users[i].status == 2){
              r_count++;
              if(rejected_users ==''){
                rejected_users += users[i].fname +' '+users[i].lname; 
              }else{
                rejected_users += ','+users[i].fname +' '+users[i].lname; 
              }
              if(users[i].message){
                rejected_users = rejected_users+'('+users[i].message+')';
              }
            }else if(users[i].status == 0){
              p_count++;
              if(pending_users ==''){
                pending_users += users[i].fname +' '+users[i].lname; 
              }else{
                pending_users += ','+users[i].fname +' '+users[i].lname; 
              }
              if(users[i].message){
                pending_users = pending_users+'('+users[i].message+')';
              }
            }else{
              d_count++;
              if(deleted_users ==''){
                deleted_users += users[i].fname +' '+users[i].lname; 
              }else{
                deleted_users += ','+users[i].fname +' '+users[i].lname; 
              }
              if(users[i].message){
                deleted_users = deleted_users+'('+users[i].message+')';
              }
            }
          }
          if(accepted_users.substr(1) == ''){
            accepted_users = 'No records ';
          }
          if(rejected_users.substr(1) == ''){
            rejected_users = 'No records';
          }
          if(pending_users.substr(1) == ''){
            pending_users = 'No records';
          }
          if(deleted_users.substr(1) == ''){
            deleted_users = 'No records';
          }
        }else{
          accepted_users = 'No records';
          rejected_users = 'No records';
          pending_users = 'No records';
          deleted_users = 'No records';
        }
        html += '<label style="font-weight: bold;text-decoration:underline;">Accepted'+'('+a_count+')'+'</label><br>';
        html += "<p class='accepted_users'>"+accepted_users+"</p><br>";
        html += '<label style="font-weight: bold;text-decoration:underline;">Rejected'+'('+r_count+')'+'</label>';
        html += "<p class='rejected_users'>"+rejected_users+"</p><br>";
        html += '<label style="font-weight: bold;text-decoration:underline;">Deleted'+'('+d_count+')'+'</label><br>';
        html += "<p class='deleted_users'>"+deleted_users+"</p><br>";
        html += '<label style="font-weight: bold;text-decoration:underline;">Pending'+'('+p_count+')'+'</label><br>';
        html += "<p class='pending_users'>"+pending_users+"</p><br>";
        swal({
            title: "Total request : "+total_requets,
            text: html,
            // --------------^-- define html element with id
            html: true,
            showCancelButton: false,
            closeOnConfirm: true
        }, function (isConfirm) {
        if(isConfirm){

        }
        });
      }
    });
  });
  $(document).on('click', '.send_request', function(event) {
    event.preventDefault();
    var users = [];
    var unit_id = $( "#unitdata option:selected" ).val();
    var unittype = $( "#unittype option:selected" ).val();
    var unit_name = $( "#unitdata option:selected" ).text();
    var unit_for_id = $( "#unitdatafor option:selected" ).val();
    var unit_for_name = $( "#unitdatafor option:selected" ).text();
    var shift_name = $( "#shift option:selected" ).text();
    var shift_id = $( "#shift option:selected" ).val();
    var parent_shift_id = $( "#shift option:selected" ).attr('data-parent');
    var shift_cat = $( "#shift option:selected" ).attr('data-cat');
    var date = $("#start_date").val();
    var html = '';
    var shift_min = '';
    var staffs = '';
    $("input:checkbox[name=users]:checked").each(function() {
      var user_id = $(this).val();
      var email = $(this).attr('data-email');
      var name = $(this).attr('data-name');
      var mobile_number = $(this).attr('data-number');
      var user_unitid = $(this).attr('data-userunitid');
      users.push({
        'user_id' : user_id,
        'email'  : email,
        'name'   : name,
        'mobile_number' : mobile_number,
        'user_unitid' : user_unitid,
        'unittype' : unittype
      });      
    });
  if(users.length >0){
      var staffs_on_leave = leave_details.staffs_on_leave;
      var day_shift_min = leave_details.day_shift_min;
      var night_shift_min = leave_details.night_shift_min;
      var staffs_on_night = leave_details.staffs_on_night;
      var staffs_on_day = leave_details.staffs_on_day
      var leave_count = leave_details.length;
      if(shift_cat == 1){
        shift_min = day_shift_min;
        staffs = staffs_on_day;
      }else if(shift_cat == 2){
        shift_min = night_shift_min;
        staffs = staffs_on_night; 
      }else{
        //nothing
      }
      if(shift_min <= staffs){
        html += '<p style="font-weight: bold;">Enter comment</p>';
        html += "<textarea rows='2' placeholder='Comment' cols='40' id='comment'></textarea>";
        html += '<p style="font-weight: bold;">Enter request count</p>';
        html += "<textarea rows='2' placeholder='Request count' cols='40' maxlength='50' id='request_count'></textarea>";
        swal({
            title: "",
            text: html,
            // --------------^-- define html element with id
            html: true,
            showCancelButton: true,
            closeOnCancel : true,
            closeOnConfirm: false
        }, function (isConfirm) {
        if(isConfirm){
          var comment = $("#comment").val();  
          var request_count = $("#request_count").val();  
          if(comment && request_count){
            if(isNaN(request_count)){
              swal.showInputError("Please enter a number value for request count");
            }else{
              swal.close();
              $.ajax({
                url:baseURL+'admin/reports/sendRequest',
                type:'post',
                dataType:'json',
                delay: 250,
                data: {
                  users : users,
                  unit_id : unit_id,
                  unit_name : unit_name,
                  shift_name : shift_name,
                  date : date,
                  shift_id : shift_id,
                  unit_for_id : unit_for_id,
                  unit_for_name : unit_for_name,
                  parent_shift_id : parent_shift_id,
                  comment : comment,
                  request_count : request_count,
                  unittype : unittype
                },
                success: function (data) {
                  $( "#frmViewStaffavailAbilityReport" ).submit();
                }
              });
            }
          }else{
            swal.showInputError("Please enter both values");
          }
        }else{
          //Nothing
        }
        });
      }else{
        html += '<p style="font-weight: bold;">Enter Request Count</p>';
        html += "<textarea rows='2' placeholder='Request Count' cols='40' maxlength='50' id='request_count'></textarea>";
        swal({
            title: "",
            text: html,
            // --------------^-- define html element with id
            html: true,
            showCancelButton: true,
            closeOnCancel : true,
            closeOnConfirm: false
        }, function (isConfirm) {
          if(isConfirm){
            var request_count = $("#request_count").val();
            if(request_count){
              if(isNaN(request_count)){
                swal.showInputError("Please enter a number value for request count");
              }else{
                $.ajax({
                  url:baseURL+'admin/reports/sendRequest',
                  type:'post',
                  dataType:'json',
                  delay: 250,
                  data: {
                    users : users,
                    unit_id : unit_id,
                    unit_name : unit_name,
                    shift_name : shift_name,
                    date : date,
                    shift_id : shift_id,
                    unit_for_id : unit_for_id,
                    unit_for_name : unit_for_name,
                    parent_shift_id : parent_shift_id,
                    request_count : request_count
                  },
                  success: function (data) {
                    $( "#frmViewStaffavailAbilityReport" ).submit();
                  }
                });
              }
            }else{
              swal.showInputError("Please enter request count");
            }
          }
          else{
            //Nothing
          }
        });
      }
    }else{
      swal({
        title : "",
        text: "Please select users.",
        icon: "warning",
        button: "ok",
      });
    }
  });
});
$('#unitdata').change(function(){
  $("#unittype").val(0);
});
$('#unittype').change(function(){  
  $("#unitdata").val("");
});