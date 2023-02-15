<?php
class Adminforgot_model extends CI_Model
{

/*=================Appbreset_process=======*/

function forgot_process($email){
$select=$this->db->where('email',$email)
                 ->get('admin');
 if($select->num_rows()>0){
$random=new common();
$token = $random->generateRandomString();
$update=$this->db->set('forgot_token',$token)
                 ->where('email',$email)
                 ->update('admin');
  if($update==1){
   $select1=$this->db->where('email',$email)->get('admin');
   $result=$select1->row();
   return $result; 
  }                
 }else{
  return []; 
 }                
}

/*=================webreset_process=======*/


function reset_process($data){
$select=$this->db->where('email',$data['email'])
                  ->get('admin');         
 if($select->num_rows()>0){
 $update=$this->db->where('email',$data['email'])
                  ->set('password',$data['password'])
                  ->set('forgot_token',$data['forgot_token'])
                  ->update('admin');                                 
   if($update==1){
    return 1;
   }else{
    return 2;
   }                
 }                 
}


/*=================admin checkstatus===========*/

function checkstatus($email,$token){
$select=$this->db->where('email',$email)
                 ->where('forgot_token',$token)
                  ->get('admin');
 if($select->num_rows()>0){
 return 1;
 }else{
  return 2;
 } 
}


} 
