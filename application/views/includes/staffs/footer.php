
<footer class="footer">© 2019</footer>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/waves.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/dropify/dist/js/dropify.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/moment/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/footable/js/footable.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.datetimepicker.min.js"></script>

                                    <!-- start - This is for export functionality only -->
 <script type="text/javascript"
	src="<?php echo base_url();?>/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<!-- start - This is for export functionality only -->
<script type="text/javascript"
	src="<?php echo base_url();?>assets/plugins/datatables.net/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.flash.min.js"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>assets/plugins/datatables.net/js/jszip.min.js"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>assets/plugins/datatables.net/js/pdfmake.min.js"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>assets/plugins/datatables.net/js/vfs_fonts.js"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.html5.min.js"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.print.min.js"></script>
	
<script src="<?php echo base_url();?>/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
	    <!-- Sweet-Alert  -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 

<script src="<?php echo base_url();?>/assets/plugins/wizard/jquery.steps.min.js"></script>
    <script src="<?php echo base_url();?>/assets/plugins/wizard/jquery.validate.min.js"></script>
    <!-- Sweet-Alert  --><!-- 
    <script src="<?php echo base_url();?>/assets/plugins/sweetalert/sweetalert.min.js"></script> -->
    <script src="<?php echo base_url();?>/assets/plugins/wizard/steps.js?<?php echo time(); ?>"></script>  
    
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
	<?php
if(isset($js_to_load))
{
foreach ($js_to_load as $value) 
{ if($value){?>
     	<script type="text/javascript" src="<?php echo base_url();?>assets/js/<?php echo $value; ?>?<?php echo time(); ?>"></script>
      <?php
}}?>
<?php
}?>

 
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <!-- end - This is for export functionality only -->
    <script type="text/javascript">
        $(document).on('click', '.annual_leave_view ', function(e) {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
        {
            event.preventDefault();
            // window.location = baseURL+"staffs/leave";
            window.location = baseURL+"staffs/leave/listleaves";
        }
        else
        {
            event.preventDefault();
            // window.location = baseURL+"staffs/leave/listleaves";
            window.location = baseURL+"staffs/leave";

        }
    });
    </script>
    <script type="text/javascript">
        $(document).on('click', '.training_view ', function(e) {
                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
                {
                    event.preventDefault();
                    // window.location = baseURL+"staffs/training";
                    window.location = baseURL+"staffs/training/listtraining";
                }
                else
                {
                    event.preventDefault();
                    window.location = baseURL+"staffs/training";
                    // window.location = baseURL+"staffs/training/listtraining";

                }
            });
    </script>
     <script type="text/javascript">
        $(document).on('click', '.timesheet_view ', function(e) {
                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
                {
                    event.preventDefault();
                    window.location = baseURL+"manager/Reports_staff/timesheet";
                }
                else
                {
                    event.preventDefault();
                    window.location = baseURL+"manager/Reports_staff/payrollreport";

                }
            });
    </script>

     <script type="text/javascript">
        $(document).on('click', '.timelog_view ', function(e) {
                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
                {
                    event.preventDefault();
                    window.location = baseURL+"manager/Reports_staff/timelogmobileview";
                }
                else
                {
                    event.preventDefault();
                    window.location = baseURL+"manager/Reports_staff/timelogview";

                }
            });
    </script>

 <script type="text/javascript">
        
     $(document).on('click', '.rota_view ', function(e) {    

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
    {
        //window.location.href = "<?php echo base_url();?>staffs/rota";
        event.preventDefault();
        window.location = baseURL+"staffs/rota/staffviewrota";
          
    }
    else
    {
         //window.location.href = "<?php echo base_url();?>staffs/rota/staffviewrota";
         event.preventDefault();
         window.location = baseURL+"staffs/rota";
         
    }
        
       
     });
 </script>
 <script type="text/javascript">
        $(document).on('click', '.availability_view ', function(e) {    

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
    {
        //window.location.href = "<?php echo base_url();?>staffs/rota";
        event.preventDefault();
        window.location = baseURL+"staffs/rota/staffmanagerota";
          
    }
    else
    {
         //window.location.href = "<?php echo base_url();?>staffs/rota/staffviewrota";
         event.preventDefault();
         window.location = baseURL+"staffs/createrota";
         
    }
        
       
     });
 </script>
 <script type="text/javascript">
        $(document).on('click', '.viewrota_manager ', function(e) {    

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
    {
        //window.location.href = "<?php echo base_url();?>staffs/rota";
        event.preventDefault();
        window.location = baseURL+"manager/rota/managerviewrota";
          
    }
    else
    {
         //window.location.href = "<?php echo base_url();?>staffs/rota/staffviewrota";
         event.preventDefault();
         window.location = baseURL+"manager/rota";
         
    }
        
       
     });
 </script>
 <script type="text/javascript">
        $(document).on('click', '.availability_manager ', function(e) {    

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
    {
        //window.location.href = "<?php echo base_url();?>staffs/rota";
        event.preventDefault();
        window.location = baseURL+"manager/rota/managercreaterota";
          
    }
    else
    {
         //window.location.href = "<?php echo base_url();?>staffs/rota/staffviewrota";
         event.preventDefault();
         window.location = baseURL+"manager/createrota";
         
    }
        
       
     });
 </script>
 
</body>
</html>