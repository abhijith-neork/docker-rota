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
                            <li class="breadcrumb-item active">Overstaffing Report</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">  
                                                           
                                                           
                    </div>
            </div>
             <?php
            if($this->session->flashdata('error')==1)
                $color="red";
            else 
                $color="green";
            
            if($this->session->flashdata('message')):?>
            <p class="success-msg" id="success-alert"
                    style="color: <?php echo $color; ?>; text-align:center;">
              <?php echo $this->session->flashdata('message');?>
            </p>
            <?php endif;?>   
            <form method="POST" action="<?php echo base_url();?>admin/Reports/overstaffingreport" id="frmViewOverstaffingReport"   name="frmViewOverstaffingReport">
            <div class="row">
            	<div class="col-md-3" style="padding-left: 36px">
            		<div class="form-group">
                                    <label for="Location">Unit</label> 
                                        <select required="required" class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select Unit">
                                        <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                        <option value="none"><?php echo "------Select unit-------"?></option>
                                        <?php }?>
                                        <?php foreach($unit as $cl) { ?>
                                        	<option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                        <?php } ?>
                                        </select> 


                                    <!-- <input type="text" id="start_year" name="start_year" value="">
                                    <input type="text" id="start_month" name="start_month" value="">
                                    <input type="text" id="unit_id" name="unit_id" value=" "> -->
                    </div>
            	</div>
                <!--  <div class="col-md-3" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">Job role</label> 
                                        <select required="required" class="form-control custom-select required jobrole" id="jobrole" name="jobrole" placeholder="Select Job Role">
                                        <option value=""><?php echo "------Select job role-------"?></option>
                                        <?php foreach($jobrole as $cl) { ?>
                                            <option <?php    if($this->input->post("jobrole")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['designation_name']; ?></option>  
                                        <?php } ?>
                                        </select>  
                    </div>
                </div> -->
            	
            	<div class="col-md-2" style="padding-right: 36px;">
            		<div class="form-group">
                                    <label for="Location">Year</label> 
                                        <select required="required" class="form-control custom-select required year" id="year" name="year" placeholder="Select Year">
                                        <option value="none"><?php echo "------Select year-------"?></option>
                                         <?php $n=12; for($i=0;$i<$n;$i++){ $a=2019; $b=$a+$i; $c=$b;?> 
                                       
                                        <option  value="<?php echo $c; ?>" <?php if($start_year==$c){?> selected="selected" <?php }?>><?php echo $c; ?></option><?php }?> 
                                        </select> 
                    </div>
            	</div>

            	<div class="col-md-2" style="padding-right: 36px;">
            		<div class="form-group">
                                    <label for="Location">Month</label> 
                                        <select required="required" class="form-control custom-select required month" id="month" name="month" placeholder="Select Month">
                                        <option value="none"><?php echo "------Select month-------"?></option>
                                        
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
            	<!-- <div class="col-md-2" style="padding-top: 34px;" > -->
            	<div class="col-md-2">
                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                     <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search">Search</button> 
                                    
                    </div>
                      
                </div>
            </div>
            <div class="row">
            	
            	<div class="col-md-6" style="padding-left: 40px;"> 
            	<!-- 	<label for="designation_name">Designation:</label>
                    <input type="text" class="form-control" name="designation_name" id="designation_name" readonly="readonly"> -->
                     </div> 
            	  </div>
            	<div class="col-md-6" style="padding-right: 40px;"> </div>
            		
            </div>
            <div class="row"> 
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                	<h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                <table id="myTable1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th>Date</th>
                        <th>Dayshift Maximum</th> 
                        <th>Total Dayshift Staff</th>
                        <th>Overstaffed On Dayshift</th>
                        <th>Nightshift Maximum</th>
                        <th>Total Nightshift Staff</th>
                        <th>Overstaffed On Nightshift</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($dates!=''){?>
                    <?php foreach ($dates as $value) { ?>
                        <tr>
                            <td><span style="display: none;"><?php echo $value['date']; ?></span><?php echo date("d/m/Y",strtotime($value['date']));?></td>
                            <td><?php echo $value['day_shift_max'];?></td>
                            <td><?php echo $value['day_count'];?></td>
                            <?php if ($value['day_overstaffed'] > 0 ) { ?>
                                <td><?php echo $value['day_overstaffed'];?></td>
                            <?php } else { ?>
                                <td></td>
                            <?php } ?>
                            <td><?php echo $value['night_shift_max'];?></td>
                            <td><?php echo $value['night_count'];?></td>
                            <?php if ($value['night_overstaffed'] > 0 ) { ?>
                                <td><?php echo $value['night_overstaffed'];?></td>
                            <?php } else { ?>
                                <td></td>
                            <?php } ?>
                        </tr>
                    <?php } }?>
                    </tbody>
                 </table>
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
 