<?php
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : 'login';

switch ($url) {
    case 'login':
        require_once 'views/civicadmin_login.php';
        break;
    case 'dashboard':
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ?url=login');
            exit;
        }
        require_once 'views/civicadmin_dashboard.php';
        break;
    case 'subadmin':
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ?url=login');
            exit;
        }
        if ($_SESSION['admin_role'] !== 'super') {
            header('Location: ?url=dashboard');
            exit;
        }
        require_once 'views/subAdminDashboard.php';
        break;
    default:
        http_response_code(404);
        echo '404 Not Found';
        break;
}