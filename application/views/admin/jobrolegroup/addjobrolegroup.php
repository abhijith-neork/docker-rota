<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body"> 
             <div class="row page-titles">
                                                         <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Add Job Role Group</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active"><a href="<?php echo base_url('admin/managejobrolegroup/');?>">Manage Job Role Group</a></li>
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
                            method="post" action="<?php echo base_url('admin/JobRoleGroup/');?>addjobrolegroup" style="width: 500px;">
                                    <div class="form-group">
                                        <label for="group_name">Job role group name</label>
                                        <input type="text" class="form-control required" name="group_name" value="<?php echo $this->input->post('group_name');?>" id="group_name" placeholder="Enter Job role group name" required="required"> 
                                    </div>   
                                    <button type="button" onclick="location.href='<?php echo base_url('admin/managejobrolegroup/');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Add</button> 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div> 
