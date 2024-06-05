<!DOCTYPE HTML>
<html>
<body style="background-color: #f6f6f6;font-family: sans-serif;-webkit-font-smoothing: antialiased;font-size: 14px;line-height: 1.4;margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
<span style="color: transparent;display: none;height: 0;max-height: 0;max-width: 0;opacity: 0;overflow: hidden;mso-hide: all;visibility: hidden;width: 0;"></span>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background-color: #f6f6f6;">
<tr>
<td style="font-family: sans-serif;font-size: 14px;vertical-align: top;">&nbsp;</td>
<td style="font-family: sans-serif;font-size: 14px;vertical-align: top;display: block;max-width: 580px;padding: 10px;width: 580px;margin: 0 auto !important;">
<div style="box-sizing: border-box;display: block;margin: 0 auto;max-width: 580px;padding: 10px;">
<a href=""><img src="<?php echo base_url();?>assets/images/mailbanner.png" style="border: none;-ms-interpolation-mode: bicubic;max-width: 100%;"></a>

<table role="presentation" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;background: #ffffff;border-radius: 3px;">

<tr>
<td style="font-family: sans-serif;font-size: 14px;vertical-align: top;box-sizing: border-box;padding: 10px 40px;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
<tr>
<td style="font-family: sans-serif;font-size: 14px;vertical-align: top;padding:0px 30px;">
<p style="font-family: sans-serif;font-size: 14px;font-weight: normal;margin: 0;margin-bottom: 15px; text-align:center; padding-top:20px;"><strong>Dear <?php echo $staff_name?></strong>,<br>
<br>

        <?php echo $subject;?><br>
        <?php $count = count($data) ?>
        <?php $j = 1 ?>
        <?php 
          for($i=0;$i<$count;$i++)
        { ?>
          <?php $t_status = 0 ?>
          <?php $h_status = 0 ?>
<p style="text-align:center;font-weight:normal;font-family:sans-serif !important;">Week <?php echo weekOfMonth($data[$i]['rota']['start_date']) ?> </p>
<table  border="0" cellpadding="0" cellspacing="0"  style="margin: auto; width: 100%; font-weight: normal; font-family:sans-serif !important"><thead><tr><th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Shift Name</th>
<th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Date</th>
<th style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px">Notes</th>
</tr></thead>
<tbody>
  <?php $week_count = count($data[$i]['Week']) ?>
  <?php $shift_data = $data[$i]['Week'] ?>
  <?php $offdays = checkAllShift($shift_data) ?>
  <?php 
  for($k=0;$k<$week_count;$k++)
  { ?>
  <tr>
    <?php if($shift_data[$k]['shift_id'] != null):?>
      <?php $new_shift_id = findTrainigHolidayStatus($shift_data[$k]['date'],$shift_data[$k]['user_id'],$shift_data[$k]['shift_id']);?>
      <td style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px"><?php echo findshift($new_shift_id);?></td>
    <?php endif;?>
    <?php if($shift_data[$k]['shift_id'] == null):?>
      <?php $new_shift_id = findTrainigHolidayStatus($shift_data[$k]['date'],$shift_data[$k]['user_id'],$shift_data[$k]['shift_id']);?>
      <?php if ($new_shift_id) { ?>
        <td style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px"><?php echo findshift($new_shift_id);?></td>
      <?php } else { ?>
        <?php $result = findUnitShift($shift_data[$k]['user_id'],$shift_data[$k]['date']);?>
        <td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;"><?php echo $result['shift_name']; ?></td>
      <?php } ?>
    <?php endif;?>
    <td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;"><?php echo corectDateFormat($shift_data[$k]['date']); ?></td>
    <?php if($shift_data[$k]['shift_id'] == null):?>
      <td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;"><?php echo $result['message']; ?></td>
    <?php endif;?>
    <?php if($shift_data[$k]['shift_id'] != null):?>
      <?php $array = showNoteWithTrainingHoliday($shift_data[$k]['date'],$shift_data[$k]['shift_id'],$shift_data[$k]['user_id']);?>
      <?php if($array['training_status'] >= 1):?>
        <?php $t_status = $array['training_status'] ?>
      <?php endif;?>
      <?php if($array['holiday_status'] >= 1):?>
        <?php $h_status = $array['holiday_status'] ?>
      <?php endif;?>
      <td style="font-weight:normal !important;border:1px solid black;border-collapse: collapse;text-align:left;padding-left:5px;"><?php echo $array['message']; ?></td>
    <?php endif;?>
</tr>
<?php
  }
?>
<?php if($offdays == 7):?>
  <tr>
    <td style="font-weight: normal !important; border: 1px solid black; border-collapse: collapse; text-align: left; padding-left: 5px;text-align: center;" colspan="3">No shifts assigned for this week.
    </td>                            
  </tr>
<?php endif;?>
</tbody>
</table>
<?php if($t_status >= 1 || $h_status >= 1) :?>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;box-sizing: border-box;">
<tbody>
<tr>
<td style="font-family: sans-serif;font-size: 14px;vertical-align: top;padding: 15px;">
  <!-- <?php if($t_status >= 1):?>
                        <p>Your training dates are taken in this week schedule.</p>
                      <?php endif;?> -->

                      <?php if($h_status >= 1):?>
                        <p>Your holiday dates are taken in this week schedule.</p>
                      <?php endif;?>
</td>
</tr>
</tbody>
</table>
<?php endif;?>
        <?php
        }
        ?>


