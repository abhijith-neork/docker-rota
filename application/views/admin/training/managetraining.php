<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Manage Training</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Manage Training</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">

                        <button class="btn float-right hidden-sm-down btn-success"
                            onclick="location.href='<?php echo base_url();?>admin/Training/addtraining'">
                            <i class="mdi mdi-plus-circle"></i> Add Training
                        </button> 

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
                 
                    <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body"><p id="selectTriggerFilter"></p>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                 <th style="border:1px solid #dee2e6">Title</th> 
                                                 <th style="border:1px solid #dee2e6">Description</th>
                                                 <th style="border:1px solid #dee2e6">From</th>
                                                 <th style="border:1px solid #dee2e6">To</th>
                                                 <th style="border:1px solid #dee2e6">Hour</th>
                                                 <th style="border:1px solid #dee2e6">Place</th>
                                                 <th style="border:1px solid #dee2e6">Unit</th>
                                                 <th style="border:1px solid #dee2e6">Point of Contact</th>
                                                 <th style="border:1px solid #dee2e6">Created Date & Time</th>
                                                 <th style="border:1px solid #dee2e6">Created By</th> 
                                                 <th style="border:1px solid #dee2e6;width: 90px !important;">Action</th>
                                            </tr>
                                        </thead>
                                       <tbody> 

                                        </tbody>

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