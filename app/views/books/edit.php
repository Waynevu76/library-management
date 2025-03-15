<?php include __DIR__.'/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-primary">✏ Edit Book</h2>
            <form action="/books/edit/<?= $book['id'] ?>" method="POST">
                <input type="hidden" name="id" value="<?= $book['id']; ?>">
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($book['title']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" name="author" id="author" class="form-control" value="<?= htmlspecialchars($book['author']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select name="category_id" id="category" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= ($category['id'] == $book['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" name="year" id="year" class="form-control" value="<?= htmlspecialchars($book['year']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="<?= htmlspecialchars($book['quantity']); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-success">✅ Update</button>
                <a href="/books" class="btn btn-secondary">⬅ Back</a>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
