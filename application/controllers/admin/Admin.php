<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->library('session');
     $email=$this->session->userdata('email');
    if($email!='' && isset($email)){
     require APPPATH . '/helpers/common.php';
     $this->load->model('admin/Adminmodel');
     $this->load->library('pagination');
     $this->load->library('S3');
    }else{
      redirect('admin/login');
   }
   
 }	
 //==========use file upload through s3===========//
 function user_fileUpload($FILES){
  $name=time().'_'.$FILES['image']['name'];
  $tmp_name=$FILES['image']['tmp_name'];
  if($this->s3->putObjectFile($tmp_name, "talzo", 'users_image/'.$name, S3::ACL_PUBLIC_READ)) {
    return 'https://talzo.s3.ap-south-1.amazonaws.com/users_image/'.$name;
  }else{
    return 0;
  } 
} 

//=========end construct function==========//
//=========Dashboard=============//
function dashboard(){
  $result['graph']=$this->Adminmodel->dashboard_graph();
  $result['data']=$this->Adminmodel->dashboard();
  $this->load->view('admin/header');
  $this->load->view('admin/dashboard',$result);
}
//===========Userlist===========//
function user_list(){
	$this->load->view('admin/header');
	$this->load->view('admin/userlist');
}

//===========get user list===========//
function get_users(){
 $postData = $this->input->post();
 $data = $this->Adminmodel->getUsers($postData);
 echo json_encode($data);
}

//============delete user================//

function deleteuser(){
  $user_id=$this->input->get('user_id');	
  $result=$this->Adminmodel->deleteuser($user_id);
  if($result==1){
   redirect('admin/user-list');
 }else{
   redirect('admin/user-list');
 }
}

//=========block user============//

function block_user(){
  $user_id=$this->input->post('id');
  $user_status=$this->input->post('active');
  $data=["user_id"=>$user_id,"user_status"=>$user_status];
  $response=$this->Adminmodel	->block_user($data);
  if($response==1){
    echo 1;die;
  }else{
   echo 0;die;
 }
}

//============User details============//

function user_detail(){
  $user_id=$this->input->get('id');
  $this->session->set_userdata('user_id',$user_id);
  $result['data']=$this->Adminmodel->user_detail($user_id);
  $result['dashboard']=$this->Adminmodel->user_dashboard($user_id); 	
  $this->load->view('admin/header');
  $this->load->view('admin/user_detail',$result);	
}


//===========edit_user=============//

function edit_user(){
  $post=$this->input->post();
  $data=["user_id"=>$post['user_id'],"name"=>$post['name'],"email"=>$post['email']];
  if(!empty($_FILES['image']['name']) && isset($_FILES['image']['name'])){
    $user_image=$this->user_fileUpload($_FILES);
    $data['user_image']=$user_image;
  }
  $response=$this->Adminmodel->edit_user($data);
  if($response==1){
   echo 1;die();
 }else{
   echo 0;die();
 }
}

//=========view_user_post==============//

function view_user_post(){
 $user_id=$this->session->userdata('user_id');
 $this->load->view('admin/header');
 $config = array();
 $config["base_url"] = base_url()."admin/view-user-post"; 
 $config["total_rows"] = $this->Adminmodel->user_post_count($user_id);
 $config["per_page"] = 8;
 $config["uri_segment"] = 3;
 $config['next_link'] = 'Next';
 $config['prev_link'] = 'Previous';
 $this->pagination->initialize($config);
 $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;   
 $data["user_post"] =$this->Adminmodel->view_user_allpost($config["per_page"],$page,$user_id);
 $data["links"] =$this->pagination->create_links();
 $this->load->view('admin/userall_post',$data);  
}


//========//===========All user post===========//
function post_list(){
  $this->load->view('admin/header');
  $this->load->view('admin/postlist');
}

//===========get user post===========//
function get_posts(){
 $postData = $this->input->post();
 $data = $this->Adminmodel->get_posts($postData);
 echo json_encode($data);
}

//=========block post============//

function block_post(){
  $post_id=$this->input->post('id');
  $post_status=$this->input->post('active');
  $data=["post_id"=>$post_id,"post_status"=>$post_status];
  $response=$this->Adminmodel ->block_post($data);
  if($response==1){
    echo 1;die;
  }else{
   echo 0;die;
 }
}

//============delete post================//

function deletepost(){
  $post_id=$this->input->get('post_id');  
  $result=$this->Adminmodel->deletepost($post_id);
  if($result==1){
    redirect('admin/post-list');
  }else{
    redirect('admin/post-list');
  }
}

//========post detail=============//

function post_detail(){
  $post_id=$this->input->get('id');
  $result['data']=$this->Adminmodel->post_detail($post_id); 
  $this->load->view('admin/header');
  $this->load->view('admin/post_detail',$result); 
}

//=========view_user_post==============//

function view_user_add(){
 $user_id=$this->session->userdata('user_id');
 $this->load->view('admin/header');
 $config = array();
 $config["base_url"] = base_url()."admin/view-user-add"; 
 $config["total_rows"] = $this->Adminmodel->user_add_count($user_id);
 $config["per_page"] = 8;
 $config["uri_segment"] = 3;
 $config['next_link'] = 'Next';
 $config['prev_link'] = 'Previous';
 $this->pagination->initialize($config);
 $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;   
 $data["user_add"] =$this->Adminmodel->view_user_alladd($config["per_page"], $page,$user_id);
 $data["links"] =$this->pagination->create_links();
 $this->load->view('admin/userall_add',$data);  
}

