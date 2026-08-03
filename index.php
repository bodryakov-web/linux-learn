<?php
/**
 * Front Controller - главный входной файл приложения
 * Обрабатывает все HTTP-запросы и осуществляет маршрутизацию
 */

// Запуск сессии
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Подключение конфигурации
require_once 'config.php';

// Подключение контроллеров
require_once 'controllers/LessonController.php';
require_once 'controllers/AdminController.php';

// Получаем путь запроса
$requestUri = $_SERVER['REQUEST_URI'];
// Удаляем query string если есть
$requestUri = strtok($requestUri, '?');

// Удаляем базовый URL из пути
if (!empty(BASE_URL)) {
    $path = str_replace(BASE_URL, '', $requestUri);
} else {
    $path = $requestUri;
}

// Удаляем начальный и конечный слеши
$path = trim($path, '/');

// Для отладки - закомментировать после тестирования
// error_log("Request URI: $requestUri, Path: $path, BASE_URL: " . BASE_URL);

// Разбираем путь на части
$parts = explode('/', $path);

// Маршрутизация
if (empty($path) || $path === '') {
    // Главная страница - список уроков
    $controller = new LessonController();
    $controller->index();
    
} elseif ($parts[0] === 'bod') {
    // Админ-панель
    $adminController = new AdminController();
    
    if (count($parts) === 1) {
        // /bod - дашборд админки
        $adminController->dashboard();
        
    } elseif ($parts[1] === 'login') {
        // /bod/login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->login();
        } else {
            $adminController->loginForm();
        }
        
    } elseif ($parts[1] === 'logout') {
        // /bod/logout
        $adminController->logout();
        
    } elseif ($parts[1] === 'create') {
        // /bod/create - форма создания урока
        $adminController->showCreateForm();
        
    } elseif ($parts[1] === 'store') {
        // /bod/store - сохранение нового урока
        $adminController->store();
        
    } elseif ($parts[1] === 'edit' && isset($parts[2])) {
        // /bod/edit/{id} - форма редактирования урока
        $lessonId = intval($parts[2]);
        $adminController->showEditForm($lessonId);
        
    } elseif ($parts[1] === 'update' && isset($parts[2])) {
        // /bod/update/{id} - обновление урока
        $lessonId = intval($parts[2]);
        $adminController->update($lessonId);
        
    } elseif ($parts[1] === 'delete' && isset($parts[2])) {
        // /bod/delete/{id} - удаление урока
        $lessonId = intval($parts[2]);
        $adminController->delete($lessonId);
        
    } else {
        // Некорректный URL админки
        $adminController->notFound();
    }
    
} elseif (preg_match('/^(\d+)-([a-z-]+)$/', $path, $matches)) {
    // Страница урока: /{order}-{slug}
    $order = intval($matches[1]);
    $slug = $matches[2];
    
    $controller = new LessonController();
    $controller->show($order, $slug);
    
} else {
    // Некорректный URL - страница 404
    $controller = new LessonController();
    $controller->notFound();
}
