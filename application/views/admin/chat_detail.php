
<!-- Dashboard Section Start Here-->
<div class="dashboard_data_wrap">
  <div class="dahaboard_heading">
    <span>Chat Conversation</span> <a href="#"></a>
  </div>
<div class="dashbord_cont_wrapper">
<div class="talzo_chat">
<section class="msger">
<header class="msger-header">
<div class="msger-header-title">
<i class="fas fa-comment-alt"></i> Chat</div>
</header>
<main class="msger-chat">


<?php 
// print_r($data);die;
foreach ($data as $key => $value) { 
  if($value['r_id']==$reciver_id){?>
  <div class="msg right-msg">
    <div class="msg-bubble">
      <div class="msg-info">
        <div class="msg-info-name"><?php echo $value['sender_name'];?></div>
        <div class="msg-info-time"><?php echo $value['date_added'];?></div>
      </div>

      <div class="msg-text">
        <?php if(!empty($value['url'])){
        $allowed =  array('mp4');
        $ext=pathinfo($value['url'], PATHINFO_EXTENSION);
        if(in_array($ext,$allowed)) { ?>
        <video width="150" height="100" class="popup" value="<?php echo $value['url'];?>" controls>
        <source src="<?php echo $value['url'];?>"></video>
        <?php } else { ?>
        <img width="150" height="100" class="popup" src="<?php echo $value['url'];?>" value="<?php echo $value['url'];?>" alt="">
        <?php
         }
         } else{
         echo $value['msg'];
        }?>
      </div>
    </div>
  </div>
<?php } else { ?>
  <div class="msg left-msg">
     <?php if(!empty($value['sender_image'])){
      $sender_image=$value['sender_image'];
    }else{
      $sender_image="https://image.flaticon.com/icons/svg/145/145867.svg";
    }
    ?>

 <!--  <div class="msg-img"
    style="background-image: url(<?php echo $sender_image;?>)"
    ></div> -->

    <div class="msg-bubble">
      <div class="msg-info">
        <div class="msg-info-name"><?php echo $value['sender_name'];?></div>
        <div class="msg-info-time"><?php echo $value['date_added'];?></div>
      </div>

      <div class="msg-text">
        <?php if(!empty($value['url'])){
        $allowed =  array('mp4');
        $ext=pathinfo($value['url'], PATHINFO_EXTENSION);
        if(in_array($ext,$allowed)) { ?>
        <video width="150" height="100" class="popup" value="<?php echo $value['url'];?>" controls>
        <source src="<?php echo $value['url'];?>"></video>
        <?php } else { ?>
        <img width="150" height="100" class="popup" src="<?php echo $value['url'];?>" value="<?php echo $value['url'];?>" alt="">
        <?php
         }
         } else{
         echo $value['msg'];
        }?>
      </div>
    </div>
  </div>
<?php
}
}
 ?>
</main>
</section>

</div>       
</div>



<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
        <video height="300" width="573" id="show_video" controls>
        <source ></video>
          <img id="show_image" height="300" width="573">
        </div>
      </div>
    </div>
  </div>
</div>

</body>
<script type="text/javascript">
  $('.popup').on('click',function(){
  $('#show_video').attr('src')=="";
  $('#show_image').attr('src')=="";
  var filename='';
  filename=$(this).attr("value");
   $('#myModal').modal('show');
  var ext = $(filename.split('.').pop().toLowerCase());
  if($.inArray(ext, ['mp4']) == -1) {
   $('#show_video').attr('src', filename);
   $('#show_image').remove();
  }else{
     $('#show_image').attr('src', filename);
      $('#show_video').remove();
  }
  })
</script>
</html>