<?php
/**
 * Конфигурационный файл приложения
 * Содержит параметры подключения к базе данных и глобальные константы
 */

// Параметры подключения к базе данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'p-351366_linux-learn');
define('DB_USER', 'p-351366_linux-learn');
define('DB_PASS', 'Anna-140275');
define('DB_CHARSET', 'utf8mb4');

// Параметры авторизации в админ-панели
define('ADMIN_LOGIN', 'bodryakov.web');
define('ADMIN_PASSWORD', 'Anna-140275');

// Базовый URL приложения
define('BASE_URL', '/linux-learn');

// Кодировка приложения
define('APP_CHARSET', 'UTF-8');

// Включение отображения ошибок для разработки
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Настройка часового пояса
date_default_timezone_set('Europe/Moscow');
