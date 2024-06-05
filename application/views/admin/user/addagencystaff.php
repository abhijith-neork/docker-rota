<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
		<div class="card-body">
		<div class="row page-titles">
			<div class="col-md-6 col-8 align-self-center">
				<h3 class="text-themecolor mb-0 mt-0">Add Agency Employee</h3>
				<ol class="breadcrumb">
					<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                    <?php } elseif($this->session->userdata('user_type')>=3) {?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                    <?php }?>
					 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser/">Manage Users</a></li>
					<li class="breadcrumb-item active">Add Agency Employee</li>
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
								action="<?php echo base_url('admin/User/');?>addagencystaff"
								style="width: 50%;">
								<div class="form-group">
									<label for="Firstname">First name</label> <input type="text"
										class="form-control" id="firstname" name="firstname" required="required"
										placeholder="Enter first name" autocomplete="off" value="<?php echo $this->input->post('firstname'); ?>" >
								</div>
								<div class="form-group">
									<label for="Lastname">Last name</label> <input type="text" required="required"
										class="form-control" id="lastname" autocomplete="off" name="lastname" 
										placeholder="Enter last name" value="<?php echo $this->input->post('lastname'); ?>" >
								</div>  
								<div class="form-group">
									<label for="Designation">Job role</label> 
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
								
                                <div class="form-group" style="width: 200px;">
                                                    <label for="wgender2"> Gender : 
                                                    </label>
                                                    <select class="custom-select form-control " id="gender" required="required" 
                                                        name="gender">
                                                        <option value="M" <?php if($this->input->post('gender')=="M"){?>  selected="selected" <?php }?> >Male</option> 
                                                        <option value="F"  <?php if($this->input->post('gender')=="F"){?>  selected="selected" <?php }?> >Female</option>   
                                                    </select>
                                </div>
                                           
								<br>
								<button type="submit" class="btn btn-success mr-2">Submit</button>
								<button type="button" onclick="location.href='<?php echo base_url('admin/user/');?>';" 
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