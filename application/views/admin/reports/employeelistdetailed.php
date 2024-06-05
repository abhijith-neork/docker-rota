<div class="page-wrapper">
        <div class="container-fluid"> 
            <div class="card">
            <div class="card-body">
            <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Reports</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active">Detailed Employee List</li>
                                                            </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center"> 
                      <!--    <button class="btn float-right hidden-sm-down btn-success" 
                                                           onclick="location.href='<?php echo base_url();?>admin/User/addUser'">
                                                            </i> Add User</button>  -->
                                                           
                                                           
                    </div>
            </div>
             <?php
            if($this->session->flashdata('error')==1)
                $color="red";
            else 
                $color="green";
            
            if($this->session->flashdata('message')):?>
            <p class="success-msg" id="success-alert"
                    style="color: <?php echo $color; ?>; text-align:center;">
              <?php echo $this->session->flashdata('message');?>
            </p>
            <?php endif;?>  
             <form method="POST" action="" id="frmViewalluserlistReport"   name="frmViewalluserlistReport">
              <div class="row">
                <div class="col-md-3" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Unit:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                        <select required="required" class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select Unit">
                                        <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                        <option value="0"><?php echo "------Select unit-------"?></option>
                                        <?php }?>
                                        <?php foreach($unit as $cl) { ?>
                                            <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
                <div class="col-md-3" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Job role</label> 
                                        <select required="required" class="form-control custom-select required jobrole" id="jobrole" name="jobrole" placeholder="Select Job Role">
                                        <option value="0"><?php echo "------Select job role-------"?></option>
                                        <?php foreach($jobrole as $cl) { ?>
                                            <option <?php    if($this->input->post("jobrole")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['designation_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="enrolled_status">Enrolled Status</label> 
                                        <select required="required" class="form-control custom-select required enrolled_status" id="enrolled_status" name="enrolled_status" placeholder="Select Enrolled Status">
                                        <option value="0"  <?php if($this->input->post('enrolled_status')=="0"){?>  selected="selected" <?php }?> >--Select enroll status--</option> 
                                        <option value="1"  <?php if($this->input->post('enrolled_status')=="1"){?>  selected="selected" <?php }?> >Enrolled</option>
                                        <option value="2"  <?php if($this->input->post('enrolled_status')=="2"){?>  selected="selected" <?php }?> >Unenrolled</option>  
                                        </select>
                    </div>
                </div>
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Status">Status</label> 
                                        <select required="required" class="form-control custom-select required status" id="status" name="status" placeholder="Select Status"> 
                                        <option value="1"  <?php if(($user[0]['status'])=="1"){?>  selected="selected" <?php }?> >Active</option>
                                        <option value="0"  <?php if(($user[0]['status'])=="0"){?>  selected="selected" <?php }?> >All</option>
                                        <option value="2"  <?php if(($user[0]['status'])=="2"){?>  selected="selected" <?php }?> >Inactive</option> 
                                        <option value="3"  <?php if(($user[0]['status'])=="3"){?>  selected="selected" <?php }?> >Deleted</option> 
                                        </select>
                    </div>
                </div>
                
                <!-- <div class="col-md-2" style="padding-top: 34px;" > -->
                <div class="col-md-2">
                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                     <button type="button" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                                    
                    </div>
                      
                </div>
            </div>   </form> 
            <div class="row">
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr> 
                                                                                    <th>User ID</th> 
                                                                                    <th>Name</th>
                                                                                    <th>Email Address</th>
                                                                                    <th>Phone Number</th>
                                                                                    <th>Date of Birth</th>
                                                                                    <th>Gender</th>
                                                                                    <th>Ethnicity</th>
                                                                                    <th>Visa Status</th>
                                                                                    <th>Address</th>
                                                                                    <th>Country</th>
                                                                                    <th>City</th>
                                                                                    <th>Postcode</th>
                                                                                    <th>Next Of Kin</th>
                                                                                    <th>Group</th>
                                                                                    <th>Weekly Hours</th>
                                                                                    <th>Annual Leave Allowance</th>
                                                                                    <th>Annual Leave Entitlement</th>
                                                                                    <th>Default Shift</th> 
                                                                                    <th>Start Date</th>
                                                                                    <th>End Date</th>
                                                                                    <th>Payroll ID</th>
                                                                                    <th>Payment Type</th>
                                                                                    <th>Basic Pay Rate</th>
                                                                                    <th>Unit</th>  
                                                                                    <th>Job Role</th> 
                                                                                    <th>Status</th> 
                                                                                    <th>Enrolled Status</th> 
                                                                                    <!-- <th>Action</th> -->
                                                    </tr>
                                                </thead>
                                                <!--  -->
                                            </table>
                                    </div>
                                </div>
                            </div>
                    </div>
             </div>
                    </div>
             </div>                                 
        </div>
</div>
 