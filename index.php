<?php
require_once 'functions.php';

$conn = connectDB();

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['com'])) {
    $name = trim($_POST['name']);
    $comment = trim($_POST['com']);
    
    if (!empty($name) && !empty($comment)) {
        addReview($conn, $name, $comment);
    }
}

$pizzas = getPizzas($conn);
$contacts = getContacts($conn);
$reviews = getReviews($conn); // Получаем все отзывы

$menu_title = getSectionTitle($conn, 'menu_title');
$about_title = getSectionTitle($conn, 'about_title');
$reviews_title = getSectionTitle($conn, 'reviews_title');
?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantum Pizza - Пицца будущего</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

    <header class="header">
        <div class="container">
            <div class="header__inner">
                <a href="index.php" class="header__logo">Quantum Pizza</a>
                <nav class="header__nav">
                    <a href="#menu" class="header__nav-link">Меню</a>
                    <a href="#about" class="header__nav-link">О нас</a>
                    <a href="#reviews" class="header__nav-link">Отзывы</a>
                </nav>
                <a href="entry.php" class="btn btn-primary">Войти</a>
            </div>
        </div>
    </header>
    <section class="hero" id="home">
        <div class="container">
            <div class="hero__content glass-card">
                <h1 class="hero__title">Quantum Pizza - Пицца будущего уже здесь</h1>
                <p class="hero__subtitle">Испытайте революцию вкуса с нашими квантовыми пиццами, приготовленными с
                    использованием передовых технологий</p>
                <div class="hero__cta">
                    <button class="btn btn-primary">Заказать сейчас</button>
                    <button class="btn btn-secondary">Меню</button>
                </div>
            </div>
        </div>
    </section>
    <section class="section" id="menu">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($menu_title); ?></h2>
            <div class="menu-grid">
                <?php foreach ($pizzas as $pizza): ?>
                    <div class="menu-item glass-card">
                        <img src="img/p1.jpg" alt="<?php echo htmlspecialchars($pizza['name']); ?>"
                            class="menu-item__image">
                        <div class="menu-item__content">
                            <h3 class="menu-item__title"><?php echo htmlspecialchars($pizza['name']); ?></h3>
                            <p class="menu-item__description"><?php echo htmlspecialchars($pizza['description']); ?></p>
                            <div class="menu-item__footer">
                                <span class="menu-item__price">₽<?php echo htmlspecialchars($pizza['price']); ?></span>
                                <button class="btn btn-primary" style="padding: 8px 16px;">В корзину</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section class="section" id="about">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($about_title); ?></h2>
            <div class="about-container">
                <div class="about-image glass-card">
                    <img src="img/pizzeria.jpg" alt="Наша пиццерия">
                </div>
                <div class="about-content glass-card" style="padding: 2rem;">
                    <h2 class="about-title">О Quantum Pizza</h2>
                    <p class="about-text">Мы объединяем лучшие традиции итальянской кухни с передовыми
                        технологиями
                        будущего. Наша миссия - создавать идеальную пиццу, используя научный подход и
                        роботизированное
                        производство.</p>
                    <div class="about-features">
                        <div class="about-feature">
                            <i class="fas fa-atom about-feature__icon"></i>
                            <p class="about-feature__text">Квантовые технологии приготовления</p>
                        </div>
                        <div class="about-feature">
                            <i class="fas fa-leaf about-feature__icon"></i>
                            <p class="about-feature__text">100% органические ингредиенты</p>
                        </div>
                        <div class="about-feature">
                            <i class="fas fa-robot about-feature__icon"></i>
                            <p class="about-feature__text">Автоматизированное производство</p>
                        </div>
                        <div class="about-feature">
                            <i class="fas fa-bolt about-feature__icon"></i>
                            <p class="about-feature__text">Доставка за 15 минут или бесплатно</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="section" id="reviews">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($reviews_title); ?></h2>
            <div class="reviews__content">
                <form class="reviews__form" method="post" action="index.php">
                    <label for="name">Имя:</label>
                    <input type="text" name="name" id="name">
                    <label for="com">Комментарий:</label>
                    <textarea name="com" id="com" rows="5"></textarea>
                    <button type="submit">Отправить</button>
                </form>
            </div>
        </div>
    </section>

    <section class="reviews-list">
        <div class="container">
                <?php if (!empty($reviews)): ?>
                    <h3>Последние отзывы:</h3>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <strong><?php echo htmlspecialchars($review['name']); ?></strong>
                            <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Пока нет отзывов. Будьте первым!</p>
                <?php endif; ?>
                </div>
    </section>        
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    Quantum Pizza
                </div>
                <div class="footer-links">
                    <a href="#menu" class="footer-link">Меню</a>
                    <a href="#about" class="footer-link">О нас</a>
                    <a href="#delivery" class="footer-link">Доставка</a>
                    <a href="#reviews" class="footer-link">Отзывы</a>
                </div>
                <div class="footer-contacts">
                <p>Телефон: <?php echo htmlspecialchars($contacts['phone']); ?></p>
                <p>Email: <?php echo htmlspecialchars($contacts['email']); ?></p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
<?php $conn->close(); ?>