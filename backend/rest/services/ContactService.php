<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../dao/contactDAO.php';

class ContactService extends BaseService {
    public function __construct(){
        parent::__construct(new ContactDao());
    }
    public function getByEmail($email) {
        return $this->dao->getByEmail($email);
    }

    public function searchBySubject($term) {
        return $this->dao->searchBySubject($term);
    }

    public function getRecent($limit = 10, $offset = 0) {
        return $this->dao->getRecent($limit, $offset);
    }
}

?>

