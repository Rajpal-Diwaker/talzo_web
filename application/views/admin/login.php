<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/talzo_logo.png">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/validate.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/media.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css">
    <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/custom.js"></script>
     <script src="<?php echo base_url();?>assets/js/validate.js"></script>
     <style type="text/css">
        label.errors{
        color: red;
        margin-top:-5px; 
     </style>
</head>

<body>
    <div id="wrapper">
        <!--Login Section Start Here-->


        <div class="login_wrap">
            <div class="login_bx">
                <div class="login_card">
                    <div class="logo_wrap">
                        <img src="<?php echo base_url();?>assets/images/talzo_logo.png" alt="">
            </div>
            <div class=" login_cont">
                            <label for="">Login</label>
                              <?php $msg=$this->session->flashdata('msg');?>
                            <h3><?php if(!empty($msg)){ echo $msg;}?></h3>
                      <form id="loginform" name="loginform" action="<?php echo base_url();?>admin/Login/login_process" method="post">
                            <input class="form-contorl" type="text" placeholder="Enter your Email" name="email" id="email">
                            <label for="">Password</label>
                            <input type="password" placeholder="Enter your password"
                             name="password" id="password">
                    </div>
                    <div class="btn_large_email">
                        <button>Login</button>
                    </div>
                    </form>
                    <a href="<?php echo base_url();?>admin/forgot"><h2>Forgot Password?</h2></a>

                </div>


            </div>



        </div>

    </div>
    <!--Login Section End Here-->
</body>
 <script>
    $("#loginform").validate({
      errorClass: 'errors',
      rules: {
            email: {
                    required: true,
                    email: true 
                    },
            password:
            { 
              required: true,
              minlength:6,
              maxlength:10
            },
          },
    message: {
              email: {
                       required:"Please fill the email id.",
                       email:"Please enter a valid email address"
                    },
              password:
              {
                  required:"Please fill the password.",
                  minlength: "Password must be at least 6 character.",
                 maxlength: "Password should not exceed 10 character."
              },
             },

    });
              
 setTimeout(function(){ 
 $("#error").hide();
},
  3000);
</script>
</html>