<!DOCTYPE html>
<html lang="en">

<head>
  <title>Talzo Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/talzo_logo.png">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/media.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css">
  <link  rel="stylesheet" href="<?php echo base_url();?>assets/css/datatable.css">
  <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>

  <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/custom.js"></script>
   <script src="<?php echo base_url();?>assets/js/bootstrap-select.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/bootbox.js"></script>
  <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/canvasjs.min.js"></script> -->
<script src="<?php echo base_url();?>assets/js/datatable.js"></script>
</head>

<body>
<div class="backslidemenu"></div>
  <div class="loaderCntr" style="display: none;">
    <div class="lds-hourglass"></div>
  </div>
  <div id="wrapper">
    <!-- Sidebar Section Start Here-->
    <div class="side_bar ">
       
      <div class="logo_wrap">
        <a href="<?php echo base_url();?>admin/dashboard"><img src="<?php echo base_url();?>assets/images/talzo_logo.png" alt=""></a>
      </div>
      <div class="sidebar_menu_wrap">
        <div class="sidebar_menu">
          <ul>
            <li></span><a href="<?php echo base_url();?>admin/dashboard">My Dashboard</a></li>
            <li><a href="<?php echo base_url();?>admin/user-list">Users</a></li>
            <li><a href="<?php echo base_url();?>admin/report-list"> Reported Content</a></li>
          <li><a href="<?php echo base_url();?>admin/chat-list">Messages</a></li>
         <li class="menu_toggle_wrap collapsed" data-toggle="collapse" href="#collapse1">
              <a href="#">Advertisement<i class="fa fa-angle-up"
                aria-hidden="true"></i> </a>
          </li> 
             <div id="collapse1" class="panel-collapse collapse">
                <ul>
                  <li><a href="<?php echo base_url();?>admin/add_request-list">Add request</a>
                  </li>
                  <li><a href="<?php echo base_url();?>admin/add_request-list">Active ads</a>
                  </li>
                 </ul>
              </div>
            <li><a href="<?php echo base_url();?>admin/subscription-list"> Subscription</a></li>
            <li><a href="<?php echo base_url();?>admin/post-list">Posts</a></li>
            <li><a href="javascript:void(0)" target="_blank"> Transactions</a></li>
            <li><a href="<?php echo base_url();?>admin/send-push"> Send Push</a></li>
           </ul>
        </div>
      </div>
    </div>
    <!-- Sidebar Section End Here-->
    <div class="dashboard_header_wrap clearfix">
      <!-- Header Section Start Here-->
      <div class="header_wrapper">
        <div class="toggle_wrap">
         <a href="javascript:void(0)" class="atab-menu"><i class="fa fa-bars tab-menu" aria-hidden="true"></i></a>
          <a href="javascript:void(0)" class="atab-menumb"><i class="fa fa-bars tab-menu" aria-hidden="true"></i></a>
           <a href="javascript:void(0)" class="btn-close-menu"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
        <!-- <div class="page_heading"><span>Dashboard</span></div> -->
        
        <div class="user_detail_wrap" aria-labelledby="dropdownMenuButton">
          <!-- <div class="notiofy"><img src="<?php echo base_url();?>assets/images/bell.png" alt="">
          <span class="notification_number_bx">99</span>
          </div> -->
          <a class="waves-effect" href="javascript:void(0)" id="dropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false"><img src="<?php echo base_url();?>assets/images/admin_user_pic.jpg" alt="">My Account<i
              class="fa fa-angle-down" aria-hidden="true"></i>
          </a>
          <div class="header_drop_content dropdown-menu" aria-labelledby="dropdownMenuButton">
            <ul>
              <li><a href="<?php echo base_url();?>admin/change_password"><i class="fa fa-lock" aria-hidden="true"></i>Change Password</a>
              </li>
              <li><a href="<?php echo base_url();?>admin/login/logout"><i class="fa fa-sign-in" aria-hidden="true"></i>
                  Logout</a>
              </li>
            </ul>
          </div>
        </div>

      </div>
          <!-- Header Section End Here-->
        