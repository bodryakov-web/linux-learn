/**
 * JavaScript файл для обработки тестов
 * Обрабатывает клики на ответы и показывает правильность/неправильность
 */

document.addEventListener('DOMContentLoaded', function() {
    // Находим все кнопки ответов
    const answerButtons = document.querySelectorAll('[data-answer]');
    
    // Добавляем обработчик клика на каждый ответ
    answerButtons.forEach(button => {
        button.addEventListener('click', handleAnswerClick);
    });
});

/**
 * Обработчик клика на ответ
 * @param {Event} event - Событие клика
 */
function handleAnswerClick(event) {
    const button = event.currentTarget;
    const testContainer = button.closest('[data-test]');
    
    // Проверяем, уже ли был дан ответ на этот вопрос
    if (testContainer.hasAttribute('data-answered')) {
        return; // Если уже ответили, не делаем ничего
    }
    
    // Получаем данные ответа
    const answerIndex = parseInt(button.getAttribute('data-answer'));
    const correctIndex = parseInt(button.getAttribute('data-correct'));
    
    // Помечаем тест как отвеченный
    testContainer.setAttribute('data-answered', 'true');
    
    // Отключаем все кнопки ответов в этом тесте
    const allAnswers = testContainer.querySelectorAll('[data-answer]');
    allAnswers.forEach(answerButton => {
        answerButton.disabled = true;
        answerButton.style.cursor = 'not-allowed';
    });
    
    // Проверяем правильность ответа
    if (answerIndex === correctIndex) {
        // Правильный ответ
        button.setAttribute('data-answered', 'correct');
    } else {
        // Неправильный ответ
        button.setAttribute('data-answered', 'incorrect');
        
        // Подсвечиваем правильный ответ
        const correctButton = testContainer.querySelector(`[data-answer="${correctIndex}"]`);
        if (correctButton) {
            correctButton.setAttribute('data-answered', 'correct');
        }
    }
    
    // Добавляем анимацию
    button.style.transform = 'scale(1.02)';
    setTimeout(() => {
        button.style.transform = 'scale(1)';
    }, 200);
}

/**
 * Сброс всех тестов (для тестирования)
 * Эта функция может быть использована для сброса состояния тестов
 */
function resetAllTests() {
    const testContainers = document.querySelectorAll('[data-test]');
    
    testContainers.forEach(container => {
        container.removeAttribute('data-answered');
        
        const answers = container.querySelectorAll('[data-answer]');
        answers.forEach(answer => {
            answer.removeAttribute('data-answered');
            answer.disabled = false;
            answer.style.cursor = 'pointer';
            answer.style.transform = 'none';
        });
    });
}

/**
 * Получение статистики по тестам
 * @returns {Object} Объект со статистикой (всего, правильно, неправильно)
 */
function getTestStatistics() {
    const testContainers = document.querySelectorAll('[data-test]');
    let total = 0;
    let correct = 0;
    let incorrect = 0;
    
    testContainers.forEach(container => {
        if (container.hasAttribute('data-answered')) {
            total++;
            
            const correctAnswer = container.querySelector('[data-answered="correct"]');
            const incorrectAnswer = container.querySelector('[data-answered="incorrect"]');
            
            if (correctAnswer && !incorrectAnswer) {
                correct++;
            } else if (incorrectAnswer) {
                incorrect++;
            }
        }
    });
    
    return {
        total,
        correct,
        incorrect,
        percentage: total > 0 ? Math.round((correct / total) * 100) : 0
    };
}
