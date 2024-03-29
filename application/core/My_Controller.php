<?php
class My_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
		    $this->form_validation->set_error_delimiters('<strong><div class="text-danger">', '</div></strong>');
        date_default_timezone_set('Asia/Kolkata');
    }

    /* 
		Admin Login Session Check
    */
    public function check_admin_login(){

    	if(empty($this->session->userdata('EMAIL')) AND empty($this->session->userdata('USER_ID')))
			return redirect('Login');
    }

    public function render($main, $data=array(), $menu="common/menu", $header = "common/header",  $footer = "common/footer"){
        $data['main'] = $main;
        $data['header'] = $header;
        $data['menu'] = $menu;
        $data['footer'] = $footer;

        $userdata = $this->session->userdata(USER_INFO);
        if(!isset($userdata)){
            $data['isLogin'] = false;
            redirect('/admin/user/signin', 'refresh');
        }else {
          $data['isLogin'] = true;
          $data['user_info'] = $this->session->userdata(USER_INFO);
        }
        
        $this->load->view("common/layout", $data);
    }

    public function bet_render($main, $data=array(), $sub_header = "bet_common/sub_header", $header = "bet_common/header",  $footer = "bet_common/footer"){
        $data['main'] = $main;
        $data['header'] = $header;
        $data['sub_header'] = $sub_header;
        $data['footer'] = $footer;
        if($this->session->userdata('userinfo')){
            $data['isLogin'] = true;
            $data['username'] = $this->session->userdata('userinfo')['name'];
        }else{
            $data['isLogin'] = false;
        }
        
        $this->load->view("bet_common/layout", $data);
    }

    public function returnVal($data) {
        echo json_encode($data);
    }

    public function uploadFile($file) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir))
        {
          mkdir($target_dir, 0777);
        }
        $temp       = explode(".",$file['name']);
        $extension  = end($temp);
        $target_file = $target_dir . rand(). time(). "." . $extension;
        $imageFileType = 
          strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if file already exists
        if (file_exists($target_file)) {
          echo json_encode(
             array(
               "status" => 0,
               "data" => array()
               ,"msg" => "Sorry, file already exists."
             )
          );
          die();
        }
        // Check file size
        if ($file["size"] > 50000000) {
          // echo json_encode(
          //    array(
          //      "status" => 0,
          //      "data" => array(),
          //      "msg" => "Sorry, your file is too large."
          //    )
          //  );
          // die();
        }
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
          return $target_file;
        } 

        return $target_file;
    }

    public function sendNotification($token, $title, $body) {
      $json_data = [
          "to" => $token,
          "notification" => [
              "body" => $body,
              "title" => $title,
          ]
      ];
      $data = json_encode($json_data);
      //FCM API end-point
      $url = 'https://fcm.googleapis.com/fcm/send';
      //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
      $server_key = 'AAAAIXQDvJw:APA91bGh3aHCOTanCKFHkJYYVhUuX3C90EwsGTskkqkCECCxZdTeaoPior3A5D_L3aDdKBrNcmuOQ0aYaM19rzaifwfl-73dDAVkqbZqzseOr2rsBGzBnyf0-YO_1qLct4Oo-bSNlr7J';
      //header with content_type api key
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
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      $result = curl_exec($ch);
      // if ($result === FALSE) {
      //     die('Oops! FCM Send Error: ' . curl_error($ch));
      // }
      curl_close($ch);
    }
}

?>