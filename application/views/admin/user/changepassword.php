<div class="page-wrapper">
  <div class="container-fluid"> 
    <div class="card">
      <div class="card-body">  
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor mb-0 mt-0">Change Password</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser/">Manage Users</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                    <li class="breadcrumb-item active"><?php echo $user[0]['fname']." ".$user[0]['lname'];?></li>
                </ol>
            </div>
        </div> 
 
             <div align="center" class="alert alert-error">
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>
            </div>

  <div class="row">
   <div class="col-12">
     <div class="card">
        <div class="card-body"> 

        <form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('admin/user/');?>changepassword/<?php echo $id;?>" >             
        <div class="row">
            <div class="col-6">
                <div class="card">               
                    <div class="card-body">
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wnew_password2"> Enter new password : <span class="danger">*</span>
                                                    </label>
                                                    <input type="password" class="form-control required " id="newpassword" name="newpassword" placeholder="Enter new password"  required="required"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                     
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wconfirm_password2"> Confirm password : <span class="danger">*</span> </label>
                                                    <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm new password "   name="confirmpassword" required="required" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                         
                                         
                                        </section>
                                        
                                       
                         
                    </div>
                </div>
                 <div class="row" style="padding-left: 33px;">
            <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $id;?>">
             <button type="button" onclick="location.href='<?php echo base_url('admin/manageuser');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel" style="width:150px;">Cancel</button>&nbsp&nbsp
             <button type="submit" class="btn btn-success mr-2" style="width:150px;">Submit</button>

        </div>
            </div>  
             <div class="col-md-6">
             <fieldset class="border p-2">
   <legend  class="w-auto">Enable/Disable App Password</legend>
      <div class="row">
               <div class="col-md-12">
                     
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wnew_password2"> Enable App Password: <span class="danger">*</span>
                                                    </label>
                                                    <select class="form-control"  onchange="sendAppPassword(<?php echo $id;?>,'<?php echo $user[0]['fname']." ".$user[0]['lname'];?>');" id="apppassword" name="apppassword">
                                                     <option <?php if($user[0]['pass_enable']==1) { ?>selected="selected"<?php } ?> value="1">Enable</option>
                                                    <option <?php if($user[0]['pass_enable']==null || $user[0]['pass_enable']=='') { ?>selected="selected"<?php } ?>  value="2">Disable</option>
                                                    
                                                    </select>
                                                 </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                     <?php if($user[0]['pass_enable']==1) { ?>
                                                       <label for="app_password2" style="padding-top: 30px;"> App password : <?php echo $user[0]['app_pass']; ?> </label>
                                                     <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                      
                                         
                                         
                           </ >   
                            </div>
        </div>
</fieldset>
             </div>
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
var status_message=<?php print $status_message;?>;  
if(status==1)
{

    swal({
            title: status_message, 
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