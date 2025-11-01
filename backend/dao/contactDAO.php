<?php
require_once 'baseDao.php';

class ContactDao extends BaseDao {
    public function __construct() {
        parent::__construct("contacts", "contact_id");
    }

    public function getByEmail($email) {
        return $this->query(
            "SELECT * FROM contacts WHERE user_email = :email ORDER BY date_sent DESC",
            [ 'email' => $email ]
        );
    }

    public function searchBySubject($term) {
        $like = "%" . $term . "%";
        return $this->query(
            "SELECT * FROM contacts WHERE subject LIKE :q ORDER BY date_sent DESC",
            [ 'q' => $like ]
        );
    }

    public function getRecent($limit = 10, $offset = 0) {
        $limit = (int)$limit;
        $offset = (int)$offset;
        $sql = "SELECT * FROM contacts ORDER BY date_sent DESC LIMIT $offset, $limit";
        return $this->query($sql, []);
    }
}
?>
