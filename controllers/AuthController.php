<?php
/**
 * Контроллер авторизации для админ-панели
 * Обрабатывает вход, выход и проверку авторизации
 */

class AuthController {
    /**
     * Запускает сессию
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Обрабатывает попытку входа в админ-панель
     * Проверяет логин и пароль с жестко прописанными значениями
     * 
     * @param string $login Логин пользователя
     * @param string $password Пароль пользователя
     * @return bool true если авторизация успешна, false в противном случае
     */
    public function login($login, $password) {
        // Проверка логина и пароля с константами из config.php
        if ($login === ADMIN_LOGIN && $password === ADMIN_PASSWORD) {
            $_SESSION['is_admin'] = true;
            return true;
        }
        
        return false;
    }
    
    /**
     * Выходит из админ-панели
     * Удаляет флаг авторизации из сессии
     */
    public function logout() {
        unset($_SESSION['is_admin']);
        session_destroy();
    }
    
    /**
     * Проверяет, авторизован ли пользователь как админ
     * 
     * @return bool true если пользователь авторизован как админ, false в противном случае
     */
    public function checkAuth() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }
    
    /**
     * Перенаправляет на форму входа, если пользователь не авторизован
     */
    public function requireAuth() {
        if (!$this->checkAuth()) {
            header('Location: ' . BASE_URL . '/bod/login');
            exit;
        }
    }
}
