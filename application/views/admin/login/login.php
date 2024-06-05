<section id="wrapper">
        <div class="login-register" style="background-image:url(<?php echo base_url();?>/assets/images/background/login.jpeg);">        
            <div class="login-box card">
            <div class="card-body">
          
                <div align="center" class="alert alert-error">
                    <?php 
           
            if (isset($error)): ?>
               <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
                    <?php echo $error; ?>
                </p>
            <?php endif; 
                   ?>
                   <?php if($this->session->flashdata('message')):?>
            <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
              <?php echo $this->session->flashdata('message');?>
            </p>
            <?php endif;?>  

                  <!--  <?php if($this->session->flashdata('Successfully')):?>
            <p class="success-msg" id="success-alert" style="color: red; font-size: 16px; text-align: center;">
            
            </p>
            <?php endif;?> -->
         <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
             <?php echo validation_errors(); ?>
             </p>
                </div>
                <form method="POST" class="form-horizontal form-material" id="loginform" action="<?php echo base_url();?>adminlogin">
                    <h3 class="box-title mb-3">Sign In</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" name="email" id="email" type="email" required="" placeholder="Email id"> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" id="password" name="password" type="password" required="" placeholder="Password"> </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary float-left pt-0">
                        
                            </div> <a href="<?php echo base_url();?>forgotpassword" title="forgot password"  ><i class="fa fa-lock mr-1"></i> Forgot Password?</a> 
                        </div>
                    </div>
                    <div class="form-group text-center mt-3">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>  
                </form> 
            </div>
          </div>
        </div>
        
    </section>