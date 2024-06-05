<style>
   #img-loader-data{
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('../../../assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}
</style>
<div class="loader" id="img-loader-data" style="display:none;"></div>
<div class="page-wrapper">
        <div class="container-fluid"> 
            <div class="row page-titles">
                    <div class="col-md-6 col-8 align-self-center">
                                                            <h3 class="text-themecolor mb-0 mt-0">Edit Users</h3>
                                                            <ol class="breadcrumb">
                                                                <?php if(in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/Dashboard/index">Home</a></li>
                                                                 <?php } elseif($this->session->userdata('user_type')>=3) {?>
                                                                 <li class="breadcrumb-item"><a href="<?php echo base_url();?>manager/editprofile">Home</a></li>
                                                                 <?php }?>
                                                                <li class="breadcrumb-item"><a href="<?php echo base_url();?>/admin/manageuser">Manage Users</a></li>
                                                                  <li class="breadcrumb-item active"><?php echo $user[0]['fname']." ".$user[0]['lname'];?></li>
                                                            </ol>
                    </div>
                    <div class="col-md-6 col-4 align-self-center"> 
                       
                                                           
                                                           
                    </div>
            </div>
        
 <div class="row" id="validation">
   <!--  <?php if($responce = $this->session->flashdata('Successfully')): ?> 
        
           <div class="alert alert-success"><?php echo $responce;?>
           </div> 
        <?php endif;?> -->
                    <div class="col-12">
                        <div  class="card wizard-content">
                            <div class="card-body">
                                <h4 class="card-title">User Details</h4>
                                <h6 class="card-subtitle">Update User Details</h6>
                                <?php
            if($this->session->flashdata('error')==1)
                $color="red";
            else 
                $color="green";
            
            if($this->session->flashdata('Successfully')):?>
            <p class="alert alert-success" id="alert-success"
                    style="color: <?php echo $color; ?>; text-align:center;">
              <?php echo $this->session->flashdata('Successfully');?>
            </p>
            <?php endif;?>  
             <p class="" id="msgalert" style="text-align:center;"></p>
             <p class="" id="msgerroralert" style="text-align:center;"></p>

                                <form action="#" class="validation-wizard wizard-circle">
                                    <!-- Step 1 -->
                                    <input type="hidden" name="id" id="id" value="<?php echo $user[0]['id'];?>">
                                    <input type="hidden" name="unit_id" id="unit_id" value="<?php echo $unitbyuser[0]['unit_id'];?>">
                                    <h6>Personal Details</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wfirstName2"> First name : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(1,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required " id="wfirstName2"
                                                        name="firstName" value="<?php echo $user[0]['fname'];?>" > 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wlastName2"> Last name : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(2,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="wlastName2"
                                                        name="lastName" value="<?php echo $user[0]['lname'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wemailAddress2"> Email address : <span
                                                            class="danger">*</span> </label>
                                                    <?php 
                                                        $status = get_permission_status(3,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="email" class="form-control "
                                                        id="wemailAddress2" name="emailAddress required" value="<?php echo $user[0]['email'];?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                
                                                <div class="form-group">
                                                    <label for="wphoneNumber2">Phone number : <span class="danger">*</span></label> 
                                                    <?php 
                                                        $status = get_permission_status(4,$this->session->userdata('user_type'));
                                                    ?>
                                                        <input <?php if($status == 0) { ?> disabled  <?php } ?> type="tel" class="form-control required" id="wphoneNumber2" value="<?php echo $user[0]['mobile_number'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                           <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wdob2">Date of Birth:</label>
                                                    <?php 
                                                        $status = get_permission_status(5,$this->session->userdata('user_type'));
                                                    ?>
                                                    <?php if($user[0]['dob']==''){?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="from-datepicker" value="<?php echo $user[0]['dob'];?>">
                                                    <?php }else{?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="from-datepicker" value="<?php echo date("d/m/Y",strtotime($user[0]['dob'])); ?>"> 
                                                     <?php }?>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label for="wgender2"> Gender : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(6,$this->session->userdata('user_type'));
                                                    ?>
                                                    <select <?php if($status == 0) { ?> disabled  <?php } ?>  class="custom-select form-control " id="wgender2"
                                                        name="wgender2">
                                                        <option value="M" <?php if(($user[0]['gender'])=="M"){?>  selected="selected" <?php }?> >Male</option> 
                                                        <option value="F"  <?php if(($user[0]['gender'])=="F"){?>  selected="selected" <?php }?> >Female</option>
                                                        <option value="O"  <?php if(($user[0]['gender'])=="O"){?>  selected="selected" <?php }?> >Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="waddress12"> Address 1 : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(7,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?>  type="text" class="form-control required" id="waddress12"
                                                        name="address1" value="<?php echo $user[0]['address1'];?>"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="waddress22"> Address 2 :
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(8,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control" id="waddress22"
                                                        name="address2" value="<?php echo $user[0]['address2'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                     <label for="wcountry2"> Country : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(10,$this->session->userdata('user_type'));
                                                    ?>
                                                     <select <?php if($status == 0) { ?> disabled  <?php } ?> class="custom-select form-control required" id="wcountry2"
                                                        name="wcountry2"> 
                                                        <option value="">Select country</option>
                                                         <?php
                                                        foreach ($country as $cou) {
                                                            ?>   
                                                                  <option value="<?php echo $cou['country']?>"<?php if(($user[0]['country'])==$cou['country']){?> selected="selected" <?php }?>><?php echo $cou['country']; ?></option> 
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wcity2"> City :  
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(9,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control" id="wcity2"
                                                        name="wcity2" value="<?php echo $user[0]['city'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wethnicity2"> Ethnicity : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(11,$this->session->userdata('user_type'));
                                                    ?>
                                                     <select <?php if($status == 0) { ?> disabled  <?php } ?> class="ethnicity custom-select form-control required" id="ethnicity" name="ethnicity"> 
                                                        <option value=""><?php echo "------Select ethnicity-------"?></option>
                                                        <?php
                                                        foreach ($ethnicity as $cou) { 
                                                            ?>    
                                                            <?php if($cou['parent']==0){?>
                                                              <option  disabled="disabled" style="font-size: 18px !important;" value="<?php echo $cou['id']; ?>"> <?php echo $cou['Ethnic_group']; ?></option> 
                                                            <?php } else { ?>
                                                              <option value="<?php echo $cou['id']?>"<?php if(($user[0]['ethnic_id'])==$cou['id']){?> selected="selected" <?php }?>><?php echo $cou['Ethnic_group']; ?></option>  
                                                            <?php } ?>
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <label for="wvisastatus2"> Visa Status : <span class="danger">*</span></label>
                                                <?php 
                                                        $status = get_permission_status(12,$this->session->userdata('user_type'));
                                                    ?>
                                                <select <?php if($status == 0) { ?> disabled  <?php } ?> class="form-control" name="visastatus" id="wvisastatus2" class="visastatus">
                                            <option value="0"  <?php if(($user[0]['visa_status'])=="0"){?>  selected="selected" <?php }?> >NA</option>
                                            <option value="1"  <?php if(($user[0]['visa_status'])=="1"){?>  selected="selected" <?php }?> >Migrant Worker</option>
                                            <option value="2"  <?php if(($user[0]['visa_status'])=="2"){?>  selected="selected" <?php }?> >Sponsored</option> 
                                        </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wpostcode2"> Postcode : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(30,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="wpostcode2"
                                                        name="postcode" value="<?php echo $user[0]['postcode'];?>"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <!-- <label for="wstatus2"> Status : <span class="danger">*</span></label>
                                                <select class="form-control status_change_select" name="status" id="wstatus2" class="status" data-id="<?php echo $user[0]['id'] ?>">
                                            <option value="1"  <?php if(($user[0]['status'])=="1"){?>  selected="selected" <?php }?> >Active</option>
                                            <option value="2"  <?php if(($user[0]['status'])=="2"){?>  selected="selected" <?php }?> >Inactive</option> 
                                        </select> -->
                                            </div>
                                        </div>
                            
                                         <fieldset> <legend>Next of kin:</legend>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wkin_name2">Name:<span class="danger">*</span></label>
                                                    <?php 
                                                        $status = get_permission_status(13,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="wkin_name2" name="wkin_name2" value="<?php echo $user[0]['kin_name'];?>"> 
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wkin_phone2">Phone number: <span class="danger">*</span></label>
                                                    <?php 
                                                        $status = get_permission_status(14,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="wkin_phone2" name="wkin_phone2"value="<?php echo $user[0]['kin_phone'];?>"> 
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wkin_address2">Address:<span class="danger">*</span></label>
                                                    <?php 
                                                        $status = get_permission_status(15,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="wkin_address2" name="wkin_address2" value="<?php echo $user[0]['kin_address'];?>"> 
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                              
                                            </div>
                                        </div>
                                        

                                        </fieldset><br>
                                        

                                    </section>
                                    <!-- Step 2 -->
                                     <h6>Employee Details</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wgroup2"> Select group : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(16,$this->session->userdata('user_type'));
                                                    ?>
                                                    <select <?php if($status == 0) { ?> disabled  <?php } ?> class="custom-select form-control required" id="wgroup2"
                                                        name="group">

                                                        <?php if($user[0]['group_id']==1 || in_array($this->session->userdata('user_type'),$this->config->item('group_id'))){?>
                                                            <?php
                                                            foreach ($group as $uni) {
                                                                ?>  
                                                                     <option value="<?php echo $uni['id']; ?>"<?php if(($user[0]['group_id'])==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['group_name']; ?></option> 
                                                            <?php } ?> 
                                                        <?php } else {?>
                                                            <?php
                                                            foreach ($groups as $uni) {
                                                                ?>  
                                                                     <option value="<?php echo $uni['id']; ?>"<?php if(($user[0]['group_id'])==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['group_name']; ?></option> 
                                                            <?php } ?>


                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="whours2"> Weekly hours (hh:mm) : <span
                                                            class="danger">*</span> </label>
                                                    <?php 
                                                        $status = get_permission_status(17,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required"
                                                        id="whours2" name="hours" placeholder="hh:mm" value="<?php echo $user[0]['weekly_hours'];?>"> </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wallowance2"> Annual Leave Allowance (hh.hh) : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(18,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text"  placeholder="00.00" class="form-control required" id="wallowance2"
                                                        name="allowance" style="width: 90%;" value="<?php echo $user[0]['annual_holliday_allowance'];?>"> 
                                                </div>
                                                <input type="hidden" id="prev_anual_allowance_value" value="<?php echo $user[0]['annual_holliday_allowance'];?>"/>
                                                <input type="hidden" id="prev_remaining_holiday_allowance" value="<?php echo $user[0]['remaining_holiday_allowance'];?>"/>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="wannual_allowance_type2"><?php echo "<br>"?></label>
                                                <div style="width: 80%;">
                                                   <?php if($user[0]['annual_allowance_type']>0)
                                                        $disable ='disabled="disabled"';
                                                      else
                                                        $disable=''
                                                    ?>
                                                     <select class="custom-select form-control required" id="wannual_allowance_type2"
                                                        name="annual_allowance_type" <?php echo $disable; ?>>
                                                        <!-- <option value="1"  <?php if(($user[0]['annual_allowance_type'])=="1"){?>  selected="selected"   <?php }?> >Days</option> -->
                                                        <option value="2"  <?php if(($user[0]['annual_allowance_type'])=="2"){?>  selected="selected" <?php }?> >Hours</option>
                                                     </select>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label for="wdesignation2"> Select job role : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(19,$this->session->userdata('user_type'));
                                                    ?>
                                                    <select <?php if($status == 0) { ?> disabled  <?php } ?> class="custom-select form-control required" id="wdesignation2"
                                                        name="designation">
                                                          <?php
                                                        foreach ($designation as $uni) {
                                                            ?>  
                                           <option value="<?php echo $uni['id']; ?>"<?php if(($user[0]['designation_id'])==$uni['id']){?> selected="selected" <?php }?>><?php echo $uni['designation_name']; ?></option>  
                                                        <?php } ?> 
                                                    </select>
                                                </div> 
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="wactualallowance2"> Annual Leave Entitlement (hh.hh) : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(20,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" placeholder="00.00" class="form-control required" id="wactualallowance2"
                                                        name="actualallowance" style="width: 90%;" value="<?php echo $user[0]['actual_holiday_allowance'];?>"> 
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="wactual_holiday_allowance_type2"><?php echo "<br>"?></label>
                                                <div style="width: 80%;">
                                                    <?php if($user[0]['annual_allowance_type']>0)
                                                        $disable2 ='disabled="disabled"';
                                                      else
                                                        $disable2=''
                                                    ?>
                                                     <select class="custom-select form-control required" id="wactual_holiday_allowance_type2"
                                                        name="wactual_holiday_allowance_type2" <?php echo $disable2; ?>>
                                                        <!-- <option value="1"  <?php if(($user[0]['actual_holiday_allowance_type'])=="1"){?>  selected="selected" <?php }?> >Days</option> -->
                                                        <option value="2"  <?php if(($user[0]['actual_holiday_allowance_type'])=="2"){?>  selected="selected" <?php }?> >Hours</option>
                                                     </select>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wshift2"> Select default shift : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(21,$this->session->userdata('user_type'));
                                                    ?>
                                                    <select <?php if($status == 0) { ?> disabled  <?php } ?> class="custom-select form-control required" id="wshift2"
                                                        name="shift">
                                                        <option value=''>---Select default shift---</option>
                                                          <?php
                                                        foreach ($shifts as $shift) {
                                                            ?>  
                                           <option value="<?php echo $shift['id']; ?>"<?php if(($user[0]['default_shift'])==$shift['id']){?> selected="selected" <?php }?>><?php echo $shift['shift_name']; ?></option>  
                                                        <?php } ?> 
                                                    </select>
                                                </div> 
                                            </div>
                                        </div> 
                                        <div class="row">
                                           <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wsdate2">Start date:<span class="danger">*</span></label>
                                                    <?php 
                                                        $status = get_permission_status(22,$this->session->userdata('user_type'));
                                                    ?>
                                                    <?php if($user[0]['start_date']=='' || $user[0]['start_date']=='0000-00-00'){?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="start-datepicker" value="<?php echo '00/00/0000';?>"> 
                                                    <?php }else{?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="start-datepicker" value="<?php echo date("d/m/Y",strtotime($user[0]['start_date']));?>">  
                                                     <?php }?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="whrID2">HR ID:</label>
                                                    <?php 
                                                        $status = get_permission_status(23,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control" id="whrID2" name="whrID2" value="<?php echo $user[0]['hr_ID'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wpayroll2">Payroll ID:<span class="danger">*</span></label>
                                                    <?php 
                                                        $status = get_permission_status(24,$this->session->userdata('user_type'));
                                                    ?>
                                                    <input <?php if($status == 0) { ?> disabled  <?php } ?> type="text" class="form-control required" id="wpayroll2" name="wpayroll2" value="<?php echo $user[0]['payroll_id'];?>"> 
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label for="wpayment2"> Select paymenttype : <span class="danger">*</span>
                                                    </label>
                                                    <?php 
                                                        $status = get_permission_status(25,$this->session->userdata('user_type'));
                                                    ?>
                                                    <select <?php if($status == 0) { ?> disabled  <?php } ?> class="custom-select form-control required" id="wpayment2"
                                                        name="payment">
                                                          <?php
                                                        foreach ($paymenttype as $payment) {
                                                            ?>  
                                           <option value="<?php echo $payment['id']; ?>"<?php if(($user[0]['payment_type'])==$payment['id']){?> selected="selected" <?php }?>><?php echo $payment['payment_type']; ?></option>  
                                                        <?php } ?> 
                                                    </select>
                                                </div> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wnotes2">Notes : </label>
                                                        <?php 
                                                            $status = get_permission_status(26,$this->session->userdata('user_type'));
                                                        ?>
                                                        <textarea <?php if($status == 0) { ?> disabled  <?php } ?> class="description form-control" id="wnotes2" name="notes" cols="40"  placeholder="Enter notes"><?php echo html_entity_decode($user[0]['notes']);?></textarea> 
                                                  
                                                 
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label for="wday_rate2">Basic pay rate:<span class="danger">*</span></label>
                                                    <input type="text" class="form-control required" id="wday_rate2" name="wday_rate2" value="<?php echo $user_rates[0]['day_rate'];?>"> 
                                                </div>
                                                
                                            </div>
                                        </div>
 
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="wexit_interview2"> Need exit interview : <span class="danger">*</span></label>
                                                <?php 
                                                    $status = get_permission_status(27,$this->session->userdata('user_type'));
                                                ?>
                                                <select <?php if($status == 0) { ?> disabled  <?php } ?> required class="form-control" name="exit_interview" id="wexit_interview2" class="exit_interview">
                                                    <option value=""><?php echo "------Need exit interview-------"?></option>
                                                    <option value="1"  <?php if(($user[0]['exit_interview'])=="1"){?>  selected="selected" <?php }?> >Yes</option>
                                                    <option value="2"  <?php if(($user[0]['exit_interview'])=="2"){?>  selected="selected" <?php }?> >No</option> 
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" id="exit_reason">
                                                    <label for="wexit_reason2">Reason for leaving : </label>
                                                    <?php 
                                                        $status = get_permission_status(28,$this->session->userdata('user_type'));
                                                    ?>
                                                    <textarea <?php if($status == 0) { ?> disabled  <?php } ?> class="exit_reason form-control" id="wexit_reason2" name="exit_reason" cols="40"  placeholder="Enter reason for leaving"><?php echo html_entity_decode($user[0]['exit_reason']);?></textarea>  
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="wstatus2"> Status : <span class="danger">*</span></label>
                                                <?php 
                                                    $status = get_permission_status(28,$this->session->userdata('user_type'));
                                                ?>
                                                <select <?php if($status == 0) { ?> disabled  <?php } ?> class="form-control status_change_select" name="status" id="wstatus2" class="status" data-id="<?php echo $user[0]['id'] ?>">
                                                    <option value="1"  <?php if(($user[0]['status'])=="1"){?>  selected="selected" <?php }?> >Active</option>
                                                    <option value="2"  <?php if(($user[0]['status'])=="2"){?>  selected="selected" <?php }?> >Inactive</option> 
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" id="end-datepicker1">
                                                    <label for="wedate2">Final working date :</label>  
                                                    <?php if($user[0]['final_date']=='0000-00-00' || $user[0]['final_date']==NULL){?>
                                                    <input type="text" class="form-control" id="end-datepicker" value=" " name="end-datepicker"> 
                                                    <?php } else {?>

                                                    <input type="text" class="form-control" id="end-datepicker" value="<?php echo date("d/m/Y",strtotime($user[0]['final_date']));?>" name="end-datepicker"> 
                                                    <?php }?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <fieldset> <legend>User rates:</legend>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wday_rate2">Day rate:<span class="danger">*</span></label>
                                                    <input type="text" class="form-control required" id="wday_rate2" name="wday_rate2" value="<?php echo $user_rates[0]['day_rate'];?>"> 
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wnight_rate2">Night rate:<span class="danger">*</span></label>
                                                    <input type="text" class="form-control required" id="wnight_rate2" name="wnight_rate2"value="<?php echo $user_rates[0]['night_rate'];?>"> 
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wday_saturday_rate2">Day saturday rate:<span class="danger">*</span></label>
                                                    <input type="text" class="form-control required" id="wday_saturday_rate2" name="wday_saturday_rate2" value="<?php echo $user_rates[0]['day_saturday_rate'];?>"> 
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wday_sunday_rate2">Day sunday rate:<span class="danger">*</span></label>
                                                    <input type="text" class="form-control required" id="wday_sunday_rate2" name="wday_sunday_rate2" value="<?php echo $user_rates[0]['day_sunday_rate'];?>"> 
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="weekend_night_rate">Weekend night rate:<span class="danger">*</span></label>
                                                    <input type="text" class="form-control required" id="weekend_night_rate" name="weekend_night_rate" value="<?php echo $user_rates[0]['weekend_night_rate'];?>"> 
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"> 
                                                </div>
                                            </div>
                                        </div>



                                        </fieldset><br>
                                         -->
                                    </section>
                                    
                              
                                    <h6>Unit</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wunit2">Select unit :</label> 
                                                     <select class="custom-select form-control required" id="wunit2"
                                                        name="unit">
                                                          <?php
                                                         
                                                        foreach ($unit as $uni) {
                                                            ?>  
                                      <option <?php if($units[0]['unit_id']==$uni['id']){?> selected="selected" <?php }?>
                                            value="<?php echo $uni['id']; ?>"><?php echo $uni['unit_name']; ?></option> 
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
            <div class="row">
                <div class="col-md-6"><h3>Unit Change Scheduler</h3></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="wunit2">Select unit :</label> 
                        <?php 
                            $status = get_permission_status(31,$this->session->userdata('user_type'));
                        ?>
                        <select <?php if($status == 0) { ?> disabled  <?php } ?> class="custom-select form-control required" id="to_unit"
                            name="unit">
                            <option value=""><?php echo "------Select unit-------"?></option>
                              <?php foreach ($unit as $uni) {
                                ?>
                                <?php if ($units[0]['unit_id'] != $uni['id']) { ?>

                                    <option <?php if($user[0]['to_unit']==$uni['id']){?> selected="selected" <?php }?> value="<?php echo $uni['id']; ?>"><?php echo $uni['unit_name']; ?></option> 
                                <?php } ?>  
                            <?php } ?> 
                        </select>
                    </div>
                </div>
            </div>
            <?php if($user[0]['unit_change_date']>=date('Y-m-d')){ $new_date=changeDateFormat($user[0]['unit_change_date']); } else { $new_date='';} ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="wunit2">Date :</label>
                        <input <?php if($status == 0) { ?> disabled  <?php } ?> class="form-control" autocomplete="off" onkeydown="return false" type="text" placeholder="dd/mm/yy"  id="unit_change_date"  name="unit_change_date" value="<?php echo $new_date;?>">
                    </div>
                </div>
            </div>
                                    </section> 

                                    <!-- Step 3 -->
                                    <h6>Work Schedule</h6>
                                    <section>
                                        <div class="row">
                                          
                                            <div class="col-md-3">
                                                 <div class="form-group">
                                                    <label for="wsunday2">Sunday :</label> 
                                                     <select class="custom-select form-control required" id="wsunday2"
                                                        name="sunday"> 
                                                         <option <?php if($workschedule[0]['sunday']==0) { ?> selected="selected" <?php } ?> value="0">Working</option>
                                                         <option <?php if($workschedule[0]['sunday']==1) { ?> selected="selected" <?php } ?> value="1">Dayoff</option>
                                                    </select>
                                                </div>  
 
                                            </div>
                                            <div class="col-md-3">
                                                 <div class="form-group">
                                                    <label for="wmonday2">Monday :</label> 
                                                     <select class="custom-select form-control required" id="wmonday2"
                                                        name="monday"> 
                                                         <option <?php if($workschedule[0]['monday']==0) { ?> selected="selected" <?php } ?> value="0">Working</option>
                                                         <option <?php if($workschedule[0]['monday']==1) { ?> selected="selected" <?php } ?> value="1">Dayoff</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                 <div class="form-group">
                                                    <label for="wtuesday2">Tuesday :</label> 
                                                     <select class="custom-select form-control required" id="wtuesday2"
                                                        name="tuesday"> 
                                                         <option <?php if($workschedule[0]['tuesday']==0) { ?> selected="selected" <?php } ?> value="0">Working</option>
                                                         <option <?php if($workschedule[0]['tuesday']==1) { ?> selected="selected" <?php } ?> value="1">Dayoff</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                 <div class="form-group">
                                                    <label for="wwednesday2">Wednesday :</label> 
                                                     <select class="custom-select form-control required" id="wwednesday2"
                                                        name="wednesday"> 
                                                         <option <?php if($workschedule[0]['wednesday']==0) { ?> selected="selected" <?php } ?> value="0">Working</option>
                                                         <option <?php if($workschedule[0]['wednesday']==1) { ?> selected="selected" <?php } ?> value="1">Dayoff</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                 <div class="form-group">
                                                    <label for="wthursday2">Thursday :</label> 
                                                     <select class="custom-select form-control required" id="wthursday2"
                                                        name="thursady"> 
                                                         <option <?php if($workschedule[0]['thursday']==0) { ?> selected="selected" <?php } ?> value="0">Working</option>
                                                         <option <?php if($workschedule[0]['thursday']==1) { ?> selected="selected" <?php } ?> value="1">Dayoff</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                 <div class="form-group">
                                                    <label for="wfriday2">Friday :</label> 
                                                     <select class="custom-select form-control required" id="wfriday2"
                                                        name="friday"> 
                                                         <option <?php if($workschedule[0]['friday']==0) { ?> selected="selected" <?php } ?> value="0">Working</option>
                                                         <option <?php if($workschedule[0]['friday']==1) { ?> selected="selected" <?php } ?> value="1">Dayoff</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                 <div class="form-group">
                                                    <label for="wsaturday2">Saturday :</label> 
                                                     <select class="custom-select form-control required" id="wsaturday2"
                                                        name="saturday"> 
                                                         <option <?php if($workschedule[0]['saturdy']==0) { ?> selected="selected" <?php } ?> value="0">Working</option>
                                                         <option <?php if($workschedule[0]['saturdy']==1) { ?> selected="selected" <?php } ?> value="1">Dayoff</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
  
</div>
<div class="modal fade" id="session_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Session Expired</h5>
      </div>
      <div class="modal-body">
        Your session has expired. Please log in again.
        <input type="hidden" name="session_url" id="session_url" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="session-expire-confirmBtn">OK</button>
      </div>
    </div>
  </div>
</div>
 