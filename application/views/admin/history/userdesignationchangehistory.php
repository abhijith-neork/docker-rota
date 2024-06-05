<div class="page-wrapper">
        <div class="container-fluid"> 
            <div class="card">
            <div class="card-body">
            <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Reports</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item active">User Designation Change History</li>
                                                            </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">  
                                                           
                                                           
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
                                	<h6 class="card-subtitle" style="padding-left: 24px;">Export data to Copy, Excel, PDF & Print</h6> 
                                    <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                    	                            <th>User Id</th>
                                                                                    <th>Name</th> 
                                                                                    <th>Previous Designation</th>
                                                                                    <th>Current Designation</th> 
                                                                                    <th>Updation Date</th> 
                                                                                    <th>Updated By</th>
                                                    </tr>
                                                </thead>
                                                <!-- <tbody> 
                                                    <?php 
                                                            foreach($user as $use) 
                                                    { ?> 
                                                    <tr>
                                                        <?php $old_desig=getDesignationold($use['Previous_designation']);
                                                              $new_desig=getDesignationold($use['Current_designation']);
                                                              $updated_user=getUpdateduser($use['Updated_by']);
                                                        ?>
                                                    	                            <td><?php echo $use['User_id'];?></td>  
                                                                                    <td><?php echo $use['fname']." ".$use['lname'];?></td>
                                                                                    <td><?php echo $old_desig[0]['designation_name'];?></td>
                                                                                    <td><?php echo $new_desig[0]['designation_name'];?></td>
                                                                                    <td><span style="display: none;"><?php echo $use['Updation_date']; ?></span><?php echo date("d/m/Y  H:i:s",strtotime($use['Updation_date']));?></td>
                                                                                    <td><?php echo $updated_user[0]['fname'].' '.$updated_user[0]['lname'];?></td>
                                                                                    
                                                                                     
                                                    </tr> 

                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                </tbody> -->
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
 