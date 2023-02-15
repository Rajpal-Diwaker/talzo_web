<?php
class Adminmodel extends CI_Model
{


//=========Dashbaord============//

  function dashboard(){
    $all_users=$this->db->from("users")->where('delete_status','0')->count_all_results();
    $all_posts=$this->db->from("posts")->count_all_results();
    $all_add=$this->db->from("advertisements")->count_all_results(); 
    $active_add=$this->db->where('ads_status','1')->from("advertisements")->count_all_results();  
    $result=["all_users"=>$all_users,"all_posts"=>$all_posts,"all_add"=>$all_add,"active_add"=>$active_add];
    return $result;
  }

function dashboard_graph(){
  $userdata=[];
  $year=date('Y');
$query = $this->db->query("SELECT Year(DATE_FORMAT(`created_at`, '%Y-%m-%d')) as Year, sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 1) as 'JAN', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 2) as 'FEB', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 3) as 'MAR', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 4) as 'APR', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 5) as 'MAY', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 6) as 'JUN', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 7) as 'JUL', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 8) as 'AUG', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 9) as 'SEP', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 10) as 'OCT', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 11) as 'NOV', sum(month(DATE_FORMAT(`created_at`, '%Y-%m-%d')) = 12) as 'DEC', Count(*) As Users FROM users where user_status='1' and delete_status='0' GROUP BY Year(`created_at`)");
// echo $this->db->last_query();die;
$users = $query->result_array();
foreach($users as $value){
if($value['Year'] == $year){
$userdata = [
array( 'y'=> 'Jan', 'a'=> $value['JAN'] ),
array( 'y'=> 'Feb', 'a'=> $value['FEB'] ),
array( 'y'=> 'Mar', 'a'=> $value['MAR'] ),
array( 'y'=> 'Apr', 'a'=> $value['APR'] ),
array( 'y'=> 'May', 'a'=> $value['MAY'] ),
array( 'y'=> 'Jun', 'a'=> $value['JUN'] ),
array( 'y'=> 'Jul', 'a'=> $value['JUL'] ),
array( 'y'=> 'Aug', 'a'=> $value['AUG'] ),
array( 'y'=> 'Sep', 'a'=> $value['SEP'] ),
array( 'y'=> 'Oct', 'a'=> $value['OCT'] ),
array( 'y'=> 'Nov', 'a'=> $value['NOV'] ),
array( 'y'=> 'Dec', 'a'=> $value['DEC'] )
];
}else{
continue;
}
} 

return $userdata;
  }

//==========get users============//
  function getUsers($postData){
    // print_r($postData);die;
   $response = array();
   $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
   $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data'];
    if($columnName=='no'){
    $columnName='user_id';
     }
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value']; // Search value
   $search_arr = array();
   $searchQuery = "";
   if($searchValue != ''){
     $search_arr[] = "(name like '%".$searchValue."%' or 
     email like '%".$searchValue."%' or 
     phone like'%".$searchValue."%' ) ";
   }
   
   if(count($search_arr) > 0){
    $searchQuery = implode(" and ",$search_arr);
  }
  $this->db->select('*');
  $select=$this->db->where('delete_status','0')->get('users');
  $records=$select->result();
  $totalRecords =$select->num_rows();

     ## Total number of record with filtering
  $this->db->select('*');
  if($searchQuery != '')
   $this->db->where($searchQuery);
 $select1=$this->db->where('delete_status','0')->get('users');
$records=$select1->result();
$totalRecordwithFilter =$select1->num_rows();

     ## Fetch records
 $this->db->select('*');
 if($searchQuery != '')
   $this->db->where($searchQuery);
  $this->db->where('delete_status','0'); 
  $this->db->order_by($columnName, $columnSortOrder);
 $this->db->limit($rowperpage, $start);
$select2=$this->db->get('users');
$records=$select2->result();

 $data = array();
 $no = $_POST['start'];
 foreach($records as $record ){
  $no++;
  if ($record->user_status == '1') {
    $user_status='<button class="btn btn-danger" id="status_'.$record->user_id.'" onclick="status(this,'.$record->user_id.');" style="width:76px;">Block</button>';
  }else{
    $user_status='<button class="btn btn-success" id="status_'.$record->user_id.'"  onclick="status(this,'.$record->user_id.');"  style="width:76px;">Unblock</button>';
  }
  $action='<div class="view_del"><a href="'.base_url().'admin/user_detail?id='.$record->user_id.'" id="edit"><i class="fa fa-eye" aria-hidden="true"></i></a>
  <button onclick="deleteuser('.$record->user_id.')" id="delete_'.$record->user_id.'"><i id="delete" class="fa fa-trash" aria-hidden="true"></i></button></div>';

  $data[] = array( 
    "no"=>$no,
    "name"=>$record->name,
    "phone"=>$record->country_code.'-'.$record->phone,
    "email"=>$record->email,
    "manage"=>$user_status,
    "action"=>$action
  ); 
}

