<?php
class User {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function findUserByUsername($username) {
        $stmt = $this->connection->prepare("SELECT id, username, email, password, role FROM users WHERE username = ?");
        if (!$stmt) {
            error_log("SQL Error: " . $this->connection->error);
            return false;
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createUser($username, $email, $password, $role) {
        if (empty($username) || empty($email) || empty($password) || empty($role)) {
            return false; // Không cho phép dữ liệu rỗng
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            error_log("SQL Error: " . $this->connection->error);
            return false;
        }
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
        return $stmt->execute();
    }

    public function getAllUsers() {
        $stmt = $this->connection->prepare("SELECT id, username, email, role FROM users ORDER BY username ASC");
        if (!$stmt) {
            error_log("SQL Error: " . $this->connection->error);
            return [];
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->connection->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
        if (!$stmt) {
            error_log("SQL Error: " . $this->connection->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function editUser($id, $username, $email, $role, $password = null) {
        if (empty($username) || empty($email) || empty($role)) {
            return false;
        }

        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->connection->prepare("UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $username, $email, $role, $hashedPassword, $id);
        } else {
            $stmt = $this->connection->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $email, $role, $id);
        }

        if (!$stmt) {
            error_log("SQL Error: " . $this->connection->error);
            return false;
        }
        return $stmt->execute();
    }

    public function deleteUser($id) {
        if ($id == 1) { 
            error_log("Bảo vệ tài khoản admin gốc, không thể xóa!");
            return false;
        }
        $stmt = $this->connection->prepare("DELETE FROM users WHERE id = ?");
        if (!$stmt) {
            error_log("SQL Error: " . $this->connection->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
