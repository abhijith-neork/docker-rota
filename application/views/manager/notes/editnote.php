 <div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
            <div class="card-body"> 
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">View Notifications</h3>
                        <ol class="breadcrumb">
                            <?php if( in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')==3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active"><a href="<?php echo base_url();?>manager/notes/index">Manage Notifications</a></li>
                            <li class="breadcrumb-item active">View Notifications</li>
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
                                method="post" action="<?php echo base_url('manager/Notes/');?>editNote/<?php echo $id ?>" style="width: 100%;">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" class="title form-control" name="title" id="title" placeholder="Enter title" value="<?php echo $note[0]['title'];?>" readonly> 
                                        </div>  
                                        <div class="form-group">
                                        <label for="notification_type">Notification type</label> 
                                        <select required="required" class="form-control" name="notification_type" id="notification_type" class="notification_type" readonly>  
                                            <option value="1" <?php if(($note[0]['notification_type'])=="1"){?>  selected="selected" <?php }?> >Both (SMS & Mail)</option>
                                            <option value="2" <?php if(($note[0]['notification_type'])=="2"){?>  selected="selected" <?php }?> >SMS</option>
                                            <option value="3" <?php if(($note[0]['notification_type'])=="3"){?>  selected="selected" <?php }?> >Mail</option> 
                                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_time">Date & Time</label>
                                            <input type="text" class="date_time form-control" name="date_time" id="date_time" placeholder="Enter date_time" value="<?php echo date("d/m/Y H:i:s",strtotime($note[0]['creation_date'])); ?>" readonly> 
                                        </div>  
                                        <div class="form-group">
                                            <label for="description">Comment</label>
                                            <textarea style="height: 150px;" class="description form-control" id="comment" name="comment"   placeholder="Enter comment" value="<?php echo $note[0]['comment'];?>" readonly><?php echo $note[0]['comment'];?></textarea>
                                            <input type="hidden" id="user_unitids" name="result" value="<?php echo $unit_id_string; ?>">
                                            <input type="hidden" id="id" name="id" value="<?php echo $id ?>">
                                        </div>
                                        <button type="button" onclick="location.href='<?php echo base_url('manager/notes/index');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>&nbsp;
<!--                                         <button type="submit" class="btn btn-primary" id="try">Update</button> 
 -->                                </div>
                            </div>
                        </div>
                        <div class="col-6"> 
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body" style="padding-top: 9px;">
                                            <input type="hidden" name="unit_id" value="<?php echo $unit_id_string ?>">
                                            <h5 class="mt-3">Selected unit</h5>
                                            <select class="select2 mb-2 select2-multiple" id="unit" name="unit[]" style="width: 100%" multiple="multiple" data-placeholder="Choose" readonly="readonly">
                                                <optgroup label="Units" class="units" id="units" name="unit[]">
                                                    <?php
                                                        foreach ($units as $unit) {
                                                    ?>   
                                                    <option <?php if(in_array($unit['id'], $unit_id_array)) { ?> selected <?php } ?> data-id="<?php echo $unit['id']; ?>" value="<?php echo $unit['id']; ?>" ><?php echo $unit['unit_name']; ?></option>  
                                                    <?php } ?>  
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body"><p id="selectTriggerFilter"></p>
                                            <div class="table-responsive m-t-40"  style="padding: 1px;">
                                                <table id="myTable1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>                               
                                                            <th id="emp" style="border:1px solid #dee2e6">Employee</th> 
                                                            <th id="des" style="border:1px solid #dee2e6">Job Role</th> 
                                                            <!-- <th style="border:1px solid #dee2e6"><input type="checkbox" class="selectall" name="selectall" id="selectall"> Select All</th> -->
                                                       </tr>
                                                    </thead>
                                                    <!-- <tbody>
                                                       <?php foreach ($users as $key => $user) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $user['fname']." ".$user['lname'] ; ?></td>
                                                            <td><?php echo $user['designation_name']; ?></td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody> -->
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