     ## Response
$response = array(
 "draw" => intval($draw),
 "iTotalRecords" => $totalRecords,
 "iTotalDisplayRecords" => $totalRecordwithFilter,
 "aaData" => $data
);
return $response; 
}

//============deleteuser================//

function deleteuser($user_id){
 $select=$this->db->select('post_id')->where('user_id',$user_id)->get('posts');
 if($select->num_rows()>0){
  $result=$select->result_array();
  foreach ($result as $key => $value) {
    $this->db->where('post_id',$value['post_id'])->delete('reports');
  }
 } 
 $this->db->where('user_id',$user_id)->delete('posts');
 $this->db->where('user_id',$user_id)->set('delete_status','1')->update('users');
$this->db->where('user_id',$user_id)->delete('advertisements');
$this->db->where('user_id',$user_id)->or_where('block_user_id',$user_id)->delete('user_block');
$this->db->where('user_id',$user_id)->delete('notification');
$this->db->where('user_id',$user_id)->delete('followers');
$this->db->where('user_id',$user_id)->delete('post_stars');
$this->db->where('user_id',$user_id)->delete('post_views');
$this->db->where('user_id',$user_id)->delete('reports');
return 1;  
}

//=========block users============//

function block_user($data){
  $select=$this->db->where('user_id',$data['user_id'])
  ->get('users');
  if($select->num_rows()>0){
    $this->db->where('user_id',$data['user_id']);
    $this->db->set('user_status',$data['user_status']);
    $this->db->set('updated_at',date('Y-m-d'));
    if($data['user_status']=='0'){            
     $this->db->set('user_token','');
   }           
   $update=$this->db->update('users');
   if($update==1){
    return 1;
  }else{
    return 0;
  }        
}
else{
  return 0;
}
}

//===========user_detail===========//

function user_detail($user_id){
 $select=$this->db->where('user_id',$user_id)->get('users');
 if($select->num_rows()>0){
  $result=$select->row_array();
  return $result;
}else{
  return [];
}               
}

//=========user_dashboard===============//

function user_dashboard($user_id){
$result=[];
$bronze=0;
$silver=0;
$gold=0;  
$select=$this->db->select('stars')
                  ->where('user_id',$user_id)
                  ->where('post_status','1')
                  ->get('posts');
    if($select->num_rows()>0){
      $result1=$select->result_array();
      foreach ($result1 as $key => $value) {
        if($result1[$key]['stars']>500 && $result1[$key]['stars']<1000){
          $bronze=$bronze+1;
        }
        elseif($result1[$key]['stars']>1000 && $result1[$key]['stars']<2000){
          $silver=$silver+1;
        }
        elseif($result1[$key]['stars']>2000){
          $gold=$gold+1;
        }
      }
    }

$all_post=$this->db->where('user_id',$user_id)->from("posts")->where('post_status','1')->count_all_results();
$all_follower=$this->db->where('following_id',$user_id)->where('is_follow','1')->from("followers")->count_all_results(); 
$all_following=$this->db->where('user_id',$user_id)->where('is_follow','1')->from("followers")->count_all_results();
  $result=["all_post"=>$all_post,"all_follower"=>$all_follower,"all_following"=>$all_following,"bronze"=>$bronze,"silver"=>$silver,"gold"=>$gold];
  return $result; 
}

//=======edit_user================//

function edit_user($data){
  $select=$this->db->where('user_id',$data['user_id'])
  ->get('users');
  if($select->num_rows()>0){
    $update=$this->db->where('user_id',$data['user_id'])->update('users',$data);
    if($update==1){
      return 1;
    }else{
      return 0;
    } 
  }else{
    return 0;
  } 
}

//===========get_All==posts===============//

