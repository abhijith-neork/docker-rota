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
                                                                <li class="breadcrumb-item active">Annual Leave All Employees</li>
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
            <form method="POST" action="" id="frmViewallstaffleavetReport"   name="frmViewallstaffleavetReport">
              <div class="row">
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Unit <span style="color: red;">*</span></label> 
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
                <div class="col-md-2">
                   <div class="form-group" style="padding-right: 36px;">
                    <label for="Location">Job role </label> 
                      <select class="select2 mb-2 select2-multiple jobrole" id="jobrole" name="jobrole[]" style="width: 100%" multiple="multiple" data-placeholder="--Select job role--">
                          <optgroup label="--Select job role--" class="roles" id="roles" name="role[]">
                              <?php
                                  foreach ($jobrole as $job_role) {
                              ?>   
                              <option data-id="<?php echo $job_role['id']; ?>" value="<?php echo $job_role['id']; ?>" ><?php echo $job_role['designation_name']; ?></option>  
                              <?php } ?>  
                          </optgroup>
                       
                      
                      </select>

                     
                    </div>
                </div>

               <!--  <div class="col-md-3" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Job role</label> 
                                        <select required="required" class="form-control custom-select required jobrole" id="jobrole" name="jobrole" placeholder="Select Job Role">
                                        <option value="0"><?php echo "------Select job role-------"?></option>
                                        <?php foreach($jobrole as $cl) { ?>
                                            <option <?php    if($this->input->post("jobrole")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['designation_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div> -->
                 <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Status">Status</label> 
                                        <select required="required" class="form-control custom-select required user_status" id="user_status" name="user_status" placeholder="Select Status"> 
                                        <option value="1"  <?php if($this->input->post('user_status')=="1"){?>  selected="selected" <?php }?> >Active</option>
                                        <option value="0"  <?php if($this->input->post('user_status')=="0"){?>  selected="selected" <?php }?> >All</option>
                                        <option value="2"  <?php if($this->input->post('user_status')=="2"){?>  selected="selected" <?php }?> >Inactive</option> 
                                        <option value="3"  <?php if($this->input->post('user_status')=="3"){?>  selected="selected" <?php }?> >Deleted</option>  
                                        </select>
                    </div>
                </div>
                <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group"> 
                                <label for="from_date">From date <span style="color: red;">*</span></label> 
                                <input type="text"  class="form-control required" id="start_time" placeholder="dd/mm/yy" name="start_time" value="<?php echo "$start_date";?>"> 
                            </div>
                </div>
                <div class="col-md-2" style="padding-right: 36px;">
                            <div class="form-group"> 
                                <label for="to_date">To date <span style="color: red;">*</span></label> 
                                <input type="text" class="form-control required" id="end_time" placeholder="dd/mm/yy"  name="end_time" value="<?php echo "$end_date";?>"> 
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
                                                            <th>HR ID</th>
			                                                <th>Name</th>
			                                                <th>Unit</th>
			                                                <th>From Date</th> 
			                                                <th>To Date</th> 
			                                                <th>Hours</th>
                                                            <th>Annual Holiday Allowance</th>
                                                            <th>Leave Status</th>
                                                            <th>Leave</th>
                                                            <th>Status</th>
                                                               
                                            		</tr>
                                        		</thead>
                                        		 
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
<script type="text/javascript"> 
var jobe_roles=<?php print $job_roles;?>; 

</script> 
 