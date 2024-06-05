 <div class="page-wrapper">
    <div class="container-fluid"> 
    <div class="card">
    <div class="card-body"> 
             <div class="row page-titles">
                                                        <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Edit Training</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/training/">Manage Training</a></li>
                                                                <li class="breadcrumb-item active">Edit Training</li>
                                                            </ol>
                                                        </div>
             </div>
            <?php if($this->session->flashdata('message')):?>
            <p class="success-msg" id="success-alert" style="color: green;">
              <?php echo $this->session->flashdata('message');?>
            </p>
            <?php endif;?> 
              <?php if($error):?>
            <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
              <?php echo $error;?>
            </p>
            <?php endif;?> 
                <div class="row page-titles">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">  
                             <form enctype="multipart/form-data" name="add" id="add"
                            method="post" action="<?php echo base_url('admin/Training/');?>edittraining/<?php echo $id ?>" style="width: 100%;">
                            <div class="form-group">
                                <input type="hidden" name="training_id" id="training_id" value="<?php echo $id ?>">
                                        <label for="training_title">Training title</label> 
                                        <select class="training_title form-control custom-select required" id="training_title"
                                            name="training_title"  placeholder="Select training title"
                                            required="required">
                                            <option value="none"><?php echo "------Select training title-------"?></option>
                                                            <?php
                                                            foreach ($training_title as $uni) {
                                                                ?>  

                                                                     <option
                                                value="<?php echo $uni['id']; ?>"><?php echo $uni['title']; ?></option> 
                                                            <?php } ?>
                                                </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Others</label>
                                        <input type="text" class="title form-control" value="<?php echo $training[0]['title'];?>" autocomplete="off" name="title" id="title" required="required"  placeholder="Enter title"> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="description form-control" id="description"  name="description" required="required"  placeholder="Enter description"><?php echo $training[0]['description'];?></textarea>
                                        <input type="hidden" id="user_unitids" name="result" value="<?php echo $unit_id_string; ?>">
                                    </div>  
                                     <div class="row">
                                           <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wfromdate2">From date:<span class="danger">*</span></label>
                                                     <?php if($status==1){?>
                                                        <input type="text" name="fromdate" class="form-control required" id="from-datepicker" value="<?php echo date("d/m/Y",strtotime($training[0]['date_from'])); ?>"> 
                                                    <?php } else {?>
                                                         <input type="text" name="fromdate" class="form-control" readonly="readonly" onkeydown="return false" id="from-datepicker" value="<?php echo date("d/m/Y",strtotime($training[0]['date_from'])); ?>"> 
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wtodate2">To date :</label>
                                                     <?php if($status==1){?>
                                                        <input type="text" class="form-control required" name="todate" id="to-datepicker" value="<?php echo date("d/m/Y",strtotime($training[0]['date_to'])); ?>"> 
                                                     <?php } else {?>
                                                        <input type="text" class="form-control" readonly="readonly" name="todate" onkeydown="return false" id="to-datepicker" value="<?php echo date("d/m/Y",strtotime($training[0]['date_to'])); ?>"> 
                                                     <?php } ?>
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
                                                        for($i=1;$i<=$n;$i++) { if($i<10) {?>
                                                         <option value="<?php echo '0'.$i;?>" <?php if(($a[0])=="$i"){?>  selected="selected" <?php }?> ><?php echo $i;?></option><?php } else {?>
                                                         <option value="<?php echo $i;?>" <?php if(($a[0])=="$i"){?>  selected="selected" <?php }?> ><?php echo $i;?></option> <?php } ?>
                                                        <?php } ?>
                                                     </select>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                <div class="form-group"> 
                                                <label for="wstart_time12"><br></label>   
                                                    <p><h7>Minutes:</h7></p>
                                                    <select class="custom-select form-control required" id="wstart_time12" name="wstart_time12">
                                                        <option value="00" <?php if(($a[1])=="00"){?>  selected="selected" <?php }?> >00</option>
                                                        <option value="15" <?php if(($a[1])=="15"){?>  selected="selected" <?php }?> >15</option>
                                                        <option value="30" <?php if(($a[1])=="30"){?>  selected="selected" <?php }?> >30</option>
                                                        <option value="45" <?php if(($a[1])=="45"){?>  selected="selected" <?php }?> >45</option>
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
                                                        for($i=1;$i<=$n;$i++) { if($i<10) {?>
                                                         <option value="<?php echo '0'.$i;?>" <?php if(($b[0])=="$i"){?>  selected="selected" <?php }?> ><?php echo $i;?></option><?php } else {?>
                                                         <option value="<?php echo $i;?>" <?php if(($b[0])=="$i"){?>  selected="selected" <?php }?> ><?php echo $i;?></option> <?php } ?>
                                                        <?php } ?>
                                                     </select>  
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                <div class="form-group"> 
                                                <label for="wend_time12"><br></label>   
                                                    <p><h7>Minutes:</h7></p>
                                                    <select class="custom-select form-control required" id="wend_time12" name="wend_time12">
                                                        <option value="00" <?php if(($b[1])=="00"){?>  selected="selected" <?php }?> >00</option>
                                                        <option value="15" <?php if(($b[1])=="15"){?>  selected="selected" <?php }?> >15</option>
                                                        <option value="30" <?php if(($b[1])=="30"){?>  selected="selected" <?php }?> >30</option>
                                                        <option value="45" <?php if(($b[1])=="45"){?>  selected="selected" <?php }?> >45</option>
                                                     </select>
                                                </div>
                                            </div> 
                                    </div>
                                    <?php  
                                    if($training[0]['training_hour']=='' || $training[0]['training_hour']=='00:00')
                                    {
                                        $training_hour='00:00';
                                    }
                                    else
                                    {
                                        $training_hour=$training[0]['training_hour'];
                                    }

                                    ?>
                                    <div class="form-group">
                                        <label for="training_hour">Training hour</label>
                                        <input type="text" class="training_hour form-control" name="training_hour" id="training_hour" value="<?php echo $training_hour;?>" required="required" placeholder="hh:mm"> 
                                        <!-- <label for="decimal_hour" id="decimal_hour"> <?php echo settimeformat(getPayrollformat1($training[0]['training_hour']));?></label> -->
                                    </div> 
                                   <div class="form-group">
                                    <label for="Location">Unit</label> 
                                        <select required="required" class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select unit">
                                        <option value="none"><?php echo "------Select unit-------"?></option>
                                        <?php foreach($locationunit as $cl) { ?>
                                            <option <?php  if($training[0]['place']==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option> 
                                        <?php } ?>
                                        </select> 
                                    </div>
                                    <div class="form-group">
                                        <label for="training_unit">Place</label>
                                        <input type="text" class="training_unit form-control" name="training_unit" required="required" id="training_unit" value="<?php echo $training[0]['place'];?>"  placeholder="Enter unit"> 
                                    </div> 

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="address form-control" id="address" name="address" required="required"  placeholder="Enter address"><?php echo $training[0]['address'];?></textarea> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="point_of_person">Point of contact</label>
                                        <input type="text" class="point_of_person form-control" value="<?php echo $training[0]['point_of_person'];?>" name="point_of_person" id="point_of_person" required="required"  placeholder="Enter point of contact"> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="contact_number">Contact number</label>
                                        <input type="text" class="contact_number form-control" value="<?php echo $training[0]['contact_num'];?>" name="contact_number" id="contact_number" required="required"  placeholder="Enter contact number"> 
                                    </div>  
                                     <div class="form-group">
                                        <label for="contact_email">Contact email</label>
                                        <input type="text" class="contact_email form-control" name="contact_email" id="contact_email" value="<?php echo $training[0]['contact_email'];?>" required="required"  placeholder="Enter contact email"> 
                                    </div>  
                                    <div class="form-group"  style="width: 200px;">
                                        <select class="form-control" name="status1" id="status1" class="status1">
                                        	<option <?php if($training[0]['status']==1) { ?> selected="selected" <?php } ?> value="1">Active</option>
											<option <?php if($training[0]['status']==2) { ?> selected="selected" <?php } ?> value="2">Inactive</option> 
                                        </select>
                                    </div><br>
                                    <input type="hidden" name="id" value="<?php echo $training[0]['id'];?>"> 
                                    <input type="hidden" name="status" value="<?php echo $status;?>"> 
                                    <button type="button" onclick="location.href='<?php echo base_url('admin/training/');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Update</button>
                                    <div class="form-group user-list">
                                        <label></label>
                                        <label for="users">Selected Users</label>
                                        <input type="hidden" id="selected_users" class="selected_users" name="selected_users[]" value="" />
                                        <div class="table-responsive m-t-40">
                                            <table id="selected-users" class="table table-bordered table-striped selected-users-table">
                                               <tbody class='selected-user-data'>
                                                <tr></tr>
                                            </tbody>
                                            </table>
                                        </div>
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
                                                    <option <?php if(in_array($uni['id'], $unit_id_array)) { ?> selected <?php } ?> class='sl-unit' data-id="<?php echo $uni['id']; ?>" text="<?php echo $uni['parent_unit'];?>" data-parent="<?php echo $uni['parent_unit'];?>" value="<?php echo $uni['id']; ?>" ><?php echo $uni['unit_name']; ?></option>  
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
                                                    <th style="border:1px solid #dee2e6"><input type="checkbox"  class="selectall" name="selectall" id="selectall"/>Select all</th>
                                               </tr>
                                                </thead>
                                                 <tbody>  
                                                <?php 

                                                    // Extract designations from the array
                                                    $designations = array_column($user_all, 'designation_name');

                                                    // Get unique designations using array_unique
                                                    $uniqueDesignations = array_unique($designations);

                                                    // Convert the result to a simple 1D array
                                                    $uniqueDesignationsArray = array_values($uniqueDesignations);

                                                  
                                                ?>
                                                <!-- <tr>
                                                    <td><?php echo $user['fname']." ".$user['lname'] ; ?></td>
                                                    <td><?php echo $user['designation_name']; ?></td>
                                                    <td><input type="checkbox" id="user_<?php echo $user['user_id'];?>" data-unitid="<?php echo $user['unit_id'];?>" data-unit="<?php echo $user['unit_shortname'];?>" data-username="<?php echo $user['fname'];?> <?php echo $user['lname'];?>" name="usercheck[]" class="checkItem" value="<?php echo $user['user_id'].'___'.$user['email'].'___'.$user['unit_id'].'___'.$user['unit_shortname'].'___'.$user['fname'].' ',$user['lname'];?>"></td>
                                                    
                                                </tr> -->
                                                 
                                            </tbody>
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
    var new_status=<?php print_r($status);?>; 
    var unit_array=<?php print_r($unit_array);?>;
    var user_new_array=<?php print_r($users_new);?>;
    var uniqueDesignations=<?php echo json_encode($uniqueDesignationsArray);?>;
    uniqueDesignations.sort();
</script>

