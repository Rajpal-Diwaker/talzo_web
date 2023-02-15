<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
  require APPPATH . 'helpers/phpmailer/class.phpmailer.php'; 
class Common{

/**
* Constructor
*
* Get instance for Database Lib
*
* @access	public
*/
function __construct()
{
$this->CI =& get_instance();
log_message('debug', "User Class Initialized");
}

// --------------------------------------------------------------------

/**
* checkRecord
*
* @access	public
* @param	string	the username
* @param	string	what row to grab
* @return	string	the data
*/
public function notification($device_token=null,$device_type=null,$message=null,$notitype=null,$badge =null){
// print_r($message);die;
$url = "https://fcm.googleapis.com/fcm/send";
$server_key = "AAAAanEc3ps:APA91bHgd01bAG--9q5b3_tJ_5DrXZ2P4nm8LxUShNQbkcmoEaYaEhafuikUIhqOGwcxquO9FMzkIQuyG6KWwWbTi3elMN_rnKLZR2DpvM5g9CW6fN7wdOoOhy6uSYSDWJhpldj5TI-2";
if($device_type == "android"){//android
$AndroidService[] = $device_token;
$resgistrationIDs = $AndroidService;

$fields = array(
'registration_ids'=>$resgistrationIDs,
'data'=>array(
'title'=>$message['title'],
'body'=>$message['body'],
'data' =>$message['body'],
'badge'=>(int)1,
'sound'=>'default'),
'priority'=>'high'
);
//print_r($fields);die();

//CURL request to route notification to FCM connection server (provided by Google)	
$headers = array(
'Content-Type:application/json',
'Authorization:key='.$server_key
);
//CURL request to route notification to FCM connection server (provided by Google)	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
$result = curl_exec($ch);
//print_r($result);die();
if($result == FALSE){
return curl_error($ch);
}
else{
return 1;
}
curl_close($ch);
}

else{//IOS

$AndroidService[] = $device_token;
$resgistrationIDs = $AndroidService;

$fields = array(
'registration_ids'=>$resgistrationIDs,
'notification'=>array(
'title'=>$message['title'],
'body'=>$message['body'],
'data' =>$message['body'],
'badge'=>(int)1,
'sound'=>'default'),
'priority'=>'high'
);
//print_r($fields);die();

//CURL request to route notification to FCM connection server (provided by Google)	
$headers = array(
'Content-Type:application/json',
'Authorization:key='.$server_key
);
//CURL request to route notification to FCM connection server (provided by Google)	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
$result = curl_exec($ch);//print_r($result);die();
if($result == FALSE){
return curl_error($ch);
}
else{
return $result;
}
curl_close($ch);
}
}

public function forgot_email($email=null,$body=null,$subject=null){
                 $from='uae.ahmed.sh@gmail.com';
	             $fromname="talzo";
                $mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->SMTPSecure = 'ssl';
				$mail->Host = 'smtp.gmail.com'; // specify main and backup 
				$mail->Port = '465';
				$mail->SMTPAuth = true; // turn on SMTP authentication
				$mail->Username = "uae.ahmed.sh@gmail.com"; // SMTP 
				$mail->Password = "Burlfloor@27"; // SMTP password 
				$mail->From = $from;
				$mail->FromName = $fromname;
				$mail->AddAddress($email);
				$mail->WordWrap = 50;
				$mail->IsHTML(true);
				$mail->Subject = $subject;
				$mail->Body = $body;
				$mail->AltBody = "";
				if ($mail->Send())
                 {
                   return 1; // if send mail
				}
				else
				{
				return 0; //if not sent mail
				}
	
}

function generateRandomString($length = 10) {
    $characters = '0123456789iWantUabcdefghijklmnopqrstuvwxyzTodayABCDEFGHIJKLMNOPQRSTUVWXYZTechugo';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}



	

}//======class========//