function get_posts($postData){
  // print_r($postData);
    $response = array();
    $draw = $postData['draw'];
    $start = $postData['start'];
    $rowperpage = $postData['length']; // Rows display per page
    $columnIndex = $postData['order'][0]['column']; // Column index
    $columnName = $postData['columns'][$columnIndex]['data'];
    if($columnName=='no'){
    $columnName='post_id';
     }
    $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
    $searchValue = $postData['search']['value'];

 $search_arr = array();
 $searchQuery = "";
 if($searchValue != ''){
   $search_arr[] = "(name like '%".$searchValue."%' or 
   description like '%".$searchValue."%') ";
 }
 
 if(count($search_arr) > 0){
  $searchQuery = implode(" and ",$search_arr);
}
$this->db->select('*');
$this->db->from('posts as p');
$this->db->join('users as u','p.user_id=u.user_id','left');
$select=$this->db->get();
$totalRecords=$select->num_rows();
$records=$select->result();
     ## Total number of record with filtering
$this->db->select('*');
$this->db->from('posts as p');
$this->db->join('users as u','p.user_id=u.user_id','left');
if($searchQuery != ''){
 $this->db->where($searchQuery);
}
$select1=$this->db->get();
$records=$select1->result();
$totalRecordwithFilter =$select1->num_rows();
// print_r($totalRecordwithFilter);die;
     ## Fetch records
$this->db->select('p.post_id,p.user_post,p.description,p.post_status,u.name');
if($searchQuery != ''){
 $this->db->where($searchQuery);
}
$this->db->from('posts as p');
$this->db->join('users as u','p.user_id=u.user_id','left');
$this->db->order_by($columnName, $columnSortOrder);
$this->db->limit($rowperpage, $start);
$select2=$this->db->get();
$records=$select2->result();
// echo $this->db->last_query();die;

$data = array();
$no = $_POST['start'];
foreach($records as $record ){
  $no++;
  if ($record->post_status == '1') {
    $post_status='<button class="btn btn-danger" id="status_'.$record->post_id.'" onclick="status(this,'.$record->post_id.');" style="width:76px;">Block</button>';
  }else{
    $post_status='<button class="btn btn-success" id="status_'.$record->post_id.'"  onclick="status(this,'.$record->post_id.');"  style="width:76px;">Unblock</button>';
  }
  $ext=pathinfo($record->user_post, PATHINFO_EXTENSION);
  if($ext=='mp4'){ 
    $user_post='<div class="report_posts"><video controls>
    <source src="'.$record->user_post.'"></video></div>';
  }else{
    $user_post='<div class="report_posts"><img src="'.$record->user_post.'" alt=""></div>';
  }


 // $user_post='<img src="'.$record->user_post.'" style="width:80px; height:80px; border-radius:50%;" >';   
  $action='<div class="view_del"><a href="'.base_url().'admin/post_detail?id='.$record->post_id.'" id="edit"><i class="fa fa-eye" aria-hidden="true"></i></a>
  <button onclick="deletepost('.$record->post_id.')" id="delete_'.$record->post_id.'"><i id="delete" class="fa fa-trash" aria-hidden="true"></i></button></div>';

  $data[] = array( 
    "no"=>$no,
    "user_post"=>$user_post,
    "name"=>$record->name,
    "description"=>$record->description,
    "manage"=>$post_status,
    "action"=>$action
  ); 
}

     ## Response
$response = array(
 "draw" => intval($draw),
 "iTotalRecords" => $totalRecords,
 "iTotalDisplayRecords" => $totalRecordwithFilter,
 "aaData" => $data
);

return $response;  
}

//=========block post===========//

function block_post($data){
  $select=$this->db->where('post_id',$data['post_id'])
  ->get('posts');
  if($select->num_rows()>0){
    $this->db->where('post_id',$data['post_id']);
    $this->db->set('post_status',$data['post_status']);
    $this->db->set('updated_at',date('Y-m-d'));         
    $update=$this->db->update('posts');
    if($update==1){
      return 1;
    }else{
      return 0;
    }        
  }
  else{
    return 0;
  }
}

//============deletepost================//

function deletepost($post_id){
 $this->db->where('post_id',$post_id)->delete('posts');
 if($this->db->affected_rows()>0){
  return 1;
} else{
  return 0;
}   
}


//===========post_detail===========//

function post_detail($post_id){
 $select=$this->db->select('p.*,u.name')
 ->from('posts as p')
 ->join('users as u','p.user_id=u.user_id','left')
 ->where('p.post_id',$post_id)
 ->get();
 if($select->num_rows()>0){
  $result=$select->row_array();
  return $result;
}else{
  return [];
}               
}


