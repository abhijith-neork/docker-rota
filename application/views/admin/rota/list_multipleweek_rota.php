<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Approve/Reject Shifts</h3>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <button style="margin-right:16px;" class="btn waves-effect float-right waves-light btn-info perform_all" id="<?php echo $user_id; ?>_<?php echo $unit_id; ?>_<?php echo $from_unit; ?>_1">Approve All
                        </button>
                        <button  style="margin-right:16px;" class="btn waves-effect float-right waves-light btn-danger perform_all" id="<?php echo $user_id; ?>_<?php echo $unit_id; ?>_<?php echo $from_unit; ?>_2">Reject All
                        </button>
                          <input type="hidden" id="rota_ids" name="custId" value="<?php echo $rota_id; ?>"> 
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

                                    <?php $count = count($user_details) ?>
                                    <?php $j = 1 ?>
                                    <?php 
                                        for($i=0;$i<$count;$i++)
                                    { ?>
                                        <p style="text-align:center;font-weight:normal;font-family:Rubik, sans-serif !important;">Week <?php echo $j++ ?> </p>
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr> 
                                                <th>Shift Name</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $week_count = count($user_details[$i]['Week']) ?>
                                            <?php $shift_data = $user_details[$i]['Week'] ?>
                                            <?php 
                                            for($k=0;$k<$week_count;$k++)
                                            { ?> 
                                                <tr>
                                                    <?php if($shift_data[$i]['shift_id'] != 0):?>
                                                        <td><?php echo findshift($shift_data[$k]['shift_id']);?></td>
                                                    <?php endif;?>
                                                    <?php if($shift_data[$i]['shift_id'] != 0):?>
                                                        <td><?php echo corectDateFormat($shift_data[$k]['date']); ?></td>
                                                    <?php endif;?>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <button class="btn waves-effect waves-light btn-info shift_action" id="<?php echo $user_id; ?>_<?php echo $unit_id; ?>_<?php echo $from_unit; ?>_<?php echo $shift_data[0]['rota_id'];?>_1">Approve
                                    </button>
                                    <button  class="btn waves-effect waves-light btn-danger shift_action" id="<?php echo $user_id; ?>_<?php echo $unit_id; ?>_<?php echo $from_unit; ?>_<?php echo $shift_data[0]['rota_id'];?>_2">Reject
                                    </button>
                                    <?php
                                    }
                                    ?>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>