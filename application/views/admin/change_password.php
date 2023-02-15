 <link rel="stylesheet" href="<?php echo base_url();?>assets/css/validate.css">
 <script src="<?php echo base_url();?>assets/js/validate.js"></script>
<style type="text/css">
    label.errors1 {
    color: red;
   font-size: 14px;
   display: block;
   font-weight: bold;
   margin-top:0px;
 } 
</style>
<body>
<div class="login_wrap">
<div class="login_bx">
<div class="login_card">
<form id="change_password_form" name="change_password_form">
<div class="login_cont">
<label for="">Old Password</label>
<input type="password" placeholder="Old Password" id="old_password" name="old_password">
<label for="">New Password</label>
<input type="password"   placeholder="New Password" id="new_password" name="new_password">
<label for="">Confirm Password</label>
<input type="password" placeholder="Confirm password" id="confirm_password" name="confirm_password">
</div>
<div class=" btn_large_email">
<button>Submit</button>
</div>
</form>
</div>
</div>
</div>
<script>
  $("#change_password_form").validate({
    errorClass: 'errors1',
    rules: {
     old_password: {
      required: true,
      minlength:6,
      maxlength: 10
    },
    new_password: {
      required: true,
      minlength:6,
      maxlength: 10
    },

    confirm_password: {
      required:true,
      equalTo: "#new_password"

    }
  },
  messages: {
   old_password: { 
    required: "Enter Old Password",
    minlength: "Password must be at least 6 characters.",
    maxlength: "Password should not exceed 10 characters."

  },
  new_password: { 
    required: "Enter New Password",
    minlength: "Password must be of at least 6 characters.",
    maxlength: "Password should not exceed 10 characters."

  },
  confirm_password:{
    required: 'Enter Confirm password',
    equalTo: "New and Confirm password does not match."
  },
},
submitHandler: function(form){
  event.preventDefault();
  $(".loaderCntr").show();
  $('form input[type="submit"]').prop("disabled", true); 
  $.ajax({
    url: "<?php echo base_url()?>admin/Admin/change_password_process",
    type: 'POST',
    data:$("#change_password_form").serialize(),
    success: function(response)
    {
     $(".loaderCntr").hide();
     if(response == 1){
       $('#submit').attr('disabled',true);
       $('#myModal').modal('show');
     }
     if(response == 0){
       $('#submit').attr('disabled',true);
       $('#myModal1').modal('show');
     } 
   }
 }); 
}       
});
$('#reload').on('click',function(){
   window.location.reload(); 
 }) 
</script>
<div id="myModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog thanksbx">
    <div class="modal-content">
      <div class="modal-body"><h2 style="text-align: center;">Password changed successfully</h2>
      </div>
      <div class="modal-footer" style="border:0px!important;text-align: center;">
        <a class="btn btn-info" href="<?php echo base_url();?>admin/login"> &nbsp;&nbsp;OK&nbsp;&nbsp;</a>
      </div>
    </div>

  </div>
</div>
  <!-- second modal -->
  <div id="myModal1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog thanksbx">
    <div class="modal-content">
      <div class="modal-body">
  <h2 style="text-align: center;">Old password is incorrect please try again!</h2>
      </div>
      <div class="modal-footer" style="border:0px!important;text-align: center;">
        <a class="btn btn-info" id="reload" href=""> &nbsp;&nbsp;OK&nbsp;&nbsp;</a>
      </div>
    </div>

  </div>
</div>

</div>
</body>

</html>