<footer class="footer">© 2019</footer>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/waves.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom.min.js"></script> 

<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/moment/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/footable/js/footable.min.js"></script>

<!-- start - This is for export functionality only -->
 <script type="text/javascript" src="<?php echo base_url();?>/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<!-- start - This is for export functionality only -->
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/datatables.net/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/datatables.net/js/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/datatables.net/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/datatables.net/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.print.min.js"></script>    
<script src="<?php echo base_url();?>/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <!-- Sweet-Alert  -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script src="<?php echo base_url();?>/assets/plugins/wizard/jquery.steps.min.js"></script>
    <script src="<?php echo base_url();?>/assets/plugins/wizard/jquery.validate.min.js"></script>
    <!-- Sweet-Alert  --><!-- 
    <script src="<?php echo base_url();?>/assets/plugins/sweetalert/sweetalert.min.js"></script> -->
    <script src="<?php echo base_url();?>/assets/plugins/wizard/steps.js?<?php echo time(); ?>"></script>   

    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/switchery/dist/switchery.min.js"></script>
   <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/dff/dff.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/multiselect/js/jquery.multi-select.js"></script>

    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jquery-asColor/dist/jquery-asColor.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jquery-asGradient/dist/jquery-asGradient.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/select2.js"></script>
    
  <!--   <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/fullcalendar/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/fullcalendar/moment.js"></script> 
    <script stype="text/javascript" src="<?php echo base_url() ?>assets/plugins/fullcalendar/fullcalendar.js"></script>  
    <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/fullcalendar/scheduler.js"></script> -->
 
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script type="text/javascript" src='<?php echo base_url();?>assets/js/popper.min.js'></script>
    <script type="text/javascript" src='<?php echo base_url();?>assets/js/tooltip.min.js'></script>
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
 
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <!-- end - This is for export functionality only -->
     <script type="text/javascript">
        $(document).on('click', '.annual_leave_view ', function(e) {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
        {
            event.preventDefault();
            window.location = baseURL+"manager/leave/listleaves";
        }
        else
        {
            event.preventDefault();
            // window.location = baseURL+"manager/leave/listleaves";
            window.location = baseURL+"manager/leave";

        }
    });
    </script>
    <script type="text/javascript">
        $(document).on('click', '.training_view ', function(e) {
               if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
                {
                    event.preventDefault();
                    window.location = baseURL+"staffs/training/listtraining";
                }
                else
                {
                    event.preventDefault();
                    window.location = baseURL+"staffs/training";

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