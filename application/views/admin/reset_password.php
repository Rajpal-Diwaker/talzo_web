<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css">
     <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/talzo_logo.png">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
    <link rel="stylesheet" href=<?php echo base_url();?>"assets/css/media.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css">
    <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/materialize.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/custom.js"></script>
      <script src="<?php echo base_url();?>assets/js/custom.js"></script>
     <script src="<?php echo base_url();?>assets/js/validate.js"></script>
 <style type="text/css">
 label.errors{
  color: red;
  position: absolute;
  left: 52px;
  margin-top: -7px;
} 
</style>
</head>
<body>
    <div id="wrapper">
<div class="login_wrap">
<div class="login_bx">
<div class="login_card">
        <div class="logo_wrap">
    <img src="<?php echo base_url();?>assets/images/talzo_logo.png" alt="">
    </div>
<div class="login_cont">
<h2><b>Reset Password</b></h2>
<form id="resetform" name="resetform" action="<?php echo base_url();?>admin/adminforgot/reset_process" method="post">
<input type="hidden" name="email" value="<?php echo $data;?>">
<label for="">New Password</label>
<input type="password" placeholder="New Password" name="password" id="password">

<label for="">Confirm Password</label>
<input type="password" placeholder="Confirm password" id="password_confirm" name="password_confirm">
</div>
<div class=" btn_large_email">
 <button type="submit" id="submit" class="btn btn-next">SUBMIT</button>
</div>
</div>
</form>


</div>

</div>
     <!--Change Password Section End Here-->
    </div>

</body>

</html>
<script>
  $(function()
  {
    $("#resetform").validate({
      errorClass: 'errors',
       rules: {
                password: {
                    required: true,
                    minlength:6,
                    maxlength: 10
                },

                password_confirm: {
                  required:true,
                    equalTo: "#password"
                    
                }
            },
            messages: {
                password: { 
                    required: " Enter New Password",
                    minlength: "Password must be at least 6 character.",
                     maxlength: "Password should not exceed 10 character."

                },
                password_confirm:{

                    required: 'Enter Confirm Password',
                    equalTo: "Password and confirm password does not match."
                },
            },
  });
});
</script>