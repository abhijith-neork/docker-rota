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
         <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
             <?php echo validation_errors(); ?>
             </p>
                </div>
                
                <form method="POST" class="form-horizontal form-material" id="loginform" action="<?php echo base_url();?>forgotpassword">
                    <div class="col-xs-12">
                            <h3>Recover Password</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" name="email" id="email" type="email" required="" placeholder="Email"> </div>
                    </div>
                    
                    <div class="form-group text-center mt-3">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>  
                </form> 
            </div>
          </div>
        </div>   
    </section>
