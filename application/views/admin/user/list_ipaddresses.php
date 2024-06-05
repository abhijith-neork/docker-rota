<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Manage IP Address</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Manage IP Addresses</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success"
                            onclick="location.href='<?php echo base_url();?>admin/User/addipaddress'">
                            <i class="mdi mdi-plus-circle"></i> Add IP Address
                        </button> 
                    </div>
                </div>
                <?php if ($this->session->flashdata('message')): ?>
                    <div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('message'); ?></div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                   <table id="ipaddress_table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="border:1px solid #dee2e6">IP Address</th>
                                                <th style="border:1px solid #dee2e6">Creation Date</th>
                                                <th style="border:1px solid #dee2e6">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            foreach($ipaddresses as $ipaddress) { 
                                            ?> 
                                            <tr>
                                                <td><?php echo $ipaddress['ip_address']; ?></td>
                                                <td><?php echo date('d/m/Y H:i:s', strtotime($ipaddress['created_at'])); ?></td>
                                                <td>
                                                    <?php $encrypted_id = $encoded_id = base64_encode($ipaddress['id']); ?>
                                                    <a class="Edit" data-id="<?php echo $encrypted_id; ?>" href="<?php echo base_url("admin/user/editipaddress/").$encrypted_id;?>" title="Edit"><i class="fas fa-edit"></i></a>
                                                    <a class="Delete" data-id="<?php echo $encrypted_id; ?>" title="Delete" href="<?php echo base_url("admin/user/deleteipaddress/").$encrypted_id;?>" onclick="return confirm('Are you sure you want to delete this IP address?');"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
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