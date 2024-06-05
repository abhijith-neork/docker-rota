<style>
select {
  background-color: white;
  border: thin solid grey;
  border-radius: 4px;
  display: inline-block;
  font: inherit;
  line-height: 1.5em;
  padding: 0.5em 3.5em 0.5em 1em;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
}
select.eventcls {
  background-image:    url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAA3klEQVRIS+3VMU9CMRTF8d8zBL+aizoQFhx0kUk33RzdYMNFXUFnYeGrYYyaJiUxJHDLSxodbNKpfeffc9/pbaPyaCrr+3OA++z4rtT5Pg5GuMnCY9yWQEoBE1xhlUUP8YDrCBIB0vojLvGO0yz4hm4JJAKcYYoPHGOZAUdYoIMBXrc5iQAHeMlzviFygj7O8dkWEJU4XI8chALRhn9AVKHf70VRTHu4wFfbmKZLNKt50dLBnna0imcMd/2I0phWa3Y/D1e1Xa9BCZJG0VuQNpaWKMx72xS1Fl5/WN3BN+AgJhnZQlq4AAAAAElFTkSuQmCC') !important;
  background-position: calc(100% - .5rem), 100% 0 !important;
  background-size:  1.5em 1.5em !important;
  background-repeat: no-repeat !important;
}
#img-loader-data {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../../../../../../../assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}
.color-dot {
  background-color: #b19cd9;
  width: 8px;
  height: 8px;
  margin-left: 2px;
  border-radius: 50%;
  display: inline-block;
  margin-bottom: 6px;
  align-content: center;
}
select.eventcls:focus {
  border-color: blue;
  outline: 0;
}
html {
  position: fixed;
  height: 100%;
  overflow: hidden;
}

