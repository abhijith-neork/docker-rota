<div class="page-wrapper">
  <div class="container-fluid"> 
    <div class="card">
      <div class="card-body">  
		<div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor mb-0 mt-0">Edit Profile (#<?php echo $this->session->userdata('user_id'); ?>)</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Edit Profile</li>
                </ol>
            </div>
        </div>  
          <div align="center" class="alert alert-error">
             
            <?php if($this->session->flashdata('message')){?>
            <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
            <?php echo $this->session->flashdata('message');?>
            </p>
            <?php }else{?>
            <p class="success-msg" id="success-alert" style="color: green; font-size: 16px; text-align: center;">
            <?php echo $this->session->flashdata('Successfully');?>
            <?php }?> 
            <p class="success-msg" id="success-alert" style="color: red;text-align:center;">
            <?php echo validation_errors(); ?>
            </p>
                </div>
  <div class="row">
   <div class="col-12">
     <div class="card">
        <div class="card-body"> 

        <form enctype="multipart/form-data" name="add" id="add" method="post" action="<?php echo base_url('staffs/Profile/');?>index" >  

        <div class="row"> 
                    <div class="col-md-6">
                      <!--   <div class="card">
                            <div class="card-body"> 
                                <h4 class="card-title">Profile picture</h4>
 
                             </div>  
                                 
                                <input type="file" id="image" class="dropify" name="image" data-max-file-size="2M" value="<?php echo $user[0]['profile_image'];?>" data-default-file="<?php echo base_url('./uploads/').$user[0]['profile_image'];?>" />
                            </div> -->
                         </div>
                 
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $unit[0]['unit_name'];?></h3>
                                <p class="card-text"><?php  echo $unit[0]['address'];?>.</p>
                                <p class="card-text"><?php echo $unit[0]['phone_number'];?>.</p> 
                            </div>
                        </div>
                    </div>
        </div>
   
        <div class="row">
            <div class="col-12">
                <div class="card">               
                    <div class="card-body">
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wfirstName2"> First name : <span class="danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control required" readonly="readonly" id="wfirstName2"
                                                        name="firstName" required="required" value="<?php echo $user[0]['fname'];?>" > 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wlastName2"> Last name : <span class="danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control required" readonly="readonly" id="wlastName2"
                                                        name="lastName" required="required" value="<?php echo $user[0]['lname'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wemailAddress2"> Email address : <span
                                                            class="danger">*</span> </label>
                                                    <input type="email" class="form-control" id="wemailAddress2" required="required"  name="emailAddress" value="<?php echo $user[0]['email'];?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wphoneNumber2">Phone number :</label>
                                                    <input type="tel" name="phone_number" required="required" class="form-control required"  id="wphoneNumber2" value="<?php echo $user[0]['mobile_number'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                           <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wdob2">Date Of Birth:</label>
                                                    <input type="text" name="dob" required="required" class="form-control required" readonly="readonly" id="wdob2" value="<?php echo date("d/m/Y",strtotime($user[0]['dob'])); ?>"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">  
                                                <div class="form-group">
                                                    <label for="wgender2"> Gender : <span class="danger">*</span>
                                                    </label>
                                                    <select class="custom-select form-control " id="gender"
                                                        name="gender" readonly="readonly">
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
                                                    <label for="wethnicity2"> Ethnicity : <span class="danger">*</span>
                                                    </label>
                                                     <select class="ethnicity custom-select form-control required" id="ethnicity" name="ethnicity"> 
                                                        <option value="none"><?php echo "------Select ethnicity-------"?></option>
                                                        <?php
                                                        foreach ($ethnicity as $cou) { 
                                                            ?>    
                                                            <?php if($cou['parent']==0){?>
                                                              <option  disabled="disabled" style="font-size: 18px !important;" value="<?php echo $cou['id']; ?>"> <?php echo $cou['Ethnic_group']; ?></option> 
                                                            <?php } else { ?>
                                                                <option value="<?php echo $cou['id']?>"<?php if(($user[0]['ethnic_id'])==$cou['id']){?> selected="selected" <?php }?>><?php echo $cou['Ethnic_group']; ?></option> 
                                                             <!--  <option  value="<?php echo $cou['id']; ?>"> <?php echo $cou['Ethnic_group']; ?></option> --> 
                                                            <?php } ?>
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="new_ethnicity" style="padding-top: 20px;"> 
                                               <!--  <div class="form-group">
                                                    <label for="newethnicity" id="other" value='' style="display: none;"> </label>
                                                    <?php if($user[0]['Ethnicity']==''){?>
                                                    <input type="text" placeholder="Enter other ethnicty" readonly="readonly" class="form-control" id="newethnicity" name="newethnicity" value=" "> 
                                                <?php } else {?>
                                                    <input type="text" placeholder="Enter other ethnicty" readonly="readonly" class="form-control" id="newethnicity" name="newethnicity" value="<?php echo $user[0]['Ethnicity']; ?>"> 
                                                <?php } ?>
                                                </div>  -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="waddress12"> Address 1 : <span class="danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control required" id="waddress12"
                                                        name="address1" required="required"  value="<?php echo $user[0]['address1'];?>"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="waddress22"> Address 2 : 
                                                    </label>
                                                    <input type="text" class="form-control" id="waddress22"
                                                        name="address2"  value="<?php echo $user[0]['address2'];?>"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                     <label for="wcountry2"> Country : <span class="danger">*</span>
                                                    </label>
                                                     <select class="custom-select form-control required" id="wcountry2"
                                                        name="wcountry2">  
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
                                                    <input type="text" class="form-control" id="wcity2" 
                                                        name="wcity2" value="<?php echo $user[0]['city'];?>"> 
                                                </div> 
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wpostcode2"> Postcode : <span class="danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control required" id="wpostcode2" 
                                                        name="postcode" required="required" value="<?php echo $user[0]['postcode'];?>"> 
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                      
                                            </div>
                                        </div>

                                        <fieldset> <legend>Next Of Kin:</legend>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wkin_name2">Name:<span class="danger">*</span></label>
                                                    <input type="text"   class="form-control" required="required" id="wkin_name2" name="kin_name" value="<?php echo $user[0]['kin_name'];?>"> 
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wkin_phone2">Phonenumber:<span class="danger">*</span></label>
                                                    <input type="text"  class="form-control" required="required" id="wkin_phone2" name="kin_phone"value="<?php echo $user[0]['kin_phone'];?>"> 
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wkin_address2">Address:<span class="danger">*</span></label>
                                                    <input type="text"  class="form-control" required="required" id="wkin_address2" name="kin_address" value="<?php echo $user[0]['kin_address'];?>"> 
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                              
                                            </div>
                                        </div>
                                        

                                        </fieldset><br>

                                    </section>
                         
                    </div>
                </div>
            </div>  
        </div>

        <div class="row" style="padding-left: 33px;">
            <input type="hidden" class="form-control" required="required" id="session_unittype" name="session_unittype" value="<?php echo $this->session->userdata('user_type');?>"> 
            <button type="submit" class="btn btn-success mr-2">Submit</button>
        </div>
        </form>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div> 
 