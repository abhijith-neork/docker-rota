<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<div class="row page-titles">
					<div class="col-md-6 col-8 align-self-center">
						<h3 class="text-themecolor mb-0 mt-0">Edit IP Address</h3>
						<ol class="breadcrumb">
							<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                            <?php } elseif($this->session->userdata('user_type')>=3) {?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                            <?php }?>
							<li class="breadcrumb-item active"><a
								href="<?php echo base_url();?>admin/user/ipaddresses">Manage IP Addresses</a></li>
							<li class="breadcrumb-item active">Edit IP Address</li>
						</ol>
					</div>
				</div>
            <?php if( $error!=''){?>
            <div class="alert alert-danger" role="alert"><?php echo $error;?></div>
            <?php } ?>
            <?php if( $message!=''){?>
            <div class="alert alert-success" role="alert"><?php echo $message;?></div>
            <?php } ?>
            <?php if (validation_errors()){?>
            <div class="alert alert-danger" role="alert"><?php echo validation_errors();?></div>
            <?php } ?>    
            <div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('admin/User/');?>updateipaddress" style="width: 50%;">
									<div class="form-group">
										<label for="ip_address">IP Address</label> <input type="text"
											class="form-control ip_address" id="ip_address"  required="required"  name="ip_address"
											placeholder="Enter IP address" value="<?php echo $ip_details[0]['ip_address']; ?>"  >
											<input type="hidden" name="ip_id" value="<?php echo $ip_details[0]['id']; ?>" id="ip_id">
									</div>
									<button type="button"
										onclick="location.href='<?php echo base_url('admin/user/ipaddresses');?>';"
										class="btn waves-effect waves-light btn-secondary" id="cancel">Cancel</button>
									&nbsp;
									<button type="submit" class="btn btn-primary" id="try">Edit</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/scripts/jquery.min.js"></script>