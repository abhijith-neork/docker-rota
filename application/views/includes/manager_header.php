<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>/assets/images/favicon.png">
    <?php $site_title=getSitetitle();?>
    <?php $permission=GetPermission($this->session->userdata('user_type'),'Admin.Rota.Create'); ?>
    <?php $permission_edit_rota=GetPermission($this->session->userdata('user_type'),'Admin.Rota.Edit'); ?>
    <?php $permission_view_rota=GetPermission($this->session->userdata('user_type'),'Admin.Rota.View'); ?>
    <?php $permission_moveshift=GetPermission($this->session->userdata('user_type'),'Admin.rota.Moveshift'); ?>
    <title><?php echo $site_title[0]['company_name'];?></title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
     <!-- chartist CSS -->
   <!-- toast CSS -->
      <!-- Custom CSS -->
      <?php
    if(strpos(base_url(), 'erota') !== false){
    
    ?>
    <link rel="stylesheet" type="text/css" id="theme"  href="<?php echo base_url();?>assets/css/colors/blue.css">
    <?php } else{ ?>

    <link rel="stylesheet" type="text/css" id="theme"  href="<?php echo base_url();?>assets/css/colors/default.css">
    <?php }  ?>
         
       <!--<link href="<?php echo base_url();?>assets/css/fulcalendar_staff.css" rel="stylesheet">-->
    <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/custom_sweetalert.css" rel="stylesheet">
 
    <?php if($this->session->userdata('css_value')==3){?>
        <link href="<?php echo base_url();?>assets/css/eRota_extrasmall_font.css" rel="stylesheet">
    <?php } elseif ($this->session->userdata('css_value')==1) {?>
        <link href="<?php echo base_url();?>assets/css/eRota_Medium_font.css" rel="stylesheet">
    <?php } else {?>
        <link href="<?php echo base_url();?>assets/css/eRota_Small_font.css" rel="stylesheet">
    <?php } ?>

 <link href="<?php echo base_url();?>assets/css/sweetalert.css" rel="stylesheet" />
 <link href="<?php echo base_url();?>assets/plugins/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css" />
 <link href="<?php echo base_url();?>assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
 <link href="<?php echo base_url();?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
 <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
 <link href="<?php echo base_url();?>assets/css/fullcalendar.css" rel='stylesheet' />
 <link href="<?php echo base_url();?>assets/css/fullcalendar.print.css" rel='stylesheet' media='print' />
 <link href="<?php echo base_url();?>assets/css/scheduler.min.css" rel='stylesheet' />
 <link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="Stylesheet" type="text/css" />
 <link href="<?php echo base_url();?>assets/css/select2.min.css" rel="stylesheet">

     <!-- You can change the theme colors from here -->
     <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
 <script>
var baseURL = "<?php echo base_url();?>";
</script>
</head>

