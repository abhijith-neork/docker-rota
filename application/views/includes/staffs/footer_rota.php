<footer class="footer">© 2019</footer>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery-migrate-1.0.0.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/waves.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom.min.js"></script> 



<script type="text/javascript"
	src="<?php echo base_url();?>assets/plugins/moment/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/footable/js/footable.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>
  
  <!--   <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/fullcalendar/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/fullcalendar/moment.js"></script> 
    <script stype="text/javascript" src="<?php echo base_url() ?>assets/plugins/fullcalendar/fullcalendar.js"></script>  
    <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/fullcalendar/scheduler.js"></script> -->
 
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
 
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
    <!-- end - This is for export functionality only -->
    <script type="text/javascript">
        $(document).on('click', '.annual_leave_view ', function(e) {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
        {
            event.preventDefault();
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
 
 
</body>
</html>