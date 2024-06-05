<div class="page-wrapper">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<div class="row page-titles">
					<div class="col-md-6 col-8 align-self-center">
						<h3 class="text-themecolor mb-0 mt-0">Manage Job Role Group</h3>
						<ol class="breadcrumb">
							<?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                             <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                             <?php } elseif($this->session->userdata('user_type')>=3) {?>
                             <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                             <?php }?>
							<li class="breadcrumb-item active">Manage Job Role Group</li>
						</ol>
					</div>
					<div class="col-md-6 col-4 align-self-center">

						<button class="btn float-right hidden-sm-down btn-success"
							onclick="location.href='<?php echo base_url();?>admin/JobRoleGroup/addjobrolegroup'">
							<i class="mdi mdi-plus-circle"></i> Add Job Role Group
						</button>
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
										 
												<th style="border: 1px solid #dee2e6">Group Name</th>
												<th style="border: 1px solid #dee2e6">Action</th>
											</tr>
										</thead>
										<tbody>
                                            <?php
                                            foreach ($group as $gp) {
                                            if($gp['status']!=3){ ?> 
                                            <tr>
												<td><a href="<?php echo base_url("admin/JobRoleGroup/editjobrolegroup/").$gp['id'];?>"><?php echo $gp['group_name']; ?></a></td>
												<td>
													<center>
														<a class="Edit" data-id="<?php echo $gp['id']; ?>" href="<?php echo base_url("admin/JobRoleGroup/editjobrolegroup/").$gp['id'];?>" title="Edit"><i class="fa fa-edit"></i></a>

														<!-- <a class="Delete" data-id="<?php  $gp['id']; ?>" title="Delete" href="javascript:void(0);" onclick="deleteFunction('<?php echo $gp['id']; ?>','<?php echo $gp['group_name']; ?>')" ><i class="fa fa-trash"></i></a>  -->
												    </center>
												</td>
											</tr>  

                                            <?php
                                            } }
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
 

