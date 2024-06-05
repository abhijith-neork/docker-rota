<style>
select {

    word-wrap: break-word;
    width: 75px;

}
.select2-dropdown
{
    width: 200px !important;
}
.select2-selection__choice
{
    height: 20px;
}
</style>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Manage Users</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Manage Users</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">

                        <button class="btn float-right hidden-sm-down btn-success"
                            onclick="location.href='<?php echo base_url();?>admin/User/addUser'" style="height: 40px;margin:0 10px;">
                            <i class="mdi mdi-plus-circle"></i> Add User
                        </button> 
                        
                        <button class="btn  float-right hidden-sm-down btn-success" 
                                onclick="location.href='<?php echo base_url();?>admin/User/addagencystaff'" style="height: 40px;margin-left: 10px;">
                                <i class="mdi mdi-plus-circle"></i>Add Agency Employee</button>
                        <?php if($this->session->userdata('user_type') == 1){?>
                        <button class="btn  float-right hidden-sm-down btn-success" 
                                onclick="location.href='<?php echo base_url();?>admin/User/employeedetailspermission'" style="height: 40px;">
                                <i class="mdi mdi-plus-circle"></i>Edit Permission</button>
                        <?php }?>
                        <!-- <?php if($this->session->userdata('user_type') == 1){?>
                        <button class="btn  float-right hidden-sm-down btn-success" 
                                onclick="location.href='<?php echo base_url();?>admin/User/ipaddresses'" style="height: 40px;margin:0 10px;">
                                <i class="mdi mdi-plus-circle"></i>Manage IP Address</button>
                        <?php }?> -->
                    </div>


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
                                                                                    <th>Id</th> 
                                                                                    <th>Name</th> 
                                                                                    <th>Email Address</th>
                                                                                    <th>Payment Type</th>
                                                                                    <th>Unit
                                                                                    <select class="unit" id="unit"> 
                                                                                        <?php  if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                                        <option value=""></option>
                                                                                    <?php }?>
                                                                                        <?php foreach($categoryList as $cl) { ?>
                                                                                            <option value="<?php echo $cl['id']; ?>"<?php if($this->input->post('unit')==$cl['id']){?> selected="selected" <?php }?>><?php echo $cl['unit_name']; ?>
                                                                                            </option>
                                                                                        <?php } ?>
                                                                                    </th> 
                                                                                    <th>Group</th>
                                                                                    <th>Job Role</th>
                                                                                    <th>Last Login Date</th>
                                                                                    <th>Status
                                                                                    <br>
                                                                                    <select id="status" class="select2 mb-2 select2-multiple status" name="status[]"  multiple="multiple"> 
                                                                                           <option value="1">Active</option>
                                                                                           <option value="2">Inactive</option>
                                                                                           <option value="3">Deleted</option>
                                                                                           <!-- <option value="4">All</option> -->
                                                                                    </select> 
                                                                                    </th>
                                                                                    <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody> 
                                                                                        <?php 
                                                                                                foreach($user as $use) 
                                                                                        { if($use['status']!=3){?> 
                                                                                        <tr>
                                                                                    <td><?php echo $use['id'];?></a></td>
                                                                                    <td><a href="<?php echo base_url("admin/user/edituser/").$use['id'];?>"><?php echo $use['fname']." ".$use['lname'];?></a></td> 
                                                                                    <td><?php echo $use['email'];?></a></td>
                                                                                    <td><?php echo $use['payment_type'];?></td> 
                                                                                    <td><?php echo $use['unit_name'];?></td>
                                                                                    <td><?php echo $use['group_name'];?></td>
                                                                                    <td><?php echo $use['designation_code'];?></td>
                                                                                    <?php if($use['lastlogin_date']!=''){?>
                                                                                    <td><span style="display: none;"><?php echo $use['lastlogin_date']; ?></span><?php echo date("d/m/Y H:i:s",strtotime($use['lastlogin_date'])); ?></td>
                                                                                    <?php } else {?>
                                                                                    <td><?php echo ""; ?></td>
                                                                                    <?php }?>
                                                                                    <td><?php if($use['status']==1) { echo "Active"; } else { echo "Inactive";} ?></td>
                                                                                    <td> 
                                                                                        <a class="Edit" data-id="<?php echo $use['id']; ?>" href="<?php echo base_url("admin/user/edituser/").$use['id'];?>" title="Edit"><i class="fas fa-edit"></i></a>  
                                                                                    <?php if( $use['group_id']!=1 ){?> 
                                                                                        
                                                                                        <a class="Delete" data-id="<?php  $use['id']; ?>" title="Delete" href="javascript:void(0);" onclick="deleteFunction('<?php echo $use['id']; ?>','<?php echo $use['fname']; ?>')" title="Remove"><i class="fas fa-trash"></i></a>

                                                                                        <a class="Changepassword" data-id="<?php  $use['id']; ?>" title="Change password" href="<?php echo base_url("admin/user/changepassword/").$use['id'];?>" target="_blank" ><i class="fas fa-key"></i></a>

                                                                                         <a class="Send" data-id="<?php  $use['id']; ?>" title="Send Password Reset Link" href="#" onclick="sendFunction('<?php echo $use['id']; ?>','<?php echo $use['fname']; ?>')" title="Remove"><i class="fa fa-external-link-alt"></i></a>

                                                                                         <a class="photochange" data-id="<?php  $use['id']; ?>" title="Change profile picture" href="<?php echo base_url("admin/user/changepicture/").$use['id'];?>" target="_blank" ><i class="fas fa-image"></i></a>

                                                                                    <?php } ?> 
                                                                                        
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