//=============view_user_allpost==========//

public function view_user_allpost($limit,$start,$user_id) {
  $select=$this->db->select('*')
  ->where('user_id',$user_id)
  ->limit($limit, $start)
  ->order_by('post_id','desc')
  ->get('posts');
  if($select->num_rows()>0){                
    $result=$select->result_array();
    return $result;
  }else{
    return [];
  }             
}

//========view_user_allpost count========//
function user_post_count($user_id){
  $allcount=$this->db->where('user_id',$user_id)->from("posts")->count_all_results();
  return $allcount; 
}


//=============view_user_alladd==========//

public function view_user_alladd($limit,$start,$user_id) {
  $select=$this->db->select('*')
  ->where('user_id',$user_id)
  ->limit($limit, $start)
  ->order_by('add_id','desc')
  ->get('advertisements');
  if($select->num_rows()>0){                
    $result=$select->result_array();
    return $result;
  }else{
    return [];
  }             
}

//========view_user_alladd count========//
function user_add_count($user_id){
  $allcount=$this->db->where('user_id',$user_id)->from("advertisements")->count_all_results();
  return $allcount; 
}

//========send_push==============//

function send_push(){
 $select=$this->db->select('user_id,name')
 ->where('user_status','1')
 ->where('delete_status','0')
 ->get('users');
 if($select->num_rows()>0){
  $result=$select->result_array();
  return $result;
}else{
  return [];
}                 
}

//================change_password_process=========//

function change_password_process($data){
 $select=$this->db->where('admin_id',$data['admin_id'])
 ->where('password',md5($data['old_password']))
 ->get('admin');
 if($select->num_rows()>0){
  $update=$this->db->where('admin_id',$data['admin_id'])
  ->set('password',md5($data['new_password']))
  ->update('admin');
  if($update==1){
   return 1;
 }else{
   return 0;
 }                  
}else{
  return 0;
}                  
}

//===========subscription_list===============//

function subscription_list(){
  $select=$this->db->select('*')
  ->order_by('subs_id','desc')
  ->get('subscription');
  if($select->num_rows()>0){
    $result=$select->result_array();
    return $result;
  }else{
    return [];
  }                  
}

//==========create_subscription_process=========//

function create_subscription_process($data){  
 $this->db->insert('subscription',$data);
 if($this->db->insert_id()>0){
  return 1;
}else{
  return 0;
} 
}

//========edit_subscription===================//

function edit_subscription($subs_id){
  $select=$this->db->where('subs_id',$subs_id)->get('subscription');
  if($select->num_rows()>0){
    $result=$select->row_array();
    return $result;
  } else{
    return [];
  } 
}

//============deletesubscription================//

function deletesubscription($subs_id){
 $this->db->where('subs_id',$subs_id)->delete('subscription');
 if($this->db->affected_rows()>0){
  return 1;
} else{
  return 0;
}   
}

//========edit_subscription_process===========//

function edit_subscription_process($data){
  $select=$this->db->where('subs_id',$data['subs_id'])
  ->get('subscription');
  if($select->num_rows()>0){
    $update=$this->db->where('subs_id',$data['subs_id'])->update('subscription',$data);
    if($update==1){
      return 1;
    }else{
      return 0;
    }
  }else{
    return 0;
  }                 
}
//=========status_subscription==============//

function status_subscription($data){
 $select=$this->db->where('subs_id',$data['subs_id'])
 ->get('subscription');
 if($select->num_rows()>0){
  $this->db->where('subs_id',$data['subs_id']);
  $this->db->set('subs_status',$data['subs_status']);
  $this->db->set('updated_at',date('Y-m-d'));         
  $update=$this->db->update('subscription');
  // echo $this->db->last_query();die;
  if($update==1){
    return 1;
  }else{
    return 0;
  }        
}
else{
  return 0;
}
}

//==========report list===============//

function report_list(){
$select=$this->db->select('p.post_id,p.user_post,p.post_status,u.name,(select count(report_id) from reports where post_id=p.post_id) as reportcount')
                ->from('reports as r')
                ->join('posts as p','r.post_id=p.post_id','left')
                ->join('users as u','p.user_id=u.user_id','left')
                ->group_by('p.post_id')
                // ->where('p.post_status','1')
                ->get();
if($select->num_rows()>0){
 $result=$select->result_array();
 // print_r($result);die;
 return $result; 
}else{
  return[];
}                  
}

