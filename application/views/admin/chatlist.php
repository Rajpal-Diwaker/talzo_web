       
       <div class="dashboard_data_wrap">
          <div class="dahaboard_heading">
              <span>Chat List</span>
            </div>
        <!-- User List Section Start Here-->
       <div class="dashbord_cont_wrapper">
          <div class="user_list_wrap table-responsive">
            <table class="table table-stripd" id="ChatTable"> 
              <thead>
                <th align="center">S.No</th>
                <th align="center">User</th>
                <th align="center">No Of User</th>
                <th align="center">No Of Conversations</th>
                <th class="table_data_center">Action</th>
              </thead>
            </table>

          </div>
        </div>
    <script type="text/javascript">
    $(document).ready(function(){
       var userDataTable = $('#ChatTable').DataTable({
         'processing': true,
         'serverSide': true,
         'serverMethod': 'post',
         'ajax': {
            'url':'<?=base_url()?>admin/Admin/get_chats',
            'data': function(data){
            }
         },
         'columns': [
            {data: 'no'},
            { data: 'users' },
             { data: 'no_of_users' },
            { data: 'msg_count' },
            {data:'action'}
           
         ],
         'columnDefs': [{
                "targets": [-1,-2,-3,-4,-5],
                "orderable": false
            }]
       });
    });
</script>