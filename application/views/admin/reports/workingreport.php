<div class="page-wrapper">
        <div class="container-fluid"> 
            <div class="card">
            <div class="card-body">
            <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Reports</h3>
                                                            <ol class="breadcrumb">
                                                                <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser">Home</a></li>
                                                                <li class="breadcrumb-item active">Working Report</li>
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
            <form method="POST" action="">
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
               <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="shift_category">Shift category:</label> 
                                        <select required="required" class="form-control" name="shift_category" id="shift_category" class="shift_category">  
                                            <option value="0">Select shift category</option>
                                            <option value="1"<?php if($this->input->post('shift_category')=="1"){?> selected="selected" <?php }?>>Day</option>
                                            <option value="2"<?php if($this->input->post('shift_category')=="2"){?> selected="selected" <?php }?>>Night</option>
                                            <option value="3"<?php if($this->input->post('shift_category')=="3"){?> selected="selected" <?php }?>>Early</option>  
                                            <option value="4"<?php if($this->input->post('shift_category')=="4"){?> selected="selected" <?php }?>>Late</option>  
                                        </select>
                                        </select>  
                    </div>
                </div>

                 <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="part_of_number">Part of number:</label> 
                                        <select required="required" class="form-control" name="part_of_number" id="part_of_number" class="part_of_number">  
                                            <option value="none"<?php if($this->input->post('part_of_number')=="none"){?> selected="selected" <?php }?>>All</option>
                                            <option value="1"<?php if($this->input->post('part_of_number')=="1"){?> selected="selected" <?php }?>>Yes</option>
                                            <option value="0"<?php if($this->input->post('part_of_number')=="0"){?> selected="selected" <?php }?>>No</option>  
                                        </select>
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
                                <label for="from_date">Date:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                <input type="text"  class="form-control required" id="from-datepicker" name="from_date" value="<?php echo $from_date; ?>"> 
                    </div>
              </div> 

              <div class="col-md-2" style="padding-right: 36px;padding-top: 30px;">
                    <div class="form-group" style="width: 200px;">
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
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                                                    <th>Name</th>
                                                                                    <th>Unit</th> 
                                                                                    <th>Designation</th>
                                                                                    <th>Shift Category</th> 
                                                                                    <th>Part of Number</th> 
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
 