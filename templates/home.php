<?php
/**
 * Шаблон главной страницы
 * Отображает список всех уроков в виде кнопок-ссылок
 */
require_once __DIR__ . '/header.php';
?>

    <section class="lessons">
        <div class="lessons__container">
            <h2 class="lessons__title">Уроки</h2>
            <div class="lessons__list">
                <?php if (!empty($lessons)): ?>
                    <?php foreach ($lessons as $lesson): ?>
                        <a href="<?php echo BASE_URL; ?>/<?php echo $lesson['lesson_order']; ?>-<?php echo htmlspecialchars($lesson['slug']); ?>" 
                           class="lesson-card"
                           data-lesson-link>
                            <span class="lesson-card__number"><?php echo $lesson['lesson_order']; ?></span>
                            <span class="lesson-card__title"><?php echo htmlspecialchars($lesson['title_ru']); ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="lessons__empty">Уроки пока не добавлены</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php require_once __DIR__ . '/footer.php'; ?>
