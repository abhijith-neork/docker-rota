<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/waves.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom.min.js"></script>
	<?php
if(isset($js_to_load))
{
foreach ($js_to_load as $value) 
     {?>
     	<script type="text/javascript" src="<?php echo base_url();?>assets/js/<?php echo $value; ?>"></script>
      <?php
    }?>
<?php
}?>

 
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>