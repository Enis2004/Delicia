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
    public function insertUser($user) {
        if (isset($user['email'])) {
            $existingUser = $this->dao->getByEmail($user['email']);
            if ($existingUser) {
                throw new Exception("User with this email already exists.");
            }
        }
        if (isset($user['email']) && !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        if (!isset($user['role']) || empty($user['role'])) {
            throw new Exception("Role is required and must be 'admin' or 'user'.");
        }
        $user['role'] = strtolower(trim($user['role']));
        if (!in_array($user['role'], ['admin', 'user'])) {
            throw new Exception("Role must be 'admin' or 'user'.");
        }
        
        return $this->dao->insertUser($user);
    }
}

?>

