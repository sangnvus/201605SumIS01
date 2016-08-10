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
        $this->load->library('DateUtils');
        $this->load->model('usersModel', '', TRUE);
        $this->load->model('addressModel', '', TRUE);
        $this->load->model('imageModel', '', TRUE);
        $this->load->model('categoriesOfRestaurantModel', '', TRUE);
        $this->load->model('bookingModel', '', TRUE);
        $this->load->model('foodModel', '', TRUE);
        $this->load->model('restaurantModel', '', TRUE);
        $this->load->model('imageModel', '', TRUE);
        $this->load->model('newsModel', '', TRUE);
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
        
        $data = array(
            'title'=> 'Quản trị',
            'fullname' => $this->session->userdata('fullname'), 
            'user'=> $user, 
            'model'=> array()
         );
        $data['content'] = 'admin/dashboard.phtml';
        $this->load->view('admin/layout/layout.phtml', $data);
    }

    public function news($offset = 0) {
        $offset = intval($offset);
        
        $user = $this->session->userdata('user');  
                                
        $rows = 0;  
        $config = $this->getConfig();                                                  
        $result = $this->newsModel->Admin_FindBy($offset, $config['per_page'], $rows);
        
        $config['base_url'] = base_url().'/admin/news';
        $config['total_rows'] = $rows;  
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        $data = array(
                    'title' => 'Danh sách nhà hàng',
                    'user' => array('user' => $user),
                    'fullname' => $this->session->userdata('fullname'), 
                    'model' => array(      
                        'result' => $result,
                        'rows' => $rows,
                        'pagination' => $pagination)
                    );
        $data['content'] = 'admin/news/all.phtml';
        
        $this->load->view('admin/layout/layout.phtml', $data);  
    }

    public function add_news() {
        $data = array(
                'title' => 'Thêm tin tức',
                'content' => 'admin/news/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                'fullname' => $this->session->userdata('fullname'), 
                );  
        $model = array(
             'error' => ''
        );                      
  
        $submit = $this->input->post('submit');
        if ($submit){
            $model['titleNews'] = strip_tags($this->input->post('titleNews'));
            $model['contentNews'] = $this->input->post('contentNews');
            $model['imageNewsTemp'] = $this->input->post('imageNewsTemp');
            $model['imageNews'] = strip_tags($this->input->post('imageNews')); 
            $model['typeNews'] = strip_tags($this->input->post('typeNews')); 
            $model['statusNews'] = strip_tags($this->input->post('statusNews')); 
        }
                      
        if ($submit){
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = 1;
            if (strlen($model['titleNews']) == 0){
                $error .= $this->Error('Chưa nhập tiêu đề');
                $ok = 0;
            }
            if (strlen($model['contentNews']) == 0){
                $error .= $this->Error('Chưa nhập nội dung');
                $ok = 0;
            }
            if (strlen($model['imageNewsTemp']) > 0){ 
                $model['imageNews'] = $model['imageNewsTemp'];
            }
            
            if (!$ok)   {
                $model['error'] = $error;                                                           
            }
            else
            {                                  
                $entity = array(
                                'titleNews' => $model['titleNews'],
                                'contentNews' => $model['contentNews'],
                                'imageNews' => $model['imageNews'], 
                                'typeNews' => $model['typeNews'], 
                                'statusNews' => $model['statusNews']
                );
                if ($this->newsModel->Create($entity))
                {                         
                    //Neu luu thanh cong  
                    $model['error'] = $this->Error('Cập nhật thành công!'); 
                    $model['ok']  = 1;
                }
                else
                {
                    //Nguoc lai neu khong luu duoc 
                    $model['error'] = $this->Error('Không cập nhật được dữ liệu!');    
                }  
            }
        }
          
        $data['model'] = $model;  
                                                                                          
        $this->load->view('admin/layout/layout.phtml', $data); 
    }

    public function edit_news($id) {
        $this->check_id($id, 'admin/news');                   
                 
        $data = array(                                                 
                'title' => 'Cập nhật tin tức',
                'content' => 'admin/news/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                'fullname' => $this->session->userdata('fullname'), 
                );      
        $model = array(
             'error' => ''
        );
        if (!$this->input->post('submit')){
            $news = $this->newsModel->GetById($id);
            if ($news == null)
                return redirect(base_url("admin/news"));    
                         
            $model['titleNews'] = $news->titleNews;
            $model['contentNews'] = $news->contentNews;
            $model['imageNews'] = $news->imageNews;     
            $model['typeNews'] = $news->typeNews; 
            $model['statusNews'] = $news->statusNews; 
            
            $data['model']  = $model;                                      
        }                                                                             
        else
        {
            $model['titleNews'] = strip_tags($this->input->post('titleNews'));
            $model['contentNews'] = $this->input->post('contentNews');
            $model['imageNews'] = strip_tags($this->input->post('imageNews')); 
            $model['imageNewsTemp'] = strip_tags($this->input->post('imageNewsTemp')); 
            $model['typeNews'] = strip_tags($this->input->post('typeNews')); 
            $model['statusNews'] = strip_tags($this->input->post('statusNews')); 
            
            $data['model']  = $model;
            //kiem tra du lieu
            //kiem tra du lieu
            $error = '';
            $ok = 1;
            if (strlen($model['titleNews']) == 0){
                $error .= $this->Error('Chưa nhập tiêu đề');
                $ok = 0;
            }
            if (strlen($model['contentNews']) == 0){
                $error .= $this->Error('Chưa nhập nội dung');
                $ok = 0;
            }
            
            if (strlen($model['imageNewsTemp']) > 0){ 
                $model['imageNews'] = $model['imageNewsTemp'];
            }
            
            if ($ok == 1)
            {
                $entity = array(
                                'titleNews' => $model['titleNews'],
                                'contentNews' => $model['contentNews'],
                                'imageNews' => $model['imageNews'], 
                                'typeNews' => $model['typeNews'], 
                                'statusNews' => $model['statusNews']
                ); 
                
                if ($this->newsModel->Update($id, $entity))
                {    
                    //Neu luu thanh cong   
                    $model['ok'] = 1;
                    $model['error'] = $this->Error('Cập nhật thành công!'); 
                }
                else
                {
                    //Nguoc lai neu khong luu duoc  
                    $model['error'] = $this->Error('Không cập nhật được dữ liệu!');
                }  
            } 
        }  
                                                                           
        $data['model'] = $model;                                                                                    
        $this->load->view('admin/layout/layout.phtml', $data);   
    }

    public function delete_news($id)
    {                                     
        $this->check_id($id, 'admin/news');
        $this->newsModel->Delete($id);
        redirect(base_url('admin/news'));
    }

