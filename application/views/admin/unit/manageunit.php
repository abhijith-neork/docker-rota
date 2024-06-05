<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
          <div class="card-body"> 
             <div class="row page-titles">
                                                        <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Manage Unit</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active">Manage Unit</li>
                                                            </ol>
                                                        </div> 
                                                        <div class="col-md-6 col-4 align-self-center">

                                                        <button class="btn float-right hidden-sm-down btn-success"
                                                            onclick="location.href='<?php echo base_url();?>admin/Unit/addunit'">
                                                            <i class="mdi mdi-plus-circle"></i> Add Unit
                                                        </button> 

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
                                                                        <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                                                            <thead>
                                                                                <tr>
                                                                                    <!-- <th style="border:1px solid #dee2e6">No</th> --> 
                                                                                     <th style="border:1px solid #dee2e6">Unit Shortname</th> 
                                                                                     <th style="border:1px solid #dee2e6">Unit Name</th> 
                                                                                     <th style="border:1px solid #dee2e6">Unit Type</th> 
                                                                                     <th style="border:1px solid #dee2e6">Staff Limit</th>
                                                                                     <th style="border:1px solid #dee2e6">Maximum Patients</th>
                                                                                     <th style="border:1px solid #dee2e6">Number of Beds</th>
                                                                                     <th style="border:1px solid #dee2e6">Description</th>
                                                                                     <th style="border:1px solid #dee2e6">Status
                                                                                     <br>
                                                                                     <select id="status" class="select2 mb-2 select2-multiple status" name="status[]"  multiple="multiple"> 
                                                                                           <option value="1">Active</option>
                                                                                           <option value="2">Inactive</option>
                                                                                           <option value="3">Deleted</option>
                                                                                          <!--  <option value="4">All</option> -->
                                                                                     </select> 
                                                                                     </th>
                                                                                     <th style="border:1px solid #dee2e6">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                    <?php 
                                                                                    foreach($unittypes as $uni)
                                                                                    { if($uni['status']!=3){ ?> 
                                                                                    <tr> 

                                                                                        <td><a href="<?php echo base_url("admin/unit/editunit/").$uni['id'];?>"><?php echo $uni['unit_shortname']; ?></a></td>                
                                                                                        <td><a href="<?php echo base_url("admin/unit/editunit/").$uni['id'];?>"><?php echo $uni['unit_name']; ?></a>
                                                                                        <button style="float:right;height:10px;background-color:<?php echo $uni['color_unit'];?>" disabled></button>                  
                                                                                        </td> 
                                                                                        <td><?php echo $uni['name']; ?></td>
                                                                                        <td><?php echo $uni['staff_limit']; ?></td>
                                                                                        <td><?php echo $uni['max_patients']; ?></td>
                                                                                        <td><?php echo $uni['number_of_beds']; ?></td>
                                                                                        <td><?php echo $uni['description']; ?></td>
                                                                                        <td><?php if($uni['status']==1) { echo "Active"; } else { echo "Inactive";} ?></td>
                                                                                        <td><a class="Edit" data-id="<?php echo $uni['id']; ?>" href="<?php echo base_url("admin/unit/editunit/").$uni['id'];?>" title="Edit"><i class="fa fa-edit"></i></a>
                                                                                     <?php if( $uni['id']!=1 ){?>
                                                                                        <a class="Delete" data-id="<?php  $uni['id']; ?>" title="Delete" href="javascript:void(0);" onclick="deleteFunction('<?php echo $uni['id']; ?>','<?php echo $uni['unit_name']; ?>')" ><i class="fa fa-trash"></i></a> 
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
