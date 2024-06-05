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
                                                                <th style="display: none;">Shift Hours</th>
                                                                <!-- <th>Shift Start Time</th> 
                                                                <th>Shift End Time</th>  -->
                                                                <th>Date</th>
                                                                <th>Daily Census Note</th>
                                                                <th>Checked In</th>
                                                                <th>CheckedOut</th>
                                                                <th>In Time</th>
                                                                <th>Additional Hours</th> 
                                                                <th>Total Hours</th>
                                                                <th>Status</th>

                                                                                    
                                                    </tr>
                                                </thead>
                                                    <tbody>   
                                                    <?php  for($i=0;$i<count($payroll_data);$i++)
                                                     {  
                                                      if($payroll_data[$i]['user_id']==$payroll_data[$i+1]['user_id']){ 
                                                        if($payroll_data[$i]['time_to']=='00:00:00' && !empty($payroll_data[$i+1]['time_to']))
                                                        {
                                                        $payroll_data[$i]['time_to']= $payroll_data[$i+1]['time_to'];
                                                        $date=$payroll_data[$i+1]['time_log_date'];
                                                        }
                                                        else
                                                        {
                                                        $payroll_data[$i]['time_to']= $payroll_data[$i]['time_to'];
                                                        $date=$payroll_data[$i]['time_log_date'];
                                                        }
                                                      }
                                                      else
                                                      {
                                                        $payroll_data[$i]['time_to']= $payroll_data[$i]['time_to'];
                                                        $date=$payroll_data[$i]['time_log_date'];
                                                      }
                                                    ?>
                                                    <?php  

                                                    if($payroll_data[$i]['time_from']=='00:00:00')
                                                    {
                                                      if($payroll_data[$i]['time_to']==$payroll_data[$i-1]['time_to'])
                                                      {
                                                        $pay_status=1;
                                                      }
                                                      else
                                                      {
                                                         $pay_status=0;
                                                      }
                                                    }
                                                    else
                                                    {
                                                       $pay_status=0;
                                                    }

                                                    ?> 
                                                        <?php  if($pay_status==1){  ?>  <!-- time_from 0 and time_to of current and previous are equal -->


                                                          <?php if($payroll_data[$i]['date']!=$payroll_data[$i-1]['date']){?>


                                                           <?php $userSchedule= getSchedule($payroll_data[$i]['date'],$payroll_data[$i]['user_id']); ?>
                                                         <?php 
                                                             if($userSchedule[0]['id']==16)
                                                            {
                                                                if($payroll_data[$i]['time_from']=='00:00:00')
                                                                {  
                                                                  $date1 = str_replace('-', '/', $payroll_data[$i]['time_log_date']);
                                                                  $tomorrow1 = date('Y-m-d',strtotime($date1 . "-1 days"));
                                                                  $datenew=$tomorrow1;
                                                                  $stat="from";
                                                                }
                                                                else if($payroll_data[$i]['time_to']=='00:00:00')
                                                                {  
                                                                  $date1 = str_replace('-', '/', $payroll_data[$i]['time_log_date']);
                                                                  $tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
                                                                  $datenew=$tomorrow;
                                                                  $stat="to";
                                                                } 
                                                                else
                                                                {
                                                                  $datenew=$payroll_data[$i]['time_log_date'];
                                                                  $stat="not";

                                                                }
                                                                $date_time=getDateAndTime($datenew,$payroll_data[$i]['user_id']);
                                                                if(!empty($date_time))
                                                                {
                                                                  if($stat=='from')
                                                                  { //print "hii";
                                                                    $payroll_data[$i]['time_from']=$date_time[0]['time_from'];
                                                                    $payroll_data[$i]['time_log_date']=$datenew;
                                                                    $payroll_data[$i]['date']=$datenew;
                                                                  }
                                                                  else if($stat=='to')
                                                                  { //print "hello";
                                                                    $payroll_data[$i]['time_to']=$date_time[0]['time_to'];
                                                                    $date=$datenew;
                                                                  }
                                                                  else
                                                                  {

                                                                  }

                                                                }
                                                                else
                                                                {
                                                                   
                                                                }
                                                            }
                                                            ?>

                                                        <?php if(($payroll_data[$i]['date']!=$payroll_data[$i+1]['date']) || ($payroll_data[$i]['shift_name']!='Offday')){?> 
                                                        <!-- date not equal to next date and shfit not offday -->

                                                        <?php if($userSchedule[0]['id']!=16 || $payroll_data[$i]['time_log_date']!=$tomorrow1 ) {?>
                                                        <!-- not night shift and date not equal to previous date -->

                                                        <tr>
                                                          <td><?php echo $payroll_data[$i]['user_id'];?> </td> <!-- user_id -->
                                                          <td><?php echo $payroll_data[$i]['fname'].' '.$payroll_data[$i]['lname'];?> </td> <!-- name -->

                                                            <?php if($userSchedule[0]['shift_name']==''){ $shift_name='No Shift';}else{$shift_name=$userSchedule[0]['shift_name']; }

                                                            if($shift_name=='No Shift') 
                                                              {$new_shift=$payroll_data[$i]['shift_name']; }else
                                                              {$new_shift=$shift_name; }
                                                              ?>
                                                          <td><?php echo $new_shift;?> </td> <!-- shift name -->

                                                           
                                                          <td id="shifthour_<?php echo $i; ?>" style="display: none;"><?php echo $userSchedule[0]['shift_hours'];?></td> 
                                                        <!--   <td><?php echo $userSchedule[0]['start_time'];?> </td>
                                                          <td><?php echo $userSchedule[0]['end_time'];?> </td> -->
                                                                   <?php $check_holiday=checkHoliday($payroll_data[$i]['date']);?>
                                                                   <?php if ($check_holiday == "true") { ?>
                                                                        <td><span style="display: none;"><?php echo $payroll_data[$i]['date']; ?></span><?php echo corectDateFormat($payroll_data[$i]['date']);?>&nbsp&nbsp(<span style="color:red">Bank Holiday</span>)</td>
                                                                   <?php } else { ?>
                                                                        <td><span style="display: none;"><?php echo $payroll_data[$i]['date']; ?></span><?php echo corectDateFormat($payroll_data[$i]['date']);?></td> <!-- date -->
                                                                   <?php } ?>
                                                            <td> 
                                                              <?php $Note=GetCensus($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
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

                                                            <?php if($payroll_data[$i]['time_from']!='00:00:00' && $payroll_data[$i]['time_from']!='') 
                                                            {  

                                                              if($payroll_data[$i]['time_to']=='00:00:00' || $payroll_data[$i]['time_to']=='')
                                                              { 
                                                                  $checkin_date=$payroll_data[$i]['time_log_date'];
                                                                  $pre_date=$payroll_data[$i-1]['time_log_date'];
                                                                  $next_date=$payroll_data[$i+1]['time_log_date'];

                                                                  $current_timefrom=$payroll_data[$i]['time_from'];
                                                                  $next_timefrom=$payroll_data[$i+1]['time_from'];
                                                                  $prev_timefrom=$payroll_data[$i-1]['time_from']; 
                                                                  


                                                                  if($prev_timefrom=='')
                                                                  {  
                                                                    if(($next_date!='')&&($next_date==$checkin_date))
                                                                    { 
                                                                       $current=date("H:i:s"); 
                                                                       $checkin_time=$current_timefrom;
                                                                       $new=getCheckoutDetails($current,$checkin_time); 
                                                                       if($new>13)
                                                                       {
                                                                        $status_new="Missed Check Out";
                                                                       }
                                                                       else
                                                                       {
                                                                        $status_new='';
                                                                       }
                                                                    }
                                                                    else
                                                                    { 
                                                                      $status_new='';
                                                                    }
                                                                  }
                                                                  else
                                                                  {  
                                                                    if(($pre_date!='')&&($pre_date==$checkin_date))
                                                                    {    
                                                                        if($prev_timefrom=='00:00:00')
                                                                        {
                                                                          $previous_timefrom=$payroll_data[$i-2]['time_from'];
                                                                        }
                                                                        else
                                                                        {
                                                                          $previous_timefrom=$prev_timefrom;
                                                                        } 
                                                                        $new=getCheckoutDetails($previous_timefrom,$current_timefrom);  
                                                                        if($new<15)
                                                                        {
                                                                          $status_new="Already Checked in";
                                                                        }
                                                                        else
                                                                        {
                                                                          $status_new='';
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        $status_new='';
                                                                    } 
                                                                  }

                                                                  $new_status=$status_new;

                                                              }
                                                              else
                                                              { 
                                                                  $new_status='';
                                                              }



                                                            ?> <!-- checkin time -->


                                                            <td><span style="display: none;"><?php echo $payroll_data[$i]['time_log_date']; ?></span> 
                                                              
                                                            </td>

                                                            <?php } else {?>

                                                            <td>
                                                              <?php echo '';?> 
                                                            </td>
                                                            <?php }?>


                                                            <?php if($payroll_data[$i]['time_to']!='00:00:00' && $payroll_data[$i]['time_to']!='') 
                                                            {

                                                              //print_r("<pre>"); 
                                                             // print_r($i); print "<br>";
                                                              //print_r($payroll_data[$i]['time_log_date']); print "<br>";
                                                              //print_r($payroll_data[$i]['time_from']); print "<br>";
                                                             // print_r($payroll_data[$i]['time_to']); print "<br>";
                                                              if($payroll_data[$i]['time_from']=='00:00:00' || $payroll_data[$i]['time_from']=='')
                                                              { 
                                                                //print "Hiii";
                                                                  $checkout_date=$payroll_data[$i]['time_log_date']; //print_r($checkout_date);print "<br>";
                                                                  $pre_date=$payroll_data[$i-1]['time_log_date']; //print_r($pre_date);print "<br>";
                                                                  $current_timeto=$payroll_data[$i]['time_to'];  //print_r($current_timeto);print "<br>";
                                                                  $prev_timeto=$payroll_data[$i-1]['time_to']; //print_r($prev_timeto);print "<br>";
                                                                  if(($pre_date!='')&&($pre_date==$checkout_date))
                                                                  {
                                                                    //print "date is equal";
                                                                    $new=getCheckoutDetails($prev_timeto,$current_timeto); 

                                                                    if($new<15)
                                                                    { 
                                                                      $status_new="Already Checked Out";
                                                                    }
                                                                    else if($new<=15)
                                                                    { 
                                                                      $status_new='Already Checked Out/Missed Check In';
                                                                    }
                                                                    else
                                                                    { 
                                                                      $status_new='';
                                                                    }
                                                                  }
                                                                  else
                                                                  {
                                                                    $status_new='';
                                                                  }
                                                              }
                                                              else
                                                              {
                                                                $status_new='';
                                                              }

                                                              $new_status=$status_new;


                                                             ?> <!-- checkout time -->
                                                            <td><span style="display: none;"><?php echo $payroll_data[$i]['time_log_date']; ?></span>
                                                               
                                                            </td>
                                                            
                                                            <?php } else {?>

                                                            <td>
                                                              <?php echo '';?> 
                                                            </td>
                                                            <?php }?>


                                                            <?php 
                                                            $break_status=$payroll_data[$i]['date'].'_'.$payroll_data[$i]['user_id'].'_'.$payroll_data[$i]['fname']; 
                                                            $break_status_new=$payroll_data[$i-3]['date'].'_'.$payroll_data[$i-1]['user_id'].'_'.$payroll_data[$i-1]['fname']; 
                                                            ?>
                                                            <?php 
                                                            if($payroll_data[$i]['time_to']!='00:00:00' && $payroll_data[$i]['time_from']!='00:00:00' ){

                                                            $hour=getPayrollExtrahour($payroll_data[$i]['time_from'],$payroll_data[$i]['time_to'],$date,$payroll_data[$i]['time_log_date'],$userSchedule[0]['start_time'],$userSchedule[0]['end_time'],$payroll_data[$i]['break'],$break_status,$break_status_new); 
                                                              $time=date("H:i",strtotime($hour['time']));
                                                              $hour_new=$hour['hour'];
                                                            }
                                                            else
                                                            {
                                                              $time='00:00';
                                                              $hour_new='00:00';
                                                            }

                                                            ?>

                                                                    <?php if($payroll_data[$i]['time_to']!='00:00:00' && $payroll_data[$i]['time_from']!='00:00:00' )
                                                                    {?>
                                                                        <?php  $in_time=$hour['hour'];?>
                                                                    <?php } else{?>
                                                                        <?php  $in_time='00:00';?>
                                                                    <?php }?>  

                                                          <td> <?php  echo settimeformat(getPayrollformat1($time)); ?></td>  <!-- intime -->
                                                          <td> 
                                                                    <?php 
                                                                  $user_id=$payroll_data[$i]['user_id'];
                                                                  $shift_id=$payroll_data[$i]['shift_id'];
                                                                  $date=$payroll_data[$i]['date'];
                                                                  $unit_id=$payroll_data[$i]['unit_id'];
                                                                  $timelog_id=$payroll_data[$i]['timelogid'];
                                                                  ?>
                                                                   <?php  $user=getHour($user_id,$shift_id,$date,$unit_id,$timelog_id);

                                                                    $shift_cat=getshiftcategory($user_id);
                                                                    //print_r($shift_cat);

                                                                   // print_r($user);
                                                                   // print_r($payroll_data[$i]['shift_category']);

                                                                    if(empty($user))
                                                                   { //print 'empty';
                                                                      if($payroll_data[$i]['shift_category']==2)
                                                                      {
                                                                        $shift_category=2;
                                                                      }
                                                                      else if($payroll_data[$i]['shift_category']==0 || $payroll_data[$i]['shift_category']=='')
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
                                                                     if($payroll_data[$i]['shift_category']==2)
                                                                      {
                                                                        $shift_category=2;
                                                                      }
                                                                      else if($payroll_data[$i]['shift_category']==0 || $payroll_data[$i]['shift_category']=='')
                                                                      {
                                                                        $shift_category=$shift_cat;
                                                                      }
                                                                      else
                                                                      {
                                                                        $shift_category=1;
                                                                      }
                                                                   }


                                                                    ?> 
                                                                   <?php if(empty($user)){ ?>



                                                                     <p>  
                                                                      <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="">0</label>
                                                                      <label id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value="">0</label> </p>
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
                                                                  <a id="edit" class="edit" href="#" onclick="edit('<?php echo $payroll_data[$i]['user_id']; ?>','<?php echo $payroll_data[$i]['date']; ?>','<?php echo $payroll_data[$i]['shift_id']; ?>','<?php echo $payroll_data[$i]['unit_id']; ?>','hours_<?php echo $i;?>','edit_<?php echo $i;?>','comment_<?php echo $i;?>','<?php echo $shift_hours;?>','<?php echo $i; ?>','<?php  echo $payroll_data[$i]['timelogid']; ?>')" value="">   
                                                                        <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){?>
                                                                    <label id="edit_<?php echo $i; ?>" class="edit" style="width:100px;" value="" style="float: right;">Add</label>
                                                                        <?php }else{?>
                                                                    <label id="edit_<?php echo $i; ?>" class="edit" style="width:100px;" value="" style="float: right;">Edit</label>
                                                                         <?php }?>
                                                                   </a>

                                                          </td>  
                                                            <!-- <?php echo $in_time; ?> <?php echo $userSchedule[0]['shift_hours']; ?> -->
                                                      <?php if($userSchedule[0]['shift_hours']){ ?>

                                                        <?php if($userSchedule[0]['shift_hours']<$in_time) {?>
                                                            <?php if($user[0]['day_additional_hours']){ ?>
                                                              <?php $o = strtotime($userSchedule[0]['shift_hours'])+strtotime($user[0]['day_additional_hours'])-strtotime('00:00:00'); //print_r($o);?>
                                                            <?php } else { ?>
                                                              <?php $o = strtotime($userSchedule[0]['shift_hours'])+strtotime($user[0]['night_additional_hours'])-strtotime('00:00:00'); //print_r($o);?>
                                                            <?php }?>

                                                            <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){ ?>
                                                            <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1(date("H:i", strtotime($userSchedule[0]['shift_hours']))));?> </td>
                                                            <?php } else { ?>
                                                            <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1(date('H:i',$o)));?> </td>
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

                                                                        <?php if($payroll_data[$i]['time_to']=='00:00:00'){ ?>
                                                                        <!-- <td id="totalhour_<?php echo $i; ?>"><?php print_r($payroll_data[$i]['time_to']); echo date("H:i", strtotime($userSchedule[0]['shift_hours']));?> </td> -->

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

                                                        <?php if($payroll_data[$i]['time_from']!='' || $payroll_data[$i]['time_to']!=''){?>

                                                          <td><?php echo $new_status;?></td>
                                                        <?php } else {?>

                                                          <td><?php echo " ";?></td>
                                                        <?php }?> 

                                                                    
                                                                
                                                                   
                                                        </tr>
                                                      <?php } } }?>


                                                        <?php } else {?> 

                                                          <?php if(($payroll_data[$i]['date']!=$payroll_data[$i+1]['date']) || ($payroll_data[$i]['shift_name']!='Offday')){?>


                                                        <?php $userSchedule= getSchedule($payroll_data[$i]['date'],$payroll_data[$i]['user_id']); ?>
                                                         <?php 
                                                             if($userSchedule[0]['id']==16)
                                                            {
                                                                if($payroll_data[$i]['time_from']=='00:00:00')
                                                                {  
                                                                  $date1 = str_replace('-', '/', $payroll_data[$i]['time_log_date']);
                                                                  $tomorrow1 = date('Y-m-d',strtotime($date1 . "-1 days"));
                                                                  $datenew=$tomorrow1;
                                                                  $stat="from";
                                                                }
                                                                else if($payroll_data[$i]['time_to']=='00:00:00')
                                                                {  
                                                                  $date1 = str_replace('-', '/', $payroll_data[$i]['time_log_date']);
                                                                  $tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
                                                                  $datenew=$tomorrow;
                                                                  $stat="to";
                                                                } 
                                                                else
                                                                {
                                                                  $datenew=$payroll_data[$i]['time_log_date'];
                                                                  $stat="not";

                                                                }
                                                                $date_time=getDateAndTime($datenew,$payroll_data[$i]['user_id']);
                                                                if(!empty($date_time))
                                                                {
                                                                  if($stat=='from')
                                                                  { //print "hii";
                                                                    $payroll_data[$i]['time_from']=$date_time[0]['time_from'];
                                                                    $payroll_data[$i]['time_log_date']=$datenew;
                                                                    $payroll_data[$i]['date']=$datenew;
                                                                  }
                                                                  else if($stat=='to')
                                                                  { //print "hello";
                                                                    $payroll_data[$i]['time_to']=$date_time[0]['time_to'];
                                                                    $date=$datenew;
                                                                  }
                                                                  else
                                                                  {

                                                                  }

                                                                }
                                                                else
                                                                {
                                                                   
                                                                }
                                                            }
                                                            ?>

                                                        <?php if($userSchedule[0]['id']!=16 || $payroll_data[$i]['time_log_date']!=$tomorrow1 ) {?>

                                                        <tr>
                                                          <td><?php echo $payroll_data[$i]['user_id'];?> </td> <!-- user_id -->
                                                          <td><?php echo $payroll_data[$i]['fname'].' '.$payroll_data[$i]['lname'];?> </td> <!-- name -->

                                                            <?php if($userSchedule[0]['shift_name']==''){ $shift_name='No Shift';}else{$shift_name=$userSchedule[0]['shift_name']; }

                                                            if($shift_name=='No Shift') 
                                                              {$new_shift=$payroll_data[$i]['shift_name']; }else
                                                              {$new_shift=$shift_name; }
                                                              ?>
                                                          <td><?php echo $new_shift;?> </td> <!-- shift name --> 

                                                           
                                                          <td id="shifthour_<?php echo $i; ?>" style="display: none;"><?php echo $userSchedule[0]['shift_hours'];?></td> 
                                                        <!--   <td><?php echo $userSchedule[0]['start_time'];?> </td>
                                                          <td><?php echo $userSchedule[0]['end_time'];?> </td> -->
                                                                   <?php $check_holiday=checkHoliday($payroll_data[$i]['date']);?>
                                                                   <?php if ($check_holiday == "true") { ?>
                                                                        <td><span style="display: none;"><?php echo $payroll_data[$i]['date']; ?></span><?php echo corectDateFormat($payroll_data[$i]['date']);?>&nbsp&nbsp(<span style="color:red">Bank Holiday</span>)</td>
                                                                   <?php } else { ?>
                                                                        <td><span style="display: none;"><?php echo $payroll_data[$i]['date']; ?></span><?php echo corectDateFormat($payroll_data[$i]['date']);?></td> <!-- date -->
                                                                   <?php } ?>
                                                            <td> 
                                                              <?php $Note=GetCensus($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
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

                                                            <?php if($payroll_data[$i]['time_from']!='00:00:00' && $payroll_data[$i]['time_from']!='') 
                                                            {  

                                                              if($payroll_data[$i]['time_to']=='00:00:00' || $payroll_data[$i]['time_to']=='')
                                                              { 
                                                                  $checkin_date=$payroll_data[$i]['time_log_date'];
                                                                  $pre_date=$payroll_data[$i-1]['time_log_date'];
                                                                  $next_date=$payroll_data[$i+1]['time_log_date'];

                                                                  $current_timefrom=$payroll_data[$i]['time_from'];
                                                                  $next_timefrom=$payroll_data[$i+1]['time_from'];
                                                                  $prev_timefrom=$payroll_data[$i-1]['time_from']; 
                                                                  


                                                                  if($prev_timefrom=='')
                                                                  {  
                                                                    if(($next_date!='')&&($next_date==$checkin_date))
                                                                    { 
                                                                       $current=date("H:i:s"); 
                                                                       $checkin_time=$current_timefrom;
                                                                       $new=getCheckoutDetails($current,$checkin_time); 
                                                                       if($new>13)
                                                                       {
                                                                        $status_new="Missed Check Out";
                                                                       }
                                                                       else
                                                                       {
                                                                        $status_new='';
                                                                       }
                                                                    }
                                                                    else
                                                                    { 
                                                                      $status_new='';
                                                                    }
                                                                  }
                                                                  else
                                                                  {  
                                                                    if(($pre_date!='')&&($pre_date==$checkin_date))
                                                                    {    
                                                                        if($prev_timefrom=='00:00:00')
                                                                        {
                                                                          $previous_timefrom=$payroll_data[$i-2]['time_from'];
                                                                        }
                                                                        else
                                                                        {
                                                                          $previous_timefrom=$prev_timefrom;
                                                                        } 
                                                                        $new=getCheckoutDetails($previous_timefrom,$current_timefrom);  
                                                                        if($new<15)
                                                                        {
                                                                          $status_new="Already Checked in";
                                                                        }
                                                                        else
                                                                        {
                                                                          $status_new='';
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        $status_new='';
                                                                    } 
                                                                  }

                                                                  $new_status=$status_new;

                                                              }
                                                              else
                                                              { 
                                                                  $new_status='';
                                                              }



                                                            ?> <!-- checkin time -->


                                                            <td><span style="display: none;"><?php echo $payroll_data[$i]['time_log_date']; ?></span> 
                                                              <?php echo corectDateFormat($payroll_data[$i]['time_log_date']).' '.$payroll_data[$i]['time_from'];?> 
                                                            </td>

                                                            <?php } else {?>

                                                            <td>
                                                              <?php echo '';?> 
                                                            </td>
                                                            <?php }?>


                                                            <?php if($payroll_data[$i]['time_to']!='00:00:00' && $payroll_data[$i]['time_to']!='') 
                                                            {

                                                              //print_r("<pre>"); 
                                                             // print_r($i); print "<br>";
                                                              //print_r($payroll_data[$i]['time_log_date']); print "<br>";
                                                              //print_r($payroll_data[$i]['time_from']); print "<br>";
                                                             // print_r($payroll_data[$i]['time_to']); print "<br>";
                                                              if($payroll_data[$i]['time_from']=='00:00:00' || $payroll_data[$i]['time_from']=='')
                                                              { 
                                                                //print "Hiii";
                                                                  $checkout_date=$payroll_data[$i]['time_log_date']; //print_r($checkout_date);print "<br>";
                                                                  $pre_date=$payroll_data[$i-1]['time_log_date']; //print_r($pre_date);print "<br>";
                                                                  $current_timeto=$payroll_data[$i]['time_to'];  //print_r($current_timeto);print "<br>";
                                                                  $prev_timeto=$payroll_data[$i-1]['time_to']; //print_r($prev_timeto);print "<br>";
                                                                  if(($pre_date!='')&&($pre_date==$checkout_date))
                                                                  {
                                                                    //print "date is equal";
                                                                    $new=getCheckoutDetails($prev_timeto,$current_timeto); 

                                                                    if($new<15)
                                                                    { 
                                                                      $status_new="Already Checked Out";
                                                                    }
                                                                    else if($new<=15)
                                                                    { 
                                                                      $status_new='Already Checked Out/Missed Check In';
                                                                    }
                                                                    else
                                                                    { 
                                                                      $status_new='';
                                                                    }
                                                                  }
                                                                  else
                                                                  {
                                                                    $status_new='';
                                                                  }
                                                              }
                                                              else
                                                              {
                                                                $status_new='';
                                                              }

                                                              $new_status=$status_new;


                                                             ?> <!-- checkout time -->
                                                            <td><span style="display: none;"><?php echo $payroll_data[$i]['time_log_date']; ?></span>
                                                              <?php echo corectDateFormat($date).' '.$payroll_data[$i]['time_to'];?> 
                                                            </td>
                                                            
                                                            <?php } else {?>

                                                            <td>
                                                              <?php echo '';?> 
                                                            </td>
                                                            <?php }?>


                                                            <?php 
                                                            $break_status=$payroll_data[$i]['date'].'_'.$payroll_data[$i]['user_id'].'_'.$payroll_data[$i]['fname']; 
                                                            $break_status_new=$payroll_data[$i-3]['date'].'_'.$payroll_data[$i-1]['user_id'].'_'.$payroll_data[$i-1]['fname']; 
                                                            ?>
                                                            <?php 
                                                            if($payroll_data[$i]['time_to']!='00:00:00' && $payroll_data[$i]['time_from']!='00:00:00' ){

                                                            $hour=getPayrollExtrahour($payroll_data[$i]['time_from'],$payroll_data[$i]['time_to'],$date,$payroll_data[$i]['time_log_date'],$userSchedule[0]['start_time'],$userSchedule[0]['end_time'],$payroll_data[$i]['break'],$break_status,$break_status_new); 
                                                              $time=date("H:i",strtotime($hour['time']));
                                                              $hour_new=$hour['hour'];
                                                            }
                                                            else
                                                            {
                                                              $time='00:00';
                                                              $hour_new='00:00';
                                                            }

                                                            ?>

                                                                    <?php if($payroll_data[$i]['time_to']!='00:00:00' && $payroll_data[$i]['time_from']!='00:00:00' )
                                                                    {?>
                                                                        <?php  $in_time=$hour['hour'];?>
                                                                    <?php } else{?>
                                                                        <?php  $in_time='00:00';?>
                                                                    <?php }?>  

                                                          <td> <?php  echo settimeformat(getPayrollformat1($time)); ?></td>  <!-- intime -->
                                                          <td> 
                                                                    <?php 
                                                                  $user_id=$payroll_data[$i]['user_id'];
                                                                  $shift_id=$payroll_data[$i]['shift_id'];
                                                                  $date=$payroll_data[$i]['date'];
                                                                  $unit_id=$payroll_data[$i]['unit_id'];
                                                                  $timelog_id=$payroll_data[$i]['timelogid']; 
                                                                  ?>
                                                                   <?php  $user=getHour($user_id,$shift_id,$date,$unit_id,$timelog_id);  
                                                                    $shift_cat=getshiftcategory($user_id); 

                                                                   if(empty($user))
                                                                   {  //no timelog entry shows empty
                                                                      if($payroll_data[$i]['shift_category']==2)
                                                                      { //if shiftcategory ==2 then shows night
                                                                        $shift_category=2;
                                                                      }
                                                                      else if($payroll_data[$i]['shift_category']==0 || $payroll_data[$i]['shift_category']=='')
                                                                      { //if shiftcategory ==0/'' then shows category of the default shift
                                                                        $shift_category=$shift_cat;
                                                                      }
                                                                      else
                                                                      { //shows day shift
                                                                        $shift_category=1;
                                                                      }
                                                                   } //if a timelog and rotaschedule have day additional hours set shift cat=`1
                                                                   else if($user[0]['day_additional_hours'])
                                                                   {  
                                                                     $shift_category=1;
                                                                   }//if a timelog and rotaschedule have night additional hours set shift cat=`2
                                                                   else if($user[0]['night_additional_hours'])
                                                                   {  
                                                                     $shift_category=2;
                                                                   }
                                                                   else
                                                                   {  //if a timelog and rotaschedule have no additional hours 
                                                                     if($payroll_data[$i]['shift_category']==2)
                                                                      {//if shiftcategory ==2 then shows night
                                                                        $shift_category=2;
                                                                      }
                                                                      else if($payroll_data[$i]['shift_category']==0 || $payroll_data[$i]['shift_category']=='')
                                                                      {//if shiftcategory ==0/'' then shows category of the default shift
                                                                        $shift_category=$shift_cat;
                                                                      }
                                                                      else
                                                                      {//shows day shift
                                                                        $shift_category=1;
                                                                      }
                                                                   }




                                                                    ?> 
                                                                   <?php if(empty($user)){
                                                                    ?>

                                                                     <p>  
                                                                      <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="">0</label>
                                                                      <label style="display: none;" id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value="">0</label> 

                                                                     </p>
                                                                     <label id="comment_<?php echo $i;?>" class="comment" style="width:100px;" value=""></label> 
                                                                     

                                                                  <?php }else {?>

                                                                     <p>
                                                                      <?php if($user[0]['day_additional_hours']){?> 
                                                                        <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="<?php echo $user[0]['day_additional_hours'];?>"><?php echo settimeformat(getPayrollformat1($user[0]['day_additional_hours']));?></label>
                                                                        <label style="display: none;" id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value=""><?php echo $user[0]['day_additional_hours'];?></label>
                                                                      <?php }else {?>
                                                                        <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="<?php echo $user[0]['night_additional_hours'];?>"><?php echo settimeformat(getPayrollformat1($user[0]['night_additional_hours']));?></label>
                                                                        <label style="display: none;" id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value=""><?php echo $user[0]['night_additional_hours'];?></label>
                                                                      <?php }?>
                                                                     
                                                                    </p>
                                                                     <label id="comment_<?php echo $i;?>" class="comment" style="width:100px;" value=""><?php echo $user[0]['comment'];?></label> 

                                                                 <?php }?>

                                                                  <label style="display: none;" id="shiftcategory_<?php echo $i;?>" class="shiftcategory" style="width:100px;" value="<?php echo $shift_category; ?>"><?php echo $shift_category; ?></label>
                                                                  <?php if($userSchedule[0]['shift_hours']<$in_time) { $shift_hours=$userSchedule[0]['shift_hours']; } else {$shift_hours=$in_time;}?>
                                                                  <a id="edit" class="edit" href="#" onclick="edit('<?php echo $payroll_data[$i]['user_id']; ?>','<?php echo $payroll_data[$i]['date']; ?>','<?php echo $payroll_data[$i]['shift_id']; ?>','<?php echo $payroll_data[$i]['unit_id']; ?>','hours_<?php echo $i;?>','edit_<?php echo $i;?>','comment_<?php echo $i;?>','<?php echo $shift_hours;?>','<?php echo $i; ?>','<?php  echo $payroll_data[$i]['timelogid']; ?>')" value="">   
                                                                        <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){?>
                                                                    <label id="edit_<?php echo $i; ?>" class="edit" style="width:100px;" value="" style="float: right;">Add</label>
                                                                        <?php }else{?>
                                                                    <label id="edit_<?php echo $i; ?>" class="edit" style="width:100px;" value="" style="float: right;">Edit</label>
                                                                         <?php }?>
                                                                   </a>

                                                          </td>  
                                                            <!-- <?php echo $in_time; ?> <?php echo $userSchedule[0]['shift_hours']; ?> -->
                                                      <?php if($userSchedule[0]['shift_hours']){ ?>

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

                                                                        <?php if($payroll_data[$i]['time_to']=='00:00:00'){ ?>
                                                                        <!-- <td id="totalhour_<?php echo $i; ?>"><?php print_r($payroll_data[$i]['time_to']); echo date("H:i", strtotime($userSchedule[0]['shift_hours']));?> </td> -->

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

                                                        <?php if($payroll_data[$i]['time_from']!='' || $payroll_data[$i]['time_to']!=''){?>

                                                          <td><?php echo $new_status;?></td>
                                                        <?php } else {?>

                                                          <td><?php echo " ";?></td>
                                                        <?php }?> 

                                                                    
                                                                
                                                                   
                                                        </tr>
                                                      <?php } } }?>
                                                    <?php }?>
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