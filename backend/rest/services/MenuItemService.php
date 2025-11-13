<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/menuItemDAO.php';

class MenuItemService extends BaseService {
    public function __construct(){
        parent::__construct(new MenuItemDao());
    }
    public function getByCategoryId($category_id) {
        return $this->dao->getByCategoryId($category_id);
    }

    public function searchByName($term) {
        return $this->dao->searchByName($term);
    }

    public function filterByPriceRange($min_price, $max_price) {
        return $this->dao->filterByPriceRange($min_price, $max_price);
    }

    public function getWithCategory($item_id) {
        return $this->dao->getWithCategory($item_id);
    }

    public function getManyByIds($ids) {
        return $this->dao->getManyByIds($ids);
    }

    public function createMenuItem($data) {
        if (isset($data['price']) && $data['price'] <= 0) {
            throw new Exception('Price must be a positive value.');
        }
        return $this->add($data);
    }

    public function updateMenuItem($data, $id) {
        if (isset($data['price']) && $data['price'] <= 0) {
            throw new Exception('Price must be a positive value.');
        }
        return $this->update($data, $id);
    }
}

?>

