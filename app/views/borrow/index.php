<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">📚 Borrowed Books</h2>
        <button class="btn btn-success" onclick="location.href='/borrow/create'">➕ Add Borrow</button>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>📖 Title(s)</th>
                        <th>📅 Borrow Date</th>
                        <th>🔄 Status</th>
                        <th>⚙️ Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrows as $borrow): ?>
                        <?php if ($borrow['status'] === 'returned') continue; ?>
                        <tr id="row-<?= $borrow['id'] ?>" class="align-middle">
                            <td><?= nl2br(htmlspecialchars($borrow['titles'])) ?></td>
                            <td><?= htmlspecialchars($borrow['borrow_date']) ?></td>
                            <td>
                                <span class="badge bg-<?= $borrow['status'] === 'borrowed' ? 'warning' : 'success' ?>">
                                    <?= htmlspecialchars(ucfirst($borrow['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($borrow['status'] === 'borrowed'): ?>
                                    <button class="btn btn-danger return-btn" data-id="<?= $borrow['id'] ?>">Return</button>
                                <?php else: ?>
                                    <span class="text-success">✅ Returned</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".return-btn").click(function() {
            console.log("Return button clicked");
            var borrowId = $(this).data("id");

            fetch('/borrow/return', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: borrowId
                    })
                })
                .then(response => {
                    if (response.ok) {
                        $("#row-" + borrowId).remove(); // Xóa dòng khỏi bảng
                    } else {
                        alert("An error occurred while returning books.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("An error occurred while returning books.");
                });
        });

        // Hàm cập nhật danh sách
        function refreshBorrowList() {
            $("#borrow-list").load(location.href + " #borrow-list > *"); // Chỉ cập nhật bảng borrow
        }
    });
</script>
<?php include __DIR__ . '/../layout/footer.php'; ?>