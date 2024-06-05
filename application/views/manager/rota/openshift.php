<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Reports</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/manageuser">Home</a></li>
                            <li class="breadcrumb-item active">Openshift</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">  
                    </div>
                </div> 
                <form method="#" id="frmshft" name="frmshft">
                    <div class="row"> 
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive m-t-40">
                                        <table id="myTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    
                                                    <th>Unit Name</th>
                                                    <th>Shift Name</th> 
                                                    <th>Shift Start Time</th>
                                                    <th>Shift End Time</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php if(count($unit_data) > 0 && count($shift_data) > 0):?>
                                                       <td><?php echo $unit_data[0]['unit_name'];?></td>
                                                       <td><?php echo $shift_data[0]['shift_name'];?></td>
                                                       <td><?php echo $shift_data[0]['start_time'];?></td>
                                                       <td><?php echo $shift_data[0]['end_time'];?></td>
                                                       <td><?php echo $date;?></td>
                                                       <td><button type="button" class="btn btn-success btn-accept" id="1">Accept</button> <button type="button" class="btn btn-success btn-reject" id="2">Reject</button></td>
                                                       <input type="hidden" id="shift_id" name="shift_id" value="<?php echo $shift_data[0]['id'];?>">
                                                       <input type="hidden" id="unit_id" name="unit_id" value="<?php echo $unit_data[0]['id'];?>">
                                                       <input type="hidden" id="date" name="date" value="<?php echo $date;?>">
                                                       <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id;?>">
                                                       <input type="hidden" id="user_unit_id" name="user_id" value="<?php echo $user_unit_id;?>">
                                                    <?php endif; ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>                                 
    </div>
</div>