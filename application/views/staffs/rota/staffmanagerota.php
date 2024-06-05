<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" id="theme"  href="<?php echo base_url();?>assets/css/colors/blue.css">
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/css/custom_sweetalert.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/css/sweetalert.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/plugins/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/css/fullcalendar.css" rel='stylesheet' />
<link href="<?php echo base_url();?>assets/css/fullcalendar.print.css" rel='stylesheet' media='print' />
<link href="<?php echo base_url();?>assets/css/scheduler.min.css" rel='stylesheet' />
<link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="Stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/css/select2.min.css" rel="stylesheet">
<style>
  body{
        font-family: "Rubik", sans-serif;
            color: #54667a;
    font-weight: 300;
    cursor: pointer;
}
.page-wrapper {
     margin-left: 0px !important; 
}
.fc-prev-button, .fc-next-button {
    background: #2196F3 !important;
    border: 1px solid #2196F3 !important;
}
#calendar .fc-today-button {
    display: none;
}
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
.btn-success, .btn-success.disabled {
    background: rgb(85, 206, 99);
    border-width: 1px;
    border-style: solid;
    border-color: rgb(85, 206, 99);
    border-image: initial;
    float: right !important;
    border-radius: .25rem;
}
.text-themecolor {
    color: #009efb !important;
    line-height: 30px;
    font-size: 21px;
    font-weight: 400 !important;
}

h1, h2, h3, h4, h5, h6 {
    color: #2c2b2e;
    font-family: "Rubik", sans-serif;
    font-weight: 400;
}
.fc-toolbar h2 {
    font-size: 18px;
    font-weight: bold;
    line-height: 30px;
    text-transform: uppercase;
}
.btn-cancel {
    background: #E91E63;
    border: 1px solid #E91E63;
    color: #ffff;
    width: 200px;
}
.save_shift {
    background: #4682B4;
    border: 1px solid #4682B4;
    color: #ffff;
    width: 200px;
}

.btn-primary, .btn-primary.disabled {
    background: #4CAF50;
    border: 1px solid #4CAF50;
}
.btn {
    padding: 7px 12px;
    font-size: 14px;
    cursor: pointer;
        line-height: 1.5;
    border-radius: .25rem;
}
.disable-button{
  background: grey!important;
}
.alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeeba;
}

.alert {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
}
.fc th.fc-widget-header {
    color: #54667a;
    font-size: 13px;
    font-weight: 300;
    line-height: 20px;
    padding: 7px 0px;
    text-transform: uppercase;
}
.fc-head td.fc-divider
{
  width: 0px;
}
.btn-secondary, .btn-secondary.disabled {
    background: #868e96 !important;
    border: 1px solid #868e96;
    color: #ffffff !important;
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
  var baseURL = "<?php echo base_url();?>";
  var rotabc='';
  rotabc=<?php print_r($posts); ?>;
  var unit='';
  var unitID = '';
  var user_id = '';
  var user_offday = '';
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-migrate-1.0.0.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/popper.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/waves.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sidebarmenu.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/moment/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/footable/js/footable.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/fullcalendar/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/fullcalendar/moment.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/fullcalendar/fullcalendar.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/fullcalendar/scheduler.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/js/fullcalendar/sweetalert2.min.js"></script>  
<script type="text/javascript" src="<?php echo base_url();?>assets/js/staff_rota/availability.js"></script> 