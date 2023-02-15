  <!-- User Details Section Start Here-->
  <style type="text/css">
    .errors_form{
        display: block;
        font-size: 14px;
        color:red;
        font-weight: bold;
        margin-left: 157px;
        margin-top: -11px;
    }
</style>
<div class="dashboard_data_wrap">
    <div class="dahaboard_heading">
        <span>User Details</span>
    </div>
    <!-- User Details Section Start Here-->
    <div class="dashbord_cont_wrapper">
        <div class="user_details_sec clearfix">
            <div class="row">
                <form id="user_form" name="user_form">
                    <input type="hidden" name="user_id" name="user_id" value="<?php echo $data['user_id'];?>">
                    <div class="col-sm-9 col-lg-6 col-md-8 col-xs-12">
                        <div class="user_profile_wrap clearfix ">
                            <label for="">User Name</label>
                            <input class="form-control" type="text" value="<?php echo $data['name'];?>" name="name" id="name" placeholder="User Name">
                            <span class="errors_form" id="name_error"></span>
                        </div>
                        <div class="user_profile_wrap clearfix ">
                            <label for="">Contact</label>
                            <input class="form-control" type="text" value="<?php echo $data['country_code'].'-'.$data['phone'];?>" id="phone" name="phone" placeholder="Contact No">
                        </div>
                        <div class="user_profile_wrap clearfix ">
                            <label for="">Email</label>
                            <input class="form-control" type="text" value="<?php echo $data['email'];?>" id="email" name="email" placeholder="Email">
                            <span class="errors_form" id="email_error"></span>
                        </div> 
                    </div>
                    <div class="col-sm-3 col-lg-3 col-md-4 col-xs-12">
                       <div class="edit_profile_img_wrapper">
                        <div class="edit_profile_img">
                            <?php if(!empty($data['user_image']) && isset($data['user_image'])){ ?>
                            <img id="profileimg" src="<?php echo $data['user_image'];?>" alt="">
                            <?php } else{ ?>
                            <img id="profileimg" src="../assets/images/upload_img.png" alt="">
                            <?php } ?>
                        </div>
                        <div class="edit_profile_upload-icon-img">
                            <i id="profileuploadicon" class="fa fa-camera" aria-hidden="true"></i>
                        </div>
                        <input id="profile_img_Upload" type="file" class="hidden-lg hidden-md hidden-sm hidden-xs" placeholder="Photo"
                        required="" capture="">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3 col-md-12 col-xs-12">
                    <div class="edit_del">
                        <button id="edit">Edit</button>
                        <button id="submit">Save</button>
                        <button id="profle_del" onclick="deleteuser(<?php echo $data['user_id'];?>)">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="user_follows">
        <div class="dashboard_wrap">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
                    <div class="dashboard_cont">
                        <h2><?php echo $dashboard['all_post'];?></h2>
                        <span>Number of posts</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
                    <div class="dashboard_cont">
                        <h2><?php echo $dashboard['all_follower'];?></h2>
                        <span>Number of followers</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
                    <div class="dashboard_cont">
                        <h2><?php echo $dashboard['all_following'];?></h2>
                        <span>Number of followings</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
                    <div class="dashboard_cont">
                        <h2><?php echo $dashboard['gold'];?></h2>
                        <span>Gold Stars</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
                    <div class="dashboard_cont">
                        <h2><?php echo $dashboard['silver'];?></h2>
                        <span>Silver Stars</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-4 col-xs-12">
                    <div class="dashboard_cont">
                        <h2><?php echo $dashboard['bronze'];?></h2>
                        <span>Bronze Stars</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cont_post_details">
        <div class="row">

           <div class="col-md-6 col-xs-6 padding_rt">
            <div class="heading_adds">
                <h2>Posts</h2>
            </div>
            <div class="view_post_btn_wrap"> 
                <a href="<?php echo base_url();?>admin/view-user-post"> <button>View Posts</button></a>
            </div>

        </div> 
        <div class="col-md-6 col-xs-6 padding_lt">
            <div class="heading_adds">
                <h2>Ads</h2>
            </div>
            <div class="view_post_btn_wrap"> 
                <a href="<?php echo base_url();?>admin/view-user-add"> <button>View Ads</button></a>
            </div>
        </div> 
    </div>
