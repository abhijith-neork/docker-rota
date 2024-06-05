<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Manage Notifications</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')==3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Manage Notifications</li>
                        </ol>
                    </div>
                    <div class="col-md-3 col-4 align-self-center">
                    </div>
                    <div class="col-md-3 col-4 align-self-center">
                        <button class="btn float-right hidden-sm-down btn-success"
                            onclick="location.href='<?php echo base_url();?>manager/notes/addNote'">                           
                            <i class="mdi mdi-plus-circle"></i> Add Notifications
                        </button>
                    </div>
                </div>
                 <div class="row"> 
                    <div class="col-12"> 
                            <?php if($this->session->flashdata('message')):?>
            <p  class="success-msg" id="success-alert" style="color: green;text-align: center;">
              <?php echo $this->session->flashdata('message');?>
            </p>
            <?php endif;?>  
                        <div class="card"> 
                   
                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>  
                                                <th>Title</th> 
                                                <th>Description</th> 
                                                <th>Notification Type</th> 
                                                <th>Date & Time</th> 
                                                <th>Added By</th>
                                                <th>Action</th> 
                                            </tr>
                                        </thead>
                                        <!-- <tbody>
                                            <?php  
                                            foreach($notes as $note)
                                            { ?> 
                                                <tr> 
                                                    <td> <a href="<?php echo base_url("manager/notes/editNote/").$note['id'];?>"><?php echo $note['title']; ?></td> 
                                                    <td><?php echo $note['comment']; ?></td>
                                                    <td><?php if($note['notification_type']==1) { echo "Both (SMS & Mail)"; } else if($note['notification_type']==2){ echo "SMS";} else { echo "Mail"; } ?></td>
                                                    <td data-order="<?php echo $note['creation_date'];?>"><span style="display: none;"><?php echo $note['creation_date']; ?></span><?php echo date("d/m/Y H:i:s",strtotime($note['creation_date'])); ?></td>  
                                                    <?php $user=getupdatedusernote($note['updated_userid']);?> 
                                                    <td><?php echo $user[0]['fname']." ".$user[0]['lname'];?></td> 
                                                    <td>
                                                         <center><a class="View"  data-id="<?php echo $note['id']; ?>" href="<?php echo base_url("manager/notes/editNote/").$note['id'];?>" title="View"><i class="fas fa-eye"></i></a> </center>
 
                                                    </td>
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