<div style="border-bottom:2px solid #366fa8;padding-top:30px;"></div>

<p style="font-family: sans-serif;font-size: 12px;font-weight: normal;margin: 0;margin-bottom: 15px; text-align: center;padding: 40px 20px 0px 20px">

</p>
<p style="font-family: sans-serif;font-size: 12px;font-weight: normal;margin: 0;margin-bottom: 15px; text-align: center;padding: 0px 20px 0px 20px">
Kind Regards,</p>
<p style="font-family: sans-serif;font-size: 12px;font-weight: normal;margin: 0;margin-bottom: 15px; text-align: center;padding: 0px 20px 0px 20px"><?php echo $supervisor_name ?></p>
<br>
<img style="margin:0px auto; position: relative;display: block; height:50px; padding-bottom: 30px;" src="<?php echo base_url();?>assets/images/logo-email1.png">
</p>
</td>
</tr>
</table>
</td>
</tr>

</table>

<div style="clear: both;margin-top: 10px;text-align: center;width: 100%;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 100%;">
<tr>

<td style="font-family: sans-serif;font-size: 10px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: #999999;padding: 10px 10px;text-align: center;">

<!--     <p style="font-size:10px; text-align: center; margin-top:-5px;">By clicking Set Password, you agree to our terms and conditions and privacy policy.<br>For more information, <a href="/" style="color:#366fa8;text-decoration: none;">click here</a>.</p> -->
<p style="color: #999999;font-size: 10px;padding: 10px 10px;text-align: center; padding:0px 50px">This E-mail and any files transmitted with it are private and intended solely for the use of the individual or entity to whom they are addressed. If you are not the intended recipient, the E-mail and any files have been transmitted to you in error and any copying, distribution or other use of the information contained in them is strictly prohibited. Nothing in this E-mail message amounts to a contractual or other legal commitment on the part of St Matthews Healthcare, or their agents, unless confirmed by a communication signed on behalf of St Matthews Healthcare.
</p></td>
</tr>
<tr>
<td style="font-family: sans-serif;font-size: 10px;vertical-align: top;padding-bottom: 10px;padding-top: 10px;color: #999999;padding: 10px 10px;text-align: center;">
<a href="<?php echo base_url();?>" style="color: #999999;text-decoration: none;font-size: 10px;padding: 10px 10px;text-align: center;">St Matthews Healthcare © <script>document.write(new Date().getFullYear())</script></a><br>

</td>
</tr>
</table>
</div>

</div>
</td>
<td style="font-family: sans-serif;font-size: 14px;vertical-align: top;">&nbsp;</td>
</tr>
</table>
</body>
</html>
