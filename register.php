<?php 
require_once 'config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    if (empty($login)) $errors[] = 'Введите логин';
    if (empty($full_name)) $errors[] = 'Введите ФИО';
    if (strlen($password) < 6) $errors[] = 'Пароль должен быть от 6 символов';
    if (empty($phone)) $errors[] = 'Введите телефон';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';

    if(empty($errors)) {
        $stmt = $pdo ->prepare("SELECT COUNT(*) FROM users WHERE login = ? OR email = ?");
        $stmt -> execute([$login, $email]);
        if ($stmt -> fetchColumn() > 0) {
            $errors[] = 'Логин или email уже используются';
        }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo -> prepare("INSERT INTO users (login, full_name, password, phone, email) VALUES (?, ?, ?, ?, ?)");
        if ($stmt -> execute([$login, $full_name, $hashed_password, $phone, $email])) {
            $success = 'Регистрация успешна! Данные добавлены в БД';
        } else {
            $errors[] = 'Ошибка при регистрации';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sign Up</title>
</head>
    <body>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4>Регистрация</h4>
                    </div> 
                    <div class="p-4">
                        <?php if($errors): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <div><?= $error ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                                
                        <?php if($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php else: ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Логин</label>
                                <input type="text" name="login" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ФИО</label>
                                <input type="text" name="full_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Пароль</label>
                                <input type="text" name="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Номер Телефона</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Почта</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">Зарегистрироваться</button>
                        </form>
                        <?php endif; ?>
                        <div class="text-center mt-3">
                            <a href="index.php">Войти</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>