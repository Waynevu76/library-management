<?php
class Borrow {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllAvailableBooks() {
        $stmt = $this->db->prepare("SELECT id, title, quantity FROM books WHERE quantity > 0");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function borrowBooks($user_id, $book_ids) {
        $this->db->begin_transaction();

        try {
            $user_id = intval($user_id);
            
            // Kiểm tra số lượng sách trước khi mượn
            $placeholders = implode(',', array_fill(0, count($book_ids), '?'));
            $stmt = $this->db->prepare("SELECT id, quantity FROM books WHERE id IN ($placeholders)");
            $stmt->bind_param(str_repeat('i', count($book_ids)), ...$book_ids);
            $stmt->execute();
            $books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            foreach ($books as $book) {
                if ($book['quantity'] <= 0) {
                    throw new Exception("Book ID {$book['id']} is out of stock.");
                }
            }

            // Tạo đơn mượn
            $stmt = $this->db->prepare("INSERT INTO borrow_orders (user_id, borrow_date, status) VALUES (?, NOW(), 'borrowed')");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            // Chèn vào bảng borrow_items
            $stmt = $this->db->prepare("INSERT INTO borrow_items (order_id, book_id) VALUES (?, ?)");
            foreach ($book_ids as $book_id) {
                $book_id = intval($book_id);
                $stmt->bind_param("ii", $order_id, $book_id);
                $stmt->execute();
            }

            // Giảm số lượng sách với một câu lệnh SQL duy nhất
            $stmt = $this->db->prepare("UPDATE books SET quantity = quantity - 1 WHERE id IN ($placeholders)");
            $stmt->bind_param(str_repeat('i', count($book_ids)), ...$book_ids);
            $stmt->execute();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function returnBook($borrow_id) {
        try {
            $borrow_id = intval($borrow_id);

            // Lấy danh sách book_id từ đơn mượn
            $stmt = $this->db->prepare("SELECT book_id FROM borrow_items WHERE order_id = ?");
            $stmt->bind_param("i", $borrow_id);
            $stmt->execute();
            $books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            if (empty($books)) {
                throw new Exception("No books found for this borrow ID.");
            }

            $book_ids = array_column($books, 'book_id');
            $placeholders = implode(',', array_fill(0, count($book_ids), '?'));

            // Cập nhật số lượng sách
            $stmt = $this->db->prepare("UPDATE books SET quantity = quantity + 1 WHERE id IN ($placeholders)");
            $stmt->bind_param(str_repeat('i', count($book_ids)), ...$book_ids);
            $stmt->execute();

            // Cập nhật trạng thái đơn mượn
            $stmt = $this->db->prepare("UPDATE borrow_orders SET status = 'returned' WHERE id = ?");
            $stmt->bind_param("i", $borrow_id);
            $stmt->execute();

            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Return book error: " . $e->getMessage());
            return false;
        }
    }

    public function getUserBorrows($user_id) {
        $user_id = intval($user_id);
        $stmt = $this->db->prepare("
            SELECT borrow_orders.id, 
                   GROUP_CONCAT(books.title SEPARATOR ', ') AS titles, 
                   borrow_orders.borrow_date, 
                   borrow_orders.status 
            FROM borrow_orders 
            JOIN borrow_items ON borrow_orders.id = borrow_items.order_id 
            JOIN books ON borrow_items.book_id = books.id 
            WHERE borrow_orders.user_id = ? 
            GROUP BY borrow_orders.id
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function isBookBorrowedByUser($user_id, $book_id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM borrow_orders 
            JOIN borrow_items ON borrow_orders.id = borrow_items.order_id 
            WHERE borrow_orders.user_id = ? 
            AND borrow_items.book_id = ? 
            AND borrow_orders.status = 'borrowed'
        ");
        $stmt->bind_param("ii", $user_id, $book_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['count'] > 0; // Trả về true nếu user đã mượn sách này
    }
    
}
?>
