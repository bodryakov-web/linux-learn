<?php
/**
 * Шаблон формы создания/редактирования урока
 * Содержит CKEditor 5 для редактирования контента
 */
require_once __DIR__ . '/admin_header.php';

// Определяем режим: создание или редактирование
$isEdit = isset($lesson);
$lessonData = $isEdit ? $lesson : null;
$content = $isEdit && isset($content) ? $content : null;

// Получаем ошибки из сессии если есть
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_errors']);
unset($_SESSION['form_data']);

// Если есть данные из сессии, используем их
if (!empty($formData)) {
    $lessonData = [
        'title_ru' => $formData['title_ru'] ?? '',
        'slug' => $formData['slug'] ?? '',
        'lesson_order' => $formData['lesson_order'] ?? 1
    ];
    
    // Правильно формируем массив задач
    $tasksData = $formData['tasks'] ?? [''];
    $tasksArray = [];
    foreach ($tasksData as $taskText) {
        $tasksArray[] = ['task' => $taskText];
    }
    
    $content = [
        'theory' => $formData['theory'] ?? '',
        'tests' => '',
        'tasks' => $tasksArray
    ];
}
?>

    <section class="admin-lesson-form">
        <div class="admin-lesson-form__container">
            <h2 class="admin-lesson-form__title">
                <?php echo $isEdit ? 'Редактирование урока' : 'Создание нового урока'; ?>
            </h2>
            
            <?php if (!empty($errors)): ?>
            <div class="admin-lesson-form__errors">
                <p>Исправьте следующие ошибки:</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <form class="lesson-form" 
                  action="<?php echo $isEdit ? BASE_URL . '/bod/update/' . $lessonData['id'] : BASE_URL . '/bod/store'; ?>" 
                  method="POST" 
                  data-lesson-form>
                
                <!-- Порядковый номер -->
                <div class="form__group">
                    <label class="form__label" for="lesson_order">
                        Порядковый номер *
                    </label>
                    <input type="number" 
                           id="lesson_order" 
                           name="lesson_order" 
                           class="form__input form__input--number"
                           value="<?php echo $isEdit ? $lessonData['lesson_order'] : ''; ?>"
                           required
                           min="1">
                    <?php if (isset($errors['lesson_order'])): ?>
                        <span class="form__error"><?php echo htmlspecialchars($errors['lesson_order']); ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Название урока -->
                <div class="form__group">
                    <label class="form__label" for="title_ru">
                        Название урока *
                    </label>
                    <input type="text" 
                           id="title_ru" 
                           name="title_ru" 
                           class="form__input"
                           value="<?php echo $isEdit ? htmlspecialchars($lessonData['title_ru']) : ''; ?>"
                           required>
                    <?php if (isset($errors['title_ru'])): ?>
                        <span class="form__error"><?php echo htmlspecialchars($errors['title_ru']); ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Slug -->
                <div class="form__group">
                    <label class="form__label" for="slug">
                        Slug (только маленькие буквы и дефисы) *
                    </label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           class="form__input"
                           value="<?php echo $isEdit ? htmlspecialchars($lessonData['slug']) : ''; ?>"
                           pattern="[a-z0-9\-]+"
                           required>
                    <?php if (isset($errors['slug'])): ?>
                        <span class="form__error"><?php echo htmlspecialchars($errors['slug']); ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Теория с CKEditor -->
                <div class="form__group">
                    <label class="form__label" for="theory">
                        Теоретический материал *
                    </label>
                    <textarea id="theory" 
                              name="theory" 
                              class="form__textarea form__textarea--editor"><?php echo $isEdit && $content ? htmlspecialchars($content['theory']) : ''; ?></textarea>
                    <?php if (isset($errors['theory'])): ?>
                        <span class="form__error"><?php echo htmlspecialchars($errors['theory']); ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Тесты -->
                <div class="form__group">
                    <label class="form__label" for="tests">
                        Тесты (вставьте текст из RTF)
                    </label>
                    <textarea id="tests" 
                              name="tests" 
                              class="form__textarea form__textarea--tests"
                              placeholder="Вопрос?
Ответ1
Ответ2 ✓
Ответ3
Ответ4

