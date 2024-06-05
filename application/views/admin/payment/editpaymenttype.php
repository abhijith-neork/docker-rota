<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
            <div class="card-body"> 
    	        <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Edit Payment Type</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/managepayment/">Manage Payment Type</a></li>
                            <li class="breadcrumb-item active">Edit Payment Type</li>
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
                         <form enctype="multipart/form-data" name="add" id="add"
                            method="post" action="<?php echo base_url('admin/Payment/');?>Editpaymenttype" style="width: 500px;">
                                    <div class="form-group">
                                        <label for="payment_type">Payment type</label>
                                        <input type="text" class="payment_type form-control" name="payment_type" id="payment_type"  value="<?php echo $pay[0]['payment_type'];?>"> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                         
                                        <textarea class="description form-control" id="description" name="description"   ><?php echo html_entity_decode($pay[0]['description']);?></textarea>
                                    </div>  
                                    <div class="form-group"  style="width: 200px;">
                                        <select class="form-control" name="status" id="status" class="status">
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div><br>
                                    <input type="hidden" name="id" id="id" value="<?php echo $pay[0]['id'];?>">
                                    <input type="hidden" name="userid" id="userid" value="<?php print_r($this->session->userdata('user_id')); ?>">
                                    <button type="button" onclick="location.href='<?php echo base_url('admin/payment/');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Update</button>   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
     