//=========report_detail==============//
function report_detail($post_id){
  $select=$this->db->select('count(report_id)as report_count,r.post_id,u.name,p.user_post')  
     ->from('reports as r')
    ->join('posts as p','r.post_id=p.post_id','left')
    ->join('users as u','p.user_id=u.user_id','left')
    ->where('r.post_id',$post_id)
  ->get(); 
  if($select->num_rows()>0){
    $result=$select->row_array();
    return $result;
  }else{
    return [];
  }      
}

//=========report_detail_data==============//
function report_detail_data($post_id){
$allresult=[]; 
$selects=$this->db->get('allreason');
$results=$selects->result_array();
foreach ($results as $key => $value) {
$select=$this->db->select('count(r.reason_id)as count,u.name')
                 ->from('reports as r')
                 ->join('posts as p','r.post_id=p.post_id','left')
                 ->join('users as u','p.user_id=u.user_id','left')
                 ->where('r.reason_id',$value['reason_id'])
                 ->where('r.post_id',$post_id)
                 ->get();
if($select->num_rows()>0){
 $result=$select->row_array();
 if($result['count']>0){
 $result['reason_title']=$value['reason_title'];
  $allresult[]=$result;
}
}
}
// print_r($allresult);die;
return $allresult;     
}

//=============add_request_list=====================//

function add_request_list(){
  $select=$this->db->select('a.*,u.name')
  ->from('advertisements as a')
  ->join('users as u','a.user_id=u.user_id','left')
  ->where('verified_status','0')
  ->order_by('add_id','desc')
  ->get();
  if($select->num_rows()>0){
    $result=$select->result_array();
    return $result;
  }else{
    return [];
  }                 
}

//=========approve_request=============//

function approve_request($add_id){
$todaydate=date('Y-m-d');
$expired_date=date("Y-m-d", strtotime("+10 days", strtotime($todaydate))); 
 $this->db->where('add_id',$add_id)->set('ads_status','1')->set('verified_status','1')->set('expired_date',$expired_date)->update('advertisements');
 return true; 
}

//========send_notification_process=============//

function send_notification_process($user_id,$messages){
  $push = new Common();
  $notitype="User notification";
  $date_time=date("Y-m-d H:i:s");  
  $select=$this->db->where_in('user_id',$user_id)->get('users');
  if($select->num_rows()>0){
    $result=$select->result_array();
    foreach ($result as $key => $value) {
     $device_token=$result[$key]['device_token'];
     $device_type=$result[$key]['device_type'];
     $message=["title"=>"Talzo","body"=>$messages];
      $noti_data=["user_id"=>$result[$key]['user_id'],"title"=>$message['title'],"body"=>$message['body'],"date_time"=>$date_time];
      // print_r($noti_data);die;
      $this->db->insert('notification',$noti_data); 
      $res=$push->notification($device_token,$device_type,$message,$notitype);
    }
    return 1;
  }else{
    return 0;               
  }
}


//==========get chats============//
  function get_chats($postData){
   $response = array();
   $draw = $postData['draw'];
   $start = $postData['start'];
   $rowperpage = $postData['length'];
   $columnIndex = $postData['order'][0]['column'];
   $columnName = $postData['columns'][$columnIndex]['data']; 
   $columnSortOrder = $postData['order'][0]['dir']; 
   $searchValue = $postData['search']['value'];
   $search_arr = array();
   $searchQuery = "";
   if($searchValue != ''){
     $search_arr[] = "(u.name like '%".$searchValue."%' or 
     u1.name like '%".$searchValue."%') ";
   }
   
   if(count($search_arr) > 0){
    $searchQuery = implode(" and ",$search_arr);
  }
