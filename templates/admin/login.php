<?php
/**
 * Шаблон формы входа в админ-панель
 */
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель - Linux Learn</title>
    
    <!-- Подключение Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Подключение CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <main class="main main--login">
        <section class="login">
            <div class="login__container">
                <h1 class="login__title">Вход в админ-панель</h1>
                
                <?php if ($error): ?>
                    <div class="login__error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form class="login__form" action="<?php echo BASE_URL; ?>/bod/login" method="POST" data-login-form>
                    <div class="form__group">
                        <label class="form__label" for="login">Логин</label>
                        <input type="text" 
                               id="login" 
                               name="login" 
                               class="form__input" 
                               required 
                               autocomplete="username">
                    </div>
                    
                    <div class="form__group">
                        <label class="form__label" for="password">Пароль</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form__input" 
                               required 
                               autocomplete="current-password">
                    </div>
                    
                    <button type="submit" class="button button--primary button--full-width">
                        Войти
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
