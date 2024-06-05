<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body"> 
             <div class="row page-titles">
                                                         <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Add Group</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active"><a href="<?php echo base_url('admin/managegroup/');?>">Manage Group</a></li>
                                                                <li class="breadcrumb-item active">Add Group</li>
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
                        <!--      <h5 class="card-title"> Add Group </h5>  -->
                             <form enctype="multipart/form-data" name="add" id="add"
                            method="post" action="<?php echo base_url('admin/group/');?>addgroup" style="width: 500px;">
                                    <div class="form-group">
                                        <label for="group_name">Group name</label>
                                        <input type="text" class="form-control required" name="group_name" autocomplete="off" value="<?php echo $this->input->post('group_name');?>" id="group_name" placeholder="Enter group name" required="required"> 
                                    </div> 
                                    <div class="form-group"> 
                                        <label for="subunit_access" style="padding-right: 20px;">Subunit access</label><br>
                                        <select class="form-control" name="subunit_access" id="subunit_access" class="subunit_access" style="width: 200px;">
                                            <option <?php if($this->input->post('subunit_access')==1) { ?> selected="selected" <?php } ?> value="1">Yes</option>
                                            <option <?php if($this->input->post('subunit_access')==0) { ?> selected="selected" <?php } ?> value="0">No</option>
                                        </select>
                                    </div>   
                                    <div class="form-group"  style="width: 200px;">
                                        <select class="form-control" name="status" id="status" class="status">
                                            <option <?php if($this->input->post('status')==1) { ?> selected="selected" <?php } ?> value="1">Active</option>
                                            <option <?php if($this->input->post('status')==2) { ?> selected="selected" <?php } ?> value="2">Inactive</option>
                                        </select>
                                    </div><br>
                                    <button type="button" onclick="location.href='<?php echo base_url('admin/group/');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Add</button> 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div> 
