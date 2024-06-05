<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon --> 
        <?php $site_title=getSitetitle();?>
        <title><?php echo $site_title[0]['company_name'];?></title>
        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css">
    <?php 
    if(strpos(base_url(), 'erota') !== false){
    
    ?>
    <link rel="stylesheet" type="text/css" id="theme"  href="<?php echo base_url();?>assets/css/colors/blue.css">
	<?php } { ?>

    <link rel="stylesheet" type="text/css" id="theme"  href="<?php echo base_url();?>assets/css/colors/default.css">
    <?php } ?>
        <?php
if(isset($css_to_load))
{
foreach ($css_to_load as $value) 
     {?>
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/<?php echo $value; ?>"> 
      <?php
    }?>
<?php
}
?> 
    </head>
   </html>