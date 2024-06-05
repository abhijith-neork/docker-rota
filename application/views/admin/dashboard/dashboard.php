<style>
.fc-title{
color:#000;
}
.fc-prev-button, .fc-next-button {

    background: #2196F3 !important;
    border: 1px solid #2196F3 !important;

} 
.fc-toolbar{
display:none;
}
</style>
<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">

				<div class="row page-titles">
					<div class="col-md-6 col-8 align-self-center">
						<h3 class="text-themecolor mb-0 mt-0">Dashboard</h3>
						<ol class="breadcrumb">
							<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                             <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                             <?php } elseif($this->session->userdata('user_type')>=3) {?>
                             <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                             <?php }?>
							<li class="breadcrumb-item active">Dashboard</li>
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
			</div> 

		<!-- 	<div  class="row" style="text-align: center;">
				<div class="col-12">&nbsp;</div>
			</div>-->
          
          	<form method="POST" action="<?php echo base_url();?>admin/Dashboard/index" id="frmDashboard"   name="frmDashboard">
          	<div class="row">
	          		<div class="col-md-3" style="padding-left: 31px">
	                    <div class="form-group"> 
	                                        <select class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select Unit">
	                                        <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
	                                        <option value=""><?php echo "------Select unit-------"?></option>
	                                        <?php }?>
	                                        <?php foreach($unit as $cl) { ?>
	                                            <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
	                                        <?php } ?>
	                                        </select>  
	                    </div>
	                </div>
					<div class="col-md-3">
						<div class="form-group" style="padding-left: 31px;">
	                                    <select class="SelectYear custom-select form-control required" id="year" name="year"> 
	                                       <option value="none"><?php echo "--Select year--"?></option>
	                                        
	                                        <?php $n=12; for($i=0;$i<$n;$i++){ $a=2019; $b=$a+$i; $c=$b;?> 
	                                       
	                                        <option  value="<?php echo $c; ?>" <?php if($syear==$c){?> selected="selected" <?php }?>><?php echo $c; ?></option><?php }?> 
	                                        
	                                    </select>
	                    </div>
                	</div>
	                <div class="col-md-3">
	                    <div class="form-group" style="padding-left: 31px;">
                                    <select class="SelectMonth custom-select form-control required" id="month" name="month">
                                       <option value="none"><?php echo "--Select month--"?></option>
                             
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
	                <div class="col-md-2">
						<div class="form-group" style="padding-right: 90px;width: 200px;">
	                     <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search">Search</button> 
	                                    
	                    </div>
	                      
	                </div>
			</div>
			<div style="padding-left:20px;">
			Below Minimum:<button style="height:15px;background-color:rgb(255, 0, 0);width: 20px;margin-right: 10px;" disabled></button>
			Minimum:<button style="height:15px;background-color:rgb(255, 191, 0);width: 20px;margin-right: 10px;" disabled></button>
			Above Minimum:<button style="height:15px;background-color:rgb(124, 252, 0);width: 20px;margin-right: 10px;" disabled></button>
			Maximum:<button style="height:15px;background-color:rgb(0, 100, 0);width: 20px;margin-right: 10px;" disabled></button>
			Above Maximum:<button style="height:15px;background-color:rgb(0, 0, 255);width: 20px;" disabled></button>
			</div>

			<script type="text/javascript">
 
			  var default_date=<?php echo json_encode($default_date);?>;
			  var week=<?php echo json_encode($week);?>;   
			  var resources=<?php print $resources;?>;
			  var weekEvents=<?php print $weekEvents;?>; 
			  var shift_data=<?php print $shift_data;?>;
			  var shift_cats=<?php print_r($shift_cats);?>;
			  var all_units=<?php print_r($all_units);?>;
			  var unit_name=<?php print_r($unit_name);?>;
			</script> 
			<!-- <div class="row" style="width: 100%;text-align: center;padding-top: 10px">
            <div class="col-12">
              <div class="staff_limit_message alert" role="alert">
              </div>
            </div>
          </div>
        
          <div class="row" style="width: 100%;text-align: center;padding-top: 10px">
          <div class="col-4">
          </div>
          <div class="col-4">
            <div style="display: none;" class="updationstatus alert alert-warning" role="alert">Updation Failed</div>
            <div style="display: none;" class="shiftallocate alert alert-warning" role="alert"></div>
          </div>
          <div class="col-4">
          </div>
          </div> -->
   <div class="row">
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
				</div> 
			<div class="row">
           
            <!-- <div class="col-md-6" style="padding-left: 4%;">
            Day Shift Minimum:&nbsp;<label>D_min</label>,&nbsp;&nbsp; Day Shift Maximum:&nbsp;<label>D_max</label><br>
            Night Shift Minimum:&nbsp;<label>N_min</label>,&nbsp;&nbsp; Night Shift Maximum:&nbsp;<label>N_max</label><br>
            Number of Patients:&nbsp;<label>NP</label>,&nbsp;&nbsp; Number of 1:1 Patients:&nbsp;<label>1 to 1</label><br>
            Nurse Day Count:&nbsp;<label>NDC</label>,&nbsp;&nbsp; Nurse Night Count:&nbsp;<label>NNC</label>
            </div> -->
          </div>
			</form>
		</div>
	</div>
</div>
