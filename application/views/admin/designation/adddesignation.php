<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body"> 
             <div class="row page-titles">
                                                        <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Add Job Role</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/managedesignation/">Manage  Job Role</a></li>
                                                                <li class="breadcrumb-item active">Add  Job Role</li>
                                                            </ol>
                                                        </div>
             </div>

            <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>    

               <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">  
                             <form enctype="multipart/form-data" name="add" id="add"
                            method="post" action="<?php echo base_url('admin/Designation/');?>adddesignation" style="width: 500px;">
                                    <div class="form-group">
                                        <label for="designation"> Job role name</label>
                                        <input type="text" class="designation form-control" name="designation_name" required="required" id="designation" value="<?php echo $this->input->post('designation_name'); ?>"    placeholder="Enter job role name">
                                    </div>  
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="description form-control" id="description" name="description" required="required" placeholder="Enter description"><?php echo $this->input->post('description'); ?></textarea> 
                                    </div>
                                    <div class="form-group">
                                        <label for="designationcode"> Job role code</label>
                                        <input type="text" class="designationcode form-control" name="designation_code" id="designation_code" value="<?php echo $this->input->post('designation_code'); ?>" required="required" placeholder="Enter job role code">
                                    </div>
                                    <div class="form-group">
                                        <label for="jobrole_group">Job role group</label> 
                                        <select required="required" class="form-control" name="jobrole_group" id="jobrole_group" class="jobrole_group">  
                                            <option value="0"> ------Select jobrole group------</option>
                                            <?php
                                            foreach ($jobgroup as $uni) {
                                                ?> 
                                                <option value="<?php echo $uni['id']; ?>"<?php if($this->input->post('jobrole_group')==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['group_name']; ?></option> 
                                           <?php } ?>
                                       </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="avl-request-limit">Availability request limit</label>
                                        <input type="text" class="avl-request-limit form-control" name="avl-request-limit" id="avl-request-limit" value="<?php echo $this->input->post('avl_request_limit'); ?>" required="required" placeholder="Availability request limit">
                                    </div> 
                                  <!--   <div class="form-group">
                                        <label for="part_number">Part Of Number</label> 
                                        <select required="required" class="form-control" name="part_number" id="part_number" class="part_number">  
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>  
                                        </select>
                                    </div> -->
                                    <!--<div class="form-group">
                                         <label for="part_number">Part of number</label> 
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="onoffswitch1" class="onoffswitch-checkbox" id="myonoffswitch1" checked>
                                            <label class="onoffswitch-label" for="myonoffswitch1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label for="Normal">Normal rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="normal form-control" name="normal_rate" id="Normal_rate"   placeholder="Enter normal rate">
                                            </div>
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label for="Overtime">Overtime rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="Overtime form-control" name="overtime_rate" id="overtime_rate"   placeholder="Enter overtime rate"> 
                                            </div>
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div> 
                                    </div>  
                                     <div class="form-group">
                                        <label for="holiday">Holiday rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="holiday form-control" name="holiday_rate" id="holiday_rate"   placeholder="Enter holiday rate"> 
                                            </div>
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div>
                                    </div>  
                                     <div class="form-group">
                                        <label for="sickness">Sickness rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="sickness form-control" name="sickness_rate" id="sickness_rate"   placeholder="Enter sickness rate"> 
                                            </div>
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div>
                                    </div>  
                                     <div class="form-group">
                                        <label for="Maternity">Maternity rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="maternity form-control" name="maternity_rate" id="maternity_rate"   placeholder="Enter maternity rate"> 
                                            </div>
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div>
                                    </div>  
                                     <div class="form-group">
                                        <label for="Authorised">Authorised absence rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="authorised form-control" name="authorised_rate" id="authorised_rate"   placeholder="Enter authorised absence rate">
                                            </div> 
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div>
                                    </div> 
                                     <div class="form-group">
                                        <label for="Unauthorised">Unauthorised absence rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="Unauthorised form-control" name="unauthorised_rate" id="Unauthorised_rate"   placeholder="Enter unauthorised absence rate">
                                             </div>
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div> 
                                    </div>  
                                     <div class="form-group">
                                        <label for="Other">Other rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="Other form-control" name="other_rate" id="other_rate"   placeholder="Enter other rate">
                                            </div> 
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div>
                                    </div>  
                                   
                                    <div class="form-group"  style="width: 200px;">
                                        <select class="form-control" name="status" id="status" class="status">
                                            <option <?php if($this->input->post('status')==1) { ?> selected="selected" <?php } ?> value="1">Active</option>
                                            <option <?php if($this->input->post('status')==2) { ?> selected="selected" <?php } ?> value="2">Inactive</option>
                                        </select>
                                    </div><br>
                                    <button type="button" onclick="location.href='<?php echo base_url('admin/designation/');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Add</button> 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
               </div>
             </div>
    </div>
</div>


