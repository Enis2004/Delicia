<?php
require_once 'baseDao.php';

class CategoryDao extends BaseDao {
    public function __construct() {
        parent::__construct("categories", "category_id");
    }

    public function getByName($name) {
        return $this->query_unique(
            "SELECT * FROM categories WHERE name = :name",
            [ 'name' => $name ]
        );
    }

    public function listWithItemCounts() {
        return $this->query(
            "SELECT c.*, COUNT(mi.item_id) AS items_count
             FROM categories c
             LEFT JOIN menu_items mi ON mi.category_id = c.category_id
             GROUP BY c.category_id
             ORDER BY c.name",
            []
        );
    }

    public function rename($category_id, $new_name) {
        return $this->update([ 'name' => $new_name ], $category_id);
    }

    public function getItems($category_id) {
        return $this->query(
            "SELECT mi.* FROM menu_items mi WHERE mi.category_id = :cid ORDER BY mi.name",
            [ 'cid' => $category_id ]
        );
    }
}
?>

