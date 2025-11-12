<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/userDAO.php';

class UserService extends BaseService {
    public function __construct(){
        parent::__construct(new UserDao());
    }
    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function listByRole($role) {
        return $this->dao->listByRole($role);
    }

    public function searchByNameOrEmail($term) {
        return $this->dao->searchByNameOrEmail($term);
    }

    public function changePassword($user_id, $new_password_plain) {
        return $this->dao->changePassword($user_id, $new_password_plain);
    }
}

?>