//  ================================================================================

    public function restaurant($offset = 0) {
        $offset = intval($offset);
        
        $user = $this->session->userdata('user');  
                                
        $rows = 0;  
        $config = $this->getConfig();                                                  
        $result = $this->restaurantModel->Admin_FindBy($offset, $config['per_page'], $rows);
        
        $config['base_url'] = base_url().'/admin/restaurant';
        $config['total_rows'] = $rows;  
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        $data = array(
                    'title' => 'Danh sách tin tức',
                    'user' => array('user' => $user),
                    'fullname' => $this->session->userdata('fullname'), 
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
                'fullname' => $this->session->userdata('fullname'), 
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
                                                                                          
        $this->load->view('admin/layout/layout.phtml', $data); 
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
                'fullname' => $this->session->userdata('fullname'), 
                );      
        $model = array(
             'error' => '',                             
             'users' => $this->usersModel->Admin_GetUser(1, 100000, 0),                       
             'province' => $this->addressModel->ListAllProvince(),
             'categories' => $this->categoriesOfRestaurantModel->ListByStatus(1),    
             'foods' => $this->foodModel->Admin_FindBy($id, 0, 1000)
        );
        if (!$this->input->post('submit')){
            $restaurant = $this->restaurantModel->Admin_GetFullById($id);
            if ($restaurant == null)
                return redirect(base_url("admin/restaurant"));             
            
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
            
            $data['model']  = $model;                                      
        }                                                                             
        else
        {
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['provinceID'] = strip_tags($this->input->post('provinceID'));
            $model['districtID'] = strip_tags($this->input->post('districtID'));
            $model['wardID'] = strip_tags($this->input->post('wardID'));          
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
            $model['address'] = strip_tags($this->input->post('address'));
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
                if ($this->restaurantModel->Update($id, $dataEdit))
                {    
                    //Neu luu thanh cong   
                    $model['ok'] = 1;
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
        $this->load->view('admin/layout/layout.phtml', $data);      
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

    public function getDistrictByProvinceId($provinceId=0)
    {        
        $districts = $this->addressModel->FindDistrictByProvinceId($provinceId);
        echo $this->returnSuccess(json_encode($districts));
    }
     
    public function getWardByDistrictId($districtId=0)
    {        
        $wards = $this->addressModel->FindWardByProvinceId($districtId);
        echo $this->returnSuccess(json_encode($wards));
    }
    
    public function eater($level=0, $offset = 0) {
        $level = intval($level);
        $offset = intval($offset);
        
        $user = $this->session->userdata('user');  
                                
        $rows = 0;                                         
        $config = $this->getConfig();                                                  
        $result = $this->usersModel->Admin_GetUser($level, $config['per_page'], $offset);
        $rows = $this->usersModel->Count_By($level);
        
        $config['base_url'] = base_url().'/admin/eater/'.$level;
        $config['total_rows'] = $rows; 
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        $data = array(
                    'title' => 'Danh sách thực khách',  
                    'user' => $this->session->userdata('user'),
                    'fullname' => $this->session->userdata('fullname'), 
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
        $model = array(
            'error' => '',
            'level' => $level,  
            'userLevel' => $level,
            'province' => $this->addressModel->ListAllProvince(),
        );  
        if($this->input->post('submit'))
        {       
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['provinceID'] = $provinceID = $this->input->post('provinceID');
            $model['districtID'] = $districtID = $this->input->post('districtID');
            $model['wardID'] = $wardID = $this->input->post('wardID'); 
            
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
            $model['address'] = $address = strip_tags($this->input->post('address'));
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
            else{
                $userExisted = $this->usersModel->CheckNameOrEmailExisted($userName);
                if ($userExisted){
                   $ok = 0;
                   $error .= $this->Error('Tên đăng nhập đã tồn tại');; 
                }
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
            if ($userMail == '')
            {
                $ok = 0;
                $error .= $this->Error('Chưa nhập email');
            }
            else{
                $userExisted = $this->usersModel->CheckNameOrEmailExisted($userMail);
                if ($userExisted){
                   $ok = 0;
                   $error .= $this->Error('Email đã tồn tại'); 
                }
            }
            if($memName == '')
            {
                $error .= $this->Error('Chưa nhập tên');
                $ok = 0;
            } 
            if ($ok == 0){
                $model['error'] = $error; 
                $model['district'] = $this->addressModel->FindDistrictByProvinceId($provinceID);
                $model['ward'] = $this->addressModel->FindWardByProvinceId($districtID);
            }
            else
            {
                $addInfo = array(
                    'user' => array(
                        'userName' => $userName,
                        'userMail' => $userMail,
                        'userPass' => md5($userPass),
                        'userPassRe' => md5($userPass),
                        'userActived' => $userActived
                    ),
                    'mem' => array(
                        'memName'=> $memName,
                        'memBirthDay' => $memBirthDay,
                        'memGender'=> $memGender
                    ),
                    'add' => array(
                        'address' => $address,
                        'provinceID' => $provinceID,
                        'districtID' => $districtID,
                        'wardID' => $wardID,
                        'statusAdd' => 1
                    )
                );                       
                if ($this->usersModel->Create($addInfo))
                {
                    //Neu luu thanh cong
                    redirect(base_url('admin/eater/'.$level));
                }
                else
                {
                    $model['error'] = 'Có lỗi trong quá trình tạo user'; 
                    $model['district'] = $this->addressModel->FindDistrictByProvinceId($provinceID);
                    $model['ward'] = $this->addressModel->FindWardByProvinceId($districtID); 
                }
            }            
        }
        
        $data = array(
                'title' => 'Thêm thực khách',
                'content' => 'admin/eater/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                'fullname' => $this->session->userdata('fullname'), 
                'model' => $model
                ); 
        
        $this->load->view('admin/layout/layout.phtml', $data); 
    }

    public function edit_eater($id) {
        $this->check_id($id, 'admin/eater');                   
        $level = 0;                               
        $user = $this->usersModel->Admin_GetUserById($id);
        if ($user == null)
            return redirect(base_url("admin/eater/".$level)); 
        
        $model = array(
            'error' => '',
            'level' => $level,
            'userLevel' => $level,
            'province' => $this->addressModel->ListAllProvince(),  
            'district' => $this->addressModel->FindDistrictByProvinceId($user->provinceID),
            'ward' => $this->addressModel->FindWardByProvinceId($user->districtID)
        ); 
        $isSubmit = $this->input->post('submit');
        if (!$isSubmit){
            //Lay du lieu tu forn dong thoi gan bien du gia tri 
            $model['userID'] = $user->userID; 
            $model['userLevel'] = $user->userLevel; 
            $model['userName'] = $user->userName;
            $model['userMail'] = $user->userMail;  
            $model['userActived'] = $user->userActived; 
              
            $model['memName'] = $user->memName;
            $model['memBirthDay'] = $this->dateutils->FormatVnDatetimeFromDb($user->memBirthDay);
            $model['memGender'] = $user->memGender;
            $model['addressID'] = $user->addressID;
            $model['imageID'] = $user->imageID;
            
            $model['provinceID'] = $user->provinceID;
            $model['districtID'] = $user->districtID;
            $model['wardID'] = $user->wardID;
            $model['address'] = $user->address;
        } 
        else
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
            $model['address'] = $address = strip_tags($this->input->post('address'));
            $model['imageID'] = $imageID = strip_tags($this->input->post('imageID'));
            
            $model['provinceID'] = $provinceID = strip_tags($this->input->post('provinceID'));
            $model['districtID'] = $districtID = strip_tags($this->input->post('districtID'));
            $model['wardID'] = $wardID = strip_tags($this->input->post('wardID'));
            $model['address'] = $address = strip_tags($this->input->post('address'));
            
            $data['model'] = $model;   
            //kiem tra du lieu
            $error = '';
            $ok = 1;
			$isPassChanged = false;
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
            else{
                $userExisted = $this->usersModel->CheckNameOrEmailExisted($userName, $id);
                if ($userExisted){
                   $ok = 0;
                   $error .= $this->Error('Tên đăng nhập đã tồn tại');; 
                }
            }
            if($userPass != '')
            {
                if($userPass != $userPassRe)
                {
                    $error .= $this->Error('Mật khẩu nhập kiểm tra không khớp');
                    $ok = 0;
                }
				$isPassChanged = true;
            }               
            if ($userMail == '')
            {
                $ok = 0;
                $error .= $this->Error('Chưa nhập email');
            }
            else{
                $userExisted = $this->usersModel->CheckNameOrEmailExisted($userMail, $id);
                if ($userExisted){
                   $ok = 0;
                   $error .= $this->Error('Email đã tồn tại'); 
                }
            }
            if($memName == '')
            {
                $error .= $this->Error('Chưa nhập tên');
                $ok = 0;
            } 
            if ($ok == 0){
                $model['error'] = $error; 
                $model['district'] = $this->addressModel->FindDistrictByProvinceId($provinceID);
                $model['ward'] = $this->addressModel->FindWardByProvinceId($districtID);
            }
            else
            {
				$userModel = array(
					'userName' => $userName,
					'userMail' => $userMail,
					'userActived' => $userActived
				);
				if ($isPassChanged){
					$userModel['userPass'] = $userModel['userPassRe'] = md5($userPass);
				}
                $editInfo = array(
                    'user' => $userModel,
                    'mem' => array(
                        'memName'=> $memName,
                        'memBirthDay' => $this->dateutils->VnStrDatetimeToDb($memBirthDay),
                        'memGender'=> $memGender,
                        'addressID'=> $addressID
                    ),
                    'add' => array(
                        'address' => $address,
                        'provinceID' => $provinceID,
                        'districtID' => $districtID,
                        'wardID' => $wardID,
                        'statusAdd' => 1
                    )
                );                                   
                if ($this->usersModel->Update($id, $editInfo))
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
        }   
        
        $data = array(
                'title' => 'Cập nhật thực khách',
                'content' => 'admin/eater/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                'fullname' => $this->session->userdata('fullname'), 
                'model' => $model
                ); 
        
        $this->load->view('admin/layout/layout.phtml', $data); 
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
                    'fullname' => $this->session->userdata('fullname'), 
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
                'fullname' => $this->session->userdata('fullname'), 
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
        $user = $this->categoriesOfRestaurantModel->GetById($id);
        if ($user == null)
            return redirect(base_url("admin/categories/")); 
                  
        $data = array(                                                 
                'title' => 'Cập nhật danh mục',
                'content' => 'admin/category/edit.phtml',
                'user' => array('user' => $this->session->userdata('user')),
                'fullname' => $this->session->userdata('fullname'), 
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

    public function delete_category($id)
    {                                     
        $this->check_id($id, 'admin/categories');
        $this->categoriesOfRestaurantModel->Delete($id);
        redirect(base_url('admin/categories'));
    }

//    ================================================================================

    public function booking($offset=0) { 
        $offset = intval($offset);                  
                                
        $count = 0;                                                    
        $config = $this->getConfig();
        $result = $this->bookingModel->Admin_FindBy(null, null, $offset, $config['per_page'], $count);  
        
        $config['base_url'] = base_url().'/admin/booking/';
        $config['total_rows'] = $count;
        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        
        $data = array(
                    'title' => 'Danh sách đặt chỗ',
                    'user' => $this->session->userdata('user'),
                    'fullname' => $this->session->userdata('fullname'), 
                    'model' => array(    
                        'result' => $result,
                        'rows' => $count,
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
                'fullname' => $this->session->userdata('fullname'), 
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

    public function report_statistics() {    
        $user = $this->session->userdata('user');  
                                
        $rows = 0;     
        $data = array(
                    'title' => 'Thống kê',
                    'user' => array('user' => $user),
                    'fullname' => $this->session->userdata('fullname'), 
                    'model' => array(      
                        'restaurant' => $this->restaurantModel->Report_ThongKeChung(),
                        'news' => $this->newsModel->Report_ThongKeChung(),
                        )
                    );
        $data['content'] = 'admin/report/statistics.phtml';
        
        $this->load->view('admin/layout/layout.phtml', $data);  
    }

//    ================================================================================

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