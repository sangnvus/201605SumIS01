<?php

namespace Angi\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Angi\Model\Entity\Blog;
use Angi\Form\BlogForm;

class AngiController extends AbstractActionController {

    protected $blogTable;

    public function indexAction() {
        return new ViewModel(array(
            'blogs' => $this->getBlogTable()->fetchAll(),
        ));
    }

    public function addAction() {
        $form = new BlogForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $blog = new Blog();
            $form->setInputFilter($blog->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $blog->setOptions($form->getData());
                $this->getBlogTable()->saveBlog($blog);

                // Tro ve danh sach blog
                return $this->redirect()->toRoute('angi');
            }
        }

        return array('form' => $form);
    }

    public function updateAction() {
        $id = (int) $this->params()->fromRoute('id',0);
        if (!$id) {
            return $this->redirect()->toRoute('angi', array('action' => 'add'));
        }

        $blog = $this->getBlogTable()->getBlog($id);

        if (!$blog) {
            return $this->redirect()->toRoute('angi', array('action' => 'index'));
        }

        $form = new BlogForm();
        $form->bind($blog);

        $request = $this->getRequest();
        if ($request->isPost()) {
            // var_dump($blog);
            // exit();
            $form->setInputFilter($blog->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getBlogTable()->saveBlog($blog);

                // Tro ve danh sach blog
                return $this->redirect()->toRoute('angi');
            }
        }

        return array('id' => $id,'form'=>$form);
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id',0);

        if (!$id) {
            return $this->redirect()->toRoute('angi', array('action' => 'index'));
        }

        $blog = $this->getBlogTable()->getBlog($id);

        if (!$blog) {
            return $this->redirect()->toRoute('angi', array('action' => 'index'));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getBlogTable()->deleteBlog($id);

            }

            // Tro ve danh sach blog
            return $this->redirect()->toRoute('angi');
        }

        return array(
            'id' => $id,
            'blog' => $this->getBlogTable()->getBlog($id),
        );
    }

    public function getBlogTable() {
        if (!$this->blogTable) {
            $sm = $this->getServiceLocator();
            $this->blogTable = $sm->get('Angi\Model\BlogTable');
        }

        return $this->blogTable;
    }
}