<?php
defined('BASEPATH') OR exit('No direct script access allowed.');
require APPPATH . '/libraries/REST_Controller.php';
class Restapi extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->post()) 
		{
			$this->data = $this->post();
		}
		else
		{	
			$this->data = $this->get();
		}
		date_default_timezone_set('UTC');
		require APPPATH . '/helpers/common.php';
		$this->load->model('api/v1/Restapi_model');
		$this->load->library('S3');
	}
	//end function

	//---------->>>>>>>>>>>>> error check <<<<<<<<<<<<<<--------------//
	function getErrorMsg($required=array(), $request=array())
	{ 
		$notExist = true;
		foreach($required as $value)
		{  
			if(array_key_exists($value, $request))
			{    
				if($request[$value]=="")
				{     
					$data = array(
						"statusCode"=> 400,
						"APICODERESULT"=>$value." is empty."
						); 
					$this->response($data, 200);
					//echo json_encode($data);
					exit; 
				} 
			}
			else
			{ 
				$data = array(
					"statusCode"=> 400,
					"APICODERESULT"=>$value. " key is missing."
					); 
				$this->response($data, 200);
				//echo json_encode($data);
				exit;   
			}  
		} 
		return $notExist; 
	}


	/* ==================== Check Access Key  =================== */

	function checkAssessKey($user_token,$user_id)
	{
		$response= $this->Restapi_model->checkAssessKey($user_token,$user_id);

		if($response==1)
		{
			return true;
		}
		else
		{
			$data=array("statusCode" => 403, 
				"APICODERESULT"  => "Invalid Token",
				"result"=>[]
				);
			$this->response($data, 200);
			exit();
		}
	}
	

function checkrecord($tname, $field, $val){
		$query = $this->db->get_where($tname,array($field => $val),1,0);
		if($query->num_rows() >= 1){
			return true;
		}
		else{
			$data=array(
				"statusCode" => 300, 
				"APICODERESULT"  => "Incorrect user_id, please check and try again"
				);
			$this->response($data, 200);
			exit();
		}
	}
//===============User Pic upload===============//

public function user_fileUpload($FILES){
    $name=time().'_'.$FILES['image']['name'];
    $tmp_name=$FILES['image']['tmp_name'];
    if($this->s3->putObjectFile($tmp_name, "talzo", 'users_image/'.$name, S3::ACL_PUBLIC_READ)) {
      return 'https://talzo.s3.ap-south-1.amazonaws.com/users_image/'.$name;
    }else{
    $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong while uploading your file... sorry"
        );
      $this->response($data, 200);
      exit();
    } 
  } 

//===========user post upload================//

  public function user_postUpload($FILES){
    $name=time().'_'.$FILES['media']['name'];
    $tmp_name=$FILES['media']['tmp_name'];
    if($this->s3->putObjectFile($tmp_name, "talzo", 'posts/'.$name, S3::ACL_PUBLIC_READ)) {
      return 'https://talzo.s3.ap-south-1.amazonaws.com/posts/'.$name;
    }else{
     $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong while uploading your file... sorry"
        );
      $this->response($data, 200);
      exit();
    } 
  }

  //=====user add upload=====================// 

  public function user_addUpload($FILES){
    $name=time().'_'.$FILES['media']['name'];
    $tmp_name=$FILES['media']['tmp_name'];
    if($this->s3->putObjectFile($tmp_name, "talzo", 'adds/'.$name, S3::ACL_PUBLIC_READ)) {
      return 'https://talzo.s3.ap-south-1.amazonaws.com/adds/'.$name;
    }else{
     $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong while uploading your file... sorry"
        );
      $this->response($data, 200);
      exit();
    } 
  }


