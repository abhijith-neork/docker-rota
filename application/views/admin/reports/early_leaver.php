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
                                                                <li class="breadcrumb-item active">Early Leave Report</li>
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
            <form method="POST" action=" " id="frmpayrollReport"   name="frmpayrollReport">
            <div class="row">
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Unit:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                        <select required="required" class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select Unit"> 
                                        <option value="0"><?php echo "------Select unit-------"?></option> 
                                        <?php foreach($unit as $cl) { ?>
                                            <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
                 <div class="col-md-2" style="padding-right: 36px">
                    <div class="form-group">
                                    <label for="Status">Status:</label> 
                                        <select required="required" class="form-control custom-select required status" id="status" name="status" placeholder="Select Status"> 
                                            
                                        <option value="1"  <?php if($status=="1"){?>  selected="selected" <?php }?> >Active</option>
                                        <option value="0"  <?php if($status=="0"){?>  selected="selected" <?php }?> >All</option>
                                        <option value="2"  <?php if($status=="2"){?>  selected="selected" <?php }?> >Inactive</option> 
                                        <option value="3"  <?php if($status=="3"){?>  selected="selected" <?php }?> >Deleted</option> 
                                        </select>
                    </div>
                </div>
                <div class="col-md-2" style="padding-right: 36px;">
                    <div class="form-group">
                                    <label for="Location">User:</label> 
                                        <select required="required" class="form-control custom-select required user" id="user" name="user" placeholder="Select User">
                                        <option value="0"><?php echo "------Select user-------"?></option>
                                         <?php foreach($user as $cl) { ?>
                                            <option <?php    if($user_post==$cl['user_id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['user_id']; ?>" ><?php echo $cl['fname'].' '.$cl['lname']; ?></option>  
                                        <?php } ?>
                                        </select> 
                    </div>
                </div>
                
            
                <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group"> 
                                <label for="from_date">From date:<span style="color: red;">*</span></label> 
                                <input type="text"  class="form-control required" id="start_date" placeholder="dd/mm/yy" autocomplete="off" name="start_date" value="<?php echo "$start_date";?>"> 
                            </div>
                </div>
                <div class="col-md-2" style="padding-right: 36px;">
                            <div class="form-group"> 
                                <label for="to_date">To date:<span style="color: red;">*</span></label> 
                                <input type="text" class="form-control required" id="end_date" placeholder="dd/mm/yy"  autocomplete="off" name="end_date" value="<?php echo "$end_date";?>"> 
                            </div>
                </div>
              
                
                <!-- <div class="col-md-2" style="padding-top: 34px;" > -->
                <div class="col-md-2">
                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                     <button type="button" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                                    
                    </div>
                      
                </div>
                
            </div>
            <div class="row"> 
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                                                    <th>User ID</th> 
                                                                                    <th>Name</th> 
                                                                                    <th>Unit</th>
                                                                                    <th>Date</th>
                                                                                    <th>Shift Name</th>
                                                                                    <th>Check-Out Time</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($early_leaver_data as $value) {?>
                                                       <tr>
                                                           <td><?php echo $value['user_id'];?></td>
                                                           <td><?php echo $value['name'];?></td>
                                                           <td><?php echo $value['unit_name'];?></td> 
                                                           <td data-sort="<?php echo $value['date'];?>"><?php echo date('d/m/Y',strtotime($value['date']));?></td>
                                                           <td><?php echo $value['shift'];?></td>
                                                           <td><?php echo $value['check_out_time'];?></td>
                                                       </tr>
                                                    <?php } ?>
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

 
 