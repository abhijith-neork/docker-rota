<style>
    label .check-box-effect {
  display: inline-block;
  position: relative;
  background-color: transparent;
  width: 19px;
  height: 19px;
  border: 2px solid #dcdcdc; 
  border-radius: 10%;
}

label .check-box-effect:before {
  content: "";
  width: 0px;
  height: 2px;
  border-radius: 2px;
  background: #626262;
  position: absolute;
  transform: rotate(45deg);
  top: 7px;
  left: 3px;
  transition: width 50ms ease 50ms;
  transform-origin: 0% 0%;
}

label .check-box-effect:after {
  content: "";
  width: 0;
  height: 2px;
  border-radius: 2px;
  background: #626262;
  position: absolute;
  transform: rotate(305deg);
  top: 11px;
  left: 5px;
  transition: width 50ms ease;
  transform-origin: 0% 0%;
}

label:hover .check-box-effect:before {
  width: 5px;
  transition: width 100ms ease;
}

label:hover .check-box-effect:after {
  width: 11px;
  transition: width 150ms ease 100ms;
}

input[type="checkbox"] {
  display: none;
}

input[type="checkbox"]:checked + .check-box-effect {
  background-color: #009efb !important;
  transform: scale(1.25);
}

input[type="checkbox"]:checked + .check-box-effect:after {
  width: 10px;
  background: #333;
  transition: width 100ms ease 50ms;
}

input[type="checkbox"]:checked + .check-box-effect:before {
  width: 5px;
  background: #333;
  transition: width 100ms ease 50ms;
}

input[type="checkbox"]:checked:hover + .check-box-effect {
  background-color: #dcdcdc;
  transform: scale(1.25);
}

input[type="checkbox"]:checked:hover + .check-box-effect:after {
  width: 10px;
  background: #333;
  transition: width 100ms ease 50ms;
}

input[type="checkbox"]:checked:hover + .check-box-effect:before {
  width: 5px;
  background: #333;
  transition: width 100ms ease 50ms;
}
.spinnermodal {
        background-color: #FFFFFF;
        height: 100%;
        left: 0;
        opacity: 0.5;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 100000;
    }
