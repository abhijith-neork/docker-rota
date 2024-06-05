<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body"> 
    	        <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Edit  Job Role</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                             <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                             <?php } elseif($this->session->userdata('user_type')>=3) {?>
                             <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                             <?php }?>
                            <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/managedesignation/">Manage  Job Role</a></li>
                            <li class="breadcrumb-item active">Edit  Job Role</li>
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
                            method="post" action="<?php echo base_url('admin/Designation/');?>Editdesignation" style="width: 500px;">
                                    <div class="form-group">
                                        <label for="designation"> Job role name</label>
                                        <input type="text" class="designation_name form-control" name="designation_name" id="designation_name" required="required"   value="<?php echo $designation[0]['designation_name'];?>" placeholder="Enter job role name"> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                         
                                        <textarea class="description form-control" id="description" name="description" required="required"  placeholder="Enter description"><?php echo html_entity_decode($designation[0]['description']);?></textarea>
                                    </div> 
                                    <div class="form-group">
                                        <label for="designationcode"> Job role code</label>
                                        <input type="text" class="designationcode form-control" name="designation_code" id="designation_code"  required="required" placeholder="Enter job role code" value="<?php echo $designation[0]['designation_code'];?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="jobrole_group">Job role group</label> 
                                        <select required="required" class="form-control" name="jobrole_group" id="jobrole_group" class="jobrole_group">  
                                            <option value="0"> ------Select jobrole group------</option>
                                            <?php
                                            foreach ($jobgroup as $uni) {
                                                ?> 
                                                <option value="<?php echo $uni['id']; ?>"<?php if($designation[0]['jobrole_groupid']==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['group_name']; ?></option> 
                                           <?php } ?>
                                       </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="avl-request-limit">Availability request limit</label>
                                        <input type="text" class="avl-request-limit form-control" name="avl-request-limit" id="avl-request-limit" value="<?php echo $designation[0]['availability_requests']; ?>" required="required" placeholder="Availability request limit">
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="part_number">Part of number</label> 
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="onoffswitch1" class="onoffswitch-checkbox" id="myonoffswitch1" <?php
                                                if ($designation[0]['part_number'] != 1) {
                                                    echo "checked";
                                                }
                                                ?>>
                                            <label class="onoffswitch-label" for="myonoffswitch1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="Normal">Normal rate</label>
                                        <div class="row">
                                            <div class="col-md-11">
                                            <input type="text" class="normal form-control" name="normal_rate" id="Normal_rate"  placeholder="Enter normal rate" value="<?php echo $designation[0]['normal_rates'];?>">
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
                                            <input type="text" class="Overtime form-control" name="overtime_rate" id="overtime_rate"  placeholder="Enter overtime rate" value="<?php echo $designation[0]['overtime_rate'];?>"> 
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
                                            <input type="text" class="holiday form-control" name="holiday_rate" id="holiday_rate"   placeholder="Enter holiday rate" value="<?php echo $designation[0]['holiday_rate'];?>"> 
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
                                            <input type="text" class="sickness form-control" name="sickness_rate" id="sickness_rate"  placeholder="Enter sickness rate" value="<?php echo $designation[0]['sickness_rate'];?>"> 
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
                                            <input type="text" class="maternity form-control" name="maternity_rate" id="maternity_rate"   placeholder="Enter maternity rate" value="<?php echo $designation[0]['maternity_rate'];?>"> 
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
                                            <input type="text" class="authorised form-control" name="authorised_rate" id="authorised_rate"   placeholder="Enter authorised absence rate" value="<?php echo $designation[0]['authorised_absence_rate'];?>">
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
                                            <input type="text" class="Unauthorised form-control" name="unauthorised_rate" id="Unauthorised_rate"   placeholder="Enter unauthorised absence rate" value="<?php echo $designation[0]['unauthorised_absence_rate'];?>">
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
                                            <input type="text" class="Other form-control" name="other_rate" id="other_rate"   placeholder="Enter other rate" value="<?php echo $designation[0]['other_rates'];?>">
                                            </div> 
                                            <div class="col-md-1">
                                                
                                               <span style="float: right;padding-top: 8px;">/hour</span>
                                            </div>
                                        </div>
                                    </div>   
                                   
                                    <div class="form-group"  style="width: 200px;">
                                        <select class="form-control" name="status" id="status" class="status">
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div><br>
                                    <input type="hidden" name="id" id="id" value="<?php echo $designation[0]['id'];?>">
                                    <input type="hidden" name="userid" id="userid" value="<?php print_r($this->session->userdata('user_id')); ?>">
                                     <button type="button" onclick="location.href='<?php echo base_url('admin/designation/');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Update</button>  
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>