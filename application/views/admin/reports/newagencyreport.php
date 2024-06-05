<div class="page-wrapper">
    <div class="container-fluid"> 
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
                                                                <li class="breadcrumb-item active">Agency Use Report</li>
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
            <form method="POST" action=" " id="frmpayrollReport"   name="frmpayrollReport">
            <div class="row">
                <div class="col-md-2" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Unit:<span style="color: red;"></span></label> 
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
                <div class="col-md-2">
                   <div class="form-group" style="padding-right: 36px;">
                    <label for="Location">Job role:<span style="color: red;"></span></label> 
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
                                    <label for="Status">Status</label> 
                                        <select required="required" class="form-control custom-select required status" id="status" name="status" placeholder="Select Status"> 
                                        <option value="1"  <?php if($this->input->post('status')=="1"){?>  selected="selected" <?php }?> >Active</option>
                                        <option value="0"  <?php if($this->input->post('status')=="0"){?>  selected="selected" <?php }?> >All</option>
                                        <option value="2"  <?php if($this->input->post('status')=="2"){?>  selected="selected" <?php }?> >Inactive</option> 
                                        <option value="3"  <?php if($this->input->post('status')=="3"){?>  selected="selected" <?php }?> >Deleted</option> 
                                        </select>
                    </div>
                </div>
                <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group"> 
                                <label for="from_date">From date:<span style="color: red;">*</span></label> 
                                <input type="text"  class="form-control required" id="start_date" placeholder="dd/mm/yy" name="start_date" value="<?php echo "$start_date";?>"> 
                            </div>
                </div>
                <div class="col-md-2" style="padding-right: 36px;">
                            <div class="form-group"> 
                                <label for="to_date">To date:<span style="color: red;">*</span></label> 
                                <input type="text" class="form-control required" id="end_date" placeholder="dd/mm/yy"  name="end_date" value="<?php echo "$end_date";?>"> 
                            </div>
                </div>
                
                <!-- <div class="col-md-2" style="padding-top: 34px;" > -->
                <div class="col-md-2">
                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
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
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                            <th>Name</th>
                                                            <th>EL count</th> 
                                                            <th>E count</th> 
                                                            <th>L count</th>
                                                            <th>N count</th>
                                                            <!-- <th>Total hours</th> -->
                                                               
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <?php  foreach ($proll as $payroll)  { 


                                                        //calculate total hours
                                                         $dayhour=$payroll['day_hour']['dayhour'];
                                                         $daysat=$payroll['day_hour']['saturday_hour'];
                                                         $daysun=$payroll['day_hour']['sunday_hour'];


                                                         if($dayhour=='00.00')
                                                         {
                                                            $dayhour=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional'],2))));
                                                            $day=settimeformat($dayhour);
                                                         }
                                                         else
                                                         {  
                                                            ////print_r(number_format(settimeformat_new($payroll['additional'],2)));
                                                             //$dayhour=AddTimesnew($dayhour,number_format($payroll['additional'],2));
                                                             $dayhour=$dayhour+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional'],2))));
                                                             $day=number_format($dayhour,2);
                                                         } 

                                                         if($daysat=='00.00' )
                                                         {
                                                            $daysat=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_sat'],2))));
                                                            //$daysat=getPayrollformat1(settimeformat_new(number_format($payroll['additional_sat'],2)),2);
                                                            $daysat=settimeformat($daysat);
                                                         }
                                                         else
                                                         {
                                                            $daysat=$daysat+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_sat'],2))));
                                                            //$daysat=$daysat+getPayrollformat1(number_format($payroll['additional_sat'],2),2);
                                                            $daysat=number_format($daysat,2);
                                                         }
                                                        

                                                         if($daysun=='00.00')
                                                         {
                                                            $daysun=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_sun'],2))));
                                                            //$daysun=getPayrollformat1(settimeformat_new(number_format($payroll['additional_sun'],2)),2);
                                                            $daysun=settimeformat($daysun);
                                                         }
                                                         else
                                                         {
                                                            $daysun=$daysun+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_sun'],2))));
                                                            //$daysun=$daysun+getPayrollformat1(number_format($payroll['additional_sun'],2),2);
                                                            $daysun=number_format($daysun,2);
                                                         }

                                                          $weekday=$payroll['night_hour']['weekdayhour'];
                                                          $weekend=$payroll['night_hour']['weekend_hour']; 

                                                          if($weekday=='00.00')
                                                         {
                                                            $weekday=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_byweekday'],2))));
                                                            $weeknightday=settimeformat($weekday);
                                                         }
                                                         else
                                                         {  
                                                            $weekday=$weekday+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_byweekday'],2))));
                                                            $weeknightday=number_format($weekday,2);
                                                         }

                                                         if($weekend=='00.00')
                                                         {
                                                            $weekend=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_byweekend'],2))));
                                                            $weekendnightday=settimeformat($weekend);
                                                         }
                                                         else
                                                         {  
                                                            $weekend=$weekend+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_byweekend'],2))));
                                                            $weekendnightday=number_format($weekend,2);
                                                         }

                                                         
                                                         $holiday=$payroll['holiday'][0]['total'];
                                                         $training=number_format($payroll['new_training'],2); //print_r($training);
                                                         if($training=='0.00')
                                                         {
                                                            $training_new=settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_bytraining'],2))));
                                                            $training=settimeformat($training_new);

                                                         }
                                                         else
                                                         {
                                                            $training_new=$training+settimeformat(getPayrollformat1(settimeformat_new(number_format($payroll['additional_hour_bytraining'],2))));
                                                            $training=number_format($training_new,2);

                                                         }
                                                        //$training=number_format(getPayrollformat(number_format($payroll['training'][0]['trainingtime'],2)),2);
                                                         //$bank_holiday=number_format(getPayrollformat(number_format($payroll['bank_holiday'][0]['totalbankholidayhours'],2)),2);
                                                            $bank_holiday=number_format($payroll['day_hour']['day_bank_holiday'],2)+number_format($payroll['night_hour']['night_bank_holiday'],2);
                                                         // print_r("<pre>");
                                                         // print_r($payroll['user_details'][0]['fname']);print '<br>';
                                                         // print_r($day);print '<br>';
                                                         // print_r($daysun);print '<br>';
                                                         // print_r($daysat);print '<br>';


                                                        $total=$day+$daysun+$daysat+$training+$holiday+$weeknightday+$weekendnightday+$payroll['additional_Sick'][0]['totalsickhours']+$bank_holiday;
                                                        //print_r($total);
                                                           
                                                          

                                                           $Hours=getContractHours($weeks,$payroll['user_details'][0]['weekly_hours']); 
                                                        $Overtime=number_format($total,2)-number_format(getPayrollformat(number_format($Hours,2)),2);  

                                                        if($payroll['shifts_count']['EL_count']==0 && $payroll['shifts_count']['E_count']==0 && $payroll['shifts_count']['L_count']==0 && $payroll['shifts_count']['N_count']==0 && number_format($total,2)=='0.00')
                                                        {
                                                           $type='false';
                                                        }
                                                        else
                                                        {
                                                            $type='true';
                                                        }

                                                       
                                                        ?> 
                                                        <?php if($type=='true'){?>
                                                    <tr>

                                                        <td><?php print_r($payroll['user_details'][0]['fname'].' '.$payroll['user_details'][0]['lname']);?></td> 
                                                        <td><?php print_r($payroll['shifts_count']['EL_count']);?></td>
                                                        <td><?php print_r($payroll['shifts_count']['E_count']);?></td>
                                                        <td><?php print_r($payroll['shifts_count']['L_count']);?></td> 
                                                        <td><?php print_r($payroll['shifts_count']['N_count']);?></td>
                                                        <!-- <td><?php echo number_format($total,2);?></td> -->
                                                        
                                                    </tr>
                                                     <?php } ?>
                                                <?php } ?>
                                               
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

 
 