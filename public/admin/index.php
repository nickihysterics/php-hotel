<?php

declare(strict_types=1);

require __DIR__ . '/../../config/bootstrap.php';

use App\Security\Auth;

$auth = new Auth($pdo);
$auth->requireLogin();

$title = 'Главная';
require __DIR__ . '/../../templates/admin/header.php';
?>
<section class="admin-card">
  <h2>Добро пожаловать</h2>
  <p>Используйте меню для управления справочниками гостиницы.</p>
</section>
<?php
require __DIR__ . '/../../templates/admin/footer.php';
