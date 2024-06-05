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
    <title><?php echo $site_title[0]['company_name'];?></title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/dropify/dist/css/dropify.min.css">

    
    <!-- chartist CSS -->
    <!-- toast CSS -->
    <!-- Custom CSS -->
    <link href="<?php echo base_url();?>assets/plugins/wizard/steps.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet">
    <?php 
    if(strpos(base_url(), 'erota') !== false){
    
    ?>
    <link rel="stylesheet" type="text/css" id="theme"  href="<?php echo base_url();?>assets/css/colors/blue.css">
	<?php } { ?>

    <link rel="stylesheet" type="text/css" id="theme"  href="<?php echo base_url();?>assets/css/colors/default.css">
    <?php } ?>    <link href="<?php echo base_url();?>assets/css/sweetalert.css" rel="stylesheet" />
    <link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>assets/css/jquery.datetimepicker.min.css" rel="stylesheet">

    <?php if($this->session->userdata('css_value')==3){?>
        <link href="<?php echo base_url();?>assets/css/eRota_extrasmall_font.css" rel="stylesheet">
    <?php } elseif ($this->session->userdata('css_value')==1) {?>
        <link href="<?php echo base_url();?>assets/css/eRota_Medium_font.css" rel="stylesheet">
    <?php } else {?>
        <link href="<?php echo base_url();?>assets/css/eRota_Small_font.css" rel="stylesheet">
    <?php } ?>
    

        
 
          <?php
if(isset($css_to_load))
{
foreach ($css_to_load as $value) 
     {?>
      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/<?php echo $value; ?>"> 
      <?php
    }?>
<?php
}
?> 

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
                            <!-- <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
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
                           <!--  <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-email"></i>
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
                                <?php }?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img"><?php if($profile_image[0]['profile_image']!=''){ ?>
                                <img src="<?php echo base_url('./uploads/').$profile_image[0]['profile_image'];?>" alt="user" class="profile-pic" />
                                <?php } else { ?>
                                <img src="<?php echo base_url('./uploads/').'default.png';?>" alt="user" class="profile-pic" />
                                <?php }?></div>
                                            <!-- <div class="u-text">
                                                <h4><?php echo $this->session->userdata('full_name'); ?></h4>
                                                <p class="text-muted"><?php echo $this->session->userdata('email'); ?></p><a href="<?php echo base_url();?>admin/user/edituser/<?php echo $this->session->userdata('user_id'); ?>" class="btn btn-rounded btn-danger btn-sm">View Profile</a></div> -->
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo base_url('staffs/');?>editprofile"><i class="ti-user"></i> My Profile</a></li>
                                    <!--  <li><a href="#"><i class="ti-email"></i> Inbox</a></li> -->
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
                            <a href="<?php echo base_url('staffs/');?>editprofile" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
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
                            <a href="<?php echo base_url();?>staffs/editprofile" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account"></i><span class="hide-menu">Profile</a>
                            
                        </li>
                        <?php }?>
                        <?php
                            $rota_view_status = get_group_permission_status($this->session->userdata('user_type'),'Rota.View');
                            $rota_availability_status = get_group_permission_status($this->session->userdata('user_type'),'Rota.Availability');
                        ?>
                        <?php if($rota_view_status == 1 || $rota_availability_status==1){?>
                        <li>
                            <a href="#" class="has-arrow "  aria-expanded="true"><i class="mdi mdi-format-rotate-90"></i><span class="hide-menu">Rota</span></a>                            
                            <ul aria-expanded="flase" class="collapse">
                                <?php if($rota_view_status == 1){?>
                                <li>
                                    <a href="" class="has-arrow rota_view"  aria-expanded="false">View</a>
                                </li>
                                <?php }?>
                                <?php if($rota_availability_status == 1){?>
                                <li>
                                    <a href="" class="has-arrow availability_view"  aria-expanded="false">Set Availability</a>
                                </li>
                                <?php }?>
                            </ul>
                        </li>
                        <?php }?>
                        <?php
                            $annual_leave_request_status = get_group_permission_status($this->session->userdata('user_type'),'Annual Leave.View');
                        ?>
                        <?php if($annual_leave_request_status == 1){?>
                        <li>
                            <!-- <a href="<?php echo base_url();?>staffs/leave" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-briefcase"></i><span class="hide-menu">Annual Leave</a> -->
                            <a href="JavaScript:Void(0);" class="has-arrow annual_leave_view"  aria-expanded="false"><i class="mdi mdi-briefcase"></i><span class="hide-menu">Annual Leave</a>
                            
                        </li>
                        <?php }?>
                        <?php
                            $mytaining_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Mytraining');
                        ?>
                        <?php if($mytaining_status == 1){?>
                         <li>
                            <!-- <a href="<?php echo base_url();?>staffs/training" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-hospital-building"></i><span class="hide-menu">My Training</a> -->
                            <a href="JavaScript:Void(0);" class="has-arrow training_view"  aria-expanded="false"><i class="mdi mdi-hospital-building"></i><span class="hide-menu">My Training</a>
                            
                        </li>
                        <?php }?>
                         <li>
                            <a class="has-arrow " href="#" aria-expanded="true"><i class="mdi mdi-account-card-details"></i><span class="hide-menu">My Reports</span></a>
                            <ul aria-expanded="flase" class="collapse">
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
                            $password_change_status = get_group_permission_status($this->session->userdata('user_type'),'Admin.Password.Change');
                        ?>
                        <?php if($password_change_status == 1){?>
                        <li>
                            <a href="<?php echo base_url();?>staffs/password" class="has-arrow"  aria-expanded="false"><i class="mdi mdi-account-key"></i><span class="hide-menu">Password Change</a>
                            
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
         