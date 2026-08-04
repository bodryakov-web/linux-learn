/**
 * Основной JavaScript файл приложения
 * Обрабатывает переключение темы и другие общие функции
 */

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация темы
    initTheme();
    initLessonCodeCopies();
    
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

/**
 * Добавляет кнопки копирования к фрагментам кода в секции теории уроков
 */
function initLessonCodeCopies() {
    const lessonContent = document.querySelector('.lesson__content');
    if (!lessonContent) {
        return;
    }

    const codeBlocks = lessonContent.querySelectorAll('pre');
    codeBlocks.forEach((block) => {
        if (block.dataset.copyReady === 'true') {
            return;
        }

        block.dataset.copyReady = 'true';
        block.classList.add('lesson-code-block');

        const copyButton = document.createElement('button');
        copyButton.type = 'button';
        copyButton.className = 'lesson-code-block__copy';
        copyButton.textContent = 'Copy';
        copyButton.setAttribute('aria-label', 'Copy code block');

        copyButton.addEventListener('click', async () => {
            const codeElement = block.querySelector('code');
            const code = codeElement ? codeElement.textContent : block.textContent;

            try {
                await navigator.clipboard.writeText(code.trimEnd());
                copyButton.textContent = 'Copied';
                copyButton.classList.add('lesson-code-block__copy--done');
                window.setTimeout(() => {
                    copyButton.textContent = 'Copy';
                    copyButton.classList.remove('lesson-code-block__copy--done');
                }, 1500);
            } catch (error) {
                const selection = window.getSelection();
                const range = document.createRange();

                range.selectNodeContents(block);
                selection.removeAllRanges();
                selection.addRange(range);

                try {
                    document.execCommand('copy');
                    copyButton.textContent = 'Copied';
                    copyButton.classList.add('lesson-code-block__copy--done');
                    window.setTimeout(() => {
                        copyButton.textContent = 'Copy';
                        copyButton.classList.remove('lesson-code-block__copy--done');
                    }, 1500);
                } finally {
                    selection.removeAllRanges();
                }
            }
        });

        block.insertBefore(copyButton, block.firstChild);
    });
}
