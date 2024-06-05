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
#img-loader-data {
    position: fixed;
    display: none;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}
.fc-head td.fc-divider
{
  width: 0px;
}
.date-selector-text{
  font-weight: bold;
}
select.eventcls {
  background-image:    url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAA3klEQVRIS+3VMU9CMRTF8d8zBL+aizoQFhx0kUk33RzdYMNFXUFnYeGrYYyaJiUxJHDLSxodbNKpfeffc9/pbaPyaCrr+3OA++z4rtT5Pg5GuMnCY9yWQEoBE1xhlUUP8YDrCBIB0vojLvGO0yz4hm4JJAKcYYoPHGOZAUdYoIMBXrc5iQAHeMlzviFygj7O8dkWEJU4XI8chALRhn9AVKHf70VRTHu4wFfbmKZLNKt50dLBnna0imcMd/2I0phWa3Y/D1e1Xa9BCZJG0VuQNpaWKMx72xS1Fl5/WN3BN+AgJhnZQlq4AAAAAElFTkSuQmCC') !important;
  background-position: calc(100% - .5rem), 100% 0 !important;
  background-size:  1.5em 1.5em !important;
  background-repeat: no-repeat !important;
}
select.eventcls:focus {
  border-color: blue;
  outline: 0;
}
.disable-button{
  background: grey!important;
}
</style>
<div class="loader" id="img-loader-data"></div>
<div class="page-wrapper">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">

        <div class="row page-titles">
          <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor mb-0 mt-0">Set Your Availability</h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a
                href="<?php echo base_url();?>staffs/profile">Home</a></li>
              <li class="breadcrumb-item active">Set Your Availability</li>
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
          <button  type="button" onclick="location.href='<?php echo base_url('staffs/profile/');?>';" class="btn btn-cancel">Cancel</button>
          <button class="save save_shift btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving">Save</button>
          <!-- <button class="mark  btn btn-mark">Mark As Available</button> -->
          <input type="hidden" id="rota_id" name="rota_id" value="">
          
          </div></div>
        </div>
        <div class="row">
          <div class="col-4"></div>
          <div class="col-4">
            <?php if($user_availability_request):?>
              <label style="font-weight:bold">Requests Allowed : <?php echo $user_availability_request?></label>
            <?php endif; ?>
          </div>
          <div class="col-4"></div>
        </div>
        <div class="row" style="width: 100%;text-align: center;padding-top: 10px">
          <div class="col-3"></div>
            <div class="col-6">
              <div style="display:none;" class="warning_message alert alert alert-warning" role="alert">You cannot set request for this week, you can set availability only.</div>
            <div class="col-3"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="row" style="padding-top: 30px;">
            <div class="col-md-2">
              <div class="form-group">
              <label class='date-selector-text' for="wfirstName2"> <b> Date: </b></label>                
              </div>
            </div> 
            <div class="col-md-6">
              <div class="form-group" id="other-selector"> 
              <input class="form-control" autocomplete="off" type="text" placeholder="dd/mm/yy"  id="from-datepicker"  name="end_date" value="<?php echo $this->input->post('end_date'); ?>" >
              </div>
            </div>
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
       
      </form>
      </div>
    </div>
  </div> 
</div>
<script type="text/javascript">
  var rotabc='';
  rotabc=<?php print_r($posts); ?>;
  var unit='';
  var unitID = '';
  var user_id = '';
  var user_availability_request = '';
  var user_default_shift_id = '';
  user_default_shift_id=<?php echo $user_default_shift_id;?>;
  unitID=<?php echo $unitID;?>;
  user_id=<?php echo $user_id;?>;
  user_offday=<?php print_r($offday);?>;
  unit=<?php echo json_encode($unit[0]['unit_name']);?>;
  user_availability_request=<?php echo $user_availability_request;?>;
  var weekEvents='';
  var shift_array = '';
  var holidayDates = '';
  var trainingDates = '';
  var available_shift = '';
  var useravl_defaultshift = '';
  var user_default_shift = '';
  weekEvents=<?php print_r($userShifts);?>;
  all_shift_array=<?php print_r($shift_array);?>;
  holidayDates=<?php print_r($holidayDates);?>;
  trainingDates=<?php print_r($trainingDates);?>;
  // available_shift=<?php //print_r($available_shift);?>;
  available_shift=<?php print_r($useravl_defaultshift);?>;
  user_default_shift=<?php print_r($user_default_shift);?>;
  var shift_gap=<?php print_r($shift_hours);?>;
  var zero_targeted_hours_shifts=<?php print_r($zero_targeted_hours_shifts);?>;
</script>