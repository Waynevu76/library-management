<?php
require_once '../config/database.php';

class Book
{
    private $connection;
    private $table = 'books';

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function read()
    {
        $query = "SELECT books.*, categories.name AS genre 
                  FROM {$this->table} 
                  LEFT JOIN categories ON books.category_id = categories.id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function create($title, $author, $category_id, $year, $quantity)
    {
        $query = "INSERT INTO {$this->table} (title, author, category_id, year, quantity) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param("ssiii", $title, $author, $category_id, $year, $quantity);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function findById($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return null;
        }

        $query = "SELECT books.*, categories.name AS genre 
                  FROM {$this->table} 
                  LEFT JOIN categories ON books.category_id = categories.id 
                  WHERE books.id = ? LIMIT 1";

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ?: null;
    }

    public function update($id, $title, $author, $category_id, $year, $quantity)
    {
        $query = "UPDATE {$this->table} 
                  SET title = ?, author = ?, category_id = ?, year = ?, quantity = ?
                  WHERE id = ?";

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param("ssiiii", $title, $author, $category_id, $year, $quantity, $id);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $row_count = $stmt->affected_rows;
        $stmt->close();
        return $row_count > 0;
    }

    public function delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return false;
        }

        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows > 0;
    }
}
?>