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
                  <?php if($export_type == 0){?>
                    <input type="button" class="btn hidden-sm-down btn-success" onclick="printDiv('print-content')" value="Print"/>
                  <?php }?>
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
                                        <?php foreach ($rota as $rt) { ?>
                                        <table id="<?php echo $rt['end_date']; ?>" style="border: 1px solid rgba(120, 130, 140, 0.13);width: 100%;margin-bottom: 1rem;color: #212529;border-collapse: collapse;font-family: Rubik, sans-serif;"  class="table table-bordered ">
                                            <thead>
                                                <?php $unit_name=getUnitold($rt['unit_id']);?>
                                                <h2 id="h2_<?php echo $rt['end_date']; ?>" style="text-align: center;font-family: Rubik, sans-serif;"><?php echo $unit_name[0]['unit_name']?> (<?php echo corectDateFormat($rt['start_date'])?> - <?php echo corectDateFormat($rt['end_date'])?>)</h2>
                                                <input type="hidden" id="excel_name" value="<?php echo $unit_name[0]['unit_name']?>_<?php echo $rt['end_date']?>"/>
                                                <tr style="background-color: #8080804d;font-weight: 500;padding: .75rem;box-sizing: border-box;margin: 15px;height: 40px;">
                                                    <?php $date_array=findDatesBetnDates($rt['start_date'],$rt['end_date']);?>
                                                    <th style="text-align: center;font-size: 14px;">Name</th>
                                                    <th style="text-align: center;font-size: 14px;">User ID</th>
                                                    <th style="text-align: center;font-size: 14px;">Job Role</th>
                                                    <?php for($i=0;$i<count($date_array);$i++) { ?>
                                                        <th style="text-align: center;font-size: 14px;"><?php echo removeYear($date_array[$i]) ?></th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
    <?php $user_array=getArrayUsers($rt['rota_id'],$job_ids,$rt['unit_id']);?>
    <?php $designation_name=''?>
    <?php for($j=0;$j<count($user_array);$j++) { ?>
      <?php 
            $params = array();
            $params['new_start_date'] = $rt['start_date'];
            $params['new_end_date'] = $rt['end_date'];
            $params['selected_unitid'] = $unit_id;
            $params['user_unitid'] = $user_array[$j]['unit_id'];
          ?>
        <?php $user_shifts=getShifts($rt['rota_id'],$user_array[$j]['user_id'],$params);?>
        <?php $user_hours = get_hours($user_shifts); ?>
        <?php $user_details=getUserDetails($user_array[$j]['user_id']);?>
        <?php if($designation_name != $user_details[0]['designation_name']){?>
            <tr class="des">
                <td colspan="1" style="font-family: Rubik, sans-serif;font-weight:bold;font-size: 14px"><strong><?php echo $user_details[0]['designation_name']?>
                </strong></td>
                <td></td>
                <td></td>
                <?php for($i=0;$i<count($date_array);$i++) { ?>
                    <td style="font-family: Rubik, sans-serif;font-weight:bold;font-size: 12px" colspan="1">    
                        <?php $shift_count=getELNShiftCount($user_details[0]['designation_id'],$date_array[$i],$user_array,$rt['rota_id']);?><b>
                        <span>E : <?php echo $shift_count['early_shift']?></span> , <span>L : <?php echo $shift_count['late_shift']?></span> , <span>N : <?php echo $shift_count['night_shift']?></span></b>
                    </td>
                <?php } ?>
            </tr>
        <?php }?>
        <tr>
            <td style="border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_details[0]['fname']." ".$user_details[0]['lname']." (".$user_details[0]['weekly_hours'].")".' '.$user_hours; ?></td>
            <td style="border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_details[0]['id']?></td>
            <td style="border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_details[0]['designation_code']?></td>
            <?php for($k=0;$k<count($user_shifts);$k++) { ?>
                <?php $t_status=checkUserTraining($user_array[$j]['user_id'],$user_shifts[$k]['date']);?>
                <?php $h_status=checkUserHoliday($user_array[$j]['user_id'],$user_shifts[$k]['date']);?>
                <?php if ($t_status == true) { ?>
                    <td style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;">T</td>
                <?php } else { ?>
                  <?php if ($user_shifts[$k]['shift_shortcut'] != null) { ?>
                      <?php if ($user_shifts[$k]['shift_id'] == 0) { ?>
                          <?php if ($h_status == true) { ?>
                              <td style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;">H</td>
                            <?php } else { ?>
                              <td style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_shifts[$k]['shift_shortcut']?></td>
                            <?php } ?>
                        <?php } else { ?>
                        <td style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;"><?php echo $user_shifts[$k]['shift_shortcut']?></td>
                        <?php } ?>
                    <?php } else { ?>
                        <td style="text-align: center;border: 1px solid #dee2e6;padding: .75rem;"></td>
                    <?php } ?>
                <?php } ?>
        <?php } ?>
        </tr>
        <?php $designation_name=$user_details[0]['designation_name']?>  
    <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php } ?>
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
    var export_type= <?php echo $export_type; ?>; 
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
    if(export_type){
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
          $("input:checked").each(function () {
            $('#reload').show();
            rota_ids.push($(this).val()); 
            end_date_array.push($(this).attr("data-date"));
            dates_week.push($(this).attr("data-week"));
          });
          for(var i=0;i<dataarray.length;i++){
            if(end_date_array.indexOf(dataarray[i].end_date) > -1){
              st=1;
            }else{ 
              $('#'+dataarray[i].end_date).remove();
              $('#h2_'+dataarray[i].end_date).remove();
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
          }else{
            location.reload();
          }
        }
        else{
          location.reload();
        }
      });
    }else{
      var html_message = exportTypSelectHtml();
      swal({
        title: "<h4>Select export type",
        text: html_message,
        html: true,
        customClass: 'swal-wide',
        confirmButtonText: 'Show',
        showCancelButton: true,
        closeOnCancel : true,
        closeOnConfirm: true
      }, function (isConfirm) {
        if(isConfirm){
          var export_type = $( "#select_export_type option:selected" ).val();
          if(job_role_ids && job_role_ids.length > 0){
            var job_string = job_role_ids.join();
            job_string = encodeURIComponent(job_string);
            var url = baseURL+'admin/rota/excelView/'+export_type+"/"+year+"/"+month+"/"+unit_id+"/"+job_string;
            window.open(url, '_blank');
          }else{
            var url=baseURL+'admin/rota/excelView/'+export_type+"/"+year+"/"+month+"/"+unit_id;
            window.open(url, '_blank');
          }
        }
      });
    }
  }
  function reLoad(reload)
  {
    location.reload();
  }
  function GetMonthNameFromNumber(monthNumber) {
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return months[monthNumber - 1];
  }
  function getMonthName(dt){
    var mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
    return mlist[dt.getMonth()];
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
            '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left"><label><input type="checkbox" name="select_week" data-week="'+corectDateFormat(rota[i].start_date)+" to "+corectDateFormat(rota[i].end_date)+'" data-date="'+rota[i].end_date+'" value="'+rota[i].rota_id+'" class="select_week"><span class="check-box-effect select_weeks"></span></label></td>'+
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