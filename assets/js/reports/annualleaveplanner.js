$(function() {
  $(document).ready(function() {
    var role = jobe_roles;  
    $(".select2").select2();
    if(role.length > 0){
      $('.jobrole').select2().val(role).trigger("change") 
    }
  });

  $(document).on('click', '.search', function(e) {   
    var unit=$('#unitdata').val(); 
    if(unit==0 ){
        swal("Please select unit");
        e.preventDefault();
    }
  
    });
});