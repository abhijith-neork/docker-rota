<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">My Trainings</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser">Home</a></li>
                            <li class="breadcrumb-item active">My Trainings</li>
                        </ol>
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
                                                                <div class="card-body"><p id="selectTriggerFilter"></p>
                                                                    <div class="table-responsive m-t-40">
                                                                        <table id="myTable" class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <!-- <th style="border:1px solid #dee2e6">No</th> -->
                                                                                     <th style="border:1px solid #dee2e6">Date</th> 
                                                                                     <th style="border:1px solid #dee2e6">Title</th> 
                                                                                     <th style="border:1px solid #dee2e6">Description</th>
                                                                                     <th style="border:1px solid #dee2e6">From</th>
                                                                                     <th style="border:1px solid #dee2e6">To</th>
                                                                                     <th style="border:1px solid #dee2e6">Place</th>
                                                                                     <th style="border:1px solid #dee2e6">Point of Contact</th>
                                                                                     <th style="border:1px solid #dee2e6">Comments</th>
                                                                                     <!-- <th style="border:1px solid #dee2e6">Action</th> -->
                                                                                </tr>
                                                                            </thead>

                                                                           <tbody> 
                                                                                    <?php 
                                                                                    foreach($training as $desig)
                                                                                       
                                                                                    { 

$userfeedback = getfeedback($this->session->userdata('user_id'),$desig['training_id']);


                                                                                    ?> 
                                                                                    <tr>   
                                                                                        <td><?php echo date("d/m/Y",strtotime($desig['date_from'])); ?></td>                                                          
                                                                                        <td><?php echo $desig['title']; ?></td>
                                                                                        <td><?php echo $desig['description']; ?></td>
                                                                                        <td><?php echo $desig['time_from']; ?></td>
                                                                                        <td><?php echo $desig['time_to']; ?></td>
                                                                                        <td><?php echo $desig['place']; echo ","; print '<br>'; echo $desig['address'];?></td>
                                                                                        <td><?php echo $desig['point_of_person'];?></td>  
                                                                                        <td>
<?php   if(count($userfeedback)>0) { echo $userfeedback[0]['feedback']; }else{ ?>

                                                                                            <button data-id="<?php echo $desig['training_id']; ?>" class="btn waves-effect waves-light btn-info" id="feedback" onclick="editFeedback(<?php echo $desig['training_id']; ?>)" >
                                                     <i class="fa fa-edit"></i>&nbsp;Add 
                                                    </button>
                                                <?php }?>
                                                    
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