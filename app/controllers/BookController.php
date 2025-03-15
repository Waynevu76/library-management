<?php
require_once '../app/models/Book.php';
require_once '../app/models/Category.php';

class BookController
{
    private $bookModel;
    private $categoryModel;
    private $db;

    public function __construct()
    {   
        $this->db = Database::getInstance()->getConnection();
        $this->bookModel = new Book($this->db);
        $this->categoryModel = new Category($this->db);
    }

    public function index()
    {
        $books = $this->bookModel->read();
        require_once '../app/views/books/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->sanitizePostData(['title', 'author', 'category_id', 'year']);

            if ($this->bookModel->create($data['title'], $data['author'], $data['category_id'], $data['year'], $data['quantity'])) {
                header('Location: /books');
                exit;
            } else {
                $error = "Không thể tạo sách. Vui lòng thử lại!";
            }
        }

        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/books/create.php';
    }

    public function edit($id)
    {
        $book = $this->bookModel->findById($id);
        if (!$book) {
            die("Sách không tồn tại!");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->sanitizePostData(['title', 'author', 'category_id', 'year']);

            if ($this->bookModel->update($id, $data['title'], $data['author'], $data['category_id'], $data['year'], $data['quantity'])) {
                header('Location: /books');
                exit;
            } else {
                $error = "Không thể cập nhật sách. Vui lòng thử lại!";
            }
        }

        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/books/edit.php';
    }

    public function delete($id)
    {
        $book = $this->bookModel->findById($id);
        if (!$book) {
            die("Sách không tồn tại!");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->bookModel->delete($id)) {
                header('Location: /books');
                exit;
            } else {
                $error = "Không thể xóa sách. Vui lòng thử lại!";
            }
        }

        require_once __DIR__ . '/../views/books/delete.php';
    }

    private function sanitizePostData($fields)
    {
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = isset($_POST[$field]) ? trim($_POST[$field]) : null;
        }
        return $data;
    }
}
?>
