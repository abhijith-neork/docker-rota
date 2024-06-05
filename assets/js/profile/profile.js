$(document).ready(function() {

    $('select[name="ethnicity"]').prop('disabled', true);
    $('select[name="gender"]').prop('disabled', true);
     $('#wdob2').attr('disabled', 'disabled');

                $("#wdob2").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#wdob2").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })

          // $(".ethnicity").change(function () {
          //       ethnicity = $('#ethnicity option:selected').val(); //alert(ethnicity); 
          //       user_type = $('#session_unittype').val(); 
          //       if(user_type==2)
          //       {
          //           $.ajax({
          //                       type :'POST',
          //                       dataType:'json',
          //                       data : { id : ethnicity },
          //                       url: baseURL+"staffs/Profile/findethnicity",
          //                       success : function(result){  console.log(result);
          //                           if(result.other_status==1)
          //                           {
          //                               document.getElementById("newethnicity").readOnly = false; 
          //                                document.getElementById('other').innerHTML = result['Ethnic_group'];
          //                               $("#other").show();
          //                               $('#newethnicity').val('');
          //                           }
          //                           else
          //                           {
          //                               $('#newethnicity').val(result['Ethnic_group']);
          //                                $("#other").hide();
          //                               document.getElementById("newethnicity").readOnly = true;
          //                           }
                                    
          //                       }
          //                   }); 

          //       }
          //       else
          //       {

          //           $.ajax({
          //                       type :'POST',
          //                       dataType:'json',
          //                       data : { id : ethnicity },
          //                       url: baseURL+"manager/Profile/findethnicity",
          //                       success : function(result){  console.log(result);
          //                           if(result.other_status==1)
          //                           {
          //                               document.getElementById("newethnicity").readOnly = false; 
          //                                document.getElementById('other').innerHTML = result['Ethnic_group'];
          //                               $("#other").show();
          //                               $('#newethnicity').val('');
          //                           }
          //                           else
          //                           {
          //                               $('#newethnicity').val(result['Ethnic_group']);
          //                                $("#other").hide();
          //                               document.getElementById("newethnicity").readOnly = true;
          //                           }
                                    
          //                       }
          //                   }); 
          //       }


          //   });

}); 

 