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
                            <li class="breadcrumb-item active">Rota History Report</li>
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
            <form method="POST" action="" id="frmViewrotahistorylistReport" name="frmViewrotahistorylistReport">
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
                        <div class="form-group" style="padding-left: 36px;">
                            <label for="year">Year</label> 
                            <select class="SelectYear custom-select form-control required" id="year" name="year"> 
                               <option value=""><?php echo "--Select year--"?></option>
                                <?php $n=12; for($i=0;$i<$n;$i++){ $a=2019; $b=$a+$i; $c=$b;?> 
                                <option  value="<?php echo $c; ?>" <?php if($year==$c){?>  selected="selected" <?php }?> ><?php echo $c; ?></option><?php }?> 
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="padding-left: 36px;">
                            <label for="month">Month</label> 
                            <select class="SelectMonth custom-select form-control required" id="month" name="month">
                               <option value=""><?php echo "--Select month--"?></option>
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
                                            <th>Employee Name</th>
                                            <th>Modified Type</th> 
                                            <th>Changed Rota</th>
                                            <th>Previous Rota</th> 
                                            <th>Updated By</th>
                                            <th>Status</th>
                                            <th>Updated Date</th>
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
 