<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body"> 
             <div class="row page-titles">
                                                        <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Manage Payment Type</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active">Manage Payment Type</li>
                                                            </ol>
                                                        </div>
                                                        <div class="col-md-6 col-4 align-self-center">
                                                           <button class="btn float-right hidden-sm-down btn-success" 
                                                           onclick="location.href='<?php echo base_url();?>admin/Payment/addpaymenttype'">
                                                            </i> Add Payment Type</button> 
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
                                                                <div class="card-body"> 
                                                                    <div class="table-responsive m-t-40">
                                                                        <table id="myTable" class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <!-- <th style="border:1px solid #dee2e6">No</th> --> 
                                                                                    <th style="border:1px solid #dee2e6">Payment Type</th>
                                                                                    <th style="border:1px solid #dee2e6">Description</th>
                                                                                    <th style="border:1px solid #dee2e6">Status <br>
                                                                                    <select id="status" class="select2 mb-2 select2-multiple status" name="status[]"  multiple="multiple">
                                                                                           <option value="1">Active</option>
                                                                                           <option value="2">Inactive</option>
                                                                                           <option value="3">Deleted</option>
                                                                                           <!-- <option value="4">All</option> -->
                                                                                    </select> 
                                                                                    </th>
                                                                                     <th style="border:1px solid #dee2e6">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                    <?php 
                                                                                    foreach($payment as $pay)
                                                                                    { if($pay['status']!=3){?> 
                                                                                    <tr>                                                             
                                                                                        <td><a href="<?php echo base_url("admin/payment/editpaymenttype/").$pay['id'];?>"><?php echo $pay['payment_type']; ?></a></td>
                                                                                        <td><?php echo $pay['description']; ?></td>
                                                                                        <td><?php if($pay['status']==1) { echo "Active"; } else { echo "Inactive";}  ?></td>
                                                                                        <td>
                                                                                            <a class="Edit" data-id="<?php echo $pay['id']; ?>" href="<?php echo base_url("admin/payment/editpaymenttype/").$pay['id'];?>" title="Edit"><i class="fas fa-edit"></i></a>  
                                                                                            <a class="Delete" data-id="<?php  $pay['id']; ?>" title="Delete" href="javascript:void(0);" onclick="deleteFunction('<?php echo $pay['id']; ?>','<?php echo $pay['payment_type']; ?>')" ><i class="fas fa-trash"></i></a>

                                                                                        </td> 
                                                                                    </tr>  

                                                                                    <?php
                                                                                    } }
                                                                                    ?>
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
