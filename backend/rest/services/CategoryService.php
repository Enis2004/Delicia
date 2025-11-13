<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/categoryDAO.php';

class CategoryService extends BaseService {
    public function __construct(){
        parent::__construct(new CategoryDao());
    }
    public function getByName($name) {
        return $this->dao->getByName($name);
    }

    public function listWithItemCounts() {
        return $this->dao->listWithItemCounts();
    }

    public function rename($category_id, $new_name) {
        return $this->dao->rename($category_id, $new_name);
    }

    public function getItems($category_id) {
        return $this->dao->getItems($category_id);
    }
}

?>

