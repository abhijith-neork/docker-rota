<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Add Comments</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser">Home</a></li>
                            <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/training/mytraining/">My Trainings </a></li>
                            <li class="breadcrumb-item active">Add Comments</li>
                        </ol>
                    </div>
                    
                </div>
            <?php if($error):?>
            <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
              <?php echo $error;?>
            </p>
            <?php endif;?> 
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">  
                                
                             <form enctype="multipart/form-data" name="add" id="add"
                            method="post" action="<?php echo base_url('staffs/training/');?>Editfeedback" >
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="title form-control" name="title" id="title" readonly="readonly" required="required" value="<?php echo $training[0]['title']; ?>"> 
                                    </div>  
                                    <div class="form-group">
                                        <label for="feedback">Comments</label>
                                         
                                        <textarea class="feedback form-control" id="feedback" name="feedback" required="required" > </textarea>
                                    </div>  
                                    <input type="hidden" name="id" id="id" value="<?php echo $this->session->userdata('user_id'); ?>"> 
                                    <input type="hidden" name="training_id" id="training_id" value="<?php echo $training[0]['training_id']; ?>"> 
                                    <input type="hidden" name="device" id="device" value="<?php echo $device; ?>"> 
                                    <button type="button" onclick="calcelFeedBackPAge()"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary">Update</button>  
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                            