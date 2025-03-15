<?php
require_once '../app/models/User.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {   
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User($this->db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Làm sạch dữ liệu đầu vào
                $username = trim(htmlspecialchars($_POST['username'] ?? ''));
                $password = trim($_POST['password'] ?? '');

                if (empty($username) || empty($password)) {
                    throw new Exception("Username and password are required");
                }

                // Kiểm tra database connection
                if (!$this->db) {
                    throw new Exception("Database connection failed: " . mysqli_connect_error());
                }

                $user = $this->userModel->findUserByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    // Bảo vệ session
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role']
                    ];
                    header('Location: /dashboard');
                    exit;
                } else {
                    throw new Exception("Invalid username or password");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                error_log("Login error: " . $e->getMessage()); // Ghi log lỗi
            }
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function dashboard() {
        AuthMiddleware::checkLogin();
        require_once __DIR__ . '/../views/dashboard/dashboard.php';
    }

    public function logout() {
        session_start();
        $_SESSION = []; // Xóa toàn bộ dữ liệu session
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/'); // Xóa session cookie
        header('Location: /login');
        exit;
    }
}
?>
