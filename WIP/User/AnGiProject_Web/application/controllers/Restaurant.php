<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Restaurant extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session'); 
        $this->load->library('DateUtils');
        $this->load->library('Xulychuoi');
        $this->load->library('UriUtils');
        $this->load->model('restaurantModel', '', TRUE);         
        $this->load->model('foodModel', '', TRUE);         
        $this->load->model('bookingModel', '', TRUE);         
        $this->load->model('usersModel', '', TRUE);         
        $this->load->model('categoriesOfRestaurantModel', '', TRUE);         
        $this->load->model('restaurantBannerModel', '', TRUE);         
    }

    public function index() {
        $data = array(
            'user' => $this->session->userdata('user'),
            'level' => $this->session->userdata('level'),
            'content' => 'site/restaurant/index.phtml',
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
            'model' => array()
        );           
        $this->load->view('site/layout/layoutnoslider.phtml', $data);
    }

    public function view($restaurantID=0){ 
        $restaurant = $this->restaurantModel->Details($restaurantID);
        $count = 0;
        $data = array(
            'title' => 'Thông tin quán ăn', 
            'user' => $this->session->userdata('user'), 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'), 
            'content' => 'site/restaurant/view.phtml', 
            'categoryModels' => $this->categoriesOfRestaurantModel->ListByStatus(1),
            'userModel' => array( 
            ),                                        
            'model' => array(
                'restaurant' => $restaurant,    
                'restaurantBanners' => $this->restaurantBannerModel->FindImagePaged(0, 1000, $rows, $restaurant->restaurantID),
                'foods' => $this->foodModel->Admin_FindBy($restaurantID, 0, 1000),
                'relations' => $this->restaurantModel->FindTopRelation(0, 4, $count, $restaurant->categoryOfResID),
                'relationCount' => $count
            )
        );                                                  
        $this->load->view('site/layout/layoutnoslider.phtml', $data);
    }
       
    
    public function sendBooking(){ 
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);
        if ($user == null){
            return $this->returnError('Bạn phải đăng nhập trước khi đặt chỗ');   
        }
        $ok = 1;
        $restaurantID = $this->input->post('restaurantID');
        $quantityMember = $this->input->post('quantityMember');
        $dateBooking = $this->input->post('dateBooking');
        $timeBooking = $this->input->post('timeBooking'); 
        $restaurant = null;
        if ($restaurantID == '' || is_numeric($restaurantID) == FALSE)
            return $this->returnError('Không tìm thấy thông tin quán ăn');
        else {
            $restaurant = $this->restaurantModel->GetById($restaurantID);
            if ($restaurant == null)
                return $this->returnError('Không tìm thấy thông tin quán ăn');
        }
        if ($quantityMember == '')
            return $this->returnError('Chưa điền số người');
        if ($dateBooking == '')
            return $this->returnError('Hãy chọn ngày ăn');
        if ($timeBooking == '')
            return $this->returnError('Hãy chọn giờ');
        
        $dateBooking = $this->dateutils->ConvertToDatetime($dateBooking.' '.$timeBooking, 'Y-m-d H:i'); 
        
        if ($dateBooking < new DateTime('now'))
            return $this->returnError('Ngày và giờ không hợp lệ');
        
        $dataAdd = array(
                        'restaurantID'=>$restaurantID,
                        'dateBooking'=>$this->dateutils->DatetimeToDb($dateBooking),
                        'quantityMember'=>$quantityMember,
                        'userID'=>$user->userID
                    );    
        
        $isExisted = $this->bookingModel->CheckExisted($dataAdd);
        
        if ($isExisted)
            return $this->returnError('Bạn không thể đặt chỗ 2 lần cùng 1 thời điểm với cùng số người là '.$quantityMember);
        //ngày gửi comment
        $dataAdd['dateCreateBo'] = $this->dateutils->DatetimeToDb(new DateTime('now'));
        if (!$this->bookingModel->Create($dataAdd))
            return $this->returnError('Lỗi trong quá trình đặt chỗ');
            
        return $this->returnSuccess('Bạn đặt chõ thành công');
    }
                
    public function returnError($msg){
        echo json_encode(array(
                    'text' => $msg,
                    'type' => 'error'
            )); 
        return true;  
    }
    
    public function returnSuccess($msg){        
        echo json_encode(array(
                    'text' => $msg,
                    'type' => 'success'
            ));       
        return true;   
    }     
}