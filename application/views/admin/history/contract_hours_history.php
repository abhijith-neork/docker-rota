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
                            <li class="breadcrumb-item active">Contract Hours Change History</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">  
                    </div>
                </div>  
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                    <table id="contactHoursTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Previous Contracted Hours</th>
                                            <th>Current Hours</th>
                                            <th>Updated By</th>
                                            <th>Updated Date</th>
                                        </tr>
                                    </thead>
                                    <!-- <tbody> 
                                        <?php 
                                        foreach($user as $use) 
                                        { ?> 
                                            <tr>
                                                <td><?php echo $use['user_id'];?></td>
                                                <td><?php echo $use['fname']." ".$use['lname'];?></td>
                                                <td><?php echo $use['previous_contracted_hours'];?></td>
                                                <td><?php echo $use['updated_contracted_hours'];?></td>
                                                <td><?php $user=getCreator($use['updated_userid']); print_r($user); ?></td>
                                                <td><?php echo date("d/m/Y H:i:s",strtotime($use['updation_date'])); ?></td>
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