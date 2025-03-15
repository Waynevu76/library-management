<?php
class Category
{
    private $connection;
    private $table = "categories";

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getAll()
    {
        $query = "SELECT id, name FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            error_log("SQL Error: " . $this->connection->error);
            return false;
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $result ?: [];
    }
}
?>
