  <div class="dashboard_data_wrap">
    <div class="dahaboard_heading">
        <span>Post Details</span>
    </div>
    <!-- User Details Section Start Here-->
    <div class="dashbord_cont_wrapper">
        <div class="user_details_sec clearfix">
            <div class="row">
                <form id="user_form" name="user_form">
                <input type="hidden" name="user_id" name="user_id" value="<?php echo $data['user_id'];?>">
                    <div class="col-lg-7 col-xs-12">
                        <div class="user_profile_wrap clearfix ">
                            <label for="">Posted By</label>
                            <label><?php echo $data['name'];?></label>
                        </div>
                        <div class="user_profile_wrap clearfix ">
                            <label for="">Number of views</label>
                            <label><?php echo $data['views'];?></label>
                        </div>
                        <div class="user_profile_wrap clearfix ">
                            <label for="">Number of stars</label>
                           <label><?php echo $data['stars'];?></label>
                        </div> 
                        <div class="user_profile_wrap clearfix ">
                            <label for="">Details</label>
                           <label><?php echo $data['description'];?></label>
                        </div>
                         <div class="user_profile_wrap clearfix ">
                        <label for="">Active/Inactive</label>
                        <?php if($data['post_status']=='1'){ ?> 
                         <label>
    <span class="btn btn-danger" id="status_<?php echo $data['post_id'];?>" onclick="status(this,'<?php echo $data['post_id'];?>');" style="width:76px;">Block</span></label><?php } else {?>
<label>
<span class="btn btn-success" id="status_<?php echo $data['post_id'];?>"  onclick="status1(this,<?php echo $data['post_id'];?>);" style="width:76px;">Unblock</span></label><?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <div class="user_post_data">
                    <div class="post_data">
                  <?php 
                  $allowed =  array('mp4');
                  $ext=pathinfo($data['user_post'], PATHINFO_EXTENSION);
                  if(in_array($ext,$allowed)) { ?>
                  <video controls>
                  <source src="<?php echo $data['user_post'];?>"></video>
                   <?php } else { ?>
                   <img src="<?php echo $data['user_post'];?>" alt="">
                   <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-12">
                    <div class="edit_del">
                        <span class="btn btn-success" id="profle_del" onclick="deletepost1(<?php echo $data['post_id'];?>)">Delete</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script type="text/javascript">
    function deletepost1(post_id){
  var post_id=post_id;
    bootbox.confirm("<b>Do you really want to delete this Post?</b>", function(result) {
       if(result==true){
         $(".loaderCntr").show();
        location.href = 'admin/deletepost?post_id='+post_id;
       }
    });
 }

//=============status function==================//
function status(element,post_id) {
 var hasclass=$(element).hasClass('btn-success');
 if(hasclass ==false){
    var showtext ="<b>Do you really want to Block this Post?<b>";
  }else{
    var showtext ="<b>Do you really want to Un-Block this Post?<b>";
  }
bootbox.confirm(showtext, function(result) {
if(result==true){
if( $(element).hasClass('btn-success')){
var status='1';
$(element).text('Block');
$(element).removeClass('btn-success').addClass('btn-danger');
}
else 
{
var status = '0';
$(element).text('Unblock');
$(element).removeClass('btn-danger').addClass('btn-success');
}
$.ajax({
type: 'POST',
url: '<?php echo base_url(); ?>admin/admin/block_post',
data: {
'id': post_id,
'active':status
},
success: function(data) {
}
});
}
});
}
</script>