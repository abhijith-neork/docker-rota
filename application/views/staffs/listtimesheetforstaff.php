<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css">
<link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet"> 

<style type="text/css"> 
.page-wrapper {
        margin-left: 0px !important;
}
</style>

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
              <div class="col-md-3" style="padding-right: 36px;padding-left: 40px;">
                    <div class="form-group"> 
                                <label for="from_date">Start date:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                <input type="text"  class="form-control required" id="from-datepicker" name="from_date" value="<?php echo $from_date; ?>"> 
                    </div>
              </div>
              <div class="col-md-3" style="padding-right: 36px;">
                    <div class="form-group"> 
                                <label for="to_date">End date:<span style="color: red;">*</span></b> <span class="danger"></span></label> 
                                <input type="text" class="form-control required" id="to-datepicker" name="to_date" value="<?php echo $to_date; ?>"> 
                    </div>
              </div>
              <div class="col-md-3" style="padding-right: 36px;padding-top: 30px;">
                    <div class="form-group" style="width: 200px;">
                     <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                                    
                    </div>
                      
                </div>

                <div class="col-md-3 col-4 align-self-center" style="padding-left: 31px;">
                    <button type="button" onclick="cancelTraining(<?php echo $this->session->userdata('user_type');?>)"  class="btn float-right hidden-sm-down btn-secondary" id="cancel" style="height: 40px; width:120px;margin-left:10px;">Cancel</button>
                    <!-- <button class=""
                                                                    onclick="location.href='<?php echo base_url();?>staffs/profile'" >
                                                                     Back
                                                                </button> -->
                    </div>
                </div> 
            </div>
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
                                                                <!-- <th>In Time</th> -->
                                                                <!-- <th>Additional Hours</th>  -->
                                                                <!-- <th>Total Hours</th> -->
                                                            

                                                                                    
                                                    </tr>
                                                </thead>
                                                    <tbody>   
                                                    <?php  for($i=0;$i<count($payroll_data);$i++){?>

                                                        <tr>
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
                                                            else if($payroll_data[$i]['shift_name']=='Offday')
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
                                                            
                                                           <!--  <td> -->  <!--intime start-->
                                                            
                                                           <?php 
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
                                                                    
                                                                $hour=getPayrollExtrahournew($cins[$c],$couts[$c],$payroll_data[$i]['start_time'],$payroll_data[$i]['end_time'],$payroll_data[$i]['break'],$payroll_data[$i]['shift_category'],$day_date);

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
                                                                $in_time1 = $hours.":".$minutes; 
                                                                $in_time=date("H:i",strtotime($hour['time'])); 
                                                                //echo settimeformat(getPayrollformat1($in_time)); echo '<br>'; 
                                                                $new=$in_time; //print_r($new);
                                                                //$hour_n[]=$hour['hour'];
                                                                $time_n[]=$new;
                                                            }
                                                            
                                                            }

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

                                                            //$hour_n = array(" ");
                                                            $time_n = array(" ");
                                                            
                                                            ?>
                                                            
                                                                                                                      <?php 
                                                                  $user_id=$payroll_data[$i]['user_id'];
                                                                  $shift_id=$payroll_data[$i]['shift_id'];
                                                                  $date=$payroll_data[$i]['date'];
                                                                  $unit_id=$payroll_data[$i]['unit_id'];
                                                                  $timelog_id=$payroll_data[$i]['timelogid'];
                                                                  $user=getHour($user_id,$shift_id,$date,$unit_id,$timelog_id);
                                                           ?>

                                                         <!--  <?php  if($payroll_data[$i]['shift_hours']){ ?>

                                                              <?php if($payroll_data[$i]['shift_hours']<$in_time) {
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


                                                                    <?php if($in_time=='00:00'){?>

                                                                            <?php if($user[0]['day_additional_hours'] || $user[0]['night_additional_hours']){?>
                                                                              <?php if($user[0]['day_additional_hours']){?>
                                                                                <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1($user[0]['day_additional_hours']));?> </td>
                                                                              <?php } else {?>
                                                                                <td id="totalhour_<?php echo $i; ?>"><?php echo settimeformat(getPayrollformat1($user[0]['night_additional_hours']));?> </td>
                                                                              <?php }?>
                                                                            <?php } else {?>
                                                                              <td id="totalhour_<?php echo $i; ?>"><?php echo "0.00";?> </td> 
                                                                            <?php }?>


                                                                     <?php }else{?>

                                                                                <?php 
                                                                                  if($user[0]['day_additional_hours']!='00:00' || $user[0]['night_additional_hours']!='00:00')
                                                                                  { //print_r($hour_new);
                                                                                    if($user[0]['day_additional_hours'])
                                                                                    {
                                                                                      $o=AddTimesnew($hour_new,$user[0]['day_additional_hours']);
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                      $o=AddTimesnew($hour_new,$user[0]['night_additional_hours']);
                                                                                    }                  
                                                                                  }
                                                                                  else
                                                                                  { 
                                                                                    $o=$hour_new;
                                                                                  }
                                                                               ?>

                                                                              <?php if($user[0]['day_additional_hours']=='' && $user[0]['night_additional_hours']==''){?> 

                                                                                 
                                                                                 <td id="totalhour_<?php echo $i; ?>"><?php print_r(settimeformat(getPayrollformat1($hour_new)));?> </td>

                                                                              <?php } else {?>
                                                                              <td id="totalhour_<?php echo $i; ?>"><?php  echo settimeformat(getPayrollformat1($o)); ?> </td>
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
                                                              <td id="totalhour_<?php echo $i; ?>"><?php  if($hour_new!='') echo settimeformat(getPayrollformat1($hour_new)); else echo "0.00";?> </td> 
                                                            <?php }?>

                                                        <?php }?> -->
                                                                  
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
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/waves.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/dropify/dist/js/dropify.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/moment/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/footable/js/footable.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.datetimepicker.min.js"></script>
 <!-- start - This is for export functionality only -->
 <script type="text/javascript"
    src="<?php echo base_url();?>/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<!-- start - This is for export functionality only -->
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.flash.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/jszip.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/pdfmake.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/vfs_fonts.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.html5.min.js"></script>
<script type="text/javascript"
    src="<?php echo base_url();?>assets/plugins/datatables.net/js/buttons.print.min.js"></script> 
<script type="text/javascript">
     $(function() {

     //$('#hours').val(0);
                                
                                    $(document).ready(function() {

                                       $("#from-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                    $("#to-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#to-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });


                                    var table = $('#myTable').DataTable({
                                     
                                    dom: 'lBfrtip',
                                    buttons: [
                                    'copy', 'excel', 'pdf', 'print'
                                    ],
                                    "displayLength": 25,
                                    "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                    initComplete: function () {
                                        this.api().columns([]).every(function () {
                                        var column = this;
                                        if (column.index() !== 0) 
                                        {  
                                           $(column.header()).append("<br>")
                                           var select = $('<select><option value=""></option></select>')
                                                               .appendTo($(column.header()))
                                                               .on('change', function () {
                                                 var val = $.fn.dataTable.util.escapeRegex(
                                                     $(this).val()
                                                 );
                                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                                             });

                                           column.data().unique().sort().each(function (d, j) {
                                             select.append('<option value="' + d + '">' + d + '</option>')
                                          } );
                                        }  
                                 
                                        });
                                        } 
                                        
                                    });

                                    // Order by the grouping
                                    $('#myTable tbody').on('click', 'tr.user', function() {
                                    var currentOrder = table.order()[0];
                                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                                    table.order([2, 'desc']).draw();
                                    } else {
                                    table.order([2, 'asc']).draw();
                                    }
                                    });
                                    
                                   $('#search').on('click',function(){   
                                           
                                           var from_date = $('#from-datepicker').val();  
                                           var to_date = $('#to-datepicker').val(); 
                                           var date1 = new Date(formatDate(from_date)); 
                                           var date2 = new Date(formatDate(to_date)); 
                                           if(date1 > date2)
                                           {
                                              alert("Start date should be smaller than end date");
                                              event.preventDefault();
                                           }
                                           else
                                           { 
                                                 $.ajax({
                                                      type :'POST',
                                                      dataType:'json',
                                                      data : { to_date:to_date,from_date:from_date},
                                                      url: baseURL+"manager/Reports_staff/timesheet",
                                                      success : function(result){  

                                                      }
                                                }); 
                                            }

                                    });

                                    });

  });

function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}

function cancelTraining(id){   //console.log(id);
           var baseURL = '<?php echo base_url();?>';
            if(id==2)
            {
                event.preventDefault();
                window.location = baseURL+"staffs/profile";
            }
            else
            {
                event.preventDefault();
                window.location = baseURL+"manager/profile";

            }
}
</script>