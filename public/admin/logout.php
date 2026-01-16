<?php

declare(strict_types=1);

require __DIR__ . '/../../config/bootstrap.php';

use App\Security\Auth;

$auth = new Auth($pdo);
$auth->logout();

header('Location: /admin/login.php');
exit;
