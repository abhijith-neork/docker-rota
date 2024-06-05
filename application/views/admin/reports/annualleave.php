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
                                                                <li class="breadcrumb-item active">Annual Leave Individual</li>
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
            <div class="row">
            	<div class="col-md-4" style="padding-left: 36px">
            		<div class="form-group">
                                    <label for="Location">Unit:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                        <select required="required" class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select Unit">
                                        <option value="none"><?php echo "------Select unit-------"?></option>
                                        <?php foreach($unit as $cl) { ?>
                                            <option value="<?php echo $cl['id']; ?>"><?php echo $cl['unit_name']; ?></option> 
                                        <?php } ?>
                                        </select> 
                    </div>
            	</div>
                <div class="col-md-4" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Status">Status:</label> 
                                        <select required="required" class="form-control custom-select required status" id="status" name="status" placeholder="Select Status"> 
                                        <option value="1"  <?php if($this->input->post('status')=="1"){?>  selected="selected" <?php }?> >Active</option>
                                        <option value="0"  <?php if($this->input->post('status')=="0"){?>  selected="selected" <?php }?> >All</option>
                                        <option value="2"  <?php if($this->input->post('status')=="2"){?>  selected="selected" <?php }?> >Inactive</option> 
                                        <option value="3"  <?php if($this->input->post('status')=="3"){?>  selected="selected" <?php }?> >Deleted</option> 
                                        </select>
                    </div>
                </div>
            	
            	<div class="col-md-4" style="padding-right: 36px;">
            		<div class="form-group">
                                    <label for="Location">User: <span style="color: red;">*</span></label> 
                                        <select required="required" class="form-control custom-select required user" id="user" name="user" placeholder="Select User">
                                        <option value="none"><?php echo "------Select user-------"?></option>
                                        </select> 
                    </div>
            	</div>
                
            </div>
            <div class="row">
            	
            	<div class="col-md-6" style="padding-left: 40px;"> 
            	<!-- 	<label for="designation_name">Designation:</label>
                    <input type="text" class="form-control" name="designation_name" id="designation_name" readonly="readonly"> -->
                     </div> 
            	  </div>
            	<div class="col-md-6" style="padding-right: 40px;"> </div>
            		
            </div>
            <div class="row"> 
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                	<h6 class="card-subtitle" style="padding-left: 25px;">Export data to Copy, Excel, PDF & Print</h6>
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                    <th>User ID</th>
                                                    <th>Name</th>
                                                    <th>From Date</th> 
                                                    <th>To Date</th>
                                                    <th>Hours</th>
                                                    <th>Status</th>
                                                    <th>Leave</th>
                                                    <th>Annual Holiday Allowance</th> 
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
 