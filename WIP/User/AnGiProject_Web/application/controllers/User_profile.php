<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class user_profile extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');      
        $this->load->library('pagination');
        $this->load->library('DateUtils');
        $this->load->library('UriUtils');
        $this->load->model('usersModel', '', TRUE);
        $this->load->model('addressModel', '', TRUE);
        $this->load->model('imageModel', '', TRUE);
        $this->load->model('categoriesOfRestaurantModel', '', TRUE);
        $this->load->model('bookingModel', '', TRUE);
        $this->load->model('foodModel', '', TRUE);
        $this->load->model('restaurantModel', '', TRUE);
        $this->load->model('imageModel', '', TRUE);
        $this->load->model('restaurantBannerModel', '', TRUE);
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        if(!isset($user) || $user == '')
        {
            redirect(base_url().'/home');
        }              
    }

    function getConfig(){
        return array(
            'per_page' => 20,
            'full_tag_open' => "<ul class='pagination'>",
            'full_tag_close' => "</ul>",
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
            'cur_tag_open' => "<li class='disabled'><li class='active'><a href='#'>",
            'cur_tag_close' => "<span class='sr-only'></span></a></li>",
            'next_tag_open' => "<li>",
            'next_tagl_close' => "</li>",
            'prev_tag_open' => "<li>",
            'prev_tagl_close' => "</li>",
            'first_tag_open' => "<li>",
            'first_tagl_close' => "</li>",
            'last_tag_open' => "<li>",
            'last_tagl_close' => "</li>"
        );       
    }  
    
    public function index() {              
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);
        $data = array(
            'title' => 'Thông tin người dùng', 
            'user' => $userName, 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'), 
            'content' => 'site/user/profile/index.phtml',
            'userModel' => array(
                'userID' => $user-> userID,       
                'memName' => $user-> memName,    
                'addressImage' => $user-> addressImage, 
            ),                                        
            'model' => array(
            )
        );     
        $model = array( 
                'error' => '',   
                'userID' => $user-> userID,       
                'memName' => $user-> memName,
                'userMail' => $user-> userMail,
                'memGender' => $user-> memGender,
                'memBirthDay' => $this->dateutils->FormatVnDatetimeFromDb($user->memBirthDay)
        );
        
        if ($this->input->post('submit')){
            $model['memName'] = $memName = strip_tags($this->input->post('memName'));
            $model['userMail'] = $userMail = strip_tags($this->input->post('userMail'));
            $model['memGender'] = strip_tags($this->input->post('memGender'));
            $model['memBirthDay'] = strip_tags($this->input->post('memBirthDay'));   
            
            $ok = true;
            $error='';
            if ($memName == ''){
                $ok = false;
                $error .= $this->Error('Chưa nhập tên');
            }  
            if ($userMail == '')
            {
                $ok = false;
                $error .= $this->Error('Chưa nhập email');
            }  
            
            if (!$ok){
                $model['error'] = $error;
            }
            else {
                $dataEdit = array(
                    'user'    => array(
                        'userMail' => $model['userMail']
                    ),
                    'mem'    => array(
                        'memName' => $model['memName'],
                        'memGender' => $model['memGender'],
                        'memBirthDay' => $this->dateutils->VnStrDatetimeToDb($model['memBirthDay'], 'd/m/Y')
                    )
                );
                if ($this->usersModel->Update($user-> userID, $dataEdit)){ 
                    $model['error'] = $this->Error('Cập nhật thông tin thành công');
                }
                else{
                    $model['error'] = $this->Error('Có lỗi trong quá trình đổi mật khẩu');
                } 
            }
        } 
        
        $data['model'] = $model;
        
        $this->load->view('site/layout/layoutprofile.phtml', $data); 
    }

    public function restaurant_add() {
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);

        $data = array(                                                 
            'title' => 'Thông tin nhà hàng',  
            'userModel' => array(
                'userID' => $user-> userID,       
                'memName' => $user-> memName,    
                'addressImage' => $user-> addressImage, 
            ),  
            'content' => 'site/user/restaurant/edit.phtml',   
            'user' => $this->session->userdata('user'), 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level') 
            );      
        $model = array(
             'error' => '',                        
             'province' => $this->addressModel->ListAllProvince(),
             'categories' => $this->categoriesOfRestaurantModel->ListByStatus(1),    
             'foods' => $this->foodModel->Admin_FindBy(0, 0, 1000)
        );
        
        $submit = $this->input->post('submit');
        if ($submit){
            $model['provinceID'] = strip_tags($this->input->post('provinceID'));
            $model['districtID'] = strip_tags($this->input->post('districtID'));
            $model['wardID'] = strip_tags($this->input->post('wardID'));          
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['restaurantID'] = strip_tags($this->input->post('restaurantID')); 
            $model['nameRe'] = strip_tags($this->input->post('nameRe'));
            $model['descriptionRes'] = $this->input->post('descriptionRes');
            $model['phoneRe'] = strip_tags($this->input->post('phoneRe'));   
            $model['favouriteFood'] = strip_tags($this->input->post('favouriteFood'));
            $model['spaceRes'] = strip_tags($this->input->post('spaceRes'));
            $model['carParkingRes'] = strip_tags($this->input->post('carParkingRes'));
            $model['otherPoints'] = strip_tags($this->input->post('otherPoints'));
            $model['openTimeRe'] = strip_tags($this->input->post('openTimeRe'));
            $model['closeTimeRe'] = strip_tags($this->input->post('closeTimeRe'));
            $model['latitudeRe'] = strip_tags($this->input->post('latitudeRe'));
            $model['longitudeRe'] = strip_tags($this->input->post('longitudeRe'));
            $model['rateRe'] = strip_tags($this->input->post('rateRe'));
            $model['minPrice'] = strip_tags($this->input->post('minPrice'));
            $model['maxPrice'] = strip_tags($this->input->post('maxPrice'));
            $model['discount'] = strip_tags($this->input->post('discount'));
            $model['quantityBooking'] = strip_tags($this->input->post('quantityBooking'));
            $model['dateCreateRe'] = strip_tags($this->input->post('dateCreateRe'));
            $model['addressID'] = strip_tags($this->input->post('addressID')); 
            $model['userID'] = strip_tags($this->input->post('userID'));
            $model['isDepositBo'] = strip_tags($this->input->post('isDepositBo'));
            $model['isDeactivate'] = strip_tags($this->input->post('isDeactivate'));
            $model['statusRes'] = strip_tags($this->input->post('statusRes'));
                
            $model['address'] = strip_tags($this->input->post('address'));
            
            $model['categoryOfResID'] = strip_tags($this->input->post('categoryOfResID')); 
        }
                      
        if ($submit){
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = $this->validateRestaurant($model, $error);
            if (!$ok)   {
                $model['error'] = $error;                                                           
            }
            else
            {
                //Tao mang chua thong tin ve user
                $dataAdd = array(
                    'restaurant' => array(
                        'restaurantID' => $model['restaurantID'], 
                        'nameRe' => $model['nameRe'],
                        'descriptionRes' => $model['descriptionRes'],
                        'phoneRe' => $model['phoneRe'],   
                        'favouriteFood' => $model['favouriteFood'],
                        'spaceRes' => $model['spaceRes'],
                        'carParkingRes' => $model['carParkingRes'],
                        'otherPoints' => $model['otherPoints'],
                        'openTimeRe' => $model['openTimeRe'],
                        'closeTimeRe' => $model['closeTimeRe'],
                        'latitudeRe' => $model['latitudeRe'],
                        'longitudeRe' => $model['longitudeRe'],
                        'rateRe' => $model['rateRe'],
                        'minPrice' => $model['minPrice'],
                        'maxPrice' => $model['maxPrice'],
                        'discount' => $model['discount'],
                        'quantityBooking' => $model['quantityBooking'],
                        'dateCreateRe' => $model['dateCreateRe'],
                        'addressID' => $model['addressID'],
                        'userID' => $model['userID'],
                        'isDepositBo' => $model['isDepositBo'],
                        'isDeactivate' => $model['isDeactivate'],
                        'statusRes' => $model['statusRes']
                        ),  
                    'cate' => array(
                        'categoryOfResID' => $model['categoryOfResID'], 
                        'restaurantID' => 0
                        ),
                    'add' => array(
                        'address' => $model['address'],
                        'provinceID' => $model['provinceID'],
                        'districtID' => $model['districtID'],
                        'wardID' => $model['wardID'],
                        'statusAdd' => 1
                    ));
                if ($this->restaurantModel->Create($dataAdd))
                {                         
                    //Neu luu thanh cong  
                    $model['error'] = $this->Error('Cập nhật thành công!'); 
                    $model['ok']  = 1;
                }
                else
                {
                    //Nguoc lai neu khong luu duoc 
                    $model['error'] = $this->Error('Không cập nhật được nhà hàng!'); 
                    $model['district'] = $this->addressModel->FindDistrictByProvinceId($provinceID);
                    $model['ward'] = $this->addressModel->FindWardByProvinceId($districtID); 
                }  
            }
        }
          
        $data['model'] = $model;  
        
        $this->load->view('site/layout/layoutprofile.phtml', $data); 
    }

    public function restaurant_edit() {   
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);    
        $userId = $user->userID;
                    
        $restaurant = $this->restaurantModel->GetFullByUserId($userId);  
        if ($restaurant == null)
            //print_r(base_url()."user_profile/restaurant_add");exit();
            return redirect(base_url()."user_profile/restaurant_add");
            
        $restaurantID = $restaurant->restaurantID;         
        $data = array(                                                 
            'title' => 'Cập nhật nhà hàng',
            'content' => 'site/user/restaurant/edit.phtml',   
            'user' => $this->session->userdata('user'), 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'), 
            'userModel' => array(
                'userID' => $user-> userID,       
                'memName' => $user-> memName,    
                'addressImage' => $user-> addressImage, 
            ), 
        );      
        $model = array(
             'error' => '',                        
             'province' => $this->addressModel->ListAllProvince(),
             'categories' => $this->categoriesOfRestaurantModel->ListByStatus(1),    
             'foods' => $this->foodModel->Admin_FindBy($restaurantID, 0, 1000)
        );
        
        $submit = $this->input->post('submit');
        if (!$submit){
            $model['provinceID'] = $restaurant->provinceID;
            $model['districtID'] =  $restaurant->districtID;
            $model['wardID'] =  $restaurant->wardID;
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['restaurantID'] = $restaurant->restaurantID; 
            $model['nameRe'] = $restaurant->nameRe;
            $model['descriptionRes'] = $restaurant->descriptionRes;
            $model['phoneRe'] = $restaurant->phoneRe;   
            $model['favouriteFood'] = $restaurant->favouriteFood;
            $model['spaceRes'] = $restaurant->spaceRes;
            $model['carParkingRes'] = $restaurant->carParkingRes;
            $model['otherPoints'] = $restaurant->otherPoints;
            $model['openTimeRe'] = $restaurant->openTimeRe;
            $model['closeTimeRe'] = $restaurant->closeTimeRe;
            $model['latitudeRe'] = $restaurant->latitudeRe;
            $model['longitudeRe'] = $restaurant->longitudeRe;
            $model['rateRe'] = $restaurant->rateRe;
            $model['minPrice'] = $restaurant->minPrice;
            $model['maxPrice'] = $restaurant->maxPrice;
            $model['discount'] = $restaurant->discount;
            $model['quantityBooking'] = $restaurant->quantityBooking;
            $model['dateCreateRe'] = $restaurant->dateCreateRe;
            $model['addressID'] = $restaurant->addressID;
            $model['address'] = $restaurant->address;
            $model['userID'] = $restaurant->userID;
            $model['isDepositBo'] = $restaurant->isDepositBo;
            $model['isDeactivate'] = $restaurant->isDeactivate;
            $model['statusRes'] = $restaurant->statusRes;                           
            $model['categoryOfResID'] = $restaurant->categoryOfResID;  
        }                                                                             
        else {
            $model['provinceID'] = strip_tags($this->input->post('provinceID'));
            $model['districtID'] = strip_tags($this->input->post('districtID'));
            $model['wardID'] = strip_tags($this->input->post('wardID'));          
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['restaurantID'] = strip_tags($this->input->post('restaurantID')); 
            $model['nameRe'] = strip_tags($this->input->post('nameRe'));
            $model['descriptionRes'] = $this->input->post('descriptionRes');
            $model['phoneRe'] = strip_tags($this->input->post('phoneRe'));   
            $model['favouriteFood'] = strip_tags($this->input->post('favouriteFood'));
            $model['spaceRes'] = strip_tags($this->input->post('spaceRes'));
            $model['carParkingRes'] = strip_tags($this->input->post('carParkingRes'));
            $model['otherPoints'] = strip_tags($this->input->post('otherPoints'));
            $model['openTimeRe'] = strip_tags($this->input->post('openTimeRe'));
            $model['closeTimeRe'] = strip_tags($this->input->post('closeTimeRe'));
            $model['latitudeRe'] = strip_tags($this->input->post('latitudeRe'));
            $model['longitudeRe'] = strip_tags($this->input->post('longitudeRe'));
            $model['rateRe'] = strip_tags($this->input->post('rateRe'));
            $model['minPrice'] = strip_tags($this->input->post('minPrice'));
            $model['maxPrice'] = strip_tags($this->input->post('maxPrice'));
            $model['discount'] = strip_tags($this->input->post('discount'));
            $model['quantityBooking'] = strip_tags($this->input->post('quantityBooking'));
            $model['dateCreateRe'] = strip_tags($this->input->post('dateCreateRe'));
            $model['addressID'] = strip_tags($this->input->post('addressID'));
            $model['address'] = strip_tags($this->input->post('address'));
            $model['userID'] = strip_tags($this->input->post('userID'));
            $model['isDepositBo'] = strip_tags($this->input->post('isDepositBo'));
            $model['isDeactivate'] = strip_tags($this->input->post('isDeactivate'));
            $model['statusRes'] = strip_tags($this->input->post('statusRes'));
                
            $model['categoryOfResID'] = strip_tags($this->input->post('categoryOfResID')); 
        }
                      
        if ($submit){
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = $this->validateRestaurant($model, $error);
            if (!$ok)   {
                $model['error'] = $error;                                                           
            }
            else
            {
                //Tao mang chua thong tin ve user
                $dataEdit = array(
                    'restaurant' => array(
                        'restaurantID' => $model['restaurantID'], 
                        'nameRe' => $model['nameRe'],
                        'descriptionRes' => $model['descriptionRes'],
                        'phoneRe' => $model['phoneRe'],   
                        'favouriteFood' => $model['favouriteFood'],
                        'spaceRes' => $model['spaceRes'],
                        'carParkingRes' => $model['carParkingRes'],
                        'otherPoints' => $model['otherPoints'],
                        'openTimeRe' => $model['openTimeRe'],
                        'closeTimeRe' => $model['closeTimeRe'],
                        'latitudeRe' => $model['latitudeRe'],
                        'longitudeRe' => $model['longitudeRe'],
                        'rateRe' => $model['rateRe'],
                        'minPrice' => $model['minPrice'],
                        'maxPrice' => $model['maxPrice'],
                        'discount' => $model['discount'],
                        'quantityBooking' => $model['quantityBooking'],
                        'dateCreateRe' => $model['dateCreateRe'],
                        'addressID' => $model['addressID'],
                        'userID' => $model['userID']
                        ),  
                    'cate' => array(
                        'categoryOfResID' => $model['categoryOfResID'], 
                        'restaurantID' => $model['restaurantID']
                        ),
                    'add' => array(
                        'address' => $model['address'],
                        'provinceID' => $model['provinceID'],
                        'districtID' => $model['districtID'],
                        'wardID' => $model['wardID'],
                        'statusAdd' => 1
                    )
                );
                
                if ($this->restaurantModel->Update($restaurantID, $dataEdit))
                {    
                    //Neu luu thanh cong      
                    $model['error'] = $this->Error('Cập nhật thành công!');    
                }
                else
                {
                    //Nguoc lai neu khong luu duoc 
                    $model['error'] = $this->Error('Không cập nhật được nhà hàng!'); 
                }  
            }
        }
        
        $model['district'] = $this->addressModel->FindDistrictByProvinceId($model['provinceID']);
        $model['ward'] = $this->addressModel->FindWardByProvinceId($model['districtID']); 
        $data['model'] = $model;  
        $this->load->view('site/layout/layoutprofile.phtml', $data);       
    }
    
    public function add_food() { 
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $model = array();
        $image = array();
                                                                     
        $model['nameFo'] = strip_tags($this->input->post('nameFo'));
        $model['imageID'] = strip_tags($this->input->post('imageID'));      
        $model['desciptionFo'] = strip_tags($this->input->post('desciptionFo'));
        $model['priceFo'] = strip_tags($this->input->post('priceFo'));   
        $model['typeFo'] = strip_tags($this->input->post('typeFo'));
        $model['restaurantID'] = strip_tags($this->input->post('restaurantID'));
        $model['statusFo'] = strip_tags($this->input->post('statusFo'));
                                 
        $image['addressImage'] = strip_tags($this->input->post('addressImage'));
        //kiem tra du lieu
        $error = '';
        $ok = 1;
        if ($model['nameFo'] == '') 
        {
            $error .= 'Chưa nhập tên <br />';
            $ok = 0;
        }
        if ($model['desciptionFo'] == '') 
        {
            $error .= 'Chưa nhập mô tả <br />';
            $ok = 0;
        }
        if ($model['priceFo'] == '') 
        {
            $error .= 'Chưa nhập giá <br />';
            $ok = 0;
        }
        if ($model['typeFo'] == '') 
        {
            $error .= 'Chưa chọn loại <br />';
            $ok = 0;
        }
        if ($ok == 1)
        {   
            if ($this->foodModel->Create($model, $image))
            {
                //Neu luu thanh cong
                echo $this->returnSuccess('Thêm món ăn thành công');
                return;
            }
            else
            {
                //Nguoc lai neu khong luu duoc                                     
                echo $this->returnError('Không thêm được món ăn');  
                return;
            }
        }
        else{
            echo $this->returnError($error);
            return;
        }
    }

    public function info_food($id) {
        $food = $this->foodModel->Admin_GetDetail($id);
        $image = $this->imageModel->Admin_GetById($food->imageID);
        if($image != null)
            $food->imageIDSrc = $image->addressImage;
        else
            $food->imageIDSrc = '';
        echo $this->returnSuccess(json_encode($food));
    }
           
    public function delete_food($id) {
        $food = $this->foodModel->Delete($id);
        echo $this->returnSuccess('Xóa thành công');
    }
      
    public function edit_food($id) {
        $model = array();                                            
        $image = array();       
                                             
        $model['nameFo'] = strip_tags($this->input->post('nameFo'));
        $model['imageID'] = strip_tags($this->input->post('imageID'));
        $model['desciptionFo'] = strip_tags($this->input->post('desciptionFo'));
        $model['priceFo'] = strip_tags($this->input->post('priceFo'));   
        $model['typeFo'] = strip_tags($this->input->post('typeFo'));
        $model['restaurantID'] = strip_tags($this->input->post('restaurantID'));
        $model['statusFo'] = strip_tags($this->input->post('statusFo')); 
                                 
        $image['addressImage'] = strip_tags($this->input->post('addressImage'));
        if ($image['addressImage'] == ''){
            $image = null;  //khong thực hiện update
        }
        //kiem tra du lieu
        $error = '';
        $ok = 1;
        if ($model['nameFo'] == '') 
        {
            $error .= 'Chưa nhập tên <br />';
            $ok = 0;
        }
        if ($model['desciptionFo'] == '') 
        {
            $error .= 'Chưa nhập mô tả <br />';
            $ok = 0;
        }
        if ($model['priceFo'] == '') 
        {
            $error .= 'Chưa nhập giá <br />';
            $ok = 0;
        }
        if ($model['typeFo'] == '') 
        {
            $error .= 'Chưa chọn loại <br />';
            $ok = 0;
        }         
        if ($ok == 1)
        {   
            if ($this->foodModel->Update($id, $model, $image))
            {
                //Neu luu thanh cong
                echo $this->returnSuccess('Cập nhật món ăn thành công');
                return;
            }
            else
            {
                //Nguoc lai neu khong luu duoc                                     
                echo $this->returnError('Không cập nhật được món ăn');  
                return;
            }
        }
        else{
            echo $this->returnError($error);
            return;
        }
    }
       
    public function addoredit_food($id=0) {               
        if (isset($id) && $id > 0)
            $this->edit_food($id);
        else
            $this->add_food();
    }
     
    function validateRestaurant($data, & $error){
        $ok = true;
        if($data['categoryOfResID'] == '')
        {
            $error .= $this->Error('Chưa chọn loại cửa hàng');
            $ok = false;
        }  
        if($data['nameRe'] == '')
        {
            $error .= $this->Error('Chưa nhập tên');
            $ok = false;
        }   
        if($data['phoneRe'] == '')
        {
            $error .= $this->Error('Chưa nhập điện thoại');
            $ok = false;
        }    
        if($data['descriptionRes'] == '')
        {
            $error .= $this->Error('Chưa nhập mô tả');
            $ok = false;
        }   
        if($data['openTimeRe'] == '')
        {
            $error .= $this->Error('Chưa chọn giờ mở của');
            $ok = false;
        }  
        if($data['closeTimeRe'] == '')
        {
            $error .= $this->Error('Chưa chọn giờ đóng cửa');
            $ok = false;
        }    
        if($data['address'] == '' && $data['otherPoints'] == '')    
        {
            $error .= $this->Error('Hãy chọn địa chỉ');
            $ok = false;
        }    
        return $ok;
    }
    
    public function change_Password() {
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);
        $data = array(
            'user' => $userName, 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'), 
            'content' => 'site/user/profile/changePassword.phtml',
            'userModel' => array(
                'userID' => $user-> userID,       
                'memName' => $user-> memName,    
                'addressImage' => $user-> addressImage, 
            ),                                        
            
        ); 
        $model = array( 
                'error' => ''
        );
        if ($this->input->post('submit')){
            $model['passwordOld'] = $userPassOld = strip_tags($this->input->post('passwordOld'));
            $userPass = strip_tags($this->input->post('password'));
            $userPassRe = strip_tags($this->input->post('passwordRe'));
            $ok = true;
            $error='';
            if ($userPassOld == ''){
                $ok = false;
                $error .= $this->Error('Chưa nhập mật khẩu cũ để kiểm tra');
            }
            else{
                $userPassOld = md5($userPassOld);
                if ($userPassOld != $user-> userPass){
                    $ok = false;
                    $error = $this->Error('Mật khẩu cũ không khớp');
                }
            } 
            if ($userPass == '')
            {
                $ok = false;
                $error .= $this->Error('Chưa nhập mật khẩu');
            }
            else {
                if ($userPass != $userPassRe)
                {
                    $ok = false;
                    $error .= $this->Error('Mật khẩu nhập lại không khớp');  
                }
                else{
                    $userPass = $userPassRe = md5($userPass);
                }
            }
            
            if (!$ok){
                $model['error'] = $error;
            }
            else {
                $dataEdit = array(
                    'user'    => array(
                        'userPass' => $userPass,
                        'userPassRe' => $userPassRe
                    )
                );
                if ($this->usersModel->Update($user-> userID, $dataEdit)){ 
                    $this->session->sess_destroy();
                    redirect(base_url().'home');
                }
                else{
                    $model['error'] = $this->Error('Có lỗi trong quá trình đổi mật khẩu');
                } 
            }
        } 
        
        $data['model'] = $model;
        
        $this->load->view('site/layout/layoutprofile.phtml', $data);
    }

    public function changeAvatar() {
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);
        
        $image = $this->imageModel->GetById($user->imageID);    
        $ok = 1;
        $imageModel = array('addressImage' => $this->input->post('addressImage'));
        if ($image == null){  
            $imageId = $this->imageModel->Create($imageModel);  
            if ($imageId > 0){
                //cap nhat thong tin cho user
                $ok = $this->usersModel->UpdateMemberships($user-> memID, array('imageID' => $imageId));
            }
        }
        else {
            $ok = $this->imageModel->Update($user->imageID, $imageModel);
        }
        
        if ($ok == 0){
            echo $this->returnError('Không cập nhật được ảnh đại diện');  
            return;
        }
        echo $this->returnSuccess('Cập nhật thành công');
    }

    public function Restaurant_Banner($offset=0) {
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName); 
        $restaurant = $this->restaurantModel->GetByUserId($user-> userID);
            
        $rows = 0;  
        $config = $this->getConfig(); 
        $items = $this->restaurantBannerModel->FindImagePaged($offset, $config['per_page'], $rows, $restaurant->restaurantID);
   
        $config['base_url'] = base_url().'/user_profile/restaurant_banner';
        $config['total_rows'] = $rows;  
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
          
        $data = array(
            'user' => $userName, 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'), 
            'content' => 'site/user/restaurant/banner.phtml',
            'userModel' => array(
                'userID' => $user-> userID,       
                'memName' => $user-> memName,    
                'addressImage' => $user-> addressImage, 
            ),                                        
            'model' => array(
                'error' => '',
                'items' => $items,
                'pagination' => $pagination
            )
        );    
                 
        $this->load->view('site/layout/layoutprofile.phtml', $data); 
    }
    
    public function delete_restaurant_banner($id) {
        $food = $this->restaurantBannerModel->Delete($id);
        echo $this->returnSuccess('Xóa thành công');
    }
    
    public function add_restaurant_banner() { 
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName); 
        $restaurant = $this->restaurantModel->GetByUserId($user-> userID);
            
                                                                   
        $model['imageUrl'] = urldecode($this->input->post('imageUrl'));
        $model['imageMain'] = $this->input->post('imageMain');   
        $model['restaurantId'] = $restaurant->restaurantID;   
        //kiem tra du lieu 
        if ($this->restaurantBannerModel->Create($model))
        {
            //Neu luu thanh cong
            echo $this->returnSuccess('Thêm món ăn thành công');
            return;
        }
        else
        {
            //Nguoc lai neu khong luu duoc                                     
            echo $this->returnError('Không thêm được món ăn');  
            return;
        }
    }
   
    public function restaurant_manage_booking($offset=0){                
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);
        $restaurant = $this->restaurantModel->GetByUserId($user-> userID);
        $data = array(
            'title' => 'Danh sách đặt chỗ',
            'user' => $userName, 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'), 
            'content' => 'site/user/restaurant/restaurant_manage_booking.phtml',
            'userModel' => array(
                'userID' => $user-> userID,       
                'memName' => $user-> memName,    
                'addressImage' => $user-> addressImage, 
            ),                                        
            'model' => array(
            )
        );     
        $model = array( 
                'error' => '',
        );
            
        $data['model'] = $model;
        $offset = intval($offset);
        
        $user = $this->session->userdata('user');  
                                
        $rows = 0;   
        $config = $this->getConfig();                                                 
        $result = $this->bookingModel->Admin_FindBy($restaurant->restaurantID, null, $offset, $config['per_page'], $rows); 
           
        $config['base_url'] = base_url().'/user_profile/restaurant_manage_booking/';
        $config['total_rows'] = $rows;  
         
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();    
        $model['result'] = $result;
        $model['rows'] = $rows;
        $model['pagination'] = $pagination;      
                                                      
        $data['model'] = $model;
        
        $this->load->view('site/layout/layoutprofile.phtml', $data);  
    }
     
    public function user_manage_booking($offset=0){                
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);
        $restaurant = $this->restaurantModel->GetByUserId($user-> userID);
        $data = array(
            'title' => 'Lịch sử đặt chỗ',
            'user' => $userName, 
            'fullname' => $this->session->userdata('fullname'), 
            'level' => $this->session->userdata('level'), 
            'content' => 'site/user/user/user_manage_booking.phtml',
            'userModel' => array(
                'userID' => $user-> userID,       
                'memName' => $user-> memName,    
                'addressImage' => $user-> addressImage, 
            ),                                        
            'model' => array(
            )
        );     
        $model = array( 
                'error' => '',
        );
                                    
        $rows = 0;      
        $config = $this->getConfig();                                              
        $result = $this->bookingModel->Admin_FindBy(null, $user-> userID, $offset, $config['per_page'], $rows); 
                                  
        $config['base_url'] = base_url().'/user_profile/user_manage_booking/';
        $config['total_rows'] = $rows;    
         
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();    
        $model['result'] = $result;
        $model['rows'] = $rows;
        $model['pagination'] = $pagination;      
                                                      
        $data['model'] = $model;
        
        $this->load->view('site/layout/layoutprofile.phtml', $data);   
    }
    
    public function edit_booking($id=0) {
        $this->check_id($id, 'user_profile/restaurant_manage_booking');                   
                                                         
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);
                                       
        $bookingModel = $this->bookingModel->Admin_GetDetail($id);
        if ($bookingModel == null)
            return redirect(base_url("user_profile/restaurant_manage_booking")); 
                  
        $data = array(                                                 
                'title' => 'Chi tiết đặt chỗ',
                'content' => 'site/user/restaurant/edit_booking.phtml',
                'fullname' => $this->session->userdata('fullname'), 
                'level' => $this->session->userdata('level'), 
                'user' => array('user' => $this->session->userdata('user')),
                'fullname' => $this->session->userdata('fullname'), 
                'userModel' => array(
                    'userID' => $user-> userID,       
                    'memName' => $user-> memName,    
                    'addressImage' => $user-> addressImage, 
                ), 
                );      
        $model = array(
             'error' => ''         
        );
              
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $model['bookingID'] = $bookingModel->bookingID; 
        $model['dateCreateBo'] = $this->dateutils->FormatVnDatetimeFromDb($bookingModel->dateCreateBo);
        $model['dateBooking'] = $this->dateutils->FormatVnDatetimeFromDb($bookingModel->dateBooking, 'H:i d/m/Y');  
        $model['quantityMember'] = $bookingModel->quantityMember; 
        $model['commentBo'] = $bookingModel->commentBo;
        $model['restaurantName'] = $bookingModel->restaurantName;  
        $model['userName'] = $bookingModel->userName;           
        $model['statusBo'] = $bookingModel->statusBo;  
        
        $data['model']  = $model;
        if($this->input->post('submit'))
        {                           
            //Lay du lieu tu forn dong thoi gan bien du gia tri                                 
            $model['statusBo'] = $statusBo = strip_tags($this->input->post('statusBo'));                
            $model['commentBo'] = $commentBo = strip_tags($this->input->post('commentBo')); 
            
            $data['model']  = $model;
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = 1;   
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataEdit = array(
                                'statusBo'     =>  $statusBo,
                                'commentBo'     =>  $commentBo
                                );       
                if ($this->bookingModel->Update($id, $dataEdit))
                {
                    //Neu luu thanh cong                   
                    $model['error'] = $this->Error('Cập nhật thành công!'); 
                }
                else
                {
                    //Nguoc lai neu khong luu duoc    
                    $model['error'] = $this->Error('Không cập nhật được booking!');   
                }
            } 
            else
            {
                $model['error'] = $error;  
            }           
        }
            
        $data['model'] = $model;
        
        $this->load->view('site/layout/layoutprofile.phtml', $data); 
    }

    public function info_booking($id=0) {
        $this->check_id($id, 'user_profile/restaurant_manage_booking');                   
                                                         
        $userName = $this->session->userdata('user');
        $user = $this->usersModel->GetUserByNamed($userName);
                                       
        $bookingModel = $this->bookingModel->Admin_GetDetail($id);
        if ($bookingModel == null)
            return redirect(base_url("user_profile/restaurant_manage_booking")); 
                  
        $data = array(                                                 
                'title' => 'Chi tiết đặt chỗ',
                'content' => 'site/user/user/info_booking.phtml',
                'fullname' => $this->session->userdata('fullname'), 
                'level' => $this->session->userdata('level'), 
                'user' => array('user' => $this->session->userdata('user')),
                'fullname' => $this->session->userdata('fullname'), 
                'userModel' => array(
                    'userID' => $user-> userID,       
                    'memName' => $user-> memName,    
                    'addressImage' => $user-> addressImage, 
                ), 
                );      
        $model = array(
             'error' => ''         
        );
              
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $model['bookingID'] = $bookingModel->bookingID; 
        $model['dateCreateBo'] = $this->dateutils->FormatVnDatetimeFromDb($bookingModel->dateCreateBo);
        $model['dateBooking'] = $this->dateutils->FormatVnDatetimeFromDb($bookingModel->dateBooking, 'H:i d/m/Y');  
        $model['quantityMember'] = $bookingModel->quantityMember; 
        $model['commentBo'] = $bookingModel->commentBo;
        $model['restaurantName'] = $bookingModel->restaurantName;  
        $model['userName'] = $bookingModel->userName;           
        $model['statusBo'] = $bookingModel->statusBo;  
        
        $data['model']  = $model;
        if($this->input->post('submit'))
        {                           
            //Lay du lieu tu forn dong thoi gan bien du gia tri                                 
            $model['statusBo'] = $statusBo = strip_tags($this->input->post('statusBo'));                
            $model['commentBo'] = $commentBo = strip_tags($this->input->post('commentBo')); 
            
            $data['model']  = $model;
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = 1;   
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataEdit = array(
                                'statusBo'     =>  $statusBo,
                                'commentBo'     =>  $commentBo
                                );       
                if ($this->bookingModel->Update($id, $dataEdit))
                {
                    //Neu luu thanh cong                   
                    $model['error'] = $this->Error('Cập nhật thành công!'); 
                }
                else
                {
                    //Nguoc lai neu khong luu duoc    
                    $model['error'] = $this->Error('Không cập nhật được booking!');   
                }
            } 
            else
            {
                $model['error'] = $error;  
            }           
        }
            
        $data['model'] = $model;
        
        $this->load->view('site/layout/layoutprofile.phtml', $data); 
    }

    public function check_id($id, $uri)
    {
        if(is_numeric($id) == FALSE)
        {
            redirect(base_url($uri));
        }
    }       
    
    public function Error($value)
    {
        return '<br /><font color=red>- '.$value.'</font>';
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