//===========user post upload================//

  public function thumbnail($FILES){
    $name=time().'_'.$FILES['thumb_url']['name'];
    $tmp_name=$FILES['thumb_url']['tmp_name'];
    if($this->s3->putObjectFile($tmp_name, "talzo", 'thumbnail/'.$name, S3::ACL_PUBLIC_READ)) {
      return 'https://talzo.s3.ap-south-1.amazonaws.com/thumbnail/'.$name;
    }else{
     $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong while uploading your file... sorry"
        );
      $this->response($data, 200);
      exit();
    } 
  }

////=========Check Number Api============//
function check_number_post(){
$arrayRequired=array('country_code','phone');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$response=$this->Restapi_model->check_number($post['country_code'],$post['phone']);
 if ($response == 0) 
    {
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "This mobile number is already registered with us!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "successfully."
        );
      $this->response($data, 200);
    }
}



/*==============signup============*/

public function signup_post(){
   $arrayRequired=array('name','country_code','phone','user_lang');
    $var=$this->getErrorMsg($arrayRequired,$this->data);
    $post=$this->data;
    $user_image='';
   $country_name = (!empty($post['country_name']))?$post['country_name']:'';
    $random=new common();
    $user_token = $random->generateRandomString();
    if(!empty($_FILES['image']['name']) && isset($_FILES['image']['name'])){
    $user_image=$this->user_fileUpload($_FILES);
  }
    $todaydate=date("Y-m-d");
    $data=["name"=>$post['name'],"email"=>$post['email'],"country_code"=>$post['country_code'],"phone"=>$post['phone'],"user_lang"=>$post['user_lang'],"created_at"=>$todaydate,"user_status"=>'1',"user_image"=>$user_image,"user_token"=>$user_token,"country_name"=>$country_name];
    $response=$this->Restapi_model->signup($data);
    if ($response == 0){
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Number already exists!."
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Signup  successfully.",
        "result" => $response
        );
      $this->response($data, 200);
    }

}
////=========login Api============//
function login_post(){
$arrayRequired=array('country_code','phone');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$response=$this->Restapi_model->login($post['country_code'],$post['phone']);
 if ($response == 0) 
    {
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "This mobile number is not registered with us!"
        );
      $this->response($data, 200);
    }
    else if ($response == 3) 
    {
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Your account has been blocked by admin. Please contact"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Login successfully.",
        "result" => $response
        );
      $this->response($data, 200);
    }
}
//===============Update device type & device token=============//

function update_device_token_post(){	
$arrayRequired=array('user_id','user_token','device_type','device_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkrecord('users', 'user_id', $post['user_id']);
$this->checkAssessKey($post['user_token'],$post['user_id']);
$data=["user_id"=>$post['user_id'],"device_type"=>$post['device_type'],"device_token"=>$post['device_token']];
$response=$this->Restapi_model->update_device_token($data);
if ($response == 0) 
    {
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Update successfully."
        );
      $this->response($data, 200);
    }
}

//=========home Follower list=============//

function home_followers_list_post(){
$arrayRequired=array('user_id','user_token','offset');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkrecord('users', 'user_id', $post['user_id']);
$this->checkAssessKey($post['user_token'],$post['user_id']);
$response=$this->Restapi_model->home_followers_list($post['user_id'],$post['offset']);
if (empty($response) && !isset($response)){
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "No Data Found",
        "result"=>[]
        );
      $this->response($data, 200);
    }
    else{ 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}

//===========status change follower===========//

function change_follower_status_post(){
$arrayRequired=array('user_id','user_token','following_id','is_follow');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkrecord('users', 'user_id', $post['user_id']);
$this->checkAssessKey($post['user_token'],$post['user_id']);
$data=["user_id"=>$post['user_id'],"following_id"=>$post['following_id'],"is_follow"=>$post['is_follow']];
$response=$this->Restapi_model->change_follower_status($data);
if ($response == 0) 
    {
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Update successfully."
        );
      $this->response($data, 200);
    }
}


//==============User profile===========//===========//


