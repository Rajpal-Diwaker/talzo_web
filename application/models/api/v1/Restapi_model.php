<?php
class Restapi_model extends CI_Model
{

 //=========file upload1==============//
 function user_fileUpload1($FILES){
  $name=time().'_'.$FILES['image']['name'];
  $tmp_name=$FILES['image']['tmp_name'];
  if($this->s3->putObjectFile($tmp_name, "talzo", 'users_image/'.$name, S3::ACL_PUBLIC_READ)) {
    return 'https://talzo.s3.ap-south-1.amazonaws.com/users_image/'.$name;
  }else{
    echo "<strong>Something went wrong while uploading your file... sorry.</strong>";die();
  } 
} 
/* ==================== Check Access Key  =================== */

function checkAssessKey($user_token,$user_id)
{
 $query=$this->db->where('user_token',$user_token)->where('user_id',$user_id)->get('users');

 if($query->num_rows()>0)
 {
  return 1;
}
else
{
  return 0;
}
}

//====check_number=============//

function check_number($country_code,$phone){
 $select=$this->db->where('country_code',$country_code)
                  ->where('phone',$phone)
                  ->get('users');
 if($select->num_rows()>0){
  return 0;
 }else{
  return 1;
 }                  
}

/*===========signup==============*/

public function signup($data){
  $query=$this->db->where('phone',$data['phone'])
  ->get('users');
  if($query->num_rows()>0){
    return 0;
  }else{
    $insert=$this->db->insert('users',$data);
    $id=$this->db->insert_id();
    $select1=$this->db->select('user_id,name,email,country_code,country_name,bio,phone,user_image,user_lang,user_token')->where('user_id',$id)->get('users');
    if($select1->num_rows()>0){
      $result=$select1->row_array();
      return $result;
    }
  }

}

//===========Login APi=============//

function login($country_code,$phone){
$query=$this->db->where('country_code',$country_code)->where('phone',$phone)->where('user_status','0')->where('delete_status','0')->get('users'); 
if($query->num_rows()>0){
  return 3;
} 
  $select=$this->db->where('country_code',$country_code)->where('phone',$phone)->where('delete_status','0')->get('users');
  if($select->num_rows()>0){
   $result=$select->row_array();
   $user_id=$result['user_id'];
   $random=new common();
   $user_token = $random->generateRandomString();
   $this->db->where('user_id',$user_id)->set('user_token',$user_token)->update('users');
   $select1=$this->db->select('user_id,name,email,country_code,country_name,bio,phone,user_image,user_lang,user_token')->where('user_id',$user_id)->get('users');
   if($select1->num_rows()>0){
    $result1=$select1->row_array();
    return $result1;
  }
  else{
    return 0;
  }

}
else{
  return 0;
}

}

//==========update_device_token===================//

function update_device_token($data){
  $update=$this->db->where('user_id',$data['user_id'])->update('users',$data);
  if($update==1){
    return 1;
  }else{
    return 0;
  }  
}

//==========home_followers_list===================//

function home_followers_list($user_id,$offset){
 $select=$this->db->select('u.user_id,u.name,u.user_image,IFNULL((select is_follow from followers as f where f.user_id='.$user_id.' and f.following_id=u.user_id),0) as is_follow ')
 ->from('users as u')
 ->where('u.user_status','1')
 ->where('u.delete_status','0')
 ->where('u.user_id NOT IN(select ub.block_user_id from user_block as ub where ub.user_id='.$user_id.' AND block_status="1")')
 ->where_not_in('u.user_id',$user_id)
 ->order_by('u.user_id','desc')
 ->limit(10,$offset)
 ->get();
 if($select->num_rows()>0){
  $result=$select->result_array();
  return $result;
}else{
  return [];
}                 
}


//==========change_follower_status===================//

function change_follower_status($data){
  $select=$this->db->where('user_id',$data['user_id'])->where('following_id',$data['following_id'])->get('followers');
  if($select->num_rows()>0){
   $update=$this->db->where('user_id',$data['user_id'])->where('following_id',$data['following_id'])->set('is_follow',$data['is_follow'])->update('followers');
   if($update==1){
    return 1;
  }else{
    return 0;
  } 
}
else{
 $this->db->insert('followers',$data);
 return 1; 
} 
}

//=======user_profile===================//

function user_profile($user_id){
  $result1=[];
  $bronze=0;
  $silver=0;
  $gold=0;
  $all_post=$this->db->where('user_id',$user_id)->from("posts")->where('post_status','1')->count_all_results();
  $all_follower=$this->db->where('following_id',$user_id)->where('is_follow','1')->from("followers")->count_all_results(); 
  $all_following=$this->db->where('user_id',$user_id)->where('is_follow','1')->from("followers")->count_all_results();
  $select=$this->db->select('user_id,name,email,country_code,country_name,bio,phone,user_image,bio')->where('user_id',$user_id)->get('users');
  if($select->num_rows()>0){
    $result=$select->row_array();
    $select1=$this->db->select('post_id,thumb_url,user_post,description,category_id,views,stars,IFNULL((select star_status from post_stars as ps where ps.user_id='.$user_id.' and ps.post_id=posts.post_id),0) as star_status')->where('user_id',$user_id)->where('post_status','1')->order_by('post_id','desc')->get('posts');
    if($select1->num_rows()>0){
      $result1=$select1->result_array();
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
    $result['posts']=$result1;
    $result['bronze']=$bronze;
    $result['silver']=$silver;
    $result['gold']=$gold;
    $result['all_post']=$all_post;
    $result['all_follower']=$all_follower;
    $result['all_following']=$all_following;              
    return $result; 
  }else{
    return 0;
  }
}

//================update user profile===========//
function update_user_profile($post){
  $user_id=$post['user_id']; 
  $user_image='';
  $todaydate=date("Y-m-d");  
  $select=$this->db->where('user_id',$user_id)->get('users');
  if($select->num_rows()>0){
   $result=$select->row();
   $name = (!empty($post['name']))?$post['name']:$result->name;
   $country_name = (!empty($post['country_name']))?$post['country_name']:$result->country_name;
   $email = (!empty($post['email']))?$post['email']:$result->email;
   $country_code = (!empty($post['country_code']))?$post['country_code']:$result->country_code;
   $phone = (!empty($post['phone']))?$post['phone']:$result->phone;

   $bio = (!empty($post['bio']))?$post['bio']:$result->bio;

   if(!empty($_FILES['image']['name'])){
     $user_image=$this->user_fileUpload1($_FILES);
   }elseif(empty($_FILES['image']['name'])){
    $user_image=$result->user_image;
  }    
  $updatedata=["name"=>$name,"email"=>$email,"country_code"=>$country_code,"country_name"=>$country_name,"user_image"=>$user_image,"updated_at"=>$todaydate,"phone"=>$phone,"bio"=>$bio];
  $update=$this->db->where('user_id',$user_id)->set($updatedata)->update('users');
  if($update==1){
    $selects=$this->db->select('user_id,name,email,country_code,country_name,phone,user_image,user_lang,user_token,bio')->where('user_id',$user_id)->get('users');
    $results=$selects->row_array();
    return $results;            
  }
}else{
 return 0;
}
}

//=============all_category_list==================//

function all_category_list($user_id,$lang){
 $langs='_'.$lang;
 $select=$this->db->select("category_id,category$langs")
 ->get('category');
 if($select->num_rows()>0){
  $result=$select->result_array();
  return $result;
}else{
  return 0;
}                 
}

//=========other_user_profile===============//

function other_user_profile($user_id,$other_id){
  $result1=[];
  $bronze=0;
  $silver=0;
  $gold=0;
  $all_post=$this->db->where('user_id',$other_id)->from("posts")->where('post_status','1')->count_all_results();
  $all_follower=$this->db->where('following_id',$other_id)->where('is_follow','1')->from("followers")->count_all_results(); 
  $all_following=$this->db->where('user_id',$other_id)->where('is_follow','1')->from("followers")->count_all_results(); 
  $select=$this->db->select('user_id,country_name,bio,country_code,name,email,phone,user_image,IFNULL((select is_follow from followers as f where f.user_id='.$user_id.' and f.following_id=users.user_id),0) as is_follow')
  ->where('user_id',$other_id)
  ->get('users');
  if($select->num_rows()>0){
   $result=$select->row_array();
   $select1=$this->db->select('post_id,thumb_url,user_post,description,category_id,views,stars,IFNULL((select star_status from post_stars as ps where ps.user_id='.$other_id.' and ps.post_id=posts.post_id),0) as star_status')
   ->where('user_id',$other_id)->where('post_status','1')
   ->order_by('post_id','desc')
   ->get('posts');
   if($select1->num_rows()>0){
    $result1=$select1->result_array();
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
  $result['posts']=$result1;
  $result['bronze']=$bronze;
  $result['silver']=$silver;
  $result['gold']=$gold;
  $result['all_post']=$all_post;
  $result['all_follower']=$all_follower;
  $result['all_following']=$all_following;                  
  return $result; 
}else{
  return 0;
}
}


//=========user add post=================//

function add_userpost($data){
  $this->db->insert('posts',$data);
  $last_id=$this->db->insert_id();
  if($last_id>0){
   $select=$this->db->where('post_id',$last_id)->get('posts');
   if($select->num_rows()>0){
    $result=$select->row_array();
    return $result;
  }else{
    return 0;
  } 
}else{
  return 0;
}  
}

//=============search_talent==================//

function search_talent($post){
  $user_id=$post['user_id'];
  $this->db->select('u.user_id,u.name,u.user_image,IFNULL((select is_follow from followers as f where f.user_id='.$user_id.' and f.following_id=u.user_id),0) as is_follow');
  $this->db->from('users as u');
  if(!empty($post['category_id'])){
    $this->db->join('posts as p','u.user_id = p.user_id','left');
    $this->db->where('p.category_id',$post['category_id']);
    $this->db->where('p.post_status','1');
    if(!empty($post['search'])){
      $this->db->like('u.name',$post['search']);
    }
    $this->db->order_by('p.user_id','desc');
    $this->db->group_by('p.user_id');   
  }
  if(empty($post['category_id']) && !empty($post['search'])){
    $this->db->like('u.name',$post['search']);
  }
  $this->db->where('u.user_id NOT IN(select ub.block_user_id from user_block as ub where ub.user_id='.$user_id.' AND block_status="1")');
  $this->db->where_not_in('u.user_id',$user_id);
  $this->db->where('u.user_status','1');
  $this->db->where('u.delete_status','0');
  $select=$this->db->get();
               // echo $this->db->last_query();die;
  if($select->num_rows()>0){
    $result=$select->result_array();
    return $result; 
  }else{
    return [];
  }              
}

//======search_user==================//

function search_user($post){
  $select=$this->db->select('u.user_id,u.country_name,u.bio,u.country_code,u.name,u.email,u.phone,u.user_image,IFNULL((select is_follow from followers as f where f.user_id='.$post['user_id'].' and f.following_id=u.user_id),0) as is_follow')
  ->like('u.name',$post['search'])
  ->where_not_in('u.user_id',$post['user_id'])
  ->get('users as u');
  if($select->num_rows()>0){
   $result=$select->result_array();
   return $result; 
 }else{
  return 0;
}
}

//========check_mobile================//
function check_mobile($phone){
 $select=$this->db->where('phone',$phone)
 ->get('users');
 if($select->num_rows()>0){
  return 0;
}else{
  return 1;
}                
}

//===========user_block===================//

function user_block($data){
 $select=$this->db->where('user_id',$data['user_id'])
                  ->where('block_user_id',$data['block_user_id'])
                  ->get('user_block');
 if($select->num_rows()>0){
  $update=$this->db->where('user_id',$data['user_id'])->where('block_user_id',$data['block_user_id'])->update('user_block',$data);
  if($update==1){
  $query=$this->db->where('user_id',$data['user_id'])->where('block_user_id',$data['block_user_id'])->get('user_block');
  $result=$query->row_array();
  return $result;
  }else{
    return 0;
  } 
}else{
  $this->db->insert('user_block',$data);
  $insert_id=$this->db->insert_id();
  if($insert_id>0){
   $query1=$this->db->where('block_id',$insert_id)->get('user_block');
   $result1=$query1->row_array();
   return $result1; 
  }else{
    return 0;
  }
}                  
}


//===============home=====================//

function home($user_id,$category_id,$lang){
$langs='_'.$lang;
$where="((f.user_id = '$user_id' and f.is_follow='1') or p.user_id='$user_id')";
 $category=[]; 
 if(!empty($category_id)){
 $category=explode(",",$category_id);
 } 

  $this->db->select('u.name,u.user_id,c.category_id,c.category'.$langs.',u.user_image,p.post_id,p.thumb_url,p.user_post,p.description,p.views,p.stars,IFNULL((select star_status from post_stars as ps where ps.user_id='.$user_id.' and ps.post_id=p.post_id),0) as star_status');
  $this->db->from('posts as p');
  $this->db->join('followers as f','p.user_id=f.following_id','left');
  $this->db->join('users as u','p.user_id=u.user_id','left');
  $this->db->join('category as c','p.category_id=c.category_id','left');
  if(!empty($category)){
    $this->db->where_in('p.category_id',$category);
  }
  $this->db->where('p.post_status','1'); 
   $this->db->where($where); 
  $this->db->where('p.post_id NOT IN(select r.post_id from reports as r where r.user_id='.$user_id.')');
  $this->db->where('u.user_status','1');
  $this->db->where('u.delete_status','0');
   $this->db->where('u.user_id NOT IN(select ub.block_user_id from user_block as ub where ub.user_id='.$user_id.' AND block_status="1")');
  $this->db->group_by('p.post_id');
  $this->db->order_by('p.post_id','desc');
  $select=$this->db->get();
  // echo $this->db->last_query();die;
  if($select->num_rows()>0){
    $result=$select->result_array();
    return $result; 
  }else{
    return [];
  }             
}


//========user_views====================//

function post_view($user_id,$post_id){
  $select=$this->db->where('user_id',$user_id)->where('post_id',$post_id)->get('post_views');
  if($select->num_rows()>0){
   return 1; 
 }
 else{
   $data=["user_id"=>$user_id,"post_id"=>$post_id]; 
   $this->db->insert('post_views',$data);  
   $update=$this->db->where('post_id',$post_id)->set('views','views+1', FALSE)->update('posts');
   if($update==1){
    return 1;
  }else{
    return 0;
  }
}
}

//=======post_star========================//

function post_star($user_id,$post_id,$star_status){  
  $select=$this->db->where('user_id',$user_id)->where('post_id',$post_id)->get('post_stars');
  if($select->num_rows()>0){
   $result=$select->row_array();
   $last_status=$result['star_status']; 
   if($star_status==0 && $last_status==1 ){
    $this->db->where('post_id',$post_id)->set('stars','stars-1', FALSE)->update('posts'); 
  }elseif($star_status==1 && $last_status==0){
    $this->db->where('post_id',$post_id)->set('stars','stars+1', FALSE)->update('posts'); 
  } 
  $update=$this->db->where('user_id',$user_id)->where('post_id',$post_id)->set('star_status',$star_status)->update('post_stars');
  if($update==1){
    $select=$this->db->select('u.name,u.user_image,p.post_id,p.thumb_url,p.user_post,p.description,p.views,p.stars,IFNULL((select star_status from post_stars as ps where ps.user_id='.$user_id.' and ps.post_id='.$post_id.'),0) as star_status')
    ->from('posts as p')
    ->join('users as u','p.user_id=u.user_id','left')
    ->where('p.post_status','1')
    ->where('p.post_id',$post_id)
    ->where('u.user_status','1')
    ->where('u.delete_status','0')
    ->get();
    if($select->num_rows()>0){
      $result=$select->row_array();
      return $result;
    }else{
      return 0;
    }              
  }else{
    return 0;
  }
}
else{
  if($star_status==1){
    $this->db->where('post_id',$post_id)->set('stars','stars+1', FALSE)->update('posts'); 
  }
  $data=["user_id"=>$user_id,"post_id"=>$post_id,"star_status"=>$star_status]; 
  $this->db->insert('post_stars',$data); 
  $last_id=$this->db->insert_id();
  if($last_id>0){
    $select1=$this->db->select('u.name,u.user_image,p.post_id,p.thumb_url,p.user_post,p.description,p.views,p.stars,IFNULL((select star_status from post_stars as ps where ps.user_id='.$user_id.' and ps.post_id='.$post_id.'),0) as star_status')
    ->from('posts as p')
    ->join('users as u','p.user_id=u.user_id','left')
    ->where('p.post_status','1')
    ->where('p.post_id',$post_id)
    ->where('u.user_status','1')
    ->where('u.delete_status','0')
    ->get();
    if($select1->num_rows()>0){
      $result=$select1->row_array();
      return $result;
    }else{
      return 0;
    }
  }else{
    return 0;
  }
}
}

//==========post_detail===================//

function post_detail($user_id,$post_id,$lang){
   $langs='_'.$lang;
  $select=$this->db->select('u.name,u.user_id,p.post_id,p.thumb_url,p.user_post,p.description,p.views,p.stars,c.category_id,c.category'.$langs.',IFNULL((select star_status from post_stars as ps where ps.user_id='.$user_id.' and ps.post_id='.$post_id.'),0) as star_status')
  ->from('posts as p')
  ->join('users as u','p.user_id=u.user_id','left')
   ->join('category as c','p.category_id=c.category_id','left')
  ->where('p.post_id',$post_id)
  ->where('p.post_status','1')
  ->get();
  if($select->num_rows()>0){
    $result=$select->row_array();
    return $result;
  }else{
    return 0;
  }
}

//============create_group use for chat =================

function create_group($data){
  $this->db->insert('groups',$data);
  $insert_id=$this->db->insert_id();
  if($insert_id>0){
   $select=$this->db->select('name')->where('user_id',$data['group_admin'])->get('users');
   $result=$select->row_array();
   $name=$result['name']; 
   $userdata=["s_id"=>$data['group_admin'],"group_id"=>$insert_id,"chat_type"=>"group","action"=>"created by ".$name];
   $this->db->insert('chat',$userdata);
   $groupdata=["group_id"=>$insert_id,"user_id"=>$data['group_admin']];
   $this->db->insert('group_users',$groupdata);
   return 1;
 }else{
  return 0;
}  
}
//============end create_group use for chat =================

//===========add_group_user===============//

function add_group_user($data){
  $sel=$this->db->where('user_id',$data['user_id'])->where('group_id',$data['group_id'])->get('group_users');
  if($sel->num_rows()>0){
    return 2;
  }

  $this->db->insert('group_users',$data);
  $insert_id=$this->db->insert_id();
  if($insert_id>0){
   $select=$this->db->select('name')->where('user_id',$data['user_id'])->get('users');
   $result=$select->row_array();
   $name=$result['name']; 
   $userdata=["s_id"=>$data['user_id'],"group_id"=>$data['group_id'],"chat_type"=>"group","action"=>"Added ".$name];
   $this->db->insert('chat',$userdata);
   return 1;
 }else{
  return 0;
} 
}


//=========user add=================//

function createadd($data){
  $this->db->insert('advertisements',$data);
  $last_id=$this->db->insert_id();
  if($last_id>0){
   $select=$this->db->select('add_id,thumb_url,user_add,description,created_at')->where('add_id',$last_id)->get('advertisements');
   if($select->num_rows()>0){
    $result=$select->row_array();
    return $result;
  }else{
    return 0;
  } 
}else{
  return 0;
}  
}

//=============adds_list==================//

function user_adds_list($user_id){
 $todaydate=date('Y-m-d'); 
 $select=$this->db->select("add_id,thumb_url,user_add,description,created_at,views")->where('user_id',$user_id)->where('expired_date >=',$todaydate)->get('advertisements');
 if($select->num_rows()>0){
  $result=$select->result_array();
  return $result;
}else{
  return 0;
}                 
}
//=============adds_list==================//
function all_adds_list($user_id){
 $todaydate=date('Y-m-d'); 
 $select=$this->db->select("u.user_id,u.name,a.add_id,a.thumb_url,a.user_add,a.description,a.created_at,a.views")
 ->from('advertisements as a')
 ->join('users as u','u.user_id=a.user_id','left')
 ->where_not_in('u.user_id',$user_id)
 ->where('a.expired_date >=',$todaydate)
 ->get();
 if($select->num_rows()>0){
  $result=$select->result_array();
  return $result;
}else{
  return 0;
}                 
}

//=============add_delete======================//

function add_delete($add_id){
  $this->db->where('add_id',$add_id)->delete('advertisements');
  if($this->db->affected_rows()>0){
    return 1;
  }else{
    return 0;
  }  
}


//==============add_detail========================//

function add_detail($add_id){
  $select=$this->db->select("add_id,thumb_url,user_add,description,created_at,views")->where('add_id',$add_id)->get('advertisements');
  if($select->num_rows()>0){
    $result=$select->row_array();
    return $result;
  }else{
    return 0;
  }  
}

//============notification_status========

function notification_status($user_id,$notification_status){
  $update=$this->db->where('user_id',$user_id)->set('notification_status',$notification_status)->update('users');
  if($update==1){
    return 1;
  }else{
    return 0;
  }  
}

//=============Block userlist=================//

function block_list($user_id){
  $select=$this->db->select('u.user_id,u.name,u.user_image')
  ->from('user_block as ub')
  ->join('users as u','ub.block_user_id=u.user_id','left')
  ->where('ub.user_id',$user_id)
  ->where('ub.block_status','1')
  ->get();
  if($select->num_rows()>0){
    $result=$select->result_array();
    return $result;
  }
  else{
    return 0;
  }                 
}
//==========post_report====================//

function post_report($data){
  $this->db->insert('reports',$data);
  $insert_id=$this->db->insert_id();
  if($insert_id>0){
    return 1;
  }else{
    return 0;
  }  
}

//======getall_reason================//

function getall_reason(){
 $select=$this->db->get('allreason');
 if($select->num_rows()>0){
  $result=$select->result_array();
  return $result;
}else{
  return [];
} 
}

//=========edit_post=====================//

function edit_post($data){
$update=$this->db->where('post_id',$data['post_id'])->where('user_id',$data['user_id'])->update('posts',$data);
if($update==1){
  return 1;
}else{
  return 0;
}  
}

//==========delete_post==============//

function delete_post($user_id,$post_id){
$delete=$this->db->where('user_id',$user_id)->where('post_id',$post_id)->delete('posts');
if($this->db->affected_rows()>0){
  return 1;
}else{
  return 0;
}  
}

//========chat_user_block==================//

function chat_block_user($data){
$select=$this->db->where('user_id',$data['user_id'])->where('block_id',$data['block_id'])->get('chat_block_users');
if($select->num_rows()>0){
 $update=$this->db->where('user_id',$data['user_id'])->where('block_id',$data['block_id'])->update('chat_block_users',$data);
 if($update==1){
  return 1;
 }else{
  return 0;
 } 
}else{
$this->db->insert('chat_block_users',$data);
if($this->db->insert_id()>0){
  return 1;
}else{
  return 0;
}  
}
}


//============accepted_chat_status============//

function accepted_chat_status($user_id,$accept_id){
 $select=$this->db->where('user_id',$user_id)->where('accept_id',$accept_id)->get('check_users_accept');
 if($select->num_rows()>0){
  // echo "dddd";die;
  $delete=$this->db->where('user_id',$user_id)->where('accept_id',$accept_id)->delete('check_users_accept');
  if($this->db->affected_rows()>0){
    return 1;
  }else{
    return 0;
  }
 }else{
  return 0;
 } 
}

//=============following_list================//

function following_list($user_id,$other_id){
$this->db->select('u.user_id,u.name,u.user_image,f.is_follow');
$this->db->from('followers as f');
$this->db->join('users as u','f.following_id=u.user_id','left');
$this->db->where('f.user_id',$other_id);
$this->db->where('f.is_follow','1');
  $select=$this->db->get();
   if($select->num_rows()>0){
    $result=$select->result_array();
    foreach ($result as $key => $value) {
  $select1=$this->db->where('following_id',$result[$key]['user_id'])
                  ->where('user_id',$user_id)
                  ->where('is_follow','1')
                  ->get('followers');
    if($select1->num_rows()>0){
      $result[$key]['is_follow']="1";
    } else{
       $result[$key]['is_follow']="0";
    }             
    }
    return $result;
   }else{
    return 0;
   }     
}
  
//=============follower_list================//

function follower_list($user_id){
 $select=$this->db->select('u.user_id,u.name,u.user_image,f.is_follow')
                  ->from('followers as f')
                  ->join('users as u','f.user_id=u.user_id','left')
                  ->where('f.following_id',$user_id)
                  ->where('f.is_follow','1')
                  ->get();
   if($select->num_rows()>0){
    $result=$select->result_array();
    return $result;
   }else{
    return 0;
   }                  
}

//============Delete Account================//

function delete_account($user_id){
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

//=======all_posts======================//
function all_posts($user_id){
 $select=$this->db->select('u.user_id,u.name,u.user_image,p.post_id,p.user_post,p.thumb_url')
       ->from('posts as p')
       ->join('users as u','p.user_id=u.user_id','left')
       ->where('p.post_status','1')
       ->where('u.user_status','1')
       ->where('u.delete_status','0')
       ->get();
   if($select->num_rows()>0){
    $result=$select->result_array();
    return $result;
   }else{
    return 0;
   }     
}

}////////===============class================//

