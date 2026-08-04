<?php
/**
 * Шаблон главной страницы админки
 * Отображает список всех уроков с кнопками действий
 */
require_once __DIR__ . '/admin_header.php';
?>

    <section class="admin-dashboard">
        <div class="admin-dashboard__container">
            <div class="admin-dashboard__header">
                <h2 class="admin-dashboard__title">Управление уроками</h2>
                <a href="<?php echo BASE_URL; ?>/bod/create" 
                   class="button button--primary" 
                   data-create-lesson
                   title="Создать новый урок">
                    + Создать урок
                </a>
            </div>
            
            <?php if (!empty($lessons)): ?>
            <div class="admin-dashboard__lessons">
                <table class="lessons-table">
                    <thead class="lessons-table__head">
                        <tr class="lessons-table__row">
                            <th class="lessons-table__header lessons-table__header--order">№</th>
                            <th class="lessons-table__header lessons-table__header--title">Название</th>
                            <th class="lessons-table__header lessons-table__header--slug">Slug</th>
                            <th class="lessons-table__header lessons-table__header--status">Статус</th>
                            <th class="lessons-table__header lessons-table__header--actions">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="lessons-table__body">
                        <?php foreach ($lessons as $lesson): ?>
                        <tr class="lessons-table__row">
                            <td class="lessons-table__cell">
                                <?php echo $lesson['lesson_order']; ?>
                            </td>
                            <td class="lessons-table__cell">
                                <?php echo htmlspecialchars($lesson['title_ru']); ?>
                            </td>
                            <td class="lessons-table__cell">
                                <?php echo htmlspecialchars($lesson['slug']); ?>
                            </td>
                            <td class="lessons-table__cell">
                                <?php if ($lesson['is_published']): ?>
                                    <span class="status status--published">Опубликован</span>
                                <?php else: ?>
                                    <span class="status status--draft">Черновик</span>
                                <?php endif; ?>
                            </td>
                            <td class="lessons-table__cell lessons-table__cell--actions">
                                <a href="<?php echo BASE_URL; ?>/bod/edit/<?php echo $lesson['id']; ?>" 
                                   class="button button--small button--secondary"
                                   data-edit-lesson
                                   data-lesson-id="<?php echo $lesson['id']; ?>"
                                   title="Редактировать урок">
                                    Редактировать
                                </a>
                                <a href="<?php echo BASE_URL; ?>/bod/delete/<?php echo $lesson['id']; ?>" 
                                   class="button button--small button--danger"
                                   data-delete-lesson
                                   data-lesson-id="<?php echo $lesson['id']; ?>"
                                   title="Удалить урок">
                                    Удалить
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="admin-dashboard__empty">
                <p>Уроки пока не созданы</p>
                <a href="<?php echo BASE_URL; ?>/bod/create" class="button button--primary">
                    Создать первый урок
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

</main>
</body>
</html>
