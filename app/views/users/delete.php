<?php include __DIR__.'/../layout/header.php'; ?>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-danger">❌ Delete User</h2>
            <p>Are you sure you want to delete user <strong><?= htmlspecialchars($user['username']) ?></strong>?</p>
            
            <form method="POST" action="/users/delete/<?= $user['id'] ?>">
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                <a href="/users" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__.'/../layout/footer.php'; ?>

