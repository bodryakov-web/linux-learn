<?php
/**
 * Класс Database для подключения к базе данных MySQL через PDO
 * Предоставляет метод для получения соединения с базой данных
 */

class Database {
    /**
     * @var PDO|null Экземпляр соединения с базой данных
     */
    private static $connection = null;
    
    /**
     * Получает соединение с базой данных
     * Использует паттерн Singleton для одного соединения на всё приложение
     * 
     * @return PDO Объект соединения с базой данных
     * @throws PDOException При ошибке подключения
     */
    public static function getConnection() {
        // Если соединение уже установлено, возвращаем его
        if (self::$connection !== null) {
            return self::$connection;
        }
        
        try {
            // Формирование DSN строки для подключения
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            // Создание нового соединения PDO
            self::$connection = new PDO($dsn, DB_USER, DB_PASS);
            
            // Настройка атрибутов PDO
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            return self::$connection;
            
        } catch (PDOException $e) {
            // Логирование ошибки подключения
            error_log("Ошибка подключения к базе данных: " . $e->getMessage());
            // Вывод детальной ошибки для отладки
            throw new PDOException("Не удалось подключиться к базе данных: " . $e->getMessage());
        }
    }
    
    /**
     * Закрывает соединение с базой данных
     * Устанавливает соединение в null для повторного использования при необходимости
     */
    public static function closeConnection() {
        self::$connection = null;
    }
}
