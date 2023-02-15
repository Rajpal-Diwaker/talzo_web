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
<h1 style="text-align: center;"><b>Create Subscription</b></h1>
<div class="login_card">
<form id="add_subscription_form" name="add_subscription_form">
<div class="login_cont">
<label for="">Subscription Name</label>
<input type="text" placeholder="Subscription Name" id="subscription_name" name="subscription_name" autocomplete="off">
<label for="">Subscription Price</label>
<input type="text" placeholder="Subscription Price" id="subscription_price" name="subscription_price" autocomplete="off">
</div>
<div class="btn_large_email">
<button id="submit">Create</button>
</div>
</form>
</div>
</div>
</div>
<script>
  $("#add_subscription_form").validate({
    errorClass: 'errors1',
    rules: {
     subscription_name: {
      required: true,
      maxlength: 30
    },
    subscription_price: {
      required: true,
      digits:true,
      maxlength:6
    }
  },
  messages: {
   subscription_name: { 
    required: "Please enter subscription name",
    maxlength: "Subscription name should not exceed 10 characters."
  },
  subscription_price: { 
    required: "Please enter subscription price",
     digits:"Please enter only digits",
    maxlength: "Subscription price should not exceed 6 digits."

  }
},
submitHandler: function(form){
  event.preventDefault();
  $(".loaderCntr").show();
  $('#submit').prop("disabled", true); 
  $.ajax({
    url: "<?php echo base_url()?>admin/Admin/create_subscription_process",
    type: 'POST',
    data:$("#add_subscription_form").serialize(),
    success: function(response){
     $(".loaderCntr").hide();
     if(response == 1){
       $('#submit').attr('disabled',true);
       $('#myModal').modal('show');
       $('#popup').text('Subscription add successfully');
     }
     if(response == 0){
       $('#submit').attr('disabled',true);
       $('#myModal').modal('show');
       $('#popup').text('Something went wrong please try again!');
     } 
   }
 }); 
}       
});
</script>
<div id="myModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog thanksbx">
    <div class="modal-content">
      <div class="modal-body">
      <h2 id="popup" style="text-align: center;"></h2>
      </div>
      <div class="modal-footer" style="border:0px!important;text-align: center;">
        <a class="btn btn-info" href="<?php echo base_url();?>admin/subscription-list"> &nbsp;&nbsp;OK&nbsp;&nbsp;</a>
      </div>
    </div>

  </div>
</div>
</body>

</html>