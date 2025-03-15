<?php include '../app/views/layout/header.php'; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">📖 Book List</h2>
        <a href="/books/create" class="btn btn-primary">➕ Add Book</a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>📖 Title</th>
                        <th>✍️ Author</th>
                        <th>📚 Genre</th>
                        <th>📅 Year</th>
                        <th>📅 Quantity</th>
                        <th>⚙️ Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr class="align-middle">
                            <td><?= $book['id']; ?></td>
                            <td><?= htmlspecialchars($book['title']); ?></td>
                            <td><?= htmlspecialchars($book['author']); ?></td>
                            <td><?= htmlspecialchars($book['genre']); ?></td>
                            <td><?= htmlspecialchars($book['year']); ?></td>
                            <td><?= htmlspecialchars($book['quantity']); ?></td>
                            <td>
                                <a href="/books/edit/<?= $book['id']; ?>" class="btn btn-warning">✏ Update</a>
                                <a href="/books/delete/<?= $book['id']; ?>" class="btn btn-danger" onclick="return confirm('Do you want to delete?');">🗑 Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../app/views/layout/footer.php'; ?>