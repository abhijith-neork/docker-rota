<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Edit Rota</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/rota/viewrota">Edit Rota</a></li>
                            <li class="breadcrumb-item active">Update Users</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">

                    </div>
                </div>
            <?php
                    if ($this->session->flashdata('error') == 1)
                        $color = "green";
                    else
                        $color = "red";

                    if ($this->session->flashdata('message')) :
                        ?>
                    <p class="success-msg" id="success-alert"
                            style="color: <?php echo $color; ?>; text-align:center;">
                      <?php echo $this->session->flashdata('message');?>
                    </p>
                    <?php endif;?> 
            <form enctype="multipart/form-data" id="frmcreate" name="frmcreate" method="post" action="<?php echo base_url('admin/Rota/');?>">
              <div class="row" style="width: 100%;text-align: center;padding-top: 10px">
                <div class="col-12">
                  <div class="alert alert-danger error_check" role="alert">
                  </div>
                </div>
              </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <h3 class="card-subtitle" style="padding-bottom: 40px;">Update Users</h3>
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                <label for="wfirstName2"> <b> Unit :</b> <span class="danger"></span></label>                
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <select class="isSelect custom-select form-control required" id="wunit2" name="wunit2">  
                                        <?php
                                            foreach ($unit as $uni) { 
                                        ?>  

                                        <option value="<?php echo $uni['id']; ?>"<?php if(($unit_select)==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['unit_name']; ?></option> 
                                        <?php 
                                              } 
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                            </div>
                        </div><br>
                        <input type="hidden" id="session_id" name="session_id" value="<?php echo $session_id ?>">
                        <input type="hidden" class="rota_settings form-control" name="rota_settings" id="rota_settings" value="<?php echo $rota_settings[0]['id']?>">
                        <input type="hidden" class="up_rota_id form-control" name="up_rota_id" id="up_rota_id" value="<?php echo $rota_id ?>">

                        <input type="hidden" value="2" name="new_status" id="new_status">
                        <div class="row">
                          <div class="col-md-6"> 
                          <div class="form-group">
                                        <label for="day_shift"><b>Day shift:</b></label>
                          </div>
                          </div>
                          <div class="col-md-6"> 
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-1"> <label for="day_shift_min">Minimum: <span style="color: red;">*</span></label></div>
                          <div class="col-md-2">
                          <div class="form-group">
                                       
                                        <input type="text" class="day_shift_min form-control" name="day_shift_min" id="day_shift_min" required="required"  placeholder=" " value="<?php echo $rota_settings[0]['day_shift_min']?>">
                          </div>
                          </div>  
                          <div class="col-md-1"> <label for="day_shift_max">Maximum: <span style="color: red;">*</span></label></div>
                          <div class="col-md-2">
                          <div class="form-group">
                                       
                                        <input type="text" class="day_shift_max form-control" name="day_shift_max" id="day_shift_max" required="required"  placeholder=" " value="<?php echo $rota_settings[0]['day_shift_max']?>">
                          </div>
                          </div>  
                        </div>
                        <div class="row">
                          <div class="col-md-6"> 
                          <div class="form-group">
                                        <label for="night_shift"><b>Night shift:</b></label>
                          </div>
                          </div>
                          <div class="col-md-6"> 
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-1"> <label for="night_shift_min">Minimum: <span style="color: red;">*</span></label></div>
                          <div class="col-md-2">
                          <div class="form-group">
                                       
                                        <input type="text" class="night_shift_min form-control" name="night_shift_min" id="night_shift_min"  placeholder=" " value="<?php echo $rota_settings[0]['night_shift_min']?>">
                          </div>
                          </div>  
                          <div class="col-md-1"> <label for="night_shift_max">Maximum: <span style="color: red;">*</span></label></div>
                          <div class="col-md-2">
                          <div class="form-group">
                                       
                                        <input type="text" class="night_shift_max form-control" name="night_shift_max" id="night_shift_max"  placeholder=" " value="<?php echo $rota_settings[0]['night_shift_max']?>">
                          </div>
                          </div>  
                        </div>
                         <div class="row">
                          <div class="col-md-3"> 
                          <div class="form-group">
                                        <label for="nurse_day_count">Nurse count(day shift): <span style="color: red;">*</span></label>
                                        <input type="text" class="nurse_day_count form-control" name="nurse_day_count" id="nurse_day_count" autocomplete="off" required="required"  value="<?php echo $rota_settings[0]['nurse_day_count']?>">
                          </div>
                          </div>
                          <div class="col-md-3"> 
                          <div class="form-group">
                                        <label for="nurse_night_count">Nurse count(night shift): <span style="color: red;">*</span></label>
                                        <input type="text" class="nurse_night_count form-control" name="nurse_night_count" id="nurse_night_count" autocomplete="off" required="required" value="<?php echo $rota_settings[0]['nurse_night_count']?>">
                          </div>
                          </div>
                          <div class="col-md-6"> 
                          </div>
                          
                        </div>
                        <div class="row">
                          <div class="col-md-6"> 
                          <div class="form-group">
                                        <label for="num_patients">No of patients: <span style="color: red;">*</span></label>
                                        <input type="text" class="num_patients form-control" name="num_patients" id="num_patients" value="<?php echo $rota_settings[0]['num_patients']?>">
                          </div>
                          </div>
                          <div class="col-md-6"> 
                          </div>
                          
                        </div>
                         <div class="row">
                          <div class="col-md-6"> 
                          <div class="form-group">
                                        <label for="Patients">1:1 Patients: <span style="color: red;">*</span></label>
                                        <input type="text" class="patients form-control" name="patients" id="designation" value="<?php echo $rota_settings[0]['one_one_patients']?>">
                          </div>
                          </div>
                          <div class="col-md-6"> 
                          </div>
                          
                        </div>
                          
                        <style>
 

label {
  display: inline-block;
margin-bottom: 0rem !important;
  cursor: pointer;
  position: relative;
}

.swal-wide {
    width: 1700px !important;
}
.sweet-alert {
    left: 25% !important;
  }
  
label .check-box-effect {
  display: inline-block;
  position: relative;
  background-color: transparent;
  width: 19px;
  height: 19px;
  border: 2px solid #dcdcdc; 
  border-radius: 10%;
}

label .check-box-effect:before {
  content: "";
  width: 0px;
  height: 2px;
  border-radius: 2px;
  background: #626262;
  position: absolute;
  transform: rotate(45deg);
  top: 7px;
  left: 3px;
  transition: width 50ms ease 50ms;
  transform-origin: 0% 0%;
}

label .check-box-effect:after {
  content: "";
  width: 0;
  height: 2px;
  border-radius: 2px;
  background: #626262;
  position: absolute;
  transform: rotate(305deg);
  top: 11px;
  left: 5px;
  transition: width 50ms ease;
  transform-origin: 0% 0%;
}

label:hover .check-box-effect:before {
  width: 5px;
  transition: width 100ms ease;
}

label:hover .check-box-effect:after {
  width: 11px;
  transition: width 150ms ease 100ms;
}

input[type="checkbox"] {
  display: none;
}

input[type="checkbox"]:checked + .check-box-effect {
  background-color: #009efb !important;
  transform: scale(1.25);
}

input[type="checkbox"]:checked + .check-box-effect:after {
  width: 10px;
  background: #333;
  transition: width 100ms ease 50ms;
}

input[type="checkbox"]:checked + .check-box-effect:before {
  width: 5px;
  background: #333;
  transition: width 100ms ease 50ms;
}

input[type="checkbox"]:checked:hover + .check-box-effect {
  background-color: #dcdcdc;
  transform: scale(1.25);
}

input[type="checkbox"]:checked:hover + .check-box-effect:after {
  width: 10px;
  background: #333;
  transition: width 100ms ease 50ms;
}

input[type="checkbox"]:checked:hover + .check-box-effect:before {
  width: 5px;
  background: #333;
  transition: width 100ms ease 50ms;
}
html {
  position: fixed;
  height: 100%;
  overflow: hidden;
}

body {
  margin: 0;
  width: 100vw; 
  height: 100vh;
  overflow-y: auto;
  overflow-x: hidden;
  -webkit-overflow-scrolling: touch;
}
</style>
                        
    <script type="text/javascript">
  var assigned_users=<?php print $assigned_usersid;?>;
  var unit_id=<?php print $unit_select;?>;
  var absent_shifts=<?php print $absent_shifts;?>;
</script>  
          <div class="col-md-12" style="padding-left: 0px;">
            <div id="accordian-3">
              <div class="card">
                <a class="card-header" id="heading33">
                <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                <h5 class="mb-0">Employees</h5>
                </button>
                </a>
                    <div id="collapse3" class="collapse" aria-labelledby="heading33" data-parent="#accordian-3">
                      <div class="card-body">       
                        <div class="row"> 
                          <div class="col-md-12"> 
                            <?php
                            if(count($shifts ) >0){
                                 $i=1;
                                foreach ($shifts as $shift) {  
                            ?> 
                                <?php $user=getStaffs($shift['id'], $unit_select);?>
                                <label for="wshiftname2" value="<?php echo $shift['id'];?>" style="color: <?php echo $shift['color_unit'];?>;"> <b> <?php echo $shift['shift_name'];?> </b>
                                <input type="checkbox" name="shift_user_<?php echo $shift['id'];?>" value="<?php echo $shift['id'];?>"  class="shift_check_<?php echo $shift['id'];?>">
                                  <span class="check-box-effect shift_select" data-shiftid="<?php echo $shift['id'];?>"></span>&nbsp; Select All 
                                </label>   
                                
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped" style="width: 99%;">
                                    <tbody>
                                    <tr>  
                                    <tr class="shift_<?php echo $shift['id'];?>" > <?php $user=getStaffs($shift['id'], $unit_select);?>
                                        <?php if (count($user)>0): ?>
                                            <?php 
                                            $c = 0; // Our counter
                                            $n = 6;
                                            foreach ($user as $value) {
                                              if($c % $n == 0 && $c != 0)  
                                              {
                                                echo '</tr><tr>';
                                              }
                                              $c++;
                                              ?>
                                              <?php $from_unit=null?>
                                              <?php $random_string=getRandomStrig();?>
                                              <td class="shift_data_<?php echo $shift['id'];?>"> 
                                                  <label class="">&nbsp;<input id="chkProdTomove" type="checkbox"   name="usercheck_<?php echo $i ?>" class="checkItem checkusers_<?php echo $shift['id'];?> shiftstaff_<?php echo $value['user_id']?> staff_<?php echo $value['user_id']?>_<?php echo $shift['id']?>_<?php echo $value['fname']?> <?php echo $value['lname']?>_<?php echo $value['unit_id']?>_<?php echo $value['designation_code']?>_<?php echo $value['shift_shortcut']?>" id="<?php echo $value['user_id']?>" value="<?php echo $value['user_id']?>_<?php echo $shift['id']?>_<?php echo $value['fname']?> <?php echo $value['lname']?>_<?php echo $value['unit_id']?>_<?php echo $from_unit?>_<?php echo $value['designation_code']?>_<?php echo $value['shift_shortcut']?>_<?php echo $value['gender']?>"  ><span class="check-box-effect"></span>&nbsp;<?php echo $value['fname']?> <?php echo $value['lname']?></label> 
                                              </td>
                                            <?php $i++; } ?>
                                        <?php else: ?>
                                            <td id="no_staff" class="no_staff_<?php echo $shift['id'];?>">No employees</td>
                                        <?php endif; ?>
                                    </tr>
                                    </tr>
                                    </tbody>
                                    </table>
                                </div> <br> 
                            <?php 
                                } }
                            ?>
                           <?php if( $unit_select!= 0){?>
                            <?php $shift_id = 0; ?>
                                <label for="wshiftname22"  style="color: #009efb";> <b> Not assigned </b>
                                  <input type="checkbox" name="non_assignstaff_check" class="non_assignuser_check">
                                  <span class="check-box-effect users_not_assigned"></span>&nbsp;Select all
                                </label>                          
                                <table id="myTable" class="table table-bordered table-striped non_asign_staff">
                                    <tbody>
                                        <tr class="non_staff"> <?php $user=getNotAssignedStaffs($unit_select);?>
                                        <?php if (count($user)>0): ?>
                                            <?php
                                            $c = 0; // Our counter
                                            $n = 6;
                                             foreach ($user as $value) {

                                              if($c % $n == 0 && $c != 0)  
                                              {
                                                echo '</tr><tr>';
                                              }
                                              $c++; 
                                              $shift['id']='';?>
                                              <?php $from_unit=null?>
                                              <?php $random_string=getRandomStrig();?>
                                               <td><label>&nbsp;<input type="checkbox"   name="usercheck_<?php echo $random_string ?>" class="user_not_assigned checkItem non_staffs_<?php echo $value['user_id']?>" id="<?php echo $value['user_id']?>" value="<?php echo $value['user_id']?>_<?php echo $shift_id ?>_<?php echo $value['fname']?> <?php echo $value['lname']?>_<?php echo$unit_select?>_<?php echo $from_unit?>_<?php echo $value['designation_code']?>_<?php echo $value['shift_shortcut']?>_<?php echo $value['gender']?>" style="width:20px;height:15px;" ><span class="check-box-effect"></span>&nbsp;<?php echo $value['fname']?> <?php echo$value['lname']?></label>
                                              </td>
                                            <?php }?>
                                        <?php else: ?>
                                            <td class="no_staffs_area">No employees</td>
                                        <?php endif; ?>
                                        </tr>
                                    </tbody>
                                </table> 
                            <?php } ?>
                            </div>
                            <div class="col-md-6">  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
              </div>
            </div>
          </div>
          <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="wfirstName2"> <b> Add employees from other units:</b> <span class="danger"></span></label>                
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <select class="custom-select form-control required" id="units" name="units">    <?php $otherunit = findotherunit($unit_select);?>
                  <option value="">Select unit</option>
                    <?php
                    foreach ($otherunit as $uni) { 
                    ?>
                    <?php $otherunit = findUnitHaveBranches($uni['id']);?>
                    <?php if (count($otherunit) == 0) { ?> 
                      <option value="<?php echo $uni['id']; ?>"<?php if(($unit_select)==$uni['id']){?> <?php }?>><?php echo $uni['unit_name']; ?></option> <?php } ?>
                    <?php 
                    } 
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-1"> 
                <button type="button" class="btn btn-success mr-2 addShift" id="addShift" style="float: right;">Add</button>
              </div>
              <div class="col-md-7">
              </div>
          </div>
          <div class="row"> 
              <div class="col-md-6">
                  <button type="button" data-action="update" class="btn btn-success mr-2 add" id="add" style="float: right;">Update shift</button>
              </div>
              <div class="col-md-6">
              </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
</div>