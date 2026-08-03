<?php
/**
 * Класс Lesson для работы с уроками в базе данных
 * Предоставляет CRUD операции для уроков
 */

require_once __DIR__ . '/Database.php';

class Lesson {
    /**
     * @var PDO Объект соединения с базой данных
     */
    private $db;
    
    /**
     * Конструктор класса
     * Устанавливает соединение с базой данных
     */
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    /**
     * Получает все опубликованные уроки, отсортированные по порядковому номеру
     * 
     * @return array Массив уроков
     */
    public function getAllLessons() {
        $sql = "SELECT id, title_ru, slug, lesson_order, is_published 
                FROM lessons 
                WHERE is_published = TRUE 
                ORDER BY lesson_order ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Получает все уроки (включая неопубликованные) для админки
     * 
     * @return array Массив всех уроков
     */
    public function getAllLessonsForAdmin() {
        $sql = "SELECT id, title_ru, slug, lesson_order, is_published 
                FROM lessons 
                ORDER BY lesson_order ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Получает урок по порядковому номеру и slug
     * 
     * @param int $order Порядковый номер урока
     * @param string $slug URL-идентификатор урока
     * @return array|false Данные урока или false если не найден
     */
    public function getLessonByOrderAndSlug($order, $slug) {
        $sql = "SELECT * FROM lessons 
                WHERE lesson_order = :order AND slug = :slug AND is_published = TRUE";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order', $order, PDO::PARAM_INT);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Получает урок по ID
     * 
     * @param int $id ID урока
     * @return array|false Данные урока или false если не найден
     */
    public function getLessonById($id) {
        $sql = "SELECT * FROM lessons WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Создает новый урок
     * 
     * @param array $data Данные урока (title_ru, slug, lesson_order, content)
     * @return int ID созданного урока
     */
    public function createLesson($data) {
        $sql = "INSERT INTO lessons (title_ru, slug, lesson_order, content, is_published) 
                VALUES (:title_ru, :slug, :lesson_order, :content, TRUE)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title_ru', $data['title_ru'], PDO::PARAM_STR);
        $stmt->bindParam(':slug', $data['slug'], PDO::PARAM_STR);
        $stmt->bindParam(':lesson_order', $data['lesson_order'], PDO::PARAM_INT);
        $stmt->bindParam(':content', $data['content'], PDO::PARAM_STR);
        $stmt->execute();
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Обновляет существующий урок
     * 
     * @param int $id ID урока
     * @param array $data Данные урока (title_ru, slug, lesson_order, content)
     * @return bool Результат операции
     */
    public function updateLesson($id, $data) {
        $sql = "UPDATE lessons 
                SET title_ru = :title_ru, slug = :slug, lesson_order = :lesson_order, content = :content 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title_ru', $data['title_ru'], PDO::PARAM_STR);
        $stmt->bindParam(':slug', $data['slug'], PDO::PARAM_STR);
        $stmt->bindParam(':lesson_order', $data['lesson_order'], PDO::PARAM_INT);
        $stmt->bindParam(':content', $data['content'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Удаляет урок по ID
     * 
     * @param int $id ID урока
     * @return bool Результат операции
     */
    public function deleteLesson($id) {
        $sql = "DELETE FROM lessons WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Проверяет существование slug
     * 
     * @param string $slug URL-идентификатор
     * @param int|null $excludeId ID урока для исключения (при редактировании)
     * @return bool true если slug существует, false если нет
     */
    public function checkSlugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM lessons WHERE slug = :slug";
        
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        
        if ($excludeId !== null) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        return $count > 0;
    }
    
    /**
     * Проверяет существование названия урока
     * 
     * @param string $title Название урока
     * @param int|null $excludeId ID урока для исключения (при редактировании)
     * @return bool true если название существует, false если нет
     */
    public function checkTitleExists($title, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM lessons WHERE title_ru = :title";
        
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        
        if ($excludeId !== null) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        return $count > 0;
    }
    
    /**
     * Проверяет существование порядкового номера
     * 
     * @param int $order Порядковый номер
     * @param int|null $excludeId ID урока для исключения (при редактировании)
     * @return bool true если номер существует, false если нет
     */
    public function checkOrderExists($order, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM lessons WHERE lesson_order = :order";
        
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order', $order, PDO::PARAM_INT);
        
        if ($excludeId !== null) {
            $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        return $count > 0;
    }
    
    /**
     * Получает предыдущий урок
     * 
     * @param int $currentOrder Текущий порядковый номер
     * @return array|false Данные предыдущего урока или false если нет предыдущего
     */
    public function getPreviousLesson($currentOrder) {
        $sql = "SELECT id, title_ru, slug, lesson_order 
                FROM lessons 
                WHERE lesson_order < :order AND is_published = TRUE 
                ORDER BY lesson_order DESC 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order', $currentOrder, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Получает следующий урок
     * 
     * @param int $currentOrder Текущий порядковый номер
     * @return array|false Данные следующего урока или false если нет следующего
     */
    public function getNextLesson($currentOrder) {
        $sql = "SELECT id, title_ru, slug, lesson_order 
                FROM lessons 
                WHERE lesson_order > :order AND is_published = TRUE 
                ORDER BY lesson_order ASC 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order', $currentOrder, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    }
}
