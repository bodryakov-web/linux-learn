<?php
/**
 * Шаблон подвала для всех страниц
 * Содержит footer с адаптивным текстом и подключение JavaScript
 */
?>
    </main>
    
    <!-- Footer с адаптивным текстом copyright -->
    <footer class="footer">
        <div class="footer__container">
            <p class="footer__text">Linux Learn</p>
            <p class="footer__text footer__text--medium">2026 | Linux Learn | Автор: преподаватель ГТК - Бодряков А.Ю.</p>
            <p class="footer__text footer__text--large">2026 | Linux Learn - Учебный курс по командам терминала Linux | Автор: преподаватель ГТК - Бодряков А.Ю.</p>
        </div>
    </footer>
    
    <!-- Подключение JavaScript -->
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/tests.js"></script>
</body>
</html>
