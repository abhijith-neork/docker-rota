<style>
    .tableFix { /* Scrollable parent element */
  position: relative;
  overflow: auto;
  height: 1200px;
}

.tableFix table{
  width: 250px;
  height: 50px;
  border-collapse: collapse !important;
}

.tableFix th{
  padding: 8px;
  text-align: left;
}

.tableFix thead th {
  position: sticky;  /* Edge, Chrome, FF */
  top: 0px;
  background: #fff;  /* Some background is needed */
}
.table td, .table th {
 
    width: 100px !important;
}
</style>
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
                                <li class="breadcrumb-item active">Annual Leave Planner</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center"></div>
                </div>
                <?php if($this->session->flashdata('error')==1)
                        $color="red";
                    else 
                        $color="green";
            
                    if($this->session->flashdata('message')):?>
                        <p class="success-msg" id="success-alert"
                    style="color: <?php echo $color; ?>; text-align:center;">
                        <?php echo $this->session->flashdata('message');?>
                        </p>
                <?php endif;?>  
            <form method="POST" action="<?php echo base_url();?>admin/Reports/annualleaveplanner" id="frmannualplannerReport"   name="frmannualplannerReport">
                <div class="row">
                    <div class="col-md-2" style="padding-left: 36px">
                        <div class="form-group">
                            <label for="Location">Unit<span style="color: red;">*</span></label> 
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
                    <?php $result=checkLeaveyear(date('Y-m-d'));?>
                    <div class="col-md-2">
                        <div class="form-group" style="padding-right: 36px;">
                            <label for="year">Year:<span style="color: red;"></span></label> 
                            <select class="form-control custom-select required year" id="year" name="year" style="width: 100%" data-placeholder="--Select year--">
                              <optgroup label="--Select year--" class="year" id="year" name="year">
                                  <?php
                                      for($i=0;$i<10;$i++) { 
                                  ?>  
                                   <option <?php    if($year_string==$result[$i]) { ?> selected="selected" <?php } ?> value="<?php echo "$result[$i]"; ?>" ><?php echo "$result[$i]"; ?></option>
                                  <?php } ?>  
                              </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="padding-right: 36px;">
                            <label for="year">Status:<span style="color: red;"></span></label> 
                            <select class="form-control custom-select required status" id="status" name="status" style="width: 100%" data-placeholder="--status--">
                                <option <?php    if($status=="1") { ?> selected="selected" <?php } ?> value="1">Active</option>
                                <option <?php    if($status=="2") { ?> selected="selected" <?php } ?> value="2">Inactive</option>
                                <option <?php    if($status=="3") { ?> selected="selected" <?php } ?> value="3">Deleted</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                            <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                        </div>
                    </div>
                    <?php if (count($user_list) > 0) { ?>
                        <div class="col-md-2">
                            <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                                <button type="button" class="search btn float-right hidden-sm-down btn-success" id="search" onclick="exportF(this)" style="width: 150px;">Export to excel</button> 
                            </div>
                        </div>
                    <?php } ?>
                </div>
