<!-- daily censes
 --><div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body"> 
             <div class="row page-titles">
                            <div class="col-md-6 col-8 align-self-center">
                                <h3 class="text-themecolor mb-0 mt-0">Daily Census</h3>
                                <ol class="breadcrumb">
                                    <?php if($this->session->userdata('user_type')==1){?>
                                     <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                     <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                     <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                     <?php }?>
                                    <li class="breadcrumb-item active">Daily Census</li>
                                </ol>
                            </div>
                            <div class="col-md-6 col-4 align-self-center">
                               <button class="btn float-right hidden-sm-down btn-success" 
                               onclick="location.href='<?php echo base_url();?>admin/Dailysenses/addsenses'">
                                </i> Add Daily Census</button> 
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

<form method="POST" action=" " id="frmViewdailysenses"   name="frmViewdailysenses">
<div class="row">
<div class="col-md-2" style="padding-left: 36px">
<div class="form-group"> 
    <label for="start_date">From date</label> 
    <?php if($this->input->post('start_date')==''){?>
      <input type="text"  class="form-control required" id="start_date" autocomplete="off" placeholder="dd/mm/yy" name="start_date" value="<?php echo $start_date; ?>"> 
    <?php } else {?>
      <input type="text"  class="form-control required" id="start_date" autocomplete="off" placeholder="dd/mm/yy" name="start_date" value="<?php echo $this->input->post('start_date'); ?>"> 
    <?php } ?>
</div>
</div>

<div class="col-md-2" style="padding-right: 36px;">
<div class="form-group"> 
    <label for="end_date">To date</label> 

    <?php if($this->input->post('end_date')==''){?>
      <input type="text" class="form-control required" id="end_date" autocomplete="off" placeholder="dd/mm/yy"  name="end_date" value="<?php echo $end_date; ?>"> 
    <?php } else {?>
      <input type="text" class="form-control required" id="end_date" autocomplete="off" placeholder="dd/mm/yy"  name="end_date" value="<?php echo $this->input->post('end_date'); ?>"> 
    <?php } ?>
</div>
</div>
<div class="col-md-2">
<div class="form-group" style="padding-right: 90px;padding-top: 30px;width: 200px;">
 <button type="submit" class="search btn float-right hidden-sm-down btn-success" id="search" style="width: 100px;">Search</button> 
                
</div>
  
</div>
</div>


<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body"> 
                                        <div class="table-responsive m-t-40">
                                            <table id="myTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <!-- <th style="border:1px solid #dee2e6">No</th> --> 
                                                        <th style="border:1px solid #dee2e6">Unit</th>
                                                        <th style="border:1px solid #dee2e6">Name</th>
                                                        <th style="border:1px solid #dee2e6">Date</th>
                                                        <th style="border:1px solid #dee2e6">Comment<br> </th>
                                                        <th style="border:1px solid #dee2e6">Created At<br> </th>
                                                        <th style="border:1px solid #dee2e6">Created By<br> </th>
                                                        <th style="border:1px solid #dee2e6">Action<br> </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <?php 
                                                        foreach($user as $use) { ?> 
                                                            <?php if($use['status']==1){?>
                                                        <tr>                                                             
                                                          
                                                            <td><?php echo $use['unit_name']; ?></td>
                                                            <td><?php echo $use['fname'].' '.$use['lname']; ?></td>
                                                            <?php if($use['date']!='')
                                                            {?>
                                                               <td><span style="display: none;"><?php echo date("Y-m-d",strtotime($use['date'])); ?></span><?php echo date("d/m/Y ",strtotime($use['date'])); ?></td>
                                                            <?php } else {?>
                                                            <td><span style="display: none;"><?php echo date("Y-m-d",strtotime($use['date'])); ?></span><?php echo $use['date']; ?></td>
                                                            <?php }?>
                                                            <td><?php echo $use['comment']; ?></td>
                                                            <td><span style="display: none;"><?php echo $use['creation_date']; ?></span><?php echo date("d/m/Y H:i:s",strtotime($use['creation_date'])); ?></td>
                                                            <td><?php  $user=getCreator($use['created_userid']); print_r($user); ?></td>
                                                            
                                                            <td><center>
                                                                <a <?php buttonaccess('Admin.Dailycensus.Edit'); ?> class="Edit" data-id="<?php echo $use['id']; ?>" href="<?php echo base_url("admin/dailysenses/editsenses/").$use['id'];?>" title="Edit"><i class="fa fa-edit"></i></a>
                                                                <?php if ($this->session->userdata('user_type')==1){?>
                                                                <a <?php buttonaccess('Admin.Dailycensus.Delete'); ?> class="Delete" data-id="<?php  $use['id']; ?>" title="Delete" href="javascript:void(0);" onclick="deleteFunction('<?php echo $use['id']; ?>','<?php echo $use['id']; ?>')" ><i class="fa fa-trash"></i></a>  <?php }?></center></td>
                                                            
                                                           
                                                        </tr>  
                                                    <?php }?>

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
            </form>
    </div>
</div>
</div>
</div>