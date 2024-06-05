<div class="page-wrapper">
   <div class="container-fluid"> 
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Annual Leave Requests</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/holliday">Home</a></li>
                            <li class="breadcrumb-item active">Annual Leave Requests</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center"> 
                        <!--  <button class="btn float-right hidden-sm-down btn-success" 
                                                           onclick="location.href='<?php echo base_url();?>admin/User/addUser'">
                                                            </i> Add User</button>  -->
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
                <?php if($this->session->flashdata('error')):?>
                    <p class="success-msg" id="success-alert"
                            style="color: red; text-align:center;">
                      <?php echo $this->session->flashdata('error');?>
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
                                                <th>Name</th> 
                                                <th>Unit</th>
                                                <th>From Date</th> 
                                                <th>To Date</th> 
                                                <th>Number Of Days</th> 
                                                <th>Applied Date&Time</th> 
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php 
                                                foreach($user as $use) 
                                            { ?> 
                                            <tr>
                                                <td><?php echo $use['fname']." ".$use['lname'];?></td> 
                                                <td><?php echo $use['unit_name'];?></td>
                                                <td><?php echo $use['from_date'];?> <?php echo $use['start_time'];?></td>  
                                                <td><?php echo $use['to_date'];?> <?php echo $use['end_time'];?></td> 
                                                <td><?php echo $use['days'];?></td>
                                                <td><?php echo date("d/m/Y H:i:s",strtotime($use['creation_date'])); ?></td>
                                                <td><?php if($use['status']==1) { echo "Approved"; } else if($use['status']==2){ echo "Rejected";} else {echo "Pending";} ?></td>
                                                <td>
                                                    <button id="approve" data-id="<?php echo $use['id']; ?>" class="btn waves-effect waves-light btn-info" onclick="approveFunction(<?php echo $use['id']; ?>)" value="<?php echo $use['user_id'];?>">
                                                    <i class="fa fa-check-circle"></i>&nbsp;Approve 
                                                    </button>
                                                    <button data-id="<?php  $use['id']; ?>" onclick="rejectFunction('<?php echo $use['id']; ?>','<?php echo $use['fname']; ?>')" id="reject" class="btn waves-effect waves-light btn-danger" value="<?php echo $use['user_id'];?>">
                                                        <i class="fa fa-ban"></i>&nbsp;Reject 
                                                    </button>
                                                    <button type="button" class="btn waves-effect waves-light btn-info" data-container="body" title="Annual leave messages" data-html="true" data-toggle="popover" data-placement="right" data-content="<?php  $comments=getMessage($use['id']); 
                                                    foreach($comments as $msg){ 
                                                        if($msg['status']==1) echo "<b>Approved by</b> " . $msg['fname'].' '.$msg['lname'].':'.' ';   else echo "<b>Rejected by</b> " . $msg['fname'].' '.$msg['lname'].':'.' '; 
                                                    echo ($msg['comment'].','.$msg['date']);  
                                                    echo '<br><br>';                                              
                                                } 
                                                    ?> ">
                                                        Messages
                                                    </button>
                                                    <input type = "hidden" id="user_id" value = "<?php echo $use['user_id'];?>"/>
                                                </td>
                                            </tr>
                                            <?php } ?>                                                                                    
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
 