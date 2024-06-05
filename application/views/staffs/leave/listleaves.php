<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Annual Leave</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Annual Leave</li>
                        </ol>
                    </div>
                    <div class="col-md-3 col-4 align-self-center">
                       <!--  <select name="credit-card-expiration-year" id="credit-card-expiration-year">
                            <?php
                                for($i = date("Y"); $i < date("Y")+10; $i++){
                                    echo "<option>" . $i . "</option>";
                                }
                            ?>
                        </select> -->
                    </div>
                    <div class="col-md-3 col-4 align-self-center" style="padding-left: 31px;">
                        <button class="btn   btn-success"
                            onclick="location.href='<?php echo base_url();?>staffs/leave/leaverequest'" style="width: 175px;">                           
                            <i class="mdi mdi-plus-circle"></i> Apply Annual Leave
                        </button>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-12">
                        <div class="card">               
                            <div class="card-body">
                                <?php if($this->session->flashdata('message')):?>
                                    <p class="success-msg" id="success-alert" style="color: green; text-align:center; ">
                                    <?php echo $this->session->flashdata('message');?>
                                    </p>
                                <?php endif;?> 
                                <?php if($error):?>
                                     <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
                                    <?php echo $error;?>
                                    </p>
                                <?php endif;?>       
                                  <?php 
                                $nYear = date('Y')+1;
                             
                                ?>                 
                                <div class="table-responsive m-t-40">
                                    <?php 

                                    $new_year=findnextyear($leave_year); 

                                      foreach($years as $yr){  

                                        if (strpos($yr['year'], $nYear) !== true) {

                                            if($yr['year']==$leave_year || $yr['year']==$new_year){

                                                 $year=getYear($yr['year']); 
                                                ?>
                                    <label style="font-weight: 400;"></label>
                                    <table  class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                               
                                                <th>Annual Leave Allowance - <?php echo ' '.'('.' '.$year.' '.')';?></th>
                                                <th>Annual Leave Used </th>
                                                <th>Annual Leave Remaining</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                
                                           <?php $hours=getHoursByUser($user_id,$yr['year']);?>
                                            <td>
                                                <?php

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
                                                
                                                if($yr['year']==$CYear)
                                                { 
                                                    echo str_replace(':', '.', $annual_holliday_allowance).' hours'; 
                                                    $leave_remaining=(float)$annual_holliday_allowance-(float)$hours;
                                                    if($leave_remaining <= 0){$leave_remaining='0.00';}else{$leave_remaining=number_format($leave_remaining,2);} 
                                                } else 
                                                {  
                                                    echo str_replace(':', '.', $actual_holiday_allowance).' hours';
                                                    $leave_remaining=(float)$actual_holiday_allowance-(float)$hours;
                                                    if($leave_remaining <= 0){$leave_remaining='0.00';}else{$leave_remaining=number_format($leave_remaining,2);}
                                                }?>
                                            </td>
                                            <td>
                                                <?php echo number_format((float)$hours, 2) .' hours'?>
                                            </td>
                                            <td>
                                                <?php echo number_format((float)$leave_remaining, 2).' hours'?>
                                            </td>  
                                        </tbody>
                                    </table>
                                <?php  } } }?>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>

                <div class="row"> 
                    <div class="col-12"> 
                    <h5 style="padding-left: 46px;">List Annual Leave Request Details</h5>
                        <div class="card"> 
                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                    <table id="myTable1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr> 
                                                <th>From Date</th> 
                                                <th>To Date</th> 
                                                <th>Number of Hours</th>
                                                <th>Applied Date&Time</th> 
                                                <th>Status</th>  
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php foreach($users as $use) {
                                                $tdate = explode("-",$use['to_date']); //print_r($tdate); print '<br>';
                                                
                                                if(date("m") > 8)
                                                {
                                                      $currentdate = date("Y"); 
                                                }
                                                else
                                                {
                                                      $currentdate = date("Y")-1;

                                                }

                                                if($tdate[0] >= $currentdate){
                                                
                                               ?>
                                                <tr>
                                                  <?php 

                                                        if($use['leave_remaining']!= null)
                                                        {
                                                            if($use['leave_remaining'] < 0)
                                                            {
                                                                $leave = '0:0';
                                                            }
                                                            else
                                                            {
                                                                $leave=str_replace(".",":",number_format($use['leave_remaining'],2));
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $leave='0:0';
                                                         }
                                                     $days=$use['days'];
                                                     $days=str_replace(":",".",$use['days']); $day=getPayrollformat(number_format($days,2),2);
                                                    ?>
                                                    <td><?php echo date("d/m/Y",strtotime($use['from_date'])); ?></td>  
                                                    <td><?php echo date("d/m/Y",strtotime($use['to_date'])); ?></td>  
                                                    <td><?php echo number_format($day,2);?></td>
                                                    <td><span style="display: none;"><?php echo $use['creation_date']; ?></span><?php echo date("d/m/Y H:i:s",strtotime($use['creation_date'])); ?></td>
                                                    <td>
                                                        <?php if($use['status']==1) { echo "Approved"; } 
                                                         else if($use['status']==2){ echo "Rejected";} 
                                                         else if($use['status']==0)
                                                        {
                                                            echo "Pending"; ?>
                                                            <span style="padding-left: 100px;"><button type="button" class="btn btn-success" href="javascript:void(0);" onclick="deleteFunction('<?php echo $use['id']; ?>','<?php echo date("d/m/Y",strtotime($use['from_date'])); ?>','<?php echo date("d/m/Y",strtotime($use['to_date'])); ?>','<?php echo number_format($day,2);?>')" title="Cancel Leave">Cancel Leave</button></span>
                                                        <?php } 
                                                        else { echo "Cancelled";}
                                                        ?> 
                                                    </td>      
                                                </tr>
                                            <?php } } ?>
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
<script>   
 var selected_date= ' ';   
</script>