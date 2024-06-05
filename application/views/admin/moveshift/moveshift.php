 <div class="page-wrapper">
    <div class="container-fluid"> 
    <div class="card">
    <div class="card-body"> 
             <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Move Shift</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active">Move shift</li>
                                                            </ol>
                </div>
             </div>

            
 
            <?php if($success){ ?>
                <div class="success--msg" style="color: green;padding-left: 1%;padding-botton:20px;"><?php echo $success;?></div>
            <?php } else {?> 
                 <div class="success--msg" style="color: red;padding-left: 1%;padding-botton:20px;"><?php echo $error;?></div>
            <?php } ?> 



                <div class="row page-titles">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">  
                                <form enctype="multipart/form-data" name="add" id="frmmoveshift" method="post" action="<?php echo base_url('admin/MoveShift/');?>moveshift" >
                                    <div class="form-group">
                                            <label for="Location">Date <span style="color: red;">*</span></label> 
                                            <input class="start_date form-control" required type="text" name="start_date" id="from-datepicker" name="start_date" autocomplete="off" value="<?php echo "$start_date";?>">
                                    </div>

                                    <div class="form-group">
                                            <label for="Location">From unit<span style="color: red;">*</span></label> 
                                            <select  class="form-control custom-select  unitdata" id="unitdata" name="unitdata" placeholder="Select unit">
                                            <option value=""><?php echo "--Unit--"?></option>
                                            <?php foreach($unit as $cl) { ?>
                                            <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                            <?php } ?>
                                            </select>
                                    </div>

                                    <div class="form-group">
                                                        <label for="Location">User:<span style="color: red;">*</span></label> 
                                                            <select required="required" class="form-control custom-select required user" id="user" name="user" placeholder="Select User">
                                                            <option value="0"><?php echo "------Select user-------"?></option>
                                                            </select> 
                                    </div>
                                    

                                    <div class="form-group">
                                                    <label for="Location">To unit <span style="color: red;">*</span></label> 
                                                    <select  class="form-control custom-select  unitdatafor" id="unitdatafor" name="unitdatafor" required placeholder="Select unit">
                                                        <option value=""><?php echo "--Unit--"?></option>
                                                        <?php foreach($locationunit as $cl) { ?>
                                                            <option <?php    if($this->input->post("unitdatafor")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                                                        <?php } ?>
                                                    </select>
                                    </div>

                                    <div class="form-group">
                                                    <label for="Location">Shift <span style="color: red;">*</span></label> 
                                                    <select class="shift form-control custom-select" id="shift" name="shift" placeholder="Select shift" required>
                                                        <option value=""><?php echo "--Shift--"?></option>
                                                        <?php foreach($shifts as $shift) { ?>
                                                        <option <?php    if($this->input->post("shift")==$shift['id']) { ?> selected="selected" <?php } ?> value="<?php echo $shift['id']; ?>" ><?php echo $shift['shift_name']; ?></option>  
                                                        <?php } ?>
                                                    </select>
                                    </div>
                                

                                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                                         <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="move_shift" style="width: 100px;">Move Shift</button> 
                                                        
                                    </div>
                                    <input type="hidden" name="message" id="message" value="" style="width:100%;">
                                    <input type="hidden" name="shift_gap" id="shift_gap" value="<?php echo $shift_gap;?>">
                                    <input type="hidden" name="rota_unit" id="rota_unit" value="">
                                </form>        
                            </div>
                        </div>
                    </div>
                    <div class="col-6"> 
                        <div class="row">
                            <div class="col-12">
                                    <div class="card">
                                       <!--  <h5>User List</h5> -->
                                        <div class="card-body"><p id="selectTriggerFilter">Shift Details</p>
                                        <div class="table-responsive m-t-40"  style="padding: 1px;">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="border:1px solid #dee2e6">Employee</th> 
                                                        <th style="border:1px solid #dee2e6">Unit</th> 
                                                        <th style="border:1px solid #dee2e6">Date</th>
                                                        <th style="border:1px solid #dee2e6">Shift</th>
                                                   </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>