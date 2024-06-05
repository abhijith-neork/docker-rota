<section id="wrapper">
        <div class="login-register" style="background-image:url(<?php echo base_url();?>/assets/images/background/login.jpeg);">        
            <div class="login-box card">
            <div class="card-body">
          
            <div align="center" class="alert alert-error">
             <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>
                          
                </div>
                <form method="POST" class="form-horizontal form-material" id="loginform" action="<?php echo base_url();?>resetpassword">
                    <h3 class="box-title mb-3">Reset Password</h3>
                    <div class="form-group ">
                        <div class="col-xs-12"> 
                                    <label>New password</label>
                                    <input type="password" class="form-control"   name="newpassword" id="newpassword" placeholder="Enter your new password (alphanumeric)" value="" maxlength="40"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Confirm password</label>
                                    <input type="password" class="form-control"   name="confirmpassword" id="confirmpassword" placeholder="Repeat your new password" value="" maxlength="40">
                            </div>
                    </div>
                    <div class="form-group">
                       
                    </div>
                    <div class="form-group">
                       <!--   <input type="hidden" name="terms_and_conditions" id="terms_and_conditions" value="0" />   -->
                        <input type="checkbox" name="terms_and_conditions" id="terms_and_conditions" value="1"> <a href="<?php echo base_url();?>Users/terms_and_condition">Accept terms & condition.</a></div>
                    <div class="form-group text-center mt-3">
                        <div class="col-xs-12"> 
                             <input type="hidden" name="token" id="token" value="<?php echo $user['key'];?>">
                             <input type="hidden" name="user_id" id="user_id" value="<?php echo $user['user_id'];?>">
                             <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light " type="submit" id="resetpassword" name="resetpassword" class="primary_btn view btnsubmit" >Update</button><br><br>
                        </div>
                    </div>  
                </form> 
            </div>
          </div>
        </div>    
    </section>



<!-- <script>
	$(document).ready(function () {
		$('input').tooltip({
			  placement: 'right'
			  ,trigger: 'focus'
		 });
	    $('#frm-resetpassword').validate({ // initialize the plugin
	        rules: {
	            newpassword: {
	                required: true,
	                minlength:6
	            },
	            confirm: { 
	                required: true,
	                equalTo: '#newpassword'
	            }
	        }
	    });
	
	});
</script>
 -->