<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
            <div class="card-body">
                <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="text-themecolor mb-0 mt-0">Bank Holiday</h3>
                        <ol class="breadcrumb">
                            <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php }?>
                            <li class="breadcrumb-item"><a
                            href="<?php echo base_url();?>admin/Bankholiday/index">Bank Holidays</a></li>
                            <li class="breadcrumb-item active">Add Holidays</li>
                        </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">  
                    </div>
                </div>
                <?php if( $error!=''){?>
                    <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
                <?php } ?>
                <?php if( $success!=''){?>
                    <div class="alert alert-success" role="alert"><?php echo $success;?></div>
                <?php } ?>
                <form enctype="multipart/form-data" name="add_holiday" id="add_holiday" method="post" action="<?php echo base_url('admin/Bankholiday/');?>saveHolidays" style="width: 100%;">  
                    <div class="row">
                        <div class="col-md-6" style="padding-left: 36px">
                            <div class="form-group">
                                <label for="Location">Date</label> 
                                <input class="form-control" autocomplete="off" required type="text"  id="from-datepicker" name="start_date" value="<?php echo $this->input->post('start_date'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
            
                    <div class="row" style="padding-left: 33px;"> 
                        <button type="button" onclick="location.href='<?php echo base_url('admin/Bankholiday/index');?>';"  class="btn waves-effect waves-light btn-secondary" id="cancel" style="width:150px;">Cancel</button>&nbsp&nbsp
                    <button type="submit" class="btn btn-success mr-2" style="width:150px;">Submit</button>

                    </div>
                </form> 
            </div>
        </div>
    </div>
</div>