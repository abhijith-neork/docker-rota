<div class="page-wrapper">
  <div class="container-fluid"> 
    <div class="card">
      <div class="card-body">  
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor mb-0 mt-0">Change Profile Picture</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser/">Manage Users</a></li>
                    <li class="breadcrumb-item active">Change Profile Picture</li>
                    <li class="breadcrumb-item active"><?php echo $user[0]['fname']." ".$user[0]['lname'];?></li>
                </ol>
            </div>
        </div> 

             <div align="center" class="alert alert-error">
             <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>
            </div>

  <div class="row">
   <div class="col-12">
     <div class="card">
        <div class="card-body"> 

        <form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('admin/user/');?>photoupload/<?php echo $id;?>" >  

         
           
            <div class="row">
                   
                    <div class="col-md-6">
                                <h4 class="card-title">Profile picture</h4>
                                <label for="input-file-max-fs">You can add a max file size</label>
                                <?php if($user[0]['profile_image']==''){ ?>
                                <input type="file" id="input-file-max-fs" class="dropify" name="image" data-max-file-size="2M" data-default-file="<?php echo base_url('./uploads/').'default.png';?>" />
                                <?php } else {?>
                                <input type="file" id="input-file-max-fs" class="dropify" name="image" data-max-file-size="2M" data-default-file="<?php echo base_url('./uploads/').$user[0]['profile_image'];?>" />
                                <?php }?>
                                
                    </div> 
                    <div class="col-md-6">
                        <!-- <div class="card">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $unit[0]['unit_name'];?></h3>
                                <p class="card-text"><?php  echo $unit[0]['address'];?>.</p>
                                <p class="card-text"><?php echo $unit[0]['phone_number'];?>.</p> 
                            </div>
                        </div> -->
                    </div>
            </div><br>
   
         

            <div class="row" style="padding-left: 33px;">
                <button type="button" onclick="location.href='<?php echo base_url('admin/manageuser');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel" style="width:150px;">Cancel</button>&nbsp&nbsp
                <button type="submit" class="btn btn-success mr-2" style="width:150px;">Submit</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/sweetalert.min.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script type="text/javascript"> 
var status=<?php print $status;?>;   
if(status==1)
{

    swal({
            title: "Photo updated successfully", 
            confirmButtonColor: '#55ce63',
            confirmButtonText: 'Ok',
            closeOnConfirm: true,
         },
         function(isConfirm){

           if (isConfirm){
               event.preventDefault();
                window.close();
            } 
         });
 
}
</script> 