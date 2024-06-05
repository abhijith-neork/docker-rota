<style>
.fc-title{
color:#000;
}
.fc-prev-button, .fc-next-button {

    background: #2196F3 !important;
    border: 1px solid #2196F3 !important;

}

 #img-loader-data{
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../../assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
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
            <h3 class="text-themecolor mb-0 mt-0">Edit Rota</h3>
            <ol class="breadcrumb">
              <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
              <li class="breadcrumb-item active">Edit Rota</li>
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

                    <div class="row" style="text-align: center;">                      
                      <div class="col-12">            

                          <?php if ($rota_status == 0) { ?>  
                            <?php if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==9) {?>
                                <button <?php editbuttonaccess('Admin.Editrota.Edit/Update Unpublished'); ?> id="edit" style="color: black;" class="btn btn-edit"  >Edit</button>
                                <button <?php editbuttonaccess('Admin.Editrota.Edit/Update Unpublished'); ?> id="update_rota"  class="btn btn-update" style="width: 200px;background: yellow !important;color: black;">Update Users</button>
                            <?php } else {?>
                                <button <?php editbuttonaccess('Admin.Editrota.Edit/Update Unpublished'); ?> id="edit"  class="btn btn-edit"  >Edit</button>
                                <button <?php editbuttonaccess('Admin.Editrota.Edit/Update Unpublished'); ?> id="update_users"  class="btn btn-success mr-2" style="width: 200px;">Update Users</button> 
                            <?php } ?>

                          <?php } else { ?> 
                            <?php if($this->session->userdata('user_type')==3 || $this->session->userdata('user_type')==9) {?>
                              <button id="edit" class="btn btn-edit" style="color: black;">Edit</button>
                              <button id="update_rota" class="btn btn-update" style="width: 200px;background: #fff3cd !important;color: black;">Update Users</button>
                            <?php } else {?>
                              <button id="edit" class="btn btn-edit"  >Edit</button>
                              <button id="update_users" class="btn btn-success mr-2" style="width: 200px;">Update Users</button>
                            <?php } ?>

                          <?php } ?>  
                          
                      </div>       
                    </div> 
         <div class="row" style="text-align: center;"><div class="col-12">&nbsp;</div></div>
              <form method="POST" action="<?php echo base_url();?>admin/Rota/viewrota" id="frmViewRota"   name="frmViewRota">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group" style="padding-top: 30px;padding-left: 42px;">
                                              <select class="SelectUnit custom-select form-control required" id="unit" name="unit"> 
                                                  <option value="">Select unit</option>
                                                  <?php
                                                      foreach ($units as $uni) { 
                                                  ?>  

                                                  <option <?php    if($this->input->post("unit_id")==$uni['id']) { ?> selected="selected" <?php } ?> value="<?php echo $uni['id']; ?>"<?php if(($unit_select)==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['unit_name']; ?></option> 
                                                  <?php 
                                                        } 
                                                  ?>
                                              </select>
                                              <input type="hidden" id="start" name="start" value="">
                                              <input type="hidden" id="end" name="end" value="">
                                              <input type="hidden" id="session_id" name="session_id" value="<?php echo $session_id ?>">
                                              <input type="hidden" id="date_status" name="date_status" value="0">
                                              <input type="hidden" id="start_new" name="start_new" value="">
                                              <input type="hidden" id="end_new" name="end_new" value="">
                                              <input type="hidden" id="user_type" name="user_type" value="<?php echo $this->session->userdata('user_type');?>">
                                              <?php 
                                              if($this->input->post("rota_id")){
                                                  $rId = $this->input->post("rota_id");
                                                  // print "here1";
                                              }
                                              else{
                                                  $rId = $rotaId;
                                                                                          // print "here";

                                              }
                                             ?>
                                              <input type="hidden" id="rota_id" name="rota_id" value="<?php echo $rId; ?>">
                                              <input type="hidden" id="unit_id" name="unit_id" value="<?php echo $this->input->post("unit_id"); ?>">
                                              
                    </div>
                                
                  </div>
                  <div class="col-md-4">
                    <div class="row" style="padding-top: 30px;">
                        <div class="col-md-2">
                                      <div class="form-group">
                                      <label for="wfirstName2"> <b> Date: </b></label>                
                                      </div>
                        </div> 
                        <div class="col-md-6">
                                      <div class="form-group" id="other-selector"> 
                                            <input class="form-control" autocomplete="off" type="text" placeholder="dd/mm/yy"  id="from-datepicker"  name="end_date" value="<?php echo $this->input->post('end_date'); ?>" >
                                      </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-4">
                            <div  style="padding-right: 90px;padding-top: 30px;width: 200px;">
                                 <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 180px;">Search</button>
                            </div>
                      </div>
                    </div>
                  </div>
        </div>

        <script type="text/javascript">
          function findDatesBetnDates(startDate,endDate){
            var dates = [];
            dates.push(startDate);
            var currDate = moment(startDate).startOf('day');
            var lastDate = moment(endDate).startOf('day');
            while(currDate.add(1, 'days').diff(lastDate) < 0) {
              var cur_date = currDate.clone().toDate();
              date = moment(cur_date).format('YYYY-MM-DD');
              dates.push(date);
            }
            dates.push(endDate)
            return dates;
          }
          function showHolidayAndTrainings(){
            var all_holiday_dates=[];
            var all_training_dates=[];
            if(holidayDates && holidayDates.length >0){
              $.each(holidayDates, function( index, value ) {
                var dates = findDatesBetnDates(value.from_date,value.to_date);
                for(var i=0;i<dates.length;i++) {
                  $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_user');
                  $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_shift');
                  $('.shift_'+value.user_id+'_'+dates[i]).html('H');
                }            
              });
            }
            if(trainingDates && trainingDates.length >0){
              $.each(trainingDates, function( index, value ) {
                var dates = findDatesBetnDates(value.date_from,value.date_to);
                for(var i=0;i<dates.length;i++) {
                  $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_user');
                  $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_shift');
                  $('.shift_'+value.user_id+'_'+dates[i]).html('T');
                }            
              });
            }
          }
  var unit=<?php echo json_encode($unit_name);?>;
  var resources=<?php print $resources;?>;
  var weekEvents=<?php print $weekEvents;?>;
  var staff_limit=<?php print $staff_limit;?>;
  var designaton_names = <?php print_r($designaton_names);?>;
  var holidayDates=<?php print_r($holidayDates);?>;
  var trainingDates=<?php print_r($trainingDates);?>;
  var user_offday=<?php print_r($user_offday);?>;
  var start_date='';
  start_date=<?php print_r($start_date);?>;
  var absent_shifts=<?php print $absent_shifts;?>;
</script> 
                <div class="row" style="width: 100%;text-align: center;padding-top: 10px">
                <div class="col-12">

                  <div class="staff_limit_message alert" role="alert"></div>
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
