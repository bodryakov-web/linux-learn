<?php
/**
 * Контроллер для обработки запросов уроков
 * Отображает список уроков и отдельные уроки
 */

require_once __DIR__ . '/../models/Lesson.php';

class LessonController {
    /**
     * @var Lesson Модель урока
     */
    private $lessonModel;
    
    /**
     * Конструктор класса
     * Инициализирует модель урока
     */
    public function __construct() {
        $this->lessonModel = new Lesson();
    }
    
    /**
     * Отображает главную страницу со списком всех уроков
     */
    public function index() {
        $lessons = $this->lessonModel->getAllLessons();
        require_once __DIR__ . '/../templates/home.php';
    }
    
    /**
     * Отображает страницу отдельного урока
     * 
     * @param int $order Порядковый номер урока
     * @param string $slug URL-идентификатор урока
     */
    public function show($order, $slug) {
        $lesson = $this->lessonModel->getLessonByOrderAndSlug($order, $slug);
        
        if (!$lesson) {
            $this->notFound();
            return;
        }
        
        // Получаем предыдущий и следующий уроки для навигации
        $previousLesson = $this->lessonModel->getPreviousLesson($lesson['lesson_order']);
        $nextLesson = $this->lessonModel->getNextLesson($lesson['lesson_order']);
        
        // Декодируем JSON контент урока
        $content = json_decode($lesson['content'], true);
        
        require_once __DIR__ . '/../templates/lesson.php';
    }
    
    /**
     * Отображает страницу 404
     */
    public function notFound() {
        http_response_code(404);
        require_once __DIR__ . '/../templates/404.php';
    }
}