function user_profile_post(){
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkrecord('users', 'user_id', $post['user_id']);
$this->checkAssessKey($post['user_token'],$post['user_id']);
$response=$this->Restapi_model->user_profile($post['user_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}


//=========Update user profile=================//

function update_user_profile_post(){
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->update_user_profile($post);
if ($response == 0) {
			$data=array(
						"statusCode" => 300, 
						"APICODERESULT"  => "Something went wrong"
						);

			$this->response($data, 200);
		}
		else
		{	
		$data=array(
						"statusCode" => 200, 
						"APICODERESULT"  => "User profile update Successfully.",
						"result" => $response
							);
			$this->response($data, 200);
		}
}


//=======All category list=============//

function all_category_list_post(){
$arrayRequired=array('user_id','user_token','lang');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->all_category_list($post['user_id'],$post['lang']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}

//===========other profile================//

function other_user_profile_post(){
$arrayRequired=array('user_id','user_token','other_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->other_user_profile($post['user_id'],$post['other_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "No data found!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "User profile find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}

//========Add user post================//

function add_userpost_post(){
$todaydate=date("Y-m-d");
$currenttime=date("H:i:s");
$user_post="";
$thumb_url="";
$arrayRequired=array('user_id','user_token','category_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$description=(!empty($post['description']))?$post['description']:'';
 if(!empty($_FILES['media']['name']) && isset($_FILES['media']['name'])){
    $user_post=$this->user_postUpload($_FILES);
  }
 if(!empty($_FILES['thumb_url']['name']) && isset($_FILES['thumb_url']['name'])){
    $thumb_url=$this->thumbnail($_FILES);
  }
 $data=["user_id"=>$post['user_id'],"description"=>$description,"category_id"=>$post['category_id'],"created_at"=>$todaydate,"user_post"=>$user_post,"created_time"=>$currenttime,"post_status"=>'1',"thumb_url"=>$thumb_url];
$response=$this->Restapi_model->add_userpost($data);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something Went wrong!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "User add post successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}

function search_talent_post(){
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->search_talent($post);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "No data found!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}

//========Search users================//

function search_user_post(){
$arrayRequired=array('user_id','user_token','search');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->search_user($post);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "No data found!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}


//==============check mobile======================//

function check_mobile_post(){
$arrayRequired=array('user_id','user_token','phone');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->check_mobile($post['phone']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Phone number already exists!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Successfully."
        );
      $this->response($data, 200);
    }
}

//==============User Block======================//

function user_block_post(){
$todaydate=date('Y-m-d');
$arrayRequired=array('user_id','user_token','block_user_id','block_status');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$data=["user_id"=>$post['user_id'],"block_user_id"=>$post['block_user_id'],"block_status"=>$post['block_status'],"created_at"=>$todaydate];
$response=$this->Restapi_model->user_block($data);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went Wrong!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Status Update Successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}

//==============Home Api======================//

function home_post(){
$arrayRequired=array('user_id','user_token','lang');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$category_id =(!empty($post['category_id']))?$post['category_id']:'';
$response=$this->Restapi_model->home($post['user_id'],$category_id,$post['lang']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find Successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}

//==========post_views====================//

function post_view_post(){
$arrayRequired=array('user_id','user_token','post_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->post_view($post['user_id'],$post['post_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "User view Successfully."
        );
      $this->response($data, 200);
    }
} 

//==========post_star====================//

function post_star_post(){
$arrayRequired=array('user_id','user_token','post_id','star_status');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->post_star($post['user_id'],$post['post_id'],$post['star_status']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "User star status update Successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}

//===========post detail===========//

function post_detail_post(){
$arrayRequired=array('user_id','user_token','post_id','lang');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->post_detail($post['user_id'],$post['post_id'],$post['lang']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find Successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    } 
}
//==============Create group use for chat =====================//
function create_group_post(){
$arrayRequired=array('user_id','user_token','group_name','group_admin');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$todaydate=date('Y-m-d H:i:s');
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
 $group_image =(!empty($post['group_image']))?$post['group_image']:'';
 $data=["group_name"=>$post['group_name'],"group_admin"=>$post['group_admin'],"group_image"=>$group_image,"created_on"=>$todaydate];
$response=$this->Restapi_model->create_group($data);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data insert Successfully."
        );
      $this->response($data, 200);
    } 
}

//==============end Create group use for chat =====================//
//==============ADD Create group use for chat =====================//
function add_group_user_post(){
$arrayRequired=array('user_id','user_token','group_id','other_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$data=["group_id"=>$post['group_id'],"user_id"=>$post['other_id']];
$response=$this->Restapi_model->add_group_user($data);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else if ($response == 2) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "user already exist!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data insert Successfully."
        );
      $this->response($data, 200);
    } 
}

//==============end Create group use for chat =====================//

//=========notification status====================//


function notification_status_post(){
$arrayRequired=array('user_id','user_token','notification_status');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->notification_status($post['user_id'],$post['notification_status']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Status Changed Successfully."
        );
      $this->response($data, 200);
    } 
}
//=============Block userlist=================//


function block_list_post(){
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->block_list($post['user_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "No Data Found",
        "result"=>[]
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}
//=======post_report===================//
function post_report_post(){
$arrayRequired=array('user_id','user_token','post_id','reason_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$data=["user_id"=>$post['user_id'],"post_id"=>$post['post_id'],"reason_id"=>$post['reason_id'],"created_at"=>date('Y-m-d')];
$response=$this->Restapi_model->post_report($data);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Report send Successfully."
        );
      $this->response($data, 200);
    } 
}

function getall_reason_post(){
 $arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->getall_reason();
if ($response == 0) {    
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "No Data Found",
        "result"=>[]
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    } 
}
//==========edit post================//
function post_edit_post(){
 $arrayRequired=array('user_id','user_token','post_id','description');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$data=["user_id"=>$post['user_id'],"post_id"=>$post['post_id'],"description"=>$post['description'],"updated_at"=>date('Y-m-d')];
$response=$this->Restapi_model->edit_post($data);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong."
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Post Updated successfully."
        );
      $this->response($data, 200);
    } 
}


//==========delete post================//
function post_delete_post(){
 $arrayRequired=array('user_id','user_token','post_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->delete_post($post['user_id'],$post['post_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong."
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Post Deleted successfully."
        );
      $this->response($data, 200);
    } 
}

//==========Chat user block================//
function chat_block_user_post(){
 $arrayRequired=array('user_id','user_token','block_id','block_status');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$data=["user_id"=>$post['user_id'],"block_id"=>$post['block_id'],"block_status"=>$post['block_status']];
$response=$this->Restapi_model->chat_block_user($data);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong."
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "User Status changed successfully."
        );
      $this->response($data, 200);
    } 
}

//==============Accepted chat status=================//

function accepted_chat_status_post(){
$arrayRequired=array('user_id','user_token','accept_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->accepted_chat_status($post['user_id'],$post['accept_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong."
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "User accepted successfully."
        );
      $this->response($data, 200);
    } 
}

//==========chat media upload===============//
 function chatmedia($FILES){
    $name=time().'_'.$FILES['media']['name'];
    $tmp_name=$FILES['media']['tmp_name'];
    if($this->s3->putObjectFile($tmp_name, "talzo", 'chat_media/'.$name, S3::ACL_PUBLIC_READ)) {
      return 'https://talzo.s3.ap-south-1.amazonaws.com/chat_media/'.$name;
    }else{
    $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong while uploading your file... sorry"
        );
      $this->response($data, 200);
      exit();
    } 
  } 

