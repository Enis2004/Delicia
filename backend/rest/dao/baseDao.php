<?php
require_once __DIR__ . "/../config.php";

class BaseDao
{
    protected $connection;
    private $table_name;
    private $id_column;

    public function __construct($table_name, $id_column = "id")
    {
        $this->table_name = $table_name;
        $this->id_column = $id_column;
        try {
            $this->connection = new PDO(
                "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT(),
                Config::DB_USER(),
                Config::DB_PASSWORD(),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw $e;
        }
    }

    protected function query($query, $params)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique($query, $params)
    {
        $results = $this->query($query, $params);
        return reset($results);
    }

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->table_name);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->table_name . " WHERE " . $this->id_column . " = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function add($entity)
    {
        $query = "INSERT INTO " . $this->table_name . " (";
        foreach ($entity as $column => $value) {
            $query .= $column . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        foreach ($entity as $column => $value) {
            $query .= ":" . $column . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ")";

        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);
        $entity[$this->id_column] = $this->connection->lastInsertId();
        return $entity;
    }
    public function update($entity, $id, $id_column = null)
    {
        if ($id_column === null) {
            $id_column = $this->id_column;
        }
        $query = "UPDATE " . $this->table_name . " SET ";
        foreach ($entity as $column => $value) {
            $query .= $column . "=:" . $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE " . $id_column . " = :id";
        $stmt = $this->connection->prepare($query);
        $entity['id'] = $id;
        $stmt->execute($entity);
        return $this->getById($id);
    }
    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM " . $this->table_name . " WHERE " . $this->id_column . " = :id");
        $stmt->bindValue(':id', $id); 
        $result = $stmt->execute();
        if ($result) {
            return ['success' => true, 'message' => 'Item deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete item'];
        }
    }
}
