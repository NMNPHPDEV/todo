<?php

use App\Controllers\TodoController;
use App\Repositories\TodoRepository;


require_once 'app/config.php';
require_once 'app/models/Todo.php';
require_once 'app/repositories/TodoRepository.php';
require_once 'app/controllers/TodoController.php';

// Bağlantı kur
$connection = new PDO(
    'mysql:host=' . DB_HOST . ';dbname=' . DB_DATABASE,
    DB_USERNAME,
    DB_PASSWORD
);

// Repository ve Controller oluştur
$repository = new TodoRepository($connection);
$controller = new TodoController($repository);

// İşlemi yönlendir
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $controller->edit($_GET['id']);
        break;
    case 'update':
        $controller->update($_POST['id'], $_POST['title'], $_POST['completed']);
        break;
    case 'delete':
        $controller->delete($_GET['id']);
        break;
    default:
        echo "Geçersiz işlem.";
}
