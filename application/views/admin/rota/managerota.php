<style>
select {
  background-color: white;
  border: thin solid grey;
  border-radius: 4px;
  display: inline-block;
  font: inherit;
 /*  line-height: 1.5em; */
  padding: 0.5em 1.8em 0.5em .1em;
      margin-left: 3px;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
    text-align: center;
  text-align-last: center;
}
.RbtnMargin { margin-left: 5px; }
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
    background: url('../../assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}
select.eventcls:focus {
  border-color: blue;
  outline: 0;
}
option {
  text-align: left;
  /* reset to left*/
}
.totalhours{
font-size:14px;
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

.popper,
  .tooltip {
    position: absolute;
    z-index: 9999;
    background: #FFC107;
    color: black;
    width: 150px;
    border-radius: 3px;
    box-shadow: 0 0 2px rgba(0,0,0,0.5);
    padding: 10px;
    text-align: center;
  }
  .style5 .tooltip {
    background: #1E252B;
    color: #FFFFFF;
    max-width: 200px;
    width: auto;
    font-size: .8rem;
    padding: .5em 1em;
  }
  .popper .popper__arrow,
  .tooltip .tooltip-arrow {
    width: 0;
    height: 0;
    border-style: solid;
    position: absolute;
    margin: 5px;
  }

  .tooltip .tooltip-arrow,
  .popper .popper__arrow {
    border-color: #FFC107;
  }
  .style5 .tooltip .tooltip-arrow {
    border-color: #1E252B;
  }
  .popper[x-placement^="top"],
  .tooltip[x-placement^="top"] {
    margin-bottom: 5px;
  }
  .popper[x-placement^="top"] .popper__arrow,
  .tooltip[x-placement^="top"] .tooltip-arrow {
    border-width: 5px 5px 0 5px;
    border-left-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
    bottom: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
  }
  .popper[x-placement^="bottom"],
  .tooltip[x-placement^="bottom"] {
    margin-top: 5px;
  }
  .tooltip[x-placement^="bottom"] .tooltip-arrow,
  .popper[x-placement^="bottom"] .popper__arrow {
    border-width: 0 5px 5px 5px;
    border-left-color: transparent;
    border-right-color: transparent;
    border-top-color: transparent;
    top: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
  }
  .tooltip[x-placement^="right"],
  .popper[x-placement^="right"] {
    margin-left: 5px;
  }
  .popper[x-placement^="right"] .popper__arrow,
  .tooltip[x-placement^="right"] .tooltip-arrow {
    border-width: 5px 5px 5px 0;
    border-left-color: transparent;
    border-top-color: transparent;
    border-bottom-color: transparent;
    left: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
  }
  .popper[x-placement^="left"],
  .tooltip[x-placement^="left"] {
    margin-right: 5px;
  }
  .popper[x-placement^="left"] .popper__arrow,
  .tooltip[x-placement^="left"] .tooltip-arrow {
    border-width: 5px 0 5px 5px;
    border-top-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
    right: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
  }
  .dot {
    background-color: blue;
    width: 8px;
    height: 8px;
    margin-left: 2px;
    border-radius: 50%;
    display: inline-block;
    margin-bottom: 6px;
    align-content: center;
}

/*   padding: 0.5em 3.5em 0.5em 1em; .fc-timeline .fc-head .fc-cell-content .fc-cell-text {width: 100px !important;}
 */
</style>
<div class="loader" id="img-loader-data"></div>
<div class="spinnermodal" id="progressbar" style="display: none; z-index: 10001">
  <div style="position: fixed; z-index: 10001; top: 50%; left: 50%; height:65px">
      <img src="../../assets/images/loader.gif" alt="Loading..." />
    </div>
</div>
<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">

				<div class="row page-titles">
					<div class="col-md-6 col-8 align-self-center">
            <?php if ($action == "update") { ?>
						  <h3 class="text-themecolor mb-0 mt-0">Edit Rota</h3>
            <?php } else { ?>
              <h3 class="text-themecolor mb-0 mt-0">Manage Rota</h3>
            <?php } ?>
						<ol class="breadcrumb">
							<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
              <?php } elseif($this->session->userdata('user_type')>=3) {?>
                <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
              <?php }?>
              <?php if ($action == "update") { ?>
                <li class="breadcrumb-item active">Edit Rota</li>
              <?php } else { ?>
                <li class="breadcrumb-item active">Manage Rota</li>
              <?php } ?>
							
						</ol>
					</div>
					<div class="col-md-6 col-4 align-self-center"></div>
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
               <div class="row" style="text-align: center;">
					<div class="col-12">
          <?php if($new_status!=2){?>
          <button  onclick="location.href='<?php echo base_url('admin/managerota/');?>';" class="btn btn-cancel float-left">Cancel</button>
					<!-- <button class="copy copy_shift btn btn-week float-left RbtnMargin" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Copying">Copy to next week</button> --> 
        <?php } else { ?>
           <button  type="button" onclick="location.href='<?php echo base_url('admin/Rota/viewrota/');?>';" class="btn btn-cancel float-left">Cancel</button>
          <?php } ?>
          <?php if($rota_lock_status):?>
            <button class="save save_shift btn btn-primary float-left RbtnMargin" id="save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving">Save</button>
            <button type="button" style="margin-left: 13px;" class="publish btn-publish btn btn-primary float-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Publishing">Publish</button>
            <button type="button" style="display: none;margin-left: 13px;" class="save-and-publish btn-publish btn btn-primary float-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Publishing">Save & Publish</button>
          <?php endif; ?>
          <?php if($payroll_user && $action == 'update'):?>
            <?php if($rota_lock_status):?>
              <button  type="button" data-status="1" class="btn btn-primary float-right lock-rota-status" style="margin-left: 4px;" id="lock_rota">Lock Rota</button>
            <?php else: ?>
              <button  type="button" data-status="0" class="btn btn-primary float-right lock-rota-status" style="margin-left: 4px;" id="unlock_rota">Unlock Rota</button>
            <?php endif; ?>
          <?php endif; ?>
					<!--<button class="publish  btn btn-publish">Publish</button>-->
          <input type="hidden" id="session_id" name="session_id" value="<?php echo $session_id ?>">
					<input type="hidden" id="rota_id" name="rota_id" value="">
          <input type="hidden" value="" id="shift_published_status">
          <input type="hidden" value="" id="check_copied_shift">
          <input type="hidden" value="<?php echo $day_shift_min?>" id="day_shift_min">
          <input type="hidden" value="<?php echo $day_shift_max?>" id="day_shift_max">
          <input type="hidden" value="<?php echo $night_shift_min?>" id="night_shift_min">
          <input type="hidden" value="<?php echo $night_shift_max?>" id="night_shift_max">
          <input type="hidden" value="<?php echo $num_patients?>" id="num_patients">
          <input type="hidden" value="<?php echo $designation?>" id="designation">
          <input type="hidden" value="<?php echo $rota_settings?>" id="rota_settings">
          <input type="hidden" value="<?php echo $nurse_day_count?>" id="nurse_day_count">
          <input type="hidden" value="<?php echo $nurse_night_count?>" id="nurse_night_count">
          <input type="hidden" value="<?php echo $new_status?>" id="new_status">
          <input type="hidden" id="up_rota_id" name="up_rota_id" value="<?php echo $up_rota_id?>">
					</div>
          <div class="row" style="width: 100%;text-align: center;padding-top: 10px">
          <div class="col-12">

            <div class="shift_status_message alert" role="alert"></div>
          </div>
          </div>
          <div class="row" style="width: 100%;text-align: center;padding-top: 10px">
          <div class="col-12">

            <div class="rota_status_message alert" role="alert"></div>
          </div>
          </div>
          <div class="row" style="width: 100%;text-align: center;padding-top: 10px">
          <div class="col-12">

            <div class="rota_publish_message alert" role="alert"></div>
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
                <label style="font-weight:bold">Number of patients : </label>
                <label><?php echo $num_patients?></label>.&nbsp;
              <?php endif; ?>
              <?php if($designation):?>
                <label style="font-weight:bold">Number of 1:1 patients : </label>
                <label><?php echo $designation?></label>.&nbsp;
              <?php endif; ?>
              <?php if($nurse_day_count):?>
                <label style="font-weight:bold">Nurse day count : </label>
                <label><?php echo $nurse_day_count?></label>.&nbsp;
              <?php endif; ?>
              <?php if($nurse_night_count):?>
                <label style="font-weight:bold">Nurse night count : </label>
                <label><?php echo $nurse_night_count?></label>.&nbsp;
              <?php endif; ?> <br>
              <div style="padding-left:20px;">
              Below Minimum:<button style="height:15px;background-color:rgb(255, 0, 0);width: 20px;margin-right: 10px;" disabled></button>
              Minimum:<button style="height:15px;background-color:rgb(255, 191, 0);width: 20px;margin-right: 10px;" disabled></button>
              Above Minimum:<button style="height:15px;background-color:rgb(124, 252, 0);width: 20px;margin-right: 10px;" disabled></button>
              Maximum:<button style="height:15px;background-color:rgb(0, 100, 0);width: 20px;margin-right: 10px;" disabled></button>
              Above Maximum:<button style="height:15px;background-color:rgb(0, 0, 255);width: 20px;" disabled></button>
              </div>
            <!--   <?php if($female_nurse_count):?>
                <label style="font-weight:bold">Female nurse count : </label>
                <label><?php echo $female_nurse_count?></label>.&nbsp;
              <?php endif; ?>
              <?php if($male_nurse_count):?>
                <label style="font-weight:bold">Male nurse count : </label>
                <label><?php echo $male_nurse_count?></label>
              <?php endif; ?> -->
            <!-- <div class="staff_limit_message alert" role="alert"></div> -->
          </div>
        </div>
				</div> 
              <div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
							<div class="row">
								<div class="col-md-12">
                  
                  
									<div id="calendar"></div>
								</div></div>

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
  var rotabc='';
  rotabc=<?php print_r($posts); ?>;
  var unit='';
  var action = '';
  var new_scheduled_user_count = '';
  unit=<?php echo json_encode($unit[0]['unit_name']);?>;
  action='<?php echo $action;?>';
  var unitID='';
  unitID='<?php echo $unitID;?>';
  var weekEvents='';
  var shift_user_ids='';
  var holidayDates='';
  var trainingDates='';
  var shift_cats = '';
  var user_offday = '';
  var saved_rotas = '';
  var published_rotas = '';
  var updated_rota = '';
  var selected_date=''; 
  weekEvents=<?php print_r($userShifts);?>;
  shift_cats=<?php print_r($shift_cats);?>;
  user_offday=<?php print_r($user_offday);?>;
  shift_user_ids=<?php print_r($shift_user_ids);?>;
  new_scheduled_user_count=<?php print_r($new_scheduled_user_count);?>;
  holidayDates=<?php print_r($holidayDates);?>;
  trainingDates=<?php print_r($trainingDates);?>;
  saved_rotas=<?php print_r($saved_rotas);?>;
  published_rotas=<?php print_r($published_rotas);?>;
  updated_rota=<?php print_r($updated_rota);?>;
  selected_date=<?php print_r($selected_date);?>;
  var designaton_names=<?php print_r($designaton_names);?>;
  var shift_gap=<?php print_r($shift_hours);?>;
  var zero_targeted_hours_shifts=<?php print_r($zero_targeted_hours_shifts);?>;
  var userid_array_fordelete=<?php print_r($userid_array_fordelete);?>;
  var user_from_other_unit=<?php print_r($user_from_other_unit);?>;
  var absent_shifts=<?php print $absent_shifts;?>;
</script>