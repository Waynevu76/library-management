<?php
require_once '../app/models/User.php';

class UserController {
    private $db;
    private $userModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User($this->db);
    }

    public function create() {
        try {
            AuthMiddleware::checkRole(['admin']);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = trim(htmlspecialchars($_POST['username']));
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'];
                $role = $_POST['role'];

                // Kiểm tra đầu vào
                if (!$username || !$email || !$password || !in_array($role, ['admin', 'user', 'librarian'])) {
                    throw new Exception("Invalid input data.");
                }

                if ($this->userModel->createUser($username, $email, $password, $role)) {
                    header('Location: /users');
                    exit;
                } else {
                    throw new Exception("Failed to create user.");
                }
            }
            
            require_once __DIR__ . '/../views/users/create.php';
        } catch (Exception $e) {
            error_log("User creation error: " . $e->getMessage());
            http_response_code(400);
            echo "Error: " . $e->getMessage();
        }
    }

    public function index() {
        try {
            AuthMiddleware::checkRole(['admin']);
            $users = $this->userModel->getAllUsers();
            require_once __DIR__ . '/../views/users/index.php';
        } catch (Exception $e) {
            error_log("User listing error: " . $e->getMessage());
            http_response_code(403);
            echo "Access Denied";
        }
    }

    public function edit($id) {
        try {
            AuthMiddleware::checkRole(['admin']);
            
            $id = intval($id);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = trim(htmlspecialchars($_POST['username']));
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $role = $_POST['role'];

                if (!$username || !$email || !in_array($role, ['admin', 'user', 'librarian'])) {
                    throw new Exception("Invalid input data.");
                }

                if ($this->userModel->editUser($id, $username, $email, $role)) {
                    header('Location: /users');
                    exit;
                } else {
                    throw new Exception("Failed to update user.");
                }
            }

            $user = $this->userModel->getUserById($id);
            require_once __DIR__ . '/../views/users/edit.php';
        } catch (Exception $e) {
            error_log("User edit error: " . $e->getMessage());
            http_response_code(400);
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete($id) {
        try {
            AuthMiddleware::checkRole(['admin']);
            
            $id = intval($id);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($this->userModel->deleteUser($id)) {
                    header('Location: /users');
                    exit;
                } else {
                    throw new Exception("Failed to delete user.");
                }
            }

            $user = $this->userModel->getUserById($id);
            require_once __DIR__ . '/../views/users/delete.php';
        } catch (Exception $e) {
            error_log("User delete error: " . $e->getMessage());
            http_response_code(400);
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