</style>
<div class="page-wrapper">
  <div class="container-fluid"> 
    <div class="card">
      <div class="card-body">
        <form method="" action="" id="frmprintview" name="frmprintview">
          <a id="downloadLink" class="btn hidden-sm-down btn-success" onclick="exportF(this)" style="color: white">Export to excel</a>
          <input type="button" id="reload" class="btn hidden-sm-down btn-success" style="display: none;" onclick="reLoad('reloadview')" value="Reload"/>
          <div id="print-content" class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive m-t-40">
                    <div>
                      <?php if(count($rota) == 0){?>
                        <h2 align="center">No rota found</h2><?php }?>
                    </div>
                    <table style="border: 1px solid rgba(120, 130, 140, 0.13);width: 100%;margin-bottom: 1rem;color: #212529;border-collapse: collapse;font-family: Rubik, sans-serif;"  class="table table-bordered ">
                      <thead>
                        <?php $rt_ids = array();?>
                        <?php $all_dates = array();?>
                        <?php $unit_name=getUnitold($unit_id);?>
                        <h2 style="text-align: center;font-family: Rubik, sans-serif;"><?php echo $unit_name[0]['unit_name']?> (<?php echo getMonthName($month);?> - <?php echo $year;?>)</h2>
                        <tr style="background-color: #8080804d;font-weight: 500;padding: .75rem;box-sizing: border-box;margin: 15px;height: 40px;">
                            <th style="text-align: center;font-size: 14px;">Name</th>
                            <th style="text-align: center;font-size: 14px;">User ID</th>
                            <th style="text-align: center;font-size: 14px;">Job Role</th>
                            <?php $initial_datearray = array();?>
                            <?php foreach ($rota as $rt) { ?>
                              <?php array_push($rt_ids,$rt['rota_id']) ?>
                              <?php if (in_array($rt['start_date'], $initial_datearray)) { ?>
                              <?php } else { ?>
                                <?php $date_array=findDatesBetnDates($rt['start_date'],$rt['end_date']);?>
                                <?php for($i=0;$i<count($date_array);$i++) { ?>
                                    <?php array_push($all_dates,$date_array[$i]) ?>
                                    <th class="date_<?php echo formatDate($date_array[$i]); ?>" style="text-align: center;font-size: 14px;"><?php echo removeYear($date_array[$i]) ?></th>
                                <?php } ?>
                              <?php } ?>
                              <?php array_push($initial_datearray,$rt['start_date']) ?>
                            <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $user_array=getArrayUsersHorizontal($rt_ids,$job_ids,$unit_id);?>
                        <?php $designation_name=''?>
                        <?php for($j=0;$j<count($user_array);$j++) { ?>
                          <?php $user_details=getUserDetails($user_array[$j]['user_id']);?>
                          <?php $user_shifts = get_user_shifts_on_dates($user_array[$j]['user_id'],$all_dates);
                          ?>
                          <?php $user_hours = get_hours($user_shifts); ?>
                          <?php if($designation_name != $user_details[0]['designation_name']){?>
                          <tr class="des">
                            <td colspan="1" style="font-family: Rubik, sans-serif;font-weight:bold;font-size: 14px"><strong><?php echo $user_details[0]['designation_name']?>
                            </strong></td>
                            <td></td>
                            <td></td>
                            <?php for($x=0;$x<count($all_dates);$x++) { ?>
                                <td style="font-family: Rubik, sans-serif;font-weight:bold;font-size: 12px" colspan="1">    
                                  <?php $shift_count=getELNShiftCount($user_details[0]['designation_id'],$all_dates[$x],$user_array,$rt_ids);?><b>
                                    <span>E : <?php echo $shift_count['early_shift']?></span> , <span>L : <?php echo $shift_count['late_shift']?></span> , <span>N : <?php echo $shift_count['night_shift']?></span></b>
                                </td>
                            <?php } ?>
                          </tr>
                          <?php }?>
                          <tr>
                            <td class='user_<?php echo $user_details[0]['id']?>' style="border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_details[0]['fname']." ".$user_details[0]['lname']." (".$user_details[0]['weekly_hours'].")".' '.$user_hours;?></td>
                            <td style="border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_details[0]['id']?></td>
                            <td style="border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_details[0]['designation_code']?></td>
                            <?php 
                              $params = array();
                              $params['new_start_date'] = $new_start_date;
                              $params['new_end_date'] = $new_end_date;
                              $params['selected_unitid'] = $unit_id;
                              $params['user_unitid'] = $user_array[$j]['unit_id'];
                            ?>
                            <?php for($k=0;$k<count($all_dates);$k++) { ?>
                              <?php $user_shifts=getShiftsWithDate($rt_ids,$user_array[$j]['user_id'],$all_dates[$k],$params);?>
                              <?php if (count($user_shifts) > 0) { ?>
                                <?php $t_status=checkUserTraining($user_array[$j]['user_id'],$user_shifts[0]['date']);?>
                                <?php $h_status=checkUserHoliday($user_array[$j]['user_id'],$user_shifts[0]['date']);?>
                                <?php if ($t_status == true) { ?>
                                  <td class="date_<?php echo formatDate($all_dates[$k]);?>" style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;">T</td>
                                <?php } else { ?>
                                  <?php if ($user_shifts[0]['shift_shortcut'] != null) { ?>
                                    <?php if ($user_shifts[0]['shift_id'] == 0) { ?>
                                      <?php if ($h_status == true) { ?>
                                        <td class="date_<?php echo formatDate($all_dates[$k]);?>" style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;">H</td>
                                      <?php } else { ?>
                                        <td class="date_<?php echo formatDate($all_dates[$k]);?>" style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_shifts[0]['shift_shortcut']?></td>
                                      <?php } ?>
                                    <?php } else { ?>  
                                      <td class="date_<?php echo formatDate($all_dates[$k]);?>" style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_shifts[0]['shift_shortcut']?></td>
                                    <?php } ?>
                                  <?php } else { ?>
                                    <td class="date_<?php echo formatDate($all_dates[$k]);?>" style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;"><span style="display:none;">X</span></td>
                                  <?php } ?>
                                <?php } ?>
                              <?php } else { ?>
                                <td class="date_<?php echo formatDate($all_dates[$k]);?>" style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;"><span style="display:none;">X</span></td>
                              <?php } ?>
                            <?php } ?>
                          </tr>
                          <?php $designation_name=$user_details[0]['designation_name']?>  
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
    var dataarray= <?php echo json_encode($rota); ?>; 
    var job_role_ids= <?php echo json_encode($job_ids); ?>; 
    var month= <?php echo $month; ?>; 
    var unit_id= <?php echo $unit_id; ?>; 
    var year= <?php echo $year; ?>; 
    var unit_details= <?php echo json_encode($unit_details); ?>; 
</script> 
<script type="text/javascript">
  function exportTypSelectHtml(){
    var html = '';
    html =  '<select class="form-control" id="select_export_type">'+
              '<option value="1">Vertical</option>'+
              '<option value="2">Horizontal</option>'+
            '</select>'
    return html;
  }
  function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    w=window.open();
    w.document.write(printContents);
    w.print();
    w.close();
  }
  function exportF(elem) {
    var unit_name = unit_details[0]['unit_name'];
    var table = document.getElementById("print-content");
    var html = table.outerHTML; 
    var blob = new Blob([html], { type: "application/vnd.ms-excel" });
    window.URL = window.URL || window.webkitURL;
    link = window.URL.createObjectURL(blob);
    a = document.createElement("a");
    a.download = unit_name+'_('+GetMonthNameFromNumber(month)+'-'+year+').xls';
    a.href = link;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    location.reload();
  }
  /*function exportF(elem) {
    var unit_name = unit_details[0]['unit_name'];
    $('.des').remove();
    var st=0;
    var message = htmlFormatMessage(dataarray);
    swal({
      title: "",
      text: message,
      html: true,
      customClass: 'swal-wide',
      showCancelButton: true,
      closeOnCancel : true,
      closeOnConfirm: true
    }, function (isConfirm) {
      if(isConfirm){
        var rota_ids =[];
        var end_date_array = [];
        var dates_week = [];
        var unchecked_rota_ids = [];
        var unchecked_end_date = [];
        $("input:checked").each(function () {
          $('#reload').show();
          rota_ids.push($(this).val()); 
          end_date_array.push($(this).attr("data-date"));
          dates_week.push($(this).attr("data-week"));
        });
        $("input:checkbox:not(:checked)").each(function () {
          unchecked_end_date.push($(this).attr("data-sdate"));
        });
        for(var i=0;i<dataarray.length;i++){
          if(end_date_array.indexOf(dataarray[i].end_date) > -1){
            st=1;
            for(var x=0;x<unchecked_end_date.length;x++){
              var date = new Date(unchecked_end_date[x]);
              date.setDate(date.getDate() + 6);
              var date_string = moment(date).format('YYYY-MM-DD');
              findDatesBetnDates(unchecked_end_date[x],date_string);
            }
          }
        }
        if(st==1){
          var table = document.getElementById("print-content");
          var html = table.outerHTML; 
          var blob = new Blob([html], { type: "application/vnd.ms-excel" });
          window.URL = window.URL || window.webkitURL;
          link = window.URL.createObjectURL(blob);
          a = document.createElement("a");
          a.download = unit_name+'_('+GetMonthNameFromNumber(month)+'-'+year+').xls';
          a.href = link;
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          swal.close();
          location.reload();
        }
      }
      else{
        location.reload();
      }
    });
  }*/
  function reLoad(reload)
  {
    location.reload();
  }
  function findDatesBetnDates(startDate,endDate){
    var currDate = moment(startDate).startOf('day');
    var lastDate = moment(endDate).startOf('day');
    $('.date_'+startDate).remove();
    $('.date_'+endDate).remove();
    while(currDate.add(1, 'days').diff(lastDate) < 0) {
      var cur_date = currDate.clone().toDate();
      date = moment(cur_date).format('YYYY-MM-DD');
      $('.date_'+date).remove();
    }
  }
  function getMonthName(dt){
    var mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
    return mlist[dt.getMonth()];
  }
  function GetMonthNameFromNumber(monthNumber) {
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return months[monthNumber - 1];
  }
  function htmlFormatMessage(rota){
    var html = '';
    var count=1;
    var initial_array = [];
    html += '<html><head></head><body><h5>Please select the weeks to export</h5>'+
    '<table style="width:100%;border: 1px solid black;border-collapse: collapse;">'+
      '<tr>'+
        '<th style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">Week</th>'+
        '<th style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">From Date</th>'+
        '<th style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">To Date</th>'+
        '<th style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">Select Weeks</th>'+
      '</tr>';
      for(var i=0;i<rota.length;i++){
        if(initial_array.indexOf(rota[i].start_date) == -1)
        {
          html += '<tr>'+
          '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">'+count+'</td>'+
          '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">'+corectDateFormat(rota[i].start_date)+'</td>'+
          '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">'+corectDateFormat(rota[i].end_date)+'</td>'+
          '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left"><label><input type="checkbox" name="select_week" data-week="'+corectDateFormat(rota[i].start_date)+" to "+corectDateFormat(rota[i].end_date)+'" data-weeks="'+rota[i].start_date+" "+rota[i].end_date+'" data-date="'+rota[i].end_date+'" data-sdate="'+rota[i].start_date+'" value="'+rota[i].rota_id+'" class="select_week"><span class="check-box-effect select_weeks"></span></label></td>'+
          '</tr>';
        }
        count++;
        initial_array.push(rota[i].start_date);
      }
    html += '</table></body></html>';
    return html;
  }
   function corectDateFormat(date){
    var date_array = date.split("-");
    var date_with_slash = date_array[2]+"/"+date_array[1]+"/"+date_array[0];
    return date_with_slash;
  }
</script>