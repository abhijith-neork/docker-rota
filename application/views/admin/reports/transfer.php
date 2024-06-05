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
                                                                <li class="breadcrumb-item active">Transfer Hour Report</li>
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
                                    <label for="From_unit">From Unit:<span style="color: red;">*</span></label> 
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
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="At_unit">At Unit:</label> 
                                        <select required="required" class="form-control custom-select required at_unit" id="at_unit" name="at_unit" placeholder="Select Unit">
                                       <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                        <option value="0"><?php echo "------Select unit-------"?></option>
                                        <?php }?>
                                        <?php foreach($atunit as $cl) { ?>
                                            <option <?php    if($this->input->post("at_unit")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
                <div class="col-md-2">
                   <div class="form-group" style="padding-right: 36px;">
                    <label for="Location">Job role:<span style="color: red;"></span></label> 
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
                <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group"> 
                                <label for="from_date">From date:<span style="color: red;">*</span></label> 
                                <input type="text"  class="form-control required" id="start_date" placeholder="dd/mm/yy" name="start_date" value="<?php echo "$start_date";?>"> 
                            </div>
                </div>
                <div class="col-md-2" style="padding-right: 36px;">
                            <div class="form-group"> 
                                <label for="to_date">To date:<span style="color: red;">*</span></label> 
                                <input type="text" class="form-control required" id="end_date" placeholder="dd/mm/yy"  name="end_date" value="<?php echo "$end_date";?>"> 
                            </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                </div>
                <!-- <div class="col-md-2" style="padding-top: 34px;" > -->
                <div class="col-md-2" style="padding-left: 30px;">
                    <div class="form-group" style="padding-right: 50px;padding-top: 30px;width: 200px;">
                     <button type="button" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                                    
                    </div>
                      
                </div>
                
            </div>
<script type="text/javascript"> 
var jobe_roles=<?php print $job_roles;?>; 
</script> 
            <div class="row"> 
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                            <th>Payroll ID</th>
                                                            <th>Name</th>
                                                            <th>Role</th>
                                                            <th>At Unit</th>
                                                            <th>Total</th>
                                                            <th>Cost</th>
                                                               
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <?php  foreach ($proll as $payroll)  {  ?> 
                                                        
                                                                <?php for($i=0;$i<count($payroll['hours']);$i++){

                                                                    if($payroll['hours'][$i]['from_unit']!=''){ 


                                                                        $hours=$payroll['hours'][$i]['total_by_date'];
                                                                        $hours=$hours+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['hours'][$i]['additional_hour_byday'],2))));

                                                                         if($payroll['user_details'][0]['day_rate']!='0.00')
                                                                         {
                                                                            $Cost=$hours*$payroll['user_details'][0]['day_rate'];
                                                                         }
                                                                         else
                                                                         {
                                                                            $Cost=$hours;
                                                                         }

                                                                ?>

                                                                <?php if($hours > '0.00'){?>
                                                                <tr>
                                                                    <td><?php print_r($payroll['user_details'][0]['payroll_id']);?></td>
                                                                    <td><?php print_r($payroll['user_details'][0]['fname'].' '.$payroll['user_details'][0]['lname']);?></td> 
                                                                    <td><?php print_r($payroll['user_details'][0]['designation_name']);?></td>
                                                                    <td><?php print_r($payroll['hours'][$i]['from_unit']);?></td>
                                                                    <td><?php echo number_format($hours,2);?></td>
                                                                    <td><?php echo number_format($Cost,2);?></td>
                                                                </tr>
                                                                <?php } ?>


                                                                <?php } }?>
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

 
 