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
					<li class="breadcrumb-item"><a
						href="<?php echo base_url();?>admin/manageuser/">Manage Users</a></li>
					<li class="breadcrumb-item active">Edit Users</li>
				</ol>
			</div>
		</div>
 <div class="row" id="validation">
                    <div class="col-12">
                        <div class="card wizard-content">
                            <div class="card-body"> 
                                <form action="#" class="validation-wizard wizard-circle">
                                	<h6>Step 1</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wfirstName2"> First Name : <span class="danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control required" id="wfirstName2"
                                                        name="firstname"  value="<?php echo $user[0]['fname'];?>"> </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wlastName2"> Last Name : <span class="danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control required" id="wlastName2"
                                                        name="lastname" value="<?php echo $user[0]['lname'];?>"> </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wemailAddress2"> Email Address : <span
                                                            class="danger">*</span> </label>
                                                    <input type="email" class="form-control required"
                                                        id="wemailAddress2" name="email" value="<?php echo $user[0]['email'];?>"> </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wdesignation2"> Select Designation : <span class="danger">*</span>
                                                    </label>
                                                    <select class="custom-select form-control required" id="wdesignation2"
                                                        name="designation">
                                                          <?php
                                                        foreach ($designation as $uni) {
                                                            ?>  

                                                                 <option
											value="<?php echo $uni['id']; ?>"><?php echo $uni['designation_name']; ?></option> 
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wpaymenttype2"> Select Payment Type : <span class="danger">*</span>
                                                    </label>
                                                    <select class="custom-select form-control required" id="wpaymenttype2"
                                                        name="paymenttype">
                                                        <?php
                                                        foreach ($paymenttype as $payment) {
                                                            ?>  

                                                                 <option
											value="<?php echo $payment['id']; ?>"><?php echo $payment['payment_type']; ?></option> 
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                               
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 1 -->
                                    <h6>Step 2</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wunit2"> Select Unit : <span class="danger">*</span>
                                                    </label>
                                                    <select class="custom-select form-control required" id="wunit2"
                                                        name="unit">
                                                        <?php
                                                        foreach ($unit as $uni) {
                                                            ?>  

                                                                 <option
											value="<?php echo $uni['id']; ?>"><?php echo $uni['unit_name']; ?></option> 
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wphoneNumber2">Phone Number :</label>
                                                    <input type="tel" class="form-control required" id="wphoneNumber2"> </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wlocation2"> Select City : <span class="danger">*</span>
                                                    </label>
                                                    <select class="custom-select form-control required" id="wlocation2"
                                                        name="location">
                                                        <option value="">Select City</option>
                                                        <option value="India">India</option>
                                                        <option value="USA">USA</option>
                                                        <option value="Dubai">Dubai</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wdate2">Date of Birth :</label>
                                                    <input type="date" class="form-control" id="wdate2"> </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 2 -->
                                    <h6>Step 3</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jobTitle2">Company Name :</label>
                                                    <input type="text" class="form-control required" id="jobTitle2">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="webUrl3">Company URL :</label>
                                                    <input type="url" class="form-control required" id="webUrl3"
                                                        name="webUrl3"> </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="shortDescription3">Short Description :</label>
                                                    <textarea name="shortDescription" id="shortDescription3" rows="6"
                                                        class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 3 -->
                                    <h6>Step 4</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wint1">Interview For :</label>
                                                    <input type="text" class="form-control required" id="wint1"> </div>
                                                <div class="form-group">
                                                    <label for="wintType1">Interview Type :</label>
                                                    <select class="custom-select form-control required" id="wintType1"
                                                        data-placeholder="Type to search cities" name="wintType1">
                                                        <option value="Banquet">Normal</option>
                                                        <option value="Fund Raiser">Difficult</option>
                                                        <option value="Dinner Party">Hard</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="wLocation1">Location :</label>
                                                    <select class="custom-select form-control required" id="wLocation1"
                                                        name="wlocation">
                                                        <option value="">Select City</option>
                                                        <option value="India">India</option>
                                                        <option value="USA">USA</option>
                                                        <option value="Dubai">Dubai</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="wjobTitle2">Interview Date :</label>
                                                    <input type="date" class="form-control required" id="wjobTitle2">
                                                </div>
                                                <div class="form-group">
                                                    <label>Requirements :</label>
                                                    <div class="mb-2">
                                                        <label class="custom-control custom-radio">
                                                            <input id="radio3" name="radio" type="radio"
                                                                class="custom-control-input">
                                                            <span class="custom-control-label">Employee</span>
                                                        </label>
                                                        <label class="custom-control custom-radio">
                                                            <input id="radio4" name="radio" type="radio"
                                                                class="custom-control-input">
                                                            <span class="custom-control-label">Contract</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 4 -->
                                    <h6>Step 5</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="behName1">Behaviour :</label>
                                                    <input type="text" class="form-control required" id="behName1">
                                                </div>
                                                <div class="form-group">
                                                    <label for="participants1">Confidance</label>
                                                    <input type="text" class="form-control required" id="participants1">
                                                </div>
                                                <div class="form-group">
                                                    <label for="participants1">Result</label>
                                                    <select class="custom-select form-control required"
                                                        id="participants1" name="location">
                                                        <option value="">Select Result</option>
                                                        <option value="Selected">Selected</option>
                                                        <option value="Rejected">Rejected</option>
                                                        <option value="Call Second-time">Call Second-time</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="decisions1">Comments</label>
                                                    <textarea name="decisions" id="decisions1" rows="4"
                                                        class="form-control"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Rate Interviwer :</label>
                                                    <div class="c-inputs-stacked">
                                                        <label class="inline custom-control custom-checkbox block">
                                                            <input type="checkbox" class="custom-control-input"><span
                                                                class="custom-control-label ml-0">1 star</span> </label>
                                                        <label class="inline custom-control custom-checkbox block">
                                                            <input type="checkbox" class="custom-control-input"><span
                                                                class="custom-control-label ml-0">2 star</span> </label>
                                                        <label class="inline custom-control custom-checkbox block">
                                                            <input type="checkbox" class="custom-control-input"><span
                                                                class="custom-control-label ml-0">3 star</span> </label>
                                                        <label class="inline custom-control custom-checkbox block">
                                                            <input type="checkbox" class="custom-control-input"><span
                                                                class="custom-control-label ml-0">4 star</span> </label>
                                                        <label class="inline custom-control custom-checkbox block">
                                                            <input type="checkbox" class="custom-control-input"><span
                                                                class="custom-control-label ml-0">5 star</span> </label>
                                                    </div>
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
</div>
 