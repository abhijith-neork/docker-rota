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
<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/footable/js/footable.min.js"></script> -->
 
<!-- <script src="<?php echo base_url();?>/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
 -->	    <!-- Sweet-Alert  -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
 -->
<!--     <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/switchery/dist/switchery.min.js"></script>
 -->   
<!--    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/dff/dff.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script> -->
    
 
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
            //window.location = baseURL+"manager/leave/listleaves";
            window.location = baseURL+"manager/leave";

        }
    });
    </script>
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