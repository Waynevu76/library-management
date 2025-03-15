<?php
require_once __DIR__ . '/../core/Router.php';

$router = new Router();

// Routes cho AuthController
$router->add('/login', 'AuthController', 'login');       // Trang đăng nhập
$router->add('/logout', 'AuthController', 'logout');     // Đăng xuất
$router->add('/dashboard', 'AuthController', 'dashboard'); // Trang dashboard

// Routes cho BookController
$router->add('/books', 'BookController', 'index');         // Danh sách sách
$router->add('/books/create', 'BookController', 'create'); // Trang tạo sách
$router->add('/books/store', 'BookController', 'store');   // Lưu sách mới
$router->add('/books/edit/{id}', 'BookController', 'edit');// Trang chỉnh sửa sách
$router->add('/books/update/{id}', 'BookController', 'update'); // Cập nhật sách
$router->add('/books/delete/{id}', 'BookController', 'delete'); // Xóa sách

// Routes cho UserController
$router->add('/users', 'UserController', 'index');         // Danh sách người dùng
$router->add('/users/create', 'UserController', 'create'); // Trang tạo người dùng
$router->add('/users/edit/{id}', 'UserController', 'edit');   // Lưu người dùng mới
$router->add('/users/delete/{id}', 'UserController', 'delete');   // Lưu người dùng mới

// Routes cho BorrowController (mượn sách)
$router->add('/borrow', 'BorrowController', 'index');      // Danh sách sách đang mượn
$router->add('/borrow/create', 'BorrowController', 'create'); // Yêu cầu mượn sách
$router->add('/borrow/return', 'BorrowController', 'return'); // Trả sách

// Xử lý route
$url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$router->dispatch($url);
?>
