<?php
require_once __DIR__ . '/../models/Borrow.php';
require_once __DIR__ . '/../models/Book.php';

class BorrowController {
    private $db;
    private $borrowModel;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->borrowModel = new Borrow($this->db);
    }

    public function index() {
        try {
            if (!isset($_SESSION['user']['id'])) {
                throw new Exception("User not logged in");
            }

            $user_id = intval($_SESSION['user']['id']);
            $borrows = $this->borrowModel->getUserBorrows($user_id);
            require_once __DIR__ . '/../views/borrow/index.php';
        } catch (Exception $e) {
            error_log("Borrow index error: " . $e->getMessage());
            http_response_code(500);
            echo "An error occurred while fetching borrow records.";
        }
    }
    
    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['book_ids'])) {
                $user_id = intval($_SESSION['user']['id']);
                $book_ids = array_map('intval', $_POST['book_ids']); // Chuyển đổi thành số nguyên

                // Kiểm tra sách có hợp lệ không
                $availableBooks = $this->borrowModel->getAllAvailableBooks();
                $availableBookIds = array_column($availableBooks, 'id');

                foreach ($book_ids as $book_id) {
                    if (!in_array($book_id, $availableBookIds)) {
                        throw new Exception("Some books are not available for borrowing.");
                    }
                }

                $this->borrowModel->borrowBooks($user_id, $book_ids);
                header('Location: /borrow');
                exit;
            }

            $books = $this->borrowModel->getAllAvailableBooks();
            require_once __DIR__ . '/../views/borrow/create.php';
        } catch (Exception $e) {
            error_log("Borrow create error: " . $e->getMessage());
            http_response_code(400);
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function return() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Invalid request method.");
            }

            $input = json_decode(file_get_contents("php://input"), true);
            if (!isset($input['id']) || !is_numeric($input['id'])) {
                throw new Exception("Invalid book ID.");
            }

            $book_id = intval($input['id']);
            $user_id = intval($_SESSION['user']['id']);

            // Kiểm tra người dùng có quyền trả sách không
            if (!$this->borrowModel->isBookBorrowedByUser($user_id, $book_id)) {
                throw new Exception("You cannot return a book you haven't borrowed.");
            }

            $this->borrowModel->returnBook($book_id);
            echo json_encode(["success" => "Book returned successfully."]);
        } catch (Exception $e) {
            error_log("Borrow return error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}
?>
