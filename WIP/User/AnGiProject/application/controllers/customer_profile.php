<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Customer_profile extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->helper(array('form','url','date'));
        $this->load->library(array('session', 'form_validation', 'email'));
        $this->load->database();
        $this->load->model('User_model');
    }
    
    function index(){
        $this->profile();
    }


    function profile(){
        $ID =  3;
        $data['content'] = 'site/user/profile/customer_profile.phtml';
        $userData = $this->User_model->getUser($ID);
        $data['userData'] = $userData;
        $this->load->view('site/layout/layout.phtml', $data);

        //set validation rules
        $this->form_validation->set_rules('fname', 'First Name', 'trim|required|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('lname', 'Last Name', 'trim|required|min_length[3]|max_length[30]');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.emailUser]');
        $this->form_validation->set_rules('phone', 'phone', 'trim|numeric');
        
        if ($this->form_validation->run() == true){
            $data = array(
                'firstNameUser' => $this->input->post('fname'),
                'lastNameUser' => $this->input->post('lname'),
                'dateOfBirthUser' => $this->input->post('dob'),
                'genderUser' => $this->input->post('gender'),
                'descriptionUser' => $this->input->post('des'),
                'emailUser' => $this->input->post('email'),
                'phoneUser' => $this->input->post('phone')
            );
            print_r($data);

            if ($this->User_model->updateUser($ID,$data))
            {
                    $this->session->set_flashdata('msg','<div class="alert alert-success text-center">Thành công !!</div>');
                    redirect('customer_profile');
            }
            else
            {
                // error
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Thất bại, kiểm tra thông tin!!!</div>');
                redirect('customer_profile');
            }
        }
    }
}