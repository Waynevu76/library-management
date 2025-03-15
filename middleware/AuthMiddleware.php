<?php
    session_start();
    class AuthMiddleware {
        public static function checkRole($roles) {
            if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles)) {
                die("Access Denied");
            }
        }

        public static function checkLogin() {
            // Nếu chưa đăng nhập và không phải trang login, chuyển hướng về login
            if (!isset($_SESSION['user']) && $_SERVER['REQUEST_URI'] !== '/login') {
                header('Location: /login');
                exit;
            } else if (isset($_SESSION['user']) && $_SERVER['REQUEST_URI'] === '/') {
                header('Location: /dashboard');
                exit;
            }
        }
    }
?>