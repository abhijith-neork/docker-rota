<div class="page-wrapper">
  <div class="container-fluid"> 
    <div class="card">
      <div class="card-body">  
		<div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor mb-0 mt-0">Change Password</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </div>
        </div> 

             <?php if($this->session->flashdata('message')):?>
                            <p class="success-msg" id="success-alert" style="color: green; text-align:center; ">
                                <?php echo $this->session->flashdata('message');?>
                            </p>
                        <?php endif;?> 
                          <?php if($error):?>
            <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
              <?php echo $error;?>
            </p>
            <?php endif;?> 


  <div class="row">
   <div class="col-12">
     <div class="card">
        <div class="card-body"> 

        <form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('staffs/Password/');?>index" >  

         
           
        <div class="row">
            <div class="col-12">
                <div class="card">               
                    <div class="card-body">
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wnew_password2"> Enter new password : <span class="danger">*</span>
                                                    </label>
                                                    <input type="password" class="form-control required " id="new_password" name="new_password" placeholder="Enter new password" required="required" > 
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
                                                    <input type="password" class="form-control" id="confirm_password" placeholder="Confirm new password "  required="required" name="confirm_password" >
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
            </div>  
        </div>

        <div class="row" style="padding-left: 33px;">
            
            <button type="submit" class="btn btn-success mr-2">Submit</button>
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
 