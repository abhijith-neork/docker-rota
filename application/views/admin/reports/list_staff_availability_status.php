<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Availability tracker</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/reports/staffavailabilityreport">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item active">Availability tracker</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center"></div>
                </div>
                <form method="POST" action="<?php echo base_url();?>admin/Reports/showAvailabilityList" id="frmlistlivestatus" name="frmlistlivestatus">
                    <div class="row">
                        <div class="col-2">
                        </div>
                        <div class="col-2">
                        </div>
                        <div class="col-2">
                        </div>
                        <div class="col-2">
                        </div>
                        <div class="col-2">
                            <input class="form-control" autocomplete="off"  type="text"  required placeholder="--select date--" id="date_filter" name="date_filter" value="<?php echo $date; ?>">
                        </div>
                        <div class="col-2">
                            <button type="button" class="back btn-publish btn btn-primary float-right" id="back">Back</button>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive m-t-40">
                                        <div>
                                        </div>
                                        <table id="myTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>From Unit</th>
                                                    <th>To Unit</th>
                                                    <th>Shift Name</th> 
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($requests as $request) { ?>
                                                    <tr>
                                                        <?php if ($request['from_unitid']) { ?>
                                                            <td><?php echo findUnitName($request['from_unitid']);?></td>
                                                        <?php } else { ?>
                                                            <td><?php echo findUnitName($request['unit_id']);?></td>
                                                        <?php } ?>
                                                        <td><?php echo $request['unit_name'];?></td>
                                                        <td><?php echo $request['shift_name'];?></td>
                                                        <td><?php echo corectDateFormat($request['date']);?></td>
                                                        <td>
                                                            <?php echo showUserStatus($request['available_requestid']);?>
                                                                
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
                </form>
            </div>
        </div>                                 
    </div>
</div>            