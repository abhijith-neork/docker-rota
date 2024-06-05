<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body">
            <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Daily Census</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                 <li class="breadcrumb-item"><a
                                                                    href="<?php echo base_url();?>admin/Dailysenses/index">Daily Census</a></li>
                                                                <li class="breadcrumb-item active">Edit Daily Census</li>
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
            <form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('admin/Dailysenses/');?>editsenses" style="width: 100%;">  
            <div class="row">
            	<div class="col-md-6" style="padding-left: 36px">
            		<div class="form-group">
                                    <label for="Location">Unit</label> 
                                    <input type="text" class="form-control required" name="unitdata" id="unitdata" value="<?php echo $user[0]['unit_name'];?>" readonly> 
                                        <!-- <select required="required" class="form-control custom-select required unitdata" id="unitdata" readonly="readonly" name="unitdata" placeholder="Select Unit">
                                        <option value=""><?php echo "------Select unit-------"?></option>
                                        <?php foreach($unit as $cl) { ?>  
                                            <option value="<?php echo $cl['id']; ?>"<?php if(($user[0]['unit_id'])==$cl['id']){?> selected="selected" <?php }?>><?php echo $cl['unit_name']; ?></option> 
                                        <?php } ?>
                                        </select>  -->
                    </div>
            	</div>
                <div class="col-md-6">
                </div>
            </div>
            <?php $users=getUsers($user[0]['unit_id']);?>
            <div class="row">
                <div class="col-md-6" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="Location">User</label> 
                                     <input type="text" class="form-control required" name="user" id="user" value="<?php echo $user[0]['fname'];?> <?php echo $user[0]['lname'];?>" readonly>

                                       <!--  <select required="required" class="form-control custom-select required user" id="user" name="user" placeholder="Select user" readonly> 
                                        <?php foreach($users as $cl) { ?>   
                                            <option value="<?php echo $cl['user_id']; ?>"<?php if(($user[0]['user_id'])==$cl['user_id']){?> selected="selected" <?php }?>><?php echo $cl['fname']; ?> <?php echo $cl['lname']; ?></option> 
                                        <?php } ?>

                                        </select>  -->
                    </div>
                </div>
                <div class="col-md-6">
                </div>
            </div>
            <div class="row">
            <div class="col-md-6" style="padding-left: 36px">
                                                <div class="form-group">
                                                    <label for="date">Date:</label>
                                                    <input type="text" class="form-control required" required="required" name="date" id="from-datepicker" value="<?php echo date("d/m/Y",strtotime($user[0]['date'])); ?>" placeholder="Select date"> 
                                                </div>
            </div>
            </div>
            <div class="row">
                <div class="col-md-6" style="padding-left: 36px">
                    <div class="form-group">
                                    <label for="description">Comment</label>
                                        <textarea class="description form-control" id="comment" name="comment" required="required"  placeholder="Enter comment" ><?php echo $user[0]['comment'];?></textarea> 
                    </div>
                </div>
                <div class="col-md-6">
                </div>
            </div> 
            <div class="row" style="padding-left: 33px;"> 
                    <input type="hidden" name="id" id="id" value="<?php echo $user[0]['id'];?>">
                    <input type="hidden" name="unit_id" id="unit_id" value="<?php echo $user[0]['unit_id'];?>">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user[0]['user_id'];?>">
                    <button type="button" onclick="location.href='<?php echo base_url('admin/Dailysenses/index');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel" style="width:150px;">Cancel</button>&nbsp&nbsp
                    <button type="submit" class="btn btn-success mr-2" style="width:150px;">Submit</button>

            </div>
        </form> 
</div>
</div>
</div>
</div> 