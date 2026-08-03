<?php
/**
 * Шаблон страницы 404
 * Отображается при обращении к несуществующему URL
 */
require_once __DIR__ . '/header.php';
?>

    <section class="error">
        <div class="error__container">
            <h1 class="error__title">404</h1>
            <p class="error__message">Страница не найдена</p>
            <a href="<?php echo BASE_URL; ?>/" class="button button--primary" data-back-to-home>
                Вернуться на главную
            </a>
        </div>
    </section>

<?php require_once __DIR__ . '/footer.php'; ?>