$this->db->select('u.name AS sender_name,
(((c.s_id*c.r_id)+ABS(c.s_id-c.r_id))*3) AS uid,
u1.name AS receiver_name, c.s_id AS sender_id, c.r_id AS reciver_id, COUNT(s_id) AS msg_count');
 $this->db->from('chat as c');
  $this->db->join('users as u','c.s_id=u.user_id','left');
  $this->db->join('users as u1','c.r_id=u1.user_id','left');
  $this->db->where('c.chat_type','normal');
  $this->db->where('c.delete_for_everyone !=','1');
  $this->db->group_by('uid');
  $this->db->order_by('c.id', $columnSortOrder);
  $this->db->limit($rowperpage, $start);
  $selects=$this->db->get();
$totalRecords = $selects->num_rows();
// print_r($totalRecords);die;
     ## Total number of record with filtering
 $this->db->select('u.name AS sender_name,
(((c.s_id*c.r_id)+ABS(c.s_id-c.r_id))*3) AS uid,
u1.name AS receiver_name, c.s_id AS sender_id, c.r_id AS reciver_id, COUNT(s_id) AS msg_count');
 if($searchQuery != ''){
   $this->db->where($searchQuery);
 }
  $this->db->from('chat as c');
  $this->db->join('users as u','c.s_id=u.user_id','left');
  $this->db->join('users as u1','c.r_id=u1.user_id','left');
  $this->db->where('c.chat_type','normal');
  $this->db->where('c.delete_for_everyone !=','1');
  $this->db->group_by('uid');
  $this->db->order_by('c.id', $columnSortOrder);
  $this->db->limit($rowperpage, $start);
  $select1=$this->db->get();
  $totalRecordwithFilter = $select1->num_rows();
  // secho $this->db->last_query();die;
     ## Fetch records
 $this->db->select('u.name AS sender_name,
(((c.s_id*c.r_id)+ABS(c.s_id-c.r_id))*3) AS uid,
u1.name AS receiver_name, c.s_id AS sender_id, c.r_id AS reciver_id, COUNT(s_id) AS msg_count');
 if($searchQuery != ''){
   $this->db->where($searchQuery);
 }
  $this->db->from('chat as c');
  $this->db->join('users as u','c.s_id=u.user_id','left');
  $this->db->join('users as u1','c.r_id=u1.user_id','left');
  $this->db->where('c.chat_type','normal');
  $this->db->where('c.delete_for_everyone !=','1');
  $this->db->group_by('uid');
  $this->db->order_by('c.id', $columnSortOrder);
  $this->db->limit($rowperpage, $start);
  $select2=$this->db->get();
  $records=$select2->result();

 $data = array();
 $no = $_POST['start'];
 foreach($records as $record ){
  $no++;
  $action='<div class="view_del"><a href="'.base_url().'admin/chat_detail?sender_id='.$record->sender_id.'&reciver_id='.$record->reciver_id.'" id="edit"><i class="fa fa-eye" aria-hidden="true"></i></a>';
  $data[] = array( 
    "no"=>$no,
    "users"=>$record->sender_name.','.$record->receiver_name,
    "no_of_users"=>2,
    "msg_count"=>$record->msg_count,
    "action"=>$action
  ); 
}

     ## Response
$response = array(
 "draw" => intval($draw),
 "iTotalRecords" => $totalRecords,
 "iTotalDisplayRecords" => $totalRecordwithFilter,
 "aaData" => $data
);
return $response; 
}
//=========chat detail==================//

function chat_detail($sender_id,$reciver_id){
 $where="(c.s_id ='$sender_id' AND c.r_id ='$reciver_id' AND c.delete_for_everyone !='1')";
 $where1="(c.s_id ='$reciver_id' AND c.r_id ='$sender_id' AND c.delete_for_everyone != '1')";
 $select=$this->db->select('c.*,su.name as sender_name,ru.name as receiver_name,su.user_image as sender_image,ru.user_image as reciver_image')
          ->from('chat as c')
          ->join('users as su','c.s_id=su.user_id')
          ->join('users as ru','c.r_id=ru.user_id')
          ->where($where)
          ->or_where($where1)
          ->order_by('c.id','desc')
          ->get();
   // echo $this->db->last_query();die;        
if($select->num_rows()>0){
  $result=$select->result_array();
 foreach ($result as $key => $value) {
$result[$key]['date_added']=$this->time_ago($value['date_added']);
 }
  // print_r($result);die;
  return $result;
}else{
  return [];
}          
}


//==========time ===============//
function time_ago($date) {
  if ($date) {
    $timestamp = strtotime($date);
    $difference = time() - $timestamp;
    $periods = array("sec", "min", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    if ($difference > 0) { // this was in the past time
      $ending = "ago";
    } else { // this was in the future time
      $difference = -$difference;
      $ending = "to go";
    }
    
    for ($j = 0; $difference >= $lengths[$j]; $j++)
      $difference /= $lengths[$j];
    
    $difference = round($difference);
    
    if ($difference > 1)
      $periods[$j].= "s";
    
    $text = "$difference $periods[$j] $ending";
    
    return $text;
  } else {
    return 'Date Time must be in "yyyy-mm-dd hh:mm:ss" format';
  }
}

}//========class=======//