<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
		<div class="card-body">
		<div class="row page-titles">
			<div class="col-md-6 col-8 align-self-center">
				<h3 class="text-themecolor mb-0 mt-0">Add User</h3>
				<ol class="breadcrumb">
					<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                    <?php } elseif($this->session->userdata('user_type')>=3) {?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                    <?php }?>
					<li class="breadcrumb-item"><a
						href="<?php echo base_url();?>admin/manageuser/">Manage Users</a></li>
					<li class="breadcrumb-item active">Add User</li>
				</ol>
			</div>
		</div>
		 <!-- <?php if($this->session->flashdata('message')):?>
            <p class="success-msg" id="success-alert"
					style="color: green;">
              <?php echo $this->session->flashdata('message');?>
            </p>
            <?php endif;?> 
              <?php if($error):?>
            <p class="success-msg" id="success-alert"
					style="color: red; text-align: center;">
              <?php echo $error;?>
            </p>
            <?php endif;?>  -->
		   
		   <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>
                          
		<div class="row">
			<div class="col-12">
				<div class="card card-body">
					<h5 class="card-subtitle"></h5>
					<div class="row">
						<div class="col-sm-12 col-xs-12">
							<form enctype="multipart/form-data" name="add" id="add"
								method="post"
								action="<?php echo base_url('admin/User/');?>adduser"
								style="width: 50%;">
								<div class="form-group">
									<label for="Firstname">First name : <span class="danger">*</span>
                                                    </label></label> <input type="text"
										class="form-control" id="firstname" name="firstname" autocomplete="off" required="required"
										placeholder="Enter first name" value="<?php echo $this->input->post('firstname'); ?>" >
								</div>
								<div class="form-group">
									
									<label for="Lastname">Last name : <span class="danger">*</span>
                                                    </label></label> <input type="text"
										class="form-control" id="lastname" name="lastname" autocomplete="off" required="required"
										placeholder="Enter last name" value="<?php echo $this->input->post('lastname'); ?>" >
								</div>
								<div class="form-group">
									<label for="Email">Email address : <span class="danger">*</span>
                                                    </label></label> <input type="email"
										class="form-control" id="email" name="email" required="required"
										placeholder="Enter email address" autocomplete="off"  value="<?php echo $this->input->post('email'); ?>">
								</div>
								
								<div class="form-group">
									<label for="Location">Unit : <span class="danger">*</span>
                                                    </label></label> 
								 <select class="unit form-control custom-select" id="unit" name="unit" required="required" placeholder="Select Unit">
										<option value=""><?php echo "Select unit"?></option>
                                                       <?php foreach($categoryList as $cl) { ?>
                                                       	<option value="<?php echo $cl['id']; ?>"<?php if($this->input->post('unit')==$cl['id']){?> selected="selected" <?php }?>><?php echo $cl['unit_name']; ?></option>
                 
                                                       <?php } ?>
                                 </select> 
								</div>
								<div class="form-group">
									<label for="Designation">Job role : <span class="danger">*</span>
                                                    </label></label> 
									<select class="form-control custom-select required" id="designation" required="required"
										name="designation"  placeholder="Select job role"
										>
										<option value=""><?php echo "Select job role"?></option>
                                                        <?php
                                                        foreach ($designation as $uni) {
                                                            ?>  
                                                            <option value="<?php echo $uni['id']; ?>"<?php if($this->input->post('designation')==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['designation_name']; ?></option>
                                                        <?php } ?>
                                            </select>
								</div>
								<div class="form-group">
									<label for="Paymenttype">Payment type : <span class="danger">*</span>
                                                    </label></label> 
									<select class="form-control custom-select" id="paymenttype" required="required" name="paymenttype" placeholder="Select paymenttype">
										<option value=""><?php echo "Select payment type"?></option>
                                                        <?php
                                                        foreach ($paymenttype as $uni) {
                                                            ?> 
                                                            <option value="<?php echo $uni['id']; ?>"<?php if($this->input->post('paymenttype')==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['payment_type']; ?></option> 
                                                        <?php } ?>
                                            </select>
								</div> 
								<br>
								<button type="submit" class="btn btn-success mr-2">Submit</button>
								<button type="button"
										onclick="location.href='<?php echo base_url('admin/user/');?>';"
										class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>
									&nbsp;
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	    </div>
        </div>
    </div>
</div>