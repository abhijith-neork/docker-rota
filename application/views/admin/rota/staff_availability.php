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
</style>

<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Reports</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Employee Availability Report</li>
                        </ol>
                    </div> 
                    <div class="col-md-6 col-4 align-self-center">
                        
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
                    <!-- <div class="row" style="text-align: center;">
					<div class="col-12">
					<button  onclick="location.href='<?php echo base_url('admin/managerota/');?>';" class="btn btn-cancel">Cancel</button>  
					<button id="edit" class="btn btn-edit"  >Edit</button>
					<button id="update_users" class="btn btn-success mr-2"  >Update Users</button>
					</div> -->
				</div> 
				 <div class="row" style="text-align: center;"><div class="col-12">&nbsp;</div></div>
          <form method="POST" action="<?php echo base_url();?>admin/Rota/staffs" id="frmStaffRota"   name="frmStaffRota">
            <div class="row">
					<div class="col-md-3">
					<div class="form-group" style="padding-left: 31px;">
                        <label for="Location">Unit<span style="color: red;">*</span></label> 
                                    <select class="SelectUnit custom-select form-control required" id="unit" name="unit"> 
                                        <option value="1">--Select unit--</option>
                                        <?php
                                            foreach ($units as $uni) { 
                                        ?>  

                                        <option <?php    if($this->input->post("unit")==$uni['id']) { ?> selected="selected" <?php } ?> value="<?php echo $uni['id']; ?>" ><?php echo $uni['unit_name']; ?></option> 
                                        <?php 
                                              } 
                                        ?>
                                    </select> 
                                    
                    </div>   
                </div>
                <div class="col-md-3" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Job role</label> 
                                        <select  class="form-control custom-select required jobrole" id="jobrole" name="jobrole" placeholder="Select Job Role">
                                        <option value=""><?php echo "------Select job role-------"?></option>
                                        <?php foreach($jobrole as $cl) { ?>
                                            <option <?php    if($this->input->post("jobrole")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['designation_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div>
                <div class="col-md-2">
					<div class="form-group" style="padding-left: 31px;">
                                  <label for="Location">Year<span style="color: red;">*</span></label> 
                                    <select class="SelectYear custom-select form-control required" id="year" name="year"> 
                                       <option value="none"><?php echo "--Select year--"?></option>
                                        
                                        <?php $n=12; for($i=0;$i<$n;$i++){ $a=2019; $b=$a+$i; $c=$b;?> 
                                       
                                        <option  value="<?php echo $c; ?>" <?php if($start_year==$c){?> selected="selected" <?php }?>><?php echo $c; ?></option><?php }?> 
                                        
                                    </select>
                                    
                                    
                    </div>
                      
                </div>
                <div class="col-md-2">
					<div class="form-group" style="padding-left: 31px;">
                        <label for="Location">Month<span style="color: red;">*</span></label> 
                                    <select class="SelectMonth custom-select form-control required" id="month" name="month">
                                       <option value="none"><?php echo "--Select month--"?></option>
                             
                                        <option value="01"  <?php if($month=="01"){?>  selected="selected" <?php }?> >January</option>
                                        <option value="02"  <?php if($month=="02"){?>  selected="selected" <?php }?> >February</option>
                                        <option value="03"  <?php if($month=="03"){?>  selected="selected" <?php }?> >March</option>
                                        <option value="04"  <?php if($month=="04"){?>  selected="selected" <?php }?> >April</option>
                                        <option value="05"  <?php if($month=="05"){?>  selected="selected" <?php }?> >May</option>
                                        <option value="06"  <?php if($month=="06"){?>  selected="selected" <?php }?> >June</option>
                                        <option value="07"  <?php if($month=="07"){?>  selected="selected" <?php }?> >July</option>
                                        <option value="08"  <?php if($month=="08"){?>  selected="selected" <?php }?> >August</option>
                                        <option value="09"  <?php if($month=="09"){?>  selected="selected" <?php }?> >September</option>
                                        <option value="10"  <?php if($month=="10"){?> selected="selected" <?php }?> >October</option>
                                        <option value="11"  <?php if($month=="11"){?> selected="selected" <?php }?> >November</option>
                                        <option value="12"  <?php if($month=="12"){?> selected="selected" <?php }?> >December</option>
 
                                    
                                    </select> 
                                    
                    </div>
                      
                </div>
                <div class="col-md-2">
                     <div class="form-group" style="padding-right: 90px;width: 200px;padding-top: 30px;"> 
                     <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search">Search</button> 
                                    
                    </div>
                      
                </div>
			</div>

	 <script type="text/javascript">
 
      var default_date=<?php echo json_encode($default_date);?>;
      var week=<?php echo json_encode($week);?>;   
      var resources=<?php print $resources;?>;
      var weekEvents=<?php print $weekEvents;?>;
      var staff_limit=<?php print $staff_limit;?>;

    </script> 
            <div class="row" style="width: 100%;text-align: center;padding-top: 5px">
                <div class="col-12">
                    <div class="staff_limit_message alert" role="alert">
                    </div>
                </div>
            </div>
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

			</form>
			</div>
		</div>
	</div> 
</div>
