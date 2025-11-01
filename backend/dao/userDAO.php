<?php
require_once 'baseDao.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct("users", "user_id");
    }
    public function getByEmail($email) {
        return $this->query_unique(
            "SELECT * FROM users WHERE email = :email",
            [ 'email' => $email ]
        );
    }

    public function listByRole($role) {
        return $this->query(
            "SELECT * FROM users WHERE role = :role ORDER BY name",
            [ 'role' => $role ]
        );
    }

    public function searchByNameOrEmail($term) {
        $like = "%" . $term . "%";
        return $this->query(
            "SELECT * FROM users WHERE name LIKE :q OR email LIKE :q ORDER BY name",
            [ 'q' => $like ]
        );
    }

    public function authenticate($email, $password) {
        $user = $this->getByEmail($email);
        if (!$user) { return null; }
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public function changePassword($user_id, $new_password_plain) {
        $hash = password_hash($new_password_plain, PASSWORD_DEFAULT);
        $this->update([ 'password' => $hash ], $user_id);
        return true;
    }
}
?>
