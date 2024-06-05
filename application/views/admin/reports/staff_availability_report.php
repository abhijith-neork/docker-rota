<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Employees Availability</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Employees Availability</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center"></div>
                </div>
                <form method="POST" action="<?php echo base_url();?>admin/Reports/staffavailabilityreport" id="frmViewStaffavailAbilityReport"   name="frmViewStaffavailAbilityReport">
                    <div class="row">
                        <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group">
                                <label for="Location">Search-in unit <span style="color: red;"></span></label> 
                                <select  class="form-control custom-select  unitdata" id="unitdata" name="unitdata" placeholder="Select unit">
                                <option value=""><?php echo "--Unit--"?></option>
                                <?php foreach($locationunit as $cl) { ?>
                                <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <label style="padding-top: 34px;padding-left: 20px;">OR</label>
                        <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group">
                                <label for="unittype">Unit type</label> 
                                <select class="form-control custom-select required unittype" id="unittype" name="unittype" placeholder="Select unit type">
                                    <option value="0"><?php echo "--Unit Type--"?></option>
                                    <?php
                                    foreach ($unittype as $uni) {?>  
                                     <option value="<?php echo $uni['id']; ?>"<?php if($this->input->post('unittype')==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['name']; ?></option>
                                           <?php } ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group">
                                <label for="Location">For unit <span style="color: red;">*</span></label> 
                                <select  class="form-control custom-select  unitdatafor" id="unitdatafor" name="unitdatafor" required placeholder="Select unit">
                                    <option value=""><?php echo "--Unit--"?></option>
                                    <?php foreach($locationunit as $cl) { ?>
                                        <option <?php    if($selected_unit==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group">
                                <label for="Location">Shift <span style="color: red;">*</span></label> 
                                <select class="shift form-control custom-select" id="shift" name="shift" placeholder="Select shift" required>
                                    <option value=""><?php echo "--Shift--"?></option>
                                    <?php foreach($shifts as $shift) { ?>
                                    <option <?php    if($this->input->post("shift")==$shift['id']) { ?> selected="selected" <?php } ?> data-parent="<?php echo $shift['parent_id']; ?>" data-cat="<?php echo $shift['shift_category']; ?>" value="<?php echo $shift['id']; ?>" ><?php echo $shift['shift_name']; ?></option>  
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding-right: 36px;">
                            <label for="Location">Date <span style="color: red;">*</span></label> 
                            <input class="form-control" autocomplete="off"  type="text"  required placeholder="dd/mm/yy" id="start_date" name="start_date" value="<?php echo $this->input->post('start_date'); ?>">
                        </div>
                        <div class="col-md-1">
                            <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                                <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search">Search</button> 
                            </div>
                        </div>
                        <div class="col-md-1" style="padding:0px;">
                            <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 250px;">
                                <button type="button" class="live-status btn float-right hidden-sm-down btn-success" id="live-status">Availability tracker</button> 
                            </div>
                        </div>
                            <div class="col-md-1">
                                <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                                    <button type="button" style="display: none;" class="show_status btn float-right hidden-sm-down btn-success" id="show_status">Status</button> 
                               </div>
                            </div>
                    </div>
                    <div class="row">
                    <div class="col-4">
                    </div>
                    <div class="col-4"> 
                        <?php if(count($availablie_staffs) > 0):?>
                            <?php $rota_status=checkRotaStatus($this->input->post('start_date'),$this->input->post('unitdatafor'));?>
                            <?php if ($rota_status == 'false') { ?>
                                <div style="text-align: center;" class="alert alert-warning" role="alert">
                                    No rota published for this day.
                                </div>
                            <?php } ?>
                            
                        <?php endif; ?>
                        <p class="show_counts" style="display: none;text-align: center;"></p>
                    </div>
                    <div class="col-4">
                    </div>
                    </div>
                    <!--- delete all -->
                    <div class="row"> 
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive m-t-40">
                                        <!-- <?php if ($date) { ?>    
                                            <?php if ($staffs_on_leave == 0) { ?>
                                                <div class="alert alert-light" style="text-align: center;color: red" role="alert">
                                                No staffs has taken leave on these date
                                                </div>
                                            <?php } ?>
                                        <?php } ?> -->
                                        <div>
                                        </div>
                                        
                                        <table id="myTable" class="table table-bordered table-striped">
                                            <thead>
                                                
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Job Role</th>
                                                    <th>Phone</th> 
                                                    <th>Email</th>
                                                    <th>Select All <input type="checkbox" name="select_all" id="select_all" value="">
                                                        <?php if(count($availablie_staffs) > 0):?>

                                                            <?php $rota_status=checkRotaStatus($this->input->post('start_date'),$this->input->post('unitdatafor'));?>
                                                            <?php if ($rota_status == 'true') { ?>
                                                                <button type="button" class=" btn hidden-sm-down btn-success send_request">Send</button>
                                                            <?php } else { ?>
                                                                <button type="button" disabled class=" btn hidden-sm-down btn-success send_request">Send</button>
                                                            <?php } ?>
                                                        <?php endif; ?>
                                                    <!-- <?php if ($date) { ?>    
                                                        <?php if ($staffs_on_leave == 0) { ?>    
                                                            <button type="button" disabled class=" btn hidden-sm-down btn-success send_request">Send</button> 
                                                        <?php } else { ?>
                                                            <button type="button" class=" btn hidden-sm-down btn-success send_request">Send</button> 
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <button type="button" class=" btn hidden-sm-down btn-success send_request">Send</button>
                                                    <?php } ?> -->
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(count($availablie_staffs) > 0):?>
                                                <?php foreach ($availablie_staffs as $staff) { ?>
                                                    <tr>
                                                       <td><?php echo $staff['fname'];?> <?php echo $staff['lname'];?></td>
                                                       <td><?php echo $staff['designation_name'];?></td>
                                                       <td><?php echo $staff['mobile_number'];?></td>
                                                       <td><?php echo $staff['email'];?></td>
                                                       <td>
                                                        <?php if ($staff['time_gap']) { ?>
                                                            <div class="myotherdiv">
                                                                <?php echo $staff['time_gap']?>
                                                            </div>
                                                        <?php } else { ?>
                                                        <?php if ($staff['rota_status'] == 1) { ?>
                                                            <?php $user_status=checkUserStatus($this->input->post('start_date'),$staff['user_id'],$unit_id_for,$parent_shift_id);?>
                                                            <?php if ($user_status == "true") { ?>
                                                                <input type="checkbox" name="users" id="users"
                                                                data-userunitid="<?php echo $staff['user_unitid'];?>" data-email="<?php echo $staff['email'];?>" value="<?php echo $staff['user_id'];?>" data-name="<?php echo $staff['fname'];?> <?php echo $staff['lname'];?>" data-number="<?php echo $staff['mobile_number'];?>">
                                                            <?php } else { ?>
                                                                <div class="myotherdiv"><?php echo $user_status;?></div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <div class="myotherdiv">
                                                                Already assigned shift <?php echo $staff['rota_shift_id']?> on <?php echo $staff['unit_shortname']?>
                                                            </div>
                                                        <?php } ?>
                                                        <?php } ?>
                                                       </td>
                                                       <td>
                                                            <div>
                                                                <?php if ($user_status == "Sent") { ?>
                                                                    <button type="button" class=" btn hidden-sm-down btn-success delete_request" id="<?php echo $staff['user_id']?>">Delete</button>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php endif; ?>
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
<script type="text/javascript">
  var leave_details='';
  leave_details=<?php print_r($leave_details);?>;
</script>