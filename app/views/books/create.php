<?php include __DIR__.'/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-primary">📚 Add New Book</h2>
            <form action="/books/create" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" name="author" id="author" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="category" class="form-label">Genre</label>
                    <select name="category_id" id="category" class="form-select" required>
                        <option value="">-- Select Genre --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id']; ?>">
                                <?= htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" name="year" id="year" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-success">➕ Create</button>
                <a href="/books" class="btn btn-secondary">⬅ Back</a>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__.'/../layout/footer.php'; ?>
