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
                                                                <li class="breadcrumb-item active">Training Report</li>
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
            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1){?> 
            <div class="row">
               
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Unit:<span style="color: red;">*</span></label> 
                                        <select required="required" class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select Unit">
                                        <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id')) || $this->session->userdata('subunit_access')==1){?>
                                        <option value="none"><?php echo "------Select unit-------"?></option>
                                        <?php }?>
                                        <?php foreach($unit as $cl) { ?>
                                            <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div> 
                <div class="col-md-2" style="padding-right: 36px;">
                    <div class="form-group">
                                    <label for="Location">User:</label> 
                                        <select required="required" class="form-control custom-select required user" id="user" name="user" placeholder="Select User">
                                        <option value="none"><?php echo "------Select user-------"?></option>
                                        </select> 
                    </div>
                </div>
                <div class="col-md-2" style="padding-right: 36px;">
                    <div class="form-group"> 
                                <label for="from_date">From date:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                <input type="text"  class="form-control required" id="from-datepicker" name="from_date" value="<?php echo $from_date; ?>"> 
                    </div>
                </div>
                <div class="col-md-2" style="padding-right: 36px;">
                        <div class="form-group"> 
                                    <label for="to_date">To date:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                    <input type="text" class="form-control required" id="to-datepicker" name="to_date" value="<?php echo $to_date; ?>"> 
                        </div>
                </div>
                <!-- <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Status">Status</label> 
                                        <select required="required" class="form-control custom-select required status" id="status" name="status" placeholder="Select Status"> 
                                        <option value="0"  <?php if($this->input->post('status')=="0"){?>  selected="selected" <?php }?> >All</option>
                                        <option value="1"  <?php if($this->input->post('status')=="1"){?>  selected="selected" <?php }?> >Active</option>
                                        <option value="2"  <?php if($this->input->post('status')=="2"){?>  selected="selected" <?php }?> >Inactive</option>  
                                        </select>
                    </div>
                </div> -->
                <div class="col-md-2">
                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                     <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                                    
                    </div>
                      
                </div>
            <!-- </div> 
            <div class="row">
                
                <div class="col-md-6" style="padding-left: 40px;"> 
                <label for="designation_name">Designation:</label>
                    <input type="text" class="form-control" name="designation_name" id="designation_name" readonly="readonly"> -->
                     </div> 
                  </div>
                <div class="col-md-4" style="padding-right: 40px;"> </div>
                    
            </div> 
             <?php }?> 
            <div class="row">
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr> 
                                                           
                                                                <th style="border:1px solid #dee2e6">Title</th> 
                                                                <th style="border:1px solid #dee2e6">Description</th>
                                                                <th style="border:1px solid #dee2e6">From</th>
                                                                <th style="border:1px solid #dee2e6">To</th> 
                                                                <th style="border:1px solid #dee2e6">Unit</th>  
                                                                <th style="border:1px solid #dee2e6">View Staffs</th>                         
                                                                                    <!-- <th>Action</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                 
                                                </tbody>
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
 