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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
//=============Admin routes=================//
$route['admin/forgot']='admin/Adminforgot/forgot';
$route['admin/reset']='admin/Adminforgot/reset';
$route['admin/status']='admin/Adminforgot/status';
$route['admin/login']='admin/Login/login';
$route['admin/dashboard']='admin/Admin/dashboard';
$route['admin/user-list']='admin/Admin/user_list';
$route['admin/user_detail']='admin/Admin/user_detail';
$route['admin/post-list']='admin/Admin/post_list';
$route['admin/post_detail']='admin/Admin/post_detail';
$route['admin/view-user-post']='admin/Admin/view_user_post';
 $route['admin/view-user-post/(:any)']='admin/Admin/view_user_post';
$route['admin/view-user-add']='admin/Admin/view_user_add';
$route['admin/send-push']='admin/Admin/send_push';
$route['admin/change_password']='admin/Admin/change_password';
$route['admin/subscription-list']='admin/Admin/subscription_list';
$route['admin/edit_subscription']='admin/Admin/edit_subscription';
$route['admin/create-subscription']='admin/Admin/create_subscription';
$route['admin/report-list']='admin/Admin/report_list';
$route['admin/report_detail']='admin/Admin/report_detail';
$route['admin/add_request-list']='admin/Admin/add_request_list';
$route['admin/send_notification_process']='admin/Admin/send_notification_process';
$route['admin/chat-list']='admin/Admin/chat_list';
$route['admin/chat_detail']='admin/Admin/chat_detail';

