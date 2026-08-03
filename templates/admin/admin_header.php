<?php
/**
 * Шаблон заголовка для админ-панели
 * Содержит специфичный header для админки со ссылкой на выход
 */
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Linux Learn</title>
    
    <!-- Подключение Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Подключение CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body class="body--admin">
    <!-- Header для админки -->
    <header class="header header--admin">
        <div class="header__container">
            <h1 class="header__title">Админ-панель</h1>
        </div>
    </header>
    
    <!-- Основной контент админки -->
    <main class="main main--admin">
