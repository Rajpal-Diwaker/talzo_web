
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<style type="text/css">
 label.checkbox{
   width: 100%!important;
  }
  .multi_error{
    color: red;
    font-weight: bold;
    display: block;
    margin-left: 209px;
  }
</style>

<div class="dashboard_data_wrap">
<h2 align="center"><b>Send Notification</b></h2>
 <div class="dashbord_cont_wrapper">
  <div class='send_notification_wrap'>
    <form method="post" id="send_form">
      <div class="form-group form-inline">
        <label>To</label>
        <div class="multi_drop_down_wrap">
          <i class="fa fa-caret-down" aria-hidden="true"></i>
          <select id="multiselect" class="form-control check" multiple="multiple" name="user_id[]">
          <?php foreach ($data as $value) { ?>
          <option value="<?php echo $value['user_id'];?>"><?php echo $value['name'];?></option><?php } ?>
          </select>
        </div> 
        <span id="multiselect_error" class="multi_error"></span> 
        </div>
        <div class="form-group form-inline">
          <label>Message</label>
          <textarea class="form-control" id="message" name="message" placeholder="Write"></textarea>
          <span id="message_error" class="multi_error"></span>
        </div>
        <button type="submit" id="submit" class="send">Submit</button>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
 $('#multiselect').multiselect({
  includeSelectAllOption: true,
  maxHeight: 150,
  buttonWidth: 150,
  numberDisplayed: 2,
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  nSelectedText: 'selected',
  nonSelectedText: 'Select users'
});
 $(document).on('change','#multiselect',function(){
 $('#multiselect_error').hide();
 });

 $('#message').on('keyup',function(){
      var message = $("#message").val();
      if (message.length >50 ){ 
       $( "#message_error" ).text( "Message should be less than 50 characters." ).show(); 
      }else if(message.length<= 0 || message.length <=50 ){
       $("#message_error" ).hide();
       }
    });
$('#submit').click(function(){
   event.preventDefault();
  var formStatus=true;
  var count1 = 0;
$("#multiselect").each(function() {
  var val = $(this).val();
  if(val!=''){
    count1 = count1+1;
  }
})
if (count1 <=0){
 formStatus = false; 
 $("#multiselect_error" ).text( "Please select minimum one user.").show();
}
if($('#message').val()==''){
  formStatus = false; 
  $("#message_error" ).text("Please enter message.").show();
}
else if ($('#message').val().length >50){ 
formStatus = false; 
$( "#message_error" ).text( "Message should be less than 50 characters." ).show(); 
}
if(formStatus==false){
 event.preventDefault();
}else{
  $(".loaderCntr").show();
  // $('#submit').prop("disabled", true); 
   $.ajax({
    url: "<?php echo base_url()?>admin/Admin/send_notification_process",
    type: 'POST',
    data:$("#send_form").serialize(),
    success: function(response){
     $(".loaderCntr").hide();
     if(response == 1){
       $('#submit').attr('disabled',true);
       $('#myModal').modal('show');
       $('#popup').text('Notification send successfully');
     }
     if(response == 0){
       $('#submit').attr('disabled',true);
       $('#myModal').modal('show');
       $('#popup').text('Something went wrong please try again!');
     } 
   }
 }); 
} 
})//===submit end======//
</script>
<div id="myModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog thanksbx">
    <div class="modal-content">
      <div class="modal-body">
      <h2 id="popup" style="text-align: center;"></h2>
      </div>
      <div class="modal-footer" style="border:0px!important;text-align: center;">
        <a class="btn btn-info" href="<?php echo base_url();?>admin/send-push"> &nbsp;&nbsp;OK&nbsp;&nbsp;</a>
      </div>
    </div>

  </div>
</div>
</script>