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
    <div class="container-fluid" style="padding-left: 36px;"> 
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
                                                                <li class="breadcrumb-item active">Timesheet</li>
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
            <form method="POST" action="" id="frmViewpayrollReport"   name="frmViewpayrollReport">
            <div class="row">
              <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Unit:</label> 
                                        <select class="form-control custom-select unitdata" id="unitdata" name="unitdata" placeholder="Select Unit"> 
                                        <option value="none"><?php echo "------Select unit-------"?></option> 
                                        <?php foreach($unit as $cl) { ?>
                                            <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
              <!--   <?php echo $user_post;?> -->
               <div class="col-md-2" style="padding-right: 36px">
                    <div class="form-group">
                                    <label for="Status">Status:</label> 
                                        <select class="form-control custom-select status" id="status" name="status" placeholder="Select Status"> 
                                        <option value="1"  <?php if($status=="1"){?>  selected="selected" <?php }?> >Active</option>
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
                <!-- <div class="form-group">
                                    <label for="Location">Year</label> 
                                        <select required="required" class="form-control custom-select required year" id="year" name="year" placeholder="Select Year">
                                        <option value="none"><?php echo "------Select year-------"?></option>
                                         <?php $n=25; for($i=0;$i<$n;$i++){ $a=2010; $b=$a+$i; $c=$b;?> 
                                       
                                        <option  value="<?php echo $c; ?>" <?php if($start_year==$c){?> selected="selected" <?php }?>><?php echo $c; ?></option><?php }?> 
                                        </select> 
                    </div> -->
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
              <!--  <div class="form-group">
                        <label for="Location">Month</label> 
                                       <select class="SelectMonth custom-select form-control required" id="month" name="month">
                                       <option value="none"><?php echo "--Select month--"?></option>
                             
                                        <option value="01"  <?php if($month=="01"){?>  selected="selected" <?php }?> >January</option>
                                        <option value="02"  <?php if($month=="02"){?>  selected="selected" <?php }?> >February</option>
                                        <option value="03"  <?php if($month=="03"){?>  selected="selected" <?php }?> >March</option>
                                        <option value="04"  <?php if($month=="04"){?>  selected="selected" <?php }?> >April</option>
                                        <option value="05"  <?php if($month=="05"){?>  selected="selected" <?php }?> >May</option>
                                        <option value="06"  <?php if($month=="06"){?>  selected="selected" <?php }?> >June</option>
                                        <option value="07"  <?php if($month=="07"){?>  selected="selected" <?php }?> >July</option>
                                        <option value="08"  <?php if($month=="08"){?>  selected="selected" <?php }?> >August</option>
                                        <option value="09"  <?php if($month=="09"){?>  selected="selected" <?php }?> >September</option>
                                        <option value="10"  <?php if($month=="10"){?> selected="selected" <?php }?> >October</option>
                                        <option value="11"  <?php if($month=="11"){?> selected="selected" <?php }?> >November</option>
                                        <option value="12"  <?php if($month=="12"){?> selected="selected" <?php }?> >December</option>
 
                                    
                                    </select> 
                                    
                    </div> -->
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
            <div class="row" > 
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                                <th>UserID</th> 
                                                                <th>Name</th> 
                                                                <th>Shift</th> 
                                           
                                                                <th>Date</th>
                                                                <th>Daily Census Note</th>
                                                                <th>Checked In</th>
                                                                <th>CheckedOut</th>
                                                                <th>In Time</th>
                                                                <th>Additional Hours</th> 
                                                                <th>Total Hours</th>
 
                                                                                    
                                                    </tr>
                                                </thead>
                                                    <tbody>  
                                                    
                                                  
                                                    <?php  $i=0 ;foreach($timesheetdata as $timesheet)
                                                   { 
                                               
                                                            ?>
                                                     <tr>
                                                      <td><?php echo $timesheet['user_id'];?></td> 
                                                                <td><?php echo $timesheet['name'];?></td> 
                                                                <td><?php echo $timesheet['shift_name'];?></td> 
                                              
                                                                <td><?php echo $timesheet['date'];?></td>
                                                                <td>
                                                                                            <?php $Note=GetCensus($timesheet['date'],$timesheet['user_id']);
                                                              if(count($Note)==0)
                                                              {
                                                                     echo "";
                                                              }
                                                              else 
                                                              {
                                                                   foreach ($Note as $value) { ?>

                                                                    <span class="dot"><?php echo ".";?></span>
                                                                    <span><?php echo " ".$value['comment'];echo ".<br>";echo ".<br>";?></span>

                                                                  <?php }
                                                              }

                                                              ?>
                                                                </td>
                                                                <td><?php echo $timesheet['start'];?></td>
                                                                <td><?php echo $timesheet['end'];?></td>
                                                                   <td><?php  
                                                                   
                                                                   $st_time = explode(" ", $timesheet['start']);
                                                                   $en_time = explode(" ", $timesheet['end']);
                                                                   
                                                                    $userSchedule= getSchedule($timesheet['date'],$timesheet['user_id']); 
                                                                   
                                                                   
                                                                   $date1 = strtotime($timesheet['start']);
                                                                   $date2 = strtotime($timesheet['end']);  
                                                                   $diff = abs($date2 - $date1); 
                                                                   
                                                                   $years = floor($diff / (365*60*60*24));                                                                 
                                                             
                                                                   $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));                                                                   
                                                           
                                                                   $days = floor(($diff - $years * 365*60*60*24 -$months*30*60*60*24)/ (60*60*24));
                                                                   
                                                                   $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));  
                                                                   
                                                                   $minutes = floor(($diff - $years * 365*60*60*24
                                                                       - $months*30*60*60*24 - $days*60*60*24
                                                                       - $hours*60*60)/ 60);
                                                                   
                                                                   
                                                                   $seconds = floor(($diff - $years * 365*60*60*24
                                                                       - $months*30*60*60*24 - $days*60*60*24
                                                                       - $hours*60*60 - $minutes*60));
                                                                   
                                                                   printf(" %d:"
                                                                       . "%d:%d", $hours, $minutes, $seconds); 
                                                                   ?></td>
                                                                <td>  
                                                                
                                                            <?php 
                                                            $break_status=$timesheet['date'].'_'.$timesheet['user_id'].'_'.$timesheet['fname']; 
                                                            $break_status_new=$payroll_data[$i-3]['date'].'_'.$payroll_data[$i-1]['user_id'].'_'.$payroll_data[$i-1]['fname']; 
                                                            ?>
                                                            <?php 
                                                            if($en_time[1]!='00:00:00' && $st_time[1]!='00:00:00' ){

                                                                $hour=getPayrollExtrahour($st_time[1],$en_time[1],$date,$timesheet['time_log_date'],$userSchedule[0]['start_time'],$userSchedule[0]['end_time'],$timesheet['break'],$break_status,$break_status_new); 
                                                              $time=date("H:i",strtotime($hour['time']));
                                                              $hour_new=$hour['hour'];
                                                            }
                                                            else
                                                            {
                                                              $time='00:00';
                                                              $hour_new='00:00';
                                                            }

                                                            ?>

                                                                    <?php if($en_time[1]!='00:00:00' && $st_time[1]!='00:00:00' )
                                                                    {?>
                                                                        <?php  $in_time=$hour['hour'];?>
                                                                    <?php } else{?>
                                                                        <?php  $in_time='00:00';?>
                                                                    <?php }?>  
                                                                
                                                                <?php 
                                                                $user_id=$timesheet['user_id'];
                                                                $shift_id=$timesheet['shift_id'];
                                                                  $date=$timesheet['date'];
                                                                  $unit_id=$timesheet['unit_id'];
                                                                  $timelog_id=$timesheet['timelogid'];
                                                                  ?>
                                                                   <?php  $user=getHour($user_id,$shift_id,$date,$unit_id,$timelog_id);

                                                                    $shift_cat=getshiftcategory($user_id);
                                                                    //print_r($user);

                                                                   // print_r($user);
                                                                   // print_r($timesheet['shift_category']);

                                                                    if(empty($user))
                                                                   { //print 'empty';
                                                                      if($timesheet['shift_category']==2)
                                                                      {
                                                                        $shift_category=2;
                                                                      }
                                                                      else if($timesheet['shift_category']==0 || $timesheet['shift_category']=='')
                                                                      {
                                                                        $shift_category=$shift_cat;
                                                                      }
                                                                      else
                                                                      {
                                                                        $shift_category=1;
                                                                      }
                                                                   }
                                                                   else if($user[0]['day_additional_hours'])
                                                                   { //print "hii";
                                                                     $shift_category=1;
                                                                   }
                                                                   else if($user[0]['night_additional_hours'])
                                                                   { //print "hello";
                                                                     $shift_category=2;
                                                                   }
                                                                   else
                                                                   { //print 'kooi';
                                                                     if($timesheet['shift_category']==2)
                                                                      {
                                                                        $shift_category=2;
                                                                      }
                                                                      else if($timesheet['shift_category']==0 || $timesheet['shift_category']=='')
                                                                      {
                                                                        $shift_category=$shift_cat;
                                                                      }
                                                                      else
                                                                      {
                                                                        $shift_category=1;
                                                                      }
                                                                   }


                                                                    ?> 
                                                                    
                                                      
                                                                   <?php   if(empty($user)){ ?>



                                                                     <p>  
                                                                      <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="">&nbsp;</label>
                                                                      <label id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value="">&nbsp;</label> </p>
                                                                     <label id="comment_<?php echo $i;?>" class="comment" style="width:100px;" value=""></label> 
                                                                     

                                                                  <?php }else {?>

                                                                    <p>
                                                                      <?php if($user[0]['day_additional_hours']){ 
                                                                        ?> 
                                                                        <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="<?php echo $user[0]['day_additional_hours'];?>"><?php echo settimeformat(getPayrollformat1($user[0]['day_additional_hours']));?></label>
                                                                        <label style="display: none" id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value=""><?php echo $user[0]['day_additional_hours'];?></label>
                                                                      <?php }else { ?>
                                                                        <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="<?php echo $user[0]['night_additional_hours'];?>"><?php echo settimeformat(getPayrollformat1($user[0]['night_additional_hours']));?></label>
                                                                        <label style="display: none" id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value=""><?php echo $user[0]['night_additional_hours'];?></label>
                                                                      <?php }?>
                                                                     
                                                                    </p>
                                                                    <label id="comment_<?php echo $i;?>" class="comment" style="width:100px;" value=""><?php echo $user[0]['comment'];?></label> 

                                                                 <?php }?>
                                                                 <label style="display: none;" id="shiftcategory_<?php echo $i;?>" class="shiftcategory" style="width:100px;" value="<?php echo $shift_category; ?>"><?php echo $shift_category; ?></label> 
                                                                  <?php if($userSchedule[0]['shift_hours']<$in_time) { $shift_hours=$userSchedule[0]['shift_hours']; } else {$shift_hours=$in_time;}?>
                                                                  <a id="edit" class="edit" href="#" onclick="edit('<?php echo $timesheet['user_id']; ?>','<?php echo $timesheet['date']; ?>','<?php echo $timesheet['shift_id']; ?>','<?php echo $timesheet['unit_id']; ?>','hours_<?php echo $i;?>','edit_<?php echo $i;?>','comment_<?php echo $i;?>','<?php echo $shift_hours;?>','<?php echo $i; ?>','<?php  echo $timesheet['timelogid']; ?>')" value="">   
                                                                        <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){?>
                                                                    <label id="edit_<?php echo $i; ?>" class="edit" style="width:100px;" value="" style="float: right;">Add</label>
                                                                        <?php }else{?>
                                                                    <label id="edit_<?php echo $i; ?>" class="edit" style="width:100px;" value="" style="float: right;">Edit</label>
                                                                         <?php }?>
                                                                   </a></td> 
                                                              
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                <?php  if($userSchedule[0]['shift_hours']){ ?>

                                                        <?php if($userSchedule[0]['shift_hours']<$in_time) {?>
                                                            <?php if($user[0]['day_additional_hours']){ ?>
                                                              <?php $o = strtotime($userSchedule[0]['shift_hours'])+strtotime($user[0]['day_additional_hours'])-strtotime('00:00:00'); //print_r($o);?>
                                                            <?php } else { ?>
                                                              <?php $o = strtotime($userSchedule[0]['shift_hours'])+strtotime($user[0]['night_additional_hours'])-strtotime('00:00:00'); //print_r($o);?>
                                                            <?php }?>

                                                            <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){ ?>
                                                            <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1(date("H:i", strtotime($userSchedule[0]['shift_hours']))));?> </td>
                                                            <?php } else { ?>
                                                            <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1(date('H:i', $o)));?> </td>
                                                            <?php }?>

                                                         <?php } else {?>

                                                                  <?php 
                                                                    if($user[0]['day_additional_hours']!='00:00' || $user[0]['night_additional_hours']!='00:00')
                                                                    {
                                                                      if($user[0]['day_additional_hours']){
                                                                        $o=AddTimes($in_time,$user[0]['day_additional_hours'],$break_status,$break_status_new);
                                                                      }else{
                                                                        $o=AddTimes($in_time,$user[0]['night_additional_hours'],$break_status,$break_status_new);
                                                                      }                  
                                                                    }
                                                                    else
                                                                    {
                                                                      $o=$in_time;
                                                                    }

                                                                     ?>

                                                                  <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){?> 

                                                                        <?php if($en_time[1]=='00:00:00'){ ?>
                                                                     

                                                                        <td id="totalhour_<?php echo $i; ?>"><?php echo "00.00";?> </td>

                                                                        <?php } else{?>

                                                                        <td id="totalhour_<?php echo $i; ?>"><?php  print_r(settimeformat(getPayrollformat1($hour_new)));?> </td>

                                                                  <?php }} else {?>
                                                                  <td id="totalhour_<?php echo $i; ?>"><?php  echo settimeformat(getPayrollformat1($o)); ?> </td>
                                                                  <?php }?>

                                                              <?php }?>

                                                        <?php }else{?>

                                                            <?php if($user[0]['day_additional_hours'] || $user[0]['night_additional_hours']){?>
                                                              <?php if($user[0]['day_additional_hours']){?>
                                                                <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1($user[0]['day_additional_hours']));?> </td>
                                                              <?php } else {?>
                                                                <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1($user[0]['night_additional_hours']));?> </td>
                                                              <?php }?>
                                                            <?php } else {?>
                                                              <td id="totalhour_<?php echo $i; ?>"><?php echo "00.00";?> </td> 
                                                            <?php }?>

                                                        <?php }?>
                                                                
                                                                
                                                                </td>
                                                                 </tr>
                                                    <?php  $i++; } //}?>
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