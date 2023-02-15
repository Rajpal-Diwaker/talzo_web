       <div class="dashboard_data_wrap">
        <div class="dahaboard_heading">
          <span>Report Content List</span>
        </div>
        <!-- User List Section Start Here-->
        <div class="dashbord_cont_wrapper">
          <div class="user_list_wrap table-responsive">
            <table class="table table-stripd" id="reporttable"> 
              <thead>
                <th>S.No</th>
                <th>Post</th>
                <th>Posted By</th>
                <th>Number Of<br> Reports</th>
                <th>view</th>
                <th>Status</th>
              </thead>
              <tbody> 
                <?php $i=1;
                foreach($data as $value){ ?>
                 <tr>
                   <td><?php echo $i?></td>
                  <td>
                    <div class="report_posts">
                 <?php $allowed =  array('mp4');
                $ext=pathinfo($value['user_post'], PATHINFO_EXTENSION);
                if(in_array($ext,$allowed)) { ?>
                 <video controls>
                <source src="<?php echo $value['user_post'];?>"></video>
                <?php } else { ?>
                <img src="<?php echo $value['user_post'];?>" alt="" >
                <?php } ?>
                </div>
                </td>
                  <td><?php echo $value['name'];?></td>
                  <td align="center"><?php echo $value['reportcount'];?></td>
                <td><a href="<?php echo base_url()."admin/report_detail?post_id=".$value['post_id'];?>" class="btn btn-info">View</a></td>
                <td>
                  <?php if($value['post_status'] == '1'){ ?>
                  <button  class='btn btn-danger' onclick="status(this,<?php echo $value['post_id'];?>)">Block</button>
                   <?php }else{?>
                  <button  class='btn btn-success' onclick="status(this,<?php echo $value['post_id'];?>)">Un-block</button>
                  <?php } ?>
                </td>
              </tr>
              <?php
              $i++;
            } ?>

          </tbody>
        </table>

      </div>
    </div>
    <script type="text/javascript">
     $(document).ready(function() {
      $('#reporttable').DataTable({
       columnDefs: [
       { orderable: false, targets: -1 },
       { orderable: false, targets: -2 },
       { orderable: false, targets: -5 }
       ]
     });
    });


//=============status function==================//
function status(element,post_id) {
  // console.log(post_id)
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