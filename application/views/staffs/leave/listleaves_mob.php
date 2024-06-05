<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css">
<link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet"> 
<link href="<?php echo base_url();?>assets/css/sweetalert.css" rel="stylesheet" />
<style type="text/css"> 
.page-wrapper {
        margin-left: 0px !important;
}
</style>
<script>
  var baseURL = "<?php echo base_url();?>";
</script>
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
                            onclick="location.href='<?php echo base_url();?>staffs/leave/leaverequest'" style="width: 175px;margin-bottom: 9px;">                           
                            <i class="mdi mdi-plus-circle"></i> Apply Annual Leave
                        </button>
                        <div class="row">
                                                <button class="btn float-right hidden-sm-down btn-secondary"
                                                    onclick="location.href='<?php echo base_url();?>staffs/profile'" style="height: 40px; width:120px;margin-left:10px;">
                                                    Back
                                                </button> <br>
                                            </div>
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
                                                $CYear = $currentyear."-".$nxtyear;
                                                
                                                if($yr['year']==$CYear)
                                                { 
                                                    echo str_replace(':', '.', $annual_holliday_allowance).' hours'; 
                                                    $leave_remaining=$annual_holliday_allowance-$hours;
                                                    if($leave_remaining <= 0){$leave_remaining='0.00';}else{$leave_remaining=number_format((float)$leave_remaining,2);} 
                                                } else 
                                                {  
                                                    echo str_replace(':', '.', $actual_holiday_allowance).' hours';
                                                    $leave_remaining=$actual_holiday_allowance-$hours;
                                                    if($leave_remaining <= 0){$leave_remaining='0.00';}else{$leave_remaining=number_format((float)$leave_remaining,2);}
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
                                <?php } } }?>
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
                                                $tdate = explode("-",$use['to_date']);
                                                $tdate = explode("-",$use['to_date']);
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

                                                        if($use['leave_remaining'] != null)
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
                                            <?php } }?>
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script> 
<script type="text/javascript">
   $(function() {
               
    $(document).ready(function() {
        $("#start_date").datepicker({ 
            dateFormat: 'dd/mm/yy'
        });
        $("#end_date").datepicker({ 
            dateFormat: 'dd/mm/yy'
        });
        var table = $('#myTable').DataTable({
            "bFilter": false,
            "bInfo": false,
            "bLengthChange": false,    
            "order": [
                [0, 'asc']
            ],
            "displayLength": 25,
            "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
        });
        // Order by the grouping
        $('#myTable tbody').on('click', 'tr.unit', function() {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            table.order([2, 'desc']).draw();
        } else {
            table.order([2, 'asc']).draw();
        }
        });
    });
});
$(function() {                                
    $(document).ready(function() {
        var table = $('#myTable1').DataTable({
            "bFilter": false,
            "bInfo": false,
            "bLengthChange": false,
            "order": [
                [3, 'desc']
            ],
            "displayLength": 25 
        });
        // Order by the grouping
        $('#myTable tbody').on('click', 'tr.unit', function() {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            table.order([2, 'desc']).draw();
        } else {
            table.order([2, 'asc']).draw();
        }
        });
    });
});
function deleteFunction(id,from_date,to_date,hour)
{
    var from_date=from_date;
    var to_date=to_date;
        event.preventDefault(); // prevent form submit
        //var form = event.target.form; // storing the form
                swal({
          title: 'Are you sure you want to cancel the leave from  '+from_date+' to '+to_date+'('+hour+' hours'+')'+'?', 
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, cancel it!",
          cancelButtonText: "No!",
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
           // form.submit();          // submitting the form when user press yes
              window.location = baseURL+"staffs/Leave/cancelleave/mob/"+id
          } else {
              return false;
          }
        });
 
}
</script>