<?php
class Login extends CI_Controller {
	public function __construct()
	{
		date_default_timezone_set('UTC');
		parent::__construct();
		$this->load->model('admin/Login_model');
		$this->load->library('session');
		require APPPATH . '/helpers/common.php';
	}
	/*===============Login===============*/

	public function login(){
		$this->load->view('admin/login');
	} 

	/*===============Login Process===============*/

	public function login_process(){
	 $post = $this->input->post();
	$data=['email'=>$post['email'],'password'=>md5($post['password'])];
	$response = $this->Login_model->login_process($data); 
	if($response==0) {
	$this->session->set_flashdata('msg','<div class="alert alert-danger text-
	center" id="error">Invalid Credentials!</div>');
	redirect('admin/login','refresh');      
     }else{
	$this->session->set_userdata('name',$response['name']);
	$this->session->set_userdata('email',$response['email']);
	$this->session->set_userdata('admin_id',$response['admin_id']);
	redirect('admin/dashboard');
		}  	
	}



	/*================Logout =============*/
	public function logout(){
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('admin_id');
		redirect('admin/login');
	}
	








}