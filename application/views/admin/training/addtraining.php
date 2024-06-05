 <div class="page-wrapper">
    <div class="container-fluid"> 
    <div class="card">
    <div class="card-body"> 
             <div class="row page-titles">
                                                        <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Add Training</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/training/">Manage Training</a></li>
                                                                <li class="breadcrumb-item active">Add Training</li>
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
                             <form enctype="multipart/form-data" name="add" id="add"
                            method="post" action="<?php echo base_url('admin/Training/');?>Sendtraining" style="width: 100%;">
                                    <div class="form-group">
                                        <label for="training_title">Training title</label> 
                                        <select class="training_title form-control custom-select " id="training_title"
                                            name="training_title"  placeholder="Select training title" >
                                            <option value=""><?php echo "------Select training title-------"?></option>
                                                            <?php
                                                            foreach ($training as $uni) {
                                                                ?>  
                                                                 <option value="<?php echo $uni['id']; ?>"<?php if($this->input->post('training_title')==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['title']; ?></option>
 
                                                            <?php } ?>
                                                </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Others</label>
                                        <input type="text" class="title form-control" name="title" id="title" autocomplete="off" required="required" value="<?php echo $this->input->post('title'); ?>" placeholder="Enter title"> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="description form-control" id="description" name="description"  required="required"  placeholder="Enter description"><?php echo $this->input->post('description'); ?> </textarea> 
                                    </div>  
                                     <div class="row">
                                           <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wfromdate2">From date:<span class="danger">*</span></label> 

                                                    <input type="text" class="form-control required" required="required" name="fromdate" id="from-datepicker" value="<?php echo $this->input->post('fromdate'); ?>" > 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wtodate2">To date :</label>
                                                    <input type="text" class="form-control required" required="required" name="todate" id="to-datepicker" value="<?php echo $this->input->post('todate'); ?>" > 
                                                </div>
                                            </div>
                                        </div>
                                    <div class="row">
                                            <div class="col-md-4"> 
                                                <div class="form-group">
                                                    <label for="wstart_time2">Start time</label>  
                                                    <p><h7>Hours:</h7></p>
                                                    <select class="custom-select form-control required" id="wstart_time2" name="wstart_time2">
                                                       <?php $i='';$n=24;
                                                        for($i=1;$i<=$n;$i++) { if($i<10){?>
 

                                                         <option value="<?php echo '0'.$i;?>"<?php if($this->input->post('wstart_time2')=='0'.$i){?> selected="selected" <?php }?>><?php echo $i;?></option> <?php } else {?>
                                                         <option value="<?php echo $i;?>"<?php if($this->input->post('wstart_time2')==$i){?> selected="selected" <?php }?>><?php echo $i;?></option><?php } ?>
                                                        <?php } ?>
                                                     </select>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                <div class="form-group"> 
                                                <label for="wstart_time12"><br></label>   
                                                    <p><h7>Minutes:</h7></p>
                                                    <select class="custom-select form-control required" id="wstart_time12" name="wstart_time12">
                                                    <option value="00"<?php if($this->input->post('wstart_time12')=="00"){?> selected="selected" <?php }?>>00</option>
                                                    <option value="15"<?php if($this->input->post('wstart_time12')=="15"){?> selected="selected" <?php }?>>15</option>
                                                    <option value="30"<?php if($this->input->post('wstart_time12')=="30"){?> selected="selected" <?php }?>>30</option>
                                                    <option value="45"<?php if($this->input->post('wstart_time12')=="45"){?> selected="selected" <?php }?>>45</option>
                                                     </select>
                                                </div>
                                            </div>
                                    </div>
                                     <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wend_time2">End time</label>  
                                                    <p><h7>Hours:</h7></p>
                                                    <select class="custom-select form-control required" id="wend_time2" name="wend_time2">
                                                         <?php $i='';$n=24;
                                                        for($i=1;$i<=$n;$i++) { if($i<10){?>
 

                                                         <option value="<?php echo '0'.$i;?>"<?php if($this->input->post('wend_time2')=='0'.$i){?> selected="selected" <?php }?>><?php echo $i;?></option> <?php } else {?>
                                                         <option value="<?php echo $i;?>"<?php if($this->input->post('wend_time2')==$i){?> selected="selected" <?php }?>><?php echo $i;?></option><?php } ?>
                                                        <?php } ?>
                                                     </select>  
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                <div class="form-group"> 
                                                <label for="wend_time12"><br></label>   
                                                    <p><h7>Minutes:</h7></p>
                                                    <select class="custom-select form-control required" id="wend_time12" name="wend_time12">
                                                    <option value="00"<?php if($this->input->post('wstart_time12')=="00"){?> selected="selected" <?php }?>>00</option>
                                                    <option value="15"<?php if($this->input->post('wstart_time12')=="15"){?> selected="selected" <?php }?>>15</option>
                                                    <option value="30"<?php if($this->input->post('wstart_time12')=="30"){?> selected="selected" <?php }?>>30</option>
                                                    <option value="45"<?php if($this->input->post('wstart_time12')=="45"){?> selected="selected" <?php }?>>45</option>
                                                     </select>
                                                </div>
                                            </div> 
                                    </div>
                                    <div class="form-group">
                                        <label for="training_hour">Training hour</label>
                                        <input type="text" class="training_hour form-control" name="training_hour" id="training_hour" value="<?php  echo $this->input->post('training_hour');?>" required="required" placeholder="hh:mm"> 
                                    </div> 
                                    <div class="form-group">
                                    <label for="Location">Unit</label> 
                                        <select required="required" class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select unit">
                                        <option value="none"><?php echo "------Select unit-------"?></option>
                                        <?php foreach($locationunit as $cl) { ?>
                                            <option value="<?php echo $cl['id']; ?>"<?php if($this->input->post('unitdata')==$cl['id']){?> selected="selected" <?php }?>><?php echo $cl['unit_name']; ?></option>
                                        <?php } ?>
                                        </select> 
                                    </div>
                                     <div class="form-group">
                                        <label for="training_unit">Place</label>
                                        <input type="text" class="training_unit form-control" name="training_unit" id="training_unit" value="<?php  echo $this->input->post('training_unit');?>" required="required" placeholder="Enter unit"> 
                                    </div> 

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="address form-control" id="address" name="address" required="required"  placeholder="Enter address"><?php  echo $this->input->post('address');?></textarea> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="point_of_person">Point of contact</label>
                                        <input type="text" class="point_of_person form-control" required="required" name="point_of_person" id="point_of_person"  placeholder="Enter point of contact" value="<?php  echo $this->input->post('point_of_person');?>"> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="contact_number">Contact number</label>
                                        <input type="text" class="contact_number form-control" required="required" value="<?php  echo $this->input->post('contact_number');?>" name="contact_number" id="contact_number" placeholder="Enter contact number"> 
                                    </div>  
                                     <div class="form-group">
                                        <label for="contact_email">Contact email</label>
                                        <input type="text" class="contact_email form-control" required="required" value="<?php  echo $this->input->post('contact_email');?>" name="contact_email" id="contact_email"  placeholder="Enter contact email"> 
                                    </div>  
                                    <div class="form-group"  style="width: 200px;">
                                        <select class="form-control" name="status" id="status" class="status">
                                            <option <?php if($this->input->post('status')==1) { ?> selected="selected" <?php } ?> value="1">Active</option>
                                            <option <?php if($this->input->post('status')==2) { ?> selected="selected" <?php } ?> value="2">Inactive</option>
                                        </select>
                                    </div><br>
                                    <button type="button" onclick="location.href='<?php echo base_url('admin/training/');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Add</button>
                                    <div class="form-group user-list" style="display: none;">
                                        <label></label>
                                        <label for="users">Selected Users</label> 
                                        <input type="hidden" id="selected_users" class="selected_users" name="selected_users[]" value="" />
                                        <div class="table-responsive m-t-40">
                                        <table id="selected-users" class="table table-bordered table-striped selected-users-table">
                                            <tbody class='selected-user-data'>
                                                <tr></tr>
                                            </tbody>
                                        </table></div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6"> 
                        <div class="row">
                        <div class="col-12">
                                <div class="card">
                                <div class="card-body" style="padding-top: 9px;">
                                
                                <h5 class="mt-3">Select unit</h5>

                                <select class="select2 mb-2 select2-multiple" id="unit" name="unit[]" style="width: 100%" multiple="multiple" data-placeholder="Choose">
                                    <optgroup label="Units" class="units" id="units" name="unit[]">
                                        <?php
                                            foreach ($unit as $uni) {
                                        ?>    
                                        <option class='sl-unit' data-id="<?php echo $uni['id']; ?>" text="<?php echo $uni['parent_unit'];?>" data-parent="<?php echo $uni['parent_unit'];?>" value="<?php echo $uni['id']; ?>" ><?php echo $uni['unit_name']; ?></option>  
                                        <?php } ?>  
                                    </optgroup>
                                 
                    
                                </select>
                                </div>
                                </div>
                        </div>
                        <div class="col-12">
                                <div class="card">
                                   <!--  <h5>User List</h5> -->
                                    <div class="card-body"><p id="selectTriggerFilter"></p>
                                    <div class="table-responsive m-t-40"  style="padding: 1px;">
                                        <table id="myTable1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                    
                                                    <th id="emp" style="border:1px solid #dee2e6">Employee</th>
                                                    <th id="des" style="border:1px solid #dee2e6">Job Role</th>  
                                                    <th style="border:1px solid #dee2e6"><input type="checkbox"  name="selectall" class="selectall" id="selectall"> Select all</th>
                                               </tr>
                                            </thead>
                                        </table>
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
<script type="text/javascript">
    var unit_array=<?php print_r($unit_array);?>;
</script>

