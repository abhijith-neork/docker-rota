<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css">
<link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet"> 

<style type="text/css"> 
.page-wrapper {
        margin-left: 0px !important;
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
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active">Time log</li>
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
            <form method="POST" action=" " id="frmViewOverstaffingReport"   name="frmViewOverstaffingReport">
              <div class="row">
                <div class="col-md-3" style="padding-right: 36px;padding-left: 62px;">
                    <div class="form-group">
                            <label for="Location">Start date <span style="color: red;">*</span></label> 
                            <input class="start_date form-control" required type="text" name="start_date" id="start_date" name="start_date"  value="<?php echo "$start_date";?>">
                    </div>
                </div>
                <div class="col-md-3" style="padding-right: 36px;">
                    <div class="form-group">
                            <label for="Location">End date <span style="color: red;">*</span></label> 
                            <input class="end_date form-control" required type="text" name="end_date" id="end_date" name="end_date"  value="<?php echo "$end_date";?>">
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
             <div class="row"> 
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                                <th>User ID</th> 
                                                                <th>Name</th>
                                                                <th>Shift Name</th> 
                                                                <th>Unit Name</th>
                                                                <th>Check in/out from</th>
                                                                <!-- <th>Type</th> -->
                                                                <th>Date</th>
                                                                <th>Check-In</th>
                                                                <th>Check-Out</th>

                                                                                    
                                                    </tr>
                                                </thead>
                                                <tbody>   
                                                <?php for($i=0;$i<count($timelog);$i++)
                                                 { 
                                                  if($timelog[$i]['user_id']==$timelog[$i+1]['user_id'])
                                                  {
                                                    if($timelog[$i]['time_to']=='00:00:00' && !empty($timelog[$i+1]['time_to']))
                                                    {
                                                    $timelog[$i]['time_to']= $timelog[$i+1]['time_to'];
                                                    $date=$timelog[$i+1]['timelogdate'];
                                                    }
                                                    else
                                                    {
                                                    $timelog[$i]['time_to']= $timelog[$i]['time_to'];
                                                    $date=$timelog[$i]['timelogdate'];
                                                    }
                                                  }
                                                  else
                                                  {
                                                    $timelog[$i]['time_to']= $timelog[$i]['time_to'];
                                                    $date=$timelog[$i]['timelogdate'];
                                                  }
                                                 // print_r("<pre>"); print_r($timelog[$i]['user_id']); print "-"; print_r($timelog[$i]['rota_unit']); print "-"; print_r($timelog[$i]['unit_id']); print "<br>";

                                                  if($timelog[$i]['rota_unit']!=$timelog[$i]['unit_id'] && $timelog[$i]['unit_id']!='')
                                                  {
                                                        $actual_unit=getRealUnitname($timelog[$i]['rota_unit']);
                                                        $new_unit='('. $actual_unit .')';
                                                  }
                                                  else
                                                  {
                                                        $new_unit='';

                                                  }  

                                                ?> 

                                                <?php
                                                 //print_r($timelog[$i]['fname']); print '<br>';
                                                //print_r($timelog[$i]['time_to']); print '<br>';
                                                //print_r($timelog[$i-1]['time_to']);print '<br>';

                                                if(($timelog[$i]['time_to']=='00:00:00')||($timelog[$i-1]['time_to']=='00:00:00'))
                                                {
                                                  $new_status=1;
                                                }
                                                else if($timelog[$i]['time_to']!=$timelog[$i-1]['time_to'])
                                                {
                                                  $new_status=1;
                                                }
                                                else
                                                {
                                                  $new_status=2;
                                                }

                                                //print_r('<pre>');
                                                //print_r($new_status); print '<br>';
                                                //print_r($timelog[$i]['time_to']); print '<br>';
                                                //print_r($timelog[$i-1]['time_to']);print '<br>';
                                                //print_r($timelog[$i]['fname']); print '<br>'; print '<br>';


                                                ?>
                                       <?php //if($userSchedule[0]['id']!=0 || $userSchedule[0]['id']!=1 ){ //not holiday or offday ?>
 
                                                
                                                    <?php if($timelog[$i]['time_from']!='00:00:00' || $timelog[$i]['time_from']!=''){ ?>  
                                                    <?php if($new_status==1){?>
                                                    <?php $userSchedule= getSchedule($timelog[$i]['rotadate'],$timelog[$i]['user_id']);
                                                    if(count($timelog[$i]['unit_id'])==0)
                                                    {
                                                      $unit_loged=" ";
                                                    }
                                                    else
                                                    {
                                                      $units=getLogedUnit($timelog[$i]['unit_id']); 
                                                      $unit_loged=$units[0]['unit_name'];

                                                    } 

                                                          if($timelog[$i]['unit_name']=='')
                                                          {
                                                                $usersunit=getActualunit($timelog[$i]['user_id']); 
                                                                $user_unit=$usersunit[0]['unit_name'];
                                                          }
                                                          else
                                                          {
                                                                $user_unit=$timelog[$i]['unit_name'];

                                                          }


                                                     ?>

                                                     <?php 
                                                         if($userSchedule[0]['id']==16)
                                                        {  //print_r($timelog[$i]['time_from']);
                                                          if($timelog[$i]['time_from']=='00:00:00')
                                                          { //print "hiii";
                                                            $date1 = str_replace('-', '/', $timelog[$i]['timelogdate']);
                                                            $tomorrow1 = date('Y-m-d',strtotime($date1 . "-1 days"));
                                                            $datenew=$tomorrow1;
                                                            $stat="from";
                                                          }
                                                          else if($timelog[$i]['time_to']=='00:00:00')
                                                          { //print "hello";
                                                            $date1 = str_replace('-', '/', $timelog[$i]['timelogdate']);
                                                            $tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
                                                            $datenew=$tomorrow;
                                                            $stat="to";
                                                          }
                                                          else
                                                          {
                                                            $datenew=$timelog[$i]['time_log_date'];
                                                            $stat="not";

                                                          }
 //print_r($datenew);

                                                          $date_time=getDateAndTime($datenew,$timelog[$i]['user_id']);
                                                          //print_r($date_time); print_r("<pre>");
                                                          if(!empty($date_time))
                                                          {
                                                            if($stat=='from')
                                                            {  
                                                                $timelog[$i]['time_from']=$date_time[0]['time_from'];
                                                                $timelog[$i]['timelogdate']=$datenew;
                                                                $timelog[$i]['date']=$datenew;
                                                            }
                                                            else if($stat=='to')
                                                            {  
                                                              $timelog[$i]['time_to']=$date_time[0]['time_to'];
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

                                                    <?php if($userSchedule[0]['shift_name']==''){ $shift_name='No Shift';}else{$shift_name=$userSchedule[0]['shift_name']; }?>

                                                    <?php 

                                                    if($userSchedule[0]['shift_category']==''){ $shift_category='0'; } else{$shift_category=$userSchedule[0]['shift_category']; }

                                                    if(($timelog[$i]['time_from']=='00:00:00' || $timelog[$i]['time_from']=='00:00:00') && ($shift_category=='0'))
                                                    {
                                                      $pre_status=1;
                                                      //print_r($timelog[$i]['fname']);
                                                      //print_r($shift_category);
                                                      //print_r($shift_name);
                                                      //print_r($timelog[$i]['time_from']);
                                                      //print_r($timelog[$i]['time_to']);
                                                      //print_r("equl");
                                                    }
                                                    else
                                                    {
                                                      $pre_status=2;
                                                      //print_r($timelog[$i]['fname']);
                                                      //print_r($shift_category);
                                                      //print_r($shift_name);
                                                      //print_r($timelog[$i-1]['time_from']);
                                                      //print_r($timelog[$i-1]['time_to']);
                                                      //print_r("not equal");
                                                    }


                                                    //print_r($shift_name);
                                                   // print_r($start_date);
                                                    //print_r($end_date); 

                                                   // print_r($pre_status);
                                                    
                                                    //print '<br>';


                                                    ?>
 
                                                  <?php if($pre_status==2){?>

                                                    <?php if($userSchedule[0]['id']!=16 || $timelog[$i]['timelogdate']!=$tomorrow1 ) {?>

                                                    <tr title="<?php echo "--".$timelog[$i]['time_from']."-- From";?>">
                                                      
                                                      

                                                      <?php 

                                                        if($shift_name!='Training')
                                                        {  //print "asdfa-".$userSchedule[0]['start_time'];
                                                          if($timelog[$i]['time_from']=='')
                                                          { 
                                                              $shift=gettimeDiffcheckin($userSchedule[0]['start_time'],$userSchedule[0]['end_time'],$timelog[$i]['time_from'],$timelog[$i]['time_to'],$timelog[$i]['rotadate']);
                                                          }
                                                          else
                                                          { 

                                                              $shift=gettimeDiffcheckout($userSchedule[0]['start_time'],$userSchedule[0]['end_time'],$timelog[$i]['time_from'],$timelog[$i]['time_to'],$timelog[$i]['rotadate'],$userSchedule[0]['id']);
                                                          }
                                                        }
                                                        else
                                                        { 

                                                            $Training=getTrainingtime($timelog[$i]['user_id'],$timelog[$i]['rotadate'],$timelog[$i]['rotadate']); 
                                                          //print_r($Training);
                                                          
                                                          if($Training)
                                                          {
                                                            if($timelog[$i]['time_from']=='')
                                                            { 
                                                                $shift=gettimeDiffcheckin($Training[0]['time_from'],$Training[0]['time_to'],$timelog[$i]['time_from'],$timelog[$i]['time_to'],$timelog[$i]['rotadate']);
                                                            }
                                                            else
                                                            {
                                                                $shift=gettimeDiffcheckout($Training[0]['time_from'],$Training[0]['time_to'],$timelog[$i]['time_from'],$timelog[$i]['time_to'],$timelog[$i]['rotadate'],$userSchedule[0]['id']);
                                                           
                                                            }
                                                          }
                                                          else
                                                          {
                                                            $shift['status']='None';
                                                          }

                                                        }
 

                                                      ?>

                                                      <td><?php  echo $timelog[$i]['user_id']; ?> </td>
                                                      <td 

                                                      <?php if($shift_name=='Holiday' || $shift_name=='Offday' || $shift_name=='Sick' ){?>

                                                          style="color:orange;"
                                                      <?php }else{?>

                                                        <?php if($shift['status']=='True'){?>

                                                          style="color:red;"

                                                        <?php } else if($shift['status']=='False') {?>

                                                          style="color:green;"

                                                        <?php } else if($shift['status']=='True1') {?>

                                                          style="color:blue;"

                                                        <?php } else {?>

                                                          style="color:none;"


                                                        <?php }?>
                                                      <?php }?>

                                                      >
                                                      

                                                      <?php echo $timelog[$i]['fname'].' '.$timelog[$i]['lname'];?> 


                                                      </td>
                                                      <td><?php  echo $shift_name;?> </td>
                                                      <td><?php echo $user_unit;?> </td>
                                                      <td><?php echo $unit_loged;?> </td>
                                                      <!-- <td><?php echo $typename;?> </td> -->
                                                      <td  data-sort="<?php echo $timelog[$i]['rotadate']; ?>"><?php echo date('d/m/Y',strtotime($timelog[$i]['rotadate']));?></td>                                           
                                                      <?php if($timelog[$i]['time_from']!='00:00:00') { ?> <!-- checkin time -->
                                                        <td data-sort="<?php echo $timelog[$i]['timelogdate']; ?>">
                                                          <?php echo corectDateFormat($timelog[$i]['timelogdate']).' '.$timelog[$i]['time_from'];?> 
                                                        </td>

                                                        <?php } else {?>

                                                        <td>
                                                          <?php echo '';?> 
                                                        </td>
                                                        <?php }?>


                                                        <?php if($timelog[$i]['time_to']!='00:00:00') { ?> <!-- checkout time -->
                                                        <td data-sort="<?php echo $timelog[$i]['timelogdate']; ?>">
                                                          <?php echo corectDateFormat($date).' '.$timelog[$i]['time_to'];?> 
                                                        </td>
                                                        
                                                        <?php } else {?>

                                                        <td>
                                                          <?php echo '';?> 
                                                        </td>
                                                        <?php }?>

                                                    </tr>
                                                <?php  } } } }?>  
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
                                
                                    $(document).ready(function() {

                                    $("#start_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#start_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                    $("#end_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#end_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 


                                    var table = $('#myTable').DataTable({
                                    "order": [[ 5, "asc" ]],
                                    // "order": [
                                    // [1, 'asc']
                                    // ],
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

                                     $('#myTable tbody').on('click', 'tr.user', function() {
                                    var currentOrder = table.order()[0];
                                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                                    table.order([2, 'desc']).draw();
                                    } else {
                                    table.order([2, 'asc']).draw();
                                    }
                                    });
                                    
                                   $('#search').on('click',function(){   
                                           
                                           var from_date = $('#start_date').val();  
                                           var to_date = $('#end_date').val(); 
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
                                                      url: baseURL+"manager/Reports_staff/timelogmobileview",
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

 