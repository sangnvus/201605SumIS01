<?php

defined('BASEPATH') OR exit('No direct script access allow');

class Image extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library('session');
        $this->load->model('Image_model');
        $this->load->database();
        // typeImage: 0 customer avatar, 1 restaurant avatar, 2 banner, 3 food
        // authorityUser: 1 customer, 2 restaurant owner
    }

    //index function
    function index() {
        //load file upload form
        $this->load->view('view');
    }

    // upload avatar function
    function changeAvatar($type = 'avatarFile') {
        
        $ID = $this->session->userdata("ID");
        $userType = $this->session->userdata('Type');
        
        // user types: 1 customer, 2 restaurant owner
        // image types: 0 avatar, 1 restaurant image, 2 banner
        //set preferences
        $config['upload_path'] = 'images/avatar';
        $config['allowed_types'] = 'jpg|png|gif';
        // <input type="file" name="filename" >
        $imageName = time() . $_FILES[$type]['name'];
        $config['file_name'] = $imageName;
        // $config['max_size'] = '100';

        $imageType = 0; // customer avatar
        if ($type == 'restImgFile') { // restaurant owner
            //set preferences
            $config['upload_path'] = 'images/restOwner/restImage';
            $imageType = 1; // restaurant image
        }

        //load upload class library 
        $this->load->library('upload', $config);

        // <input type="file" name="filename" >
        if (!$this->upload->do_upload($type)) {
            // case - failure
            $upload_error = array('error' => $this->upload->display_errors());
            $data['upload_avatar_error'] = $upload_error;
        } else {
            // case - success
            $upload_data = $this->upload->data();

            // save image information to database
            $data['userID'] = $ID;
            $data['nameImage'] = $imageName;
            $data['descriptionImage'] = "restaurant avatar image";
            $data['typeImage'] = $imageType; // 0 avartar, 1 restaurant image, 2 banner
            $data['addressImage'] = $config['upload_path'] . '/' . $imageName;

            // delete avatar file in server folder
            $image_addr = $this->Image_model->getAvatar($userType, $ID, $imageType);

            foreach ($image_addr as $row) {
                unlink($row->addressImage);
            }

            // delete avatar path in database
            $this->Image_model->deleteAvatar($userType, $ID, $imageType);

            $this->Image_model->insertAvatar($data);
            if ($type == 'restImgFile') {
                redirect('restaurant/Restaurant_infor');
            }
            redirect('user');
        }
    }

    // upload banner function
    function changeBanner() {
        $ID = $this->session->userdata("ID");
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
            $this->session->set_flashdata('upload_error', '<div class="alert alert-danger">' . $upload_error . '</div>');
            redirect('restaurant/Restaurant_Banner');
        } else {
            // case - success
            $upload_data = $this->upload->data();

            $descriptionImage = $this->input->post('bannerDesc');

            // save image descrition to database
            $data['userID'] = $ID;
            $data['nameImage'] = $imageName;
            $data['descriptionImage'] = $descriptionImage;
            $data['typeImage'] = 2; // 0 avartar, 1 restaurant image, 2 banner
            $data['addressImage'] = 'images/restOwner/banner/' . $imageName;

            // delete banner file in server folder
            $preImage = $this->input->post('preImgAddr');
            $image_addr = $this->Image_model->getBanner($ID);
            foreach ($image_addr as $row) {
                if ($row->addressImage == $preImage) {
                    unlink($preImage);
                }
            }

            // delete banner path in database
            $preImgID = $this->input->post("preImgID");
            if ($preImgID != null) {
                $this->Image_model->deleteBanner($preImgID);
            }

            $this->Image_model->insertBannerImage($data);
            redirect('restaurant/Restaurant_Banner');
        }
    }

}
