<?php

defined('BASEPATH') OR exit('No direct script access allow');

class User extends CI_Controller {

     function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'date'));
        $this->load->library(array('session', 'form_validation', 'email'));
        $this->load->database();
        $this->load->model('User_model');
    }

     function index() {
        $ID = 2;
        $data = array();
        $data['content'] = 'site/user/profile/index.phtml';
        $userData = $this->User_model->getUser($ID);
        $data['userData'] = $userData;
        $this->load->view('site/layout/layout.phtml', $data);


        //set validation rules
        $this->form_validation->set_rules('fname', 'First Name', 'trim|required|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('lname', 'Last Name', 'trim|required|min_length[3]|max_length[30]');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.emailUser]');

        if ($this->form_validation->run() == true) {
            $data = array(
                'firstNameUser' => $this->input->post('fname'),
                'lastNameUser' => $this->input->post('lname'),
                'dateOfBirthUser' => $this->input->post('dob'),
                'genderUser' => $this->input->post('gender'),
                'emailUser' => $this->input->post('email'),
            );

            echo($this->input->post('gender'));

            if ($this->User_model->updateUser($ID, $data)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Thành công !!</div>');
                redirect('user');
            } else {
                // error
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Thất bại, kiểm tra thông tin!!!</div>');
                redirect('user');
            }
        }
    }

     function change_Password() {
        $data = array();
        $data['content'] = 'site/user/profile/changePassword.phtml';
        $this->load->view('site/layout/layout.phtml', $data);
    }

    function register() {
        //set validation rules
        $this->form_validation->set_rules('fname', 'First Name', 'trim|required|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('lname', 'Last Name', 'trim|required|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.emailUser]');
        $this->form_validation->set_rules('phone', 'phone', 'trim|numeric');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|md5');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|md5');

        //validate form input
        if ($this->form_validation->run() == FALSE) {
            // fails

            $data ['content'] = 'site/user/user/user_registration.phtml';
            $data ['ward'] = $this->User_model->getData('ward');
            $data ['district'] = $this->User_model->getData('district');
            $data ['province'] = $this->User_model->getData('province');
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            //		insert the user registration details into database
            $address = array(
                'address' => $this->input->post('address'),
                'provinceid' => $this->input->post('province'),
                'districtid' => $this->input->post('district'),
                'wardid' => $this->input->post('ward'),
            );


            if ($this->input->post('autho') == 1) {
                $data = array(
                    'firstNameUser' => $this->input->post('fname'),
                    'lastNameUser' => $this->input->post('lname'),
                    'dateOfBirthUser' => $this->input->post('dob'),
                    'genderUser' => $this->input->post('gender'),
                    'descriptionUser' => $this->input->post('des'),
                    'emailUser' => $this->input->post('email'),
                    'phoneUser' => $this->input->post('phone'),
                    'authorityUser' => $this->input->post('autho'),
                    'passwordUser' => $this->input->post('password'),
                    'dateCreateUser' => date('Y-m-d H:i:s'),
                    'imageID' => 1,
                        // 'addressID'=> $this->User_model->insertAddress($address)
                );
            } else {
                $data = array(
                    'firstNameUser' => $this->input->post('fname'),
                    'lastNameUser' => $this->input->post('lname'),
                    'dateOfBirthUser' => $this->input->post('dob'),
                    'genderUser' => $this->input->post('gender'),
                    'descriptionUser' => $this->input->post('des'),
                    'emailUser' => $this->input->post('email'),
                    'phoneUser' => $this->input->post('phone'),
                    'authorityUser' => $this->input->post('autho'),
                    'passwordUser' => $this->input->post('password'),
                    'dateCreateUser' => date('Y-m-d H:i:s'),
                    'imageID' => 1,
                    'addressID' => $this->User_model->insertAddress($address)
                );
            }

            // insert form data into database
            if ($this->User_model->insertUser($data)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Đăng ký thành công !!</div>');
                redirect('user_registration');
            } else {
                // error
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Đăng ký thất bại, kiểm tra thông tin!!!</div>');
                redirect('user_registration');
            }
        }
    }

    // function verify($hash=NULL)
    // {
    // 	if ($this->user_model->verifyEmailID($hash))
    // 	{
    // 		$this->session->set_flashdata('verify_msg','<div class="alert alert-success text-center">Your Email Address is successfully verified! Please login to access your account!</div>');
    // 		redirect('user_registration/register');
    // 	}
    // 	else
    // 	{
    // 		$this->session->set_flashdata('verify_msg','<div class="alert alert-danger text-center">Sorry! There is error verifying your Email Address!</div>');
    // 		redirect('user_registration/register');
    // 	}
    // }
    
}
