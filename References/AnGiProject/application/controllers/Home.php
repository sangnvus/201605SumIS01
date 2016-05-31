<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
  
class Home extends CI_Controller {

	 public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        // $this->load->database();
    }
  
    public function index() {
        echo 'BCK Hello Controller <br>';
         // Data cần truyền qua view
        $data1 = array(
            'title' => 'Đây là trang login',
            'message' => 'Nhập Thông Tin Đăng Nhập'
        );
  
        // Load view và truyền data qua view
        $this->load->view('login', $data1);
        $this->session->set_userdata("BCK", "10");
       	 $data=array(
            "username" => "Kaito",
            "email" => "codephp2013@gmail.com",
            "website" => "freetuts.net",
            "gender" => "Male",
        );
        $this->session->set_userdata($data);
        redirect("home/index2");
        // $query=$this->db->query("SELECT * FROM album order By id");
        // $data=$query->result_array();
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
    }

    public function index2(){
        echo base_url();
        $user=$this->session->userdata("username");
        $level=$this->session->userdata("gender");
        $email=$this->session->userdata("email");
        echo "Username: $user, Email: $email, Gender: $level";
  	}

}