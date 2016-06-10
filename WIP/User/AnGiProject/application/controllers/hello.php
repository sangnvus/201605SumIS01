<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
class Hello extends CI_Controller {
  
    public function index() {
        echo 'Freetuts.net Hello Controller';
    }
  
    public function other(){
        echo 'Freetuts.net Other Controller';
    }

    public function index2($message = '')
    {
        echo 'Freetuts.net ' . $message;
    }
}?>