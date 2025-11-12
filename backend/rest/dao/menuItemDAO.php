<?php
require_once 'baseDao.php';

class MenuItemDao extends BaseDao {
    public function __construct() {
        parent::__construct("menu_items", "item_id");
    }
    public function getByCategoryId($category_id) {
        return $this->query(
            "SELECT * FROM menu_items WHERE category_id = :category_id ORDER BY name",
            [ 'category_id' => $category_id ]
        );
    }

    public function searchByName($term) {
        $like = "%" . $term . "%";
        return $this->query(
            "SELECT * FROM menu_items WHERE name LIKE :q ORDER BY name",
            [ 'q' => $like ]
        );
    }

    public function filterByPriceRange($min_price, $max_price) {
        return $this->query(
            "SELECT * FROM menu_items WHERE price BETWEEN :min AND :max ORDER BY price, name",
            [ 'min' => $min_price, 'max' => $max_price ]
        );
    }

    public function getWithCategory($item_id) {
        return $this->query_unique(
            "SELECT mi.*, c.name AS category_name
             FROM menu_items mi
             LEFT JOIN categories c ON c.category_id = mi.category_id
             WHERE mi.item_id = :id",
            [ 'id' => $item_id ]
        );
    }

    public function getManyByIds($ids) {
        if (empty($ids)) { return []; }
        $placeholders = [];
        $params = [];
        foreach ($ids as $index => $id) {
            $key = ':id' . $index;
            $placeholders[] = $key;
            $params['id' . $index] = $id;
        }
        $in = implode(',', $placeholders);
        return $this->query(
            "SELECT * FROM menu_items WHERE item_id IN ($in)",
            $params
        );
    }

    public function deleteByExactNames($names) {
        if (empty($names)) { return 0; }
        $placeholders = [];
        $params = [];
        foreach ($names as $i => $name) {
            $ph = ':n' . $i;
            $placeholders[] = $ph;
            $params['n' . $i] = $name;
        }
        $in = implode(',', $placeholders);
        
        $this->connection->beginTransaction();
        $stmt = $this->connection->prepare("DELETE FROM menu_items WHERE name IN ($in)");
        $stmt->execute($params);
        $affected = $stmt->rowCount();
        $this->connection->commit();
        return $affected;
    }
}
?>

