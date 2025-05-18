<?php
require_once 'config.php';

// Проверка авторизации
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

function getPizzas($conn) {
    $sql = "SELECT * FROM pizzas";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getContacts($conn) {
    $sql = "SELECT * FROM contacts LIMIT 1";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function getSectionTitle($conn, $section_name) {
    $sql = "SELECT value FROM sections WHERE name = '$section_name'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()['value'];
    } else {
        return "Заголовок не найден";
    }
}

function updateSectionTitle($conn, $section_name, $new_value) {
    $new_value = $conn->real_escape_string($new_value);
    $sql = "UPDATE sections SET value = '$new_value' WHERE name = '$section_name'";
    return $conn->query($sql);
}

function getReviews($conn) {
    $sql = "SELECT * FROM reviews ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function deleteReview($conn, $id) {
    $sql = "DELETE FROM reviews WHERE id = $id";
    return $conn->query($sql);
}

function updateReview($conn, $id, $name, $comment) {
    $name = $conn->real_escape_string($name);
    $comment = $conn->real_escape_string($comment);
    $sql = "UPDATE reviews SET name='$name', comment='$comment' WHERE id=$id";
    return $conn->query($sql);
}

// Обработка действий
$success_message = $error_message = '';
if (isset($_POST['edit_sections'])) {
    $menu_title = $_POST["menu_title"];
    $about_title = $_POST["about_title"];
    $reviews_title = $_POST["reviews_title"];
    if (
        updateSectionTitle($conn, 'menu_title', $menu_title) &&
        updateSectionTitle($conn, 'about_title', $about_title) &&
        updateSectionTitle($conn, 'reviews_title', $reviews_title)
    ) {
        $success_message = "Данные успешно обновлены!";
    } else {
        $error_message = "Ошибка обновления данных.";
    }
}

if (isset($_POST['edit_pizzas'])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $sql = "UPDATE pizzas SET name='$name', description='$description', price='$price' WHERE id=$id";
    if ($conn->query($sql)) {
        $success_message = "Данные пиццы обновлены!";
    } else {
        $error_message = "Ошибка при обновлении данных: " . $conn->error;
    }
}

if (isset($_POST['edit_contacts'])) {
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $sql = "UPDATE contacts SET phone='$phone', email='$email' WHERE id=1";
    if ($conn->query($sql)) {
        $success_message = "Контакты успешно обновлены!";
    } else {
        $error_message = "Ошибка при обновлении контактов: " . $conn->error;
    }
}

if (isset($_POST['delete_review'])) {
    $id = $_POST['review_id'];
    if (deleteReview($conn, $id)) {
        $success_message = "Отзыв удален!";
    } else {
        $error_message = "Ошибка при удалении отзыва: " . $conn->error;
    }
}

if (isset($_POST['update_review'])) {
    $id = $_POST['review_id'];
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    if (updateReview($conn, $id, $name, $comment)) {
        $success_message = "Отзыв обновлён!";
    } else {
        $error_message = "Ошибка при обновлении отзыва: " . $conn->error;
    }
}

// Получение данных
$menu_title = getSectionTitle($conn, 'menu_title');
$about_title = getSectionTitle($conn, 'about_title');
$reviews_title = getSectionTitle($conn, 'reviews_title');
$pizzas = getPizzas($conn);
$contact = getContacts($conn);
$reviews = getReviews($conn);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="styles.css">

        
</head>
<body>
<div class="dashboard-container">
    <h2 class="admin__title">Админ-панель</h2>

    <?php if ($success_message): ?>
        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php elseif ($error_message): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <!-- Редактирование заголовков -->
    <div class="content__form">
        <h3>Редактировать заголовки:</h3>
        <form method="post">
            <label for="menu_title">Заголовок меню:</label>
            <input type="text" id="menu_title" name="menu_title" value="<?= htmlspecialchars($menu_title); ?>">

            <label for="about_title">Заголовок "О нас":</label>
            <input type="text" id="about_title" name="about_title" value="<?= htmlspecialchars($about_title); ?>">

            <label for="reviews_title">Заголовок отзывов:</label>
            <input type="text" id="reviews_title" name="reviews_title" value="<?= htmlspecialchars($reviews_title); ?>">

            <button type="submit" name="edit_sections">Сохранить</button>
        </form>
    </div>

    <!-- Редактирование пицц -->
    <div class="pizzas-container">
        <h3>Редактировать информацию о пиццах</h3>
        <?php foreach ($pizzas as $pizza): ?>
            <div class="pizza-item">
                <form method="post">
                    <input type="hidden" name="id" value="<?= $pizza['id']; ?>">
                    <label>Название:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($pizza['name']); ?>">

                    <label>Описание:</label>
                    <textarea name="description" rows="5"><?= htmlspecialchars($pizza['description']); ?></textarea>

                    <label>Цена:</label>
                    <input type="number" name="price" value="<?= htmlspecialchars($pizza['price']); ?>">

                    <button type="submit" name="edit_pizzas">Сохранить</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Редактирование контактов -->
    <div class="content__form">
        <h3>Редактировать контакты</h3>
        <form method="post">
            <label for="phone">Телефон:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($contact['phone']); ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($contact['email']); ?>">

            <button type="submit" name="edit_contacts">Сохранить</button>
        </form>
    </div>

    <!-- Управление отзывами -->
    <div class="reviews-container">
        <h3>Управление отзывами</h3>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-actions">
                        <button onclick="toggleEditForm(<?= $review['id'] ?>)">Редактировать</button>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                            <button type="submit" name="delete_review">Удалить</button>
                        </form>
                    </div>

                    <strong><?= htmlspecialchars($review['name']) ?></strong>
                    <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                

                    <div id="edit-form-<?= $review['id'] ?>" class="edit-review-form" style="display:none;">
                        <form method="post">
                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                            <label>Имя:</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($review['name']) ?>">
                            <label>Комментарий:</label>
                            <textarea name="comment" rows="3"><?= htmlspecialchars($review['comment']) ?></textarea>
                            <button type="submit" name="update_review">Сохранить</button>
                            <button type="button" onclick="toggleEditForm(<?= $review['id'] ?>)">Отмена</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет отзывов для отображения.</p>
        <?php endif; ?>
    </div>

    <a class="admin__link" href="index.php">Выйти</a>
</div>

<script>
    function toggleEditForm(reviewId) {
        const form = document.getElementById(`edit-form-${reviewId}`);
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }
</script>
</body>
</html>