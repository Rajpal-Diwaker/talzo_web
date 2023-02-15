<?php
class Login_model extends CI_Model
{

/*=============login process==========*/

public function login_process($array) { 
  $select=$this->db->select('*') 
                   ->where('email',$array['email'])
                  ->where('password',$array['password'])
                   ->get('admin');                                  
  if($select->num_rows()>0){
   $result=$select->row_array();
    return $result;
    }else{
     return 0;
      }   
}



}