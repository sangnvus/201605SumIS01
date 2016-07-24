<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->database();
        $this->load->library('session');    
        $this->load->library('pagination');
        $this->load->model('usersModel', '', TRUE);
        $this->load->model('addressModel', '', TRUE);
        $this->load->model('imageModel', '', TRUE);
        $this->load->model('categoriesOfRestaurantModel', '', TRUE);
        $this->load->model('bookingModel', '', TRUE);
        $this->load->model('foodModel', '', TRUE);
        $this->load->model('restaurantModel', '', TRUE);
        $this->load->model('imageModel', '', TRUE);
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        if(!isset($user) || $user == '' || !isset($level) || $level != 2)
        {
            redirect(base_url().'/home');
        }
    }

    public function index() {
        $data = array();
        $user = $this->session->userdata('user');
        $level = $this->session->userdata('level');
        
        $data = array('title'=> 'Quản trị', 'user'=> $user, 'model'=> array());
        $data['content'] = 'admin/dashboard.phtml';
        $this->load->view('admin/layout/layout.phtml', $data);
    }

//    ================================================================================

    public function restaurant($offset = 0) {
        $offset = intval($offset);
        
        $user = $this->session->userdata('user');  
                                
        $rows = 0;                                                    
        $result = $this->restaurantModel->Admin_FindBy($offset, 20, $rows);
        
        $config['base_url'] = base_url().'/admin/restaurant';
        $config['total_rows'] = $rows;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<div class='pag'>";
        $config['full_tag_close'] = "</div>";
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        $data = array(
                    'title' => 'Danh sách nhà hàng',
                    'user' => array('user' => $user),
                    'model' => array(      
                        'result' => $result,
                        'rows' => $rows,
                        'pagination' => $pagination)
                    );
        $data['content'] = 'admin/restaurant/all.phtml';
        
        $this->load->view('admin/layout/layout.phtml', $data);  
    }

    public function add_restaurant() {
        $data = array(
                'title' => 'Thêm nhà hàng',
                'content' => 'admin/restaurant/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                );  
        $model = array(
             'error' => '',       
             'address' => $this->addressModel->ListAll(),
             'users' => $this->usersModel->Admin_GetUser(1, 100000, 0)    ,
             'categories' => $this->categoriesOfRestaurantModel->ListByStatus(1)
        );
        $data['model'] = $model;
  
        if($this->input->post('submit'))
        {       
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['restaurantID'] = strip_tags($this->input->post('restaurantID')); 
            $model['nameRe'] = strip_tags($this->input->post('nameRe'));
            $model['descriptionRes'] = strip_tags($this->input->post('descriptionRes'));
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
            
            $model['categoryOfResID'] = strip_tags($this->input->post('categoryOfResID'));
            
            $data['model'] = $model;   
            //kiem tra du lieu
            $error = '';
            $ok = $this->validateRestaurant($model, $error);
             
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataAdd = array(
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
                                );                                  
                
                $cateAdd = array(
                                'categoryOfResID' => $model['categoryOfResID'], 
                                'restaurantID' => $model['restaurantID']
                                );                                  
                $id = $this->restaurantModel->Create($dataAdd, $cateAdd);
                if ($id > 0)
                {
                    //Neu luu thanh cong
                    redirect(base_url('admin/edit_restaurant/'.$id));
                }
                else
                {
                    //Nguoc lai neu khong luu duoc
                      
                    $data['model']['error'] = $this->Error('Không tạo được nhà hàng!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);  
                }
            } 
            else
            {         
                $data['model']['error'] = $error;                                                            
                $this->load->view('admin/layout/layout.phtml', $data);  
            }           
        }
        else
        {                                                                                     
             $this->load->view('admin/layout/layout.phtml', $data);  
        }
    }

    function validateRestaurant($data, & $error){
        $ok = 1;
        if($data['categoryOfResID'] == '')
        {
            $error .= $this->Error('Chưa chọn loại cửa hàng');
            $ok = 0;
        }  
        if($data['nameRe'] == '')
        {
            $error .= $this->Error('Chưa nhập tên');
            $ok = 0;
        }   
        if($data['phoneRe'] == '')
        {
            $error .= $this->Error('Chưa nhập điện thoại');
            $ok = 0;
        }    
        if($data['descriptionRes'] == '')
        {
            $error .= $this->Error('Chưa nhập mô tả');
            $ok = 0;
        }    
        if($data['userID'] == '')
        {
            $error .= $this->Error('Chưa chọn người dùng');
            $ok = 0;
        }    
        if($data['openTimeRe'] == '')
        {
            $error .= $this->Error('Chưa chọn giờ mở của');
            $ok = 0;
        }  
        if($data['closeTimeRe'] == '')
        {
            $error .= $this->Error('Chưa chọn giờ đóng cửa');
            $ok = 0;
        }    
        if($data['addressID'] == '' && $data['otherPoints'] == '')    
        {
            $error .= $this->Error('Hãy chọn địa chỉ');
            $ok = 0;
        }    
        return $ok;
    }
    
    public function edit_restaurant($id) {
        $this->check_id($id, 'admin/restaurant');                   
                 
        $data = array(                                                 
                'title' => 'Cập nhật nhà hàng',
                'content' => 'admin/restaurant/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                );      
        $model = array(
             'error' => '',                      
             'address' => $this->addressModel->ListAll(),
             'users' => $this->usersModel->Admin_GetUser(1, 100000, 0)    ,
             'categories' => $this->categoriesOfRestaurantModel->ListByStatus(1),    
             'foods' => $this->foodModel->Admin_FindBy($id, 0, 1000)
        );
        if (!$this->input->post('submit')){
            $restaurant = $this->restaurantModel->Admin_GetById($id);
            if ($restaurant == null)
                return redirect(base_url("admin/restaurant")); 
            $resCate = $this->restaurantModel->Admin_GetResCateByResId($id);
            
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
            $model['userID'] = $restaurant->userID;
            $model['isDepositBo'] = $restaurant->isDepositBo;
            $model['isDeactivate'] = $restaurant->isDeactivate;
            $model['statusRes'] = $restaurant->statusRes;
                            
            $model['categoryOfResID'] = $resCate->categoryOfResID; 
            
            $data['model']  = $model;
            return $this->load->view('admin/layout/layout.phtml', $data);
        }                                                                             
                 
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $model['restaurantID'] = strip_tags($this->input->post('restaurantID')); 
        $model['nameRe'] = strip_tags($this->input->post('nameRe'));
        $model['descriptionRes'] = strip_tags($this->input->post('descriptionRes'));
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
            
        $model['categoryOfResID'] = strip_tags($this->input->post('categoryOfResID'));

        $data['model']  = $model;
        //kiem tra du lieu
        //kiem tra du lieu
        $error = '';
        $ok = $this->validateRestaurant($model, $error);
           
        if ($ok == 1)
        {
            //Tao mang chua thong tin ve user
            $dataEdit = array(
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
                                );                                  
                
            $cateEdit = array(
                            'categoryOfResID' => $model['categoryOfResID'], 
                            'restaurantID' => $id
                            );                                  
            
            if ($this->restaurantModel->Update($id, $dataEdit, $cateEdit))
            {    
                //Neu luu thanh cong      
                redirect(base_url('admin/restaurant'));
            }
            else
            {
                //Nguoc lai neu khong luu duoc
                  
                $data['model']['error'] = $this->Error('Không cập nhật được nhà hàng!');                                      
                $this->load->view('admin/layout/layout.phtml', $data);
            }  
        } 
        else
        {
            $data['model']['error'] = $error;                                                            
            $this->load->view('admin/layout/layout.phtml', $data);
        }        
    }
    
    public function delete_restaurant($id)
    {                                     
        $this->check_id($id, 'admin/restaurant');
        $this->restaurantModel->Delete($id);
        redirect(base_url('admin/restaurant'));
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
//    ================================================================================

    public function eater($level=0, $offset = 0) {
        $level = intval($level);
        $offset = intval($offset);
        
        $user = $this->session->userdata('user');  
                                
        $rows = 0;                                                    
        $result = $this->usersModel->Admin_GetUser($level, 10, $offset);
        $rows = $this->usersModel->Count_By($level);
        
        $config['base_url'] = base_url().'/admin/eater/'.$level;
        $config['total_rows'] = $rows;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<div class='pag'>";
        $config['full_tag_close'] = "</div>";
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        $data = array(
                    'title' => 'Danh sách thực khách',
                    'user' => array('user' => $user),
                    'model' => array(
                        'level' => $level,
                        'result' => $result,
                        'rows' => $rows,
                        'pagination' => $pagination)
                    );
        $data['content'] = 'admin/eater/all.phtml';   
        $this->load->view('admin/layout/layout.phtml', $data);  
    }

    public function add_eater($level=0) {
        $data = array(
                'title' => 'Thêm thực khách',
                'content' => 'admin/eater/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                );  
        $model = array(
             'error' => '',
             'level' => $level,
             'address' => $this->addressModel->ListAll(),
             'userLevel' => $level
        );
        $data['model'] = $model;
  
        if($this->input->post('submit'))
        {       
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['userLevel'] = $userLevel = strip_tags($this->input->post('userLevel')); 
            $model['userName'] = $userName = strip_tags($this->input->post('userName'));
            $model['userMail'] = $userMail = strip_tags($this->input->post('userMail'));
            $model['userActived'] = $userActived = strip_tags($this->input->post('userActived'));
            $userPass = strip_tags($this->input->post('userPass'));
            $userPassRe = strip_tags($this->input->post('userPassRe'));
            $model['memName'] = $memName = strip_tags($this->input->post('memName'));
            $model['memBirthDay'] = $memBirthDay = strip_tags($this->input->post('memBirthDay'));
            $model['memGender'] = $memGender = strip_tags($this->input->post('memGender'));
            $model['addressID'] = $addressID = strip_tags($this->input->post('addressID'));
            $model['imageID'] = $imageID = strip_tags($this->input->post('imageID'));
            
            $data['model'] = $model;   
            //kiem tra du lieu
            $error = '';
            $ok = 1;
            if($userLevel == '')
            {
                $error .= $this->Error('Chưa chọn cấp cho user');
                $ok = 0;
            }
            if($userName == '')
            {
                $error .= $this->Error('Chưa có tên đăng nhập');
                $ok = 0;
            }
            if($userPass == '')
            {
                $error .= $this->Error('Chưa có mật khẩu');
                $ok = 0;
            }
            if($userPass != $userPassRe)
            {
                $error .= $this->Error('Mật khẩu nhập kiểm tra không khớp');
                $ok = 0;
            }
            if($memName == '')
            {
                $error .= $this->Error('Chưa nhập tên');
                $ok = 0;
            }
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataAdd_user = array(
                                'userName'     =>  $userName,
                                'userMail'     =>  $userMail,
                                'userPass'     =>  md5($userPass),
                                'userPassRe'  =>  md5($userPass),
                                'userActived' => $userActived,
                                'userLevel'    =>  $userLevel
                                );
                //Tao mang chua thong tin chi tiet ve user
                $dataAdd_mem =  array(
                                'memName'      =>  $memName,
                                'memBirthDay'  =>  $memBirthDay,
                                'memGender'   =>  $memGender,
                                'addressID'   =>  $addressID,
                                'imageID'   =>  $imageID
                                );
                //Tao mang chua toan  bo du lieu de dua vao model
                $dataAdd = array('user'=>$dataAdd_user, 'mem'=>$dataAdd_mem);
                
                if ($this->usersModel->Create($dataAdd))
                {
                    //Neu luu thanh cong
                    redirect(base_url('admin/eater/'.$level));
                }
                else
                {
                    //Nguoc lai neu khong luu duoc
                      
                    $data['model']['error'] = $this->Error('Không tạo được user!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);  
                }
            } 
            else
            {
                $data['model']['error'] = $error;                                                            
                $this->load->view('admin/layout/layout.phtml', $data);  
            }           
        }
        else
        {                                                                                     
             $this->load->view('admin/layout/layout.phtml', $data);  
        }
    }

    public function edit_eater($id) {
        $this->check_id($id, 'admin/eater');                   
        $level = 0;                               
        $user = $this->usersModel->Admin_GetUserById($id);
        if ($user == null)
            return redirect(base_url("admin/eater/".$level)); 
                  
        $data = array(                                                 
                'title' => 'Cập nhật thực khách',
                'content' => 'admin/eater/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                );      
        $model = array(
             'error' => '',              
             'level' => $user->userLevel,
             'address' => $this->addressModel->ListAll()
        );
              
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $model['userID'] = $user->userID; 
        $model['userLevel'] = $user->userLevel; 
        $model['userName'] = $user->userName;
        $model['userMail'] = $user->userMail;  
        $model['userActived'] = $user->userActived;   
        $model['memName'] = $user->memName;
        $model['memBirthDay'] = $user->memBirthDay;
        $model['memGender'] = $user->memGender;
        $model['addressID'] = $user->addressID;
        $model['imageID'] = $user->imageID;      
        
        $data['model']  = $model;
        if($this->input->post('submit'))
        {                           
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['userLevel'] = $userLevel = strip_tags($this->input->post('userLevel')); 
            $model['userName'] = $userName = strip_tags($this->input->post('userName'));
            $model['userMail'] = $userMail = strip_tags($this->input->post('userMail'));
            $model['userActived'] = $userActived = strip_tags($this->input->post('userActived'));
            $userPass = strip_tags($this->input->post('userPass'));
            $userPassRe = strip_tags($this->input->post('userPassRe'));
            $model['memName'] = $memName = strip_tags($this->input->post('memName'));
            $model['memBirthDay'] = $memBirthDay = strip_tags($this->input->post('memBirthDay'));
            $model['memGender'] = $memGender = strip_tags($this->input->post('memGender'));
            $model['addressID'] = $addressID = strip_tags($this->input->post('addressID'));
            $model['imageID'] = $imageID = strip_tags($this->input->post('imageID'));

            $data['model']  = $model;
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = 1;
            if($userLevel == '')
            {
                $error .= $this->Error('Chưa chọn cấp cho user');
                $ok = 0;
            }
            if($userName == '')
            {
                $error .= $this->Error('Chưa có tên đăng nhập');
                $ok = 0;
            }
            if ($userPass != ''){
                if($userPass != $userPassRe)
                {
                    $error .= $this->Error('Mật khẩu nhập kiểm tra không khớp');
                    $ok = 0;
                }
                else{
                    $userPass = $userPassRe = md5($userPass);
                }
            }
            else {
                $userPass = $userPassRe = $user->userPass;
            }

            if($memName == '')
            {
                $error .= $this->Error('Chưa nhập tên');
                $ok = 0;
            }
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataEdit_user = array(
                                'userName'     =>  $userName,
                                'userMail'     =>  $userMail,  
                                'userPass'     =>  $userPass,
                                'userPassRe'  =>  $userPassRe,
                                'userLevel'    =>  $userLevel ,
                                'userActived'    =>  $userActived
                                );
                //Tao mang chua thong tin chi tiet ve user
                $dataEdit_mem =  array(
                                'memName'      =>  $memName,
                                'memBirthDay'  =>  $memBirthDay,
                                'memGender'   =>  $memGender,
                                'addressID'   =>  $addressID,
                                'imageID'   =>  $imageID
                                );
                //Tao mang chua toan  bo du lieu de dua vao model
                $dataEdit = array('user'=>$dataEdit_user, 'mem'=>$dataEdit_mem);
                
                if ($this->usersModel->Update($id, $dataEdit))
                {
                    //Neu luu thanh cong      
                    redirect(base_url('admin/eater/'.$level));
                }
                else
                {
                    //Nguoc lai neu khong luu duoc
                      
                    $data['model']['error'] = $this->Error('Không cập nhật được user!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);
                }
            } 
            else
            {
                $data['model']['error'] = $error;                                                            
                $this->load->view('admin/layout/layout.phtml', $data);
            }           
        }
        else
        {                                                                                                        
            $this->load->view('admin/layout/layout.phtml', $data);
        }
    }
    
    public function delete_eater($id, $level)
    {                         
        $this->check_id($id, 'admin');
        $this->usersModel->Delete($id);
        redirect(base_url('admin/eater/'.$level));
    }
         
//    ================================================================================

    public function categories() {  
        $user = $this->session->userdata('user');  
                                
        $rows = $this->categoriesOfRestaurantModel->Count_All();;                                                    
        $result = $this->categoriesOfRestaurantModel->ListAll();
        
        $data = array(
                    'title' => 'Danh mục',
                    'user' => array('user' => $user),
                    'model' => array(  
                        'result' => $result,
                        'rows' => $rows)
                    );
        $data['content'] = 'admin/category/all.phtml';
        
        $this->load->view('admin/layout/layout.phtml', $data);  
    }

    public function add_category() {
        $data = array(
                'title' => 'Thêm thực khách',
                'content' => 'admin/category/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                );  
        $model = array(
             'error' => ''
        );
        $data['model'] = $model;
  
        if($this->input->post('submit'))
        {       
            //Lay du lieu tu forn dong thoi gan bien du gia tri                                         
            $model['statusCOR'] = $statusCOR = strip_tags($this->input->post('statusCOR')); 
            $model['nameCOR'] = $nameCOR = strip_tags($this->input->post('nameCOR'));
            $model['desciptionCOR'] = $desciptionCOR = strip_tags($this->input->post('desciptionCOR')); 
            
            $data['model'] = $model;   
            //kiem tra du lieu
            $error = '';
            $ok = 1;
            if($nameCOR == '')
            {
                $error .= $this->Error('Chưa nhập tên');
                $ok = 0;
            }
            
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataAdd = array(
                                'nameCOR'     =>  $nameCOR,
                                'desciptionCOR'     =>  $desciptionCOR,
                                'statusCOR'     =>  $statusCOR
                                );  
                                
                if ($this->categoriesOfRestaurantModel->Create($dataAdd))
                {
                    //Neu luu thanh cong
                    redirect(base_url('admin/categories/'));
                }
                else
                {
                    //Nguoc lai neu khong luu duoc
                      
                    $data['model']['error'] = $this->Error('Không tạo được user!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);  
                }
            } 
            else
            {
                $data['model']['error'] = $error;                                                            
                $this->load->view('admin/layout/layout.phtml', $data);  
            }           
        }
        else
        {                                                                                     
             $this->load->view('admin/layout/layout.phtml', $data);  
        }
    }

    public function edit_category($id) {
        $this->check_id($id, 'admin/categories');                   
        $level = 0;                               
        $user = $this->categoriesOfRestaurantModel->Admin_GetById($id);
        if ($user == null)
            return redirect(base_url("admin/categories/")); 
                  
        $data = array(                                                 
                'title' => 'Cập nhật danh mục',
                'content' => 'admin/category/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                );      
        $model = array(
             'error' => ''         
        );
              
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $model['nameCOR'] = $user->nameCOR; 
        $model['statusCOR'] = $user->statusCOR;
        $model['desciptionCOR'] = $user->desciptionCOR;   
        
        $data['model']  = $model;
        if($this->input->post('submit'))
        {                           
            //Lay du lieu tu forn dong thoi gan bien du gia tri                                 
            $model['nameCOR'] = $nameCOR = strip_tags($this->input->post('nameCOR'));
            $model['statusCOR'] = $statusCOR = strip_tags($this->input->post('statusCOR'));
            $model['desciptionCOR'] = $desciptionCOR = strip_tags($this->input->post('desciptionCOR'));   

            $data['model']  = $model;
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = 1;        
            if($nameCOR == '')
            {
                $error .= $this->Error('Chưa nhập tên');
                $ok = 0;
            }
            
            if ($ok == 1)
            {
                //Tao mang chua thong tin ve user
                $dataEdit = array(
                                'nameCOR'     =>  $nameCOR,
                                'desciptionCOR'     =>  $desciptionCOR,
                                'statusCOR'     =>  $statusCOR
                                );       
                if ($this->categoriesOfRestaurantModel->Update($id, $dataEdit))
                {
                    //Neu luu thanh cong
                    redirect(base_url('admin/categories/'));
                }
                else
                {
                    //Nguoc lai neu khong luu duoc
                      
                    $data['model']['error'] = $this->Error('Không cập nhật được user!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);
                }
            } 
            else
            {
                $data['model']['error'] = $error;                                                            
                $this->load->view('admin/layout/layout.phtml', $data);
            }           
        }
        else
        {                                                                                                        
            $this->load->view('admin/layout/layout.phtml', $data);
        }
    }

//    ================================================================================

    public function booking($offset=0) { 
        $offset = intval($offset);
        
        $user = $this->session->userdata('user');  
                                
        $rows = 0;                                                    
        $result = $this->bookingModel->Admin_FindBy($offset, 20);
        $rows = $this->bookingModel->Count_All();
        
        $config['base_url'] = base_url().'/admin/booking/'.$offset;
        $config['total_rows'] = $rows;
        $config['per_page'] = 10;
        $config['full_tag_open'] = "<div class='pag'>";
        $config['full_tag_close'] = "</div>";
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        $data = array(
                    'title' => 'Danh sách đặt chỗ',
                    'user' => array('user' => $user),
                    'model' => array(    
                        'result' => $result,
                        'rows' => $rows,
                        'pagination' => $pagination)
                    );
                    
        $data['content'] = 'admin/booking/all.phtml';
        
        $this->load->view('admin/layout/layout.phtml', $data);  
    }

    public function edit_booking($id) {
        $this->check_id($id, 'admin/booking');                   
        $level = 0;                               
        $user = $this->bookingModel->Admin_GetDetail($id);
        if ($user == null)
            return redirect(base_url("admin/booking")); 
                  
        $data = array(                                                 
                'title' => 'Chi tiết đặt chỗ',
                'content' => 'admin/booking/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                );      
        $model = array(
             'error' => ''         
        );
              
        //Lay du lieu tu forn dong thoi gan bien du gia tri 
        $model['bookingID'] = $user->bookingID; 
        $model['dateCreateBo'] = $user->dateCreateBo;
        $model['dateBooking'] = $user->dateBooking;  
        $model['quantityMember'] = $user->quantityMember; 
        $model['commentBo'] = $user->commentBo;
        $model['restaurantName'] = $user->restaurantName;  
        $model['userName'] = $user->userName;           
        $model['statusBo'] = $user->statusBo;  
        
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
                    $data['model']['error'] = $this->Error('Cập nhật thành công!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);
                }
                else
                {
                    //Nguoc lai neu khong luu duoc
                      
                    $data['model']['error'] = $this->Error('Không cập nhật được user!');                                      
                    $this->load->view('admin/layout/layout.phtml', $data);
                }
            } 
            else
            {
                $data['model']['error'] = $error;                                                            
                $this->load->view('admin/layout/layout.phtml', $data);
            }           
        }
        else
        {                                                                                                        
            $this->load->view('admin/layout/layout.phtml', $data);
        }
    }

//    ================================================================================

    public function setting() {
        $data = array();
        $data['content'] = 'admin/setting.phtml';
        $this->load->view('admin/layout/layout.phtml', $data);
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
}