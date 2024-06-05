<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Users/login';
//$route['default_controller'] = 'Calendar';
$route['404_override'] = 'utility/error_page';
$route['translate_uri_dashes'] = FALSE;

$route['adminlogin'] ='Users/login';
$route['tableimport'] ='Users/import_tables';
$route['admin/manageuser'] ='admin/User/index';
$route['forgotpassword']='Users/forgotpassword';
$route['resetpassword']='Users/resetpassword';
$route['admin/managedesignation'] ='admin/Designation/index';
$route['admin/managepayment'] ='admin/Payment/index';
$route['admin/manageunit'] = 'admin/Unit/index';
$route['admin/managegroup'] = 'admin/Group/index';
$route['admin/manageshift'] = 'admin/Shift/index';
$route['admin/holiday'] = 'admin/Holiday/index';
$route['admin/managerota'] = 'admin/Rota/index';
$route['admin/managejobrolegroup'] = 'admin/JobRoleGroup/index';

$route['staffs/editprofile'] = 'staffs/Profile/index';
$route['staffs/password'] = 'staffs/Password/index';

$route['manager/editprofile'] = 'manager/Profile/index';
$route['manager/password'] = 'manager/Password/index';
$route['manager/rota'] = 'manager/Rota/index'; 

$route['manager/rota'] = 'manager/Rota/index';
$route['manager/createrota'] = 'manager/Rota/createrota';

$route['staffs/rota'] = 'staffs/Rota/index';
$route['staffs/createrota'] = 'staffs/Rota/createrota';

$route['admin/adduser'] ='admin/User/addUser'; 
