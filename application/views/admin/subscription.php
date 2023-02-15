       <div class="dashboard_data_wrap">
          <div class="dahaboard_heading">
              <span>Subscription List</span>
            </div>
          <div class="row">
            <div class="col-md-12">
               <a style="float:right;margin-top: 15px; margin-right: 40px;" href="<?php echo base_url();?>admin/create-subscription"><button class="btn btn-success">Create Subscription</button></a> 
            </div>
          </div>
     
       <div class="dashbord_cont_wrapper">
          <div class="user_list_wrap table-responsive">
            <table class="table table-stripd" id="subscriptiontable"> 
              <thead>
                <th>S.No</th>
                <th>Name</th>
                <th>Price</th>
                <th style="text-align: center;">Status</th>
                 <th style="text-align: center;">Action</th>
              </thead>
                 <tbody> 
                <?php
                $i=1;
               foreach($data as $value){ ?>
               <tr>
                   <td><?php echo $i?></td>
                   <td><?php echo $value['subs_name'];?></td>
                   <td><?php echo '$'.$value['price'];?></td>
                   <td style="text-align: center;">
        <?php if($value['subs_status'] == '1'){ ?>
         <button  class='btn btn-danger' onclick="blocksubs(this,<?php echo $value['subs_id'];?>)">In-active</button>
        <?php }else{?>
        <button  class='btn btn-success' onclick="blocksubs(this,<?php echo $value['subs_id'];?>)">Active</button>
                    <?php } ?>
                    </td>
                 <td style="text-align: center;">
          <button class='btn btn-success' onclick="editsubs('<?php echo base64_encode($value['subs_id']);?>')">Edit</button>    
          <button class='btn btn-danger' onclick="deletesubs('<?php echo base64_encode($value['subs_id']);?>')">Delete</button>
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
    $('#subscriptiontable').DataTable({
       columnDefs: [
   { orderable: false, targets: -1 },
   { orderable: false, targets: -2 }
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
function blocksubs(element,subs_id) {
 var hasclass=$(element).hasClass('btn-success');
 if(hasclass ==false){
    var showtext ="<b>Do you really want to In-active this Subscription?<b>";
  }else{
    var showtext ="<b>Do you really want to Active this Subscription?<b>";
  }
bootbox.confirm(showtext, function(result) {
if(result==true){
if( $(element).hasClass('btn-success')){
var status='1';
$(element).text('In-active');
$(element).removeClass('btn-success').addClass('btn-danger');
}
else 
{
var status = '0';
$(element).text('Active');
$(element).removeClass('btn-danger').addClass('btn-success');
}
$.ajax({
type: 'POST',
url: '<?php echo base_url(); ?>admin/admin/status_subscription',
data: {
'id': subs_id,
'active':status
},
success: function(data) {
}
});
}
});
}
</script>