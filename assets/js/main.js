0/**
 * Основной JavaScript файл приложения
 * Обрабатывает переключение темы и другие общие функции
 */

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация темы
    initTheme();
    
    // Обработчик переключения темы
    const themeToggle = document.querySelector('[data-theme-toggle]');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
});

/**
 * Инициализация темы при загрузке страницы
 * Проверяет сохраненную тему в localStorage или использует системные настройки
 */
function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    let theme = 'light';
    
    if (savedTheme) {
        theme = savedTheme;
    } else if (systemPrefersDark) {
        theme = 'dark';
    }
    
    applyTheme(theme);
}

/**
 * Переключение темы
 */
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    applyTheme(newTheme);
    localStorage.setItem('theme', newTheme);
}

/**
 * Применение темы к документу
 * @param {string} theme - Название темы ('light' или 'dark')
 */
function applyTheme(theme) {
    const html = document.documentElement;
    const themeIcon = document.querySelector('.header__theme-icon');
    
    html.setAttribute('data-theme', theme);
    
    if (themeIcon) {
        if (theme === 'dark') {
            themeIcon.textContent = '☀️';
        } else {
            themeIcon.textContent = '🌙';
        }
    }
}

/**
 * Плавная прокрутка к элементу
 * @param {string} selector - CSS селектор элемента
 */
function scrollToElement(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Форматирование даты
 * @param {Date} date - Объект даты
 * @param {string} locale - Локаль (по умолчанию 'ru-RU')
 * @returns {string} Отформатированная дата
 */
function formatDate(date, locale = 'ru-RU') {
    return new Date(date).toLocaleDateString(locale, {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

/**
 * Debounce функция для оптимизации обработчиков событий
 * @param {Function} func - Функция для дебаунса
 * @param {number} wait - Время ожидания в миллисекундах
 * @returns {Function} Дебаунсированная функция
 */
function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Проверка поддержки веб-хранилища
 * @returns {boolean} true если поддерживается, false в противном случае
 */
function isStorageSupported() {
    try {
        const test = '__storage_test__';
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
    } catch (e) {
        return false;
    }
}
