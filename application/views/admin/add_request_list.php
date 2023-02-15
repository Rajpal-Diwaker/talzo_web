       <div class="dashboard_data_wrap">
          <div class="dahaboard_heading">
              <span>Add Request List</span>
            </div>
       <div class="dashbord_cont_wrapper">
          <div class="user_list_wrap table-responsive">
            <table class="table table-stripd" id="requesttable">
              <thead>
                <th>S.No</th>
                <th>Advertisement</th>
                <th>Posted By</th>
                <th>Approve</th>
                 <th>Action</th>
              </thead>
                 <tbody> 
                <?php
                $i=1;
               foreach($data as $value){ ?>
               <tr>
                   <td><?php echo $i?></td>
                   <td>
            <?php $allowed =  array('mp4');
            $ext=pathinfo($value['user_add'], PATHINFO_EXTENSION);
             if(in_array($ext,$allowed)) { ?>
            <video  height="180" controls>
            <source src="<?php echo $value['user_add'];?>"></video>
            <?php } else { ?>
            <img src="<?php echo $value['user_add'];?>" height="75" alt="">
            <?php } ?>
           </td>
          <td><?php echo $value['name'];?></td>
          <td>
         <button  class='btn btn-success' onclick="approve(<?php echo $value['add_id'];?>)">Approve</button>
           </td>
          <td>
          <a href="<?php echo base_url();?>add_view?id=<?php echo $value['add_id'];?>"><i style="font-size:25px;color:green;" class="fa fa-eye" aria-hidden="true"></i></a>
          <a href="<?php echo base_url();?>add_delete?id=<?php echo $value['add_id'];?>"><i style="font-size:25px;color:red;" class="fa fa-trash" aria-hidden="true"></i></a>
          </td>
          </tr>
          <?php $i++;} ?>
           </tbody>
          </table>
          </div>
        </div>
    <script type="text/javascript">
     $(document).ready(function() {
    $('#requesttable').DataTable({
       columnDefs: [
   { orderable: false, targets: -1 },
   { orderable: false, targets: -2 },
   { orderable: false, targets: -4 }
  ]
});
});
   function deletesubs(subs_id){
  var subs_id=subs_id;
    bootbox.confirm("<b>Do you really want to delete this Subscription?</b>", function(result) {
       if(result==true){
         $(".loaderCntr").show();
        location.href = 'admin/deletesubscription?subs_id='+subs_id;
       }
    });
 }

//================edit subs=============//

 function editsubs(subs_id){
  var subs_id=subs_id;
  $(".loaderCntr").show();
 location.href = 'edit_subscription?subs_id='+subs_id;   
 }

//=============status function==================//
function approve(add_id){
   var add_id=add_id;
          event.preventDefault();
        bootbox.confirm("<b>Do you really want to allow this add?</b>", function(result) {
            if (result==true) {
                $(".loaderCntr").show();
                location.href = 'admin/approve_request?add_id='+add_id;
            } 
        });
  };
</script>
<?php $msg=$this->session->flashdata('msg');?> 
<?php if(!empty($msg)){ ?>
  <script type="text/javascript">
    $('#popup').html('<?php echo $msg;?>');
    $('#myModal').modal('show');
  </script>
<div id="myModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog thanksbx">
    <div class="modal-content">
      <div class="modal-body">
      <h2 id="popup" style="text-align: center;"></h2>
      </div>
      <div class="modal-footer" style="border:0px!important;text-align: center;">
        <a class="btn btn-info" href="<?php echo base_url();?>admin/add_request-list"> &nbsp;&nbsp;OK&nbsp;&nbsp;</a>
      </div>
    </div>

  </div>
</div>
  <?php } ?>