<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Image extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->model('Image_model');
    }

    //index function
    function index() {
        //load file upload form
        $this->load->view('view');
    }
    
    // upload banner function
    function changeBanner() {
        //set preferences
        $config['upload_path'] = './images/restOwner/banner';
        $config['allowed_types'] = 'jpg|png|gif';
        // <input type="file" name="filename" >
        $imageName = time() . $_FILES["filename"]['name'];
        $config['file_name'] = $imageName;
//        $config['max_size'] = '100';
        //load upload class library
        $this->load->library('upload', $config);
        // <input type="file" name="filename" >
        if (!$this->upload->do_upload('filename')) {
            // case - failure
            $upload_error = array('error' => $this->upload->display_errors());
            $data['upload_error'] = $upload_error;
            $data['content'] = 'site/user/restaurant_owner/Rbanner.phtml';
            $this->load->view('site/layout/layout.phtml', $data);
        } else {
            // case - success
            $upload_data = $this->upload->data();

            $descriptionImage = $this->input->post('bannerDesc');

            // save image descrition to database
            $data['nameImage'] = $imageName;
            $data['descriptionImage'] = $descriptionImage;
            $data['typeImage'] = 2; // 0 avartar, 1 restaurant image, 2 banner
            $data['addressImage'] = base_url('images/restOwner/banner' . $imageName);

            $image = $this->Image_model->insertBannerImage($data);

            // if image information is inserted to the database
            if ($image) {
                // load view
                $banner = $this->Image_model->getBanner();
                $data['banner'] = $banner;
                $data['success_msg'] = '<div class="alert alert-success text-center">Your file <strong>' . $upload_data['file_name'] . '</strong> was successfully uploaded!</div>';
                $data['content'] = 'site/user/restaurant_owner/Rbanner.phtml';
                $this->load->view('site/layout/layout.phtml', $data);
            } else {
                echo 'cannot insert image to database!';
            }
        }
    }

}
