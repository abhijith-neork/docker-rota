<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
          <div class="card-body"> 
             <div class="row page-titles">
                                                        <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Add Payment Type</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/managepayment/">Manage Payment Type</a></li>
                                                                <li class="breadcrumb-item active">Add Payment Type</li>
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
                            method="post" action="<?php echo base_url('admin/Payment/');?>addpaymenttype" style="width: 500px;">
                                    <div class="form-group">
                                        <label for="payment_type">Payment type</label>
                                        <input type="text" class="payment_type form-control" name="payment_type" id="payment_type" required="required" value="<?php echo $this->input->post('payment_type'); ?>"   placeholder="Enter payment type"> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="description form-control" id="description" name="description" required="required"   placeholder="Enter description"><?php echo $this->input->post('description'); ?></textarea> 
                                    </div>  
                                    <div class="form-group"  style="width: 200px;">
                                         <select class="form-control" name="status" id="status" class="status">
                                            <option <?php if($this->input->post('status')==1) { ?> selected="selected" <?php } ?> value="1">Active</option>
                                            <option <?php if($this->input->post('status')==2) { ?> selected="selected" <?php } ?> value="2">Inactive</option>
                                        </select>
                                    </div><br>
                                      <button type="button" onclick="location.href='<?php echo base_url('admin/payment/');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Add</button> 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