//=========chat thumnail=================//
function chatmedia1($FILES){
    $name=time().'_'.$FILES['thumb_url']['name'];
    $tmp_name=$FILES['thumb_url']['tmp_name'];
    if($this->s3->putObjectFile($tmp_name, "talzo", 'chat_media/'.$name, S3::ACL_PUBLIC_READ)) {
      return 'https://talzo.s3.ap-south-1.amazonaws.com/chat_media/'.$name;
    }else{
    $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong while uploading your file... sorry"
        );
      $this->response($data, 200);
      exit();
    } 
  } 




//==============Upload media for chat =================//

function chat_media_upload_post(){
$result=[];
$result1='';
$result2='';  
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
if(!empty($_FILES['media']['name']) && isset($_FILES['media']['name'])){
$result1=$this->chatmedia($_FILES);
}
if(!empty($_FILES['thumb_url']['name']) && isset($_FILES['thumb_url']['name'])){
$result2=$this->chatmedia1($_FILES);
}
$result=["media_url"=>$result1,"thumb_url"=>$result2];
$data=array(
    "statusCode" => 200, 
    "APICODERESULT"  => "Media uplaod successfully.",
    "result"=>$result
    );
  $this->response($data, 200);
} 

//==============Following List=================//
function following_list_post(){
$arrayRequired=array('user_id','user_token','other_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->following_list($post['user_id'],$post['other_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "No Data Found.",
        "result"=>[]
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  =>"Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    } 
}


//==============Follower List=================//
function follower_list_post(){
$arrayRequired=array('user_id','user_token','other_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->follower_list($post['other_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "No Data Found.",
        "result"=>[]
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  =>"Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    } 
}

//==========Delete Account================//
function delete_account_post(){
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->delete_account($post['user_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong."
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "User Account Deleted successfully."
        );
      $this->response($data, 200);
    } 
}

function all_posts_post(){
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->all_posts($post['user_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "No Data found.",
        "result"=>[]
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find Successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    } 
}

//========Add user add================//

function createadd_post(){
$todaydate=date("Y-m-d");
// $currenttime=date("H:i:s");
$user_add="";
$thumb_url="";
$arrayRequired=array('user_id','user_token','description');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
 if(!empty($_FILES['media']['name']) && isset($_FILES['media']['name'])){
    $user_add=$this->user_addUpload($_FILES);
  }
 if(!empty($_FILES['thumb_url']['name']) && isset($_FILES['thumb_url']['name'])){
    $thumb_url=$this->thumbnail($_FILES);
  }
 $data=["user_id"=>$post['user_id'],"description"=>$post['description'],"created_at"=>$todaydate,"user_add"=>$user_add,"thumb_url"=>$thumb_url];
$response=$this->Restapi_model->createadd($data);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something Went wrong!"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "User create add successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}


//========user adds list======================//
function user_adds_list_post(){
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->user_adds_list($post['user_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "No Data Found",
        "result"=>[]
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}


//===========All add lists=======================//

function all_adds_list_post(){
$arrayRequired=array('user_id','user_token');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->all_adds_list($post['user_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "No Data Found",
        "result"=>[]
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data find successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    }
}
//===========Delete Add===========//

function add_delete_post(){
$arrayRequired=array('user_id','user_token','add_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->add_delete($post['add_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Delete Successfully."
        );
      $this->response($data, 200);
    } 
}

//===========Add Detail===========//

function add_detail_post(){
$arrayRequired=array('user_id','user_token','add_id');
$var=$this->getErrorMsg($arrayRequired,$this->data);
$post=$this->data;
$this->checkAssessKey($post['user_token'],$post['user_id']);
$this->checkrecord('users', 'user_id', $post['user_id']);
$response=$this->Restapi_model->add_detail($post['add_id']);
if ($response == 0) {    
      $data=array(
        "statusCode" => 300, 
        "APICODERESULT"  => "Something went wrong"
        );
      $this->response($data, 200);
    }
    else
    { 
      $data=array(
        "statusCode" => 200, 
        "APICODERESULT"  => "Data Find Successfully.",
        "result"=>$response
        );
      $this->response($data, 200);
    } 
}



}//class/////////