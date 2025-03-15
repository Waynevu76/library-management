<?php include __DIR__.'/../layout/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-danger">🗑 Delete Book</h2>
            <form action="/books/delete/<?= $book['id'] ?>" method="POST">
                <input type="hidden" name="id" value="<?= $book['id']; ?>">
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($book['title']); ?>" disabled>
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" name="author" id="author" class="form-control" value="<?= htmlspecialchars($book['author']); ?>" disabled>
                </div>
                
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" name="category_id" id="category" class="form-control" value="<?= htmlspecialchars($book['gerne']); ?>" disabled>
                </div>
                
                <div class="mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" name="year" id="year" class="form-control" value="<?= htmlspecialchars($book['year']); ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="<?= htmlspecialchars($book['quantity']); ?>" disabled>
                </div>
                
                <p class="text-danger">⚠ Are you sure you want to delete this book?</p>
                <button type="submit" class="btn btn-danger">🗑 Delete</button>
                <a href="/books" class="btn btn-secondary">⬅ Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__.'/../layout/footer.php'; ?>