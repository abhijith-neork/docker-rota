<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Approve/Reject Shifts</h3>
                    </div>
                    <div class="col-md-3 col-4 align-self-center">
                    </div>
                </div>
                 <div class="row"> 
                    <div class="col-12">                          
                        <h5 style="padding-left: 46px;"><?php echo $unit_name;?></h5>
                        <div class="card"> 
                            <div class="card-body">
                                <?php if($this->session->flashdata('message')):?>
                                    <p class="success-msg" id="success-alert" style="color: green; text-align:center; ">
                                        <?php echo $this->session->flashdata('message');?>
                                    </p>
                                <?php endif;?>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr> 
                                                <th>Shift Name</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $count = count($user_details) ?>
                                            <?php 
                                            for($i=0;$i<$count;$i++)
                                            { ?> 
                                                <tr>
                                                    <?php if($user_details[$i]['shift_id'] != 0):?>
                                                        <td><?php echo $user_details[$i]['shift_name']; ?></td>
                                                    <?php endif;?>
                                                    <?php if($user_details[$i]['shift_id'] != 0):?>
                                                        <td><?php echo $user_details[$i]['date']; ?></td>
                                                    <?php endif;?>
                                                </tr>  
                                            <?php
                                            }
                                            ?> 
                                        </tbody>
                                    </table>
                                    <button  class="btn waves-effect waves-light btn-info approve" id="<?php echo $user_id; ?>_<?php echo $unit_id; ?>_<?php echo $from_unit; ?>">Approve
                                    </button>
                                    <button  class="btn waves-effect waves-light btn-danger reject" id="<?php echo $user_id; ?>_<?php echo $unit_id; ?>_<?php echo $from_unit; ?>">Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>