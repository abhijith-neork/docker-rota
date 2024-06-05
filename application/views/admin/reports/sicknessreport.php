<style>
.dot {
  height: 10px;
  width: 10px;
  background-color: #0e0e0e;
  border-radius: 50%;
  display: inline-block;
}
</style>
<div class="page-wrapper">
        <div class="container-fluid"> 
            <div class="card">
            <div class="card-body">
            <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Reports</h3>
                                                            <ol class="breadcrumb">
                                                                <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser">Home</a></li>
                                                                <li class="breadcrumb-item active">Sickness report</li>
                                                            </ol>
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
            <form method="POST" action="" id="frmViewsicknessReport"   name="frmViewsicknessReport">
            <div class="row">
              <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Unit:<span style="color: red;">*</span></label> 
                                        <select class="form-control custom-select unitdata" id="unitdata" name="unitdata" placeholder="Select Unit"> 
                                        <option value="none"><?php echo "------Select unit-------"?></option> 
                                        <?php foreach($unit as $cl) { ?>
                                            <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
               <div class="col-md-2" style="padding-right: 36px">
                    <div class="form-group">
                                    <label for="Status">Status:</label> 
                                        <select class="form-control custom-select status" id="status" name="status" placeholder="Select Status">  
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
                                        <select class="form-control custom-select user" id="user" name="user" placeholder="Select User">
                                        <option value="none"><?php echo "------Select user-------"?></option>
                                         <?php foreach($user as $cl) { ?>
                                            <option <?php if($user_post==$cl['user_id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['user_id']; ?>" ><?php echo $cl['fname'].' '.$cl['lname']; ?></option>  
                                        <?php } ?>
                                        </select> 
                    </div>
              </div>
              
              <div class="col-md-2">
                   <div class="form-group" style="padding-right: 36px;">
                    <label for="Location">Job role:</label> 
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
            
              <div class="col-md-2" style="padding-right: 36px;">
                    <div class="form-group"> 
                                <label for="from_date">Start date:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                <input type="text"  class="form-control required" id="from-datepicker" name="from_date" value="<?php echo $from_date; ?>"> 
                    </div>
              </div>
              <div class="col-md-2" style="padding-right: 36px;">
                    <div class="form-group"> 
                                <label for="to_date">End date:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                <input type="text" class="form-control required" id="to-datepicker" name="to_date" value="<?php echo $to_date; ?>"> 
                    </div>
              </div>
            </div>
            <div class="row">
               <div class="col-md-10">
                 
               </div>
               <div class="col-md-2" style="float: right;">
                    <div class="form-group" style="width: 200px;">
                     <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                                    
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
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                                                    <th>Name</th>
                                                                                    <th>HR ID</th>
                                                                                    <th>Unit</th> 
                                                                                    <th>Designation</th>
                                                                                    <th>Date</th>
                                                                                    <th>Original Shift</th>
                                                                                    <th>Sickness</th>
                                                                                    <th>Daily Census Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <?php  
                                                            foreach($sicknessreport as $use) 
                                                    { 


                                                      $Origninal=getOriginalShiftNAme($use['user_id'],$use['date']);





                                                      ?> 
                                                    <tr>
                                                                                    <td><?php echo $use['fname']." ".$use['lname'];?></td> 
                                                                                    <td><?php echo $use['hr_ID'];?></td>
                                                                                    <td><?php echo $use['unit_name'];?></td>
                                                                                    <td><?php echo $use['designation_name'];?></td>
                                                                                    <td data-sort="<?php echo $use['date'];?>"><?php echo date('d/m/Y',strtotime($use['date']));?></td>
                                                                                    <td><?php echo $Origninal; ?></td>
                                                                                    <td><?php echo $use['shift_name'];?></td> 
                                                                                    <td> 
                                                                                      <?php $Note=GetCensus($use['date'],$use['user_id']);
                                                                                      if(count($Note)==0)
                                                                                      {
                                                                                             echo " ";
                                                                                      }
                                                                                      else 
                                                                                      {
                                                                                           foreach ($Note as $value) { ?>

                                                                                            <span class="dot"><?php echo ".";?></span>
                                                                                            <span><?php echo " ".$value['comment'];echo ".<br>";echo ".<br>";?></span>

                                                                                          <?php }
                                                                                      }

                                                                                      ?>
                                                                                    </td> <!-- message -->
                                                    </tr> 

                                                    <?php
                                                    }
                                                    ?>
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
 