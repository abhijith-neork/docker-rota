<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Request Annual Leave</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Request Annual Leave</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">               
                            <div class="card-body">
                                <?php if( $message!=''){?>
                                <div class="alert alert-success" role="alert"><?php echo $message;?></div>
                                <?php } ?> 
                               <?php if( $error!=''){?>
                                <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
                                <?php } ?>
                                <?php if (validation_errors()){?>
                                <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
                                <?php } ?>
                                <!-- <div class="alert alert-warning div-warning-msg" style="display: none;text-align: center;" role="alert">
                                  Please check the dates, you have selected offdays/holidays to apply leave.
                                </div> -->
                                <?php if($this->session->flashdata('error')):?>
                                <p class="success-msg" id="success-alert"
                                        style="color: red;">
                                  <?php echo $this->session->flashdata('error');?>
                                </p>
                                <?php endif;?> 
                            </div>
                            <form enctype="multipart/form-data" name="add" id="add"
                                method="post" action="<?php echo base_url('staffs/leave/');?>applyLeave">
                                <div class="form-group">
                                    <label for="wdob2">Start date:</label>
                                    <input type="text" class="form-control" onkeydown="return false" placeholder="dd/mm/yyyy" id="start_date" value="<?php echo $this->input->post('start_date');?>" name="start_date" autocomplete="off" required="required">
                                <?php 
                                         if($user[0]['annual_allowance_type']==2)
                                                 $holidaytype = ' hours(hh:mm)';
                                                 else
                                                $holidaytype ='';
                                         ?>
                                         <input type="hidden"  id="user_id" value="<?php echo $this->session->userdata('user_id');?>" name="user_id">
                               <!--  <div class="row">
                                            <div class="col-md-6">  
                                                    <select class="custom-select form-control required" id="start_time" name="start_time" onchange="checkStartTime()">
                                                        <?php $i='';$n=24;
                                                        for($i=1;$i<=$n;$i++) { if($i<10){?>
                                                         <option <?php if($this->input->post('start_time')== '0'.$i) { ?>selected="selected" <?php } ?> value="<?php echo '0'.$i;?>"><?php echo $i;?></option> <?php } else {?>
                                                         <option <?php if($this->input->post('start_time')== $i) { ?>selected="selected" <?php } ?> value="<?php echo $i;?>"><?php echo $i;?></option><?php } ?>
                                                        <?php } ?>
                                                     </select> 
                                                     <p id="hours"><h7>Hours:</h7></p>
                                            </div> 
                                            <div class="col-md-6">  
                                                    <select class="custom-select form-control required" id="start_time1" onchange="checkEndTime()" name="start_time1">
                                                        <option <?php if($this->input->post('start_time1')== 00) { ?>selected="selected" <?php } ?> value="00">00</option>
                                                        <option <?php if($this->input->post('start_time1')== 15) { ?>selected="selected" <?php } ?>  value="15">15</option>
                                                        <option <?php if($this->input->post('start_time1')== 30) { ?>selected="selected" <?php } ?> value="30">30</option>
                                                        <option <?php if($this->input->post('start_time1')== 45) { ?>selected="selected" <?php } ?>  value="45">45</option>
                                                     </select> 
                                                    <p id="minutes"><h7>Minutes:</h7></p>
                                            </div>
                                </div> -->
                            </div>
                                <div class="form-group">
                                    <label for="wdob2">End date:</label>
                                    <input type="text" class="form-control" onkeydown="return false" id="end_date" placeholder="dd/mm/yyyy" name="end_date" value="<?php echo $this->input->post('end_date');?>" autocomplete="off" required="required">
                                   <!-- <div class="row">
                                            <div class="col-md-6">  
                                                    <select class="custom-select form-control required" id="end_time" name="end_time" onchange="checkEndTime()">
                                                        <?php $i='';$n=24;
                                                        for($i=1;$i<=$n;$i++) { if($i<10){?>
                                                         <option <?php if($this->input->post('end_time')== '0'.$i) { ?>selected="selected" <?php } ?> value="<?php echo '0'.$i;?>"><?php echo $i;?></option> <?php } else {?>
                                                         <option <?php if($this->input->post('end_time')== $i) { ?>selected="selected" <?php } ?> value="<?php echo $i;?>"><?php echo $i;?></option><?php } ?>
                                                        <?php } ?>
                                                     </select> 
                                                     <p id="hours1"><h7>Hours:</h7></p>
                                            </div> 
                                            <div class="col-md-6">  
                                                    <select class="custom-select form-control required" onchange="checkEndTime()" id="end_time1" name="end_time1">
                                                        <option <?php if($this->input->post('end_time1')== 00) { ?>selected="selected" <?php } ?> value="00">00</option>
                                                        <option <?php if($this->input->post('end_time1')== 15) { ?>selected="selected" <?php } ?>  value="15">15</option>
                                                        <option <?php if($this->input->post('end_time1')== 30) { ?>selected="selected" <?php } ?> value="30">30</option>
                                                        <option <?php if($this->input->post('end_time1')== 45) { ?>selected="selected" <?php } ?>  value="45">45</option>
                                                     </select> 
                                                    <p id="minutes1"><h7>Minutes:</h7></p>
                                            </div> 
                                </div>-->
                                <div id="show_error" class="alert alert-warning" style="display:none;font-weight: bold;"></div> 
                                </div>
                                <div class="form-group">
                                    <label for="wdob2"><?php echo ucwords($holidaytype) ; ?></label> 
                                    <input type="text" class="form-control required" id="total_days" value="<?php echo $this->input->post('total_days');?>" name="total_days" data-toggle="tooltip" placeholder="hh:mm"   required="required" >
                                    <input type="hidden" name="calc_hours" id="calc_hours" value="<?php echo $this->input->post('calc_hours');?>">
                                    <div id="show_hours" class="alert alert-warning" style="display:none;font-weight: bold;"></div> 
                                </div>
                                <div class="form-group">
                                    <label for="wnotes2">Message</label>
                                    <textarea class="description form-control required " id="message" value="<?php echo $this->input->post('message');?>" name="message" required="required" cols="40"  placeholder="Enter message"><?php echo $this->input->post('message');?></textarea>
                                </div>
                                <input type="hidden" class="form-control" id="lop" value="<?php echo $lop ;?>" name="lop" > 
                                <input type="hidden" class="form-control" id="annual_holliday_allowance" value="<?php echo $annual_allowance ;?>" name="annual_holliday_allowance" >   
                                <input type="hidden" class="form-control" id="total_leave_applied" value="<?php echo $total_leave_applied ;?>" name="total_leave_applied" >     

                                 <input type="hidden" class="form-control" id="without_offday_holiday" value="" name="without_offday_holiday[]" > 

                                <button type="" class="btn btn-primary" id="try">Apply</button>
                                <!-- <button type="" onclick="lopWarning()" class="btn btn-primary" id="try">Apply</button> -->
                                <button type="button" class="btn waves-effect waves-light btn-secondary" id="cancel-button">Cancel</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var annual_allowance_type =  <?php  echo $user[0]['annual_allowance_type'];?>;
var user_offday =  <?php print_r($user_offday);?>;
var holidayDates =  <?php print_r($holidayDates);?>;
var selected_date= '<?php print_r($selected_date);?>';
</script>
