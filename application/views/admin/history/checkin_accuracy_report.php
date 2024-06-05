<div class="page-wrapper">
        <div class="container-fluid"> 
            <div class="card">
            <div class="card-body">
            <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">History</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active">Check-in/Check-out Accuracy Report</li>
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
             <form method="POST" action="" id="frmViewcheckinaccuracyReport" name="frmViewcheckinaccuracyReport">
                <div class="row">
                    <div class="col-md-3" style="padding-left: 36px">
                        <div class="form-group">
                            <label for="Location">Unit</label> 
                            <select class="form-control custom-select unit" id="unit" name="unit" placeholder="Select Unit">
                            <option value=""><?php echo "------Select unit-------"?></option>
                            <?php foreach($unit as $cl) { ?>
                                <option <?php    if($this->input->post("unitdata")==$cl['id']) { ?> selected="selected" <?php } ?> value="<?php echo $cl['id']; ?>" ><?php echo $cl['unit_name']; ?></option>  
                            <?php } ?>
                            </select>  
                        </div>
                    </div>
                    <div class="col-md-2" style="padding-left: 36px">
                            <div class="form-group"> 
                                <label for="from_date">Date<span style="color: red;">*</span></label> 
                                <input type="text"  class="form-control required" id="start_time" placeholder="dd/mm/yy" name="start_time" value="<?php echo "$start_date";?>"> 
                            </div>
                </div>
            
                    <div class="col-md-2">
                    <div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
                     <button type="button" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                                    
                    </div>
                      
                </div>
                </div>
            </form> 
            <div class="row">
                    <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                                <th>ID</th> 
                                                                <th>Accuracy</th>
                                                                <th>User#</th> 
                                                                <th>Staff Name</th>
                                                                <th>Check With UserID</th>
                                                                <th>Date</th>
                                                                <th>Status</th>  
                                                    </tr>
                                                </thead>
     
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