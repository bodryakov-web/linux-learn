<?php
/**
 * Шаблон страницы отдельного урока
 * Содержит три секции: теория, тесты, задачи и навигацию
 */
require_once __DIR__ . '/header.php';
?>

    <article class="lesson">
        <div class="lesson__container">
            <h1 class="lesson__title"><?php echo htmlspecialchars($lesson['title_ru']); ?></h1>
            
            <!-- Секция теории (без заголовка) -->
            <section class="lesson__section lesson__section--theory">
                <div class="lesson__content">
                    <?php echo $content['theory']; ?>
                </div>
            </section>
            
            <!-- Секция тестов -->
            <?php if (!empty($content['tests'])): ?>
            <section class="lesson__section lesson__section--tests">
                <h2 class="lesson__section-title">Тестирование</h2>
                <div class="tests">
                    <?php foreach ($content['tests'] as $testIndex => $test): ?>
                        <div class="test" data-test="<?php echo $testIndex; ?>">
                            <h3 class="test__question">
                                <?php echo htmlspecialchars($test['question']); ?>
                            </h3>
                            <div class="test__answers">
                                <?php foreach ($test['answers'] as $answerIndex => $answer): ?>
                                    <button class="test__answer" 
                                            data-test="<?php echo $testIndex; ?>" 
                                            data-answer="<?php echo $answerIndex; ?>"
                                            data-correct="<?php echo $test['correct']; ?>">
                                        <?php echo htmlspecialchars($answer); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- Секция задач -->
            <?php if (!empty($content['tasks'])): ?>
            <section class="lesson__section lesson__section--tasks">
                <h2 class="lesson__section-title">Задачи</h2>
                <div class="tasks">
                    <?php foreach ($content['tasks'] as $task): ?>
                        <div class="task">
                            <div class="task__content">
                                <?php echo htmlspecialchars($task['task']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- Навигация между уроками -->
            <nav class="lesson__navigation">
                <?php if ($previousLesson): ?>
                    <a href="<?php echo BASE_URL; ?>/<?php echo $previousLesson['lesson_order']; ?>-<?php echo htmlspecialchars($previousLesson['slug']); ?>" 
                       class="button button--secondary" 
                       data-nav-prev>
                        ← Предыдущий
                    </a>
                <?php endif; ?>
                
                <a href="<?php echo BASE_URL; ?>/" 
                   class="button button--primary" 
                   data-nav-home>
                    В оглавление
                </a>
                
                <?php if ($nextLesson): ?>
                    <a href="<?php echo BASE_URL; ?>/<?php echo $nextLesson['lesson_order']; ?>-<?php echo htmlspecialchars($nextLesson['slug']); ?>" 
                       class="button button--secondary" 
                       data-nav-next>
                        Следующий →
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </article>

<?php require_once __DIR__ . '/footer.php'; ?>
