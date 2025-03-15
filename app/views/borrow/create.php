<?php include __DIR__.'/../layout/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-primary">📚 Create Borrow Order</h2>
            <form method="POST" action="/borrow/create" class="mt-3">
                <div id="book-selection-container" class="mb-3">
                    <label for="book_ids" class="form-label">Select Books:</label>
                    <select name="book_ids[]" class="form-select" required>
                        <?php foreach ($books as $book): ?>
                            <option value="<?= $book['id'] ?>"> 
                                <?= htmlspecialchars($book['title']) ?> (<?= $book['quantity'] ?> available) 
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addBookSelection()">➕ Add More</button>
                <button type="submit" class="btn btn-success">✅ Borrow</button>
            </form>
        </div>
    </div>
    <a href="/borrow" class="btn btn-secondary mt-4">⬅ Back to Borrow</a>
</div>
    <script>
        function addBookSelection() {
            const container = document.getElementById("book-selection-container");
            const select = document.createElement("select");
            select.className = "form-select mt-2";
            select.name = "book_ids[]";
            select.required = true;

            // Lấy danh sách sách còn lại chưa được chọn
            let selectedBooks = new Set();
            document.querySelectorAll("select[name='book_ids[]']").forEach(s => {
                selectedBooks.add(s.value);
            });

            <?php foreach ($books as $book): ?>
                if (!selectedBooks.has("<?= $book['id'] ?>")) {
                    const option = document.createElement("option");
                    option.value = "<?= $book['id'] ?>";
                    option.text = "<?= htmlspecialchars($book['title']) ?> (<?= $book['quantity'] ?> available)";
                    select.appendChild(option);
                }
            <?php endforeach; ?>

            if (select.options.length > 0) {
                container.appendChild(select);
            } else {
                alert("No more books available to select.");
            }
        }
    </script>
<?php include __DIR__.'/../layout/footer.php'; ?>
