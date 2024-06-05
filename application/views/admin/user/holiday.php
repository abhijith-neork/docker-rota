<style>
 #img-loader-data {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('<?php echo base_url();?>/assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}

</style>

<div class="loader" id="img-loader-data"></div>
<div class="page-wrapper">
   <div class="container-fluid"> 
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Annual Leave Requests</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Annual Leave Requests</li>
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
                    <div class="col-12" style="padding-left: 45px;">
                    <div class="row">  <div class="col-md-12" style="padding-top: 30px;padding-right: 66px;"> 
                            <button <?php buttonaccess('Admin.Annual Leave.Add'); ?> class="btn float-right hidden-sm-down btn-success"
                            onclick="location.href='<?php echo base_url();?>admin/Holiday/addLeave'" style="height: 40px;margin-left:10px;width: 150px;">
                            <i class="mdi mdi-plus-circle"></i> Add Leave 
                        </button> 
                            
                        </div></div>
                        <div class="row">

                        <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group"> 
                                <label for="from_date">From date</label> 
                                <?php if($this->input->post('from_date')==''){?>
                                  <input type="text"  class="form-control required" id="from-datepicker" autocomplete="off" placeholder="dd/mm/yy" name="from_date" value="<?php echo $start_date; ?>"> 
                                <?php } else {?>
                                  <input type="text"  class="form-control required" id="from-datepicker" autocomplete="off" placeholder="dd/mm/yy" name="from_date" value="<?php echo $this->input->post('from_date'); ?>"> 
                                <?php } ?>
                            </div>
                        </div>
                
                        <div class="col-md-2" style="padding-right: 36px;">
                            <div class="form-group"> 
                                <label for="to_date">To date</label> 

                                <?php if($this->input->post('to_date')==''){?>
                                  <input type="text" class="form-control required" id="to-datepicker" autocomplete="off" placeholder="dd/mm/yy"  name="to_date" value="<?php echo $end_date; ?>"> 
                                <?php } else {?>
                                  <input type="text" class="form-control required" id="to-datepicker" autocomplete="off" placeholder="dd/mm/yy"  name="to_date" value="<?php echo $this->input->post('to_date'); ?>"> 
                                <?php } ?>
                            </div>
                        </div>

                        <div class="col-md-2" style="padding-right: 36px;">
                            <div class="form-group"> 
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

                        <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group">
                                    <label for="Location">Unit:</label> 
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

                         <div class="col-md-q" style="padding-left: 36px">
                              <div class="form-group">
                                              <label for="Status">Status</label> 
                                            <select required="required" class="form-control custom-select required status" id="status" name="status" placeholder="Select Status"><option value="0">--Select Status --</option> 
                                                  <option value="1">Active</option>
                                                  <option value="2">Inactive</option> 
                                                  <option value="3">Deleted</option> 
                                                  </select>
                              </div>
                          </div>

                        <div class="col-md-1">
                            <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                                 <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search">Search</button> 
                                    
                            </div>
                        </div>
                      
                        </div>
                    </div>  
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Name</th> 
                                                <th>Unit</th>
                                                <th>From Date</th> 
                                                <th>To Date</th> 
                                                <th>Hours</th>
                                                <th>Employee Message</th>
                                                <th>Applied Date&Time</th> 
                                                <th>Status
                                                <br>
                                                                                    <select  id="filter_status" class="select2 mb-2 select2-multiple filter_status" name="filter_status[]"  multiple="multiple">
                                                                                           <option value="1">Approved</option>
                                                                                           <option value="3">Cancelled</option>
                                                                                           <option value="0">Pending</option>
                                                                                           <option value="2">Rejected</option>
                                                                                    </select> 
                                                </th>
                                                <th>Leave Remaining</th>
                                                <th>Action</th> 
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php  
                                                foreach($user as $use) 
                                            { ?> 
                                            <tr> 
                                               <?php  $date=date("Y-m-d",strtotime($use['to_date']));  $leave_remaining=getLeaveRemainingbyuser($date,$use['user_id']);
                                               //print_r($leave_remaining['year']);
                                              if($use['remaining_leave']=='')
                                              {
                                                $Leaves=getLeaves($use['user_id'],$use['year']);
                                              }
                                              else
                                              {
                                                $Leaves=$use['remaining_leave'];
                                              }

                                               // if($Leaves >0)
                                               // {
                                               //  $result=$Leaves.' '.'('.$use['year'].')';
                                               // }
                                               // else
                                               // {
                                               //  $result='00.00';
                                               // }

                                              $year=getYear($use['year']);

                                              if(date("m") > 8)
                                              {
                                                  $currentyear = date("Y");
                                                  $nxtyear = date("Y")+1;
                                              }
                                              else
                                              {
                                                  $currentyear = date("Y")-1;
                                                  $nxtyear = date("Y");

                                              }

                                               // $currentyear = date("Y");
                                               // $nxtyear = date("Y")+1;
                                               $CYear = $currentyear."-".$nxtyear;
                                               
                                               if($leave_remaining['year']==$CYear)
                                                {  //print_r($leave_remaining['$hour']);
                                                    $leave_remaining=$use['annual_holliday_allowance']-$leave_remaining['hour'];//print_r($leave_remaining);
                                                    if((int)$leave_remaining <= 0){$leave_remaining='0.00';}else{$leave_remaining=$leave_remaining;}
                                                }
                                                else
                                                {
                                                    $leave_remaining=$use['actual_holiday_allowance']-$leave_remaining['hour'];
                                                    if((int)$leave_remaining <= 0){$leave_remaining='0.00';}else{$leave_remaining=$leave_remaining;}
                                                } 
                                                 $title=number_format($leave_remaining,2);

                                               ?>
                                               <?php if($use['status']==0){  $remaining=getRemainingLeave($use['user_id']);  $rem_day= number_format($remaining, 2);}?>
                                                <?php  $days=str_replace(":",".",$use['days']); $day=getPayrollformat(number_format($days,2),2); ?> 
                                                <?php  
                                                            if($use['status']==0)
                                                            {
                                                                $leave = $remaining;
                                                                $leaves = str_replace(".",":",$rem_day);
                                                            }
                                                            else
                                                            {
                                                                $leaves=str_replace(".",":",number_format($use['leave_remaining'],2));

                                                                if($leaves=='0:00')
                                                                {
                                                                    $leave=0;
                                                                }
                                                            }
                                                      
                                                ?> 
                                                <td><?php echo $use['user_id'];?></td>
                                                <td><?php echo $use['fname']." ".$use['lname'];?></td> 
                                                <td><?php echo $use['unit_name'];?></td>
                                                <td><span style="display: none;"><?php echo date("Y-m-d",strtotime($use['from_date'])); ?></span><?php echo date("d/m/Y",strtotime($use['from_date'])); ?></td>  
                                                <td><span style="display: none;"><?php echo date("Y-m-d",strtotime($use['to_date'])); ?></span><?php echo date("d/m/Y",strtotime($use['to_date'])); ?> </td> 
                                                <td id="days_<?php echo $use['id']; ?>"><?php echo number_format($day,2);?></td>
                                                <td><?php echo $use['comment'];?></td>
                                                <td><span style="display: none;"><?php echo $use['creation_date']; ?></span><?php echo date("d/m/Y H:i:s",strtotime($use['creation_date'])); ?></td>
                                                <td id="status_<?php echo $use['id'];?>">
                                                        <?php if($use['status']==1) { echo "Approved"; } else if($use['status']==2){ echo "Rejected";} else if($use['status']==0) {echo "Pending";}else{echo "Cancelled";} ?> 
                                                   
                                                </td>

                                                <td id="leaves_<?php echo $use['id']; ?>"> <?php echo $Leaves.' '.'('.$year.')'; ?></td> 
                                                <td> 
                                                    <?php if($use['status']==2){ ?>
                                                    <a <?php buttonaccess('Admin.Annual Leave.Approve'); ?> class="Approve_<?php echo $use['id'];?>" data-id="<?php  $use['id']; ?>" title="Approve" href="javascript:void(0);" onclick="approveFunction('<?php echo $use['id'];?>','<?php echo $use['from_date']?>','<?php echo $use['to_date']?>','<?php echo $use['unit_id']?>','<?php echo $use['designation_id']?>','<?php echo $use['user_id']?>','<?php echo $use['year']?>','<?php echo $use['remaining_leave'];?>','<?php echo $year;?>','<?php echo $days;?>')" value="<?php echo $use['user_id'];?>" ><i class="fa fa-check-circle"></i></a>
                                                    <?php }?>
                                                    <?php if($use['status']==1){  ?>
                                                     <a <?php buttonaccess('Admin.Annual Leave.Reject'); ?> class="Reject_<?php echo $use['id'];?>" data-id="<?php  $use['id']; ?>" title="Reject" href="javascript:void(0);" onclick="rejectFunction('<?php echo $use['id']; ?>','<?php echo $use['user_id'];?>','<?php echo $use['status'];?>','<?php echo $days;?>','<?php echo $leaves;?>','<?php echo $use['year']?>','<?php echo $use['remaining_leave'];?>','<?php echo $year;?>')"  value="<?php echo $use['user_id'];?>" ><i class="fas fa-ban"></i></a>
                                                    <?php }?>

                                                    <?php if($use['status']==0){?>
                                                    <a <?php buttonaccess('Admin.Annual Leave.Approve'); ?> class="Approve_<?php echo $use['id'];?>" data-id="<?php  $use['id']; ?>" title="Approve" href="javascript:void(0);" onclick="approveFunction('<?php echo $use['id'];?>','<?php echo $use['from_date']?>','<?php echo $use['to_date']?>','<?php echo $use['unit_id']?>','<?php echo $use['designation_id']?>','<?php echo $use['user_id']?>','<?php echo $use['year']?>','<?php echo $use['remaining_leave'];?>','<?php echo $year;?>','<?php echo $days;?>')" value="<?php echo $use['user_id'];?>" ><i class="fa fa-check-circle"></i></a>

                                                     <a <?php buttonaccess('Admin.Annual Leave.Reject'); ?> class="Reject_<?php echo $use['id'];?>" data-id="<?php  $use['id']; ?>" title="Reject" href="javascript:void(0);" onclick="rejectFunction('<?php echo $use['id']; ?>','<?php echo $use['user_id'];?>','<?php echo $use['status'];?>','<?php echo $days;?>','<?php echo $leaves;?>','<?php echo $use['year']?>','<?php echo $use['remaining_leave'];?>','<?php echo $year;?>')"  value="<?php echo $use['user_id'];?>" ><i class="fas fa-ban"></i></a>
                                                    <?php }?>

                                                <?php if($use['status']==2 || $use['status']==1){?>

                                                <span class="Accept_button_<?php echo $use['id'];?>" style="display: none;">
                                                    <a <?php buttonaccess('Admin.Annual Leave.Approve'); ?> class="Approve" data-id="<?php  $use['id']; ?>" title="Approve" href="javascript:void(0);" onclick="approveFunction('<?php echo $use['id'];?>','<?php echo $use['from_date']?>','<?php echo $use['to_date']?>','<?php echo $use['unit_id']?>','<?php echo $use['designation_id']?>','<?php echo $use['user_id']?>','<?php echo $use['year']?>','<?php echo $use['remaining_leave'];?>','<?php echo $year;?>','<?php echo $days;?>')" value="<?php echo $use['user_id'];?>" ><i class="fa fa-check-circle"></i></a>

                                                </span>
                                                <span class="Reject_button_<?php echo $use['id'];?>"  style="display: none;">
                                                    
                                                    <a <?php buttonaccess('Admin.Annual Leave.Reject'); ?> class="Reject" data-id="<?php  $use['id']; ?>" title="Reject" href="javascript:void(0);" onclick="rejectFunction('<?php echo $use['id']; ?>','<?php echo $use['user_id'];?>','<?php echo $use['status'];?>','<?php echo $days;?>','<?php echo $leaves;?>','<?php echo $use['year']?>','<?php echo $use['remaining_leave'];?>','<?php echo $year;?>')"  value="<?php echo $use['user_id'];?>" ><i class="fas fa-ban"></i></a>
                                                </span>
                                                <?php }?>

                                                <?php if($use['status']==3){?>

                                                   <a  class="Reject" data-container="body" title="Annual leave messages" href="javascript:void(0);" data-html="true" data-toggle="popover" data-placement="right" data-content="<?php  echo "<b>Cancelled by</b> " . $use['fname'].' '.$use['lname'].''.'.'.' ';  
                                                    echo '<br><br>'; ?> "><i class="fa fa-envelope"></i></a> 

                                                <?php }else{?>

                                                   <a  class="Reject" data-container="body" title="Annual leave messages" href="javascript:void(0);" data-html="true" data-toggle="popover" data-placement="right" data-content="<?php  $comments=getMessage($use['id']); 
                                                    foreach($comments as $msg){ 
                                                      if($msg['holi_stat']!=0){
                                                       if($msg['status']==1) echo "<b>Approved by</b> " . $msg['fname'].' '.$msg['lname'].''.':'.' ';   else echo "<b>Rejected by</b> " . $msg['fname'].' '.$msg['lname'].':'.' '; 
                                                    echo ($msg['comment'].','.date("d/m/Y  H:i:s",strtotime($msg['date'])));  
                                                    echo '<br><br>';   
                                                    }                                           
                                                    } 
                                                    ?> "><i class="fa fa-envelope"></i></a> 
                                                <?php }?>
                                                </td>  

                                            </tr>
                                            <?php } ?>                                                                                    
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
 <script type="text/javascript"> 
var jobe_roles=<?php print $job_roles;?>; 

</script>