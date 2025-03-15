<?php
require_once __DIR__ . '/../config/Database.php';

// Kết nối database
$db = Database::getInstance()->getConnection();

// Tạo bảng categories nếu chưa có
$query = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$db->query($query);

// Tạo bảng books nếu chưa có
$query = "CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    year INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$db->query($query);

// Tạo bảng users nếu chưa có
$query = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'librarian', 'user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$db->query($query);

// Tạo bảng borrow_orders nếu chưa có
$query = "CREATE TABLE IF NOT EXISTS borrow_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    borrow_date DATETIME DEFAULT NULL,
    status ENUM('borrowed', 'returned') DEFAULT 'borrowed',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$db->query($query);

// Tạo bảng borrow_items nếu chưa có
$query = "CREATE TABLE IF NOT EXISTS borrow_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT DEFAULT NULL,
    book_id INT DEFAULT NULL,
    FOREIGN KEY (order_id) REFERENCES borrow_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$db->query($query);

// Kiểm tra và chèn dữ liệu vào categories
$result = $db->query("SELECT COUNT(*) as count FROM categories");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $query = "INSERT INTO categories (name) VALUES 
        ('Fantasy'), 
        ('Dystopian'), 
        ('Classic')";
    $db->query($query);
}

// Kiểm tra và chèn dữ liệu vào users
$result = $db->query("SELECT COUNT(*) as count FROM users");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $query = "INSERT INTO users (username, email, password, role) VALUES 
        ('admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin'), 
        ('librarian', 'librarian@example.com', '" . password_hash('lib123', PASSWORD_DEFAULT) . "', 'librarian'), 
        ('user', 'user@example.com', '" . password_hash('user123', PASSWORD_DEFAULT) . "', 'user')";
    $db->query($query);
}

// Kiểm tra và chèn dữ liệu vào books
$result = $db->query("SELECT COUNT(*) as count FROM books");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $query = "INSERT INTO books (title, author, category_id, year, quantity) VALUES 
        ('The Hobbit', 'J.R.R. Tolkien', 1, 1937, 5), 
        ('1984', 'George Orwell', 2, 1949, 3), 
        ('To Kill a Mockingbird', 'Harper Lee', 3, 1960, 4)";
    $db->query($query);
}

// Kiểm tra và chèn dữ liệu vào borrow_orders
$result = $db->query("SELECT COUNT(*) as count FROM borrow_orders");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $query = "INSERT INTO borrow_orders (user_id, borrow_date, status) VALUES 
        (1, '2025-03-15 10:00:00', 'borrowed'), 
        (2, '2025-03-14 14:30:00', 'returned')";
    $db->query($query);
}

// Kiểm tra và chèn dữ liệu vào borrow_items
$result = $db->query("SELECT COUNT(*) as count FROM borrow_items");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $query = "INSERT INTO borrow_items (order_id, book_id) VALUES 
        (1, 1), 
        (1, 2), 
        (2, 3)";
    $db->query($query);
}

Database::getInstance()->closeConnection();
?>