<script type="text/javascript"> 
var jobe_roles=<?php print $job_roles;?>;
var selected_unit_details=<?php print_r($selected_unit_details);?>;
var status = <?php echo $status?>;
</script> 
<div id="print-content"  class="row"> 
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive m-t-40  tableFix" >
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="padding-right:180px;padding-left: 50px;position: sticky;left: 0px;background: #fff;z-index: 9"><centre>Name</centre></th> 
                                <th>Annual Leave Allowance</th> 
                                <th>User Rates</th>
                                <th>Start Date</th>
                                <?php $array = array("September", "October", "November", "December", "January", "February", "March", "April", "May" , "June", "July", "August");
                                for ($m=0; $m<12; $m++) {?>
                                   <th style="padding-left: 90px;"><?php print_r($array[$m]); ?></th>
                                <?php } ?> 
                                <th>Total Taken</th>
                                <th>Balance</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="weeks">
                                <td style="position: sticky;left: 0px;background: #fff;"><center>Week No</center></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php $month_array = createMonthArray($year_string)?>
                                <?php for ($m=0; $m<12; $m++) {?>
                                   <td>
                                    <?php 
                                    $full_week = weekOfMonthFromDate($month_array[$m]);
                                    $month=getNameOfMonth($month_array[$m]);
                                    ?>
                                        <table class="table table-bordered table-striped">
                                            
                                            <tr>
                                            <?php if($units!=0){$count=getCountofLeave($full_week,$this->input->post('unitdata'),$this->input->post('jobrole')); }
                                            for ($i=1; $i<=count($full_week); $i++) { $j=$i-1;?>
                                            <td class="child">
                                                <?php $date_on_sunday=getDateOnSUnday($full_week[$i-1])?>
                                                <div><span style="padding-left: 8px;"><label style="font: bold;"><?php echo $date_on_sunday; ?></label></span></div></br>
                                                <div><span>Count:<?php print_r($count[$j]['count']);?></span> </div>
                                            </td>
                                            <?php } ?>
                                            </tr>
                                        </table>
                                   </td>
                                <?php } ?>
                                <td> </td>
                                <td> </td>
                            </tr>
                                <?php foreach ($user_list as $staff) { 
                                    $total=getTotalLeavehours($staff['user_id'],$year_string,$staff['annual_holliday_allowance']);
                                    ?>
                                    <tr>
                                    <td style="position: sticky;left: 0px;background: #fff;"><?php echo $staff['fname']?> <?php echo $staff['lname']?></td> 
                                    <td><?php echo $staff['annual_holliday_allowance'];?></td>
                                    <td><?php echo $staff['day_rate'];?></td>
                                    <td><?php echo date('d/m/Y',strtotime($staff['start_date']));?></td> 
                                    <?php $month_array = createMonthArray($year_string)?>
                                    <?php for ($m=0; $m<12; $m++) {?>
                                       <td>
                                        <?php 
                                        $full_week = weekOfMonthFromDate($month_array[$m]);
                                        $month=getNameOfMonth($month_array[$m]);
                                        ?>
                                            <table class="table table-bordered table-striped" style="width: 100%;"><tr>
                                                <?php for ($i=0; $i<count($full_week); $i++) {?>
                                                <?php $date_array = getStartAndEndDates($full_week[$i])?>
                                                <?php $leaves = getWeeklyLeaves($staff['user_id'],$date_array['start_date'],
                                                   $date_array['end_date']);?>
                                                <?php $week_number = getWeeknumber1($date_array['start_date'])?>
                                                <?php $hours = getHoursFromWeekNumber($leaves,$week_number,$os);?>
                                                <?php if (count($leaves) !=0) { $a=$i+1;?>
                                                    <td style="color:white;padding: 1px; width: 42px;" class="child-<?php echo $week_number?>">
                                                        <?php echo $hours['div'];?>
                                                        <input type="hidden" class="<?php echo $month.'_'.$a.'_green';?>" id="<?php echo $month.'_'.$a.'_green';?>" value="<?php echo $hours['count'];?>">
                                                    </td>
                                                <?php } else { ?>
                                                    <td style="padding: 1px;width: 42px;">&nbsp;&nbsp;</td>
                                                <?php } ?>
                                                <?php } ?>
                                            </tr></table>
                                       </td>
                                    <?php } ?>
                                    <td><?php echo $total['total'];?></td>
                                    <td><?php echo $total['balance'];?></td>
                                    </tr>
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
<script type="text/javascript">
    function exportF(elem) {
        var text = 'eROTA - St Matthews Healthcare Annual Leave Planner -';
        var year = $("#year option:selected").text();
        var unit_name = selected_unit_details[0].unit_name;
        var table = document.getElementById("print-content");
        var html = table.outerHTML;
        var blob = new Blob([html], { type: "application/vnd.ms-excel" });
        window.URL = window.URL || window.webkitURL;
        link = window.URL.createObjectURL(blob);
        a = document.createElement("a");
        a.download = text+unit_name+'_('+year+').xls';
        a.href = link;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>
