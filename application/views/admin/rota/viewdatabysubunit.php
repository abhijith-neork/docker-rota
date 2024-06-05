<style>
.fc-title{
color:#000;
}
.fc-prev-button, .fc-next-button {

    background: #2196F3 !important;
    border: 1px solid #2196F3 !important;

}
.fc-toolbar
{
  padding: 0px;
}
  /*
  i wish this required CSS was better documented :(
  https://github.com/FezVrasta/popper.js/issues/674
  derived from this CSS on this page: https://popper.js.org/tooltip-examples.html
  */

 .fc-head .fc-time-area .fc-scrollpane
 {
    height: 184px !important;
    overflow: auto !important;
    margin: 0px !important;
 }
 /*.fc-head .fc-time-area .fc-scrollpane .fc-no-scrollbars
 {
    height: 184px !important;
    overflow: auto !important;
    margin: 0px !important;
 }*/
 /*.fc-head .fc-time-area .fc-scrollpane .fc-no-scrollbars
 {
  overflow: visible !important;
 }*/
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
</style>
<style>

  /*
  i wish this required CSS was better documented :(
  https://github.com/FezVrasta/popper.js/issues/674
  derived from this CSS on this page: https://popper.js.org/tooltip-examples.html
  */

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


  .dot0 {
 
  width: 8px;
  height: 8px;
  margin-right: 2px;
  border-radius: 50%;
  display: inline-block;
}
.fc-timeline-event .fc-content {
 
    padding-top: 10px !important;
} 
.fc-event {
 height:25px !important;
}
</style>

