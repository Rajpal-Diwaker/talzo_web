<?php
class Adminforgot extends CI_Controller {
	
	public function __construct()
	{
		date_default_timezone_set('UTC');
		parent::__construct();
		$this->load->model('admin/Adminforgot_model');
		$this->load->library('session');
		require APPPATH . '/helpers/common.php';

	}
	/*================web forgot=============*/

	function forgot(){	
		$this->load->view('admin/forgot');
	}

	/*================web forgot_process=============*/

	function forgot_process(){
		$email=$this->input->post('email');
		$response=$this->Adminforgot_model->forgot_process($email);
		if(!empty($response)){
			$token=$response->forgot_token;
			$name = (!empty($response->name))?$response->name:'';
	        $logo=base_url().'assets/images/talzo_logo.png';
			$email=$response->email;	
			$link=base_url().'admin/reset?email='.base64_encode($email).'&token='.base64_encode($token).'';
			$subject="Password Recovery.";
			$body="Hello ".$name.","."<p>We have received a request to reset the password for talzo.</p><a target='_blank' href='".$link."' data-saferedirecturl='https://www.google.com/url?q='".$link."'>Reset Password</a><br><br>If above link is not working then copy below url and paste it into browser address bar and hit enter.<br>$link<p>The link can be used only one time.</p><p>Thanks</p><h3>Talzo Support Team</h3>
				  <img src=".$logo.">";
				  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				  <html xmlns="http://www.w3.org/1999/xhtml">
				  <head>
				    <meta http-equiv="Content-Type: text/html; charset=ISO-8859-1\r\n">
				    <title>' . html_escape($subject) . '</title>
				    <style type="text/css">
				      body {
				        font-family: Arial, Verdana, Helvetica, sans-serif;
				        font-size: 16px;
				      }
				      p{
				      	margin-bottom:20px;
				      }
				    </style> 
				  </head>
				  <body>
				    </html>';
				$mail = new Common();
				$res = $mail->forgot_email($email, $body,$subject);
				if($res==1){
				echo '1';die();
				}
			}else{
				echo '2';die();
			}	
		}

//=============reset process==============//
		function reset(){
			$email=base64_decode($this->input->get('email'));
			$token=base64_decode($this->input->get('token'));
			$response=$this->Adminforgot_model->checkstatus($email,$token);
			if($response==1){
			   $emails['data']=$email;
			   $this->load->view('admin/reset_password',$emails);
			}else{
				$this->session->set_flashdata('error','<div class="alert alert-danger text-center" id="error">This link has been expired <br>Please try again.</div>');
				redirect('admin/status');
			}
		}


		/*================webreset process===========*/

		function reset_process(){
			$post=$this->input->post();
			$random=new common();
            $token = $random->generateRandomString();
			$data=["email"=>$post['email'],"password"=>md5($post['password']),"forgot_token"=>$token];
			$result=$this->Adminforgot_model->reset_process($data);
			if($result==1){
				$this->session->set_flashdata('success','<div class="alert alert-success text-center" id="successMessage">Your password has been successfully changed.<br>Please log back into your talzo account using your new password.<br>
				 Thank you!</div>');
				redirect('admin/status');
			}
			else if($result==2){
				$this->session->set_flashdata('error','<div class="alert alert-danger text-center" id="error">Something Went Wrong!</div>');
				redirect('admin/status');
			}
		}


		/*================AppSucess error=============*/

		function status(){
			$this->load->view('admin/status');
		}



	

	}//end class//	 