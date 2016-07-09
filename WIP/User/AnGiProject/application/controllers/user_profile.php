<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_profile extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form','url','date'));
        $this->load->library(array('session', 'form_validation', 'email'));
        $this->load->database();
        $this->load->model('User_model');
    }

    public function index() {
        $ID =3;
        $data = array();
        $data['content'] = 'site/user/profile/index.phtml';
        $userData = $this->User_model->getUser($ID);
        $data['userData'] = $userData;
        $this->load->view('site/layout/layout.phtml', $data);


        //set validation rules
        $this->form_validation->set_rules('fname', 'First Name', 'trim|required|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('lname', 'Last Name', 'trim|required|min_length[3]|max_length[30]');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.emailUser]');
        
        if ($this->form_validation->run() == true){
            $data = array(
                'firstNameUser' => $this->input->post('fname'),
                'lastNameUser' => $this->input->post('lname'),
                'dateOfBirthUser' => $this->input->post('dob'),
                'genderUser' => $this->input->post('gender'),
                'emailUser' => $this->input->post('email'),
            );

            echo($this->input->post('gender'));

            if ($this->User_model->updateUser($ID,$data))
            {
                    $this->session->set_flashdata('msg','<div class="alert alert-success text-center">Thành công !!</div>');
                    redirect('user_profile');
            }
            else
            {
                // error
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Thất bại, kiểm tra thông tin!!!</div>');
                redirect('user_profile');
            }
        }
    }

    public function change_Password() {
        $data = array();
        $data['content'] = 'site/user/profile/changePassword.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_Banner() {
        $data = array();
        $data['content'] = 'site/user/profile/Rbanner.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_infor() {
        $data = array();
        $data['content'] = 'site/user/profile/Rinfor.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    public function Restaurant_price() {
        $data = array();
        $data['content'] = 'site/user/profile/Rprice.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }
}