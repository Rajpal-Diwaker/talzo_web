  <div class="dashboard_data_wrap">
    <div class="dahaboard_heading">
        <span>Report Details</span>
    </div>
    <!-- User Details Section Start Here-->
    <div class="dashbord_cont_wrapper">
        <div class="user_details_sec clearfix">
            <div class="row">
                    <div class="col-lg-7 col-xs-12">
                        <div class="user_profile_wrap clearfix ">
                            <label for="">Posted By</label>
                            <label><?php echo $data['name'];?></label>
                        </div>
                        <div class="user_profile_wrap clearfix ">
                            <label for="">Number of Reports</label>
                            <label><?php echo $data['report_count'];?></label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                    <div class="user_post_data">
                        <div class="post_data">
                        <?php 
                  $allowed =  array('mp4');
                  $ext=pathinfo($data['user_post'], PATHINFO_EXTENSION);
                  if(in_array($ext,$allowed)) { ?>
                  <video  height="100%" width="100%" controls>
                  <source src="<?php echo $data['user_post'];?>"></video>
                   <?php } else { ?>
                   <img src="<?php echo $data['user_post'];?>" alt="">
                   <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-6">
                    <div class="edit_del">
                        <span class="btn btn-success" id="profle_del" onclick="deletepost1(<?php echo $data['post_id'];?>)">Delete</span>
                    </div>
                </div>
        </div>
    </div>
     <div class="user_list_wrap table-responsive">
            <table class="table table-stripd" id="reporttable1"> 
              <thead>
                <th>S.No</th>
                <th>Posted By</th>
                <th>Reason</th>
                <th>Number Of<br> Reports</th>
                <!-- <th>Created at</th> -->
              </thead>
              <tbody> 
                <?php $i=1;
                foreach($list as $value){ ?>
                 <tr>
                   <td><?php echo $i?></td>
                   <td><?php echo $value['name'];?></td>
                   <td><?php echo $value['reason_title'];?></td>
                   <td><?php echo $value['count'];?></td>
              </tr>
              <?php
              $i++;
            } ?>

          </tbody>
        </table>

      </div>
  </div>
</div>
 <script type="text/javascript">
     $(document).ready(function() {
      $('#reporttable1').DataTable({
     });
    });

    function deletepost1(post_id){
  var post_id=post_id;
    bootbox.confirm("<b>Do you really want to delete this Post?</b>", function(result) {
       if(result==true){
         $(".loaderCntr").show();
        location.href = 'admin/deletepost?post_id='+post_id;
       }
    });
 }

</script>