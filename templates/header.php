<?php
/**
 * Шаблон заголовка для всех страниц
 * Содержит DOCTYPE, head, header с темой и началом body
 */
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Учебный курс по командам терминала Linux">
    <title>Linux Learn - Команды терминала Linux</title>
    
    <!-- Подключение Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Подключение CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <!-- Header с заголовком сайта и кнопкой переключения темы -->
    <header class="header">
        <div class="header__container">
            <h1 class="header__title">Linux Learn</h1>
            <p class="header__subtitle">Команды терминала Linux</p>
            <button class="header__theme-toggle" data-theme-toggle aria-label="Переключить тему">
                <span class="header__theme-icon">🌙</span>
            </button>
        </div>
    </header>
    
    <!-- Основной контент -->
    <main class="main">
