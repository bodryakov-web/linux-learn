<?php
/**
 * Контроллер для админ-панели
 * Обрабатывает CRUD операции для уроков
 */

require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/AuthController.php';

class AdminController {
    /**
     * @var Lesson Модель урока
     */
    private $lessonModel;
    
    /**
     * @var AuthController Контроллер авторизации
     */
    private $authController;
    
    /**
     * Конструктор класса
     * Инициализирует модели и контроллеры
     */
    public function __construct() {
        $this->lessonModel = new Lesson();
        $this->authController = new AuthController();
    }
    
    /**
     * Отображает главную страницу админки со списком уроков
     */
    public function dashboard() {
        $this->authController->requireAuth();
        $lessons = $this->lessonModel->getAllLessonsForAdmin();
        require_once __DIR__ . '/../templates/admin/dashboard.php';
    }
    
    /**
     * Отображает форму входа в админку
     */
    public function loginForm() {
        // Если уже авторизован, перенаправляем на дашборд
        if ($this->authController->checkAuth()) {
            header('Location: ' . BASE_URL . '/bod');
            exit;
        }
        
        $error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : null;
        unset($_SESSION['login_error']);
        
        require_once __DIR__ . '/../templates/admin/login.php';
    }
    
    /**
     * Обрабатывает форму входа
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if ($this->authController->login($login, $password)) {
                header('Location: ' . BASE_URL . '/bod');
                exit;
            } else {
                $_SESSION['login_error'] = 'Неверный логин или пароль';
                header('Location: ' . BASE_URL . '/bod/login');
                exit;
            }
        }
    }
    
    /**
     * Выходит из админки
     */
    public function logout() {
        $this->authController->logout();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
    
    /**
     * Отображает форму создания нового урока
     */
    public function showCreateForm() {
        $this->authController->requireAuth();
        require_once __DIR__ . '/../templates/admin/lesson_form.php';
    }
    
    /**
     * Отображает форму редактирования урока
     * 
     * @param int $id ID урока
     */
    public function showEditForm($id) {
        $this->authController->requireAuth();
        $lesson = $this->lessonModel->getLessonById($id);
        
        if (!$lesson) {
            $this->notFound();
            return;
        }
        
        // Декодируем JSON контент
        $content = json_decode($lesson['content'], true);
        
        require_once __DIR__ . '/../templates/admin/lesson_form.php';
    }
    
    /**
     * Создает новый урок
     */
    public function store() {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateLessonData();
            
            if (isset($data['errors'])) {
                // Если есть ошибки, возвращаемся к форме с ошибками
                $_SESSION['form_errors'] = $data['errors'];
                $_SESSION['form_data'] = $_POST;
                header('Location: ' . BASE_URL . '/bod/create');
                exit;
            }
            
            // Создаем урок
            $lessonId = $this->lessonModel->createLesson($data);
            
            header('Location: ' . BASE_URL . '/bod');
            exit;
        }
    }
    
