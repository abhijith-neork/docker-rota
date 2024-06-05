$(function() {
  $(document).ready(function() {
    //http://localhost/rotasystem/users/shiftAprove/45/0=88&1=89 
    $(document).on('click', '.perform_all', function (e) {
      var id = event.target.id;
      var user_id = id.split("_")[0];
      var unit_id = id.split("_")[1];
      var from_unit = id.split("_")[2];
      var rota_ids = $("#rota_ids").val(); 
      var status = parseInt(id.split("_")[3]);
      performAction(user_id,unit_id,from_unit,rota_ids,status);  
    });
    $(document).on('click', '.shift_action', function (e) {
      var id = event.target.id;
      var user_id = id.split("_")[0];
      var unit_id = id.split("_")[1];
      var from_unit = id.split("_")[2];
      var rota_id = id.split("_")[3];
      var status = parseInt(id.split("_")[4]);
      performAction(user_id,unit_id,from_unit,rota_id,status);
    });
  });
});
function performAction(user_id,unit_id,from_unit,rota_id,status){
  var message;
  if(status == 1){
    message = "approved";
  }else{
    message = "rejected";
  }
  $.ajax({
    url:baseURL+'users/manageShift',
    type:'post',
    dataType:'json',
    delay: 250,
    data: {
      user_id : user_id,
      unit_id : unit_id,
      from_unit : from_unit,
      status : status,
      rota_ids: rota_id
    },
    success: function (data) {
      if(data.status == 1){
        swal({
          title : "",
          text: "Shift allocation "+message,
          icon: "warning",
          button: "ok",
        });
      }
    }
  });
}