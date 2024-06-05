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
               <div class="col-md-9">
                 
               </div>
               <div class="col-md-3" style="float: right;text-align: right;">
                    <!-- <div class="form-group" style="width: 200px;"> -->
                     <button type="submit" class="search btn hidden-sm-down btn-success" id="search" style="width: 100px;margin-right: 13px;">Search</button> 
                                    
                    <!-- </div> -->
                    <?php if($payroll_user):?>
                      <?php if($timesheet_lock_status):?>
                        <button  type="button" data-status="1" class="btn btn-primary lock-rota-status" id="lock_rota">Lock Timesheet</button>
                      <?php else: ?>
                        <button  type="button" data-status="0" class="btn btn-primary lock-rota-status" id="unlock_rota">Unlock Timesheet</button>
                      <?php endif; ?>
                    <?php endif; ?>
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
                                                                <!-- <th>Shift Start Time</th> 
                                                                <th>Shift End Time</th>  -->
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
                                                    <?php  for($i=0;$i<count($payroll_data);$i++){?>

                                                        <tr class="printabled">
                                                          <td><?php echo $payroll_data[$i]['user_id'];?> </td> <!-- user_id -->
                                                          <td><?php echo $payroll_data[$i]['fname'].' '.$payroll_data[$i]['lname'];?> </td> <!-- name -->

                                                            <?php if($payroll_data[$i]['shift_name']==''){ $shift_name='No Shift';}else{$shift_name=$payroll_data[$i]['shift_name']; }

                                                            if($shift_name=='No Shift') 
                                                              {$new_shift=$payroll_data[$i]['shift_name']; }else
                                                              {$new_shift=$shift_name; }
                                                              ?>
                                                          <td><?php echo $new_shift ?> </td> <!-- shift name -->

                                                           
                                                           
                                                        <!--   <td><?php echo $userSchedule[0]['start_time'];?> </td>
                                                          <td><?php echo $userSchedule[0]['end_time'];?> </td> -->
                                                                   <?php $check_holiday=checkHoliday($payroll_data[$i]['date']);?>
                                                                   <?php if ($check_holiday == "true") { ?>
                                                                        <td data-sort="<?php echo $payroll_data[$i]['date']; ?>"><?php echo corectDateFormat($payroll_data[$i]['date']);?>&nbsp&nbsp(<span style="color:red">Bank Holiday</span>)</td>
                                                                   <?php } else { ?>
                                                                        <td data-sort="<?php echo $payroll_data[$i]['date']; ?>"><?php echo corectDateFormat($payroll_data[$i]['date']);?></td> <!-- date -->
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

                                                            <?php

                                                           
                                                           
                                                        

                                                            //if($payroll_data[$i]['shift_name']=='Night')
                                                            if($payroll_data[$i]['shift_name']=='Night' || $payroll_data[$i]['shift_name']=='Training + Night'|| $payroll_data[$i]['shift_name']=='Student Nurse Night')
                                                            {
                                                              $checkin=getNightshiftcheckinDetails($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                              $checkout=getNightshiftChekoutDetails($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                            }
                                                            else if($payroll_data[$i]['shift_category']==1 || $payroll_data[$i]['shift_category']==3 || $payroll_data[$i]['shift_category']==4)
                                                            { 
                                                              $checkin=getshiftcheckinDetails($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                              $checkout=getDayshiftChekoutDetails($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                            }
                                                            else if($payroll_data[$i]['shift_name']=='Sick')
                                                            {  
                                                               $checkin=getshiftcheckinDetails($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                               $checkout=getDayshiftChekoutDetails($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                            }
                                                            else if($payroll_data[$i]['shift_name']=='Offday' || $payroll_data[$i]['shift_name']=='Training')
                                                            {
                                                                $checkin=getshiftcheckinDetails($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                                $checkout=getDayshiftChekoutDetails($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                            }
                                                            else
                                                            {
                                                                $checkin= "00:00:00";
                                                                $checkout= "00:00:00";
                                                            }

                                                            if($checkin==""){
                                                                $checkin="00:00:00";
                                                            }
                                                            if($checkout==""){
                                                                $checkout="00:00:00";
                                                            }
                                                            ?> 

                                                            <?php 
                                                                 $day_date=$payroll_data[$i]['date'];
                                                                 $shift_start=settimeformat($payroll_data[$i]['start_time']); //print_r($shift_start);
                                                                 $shift_end=settimeformat($payroll_data[$i]['end_time']);

                                                            ?>
                                                            
                                                            <td style="font-size: small;">  <!-- checkin  start-->
                                                            <?php
                                                            $cins = array();
                                                            $couts = array(); 
                                                            if(!empty($checkin)){?>

                                                              <?php $j=1; foreach ($checkin as $check) {?>
                                                                <?php  
                                                                    if($payroll_data[$i]['shift_category']==2)
                                                                    {
                                                                      if($day_date==$check['date'])
                                                                      {
                                                                        echo corectDateFormat($check['date']).' '.$check['time_from']; echo "<br>";
                                                                        $cins[] =$check['date'].' '.$check['time_from'];
                                                                        $cins_time[] =$check['time_from'];
                                                                      }
                                                                      else
                                                                      {

                                                                        if($shift_end<settimeformat($check['time_from']))
                                                                        {
                                                                          echo " ";
                                                                          $cins = array(" ");
                                                                          $cins_time[]= array(" ");
                                                                        }
                                                                        else
                                                                        {
                                                                          echo corectDateFormat($check['date']).' '.$check['time_from']; echo "<br>";
                                                                          $cins[] =$check['date'].' '.$check['time_from'];
                                                                          $cins_time[] =$check['time_from'];

                                                                        }


                                                                      }

                                                                    }
                                                                    else
                                                                    {
                                                                      echo corectDateFormat($check['date']).' '.$check['time_from']; echo "<br>";
                                                                      $cins[] =$check['date'].' '.$check['time_from'];
                                                                      $cins_time[] =$check['time_from'];

                                                                    }

                                                                ?> 
                                                              <?php $j++; }?>
                                                          <?php $j=1;} else {?>
                                                             <?php echo " ";
                                                                  $cins = array(" ");
                                                                  $couts = array(" ");

                                                             ?>

                                                          <?php }
                                                       
                                                          ?>
                                                          </td>  <!--checkin end-->

                                                          <td style="font-size: small;">  <!--checkout start-->
                                                              <?php if(!empty($checkout)){?>

                                                                <?php $j=1; foreach ($checkout as $checks) {?>
                                                                  <?php echo corectDateFormat($checks['date']).' '.$checks['time_to']; echo "<br>";
                                                                  $couts[] =$checks['date'].' '.$checks['time_to'];
                                                                  $couts_time[] =$checks['time_to'];
                                                                  ?> 
                                                                <?php $j++; }?>
                                                            <?php $j=1;} else {?>
                                                                  <?php echo " ";
                                                                   $cins = array(" ");
                                                                       $couts = array(" ");

                                                                  ?>
                                                            <?php }
                                                            //print_r($checkout);
                                                            ?>

                                                            </td>  <!--checkout end-->
                                                            <td>  <!--intime start-->
                                                            
                                                            <?php 
                                                         //$hour_n==array();
                                                         $time_n=array();
                                                            for($c=0;$c<count($couts);$c++){
                                                                $in_time="";
                                                              
                                                                if($couts[$c]!='' && $cins[$c]!=''){
                                                                    
                                                                 
                                                                    if($payroll_data[$i]['shift_name']=='Offday')
                                                                    {
                                                                        $_cin = explode(" ",$cins[$c]);
                                                                        $_cout = explode(" ",$couts[$c]);
                                                                        $payroll_data[$i]['start_time'] = $_cin[1];
                                                                        $payroll_data[$i]['end_time']=$_cout[1];
                                                                    }
                                                                    if($payroll_data[$i]['shift_name']=='Training')
                                                                    { //if shift is training,then get the start and end time of training
                                                                       $shift=getTrainingTimes($payroll_data[$i]['date'],$payroll_data[$i]['user_id']);
                                                                       if($shift!='')
                                                                       {
                                                                          $payroll_data[$i]['start_time'] = $shift['time_from'].':'.'00';
                                                                          $payroll_data[$i]['end_time']=$shift['time_to'].':'.'00';
                                                                       }
                                                                       else
                                                                       {
                                                                            $_cin = explode(" ",$cins[$c]);
                                                                            $_cout = explode(" ",$couts[$c]);
                                                                            $payroll_data[$i]['start_time'] = $_cin[1];
                                                                            $payroll_data[$i]['end_time']=$_cout[1];

                                                                       }

                                                                    }
                                                           /*          print "<br>----<br>".$payroll_data[$i]['start_time']."<br>";
                                                                    print $payroll_data[$i]['end_time']."<br>";
                                                                    print $payroll_data[$i]['break']."<br>";
                                                                    print $cins[$c]."<br>";
                                                                    print $couts[$c]."<br>---<br>"; */
                                                                    
                                                                $hour=getPayrollExtrahournew($cins[$c],$couts[$c],$payroll_data[$i]['start_time'],$payroll_data[$i]['end_time'],$payroll_data[$i]['break'],$payroll_data[$i]['shift_category'],$day_date);
                                                             //   print_r($hour);

                                                                $date1 = strtotime($couts[$c]);  
                                                                $date2 = strtotime($cins[$c]);  
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
                                                                 
                                                                /*  printf(" %d:"
                                                                     . "%d:%d", $hours, $minutes, $seconds);  echo '<br>';  */ 
                                                                $in_time1 = $hours.":".$minutes; 
                                                                $in_time=date("H:i",strtotime($hour['time'])); //print "hii-";print_r($in_time);
                                                                $new=$in_time; //print_r($new);
                                                                //$hour_n[]=$hour['hour'];
                                                                $time_n[]=$new;

                                                                echo settimeformat(getPayrollformat1($in_time)); echo '<br>'; 
                                                            }
                                                            
                                                            }
                                                            // $sum=0;

                                                            // foreach ($hour_n as $value) {
                                                             
                                                            //  if($value!='' || $value!='00:00')
                                                            //  {
                                                            //   $sum=$sum+settimeformat(getPayrollformat1($value));

                                                            //  }

                                                            // }
                                                            $in_time_sum=0;
                                                            foreach ($time_n as $value1) {
                                                             
                                                             if($value1!='' || $value1!='00:00')
                                                             {
                                                              $in_time_sum=$in_time_sum+settimeformat(getPayrollformat1($value1));

                                                             }

                                                            }
                                                            $break=getPayrollformat1($payroll_data[$i]['break']);
                                                           //print_r($break);
                                                            if($in_time_sum!='' ||$in_time_sum!=0)
                                                            {  
                                                              $sum_total=settimeformat($in_time_sum)-settimeformat($break);
                                                               //print_r($sum_total);
                                                            }
                                                            else
                                                            {
                                                              $sum_total='00:00';
                                                            }

                                                           
                                                            
                                                             $hour_new=$sum_total;

                                                            // print_r($hour_new); print '<br>';

                                                            //$hour_n = array(" ");
                                                            $time_n = array(" ");
                                                            
                                                            ?>
                                                            
                                                            </td><!--intime end-->
                                                            
                                                            
                                                            
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
                                                                     //print_r($user); print '<br>';

                                                                   // print_r($user);
                                                                    //print_r($timesheet['shift_category']);

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

                                                                <?php   if(empty($user)){ $decimal_hour='0.00'; ?>



                                                                     <p>  
                                                                      <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="">&nbsp;</label>
                                                                      <label id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value="">&nbsp;</label> </p>
                                                                     <label id="comment_<?php echo $i;?>" class="comment" style="width:100px;" value=""></label> 
                                                                     

                                                                  <?php }else {?>

                                                                    <p>
                                                                      <?php if($user[0]['day_additional_hours']){ $decimal_hour=settimeformat(getPayrollformat1($user[0]['day_additional_hours']));;
                                                                        ?> 
                                                                        <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="<?php echo $user[0]['day_additional_hours'];?>"><?php echo settimeformat(getPayrollformat1($user[0]['day_additional_hours']));?></label>
                                                                        <label style="display: none" id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value=""><?php echo $user[0]['day_additional_hours'];?></label>
                                                                      <?php }else { $decimal_hour=settimeformat(getPayrollformat1($user[0]['night_additional_hours'])); ?>
                                                                        <label id="hours1_<?php echo $i;?>" class="hours" style="width:100px;" value="<?php echo $user[0]['night_additional_hours'];?>"><?php echo settimeformat(getPayrollformat1($user[0]['night_additional_hours']));?></label>
                                                                        <label style="display: none" id="hours_<?php echo $i;?>" class="hours" style="width:100px;" value=""><?php echo $user[0]['night_additional_hours'];?></label>
                                                                      <?php }?>
                                                                     
                                                                    </p>
                                                                    <label id="comment_<?php echo $i;?>" class="comment" style="width:100px;" value=""><?php echo $user[0]['comment'];?></label> 

                                                                 <?php }?>
                                                                 <label style="display: none;" id="shiftcategory_<?php echo $i;?>" class="shiftcategory" style="width:100px;" value="<?php echo $shift_category; ?>"><?php echo $shift_category; ?></label> 
                                                                  <?php if(settimeformat(getPayrollformat1($payroll_data[$i]['shift_hours']))<$in_time_sum) 
                                                                  {  // if shifttime is less than intime,then take shift hour(targeted hour) as intime,then substract breakhour and pass it to insert additional hours
                                                                      if($payroll_data[$i]['break'])
                                                                      {
                                                                        $result=getSHifthourstotal($payroll_data[$i]['shift_hours'],$payroll_data[$i]['break']); 
                                                                      }
                                                                      else
                                                                      {
                                                                        $result=$payroll_data[$i]['shift_hours'];
                                                                      }
                                                                      
                                                                      $shift_hours=getPayrollformat1(settimeformat_new($result)); 
                                                                  } 
                                                                  else 
                                                                  {
                                                                    $shift_hours=$hour_new;
                                                                  }?>
                                                                  <?php if($this->session->userdata('user_type') !=18){?>
                                                                    <?php if($timesheet_lock_status):?>
                                                                    <a id="edit" class="edit" href="#" onclick="edit('<?php echo $payroll_data[$i]['user_id']; ?>','<?php echo $payroll_data[$i]['date']; ?>','<?php echo $payroll_data[$i]['shift_id']; ?>','<?php echo $payroll_data[$i]['unit_id']; ?>','hours_<?php echo $i;?>','edit_<?php echo $i;?>','comment_<?php echo $i;?>','<?php echo $shift_hours;?>','<?php echo $i; ?>','<?php  echo $payroll_data[$i]['timelogid']; ?>','<?php echo $decimal_hour;?>')" value="">   
                                                                          <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){?>
                                                                      <label id="edit_<?php echo $i; ?>" class="edit" style="width:100px;" value="" style="float: right;">Add</label>
                                                                          <?php }else{?>
                                                                      <label id="edit_<?php echo $i; ?>" class="edit" style="width:100px;" value="" style="float: right;">Edit</label>
                                                                           <?php }?>
                                                                     </a>
                                                                     <?php endif; ?>
                                                                 <?php }?>



                                                            </td> <!--additional hours--> 
 
                                                        <?php  if($payroll_data[$i]['shift_hours']){?>

                                                        <?php if(settimeformat(getPayrollformat1($payroll_data[$i]['shift_hours']))<$in_time_sum) 
                                                        { // changed on oct 28.. shift hours changed to decimal value
                                                              $result=getSHifthourstotal($payroll_data[$i]['shift_hours'],$payroll_data[$i]['break']);
                                                        ?>
                                                            <?php if($user[0]['day_additional_hours']){ ?>
                                                                <?php $o = AddTimesnew($result,$user[0]['day_additional_hours']); //print_r($o);?>
                                                            <?php } else { ?>
                                                                <?php $o = AddTimesnew($result,$user[0]['night_additional_hours']); //print_r($o);?>
                                                            <?php }?>

                                                            <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){ ?>
                                                                <td id="totalhour_<?php echo $i; ?>"><?php  echo settimeformat(getPayrollformat1(date("H:i", strtotime($result))));?> </td>
                                                            <?php } else { ?>
                                                                <td id="totalhour_<?php echo $i; ?>"><?php  echo (getPayrollformat1($o));?> </td>
                                                            <?php }?>

                                                        <?php } else {?>


                                                                          <?php if($in_time_sum=='00:00'){?> 

                                                                                <?php if($user[0]['day_additional_hours'] || $user[0]['night_additional_hours']){?>
                                                                                    <?php if($user[0]['day_additional_hours']){?>
                                                                                        <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1($user[0]['day_additional_hours']));?> </td>
                                                                                    <?php } else {?>
                                                                                        <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1($user[0]['night_additional_hours']));?> </td>
                                                                                    <?php }?>
                                                                                <?php } else {?>
                                                                                    <td id="totalhour_<?php echo $i; ?>"><?php echo "00.00";?> </td> 
                                                                                <?php }?>

                                                                          <?php }else{?>

                                                                                <?php 
                                                                                    if($user[0]['day_additional_hours']!='00:00' && $user[0]['night_additional_hours']!='00:00')
                                                                                    {
                                                                                          if($user[0]['day_additional_hours'])
                                                                                          { 
                                                                                            $o=AddTimesnew1($hour_new,getPayrollformat1($user[0]['day_additional_hours']));
                                                                                          }
                                                                                          else
                                                                                          {
                                                                                            $o=AddTimesnew1($hour_new,getPayrollformat1($user[0]['night_additional_hours']));
                                                                                          }                  
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                          $o=$hour_new;
                                                                                    }

                                                                                        $o=settimeformat_new($o);
                                                                                ?>

                                                                                <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){?> 
                                                                                       
                                                                                       <td id="totalhour_<?php echo $i; ?>"><?php print_r(settimeformat(getPayrollformat1($hour_new)));?> </td>

                                                                                <?php } else {?>

                                                                                    <td id="totalhour_<?php echo $i; ?>"><?php  echo number_format(settimeformat($o),2); ?> </td>

                                                                                <?php }?>

                                                                          <?php } ?>

                                                          <?php }?>

                                                        <?php }else{?>

                                                            <?php if($user[0]['day_additional_hours'] || $user[0]['night_additional_hours']){?>
                                                              <?php if($user[0]['day_additional_hours']){?>
                                                                <td id="totalhour_<?php echo $i; ?>"><?php  echo settimeformat(getPayrollformat1($user[0]['day_additional_hours']));?> </td>
                                                              <?php } else {?>
                                                                <td id="totalhour_<?php echo $i; ?>"><?php  echo settimeformat(getPayrollformat1($user[0]['night_additional_hours']));?> </td>
                                                              <?php }?>
                                                            <?php } else {?>
                                                              <td id="totalhour_<?php echo $i; ?>"><?php  echo "0.00"; ?> </td> 
                                                            <?php }?>

                                                        <?php }?>
                                                                
                                                                
                                                                </td> 
                                                       
                                                                   
                                                        </tr>
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