</div>
</div>
<!-- User Details Section End Here-->
</div>

<script>
$(document).ready(function(){
       $('#name,#phone,#email,#profileuploadicon').prop('disabled',true);
       $('#submit').hide();
        });
 $('#edit').click(function(){
        $('#submit').show();
        $('#name,#email,#profileuploadicon').prop('disabled',false);
        $('#name').focus();
        $('#phone').prop('disabled',true);
        $('#edit').hide();
});

    $(document).on('click', '#profileuploadicon', function () {
        $("#profile_img_Upload").click();
    });
    function fasterPreview(uploader) {
        if (uploader.files && uploader.files[0]) {
            $('#profileimg').attr('src',
                window.URL.createObjectURL(uploader.files[0]));
        }
    }
    $("#profile_img_Upload").change(function () {
        fasterPreview(this);
    });

//=========validation================//
var name_exp =/^[a-zA-Z\s]+$/;
var email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;

   //=========== name=======//
   $('#name').on('keyup',function(){
    var name = $("#name").val();
    if (!name_exp.test(name) && name!=''){ 
    $("#name_error" ).text("Please enter characters only." ).show(); 
    }else if (name.length >15 ){ 
    $( "#name_error" ).text( "User name should be less than 15 characters." ).show(); 
    }else if(name.length<= 0 || name.length <=15 ){
     $("#name_error" ).hide();
   }
 });

//=========Email=============//
    $('#email').on('keyup',function(){
      var email = $("#email").val();
      if (!email_regex.test(email) && email!='') { 
      $("#email_error" ).text( "Please enter valid email address." ).show()
  }else if(email_regex.test(email) && email!='') {
     $("#email_error" ).hide();
      }else if(email.length<= 0){
       $("#email_error" ).hide();
     } 
   }); 




    $('#submit').click(function(event){
        var formStatus=true;
        var name=$('#name').val();
        var email=$('#email').val();
       //=============== Name============//
 if (!name_exp.test(name)  && name!=''){ 
  formStatus = false; 
  $("#name_error" ).text( "Please enter characters only." ).show(); 
}else if (name.length >15 ) { 
  formStatus = false; 
  $( "#name_error" ).text( "User name should be less than 15 characters." ).show(); 
}
//=============Email===============//
 if (!email_regex.test(email) && email!='') { 
   formStatus = false; 
  $("#email_error").text( "Please enter valid email address." ).show(); 
}
if (formStatus==false) 
{ 
  event.preventDefault(); 
  return false;
}
else if (formStatus == true) {
        $(".loaderCntr").show();
        var data = new FormData($('#user_form')[0]);
        data.append('image',$('#profile_img_Upload')[0].files[0]);
        $.ajax({
            url: "<?php echo base_url()?>admin/admin/edit_user",
            type:'post',
            data:data,
            contentType: false,
            processData: false,
            success: function(response){
                $(".loaderCntr").hide();
                if(response == 1){
                    $('#myModal').modal('show');
                    $('#response').text('User detail edit successfully');
                    $('#submit').attr('disabled','true');
                }else if(response==2){
                    $('#myModal').modal('show');
                    $('#response').text('Something went wrong please try again!');
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
          <h2 style="text-align: center;" id="response"></h2>
      </div>
      <div class="modal-footer" style="border:0px!important;text-align: center;">
        <a class="btn btn-info" id="response_click" href=""> &nbsp;&nbsp;OK&nbsp;&nbsp;</a>
    </div>
</div>

</div>
</div>
</div>
<script type="text/javascript">
    $('#response_click').click(function(){
       window.location.reload();
   });

    function deleteuser(user_id){
      var user_id=user_id;
      bootbox.confirm("<b>Do you really want to delete this User?</b>", function(result) {
       if(result==true){
         $(".loaderCntr").show();
         location.href = 'admin/deleteUser?user_id='+user_id;
     }
 });
  }


</script>
</body>