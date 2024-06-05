<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
        		<div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Edit Company Details</h3>
                        <ol class="breadcrumb">
                         <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                         <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                         <?php } elseif($this->session->userdata('user_type')>=3) {?>
                         <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                         <?php }?>
                            <li class="breadcrumb-item active">Update Company</li>
                        </ol>
                    </div>
                </div>
                <div align="center" class="alert alert-error">
                    <?php if($this->session->flashdata('message')){?>
                        <p class="success-msg" id="success-alert" style="color: green; font-size: 16px; text-align: center;">
                             <?php echo $this->session->flashdata('message');?>
                        </p>
                    <?php }?> 
                </div>    
                <div class="row">
                    <div class="col-12">
                        <div class="card">               
                            <div class="card-body">
                                <section>
                                    <?php if( $error!=''){?>
                                    <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
                                    <?php } ?>
                                    <?php if (validation_errors()){?>
                                    <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
                                    <?php } ?>    
                                    <form enctype="multipart/form-data" name="add" id="add"
                                method="post" action="<?php echo base_url('admin/company/');?>index">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Company Name">Company name <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" id="company_name" name="companyName" value="<?php echo $values[0]['company_name'];?>" required="required"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Address1">Address 1 <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" id="address_1" name="address1" value="<?php echo $values[0]['Address1'];?>" required="required"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Industry">Industry <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" id="industry" name="industry" value="<?php echo $values[0]['industry'];?>" required="required"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                               <div class="form-group">
                                                    <label for="Address2">Address 2 <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" id="address_1" name="address2" value="<?php echo $values[0]['Address2'];?>" required="required"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Timezone">Timezone <span class="danger">*</span></label>
                                                    <select   class="form-control custom-select" id="timezone" name="timezone" placeholder="Select timezone">
                                                        <!-- <option>------Select Timezone-------</option> -->
                                                        <option value="BST" >BST(UTC+1)</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                               <div class="form-group">
                                                    <label for="wcity2"> City <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" id="city" name="city" required="required" value="<?php echo $values[0]['city'];?>"> 
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Currency">Phone <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" id="currency" name="currency" value="<?php echo $values[0]['currency'];?>" required="required"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                               <div class="form-group">
                                                    <label for="Country">Country <span class="danger">*</span></label>
                                                    <select  required="required" class="custom-select form-control " id="country"
                                                                    name="country">
                                                        <option value="uk">UK</option> 
                                                    </select>   
                                                </div>
                                                <div class="form-group">
                                                    <label for="Country">Postcode <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" id="postcode" name="postcode" value="<?php echo $values[0]['postcode'];?>"  required="required"> 
                                                </div> 
                                            </div>
                                        </div>
                                        <fieldset> <legend>Global Settings:</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Shift_gap">Gap between shifts <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" id="shift_gap" name="shift_gap" value="<?php echo $values[0]['shift_gap'];?>"  required="required"> 
                                                </div> 
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Reply email  <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" required="required" id="email" name="email" value="<?php echo $values[0]['from_email'];?>"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="late_notify">Late Notification <span class="danger">*</span></label>
                                                    <input type="text" class="form-control" required="required" id="late_notify" name="late_notify" value="<?php echo $values[0]['late_notify'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                        </fieldset><br>
                                        <button type="submit" class="btn btn-primary" id="try">Update</button>  
                                    </form>
                                </section>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
	</div>
</div>