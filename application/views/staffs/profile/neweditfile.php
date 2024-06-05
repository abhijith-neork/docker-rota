<!DOCTYPE html>
<html lang="en">



<body class="fix-header card-no-border fix-sidebar"> 
    <div id="main-wrapper"> 
        <div class="page-wrapper"> 
            <div class="container-fluid"> 
              
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">File Upload5</h4>
                                <label for="input-file-max-fs">You can add a max file size</label>
                                <input type="file" id="input-file-max-fs" class="dropify" data-max-file-size="2M" />
                            </div>
                        </div>
                    </div>
                     
                </div>
              
            </div>
      
        </div> 
    </div> 

    <script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
     
    <!-- <script src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script> -->
    
    <!-- <script src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script> -->
   
   <!--  <script src="<?php echo base_url();?>assets/js/waves.js"></script> -->
   
    <!-- <script src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script> -->
    
    <!-- <script src="<?php echo base_url();?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script> -->
    
    <!-- <script src="<?php echo base_url();?>assets/js/custom.min.js"></script> -->
 
    <script src="<?php echo base_url();?>assets/plugins/dropify/dist/js/dropify.min.js"></script>
    <script>
    $(document).ready(function() {
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
    });
    </script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body> 