body {
  margin: 0;
  width: 100vw; 
  height: 100vh;
  overflow-y: auto;
  overflow-x: hidden;
  -webkit-overflow-scrolling: touch;
}
</style>
<div class="loader" id="img-loader-data"></div>
<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">

				<div class="row page-titles">
          <div class="col-md-12 col-4 align-self-center">
                      <?php if ($edit_permission == false): ?> 
                        <div style="background-color: #cb8b7ca6;color: black;font-weight: bold;" class="alert alert-danger text-center" role="alert">Warning! <?php echo $rota_edit_user?> is editing this week's rota. 
                        Rota can only be edited by one person at a time.
                        </div>
                      <?php endif; ?>
                    </div>
					<div class="col-md-6 col-8 align-self-center">
						<h3 class="text-themecolor mb-0 mt-0">Rota</h3>
						<ol class="breadcrumb">
							<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
							<li class="breadcrumb-item active">Edit Rota</li>
						</ol>
					</div>
				</div>
                     <?php
                    if ($this->session->flashdata('error') == 1)
                        $color = "red";
                    else
                        $color = "green";

                    if ($this->session->flashdata('message')) :
                        ?>
                    <p class="success-msg" id="success-alert"
                            style="color: <?php echo $color; ?>; text-align:center;">
                      <?php echo $this->session->flashdata('message');?>
                    </p> 
                    <?php endif;?> 
              <form action="#" id="frmRota"   name="frmRota">
               <div class="row" style="text-align: center">
					<div class="col-12">
          <input type="hidden" id="status" value="<?php echo $published;?>">
					<button type="button" onclick="location.href='<?php echo base_url('admin/Rota/viewrota/');?>';" class="btn btn-cancel float-left">Cancel</button>
          <?php if($rota_lock_status):?>
            <button <?php if ($edit_permission == false): ?> disabled <?php endif; ?> type="button" class="publish un-button btn-publish save_shift btn btn-primary float-left" style="margin-left: 4px;" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Publishing">Save</button>
          <?php endif; ?>
          <?php if($rota_lock_status):?>
   					<?php if($published == 0):?>
             <!--  <button type="button" class="publish un-button btn-publish btn btn-primary float-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Publishing">Save</button> -->
              <button <?php if ($edit_permission == false): ?> disabled <?php endif; ?> type="button" style="margin-left: 13px;" class="publish un-Publish btn-publish btn btn-primary float-right"  id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Publishing">Publish</button>
              <button <?php if ($edit_permission == false): ?> disabled <?php endif; ?> type="button" class="publish pu-button btn-publish btn btn-primary float-right" style="display: none;margin-left: 13px;" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Publishing">Save & Publish</button>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($rota_lock_status):?>
            <?php if($published == 1):?>
              <button <?php if ($edit_permission == false): ?> disabled <?php endif; ?> type="button" style="margin-left: 13px;" class="publish pu-button btn-publish btn btn-primary float-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Publishing">Save & Publish</button>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($payroll_user):?>
            <?php if($rota_lock_status):?>
              <button  type="button" data-status="1" class="btn btn-primary float-right lock-rota-status" style="margin-left: 4px;" id="lock_rota">Lock Rota</button>
            <?php else: ?>
              <button  type="button" data-status="0" class="btn btn-primary float-right lock-rota-status" style="margin-left: 4px;" id="unlock_rota">Unlock Rota</button>
            <?php endif; ?>
          <?php endif; ?>
          <input type="hidden" id="session_id" name="session_id" value="<?php echo $session_id ?>">
		  <input type="hidden" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
		  <input type="hidden" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
          <input type="hidden" value="<?php echo $day_shift_min?>" id="day_shift_min">
          <input type="hidden" value="<?php echo $day_shift_max?>" id="day_shift_max">
          <input type="hidden" value="<?php echo $night_shift_min?>" id="night_shift_min">
          <input type="hidden" value="<?php echo $night_shift_max?>" id="night_shift_max">
          <input type="hidden" value="<?php echo $num_patients?>" id="num_patients">
          <input type="hidden" value="<?php echo $designation?>" id="designation">
          <input type="hidden" value="<?php echo $rota_settings?>" id="rota_settings">
          <input type="hidden" value="<?php echo $nurse_day_count?>" id="nurse_day_count">
          <input type="hidden" value="<?php echo $nurse_night_count?>" id="nurse_night_count">
          <input type="hidden" value="<?php echo $rota_id?>" id="rota_id">
          <input type="hidden" value="<?php echo $unit_id?>" id="unit_id">
          
					</div>
				</div> 
				<div class="row" style="width: 100%;text-align: center;padding-top: 10px">
                <div class="col-12">
                  <?php if($day_shift_min && $day_shift_max):?>
                    <label style="font-weight:bold">Day : </label>
                    <label>Min : <?php echo $day_shift_min?></label>,
                    <label>Max : <?php echo $day_shift_max?></label>.&nbsp;
                  <?php endif; ?>
                  <?php if($night_shift_min && $night_shift_max):?>
                    <label style="font-weight:bold">Night : </label>
                    <label>Min : <?php echo $night_shift_min?></label>,
                    <label>Max : <?php echo $night_shift_max?></label>.&nbsp;
                  <?php endif; ?>
                  <?php if($num_patients):?>
                    <label style="font-weight:bold">Number Of Patients : </label>
                    <label><?php echo $num_patients?></label>.&nbsp;
                  <?php endif; ?>
                  <?php if($designation):?>
                    <label style="font-weight:bold">Number Of 1:1 Patients : </label>
                    <label><?php echo $designation?></label>.&nbsp;
                  <?php endif; ?>
                  <?php if($nurse_day_count):?>
                    <label style="font-weight:bold">Nurse Day Count : </label>
                    <label><?php echo $nurse_day_count?></label>.&nbsp;
                  <?php endif; ?>
                  <?php if($nurse_night_count):?>
                    <label style="font-weight:bold">Nurse Night Count : </label>
                    <label><?php echo $nurse_night_count?></label>.&nbsp;
                  <?php endif; ?>
                 <!--  <?php if($female_nurse_count):?>
                    <label style="font-weight:bold">Female Nurse Count : </label>
                    <label><?php echo $female_nurse_count?></label>.&nbsp;
                  <?php endif; ?>
                  <?php if($male_nurse_count):?>
                    <label style="font-weight:bold">Male Nurse Count : </label>
                    <label><?php echo $male_nurse_count?></label>.&nbsp;
                  <?php endif; ?> -->
                  <!-- <div class="staff_limit_message alert" role="alert"></div> -->
                </div>
              </div>
              <div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<div class="col-md-12">
									<div id="calendar"></div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<!--   <div class="row">
					<div class="col-12">
					<button class="btn btn-cancel">Cancel</button>
					<button class="btn btn-week">Copy to next week</button>
					<button class="publish  btn btn-publish">Publish</button>
					</div>
				</div> -->
			</form>
			</div>
		</div>
	</div> 
</div>
<script type="text/javascript">
  var unit='';
  var unitID = '';
  unit=<?php echo json_encode($unit[0]['unit_name']);?>;
  unitID = <?php echo json_encode($unit_id);?>;
  var rotabc='';
  rotabc=<?php print_r($posts); ?>;
  var weekEvents='';
  var shift_cats = '';
  weekEvents=<?php print_r($userShifts);?>;
  var staff_limit=<?php print $staff_limit;?>;
  shift_cats=<?php print_r($shift_cats);?>;
  var designaton_names=<?php print $designaton_names;?>;
  var shift_gap=<?php print_r($shift_hours);?>;
  var zero_targeted_hours_shifts=<?php print_r($zero_targeted_hours_shifts);?>;
  var holidayDates=<?php print_r($holidayDates);?>;
  var trainingDates=<?php print_r($trainingDates);?>;
  var user_offday=<?php print_r($user_offday);?>;
  var prev_and_next_shifts=<?php print_r($prev_and_next_shifts);?>;
  var all_previous_shifts=<?php print_r($all_previous_shifts);?>;
  var absent_shifts=<?php print $absent_shifts;?>;
  window.addEventListener('beforeunload', function (e) {
    $.ajax({
      url:"<?php echo $this->config->item('base_url'); ?>admin/Rota/deleteRotaLockUser",
      type: 'POST',
      success: function(response) {
        // Handle success (e.g., redirect or display a success message)
      },
      error: function(xhr, status, error) {
        // Handle error (e.g., display an error message)
      }
    });
  });
</script> 