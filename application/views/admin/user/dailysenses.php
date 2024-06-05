<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body">
            <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Daily Senses</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active">Daily Senses</li>
                                                            </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">  
                                                           
                                                           
                    </div>
            </div>
             <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>    
            <form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('admin/Dailysenses/');?>addsenses" style="width: 100%;">  
            <div class="row">
            	<div class="col-md-6" style="padding-left: 36px">
            		<div class="form-group">
                                    <label for="Location">Unit</label> 
                                        <select required="required" class="form-control custom-select required unitdata" id="unitdata" name="unitdata" placeholder="Select Unit">
                                        <option value=""><?php echo "------Select unit-------"?></option>
                                        <?php foreach($unit as $cl) { ?> 
                                            <option value="<?php echo $cl['id']; ?>"<?php if($this->input->post('unitdata')==$cl['id']){?> selected="selected" <?php }?>><?php echo $cl['unit_name']; ?></option> 
                                        <?php } ?>
                                        </select> 
                    </div>
            	</div>
                <div class="col-md-6">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">User</label> 
                                        <select class="form-control custom-select required user" id="user" name="user" placeholder="Select User">
                                        <option value="none"><?php echo "------Select user-------"?></option>
                                        </select> 
                    </div>
                </div>
                <div class="col-md-6">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="description">Comment</label>
                                        <textarea class="description form-control" id="comment" name="comment" required="required"  placeholder="Enter comment" ><?php echo $this->input->post('comment'); ?></textarea> 
                    </div>
                </div>
                <div class="col-md-6">
                </div>
            </div> 
            <div class="row" style="padding-left: 33px;"> 
                    <button type="button" onclick="location.href='<?php echo base_url('admin/manageuser');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel" style="width:150px;">Cancel</button>&nbsp&nbsp
                    <button type="submit" class="btn btn-success mr-2" style="width:150px;">Submit</button>

            </div>
        </form> 
</div>
</div>
</div>
</div> 