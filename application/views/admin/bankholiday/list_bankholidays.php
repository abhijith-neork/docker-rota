<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
            <div class="card-body"> 
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Bank Holidays</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                             <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>                         
                             <?php }?>
                            <li class="breadcrumb-item active">Bank Holidays</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                       <button class="btn float-right hidden-sm-down btn-success" onclick="location.href='<?php echo base_url();?>admin/Bankholiday/addHolidays'">
                        </i> Add Bank Holidays</button> 
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
                                                <th style="border:1px solid #dee2e6">Id</th>
                                                <th style="border:1px solid #dee2e6">Date</th>
                                                <th style="border:1px solid #dee2e6">Created Date<br> </th>
                                                <th style="border:1px solid #dee2e6">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php foreach($holidays as $holiday) { ?> 
                                                <tr> 
                                                    <td><?php echo $holiday['id']; ?></td>
                                                    <td><span style="display: none;"><?php echo $holiday['date']; ?></span><?php echo corectDateFormat($holiday['date']); ?></td>
                                                    <td><span style="display: none;"><?php echo $holiday['created_date']; ?></span><?php echo date("d/m/Y H:i:s",strtotime($holiday['created_date'])); ?></td>
                                                    <td>
                                                        <a class="Edit" data-id="<?php echo $holiday['id']; ?>" href="<?php echo base_url("admin/Bankholiday/editHoliday/").$holiday['id'];?>" title="Edit"><i class="fas fa-edit"></i></a>
                                                        <a class="Delete" data-id="<?php  $holiday['id']; ?>" title="Delete" href="javascript:void(0);" onclick="deleteFunction('<?php echo $holiday['id']; ?>')"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>  
                                            <?php  } ?>
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