<?php
namespace Album\Form;

 use Zend\Form\Form;
 class AlbumForm extends Form
 {
     public function __construct($name = null)
     {
         parent::__construct('album');
         // tạo một input loại hidden để lưu giá trị ID của bộ sưu tập
         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         //tạo một input loại text giúp nhập tiêu đề của bộ sưu tập
         $this->add(array(
             'name' => 'title',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Title',
             ),
         ));
         //tạo một input loại text giúp nhập tác giả của bộ sưu tập
         $this->add(array(
             'name' => 'artist',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Artist',
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }