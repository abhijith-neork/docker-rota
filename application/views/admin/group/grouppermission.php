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
                                                                <li class="breadcrumb-item active"><a href="<?php echo base_url();?>admin/managegroup/">Manage Group</a></li>
                                                                <li class="breadcrumb-item active">Group Permission</li>
                                                            </ol>
                                                        </div> 
            </div>
            <?php if($this->session->flashdata('message')):?>
            <p class="success-msg" id="success-alert" style="color: green;">
              <?php echo $this->session->flashdata('message');?>
            </p>
            <?php endif;?> 
            <table id="permission_table" class="table">
                <thead>
                    <tr>
                        <th>Permission</th> 
                        <?php foreach ($matrix_roles as $matrix_role) : ?> 
                            <th><?php echo $matrix_role['group_name'];?><br>
                                <?php if($matrix_role['id']!=1) {?>
                                    <!-- <h6><input type="checkbox"  class="selectall" name="selectall" id="<?php echo $matrix_role['id'];?>">Select All</h6> -->
                                <?php }?></th>
                            <?php $cols[] = array('id' => $matrix_role['id'], 'group_name' => $matrix_role['group_name']);?>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <?php $current_user=$this->session->userdata('user_id'); ?>
                <tbody>
                    <?php foreach ($matrix_permissions as $matrix_perm) : ?>
                        <?php $matrix_perm = (array) $matrix_perm; ?>
                          
                        <?php if (has_permission($matrix_perm['name']) || $current_user== 1): //Admin?>
                            <tr title="<?php echo $matrix_perm['name']; ?>">
                                <td><?php echo $matrix_perm['name']; ?></td>
                                <?php
                                for ($i = 0; $i < count($cols); $i++) :
                                  $checkbox_value = $cols[$i]['id'] . ',' . $matrix_perm['id'];  
                                  $checked = in_array($checkbox_value, $matrix_role_permissions) ? ' checked="checked"' : '';
                                    ?>
                                    <td title="<?php echo $cols[$i]['group_name']; ?>">
                                        <!--    starts conditionally disable -->
                                        <?php if($cols[$i]['id'] == 1) :   ?>
                                            <?php $disable = "disabled='true'"?>
                                        <?php endif;    ?> 
                                        <?php if($matrix_perm['name'] == 'Admin.contracthours.View') :   ?>
                                            <?php $disable = "disabled='true'"?>
                                        <?php endif;    ?> 
                                        <input type="checkbox" class="checkItem group_<?php echo  $cols[$i]['id'];?>" <?php echo $disable; ?> value="<?php echo $checkbox_value; ?>"<?php echo $checked; ?> title="Role: <?php echo $cols[$i]['group_name']; ?>, Permission: <?php echo $matrix_perm['name']; ?>" />
                                        <?php $disable = '';    ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endif; ?>

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
            if(isActive)
                return;
            isActive = true;
           
            if($(this).attr('checked'))
            {
                var action = 1;
            }
            else
            {
                 var action = 2;
            } 
            $.ajax({type: "POST",
                url:"<?php echo $this->config->item('base_url'); ?>admin/Group/update",
                data: { id: $(this).val(), flag: Math.random(), action: action },
                success: function(){
                    isActive = false;
                }});
        });
       
    });
</script>
