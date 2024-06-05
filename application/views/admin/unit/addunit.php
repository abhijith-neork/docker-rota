<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<div class="row page-titles">
					<div class="col-md-6 col-8 align-self-center">
						<h3 class="text-themecolor mb-0 mt-0">Add Unit</h3>
						<ol class="breadcrumb">
							<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
							<li class="breadcrumb-item active"><a
								href="<?php echo base_url();?>admin/manageunit/">Manage Unit</a></li>
							<li class="breadcrumb-item active">Add Unit</li>
						</ol>
					</div>
				</div>
            <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>
                <div class="row page-titles">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">  
                            		<form enctype="multipart/form-data" name="add" id="add" class="add"
                            method="post" action="<?php echo base_url('admin/Unit/');?>addunit" style="width: 100%;">
                                    <div class="form-group">
										<label for="unit_name">Unit name</label> <input type="text"
											class="form-control" id="unit_name" name="unit_name"
											placeholder="Enter unit name" required="required" value="<?php echo $this->input->post('unit_name'); ?>" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="unit_type">Unit type</label> <select
											required="required" class="form-control" name="unit_type"
											id="unit_type" class="unit_type"> 
							                <?php
							                foreach ($unittype as $uni) {
							                    ?>  
							                <option value="<?php echo $uni['id']; ?>"<?php if($this->input->post('unit_type')==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['name']; ?></option>
							               <?php } ?>
							                </select>
									</div>
									<div class="form-group">
										<label for="unit_type">Parent unit</label> 
										<select  class="form-control" name="parent_unit"
											id="parent_unit" class="parent_unit"> 
											<option value="0"> ------Select parent unit------</option>
											<?php
							                foreach ($parent_unit as $uni) {
							                    ?> 
							                    <option value="<?php echo $uni['id']; ?>"<?php if($this->input->post('parent_unit')==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['unit_name']; ?></option> 
							               <?php } ?>
							           </select>
									</div>

									<div class="form-group">
										<label for="unit_shortname">Unit short name</label> <input
											type="text" class="form-control" name="unit_shortname"
											id="unit_shortname" placeholder="Enter unit short name"
											required="required" value="<?php echo $this->input->post('unit_shortname'); ?>" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="address">Address</label>
										<textarea class="address form-control" id="address"
											name="address" required="required"
											placeholder="Enter address" autocomplete="off"><?php echo $this->input->post('address'); ?></textarea>
									</div>
									<div class="form-group">
										<label for="phone_number">Phone number</label>
										<input type="text" class="form-control" name="phone_number"
											id="phone_number" placeholder="Enter phone number"
											required="required" value="<?php echo $this->input->post('phone_number'); ?>" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="staff_limit">Staff limit</label> <input
											type="text" class="form-control" name="staff_limit"
											id="staff_limit" placeholder="Enter staff limit"
											required="required" value="<?php echo $this->input->post('staff_limit'); ?>" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="max_patients">Maximum patients</label> <input
											type="text" class="form-control" name="max_patients"
											id="max_patients" placeholder="Enter maximum patients"
											required="required" value="<?php echo $this->input->post('max_patients'); ?>" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="number_of_beds">Number of beds</label> <input
											type="text" class="form-control" name="number_of_beds"
											id="number_of_beds" placeholder="Enter number of beds"
											required="required" value="<?php echo $this->input->post('number_of_beds'); ?>" autocomplete="off">
									</div>
									<div class="form-group">
										<label for="description">Description</label>
										<textarea class="description form-control" id="description"
											name="description" required="required"
											placeholder="Enter description" autocomplete="off"><?php echo $this->input->post('description'); ?></textarea>
									</div>
									<div class="form-group"> 
			                            <div class="example">
			                                <h5 class="box-title">Select color</h5> 
			                                   <input type="text" name="color" id="color" value="<?php echo $this->input->post('color'); ?>" class="complex-colorpicker form-control" value="#ffffff" /> 
			                            </div> 
			                        </div>
									<div class="form-group" style="width: 200px;">
										<select class="form-control" name="status" id="status"
											class="status">
											<option <?php if($this->input->post('status')==1) { ?>
												selected="selected" <?php } ?> value="1">Active</option>
											<option <?php if($this->input->post('status')==2) { ?>
												selected="selected" <?php } ?> value="2">Inactive</option>
										</select>
									</div> 
                                   <button type="button" onclick="location.href='<?php echo base_url('admin/unit/');?>';" class="btn waves-effect waves-light btn-secondary" id="cancel"> Cancel</button>&nbsp;
									<button type="submit" class="btn btn-primary" onclick="submitTwo()" id="try">Add</button> 
                            </div>
                        </div>
                    </div>
                    <div class="col-6"> 
                        <div class="row">
                        	<div class="col-12">
                            	<div class="card">
									<div class="card-body"> 
										<div class="card-body-title"> 
                                 			<h4 style="padding-left: 120px;"> Maximum number of leaves</h4>
                                			<div class="form-group"> 
		                            		<?php foreach ($designation as $desig) { ?>
		                           			 <div class="row">
                                 				<div class="col-md-6">
		                                   		<label for="$desig['designation_name']"><?php echo $desig['designation_name'];?>:</label>
		                                		</div>
                                        		<div class="col-md-6">
                                           		<input type="hidden" name="designation_id[]" id="designation_id" value="<?php echo $desig['id'];?>">
                                           		Days:<input class="form-control" type="text" name="max_leave[]" id="max_leave" value="" autocomplete="off">
                                           		<!-- Hours:<input class="form-control" type="text" name="max_leave_hours[]" id="max_leave_hours" value="" autocomplete="off"> -->
                                        		</div><br><br>
                                   			 </div>
                                    		<?php } ?>
                                			</div>
										</div>
					        		</div>
					        	</div>
                        	</div>
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

