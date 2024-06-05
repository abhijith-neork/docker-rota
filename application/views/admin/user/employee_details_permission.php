<div class="page-wrapper">
    <div class="container-fluid"> 
        <div class="card">
        <div class="card-body"> 
            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor mb-0 mt-0">Add Group Permission</h3>
                    <ol class="breadcrumb">
                        <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                         <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                         <?php } elseif($this->session->userdata('user_type')>=3) {?>
                         <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                         <?php }?>
                        <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/manageuser/">Manage Users</a></li>
                        <li class="breadcrumb-item active">Edit Permission</li>
                    </ol>
                </div> 
            </div>
            <?php if($this->session->flashdata('message')):?>
            <p class="success-msg" id="success-alert" style="color: green;">
              <?php echo $this->session->flashdata('message');?>
            </p>
            <?php endif;?> 
            <table id="permission_table_user" class="table">
                <thead>
                    <tr>
                        <th>Permission</th> 
                        <th>Administrator</th>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach ($personal_details_fields as $personal_details_field) : ?>
	                    <tr title="<?php echo $personal_details_field['label']; ?>">
	                        <td><?php echo $personal_details_field['label']; ?></td>
	                        <td><input  <?php if($personal_details_field['status'] == 1) { ?> checked="checked"  <?php } ?> type="checkbox" name="checkbox_<?php echo $personal_details_field['field_name']; ?>" value="<?php echo $personal_details_field['id']; ?>"></td>
	                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script>
    var isActive = false;
    $(document).ready(function(){
        $('input[type=checkbox]').click(function(){
        	action = 0;
            if(isActive)
                return;
            isActive = true;
            if ($(this).prop('checked')==true)
            {
                action = 1;
            }
            $.ajax({type: "POST",
                url:"<?php echo $this->config->item('base_url'); ?>admin/User/edit_permission",
                data: { id: $(this).val(), flag: Math.random(), action: action },
                success: function(){
                    isActive = false;
            	}
        	});
        });
    });
</script>