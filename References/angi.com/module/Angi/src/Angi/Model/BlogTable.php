<?php
namespace Angi\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Angi\Model\Entity\Blog;

class BlogTable extends AbstractTableGateway {
    protected $table = "blog";

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
            $select->order('created ASC');
        });

            $entities = array();
            foreach ($resultSet as $row) {
                $entity = new Blog();
                $entity->setId($row->id);
                $entity->setBlog($row->blog);
                $entity->setCreated($row->created);
                $entities[] = $entity;
            }
            return $entities;
    }

    public function getBlog($id) {
        $row = $this->select(array('id' => (int) $id))->current();

        if (!$row) {
            return false;
        }
        
        return $row;
    }

    public function saveBlog(Blog $blog) {
        $data = array(
            'blog' => $blog->getBlog(),
            'created' => $blog->getCreated(),
        );

        $id = (int) $blog->getId();

        if ($id == 0) {
            $data['created'] = date("Y-m-d H:i:s");
            if (!$this->insert($data)) {
                return false;
            }

            return $this->getLastInsertValue();
        } elseif ($this->getBlog($id)) {
            $this->update($data, array('id' => $id));
            return true;
        } else {
            return false;
        }
    }

    public function deleteBlog($id) {
        return $this->delete(array('id' => (int) $id));
    }

}