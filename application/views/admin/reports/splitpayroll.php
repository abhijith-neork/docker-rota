<style>
   #img-loader-data{
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../../assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}

.spinnermodal {
        background-color: #FFFFFF;
        height: 100%;
        left: 0;
        opacity: 0.5;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 100000;
    }
</style>
<div class="loader" id="img-loader-data"></div>
<div class="spinnermodal" id="progressbar" style="display: none; z-index: 10001">
  <div style="position: fixed; z-index: 10001; top: 50%; left: 50%; height:65px">
      <img src="../../assets/images/loader.gif" alt="Loading..." />
    </div>
</div>
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
                                                                <li class="breadcrumb-item active">Payroll Report</li>
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
                                    <label for="Location">Unit:<span style="color: red;"></span></label> 
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
                                       
                                        
                                        <option value="0"  <?php if($this->input->post('status')=="0"){?>  selected="selected" <?php }?> >All</option>
                                        <option value="1"  <?php if($this->input->post('status')=="1"){?>  selected="selected" <?php }?> >Active</option>
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
                
                <!-- <div class="col-md-2" style="padding-top: 34px;" > -->
                <div class="col-md-2">
                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
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
                                                            <th>Days</th> 
                                                            <th>Days Sat</th> 
                                                            <th>Days Sun</th>
                                                            <th>WeekDay Nights</th>
                                                            <th>WeekEnd Nights</th>
                                                            <th>Paid Sickness</th>
                                                            <th>Training</th>
                                                            <th>Annual Leave</th>
                                                            <th>Bank Holiday</th>
                                                            <th>Total</th>
                                                            <th>Contracted</th>
                                                            <th>Salaried</th>
                                                            <th>Overtime</th>
                                                            <th>Comments</th>
                                                            <th>Action</th>
                                                               
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

 
 
