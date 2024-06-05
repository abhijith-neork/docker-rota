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
                                                                <li class="breadcrumb-item active">Absence report</li>
                                                            </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">  
                                                           
                                                           
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
            <form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('admin/Reports/');?>absencereport" >
            <div class="row">
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Unit<span style="color: red;">*</span></label> 
                                        <select  class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select Unit">
                                        <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                        <option value=""><?php echo "------Select unit-------"?></option>
                                        <?php }?>
                                        <?php foreach($unit as $cl) { ?>
                                            <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Job role group</label> 
                                        <select  class="form-control custom-select required jobrole" id="jobrole" name="jobrole" placeholder="Select Job Role">
                                        <option value=""><?php echo "------Select job role group-------"?></option>
                                        <?php foreach($job_role_group as $cl) { ?>
                                            <option <?php    if($this->input->post("jobrole")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['group_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
               
                <div class="col-md-2" style="padding-right: 36px;">
                    <div class="form-group">
                            <label for="Location">Date <span style="color: red;">*</span></label> 
                            <input class="start_date form-control" required type="text" name="start_date" id="from-datepicker" name="start_date"  value="<?php echo "$start_date";?>">
                    </div>
                </div>

                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Shift category</label> 
                                        <select  class="form-control custom-select shift" id="shift" name="shift" placeholder="Select Shift">
                                            <option value="none">Select shift category</option>
                                            <option value="1"<?php if($this->input->post('shift')=="1"){?> selected="selected" <?php }?>>Day</option>
                                            <option value="2"<?php if($this->input->post('shift')=="2"){?> selected="selected" <?php }?>>Night</option>
                                            <option value="3"<?php if($this->input->post('shift')=="3"){?> selected="selected" <?php }?>>Early</option>  
                                            <option value="4"<?php if($this->input->post('shift')=="4"){?> selected="selected" <?php }?>>Late</option> 
                                        </select>  
                    </div>
                </div>
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Status">Status</label> 
                                        <select required="required" class="form-control custom-select required status" id="status" name="status" placeholder="Select Status"> 

                                        <option value="1"  <?php if($this->input->post('status')=="1"){?>  selected="selected" <?php }?> >Active</option>
                                        <option value="0"  <?php if($this->input->post('status')=="0"){?>  selected="selected" <?php }?> >All</option>
                                        <option value="2"  <?php if($this->input->post('status')=="2"){?>  selected="selected" <?php }?> >Inactive</option> 
                                        <option value="3"  <?php if($this->input->post('status')=="3"){?>  selected="selected" <?php }?> >Deleted</option> 
                                        </select>
                    </div>
                </div>

               <!--  <div class="col-md-2" style="padding-right: 36px;">
                    <div class="form-group">
                                    <label for="Location">Type</label> 
                                        <select  class="form-control custom-select required type" id="type" name="type" placeholder="Select Type">
                                        <option value="3"><?php echo "----Select type----"?></option>
                                        <option value="1"  <?php if($this->input->post("type")=="1"){?>  selected="selected" <?php }?> >Check-in</option>
                                        <option value="2"  <?php if($this->input->post("type")=="2"){?>  selected="selected" <?php }?> >Check-out</option>  
                                        </select> 
                    </div>
                </div> -->
                <!-- <div class="col-md-2" style="padding-top: 34px;" > -->
                <div class="col-md-2">
                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                     <button type="button" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                                    
                    </div>
                      
                </div>
            </div>
            <div class="row">
                
                <div class="col-md-6" style="padding-left: 40px;"> 
                <!--    <label for="designation_name">Designation:</label>
                    <input type="text" class="form-control" name="designation_name" id="designation_name" readonly="readonly"> -->
                     </div> 
                  </div>
                <div class="col-md-6" style="padding-right: 40px;"> </div>
                    
            </div>
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
                                                                <th>Mobile Number</th> 
                                                               <!--  <th>Telephone Number</th> -->
                                                               <!-- <th>Next of Kin</th>
                                                                <th>Next of Kin Mobile Number</th> -->
                                                                <th>Date</th>
                                                                <!-- <th>Status</th> -->

                                                                                    
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



        </form>

        </div>
        </div>                                 
    </div>
</div>

 