    /**
     * Обновляет существующий урок
     * 
     * @param int $id ID урока
     */
    public function update($id) {
        $this->authController->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateLessonData($id);
            
            if (isset($data['errors'])) {
                // Если есть ошибки, возвращаемся к форме с ошибками
                $_SESSION['form_errors'] = $data['errors'];
                $_SESSION['form_data'] = $_POST;
                header('Location: ' . BASE_URL . '/bod/edit/' . $id);
                exit;
            }
            
            // Обновляем урок
            $this->lessonModel->updateLesson($id, $data);
            
            header('Location: ' . BASE_URL . '/bod');
            exit;
        } else {
            // GET запрос - перенаправляем на форму редактирования
            header('Location: ' . BASE_URL . '/bod/edit/' . $id);
            exit;
        }
    }
    
    /**
     * Удаляет урок
     * 
     * @param int $id ID урока
     */
    public function delete($id) {
        $this->authController->requireAuth();
        
        $this->lessonModel->deleteLesson($id);
        
        header('Location: ' . BASE_URL . '/bod');
        exit;
    }
    
    /**
     * Валидирует данные урока перед сохранением
     * 
     * @param int|null $excludeId ID урока для исключения при проверке уникальности
     * @return array Массив данных или ошибок
     */
    private function validateLessonData($excludeId = null) {
        $errors = [];

        $title = trim($_POST['title_ru'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $order = intval($_POST['lesson_order'] ?? 0);
        $theory = $_POST['theory'] ?? '';
        $testsText = $_POST['tests'] ?? '';
        $tasks = $_POST['tasks'] ?? [];

        // Очищаем все строки от некорректных UTF-8 символов
        $title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');
        $slug = mb_convert_encoding($slug, 'UTF-8', 'UTF-8');
        $theory = mb_convert_encoding($theory, 'UTF-8', 'UTF-8');
        $testsText = mb_convert_encoding($testsText, 'UTF-8', 'UTF-8');
        $tasks = array_map(function($task) {
            return mb_convert_encoding($task, 'UTF-8', 'UTF-8');
        }, $tasks);
        
        // Валидация названия
        if (empty($title)) {
            $errors['title_ru'] = 'Название урока обязательно для заполнения';
        } elseif ($this->lessonModel->checkTitleExists($title, $excludeId)) {
            $errors['title_ru'] = 'Урок с таким названием уже существует';
        }
        
        // Валидация slug
        if (empty($slug)) {
            $errors['slug'] = 'Slug обязателен для заполнения';
        } elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
            $errors['slug'] = 'Slug может содержать только маленькие английские буквы, цифры и дефисы';
        } elseif ($this->lessonModel->checkSlugExists($slug, $excludeId)) {
            $errors['slug'] = 'Урок с таким slug уже существует';
        }
        
        // Валидация порядкового номера
        if ($order <= 0) {
            $errors['lesson_order'] = 'Порядковый номер должен быть положительным числом';
        } elseif ($this->lessonModel->checkOrderExists($order, $excludeId)) {
            $errors['lesson_order'] = 'Урок с таким порядковым номером уже существует';
        }
        
        // Валидация теории
        if (empty($theory)) {
            $errors['theory'] = 'Теоретический материал обязателен';
        }
        
        // Если есть ошибки, возвращаем их
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        
        // Преобразуем тесты из текста в JSON
        $tests = $this->parseTestsFromText($testsText);
        
        // Формируем JSON контент урока
        $content = [
            'theory' => $theory,
            'tests' => $tests,
            'tasks' => array_map(function($task) {
                // Очищаем задачу от возможных проблемных символов
                $cleanTask = is_string($task) ? $task : '';
                return ['task' => $cleanTask];
            }, $tasks)
        ];
        
        $jsonContent = json_encode($content, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        
        if ($jsonContent === false || $jsonContent === '') {
            $errors['content'] = 'Ошибка при формировании контента урока: ' . json_last_error_msg();
            return ['errors' => $errors];
        }
        
        return [
            'title_ru' => $title,
            'slug' => $slug,
            'lesson_order' => $order,
            'content' => $jsonContent
        ];
    }
    
    /**
     * Преобразует текстовые тесты в JSON формат
     * Ожидает формат:
     * Вопрос?
     * Ответ1
     * Ответ2 ✓
     * Ответ3
     * Ответ4
     * 
     * @param string $text Текст с тестами
     * @return array Массив тестов в формате JSON
     */
    private function parseTestsFromText($text) {
        $tests = [];
        
        // Очищаем текст от некорректных UTF-8 символов
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        $blocks = preg_split('/\n\s*\n/', trim($text));
        
        foreach ($blocks as $block) {
            $lines = array_filter(array_map('trim', explode("\n", $block)));
            
            if (count($lines) < 5) {
                continue; // Пропускаем некорректные блоки
            }
            
            $question = $lines[0];
            $answers = [];
            $correct = 0;
            
            for ($i = 1; $i <= 4; $i++) {
                $answer = trim($lines[$i]);
                
                // Сначала удаляем галочки (поддерживаем разные варианты)
                // Используем mb_substr для корректной работы с Unicode символами
                if (str_ends_with($answer, '✓') || str_ends_with($answer, '✅') || str_ends_with($answer, '[x]')) {
                    $answer = trim(mb_substr($answer, 0, -1));
                    $correct = $i - 1; // Индекс правильного ответа (0-3)
                }
                // Дополнительная проверка на галочку в начале
                if (str_starts_with($answer, '✓') || str_starts_with($answer, '✅')) {
                    $answer = trim(mb_substr($answer, 1));
                    $correct = $i - 1;
                }
                
                // Очистка от лишних пробелов
                $answer = trim($answer);
                
                $answers[] = $answer;
            }
            
            // Очищаем данные от возможных проблемных символов
            $cleanQuestion = mb_convert_encoding($question, 'UTF-8', 'UTF-8');
            $cleanAnswers = array_map(function($ans) {
                return mb_convert_encoding($ans, 'UTF-8', 'UTF-8');
            }, $answers);
            
            $tests[] = [
                'question' => $cleanQuestion,
                'answers' => $cleanAnswers,
                'correct' => $correct
            ];
        }
        
        return $tests;
    }
    
    /**
     * Отображает страницу 404
     */
    public function notFound() {
        http_response_code(404);
        require_once __DIR__ . '/../templates/404.php';
    }
}
