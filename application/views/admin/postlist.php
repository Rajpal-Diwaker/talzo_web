       <div class="dashboard_data_wrap">
          <div class="dahaboard_heading">
              <span>Post List</span>
            </div>
        <!-- User List Section Start Here-->
       <div class="dashbord_cont_wrapper">
          <div class="user_list_wrap table-responsive">
            <table class="table table-stripd" id="posttable"> 
              <thead>
                <th>S.No</th>
                <th>Post</th>
                <th>Posted By</th>
                <th>Details</th>
                <th>Status</th>
                <th class="table_data_center">Action</th>
              </thead>
            </table>

          </div>
        </div>
    <script type="text/javascript">
    $(document).ready(function(){
       var userDataTable = $('#posttable').DataTable({
         'processing': true,
         'serverSide': true,
         'serverMethod': 'post',
         'ajax': {
            'url':'<?=base_url()?>admin/Admin/get_posts',
            'data': function(data){
            }
         },
         'columns': [
            {data: 'no'},
            { data: 'user_post' },
             { data: 'name' },
            { data: 'description' },
            {data:'manage'},
            {data:'action'}
           
         ],
         'columnDefs': [{
                "targets": [-1,-2,-5],
                "orderable": false
            }]
       });
    });

   function deletepost(post_id){
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