<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<div class="row page-titles">
					<div class="col-md-6 col-8 align-self-center">
						<h3 class="text-themecolor mb-0 mt-0">View Rota</h3>
						<ol class="breadcrumb">
							<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
							<li class="breadcrumb-item active">View Rota</li> 
              <li style="padding-left: 100px;">Not Published:<button style="height:15px;background-color:#add8e6;" disabled></button></li>
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
                    <!-- <div class="row" style="text-align: center;">
					<div class="col-12">
					<button  onclick="location.href='<?php echo base_url('admin/managerota/');?>';" class="btn btn-cancel">Cancel</button>  
					<button id="edit" class="btn btn-edit"  >Edit</button>
					<button id="update_users" class="btn btn-success mr-2"  >Update Users</button>
					</div> -->
				</div> 
				 <div class="row" style="text-align: center;"><div class="col-12">&nbsp;</div></div>
          <form method="POST" action="<?php echo base_url();?>admin/Rota/editrota" id="frmViewRota"   name="frmViewRota">
          <div class="row">
					<div class="col-md-4">
					<div class="form-group" style="padding-left: 31px;">
                                    <select class="SelectUnit custom-select form-control required" id="unit" required="required" name="unit"> 
                                        <option value="">--Select unit--</option>
                                        <?php
                                            foreach ($units as $uni) { 
                                                $parent=0;
                                                $haveSubunits=findBranches($uni['id']);
                                                
                                                if(count($haveSubunits) >0 ){
                                                    $parent=1;
                                                
                                                }
                                        ?>  
                                        <option <?php    if($this->input->post("unit")==$uni['id']) { ?> selected="selected" <?php } ?> data-parent=<?php echo $parent;?> value="<?php echo $uni['id']; ?>" ><?php echo $uni['unit_name']; ?></option> 
                                        <?php 
                                              } 
                                        ?>
                                    </select> 
                                    
                    </div>   
                </div>
                <div class="col-md-2">
                   <div class="form-group" style="padding-left: 31px;">
                      <select class="select2 mb-2 select2-multiple user_role" id="job_role" name="jobrole[]" style="width: 100%" multiple="multiple" data-placeholder="--Select jobrole group--">
                          <optgroup label="--Select role--" class="roles" id="roles" name="role[]">
                              <?php
                                  foreach ($job_role_group as $job_role) {
                              ?>   
                              <option data-id="<?php echo $job_role['id']; ?>" value="<?php echo $job_role['id']; ?>" ><?php echo $job_role['group_name']; ?></option>  
                              <?php } ?>  
                          </optgroup>
                       
                      
                      </select>

                     
                    </div>
                </div>
                <div class="col-md-2">
					           <div class="form-group" style="padding-left: 31px;">
                                    <select class="SelectYear custom-select form-control required" id="year" name="year"> 
                                        
                                        <?php $n=12; for($i=0;$i<$n;$i++){ $a=2019; $b=$a+$i; $c=$b;?> 
                                       
                                        <option  value="<?php echo $c; ?>" <?php if($start_year==$c){?> selected="selected" <?php }?>><?php echo $c; ?></option><?php }?> 
                                        
                                    </select>
                                    
                                    
                    </div>
                      
                </div>
                <div class="col-md-2">
					           <div class="form-group" style="padding-left: 31px;">
                                    <select class="SelectMonth custom-select form-control required" id="month" name="month">
                             
                                      <!--   <option value="01"  <?php if($this->input->post("month")=="01"){?>  selected="selected" <?php }?> >January</option>
                                        <option value="02"  <?php if($this->input->post("month")=="02"){?>  selected="selected" <?php }?> >February</option>
                                        <option value="03"  <?php if($this->input->post("month")=="03"){?>  selected="selected" <?php }?> >March</option>
                                        <option value="04"  <?php if($this->input->post("month")=="04"){?>  selected="selected" <?php }?> >April</option>
                                        <option value="05"  <?php if($this->input->post("month")=="05"){?>  selected="selected" <?php }?> >May</option>
                                        <option value="06"  <?php if($this->input->post("month")=="06"){?>  selected="selected" <?php }?> >June</option>
                                        <option value="07"  <?php if($this->input->post("month")=="07"){?>  selected="selected" <?php }?> >July</option>
                                        <option value="08"  <?php if($this->input->post("month")=="08"){?>  selected="selected" <?php }?> >August</option>
                                        <option value="09"  <?php if($this->input->post("month")=="09"){?>  selected="selected" <?php }?> >September</option>
                                        <option value="10"  <?php if($this->input->post("month")=="10"){?> selected="selected" <?php }?> >October</option>
                                        <option value="11"  <?php if($this->input->post("month")=="11"){?> selected="selected" <?php }?> >November</option>
                                        <option value="12"  <?php if($this->input->post("month")=="12"){?> selected="selected" <?php }?> >December</option> -->

                                        <option value="01"  <?php if($smonth=="01"){?>  selected="selected" <?php }?> >January</option>
                                        <option value="02"  <?php if($smonth=="02"){?>  selected="selected" <?php }?> >February</option>
                                        <option value="03"  <?php if($smonth=="03"){?>  selected="selected" <?php }?> >March</option>
                                        <option value="04"  <?php if($smonth=="04"){?>  selected="selected" <?php }?> >April</option>
                                        <option value="05"  <?php if($smonth=="05"){?>  selected="selected" <?php }?> >May</option>
                                        <option value="06"  <?php if($smonth=="06"){?>  selected="selected" <?php }?> >June</option>
                                        <option value="07"  <?php if($smonth=="07"){?>  selected="selected" <?php }?> >July</option>
                                        <option value="08"  <?php if($smonth=="08"){?>  selected="selected" <?php }?> >August</option>
                                        <option value="09"  <?php if($smonth=="09"){?>  selected="selected" <?php }?> >September</option>
                                        <option value="10"  <?php if($smonth=="10"){?> selected="selected" <?php }?> >October</option>
                                        <option value="11"  <?php if($smonth=="11"){?> selected="selected" <?php }?> >November</option>
                                        <option value="12"  <?php if($smonth=="12"){?> selected="selected" <?php }?> >December</option>
 
 
                                    
                                    </select> 
                                    
                    </div>
                      
                </div>
                <div class="col-md-1">
					<div class="form-group" style="padding-right: 90px;width: 200px;">
                     <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search">Search</button> 
                                    
                    </div>
                      
                </div>
                <div class="col-md-1">
          <div class="form-group" style="padding-right: 90px;width: 200px;">
            <?php 
              $job_role_ids = $this->input->post("jobrole");
              $url_array = '';
              if($job_role_ids && count($job_role_ids) > 0){
                $url_array = http_build_query($job_role_ids);
              }
              if ($url_array == ""){
                $url = base_url("admin/rota/printReport/").$start_year."/".$smonth."/".$this->input->post("unit");
              }else{
                $url = base_url("admin/rota/printReport/").$start_year."/".$smonth."/".$this->input->post("unit")."/".$url_array;
              }
            ?>
                      <a href="<?php echo $url?>" id="show-print-button" style="display:none;" class="search btn hidden-sm-down btn-success" target="_blank">Print peview</a>               
                    </div>
                      
                </div>
				</div>

				<script type="text/javascript">
 
  

  var default_date=<?php echo json_encode($default_date);?>;
  var week=<?php echo json_encode($week);?>;   
  var resources=<?php print $resources;?>;
  var weekEvents=<?php print $weekEvents;?>;
  var staff_limit=<?php print $staff_limit;?>;
  var absent_shifts=<?php print $absent_shifts;?>;
  var agency_staffs=<?php print $agency_staffs;?>;
  var rota_dates=<?php print $rota_dates;?>;
  var jobe_roles=<?php print $jobe_roles;?>;
  var designaton_names=<?php print $designaton_names;?>;
  var holidayDates=<?php print_r($holidayDates);?>;
  var trainingDates=<?php print_r($trainingDates);?>;
  var UserOtherUnits=<?php print_r($UserOtherUnits);?>;
  var training_shift_details=<?php print_r($training_shift_details);?>;
  var shift_cats=<?php print_r($shift_cats);?>;
  var user_offday=<?php print_r($user_offday);?>;
  var unit_ids=<?php print_r($unit_ids);?>;
  var unitID='<?php print_r($unit_id);?>';
  var login_user_designation=<?php echo $login_user_designation;?>;
  var zero_targeted_hours_shifts=<?php print_r($zero_targeted_hours_shifts);?>;
</script> 

          <div class="row" style="width: 100%;text-align: center;padding-top: 5px">
           <div class="col-4">
           </div>
          <div class="col-4">
            <div style="display: none;" class="updationstatus alert alert-warning" role="alert">Updation Failed</div>
            <div style="display: none;" class="shiftallocate alert alert-warning" role="alert"></div>
          </div>
          <div class="col-4">
          </div>
          </div>
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<div class="col-md-12">
									<div id="calendar">    
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
