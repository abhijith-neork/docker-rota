 <div class="page-wrapper">
    <div class="container-fluid"> 
    <div class="card">
    <div class="card-body"> 
             <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor mb-0 mt-0">Add Notifications</h3>
                    <ol class="breadcrumb">
                        <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                        <?php } elseif($this->session->userdata('user_type')==3) {?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                        <?php }?>
                        <li class="breadcrumb-item active"><a href="<?php echo base_url();?>manager/notes/index">Manage Notifications</a></li>
                        <li class="breadcrumb-item active">Add Notifications</li>
                    </ol>
                </div>
             </div>
             <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>    
                <div class="row page-titles">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">  
                             <form enctype="multipart/form-data" name="add" id="add"
                            method="post" action="<?php echo base_url('manager/Notes/');?>sendNote" style="width: 100%;">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input value="<?php echo $this->input->post('title'); ?>" type="text" required="required" class="title form-control" name="title" id="title" placeholder="Enter title" > 
                                    </div>  

                                    <div class="form-group">
                                        <label for="notification_type">Notification type</label> 
                                        <select  class="form-control" required="required" name="notification_type" id="notification_type" class="notification_type">   
                                            <option value="1"<?php if($notes_type=="1"){?> selected="selected" <?php }?>>Both (SMS & Mail)</option>
                                            <option value="2"<?php if($notes_type=="2"){?> selected="selected" <?php }?>>SMS</option>
                                            <option value="3"<?php if($notes_type=="3"){?> selected="selected" <?php }?>>Mail</option>   
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Comment</label>
                                        <textarea class="description form-control" id="comment" name="comment" required="required"  placeholder="Enter comment" style="height: 150px;"><?php echo $this->input->post('comment'); ?></textarea> 
                                    </div>
                                    <button type="button" onclick="location.href='<?php echo base_url('manager/notes/index');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;<button type="submit" class="btn btn-primary" id="try">Send</button>
                                    
                                    <div class="form-group user-list" style="display: none;">
                                        <label></label>
                                        <label for="users">Selected Users</label> 
                                        <input type="hidden" id="selected_users" class="selected_users" name="selected_users[]" value="" />
                                        <div class="table-responsive m-t-40">
                                        <table id="selected-users" class="table table-bordered table-striped selected-users-table">
                                            <tbody class='selected-user-data'>
                                                <tr></tr>
                                            </tbody>
                                        </table></div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6"> 
                        <div class="row">
                        <div class="col-12">
                                <div class="card">
                                <div class="card-body" style="padding-top: 9px;">
                                
                                <h5 class="mt-3">Select unit</h5>
                                <select class="select2 mb-2 select2-multiple" id="unit" name="unit[]" style="width: 100%" multiple="multiple" data-placeholder="Choose">
                                    <optgroup label="Units" class="units" id="units" name="unit[]">
                                        <?php
                                            foreach ($units as $unit) {
                                        ?>   
                                        <option class='sl-unit' data-id="<?php echo $unit['id']; ?>" text="<?php echo $unit['parent_unit'];?>" data-parent="<?php echo $unit['parent_unit'];?>" value="<?php echo $unit['id']; ?>" ><?php echo $unit['unit_name']; ?></option>  
                                        <?php } ?>  
                                    </optgroup>
                                 
                    
                                </select>
                                </div>
                                </div>
                        </div>
                        <div class="col-12">
                                <div class="card">
                                   <!--  <h5>User List</h5> -->
                                    <div class="card-body"><p id="selectTriggerFilter"></p>
                                    <div class="table-responsive m-t-40"  style="padding: 1px;">
                                        <table id="myTable1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <input type="hidden" id="jobrole" class="jobrole" value="">
                                                    <th id="emp" style="border:1px solid #dee2e6">Employee</th> 
                                                    <th id="des" style="border:1px solid #dee2e6">Job Role</th> 
                                                    <th style="border:1px solid #dee2e6"><input type="checkbox" name="selectall" class="selectall"  id="selectall"> Select all</th>
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
</div>
</div>
</div>
</div>
<script type="text/javascript">
    var unit_array=<?php print_r($unit_array);?>;
</script>


