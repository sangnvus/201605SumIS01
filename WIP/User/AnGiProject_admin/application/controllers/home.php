<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
  
class Home extends CI_Controller {

	 public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
    }
  
    public function index() {                    
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        $data = array();
        $data['user'] = $user;
        $data['level'] = $level;
        $data['content'] = 'site/home/index/index.phtml';      

        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function search() {
        $data = array();
        $data['content'] = 'site/home/search/index.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
            
    /***********************Ham index liet ke giao dien trang home****************************/
    public function login()
    {        
        $ok = 1;
        $user = $this->input->post('username');
        $pass = $this->input->post('password');
    
        if($user == '') {
            $data['error_user'] = '<br/><font color=red>Please enter username</font>';
            $ok = 0;
        }
        
        if($pass == '') {
            $data['error_pass'] = '<br/><font color=red>Please enter password</font>';
            $ok = 0;
        }
          
        if($ok == 1) {
            //$query_login = $this->db->query("SELECT * FROM users WHERE (userName = '$user' OR userMail = '$user') AND userPass = '$pass'");
            $query_login = $this->db->query("SELECT * FROM users WHERE (userName = '$user' OR userMail = '$user') AND userPass = md5('$pass')");

            if($query_login->num_rows() == 1) {
                $this->load->library('session');
                $result = $query_login->result();
                foreach ($result as $userItem){
                    $dataSet = array(
                        'user' => $userItem->userName,
                        'level' => $userItem->userLevel,
                        'isActived' => $userItem->userActived
                    );
                }
                if($dataSet['isActived'] == 0) {
                    $data['error_notAdmin'] = '<br/><font color=red>Người dùng chưa kích hoạt!</font>';
                    echo $this->returnError('Người dùng chưa kích hoạt');
                }
                if($dataSet['level'] == 2 || $dataSet['level'] == 1 || $dataSet['level'] == 0) {
                    $this->session->set_userdata($dataSet);
                    echo $this->returnSuccess('Login success');
                }
                else
                {
                    $data['error_notAdmin'] = '<br/><font color=red>Only admin can login!</font>';
                    echo $this->returnError('Only admin can login');
                }
                
                
                
            }else{                                                               
                echo $this->returnError('Wrong username or password. Please try again');
            }
        }else{                             
            echo $this->returnError('Wrong username or password. Please try again');
        }
    }
            
    /***********************Ham index liet ke giao dien trang home****************************/
    public function logout()
    {     
        $this->session->sess_destroy();
        redirect(base_url().'home');      
    }
                    
    public function returnError($msg){
        return json_encode(array(
                    'text' => $msg,
                    'type' => 'error'
            ));   
    }
    
    public function returnSuccess($msg){        
        return json_encode(array(
                    'text' => $msg,
                    'type' => 'success'
            ));        
    }
    
}