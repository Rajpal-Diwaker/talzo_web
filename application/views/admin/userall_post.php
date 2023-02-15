  <!-- User Details Section Start Here-->

  <div class="dashboard_data_wrap">
    <div class="dahaboard_heading">
        <span>User Posts</span>
        <a href="#"></a>
    </div>
    <!-- User Details Section Start Here-->
    <div class="dashbord_cont_wrapper">
       <div class="cont_post_details">
      <div class="row packege_wrap">
      <!-- <div class="col-md-12"> -->
     <?php $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;  
       $page+=1;
     foreach($user_post as $value){ ?>
                <div class="col-sm-6 col-lg-3">
                <div class="view_post">
                <div class="post_img">
                 <?php 
                $allowed =  array('mp4');
                $ext=pathinfo($value['user_post'], PATHINFO_EXTENSION);
                if(in_array($ext,$allowed)) { ?>
                  <video controls>
                  <source src="<?php echo $value['user_post'];?>"></video>
                   <?php } else { ?>
                   <img src="<?php echo $value['user_post'];?>" alt="">
                   <?php } ?>
                        </div>
                        <h2><?php echo $value['description'];?></h2>
                    </div>
                </div>
           <?php $page++; }
            if(empty($user_post)){ ?>
            <h2>No Data found</h2>
            <?php }?>
            <!-- </div> -->
        </div>
    </div>
    <div class="pagination_wrap">
  <div class="pagination">
 <?php echo($links); ?>
  </div>
</div>
</div>
<!-- User Details Section End Here-->
