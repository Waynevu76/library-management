<?php
require_once '../middleware/AuthMiddleware.php';
AuthMiddleware::checkLogin();

require_once '../migration/init_data.php';
require_once '../core/Router.php';
require_once __DIR__ . '/../routes/web.php';
?>