<body class="fix-header card-no-border fix-sidebar">
 
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                             <img style="height: 63px;" src="<?php echo base_url();?>/assets/images/logo-icon1.jpeg" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="<?php echo base_url();?>/assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>
                         <!-- dark Logo text -->
                         <img style="width: 184px;" src="<?php echo base_url();?>/assets/images/logo-text1.png" alt="homepage" class="dark-logo" />
                         <!-- Light Logo text -->    
                         <img src="<?php echo base_url();?>/assets/images/logo-light-text.png" class="light-logo" alt="homepage" /></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0 ">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="icon-arrow-left-circle"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                           <!--  <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox animated bounceInDown">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div> -->
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <!-- <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-email"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox animated bounceInDown" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <a href="#">
                                                <div class="user-img"> <img src="<?php echo base_url();?>/assets/images/users/4.jpg" alt="user" class="img-circle"> <span class="profile-status offline float-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div> -->
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown" style="padding-right: 13px;display: none;"> 
                        <label for="Size" style="padding-top: 28px;color: #ffff;">Change size:</label> 
                        </li>
                        <li class="nav-item dropdown" style="padding-right: 13px;display: none;">             
                            <div class="form-group" style="height: 0px;" title="Change Size">
                                    <label for="Size"></label> 
                                        <select required="required" style="height:32px;font-size: 13px;font-color: white;" onchange="changeSize()" class="custom-select  size" id="size" name="size" placeholder="Select Size"> 
                                        <option value="1"  <?php if($this->session->userdata('css_value')=="1"){?>  selected="selected" <?php }?>>Large</option> 
                                        <option value="2"  <?php if($this->session->userdata('css_value')=="2"){?>  selected="selected" <?php }?>>Medium</option>
                                        <option value="3"  <?php if($this->session->userdata('css_value')=="3"){?>  selected="selected" <?php }?>>Small</option> 
                                        </select>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <?php $profile_image=getImage($this->session->userdata('user_id'));?>
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php if($profile_image[0]['profile_image']!=''){ ?>
                                <img src="<?php echo base_url('./uploads/').$profile_image[0]['profile_image'];?>" alt="user" class="profile-pic" />
                                <?php } else { ?>
                                <img src="<?php echo base_url('./uploads/').'default.png';?>" alt="user" class="profile-pic" />
                                <?php }?></a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img"><?php if($profile_image[0]['profile_image']!=''){ ?>
                                <img src="<?php echo base_url('./uploads/').$profile_image[0]['profile_image'];?>" alt="user" class="profile-pic" />
                                <?php } else { ?>
                                <img src="<?php echo base_url('./uploads/').'default.png';?>" alt="user" class="profile-pic" />
                                <?php }?></div>
                                        <!--  <div class="u-text">
                                                <h4><?php echo $this->session->userdata('full_name'); ?></h4>
                                                <p class="text-muted"><?php echo $this->session->userdata('email'); ?></p><a href="<?php echo base_url();?>admin/user/edituser/<?php echo $this->session->userdata('user_id'); ?>" class="btn btn-rounded btn-danger btn-sm">View Profile</a></div> -->
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo base_url('manager/');?>profile"><i class="ti-user"></i> My Profile</a></li>
                                     <!-- <li><a href="#"><i class="ti-email"></i> Inbox</a></li> -->
                                      <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo base_url();?>users/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#!"  aria-haspopup="true" aria-expanded="false"> <i class="flag-icon flag-icon-gb"></i></a>
                            
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
                <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                <div class="user-profile">
                    <!-- User profile image -->
                     <div class="profile-img"> <?php if($profile_image[0]['profile_image']!=''){ ?>
                                <img src="<?php echo base_url('./uploads/').$profile_image[0]['profile_image'];?>" alt="user" class="profile-pic" />
                                <?php } else { ?>
                                <img src="<?php echo base_url('./uploads/').'default.png';?>" alt="user" class="profile-pic" />
                                <?php }?> </div>  
                    <!-- User profile text-->
                    <div class="profile-text"> <a href="#" class="dropdown-toggle link u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <?php echo $this->session->userdata('full_name'); ?> </a>
                        <div class="dropdown-menu animated flipInY">
                            <a href="<?php echo base_url('manager/');?>profile" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                             <!-- <a href="#" class="dropdown-item"><i class="ti-email"></i> Inbox</a> -->
                             <div class="dropdown-divider"></div> <a href="<?php echo base_url();?>users/logout" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                    </div>
                </div>  
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav"> 
                       <?php
                            $profile_status = get_group_permission_status($this->session->userdata('user_type'),'Profile.View');
                        ?>
                        <?php if($profile_status == 1){?>
                       <li>
                            <a href="<?php echo base_url();?>manager/editprofile" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Profile</a>
                            
                        </li> <?php }?>
                        <?php
                            $annual_leave_status = get_group_permission_status($this->session->userdata('user_type'),'Annual Leave.View');
                        ?>
                        <?php if($annual_leave_status == 1){?>
                         <li>
                            <!-- <a href="<?php echo base_url();?>manager/leave" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-earth-off"></i><span class="hide-menu">Annual Leave</a> -->
                                <a href="JavaScript:Void(0);" class="has-arrow annual_leave_view"  aria-expanded="false"><i class="mdi mdi-briefcase"></i><span class="hide-menu">Annual Leave</a>
                            
                        </li> <?php }?>
                        <?php
                            $training_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Training.View');
                        ?>
                        <?php if($training_status == 1){?>
                        <li>
                            <!-- <a href="<?php echo base_url();?>staffs/training" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-library"></i><span class="hide-menu">My Trainings</a> -->
                                <a href="JavaScript:Void(0);" class="has-arrow training_view"  aria-expanded="false"><i class="mdi mdi-hospital-building"></i><span class="hide-menu">My Training</a>
                            
                        </li><?php }?>
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="true"><i class="mdi mdi-account-card-details"></i><span class="hide-menu">My Reports</span></a>
                            <ul aria-expanded="flase" class="collapse">
                                <!-- <li>
                                    <a href="<?php echo base_url();?>admin/Reports/timelog" class="has-arrow"  aria-expanded="false">Time log</a>
                                </li> -->

                                <li>
                                    <a href="JavaScript:Void(0);"  class="has-arrow timelog_view"  aria-expanded="false">Time log</a>
                                </li>

                                <li>
                                    <a href="JavaScript:Void(0);" class="has-arrow timesheet_view"  aria-expanded="false">Timesheet</a>
                                    <!-- <a href="<?php echo base_url();?>manager/Reports_staff/payrollreport" class="has-arrow"  aria-expanded="false">Timesheet</a> -->
                                </li>
                            </ul>
                        </li>
                        <?php
                            $rota_view_status = get_group_permission_status($this->session->userdata('user_type'),'Rota.View');
                            $rota_availability_status = get_group_permission_status($this->session->userdata('user_type'),'Rota.Availability');
                        ?>
                        <?php if($rota_view_status == 1 || $rota_availability_status==1){?>
                        <li>
                            <a href="#" class="has-arrow "  aria-expanded="true"><i class="mdi mdi-format-rotate-90"></i><span class="hide-menu">Rota</span></a>                            
                            <ul aria-expanded="true" class="collapse in">
                                <?php if($rota_view_status == 1){?>
                                <li>
                                   <a href="" class="has-arrow viewrota_manager"  aria-expanded="false"><span class="hide-menu">View</a>
                                </li>
                                <?php }?>
                                <?php if($rota_availability_status == 1){?>
                                <li>
                                     <a href="" class="has-arrow availability_manager"  aria-expanded="false"><span class="hide-menu">Set Availability</a>
                                </li>
                                <?php }?>
                            </ul>
                        </li>
                        <?php }?>
                        <?php
                            $dashboard_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Dashboard.View');
                        ?>
                        <?php if($dashboard_status == 1){?>
                         <li>
                            <a href="<?php echo base_url();?>admin/Dashboard/index" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</a>
                            
                        </li>  <?php }?>

                        <!-- <li>
                            <a href="<?php echo base_url();?>admin/Dailysenses/index" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account-star-variant"></i><span class="hide-menu">Daily Census </a>
                            
                        </li>  -->
                        
                        <!-- <li>
                            <a href="<?php echo base_url();?>admin/manageuser" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Manage Users </a>
                            
                        </li> -->
                       <!--  <li>
                            <a href="<?php echo base_url();?>admin/User/addagencystaff" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Add Agency Staff </a>
                            
                        </li> -->
                       <!--  <li>
                            <a href="<?php echo base_url();?>admin/managedesignation" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-briefcase"></i><span class="hide-menu">Manage Job Role </a>
                            
                        </li> -->
                        <!-- <li>
                            <a href="<?php echo base_url();?>admin/managepayment" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-credit-card"></i><span class="hide-menu">Manage Payment Type </a>
                            
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url();?>admin/manageshift" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-image-filter-tilt-shift"></i><span class="hide-menu">Manage Shift </a>
                            
                        </li>
                        <li>
                            <a href="<?php echo base_url();?>manager/notes" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-earth-off"></i><span class="hide-menu">Manage Notifications</a>
                        </li>
                         <li>
                            <a href="<?php echo base_url();?>admin/holiday" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-earth-off"></i><span class="hide-menu">Annual Leave Request</a>
                            
                        </li> -->
                        <?php
                            $dailycensus_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Dailycensus.View');
                        ?>
                        <?php if($dailycensus_status == 1){?>
                        <li>
                            <a href="<?php echo base_url();?>admin/Dailysenses/index" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account-star-variant"></i><span class="hide-menu">Daily Census </a>
                            
                        </li><?php }?>
                        <?php
                            $notification_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Notes.View');
                        ?>
                        <?php if($notification_status == 1){?>
                        <li>
                            <a href="<?php echo base_url();?>manager/notes" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-earth-off"></i><span class="hide-menu">Manage Notifications</a>
                        </li><?php }?>
                        <?php
                            $annual_leave_request_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Annual Leave.View');
                        ?>
                        <?php if($annual_leave_request_status == 1){?>
                         <li>
                            <a href="<?php echo base_url();?>admin/holiday" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-earth-off"></i><span class="hide-menu">Annual Leave Request</a>
                            
                        </li><?php }?>
                        <?php
                            $rota_create_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Rota.Create');
                            $rota_edit_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Rota.Edit');
                            $rota_view_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Rota.View');
                            $rota_moveshift_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.rota.Moveshift');
                            $rota_availability_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Rota.EmployeeAvailability');
                        ?>
                        <?php if($rota_create_status == 1 || $rota_edit_status == 1 || $rota_view_status == 1 || $rota_moveshift_status == 1 || $rota_availability_status == 1){?>
                         <li>
                            <a class="has-arrow " href="#" aria-expanded="true"><i class="mdi mdi-format-rotate-90"></i><span class="hide-menu">Manage Rota</span></a>
                            <ul aria-expanded="false" class="collapse">

                                <?php if($rota_create_status == 1){?>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/managerota" class="has-arrow"  aria-expanded="false"><span class="hide-menu">Create</a>
                                    </li>
                                <?php }?>
                                <?php if($rota_edit_status == 1){?>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/Rota/viewrota" class="has-arrow"  aria-expanded="false">Edit</a>
                                    </li>
                                <?php }?>
                                <?php if($rota_view_status == 1){?>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/Rota/editrota" class="has-arrow"  aria-expanded="false">View</a>
                                    </li>
                                <?php }?>
                                <?php if($rota_moveshift_status == 1){?>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/MoveShift/index" class="has-arrow"  aria-expanded="false">Move Shift</a>
                                    </li>
                                <?php }?>
                                <?php if($rota_availability_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/staffavailabilityreport" class="has-arrow"  aria-expanded="false">Employees Availability</a>
                                </li>
                                <?php }?>
                            </ul>
                        </li>
                        <?php }?>
                        <?php
                            $training_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Training.View');
                        ?>
                        <?php if($training_status == 1){?>
                        <li>
                            <a href="<?php echo base_url();?>admin/training" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-library"></i><span class="hide-menu">Manage Training</a>
                            
                        </li><?php }?>
                       <!--  <li>
                            <a href="<?php echo base_url();?>admin/manageunit" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-earth"></i><span class="hide-menu">Manage Unit </a>
                            
                        </li>
                       <li>
                            <a href="<?php echo base_url();?>admin/managegroup" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-group"></i><span class="hide-menu">Manage Group </a>
                            

                        </li> -->
                      <!--    <li>
                            <a href="<?php echo base_url();?>admin/managejobrolegroup" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-ungroup"></i><span class="hide-menu">Manage Job Role Group </a>
                            

                        </li>  -->
                         <li>
                            <a class="has-arrow " href="#" aria-expanded="true"><i class="mdi mdi-account-card-details"></i><span class="hide-menu">Reports</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <?php
                                    $absence_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Absence');
                                ?>
                                <?php if($absence_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/absencereport" class="has-arrow"  aria-expanded="false">Absence Report</a>
                                </li>
                                <?php }?>
                                <!-- new report added on nov10 -->
                                <?php
                                    $agency_login_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Agencyloginreport');
                                ?>
                                <?php if($agency_login_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/agencyloginreport" class="has-arrow"  aria-expanded="false">Agency Login Report</a>
                                </li>
                                <?php }?>
                                <?php
                                    $agency_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.AgencyReport');
                                ?>
                                <?php if($agency_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Splitpayroll/agencyuserreport" class="has-arrow"  aria-expanded="false">Agency Use Report</a>
                                </li>
                                <?php }?>
                                <?php
                                    $annual_leaveall_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Annualleaveallstaff');
                                ?>
                                <?php if($annual_leaveall_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/annualleaveallstaff" class="has-arrow"  aria-expanded="false">Annual Leave (All Employee)</a>
                                </li>
                                <?php }?>
                                <?php
                                    $annual_leave_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Annualleave');
                                ?>
                                <?php if($annual_leave_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/annualleave" class="has-arrow"  aria-expanded="false">Annual Leave (Individual)</a>
                                </li>
                                <?php }?>
                                <?php
                                    $annual_leave_planner_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.AnnualLeaveplanner');
                                ?>
                                <?php if($annual_leave_planner_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/annualleaveplanner" class="has-arrow"  aria-expanded="false">Annual Leave Planner</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $availability_report_count_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Availability_report_count');
                                ?>
                                <?php if($availability_report_count_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/availability_report_count" class="has-arrow"  aria-expanded="false">Availability Request Count Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $availability_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Availability_report_user_count');
                                ?>
                                <?php if($availability_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/availability_report_users" class="has-arrow"  aria-expanded="false">Availability Request Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $earlyleaver_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.earlyleaver_report');
                                ?>
                                <?php if($earlyleaver_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/earlyleaver_report" class="has-arrow"  aria-expanded="false">Early Leaver Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $emp_availability_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Availability');
                                ?>
                                <?php if($emp_availability_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Rota/staffs" class="has-arrow"  aria-expanded="false">Employee Availability Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $emp_list_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Employeelist');
                                ?>
                                <?php if($emp_list_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/employeelist" class="has-arrow"  aria-expanded="false">Employee List</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $emp_listdetailed_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Employeedetailedlist');
                                ?>
                                <?php if($emp_listdetailed_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/employeelistdetailed" class="has-arrow"  aria-expanded="false">Employee List (Detailed)</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $lastlogn_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Lastlogin');
                                ?>
                                <?php if($lastlogn_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/lastlogin" class="has-arrow"  aria-expanded="false">Last Login Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $lateness_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.lateness_report');
                                ?>
                                <?php if($lateness_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/lateness_report" class="has-arrow"  aria-expanded="false">Lateness Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $overstaffing_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Overstaffing');
                                ?>
                                <?php if($overstaffing_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/overstaffingreport" class="has-arrow"  aria-expanded="false">Overstaffing Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $overtime_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Overtime_Report');
                                ?>
                                <?php if($overtime_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Splitpayroll/extrahourreport" class="has-arrow"  aria-expanded="false">Overtime Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $payroll_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Payroll');
                                ?>
                                <?php if($payroll_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Splitpayroll/splitpayroll" class="has-arrow"  aria-expanded="false">Payroll Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $requestvsactualreport_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Requestvsactualreport');
                                ?>
                                <?php if($requestvsactualreport_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/requestvsactualreport" class="has-arrow"  aria-expanded="false">Request v Actual Shift Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $sicknessReport_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.SicknessReport');
                                ?>
                                <?php if($sicknessReport_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/sicknessreport" class="has-arrow"  aria-expanded="false">Sickness Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $timelog_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Timelog');
                                ?>
                                <?php if($timelog_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/timelog" class="has-arrow"  aria-expanded="false">Time log</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $timesheet_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Timesheet');
                                ?>
                                <?php if($timesheet_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/payrollreport" class="has-arrow"  aria-expanded="false">Timesheet</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $training_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Training');
                                ?>
                                <?php if($training_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/trainingreport" class="has-arrow"  aria-expanded="false">Training Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $transferHour_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.TransferHour');
                                ?>
                                <?php if($transferHour_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Splitpayroll/transferhourreport" class="has-arrow"  aria-expanded="false">Transfer Hours</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $weekendsreport_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Weekendsreport');
                                ?>
                                <?php if($weekendsreport_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/weekendsreport" class="has-arrow"  aria-expanded="false">Weekend Worked Report</a>
                                </li>
                                <?php } ?>
                                <?php
                                    $workingreport_report_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Workingreport');
                                ?>
                                <?php if($workingreport_report_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/workingreport" class="has-arrow"  aria-expanded="false">Working Report</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php
                            $Checkin_Checkout_history_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.History.Checkin_Checkout');
                            $address_history_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.addresshistory.View');
                            $userunit_history_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.userunithistory.View');
                            $designation_history_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.designationhistory.View');
                            $userrates_history_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.userrateshistory.View');
                            $rotaupdate_history_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.rotaupdatehistory.View');
                            $rotahistory_history_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Report.Rotahistory');
                        ?>
                        <?php if($Checkin_Checkout_history_status == 1 || $address_history_status == 1 || $userunit_history_status == 1 || $designation_history_status == 1 || $userrates_history_status == 1 || $rotaupdate_history_status == 1 || $rotahistory_history_status ==1 ){?>  
                        <li>
                            <a class="has-arrow " href="#" aria-expanded="true"><i class="mdi mdi-account-card-details"></i><span class="hide-menu">History</span></a>
                            <ul aria-expanded="true" class="collapse in" style="">
                                <?php if($Checkin_Checkout_history_status == 1){?>
                                 <li>
                                    <a href="<?php echo base_url();?>admin/History/CheckinAccuracy" class="has-arrow"  aria-expanded="false">Check-in/Check-out Accuracy Report</a>
                                </li>
                                <?php } ?>
                                <?php if($address_history_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/History/addresschangehistory" class="has-arrow"  aria-expanded="false"><span class="hide-menu">User Address</a>
                                </li>
                                <?php } ?>
                                <?php if($userunit_history_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/History/userunitchangehistory" class="has-arrow"  aria-expanded="false">User Unit</a>
                                </li>
                                <?php } ?>
                                <?php if($designation_history_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/History/userdesignationchangehistory" class="has-arrow"  aria-expanded="false">User Designation</a>
                                </li>
                                <?php } ?>
                                <?php if($userrates_history_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/History/userrateschangehistory" class="has-arrow"  aria-expanded="false"><span class="hide-menu">User Rates</a>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/History/contracthourschangehistory" class="has-arrow"  aria-expanded="false">Contract Hours</a>
                                </li>
                                <?php if($rotaupdate_history_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/History/rotaupdatehistory" class="has-arrow"  aria-expanded="false"><span class="hide-menu">Rota Updates</a>
                                </li>
                                <?php } ?>
                                <?php if($rotahistory_history_status == 1){?>
                                <li>
                                    <a href="<?php echo base_url();?>admin/Reports/rotahistorylist" class="has-arrow"  aria-expanded="false">Rota History</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php }?>
                        <?php
                            $company_edit_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Company.Edit');
                        ?>
                        <?php if($company_edit_status == 1){?>
                        <li>
                            <a href="<?php echo base_url();?>admin/company" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account-card-details"></i><span class="hide-menu">Company</a>
                            
                        </li>
                        <?php }?>
                        <?php
                            $password_change_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Password.Change');
                        ?>
                        <?php if($password_change_status == 1){?>
                        <li>
                            <a href="<?php echo base_url();?>manager/password" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account-key"></i><span class="hide-menu">Password Change</a>
                            
                        </li> 
                        <?php }?>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item-->
                <!-- <a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a> -->
                <!-- item-->
                 <!-- item-->
                <a href="<?php echo base_url();?>/users/logout" class="link" data-toggle="tooltip" title="Logout" style="padding-left: 35px;"><i class="mdi mdi-power"></i><span class="hide-menu">&nbspLogout</a>
            </div>
            <!-- End Bottom points-->
        </aside>

         <script type="text/javascript">

           function changeSize() { 
                                       var status = $("#size").val(); //alert(status); 
                                       $.ajax({ 
                                                type: "post",dataType: "json",
                                                //url:baseURL+'manager/Leave/findshifthours',
                                                url:baseURL+'admin/User/setSession',
                                                data: { 
                                                    status:status 
                                                },
                                                success: function(data) {
                                                
                                                } 

                                            });
                                       window.location.reload();        
                                     }
        </script>
         