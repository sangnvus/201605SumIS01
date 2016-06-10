<?php

class Login extends CI_Controller
{
    // Hàm load form login
    public function load_form()
    {
        // Load view
        $this->load->view('login_view');
    }
}
?>