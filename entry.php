<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Войти</title>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <div class="header__logo">
                    <i class="fas fa-pizza-slice header__logo-icon"></i>
                    Quantum Pizza
                </div>
                <nav class="header__nav">
                    <a href="#menu" class="header__nav-link">Меню</a>
                    <a href="#about" class="header__nav-link">О нас</a>
                    <a href="#reviews" class="header__nav-link">Отзывы</a>
                </nav>
                <a href="entry.php" class="btn btn-primary">Войти</a>
            </div>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <section class="section__entry">
                <div class="entry">
                    <h1>Авторизоваться</h1>
                    <form class="login__form" action="entry.php" method="POST">
                        <label for="login">Логин</label>
                        <input id='login' name='login' type="text" placeholder='логин' required>
                        <label for="password">Пароль</label>
                        <input id='password' name='pass' type="password" placeholder='пароль' required>
                        <?php
                        session_start();

                        $login_error = "";

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $login = $_POST["login"];
                            $pass = $_POST["pass"];

                            if ($login == "admin" && $pass == "password") {
                                $_SESSION["loggedin"] = true;
                                $_SESSION["username"] = "admin";
                                header("Location: admin.php");
                                exit();
                            } else {
                                $login_error = "Пользователь не найден";
                            }
                        }
                        ?>
                        <button>Войти</button>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>

</html>