//===========send push=============//

function send_push(){
 $result['data']=$this->Adminmodel->send_push(); 
 $this->load->view('admin/header'); 
 $this->load->view('admin/send_push',$result);
}

//========change password=============//

function change_password(){
 $this->load->view('admin/header');
 $this->load->view('admin/change_password'); 
}

//==========chane password process=========//
function change_password_process(){
  $post=$this->input->post();
  $admin_id=$this->session->userdata('admin_id');
  $post['admin_id']=$admin_id;
  $result=$this->Adminmodel->change_password_process($post);
  if($result==1){  
   echo 1;die;    
 }else{
   echo 0;die;
 }
}
//===========subscription_list==================//

function subscription_list(){
 $result['data']=$this->Adminmodel->subscription_list(); 
 $this->load->view('admin/header');
 $this->load->view('admin/subscription',$result); 
}

//===========Add subscription==================//

function create_subscription(){
  $this->load->view('admin/header');
  $this->load->view('admin/add_subscription'); 
}

//==========create_subscription_process=============//
function create_subscription_process(){
 $post=$this->input->post();
 $todaydate=date('Y-m-d');
 $data=["subs_name"=>$post['subscription_name'],"price"=>$post['subscription_price'],"subs_status"=>'1',"created_at"=>$todaydate];
 $result=$this->Adminmodel->create_subscription_process($data);
 if($result==1){
  echo 1;die;
}else{
  echo 0;die;
}

}

//=================edit subscription============//\

function edit_subscription(){
  $subs_id=base64_decode($this->input->get('subs_id'));
  $result['data']=$this->Adminmodel->edit_subscription($subs_id);
  $this->load->view('admin/header');
  $this->load->view('admin/edit_subscription',$result);
}

//===========edit_subscription_process===========//

function edit_subscription_process(){
  $post=$this->input->post();
  $todaydate=date('Y-m-d');
  $data=["subs_id"=>$post['subs_id'],"subs_name"=>$post['subscription_name'],"price"=>$post['subscription_price'],"updated_at"=>$todaydate];
  $result=$this->Adminmodel->edit_subscription_process($data);
  if($result==1){
    echo 1;die;
  }else{
    echo 0;die;
  }
}

//============deletesubscription================//

function deletesubscription(){
  $subs_id=base64_decode($this->input->get('subs_id'));  
  $result=$this->Adminmodel->deletesubscription($subs_id);
  if($result==1){
    redirect('admin/subscription-list');
  }else{
    redirect('admin/subscription-list');
  }
}

//==========staus_subscription===============//

function status_subscription(){
  $subs_id=$this->input->post('id');
  $subs_status=$this->input->post('active');
  $data=["subs_id"=>$subs_id,"subs_status"=>$subs_status];
  $response=$this->Adminmodel ->status_subscription($data);
  if($response==1){
    echo 1;die;
  }else{
   echo 0;die;
 }
}

//===========report_list===========//
function report_list(){
  $result['data']=$this->Adminmodel->report_list();
  $this->load->view('admin/header');
  $this->load->view('admin/report_list',$result);
}

//=========report detail=============//

function report_detail(){
$post_id=$this->input->get('post_id');
$result['data']=$this->Adminmodel->report_detail($post_id);
$result['list']=$this->Adminmodel->report_detail_data($post_id);
// print_r($result);die;
$this->load->view('admin/header');
$this->load->view('admin/report_detail',$result); 
}

function add_request_list(){
$result['data']=$this->Adminmodel->add_request_list();
$this->load->view('admin/header');
$this->load->view('admin/add_request_list',$result);
}

//=========approve_request=============//

function approve_request(){
$add_id=$this->input->get('add_id');
$result=$this->Adminmodel->approve_request($add_id);
if($result==1){
$this->session->set_flashdata('msg','ADD Approved successfully.');
  redirect('admin/add_request-list');
}else{
  $this->session->set_flashdata('msg','Something went wrong!');
  redirect('admin/add_request-list');
}  
}

//=========send_notification_process=============//

function send_notification_process(){
$user_id=$this->input->post('user_id');
$message=$this->input->post('message');
$response=$this->Adminmodel->send_notification_process($user_id,$message);
if($response==1){
  echo 1;die;
}else{
  echo 0;die;
}
}

//===========Chatlist===========//
function chat_list(){
  $this->load->view('admin/header');
  $this->load->view('admin/chatlist');
}

//===========get Chat list===========//
function get_chats(){
 $postData = $this->input->post();
 $data = $this->Adminmodel->get_chats($postData);
 echo json_encode($data);
}

//=========chat_detail==================================//
function chat_detail(){
$sender_id=$this->input->get('sender_id');
$reciver_id=$this->input->get('reciver_id');
$result['data']=$this->Adminmodel->chat_detail($sender_id,$reciver_id);
$result['sender_id']=$sender_id;
$result['reciver_id']=$reciver_id; 
$this->load->view('admin/header');
$this->load->view('admin/chat_detail',$result);
}


}///////class====================//
