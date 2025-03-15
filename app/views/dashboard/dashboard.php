<?php include __DIR__ . '/../layout/header.php'; ?>
<?php $user = $_SESSION['user'] ?? null; ?>
<div class="container mt-5">
    <div class="dashboard-card text-center p-4 shadow-sm bg-light">
        <?php if ($user): ?>
            <h2 class="text-primary">Welcome, <?= htmlspecialchars($user['username']) ?>! 🎉</h2>
            <p>Your role: <strong class="text-success"> <?= htmlspecialchars($user['role']) ?></strong></p>
        <?php else: ?>
            <p class="text-danger">Redirecting to login...</p>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>