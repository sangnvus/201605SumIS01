<?php
namespace Angi\Form;

use Zend\Form\Form;

class BlogForm extends Form {

    public function __construct($name = null) {
        parent::__construct('blog');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'blog',
            'type' => 'Text',
            'option' => array(
                'label' => 'Blog Content',
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