Вопрос2?
..."></textarea>
                    <p class="form__help">
                        Формат: вопрос на первой строке, затем 4 варианта ответа (один с галочкой ✓), блоки разделены пустой строкой
                    </p>
                </div>
                
                <!-- Задачи -->
                <div class="form__group">
                    <label class="form__label">Задачи</label>
                    <div class="tasks-list" data-tasks-list>
                        <?php 
                        $tasksList = $isEdit && $content && isset($content['tasks']) ? $content['tasks'] : [['task' => '']];
                        foreach ($tasksList as $index => $task): 
                        ?>
                        <div class="tasks-list__item" data-task-item>
                            <textarea name="tasks[]" 
                                      class="form__textarea form__textarea--task"
                                      placeholder="Условие задачи"><?php echo $isEdit && $content ? strip_tags($task['task']) : ''; ?></textarea>
                            <button type="button" 
                                    class="button button--small button--danger" 
                                    data-remove-task
                                    <?php echo $index === 0 ? 'style="display:none;"' : ''; ?>>
                                Удалить
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" 
                            class="button button--small button--secondary" 
                            data-add-task>
                        + Добавить задачу
                    </button>
                </div>
                
                <!-- Кнопки действий -->
                <div class="form__actions">
                    <a href="<?php echo BASE_URL; ?>/bod" class="button button--secondary">
                        Отмена
                    </a>
                    <button type="submit" class="button button--primary">
                        Опубликовать
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Подключение CKEditor 5 (CDN версия) -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
    <script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
        }
    }
    </script>
    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Paragraph,
            Bold,
            Italic,
            CodeBlock,
            Code,
            Link,
            Table,
            TableToolbar,
            FontSize
        } from 'ckeditor5';

        let editor;

        ClassicEditor
            .create(document.querySelector('#theory'), {
                plugins: [Essentials, Paragraph, Bold, Italic, CodeBlock, Code, Link, Table, TableToolbar, FontSize],
                toolbar: ['codeBlock', '|', 'code', '|', 'bold', 'italic', '|', 'fontSize', '|', 'link', '|', 'insertTable'],
                language: 'ru',
                placeholder: 'Введите теоретический материал...'
            })
            .then(createdEditor => {
                editor = createdEditor;

                // Обновляем textarea перед отправкой формы
                const form = document.querySelector('[data-lesson-form]');
                const textarea = document.querySelector('#theory');
                
                form.addEventListener('submit', function(e) {
                    // Принудительно обновляем textarea из редактора
                    editor.updateSourceElement();
                    
                    // Дополнительная проверка: если textarea пустой, пробуем получить данные напрямую
                    if (!textarea.value.trim()) {
                        textarea.value = editor.getData();
                    }
                    
                    // Валидация: теоретический материал не может быть пустым
                    if (!textarea.value.trim()) {
                        e.preventDefault();
                        alert('Теоретический материал не может быть пустым');
                        return false;
                    }
                    
                    // Отладочная информация
                    console.log('Theory content length:', textarea.value.length);
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    
    <!-- JavaScript для управления задачами -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tasksList = document.querySelector('[data-tasks-list]');
            const addTaskButton = document.querySelector('[data-add-task]');
            
            // Добавление новой задачи
            addTaskButton.addEventListener('click', function() {
                const taskItem = document.createElement('div');
                taskItem.className = 'tasks-list__item';
                taskItem.setAttribute('data-task-item', '');
                taskItem.innerHTML = `
                    <textarea name="tasks[]" class="form__textarea form__textarea--task" placeholder="Условие задачи"></textarea>
                    <button type="button" class="button button--small button--danger" data-remove-task>Удалить</button>
                `;
                tasksList.appendChild(taskItem);
                
                // Показываем кнопку удаления у всех задач
                document.querySelectorAll('[data-remove-task]').forEach(btn => {
                    btn.style.display = 'inline-block';
                });
            });
            
            // Удаление задачи
            tasksList.addEventListener('click', function(e) {
                if (e.target.hasAttribute('data-remove-task')) {
                    const taskItem = e.target.closest('[data-task-item]');
                    const allTasks = tasksList.querySelectorAll('[data-task-item]');
                    
                    if (allTasks.length > 1) {
                        taskItem.remove();
                    }
                    
                    // Скрываем кнопку удаления если осталась одна задача
                    if (allTasks.length === 2) {
                        document.querySelectorAll('[data-remove-task]').forEach(btn => {
                            btn.style.display = 'none';
                        });
                    }
                }
            });
        });
    </script>

</main>
</body>
</html>
