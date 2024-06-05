<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body"> 
             <div class="row page-titles">
                                                        <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Edit Shift</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/manageshift/">Manage Shift</a></li>
                                                                <li class="breadcrumb-item active">Edit Shift</li>
                                                            </ol>
                                                        </div>  
            </div>
           
            <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>    
            <div class="row">
                    <div class="col-12">
                        <div class="card">
                        	<div class="card-body">
								<form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('admin/Shift/');?>editshift" style="width: 50%;">
									<div class="form-group">
										<label for="shift_name">Shift name</label> <input type="text"
											class="form-control shift_name" id="shift_name" name="shift_name"
											placeholder="Enter shift name" required="required" value="<?php echo $shift[0]['shift_name'];?>">
									</div>
									<div class="form-group">
										<label for="shift_shortname">Shift short name</label>
										<input type="text" class="form-control shift_shortname" name="shift_shortname" id="shift_shortname" placeholder="Enter shift shortname" required="required" value="<?php echo $shift[0]['shift_shortcut'];?>">
								 
									</div>  
									<div class="row">
                                         <div class="col-md-4"> 
                                                <div class="form-group">
                                                    <label for="wstart_time2">Start time</label>  
                                                    <p><h7>Hours:</h7></p>
                                                    <select class="custom-select form-control required start_time_hour" id="wstart_time2" name="wstart_time2">
                                                        <?php $i='';$n=24;
                                                        for($i=1;$i<=$n;$i++) { if($i<10) {?>
                                                         <option value="<?php echo '0'.$i;?>" <?php if(($a[0])=="$i"){?>  selected="selected" <?php }?> ><?php echo $i;?></option><?php } else {?>
                                                         <option value="<?php echo $i;?>" <?php if(($a[0])=="$i"){?>  selected="selected" <?php }?> ><?php echo $i;?></option> <?php } ?>
                                                        <?php } ?>
                                                     </select>
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                <div class="form-group"> 
                                                <label for="wstart_time12"><br></label>   
                                                    <p><h7>Minutes:</h7></p>
                                                    <select class="custom-select form-control required start_time_min" id="wstart_time12" name="wstart_time12">
                                                        <option value="00" <?php if(($a[1])=="00"){?>  selected="selected" <?php }?> >00</option>
                                                        <option value="15" <?php if(($a[1])=="15"){?>  selected="selected" <?php }?> >15</option>
                                                        <option value="30" <?php if(($a[1])=="30"){?>  selected="selected" <?php }?> >30</option>
                                                        <option value="45" <?php if(($a[1])=="45"){?>  selected="selected" <?php }?> >45</option>
                                                     </select>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                	<label for="wend_time2">End time</label>  
                                                    <p><h7>Hours:</h7></p>
                                                    <select class="custom-select form-control required end_time_hour" id="wend_time2" name="wend_time2">
                                                        <?php $i='';$n=24;
                                                        for($i=1;$i<=$n;$i++) { if($i<10) {?>
                                                         <option value="<?php echo '0'.$i;?>" <?php if(($b[0])=="$i"){?>  selected="selected" <?php }?> ><?php echo $i;?></option><?php } else {?>
                                                         <option value="<?php echo $i;?>" <?php if(($b[0])=="$i"){?>  selected="selected" <?php }?> ><?php echo $i;?></option> <?php } ?>
                                                        <?php } ?>
                                                      
                                                     </select> 
                                                    
                                                </div>
                                            </div> 
                                            <div class="col-md-4">
                                                <div class="form-group"> 
                                                <label for="wend_time12"><br></label>   
                                                    <p><h7>Minutes:</h7></p>
                                                    <select class="custom-select form-control required end_time_min" id="wend_time12" name="wend_time12">
                                                        <option value="00" <?php if(($b[1])=="00"){?>  selected="selected" <?php }?> >00</option>
                                                        <option value="15" <?php if(($b[1])=="15"){?>  selected="selected" <?php }?> >15</option>
                                                        <option value="30" <?php if(($b[1])=="30"){?>  selected="selected" <?php }?> >30</option>
                                                        <option value="45" <?php if(($b[1])=="45"){?>  selected="selected" <?php }?> >45</option>
                                                     </select>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                         <label for="part_number">Part of number</label> 
                                        <div class="onoffswitch">
                                            <input type="checkbox" name="partofnumber" class="onoffswitch-checkbox" id="partofnumber"  <?php if(($shift[0]['part_number'])=="0"){?> checked="checked" value="on" <?php } else { ?> value="off"  <?php } ?>  >
                                            <label class="onoffswitch-label" for="partofnumber">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                            <input type="hidden" name="p_number" id="p_number" value="<?php echo $shift[0]['part_number']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="shift_category">Shift category</label> 
                                        <select required="required" class="form-control" name="shift_category" id="shift_category" class="shift_category">  
                                            <option value="">Select shift category</option>
                                            <option value="1" <?php if(($shift[0]['shift_category'])=="1"){?>  selected="selected" <?php }?> >Day shift</option>
                                            <option value="2" <?php if(($shift[0]['shift_category'])=="2"){?>  selected="selected" <?php }?> >Night shift</option> 
                                            <option value="3" <?php if(($shift[0]['shift_category'])=="3"){?>  selected="selected" <?php }?> >Early shift</option> 
                                            <option value="4" <?php if(($shift[0]['shift_category'])=="4"){?>  selected="selected" <?php }?> >Late shift</option> 
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="shift_type">Shift type</label> 
                                        <select required="required" class="form-control" name="shift_type" id="shift_type" class="shift_type">  
                                            <option value="">Select shift type</option>
                                            <option value="1" <?php if(($shift[0]['shift_type'])=="1"){?>  selected="selected" <?php }?> >Full day</option>
                                            <option value=".5" <?php if(($shift[0]['shift_type'])==".5"){?>  selected="selected" <?php }?>>Half day</option>  
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="targeted_hours">Targeted hours</label>
                                        <input type="text"
                                            class="form-control targeted_hours" id="targeted_hours" name="targeted_hours"
                                            placeholder="Enter Targeted Hours" required="required" value="<?php echo $shift[0]['targeted_hours'];?>" readonly>
                                    </div>  

                                    <div class="form-group">
                                        <label for="unpaid_break_hours">Unpaid break hours (hh:mm)</label>
                                        <input type="text"
                                            class="form-control unpaid_break_hours" id="unpaid_break_hours" name="unpaid_break_hours"
                                            placeholder="Enter unpaid break hours (hh:mm)" required="required" value="<?php echo $shift[0]['unpaid_break_hours'];?>">
                                    </div> 

                                    <div class="form-group">
                                        <label for="total_targeted_hours">Total targeted hours</label>
                                        <input type="text"
                                            class="form-control total_targeted_hours" id="total_targeted_hours" name="total_targeted_hours"
                                            placeholder="Enter total targeted hours" value="<?php echo $shift[0]['total_targeted_hours'];?>" required="required" readonly>
                                    </div> 
                                    <div class="form-group"> 
                                        <div class="example">
                                            <h5 class="box-title">Select background color</h5> 
                                                <input type="text" name="back-color" id="back-color" class="complex-colorpicker form-control" value="<?php echo $shift[0]['background_color'];?>" /> 
                                        </div> 
                                    </div>  
                                    <div class="form-group"> 
                                        <div class="example">
                                            <h5 class="box-title">Select color</h5> 
                                                <input type="text" name="color" id="color" class="complex-colorpicker form-control" value="<?php echo $shift[0]['color_unit'];?>" /> 
                                        </div> 
                                    </div> 
                                   

									<div class="form-group" style="width: 200px;">
										<select class="form-control" name="status" id="status"
											class="status">
											<option <?php if($this->input->post('status')==1) { ?>
												selected="selected" <?php } ?> value="1">Active</option>
											<option <?php if($this->input->post('status')==2) { ?>
												selected="selected" <?php } ?> value="2">Inactive</option>
										</select>
									</div>

									<input type="hidden" name="id" id="id" value="<?php echo $shift[0]['id'];?>">
                                    <input type="hidden" name="userid" id="userid" value="<?php print_r($this->session->userdata('user_id')); ?>">
									<button type="button"
										onclick="location.href='<?php echo base_url('admin/shift/');?>';"
										class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>
									&nbsp;
									<button type="submit" class="btn btn-primary" id="try">Update</button>
								</form>
							</div>

                            </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jquery.min.js"></script>