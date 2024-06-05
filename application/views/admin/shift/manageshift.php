<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Manage Shift</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Manage Shift</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">

                        <button class="btn float-right hidden-sm-down btn-success"
                            onclick="location.href='<?php echo base_url();?>admin/Shift/addshift'">
                            <i class="mdi mdi-plus-circle"></i> Add Shift
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
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <!-- <th style="border:1px solid #dee2e6">No</th> --> 
                                             <th style="border:1px solid #dee2e6">Shift Name</th>
                                             <th style="border:1px solid #dee2e6">Shift Short Name</th>
                                             <th style="border:1px solid #dee2e6">Start Time</th>
                                             <th style="border:1px solid #dee2e6">End Time</th>
                                             <th style="border:1px solid #dee2e6">Shift Category</th>
                                             <th style="border:1px solid #dee2e6">Targeted Hours</th>
                                             <th style="border:1px solid #dee2e6">Unpaid Break Hours</th>
                                             <th style="border:1px solid #dee2e6">Total Targeted Hours</th>
                                             <th style="border:1px solid #dee2e6">Part of Number</th>
                                             <th style="border:1px solid #dee2e6">Status<br>
                                                                                    <select id="status" class="select2 mb-2 select2-multiple status" name="status[]"  multiple="multiple"> 
                                                                                           <option value="1">Active</option>
                                                                                           <option value="2">Inactive</option>
                                                                                           <option value="3">Deleted</option>
                                                                                           <!-- <option value="4">All</option> --> 
                                                                                    </select> 
                                            </th>

                                             <th style="border:1px solid #dee2e6">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     <?php 
                                            foreach($shifts as $shift)
                                            { ?> 
                                               <?php  if($shift['id']!=0){
                                               if($shift['status']!=3) {?>
                                            <tr>                                                             
                                                <td><a href="<?php echo base_url("admin/shift/editshift/").$shift['id'];?>"><?php echo $shift['shift_name']; ?></a>
                                                    <button style="float:right;height:10px;background-color:<?php echo $shift['color_unit'];?>" disabled></button>   </td>
                                                <td><a href="<?php echo base_url("admin/shift/editshift/").$shift['id'];?>"><?php echo $shift['shift_shortcut']; ?></a></td>
                                                <td><?php echo $shift['start_time']; ?></td>
                                                <td><?php echo $shift['end_time']; ?></td>
                                                <td><?php if($shift['shift_category']==1) { echo "Day"; } else if($shift['shift_category']==2) { echo "Night"; } ?></td>
                                                <td><?php echo $shift['targeted_hours']; ?></td>
                                                <td><?php echo $shift['unpaid_break_hours']; ?></td>
                                                <td><?php echo $shift['total_targeted_hours']; ?></td>
                                                <td><?php if($shift['part_number']==0) { echo "No"; } else { echo "Yes"; } ?></td>
                                                <td><?php if($shift['status']==1) { echo "Active"; } else { echo "Inactive";} ?></td>
                                                <td>
                                                    <a class="Edit" data-id="<?php echo $shift['id']; ?>" href="<?php echo base_url("admin/shift/editshift/").$shift['id'];?>" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <?php if( $shift['id']!=1 && $shift['id']!=2 && $shift['id']!=3 && $shift['id']!=4 && $shift['id']!=93){?>  
                                                    <a class="Delete" data-id="<?php  $shift['id']; ?>" title="Delete" href="javascript:void(0);" onclick="deleteFunction('<?php echo $shift['id']; ?>','<?php echo $shift['shift_name']; ?>')" ><i class="fas fa-trash"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>  
                                            <?php } } ?>

                                    <?php
                                    }
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