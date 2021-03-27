<?php
include "functions//functions.php";
session_start();

$link = connectBD();
if (!empty($_REQUEST['password']) and !empty($_REQUEST['login'])) {
    $login = $_REQUEST['login'];
    $password = $_REQUEST['password'];
    $query = "SELECT * FROM `users` WHERE login=?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $user['id'] = null;
    $user['email'] = null;
    $user['name'] = null;
    $user['login'] = null;
    $user['password'] = null;
    $user['salt'] = null;
    mysqli_stmt_bind_result($stmt, $user['id'], $user['login'], $user['password'], $user['name'], $user['salt'], $user['cookie']);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!empty($user)) {
        $salt = $user['salt'];
        $saltedPassword = md5($password . $salt);
        if ($user['password'] == $saltedPassword) {
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['name'] = $user['name'];
            if (!empty($_REQUEST['remember']) and $_REQUEST['remember'] == 'on') {
                $key = generateSalt();
                setcookie('login', $user['login'], time() + 60 * 60 * 24 * 30);
                setcookie('key', $key, time() + 60 * 60 * 24 * 30);
                $query = "UPDATE `users` SET cookie=? WHERE login=?";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt, "s", $key, $login);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            $url = url();
            header("Location: $url");
        } else {
            global $errorCode;
            $errorCode = 1;
        }
    } else {
        global $errorCode;
        $errorCode = 2;
    }
} else {
}

?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="css/authStyle.css">
    <title>Авторизация</title>
</head>

<body>

    <form method="post" class="auth">
        <div class="auth-login auth-block">
            <label class="auth-login-label auth-form-label" for="login">Логин:</label>
            <input class="auth-login-input auth-form-input" type="text" name="login" id="login" placeholder="Введите логин">
        </div>

        <div class="auth-password auth-block">
            <label class="auth-password-label auth-form-label" for="password">Пароль:</label>
            <input class="auth-password-input auth-form-input" type="password" name="password" id="password" placeholder="Введите пароль">
        </div>

        <div class="auth-submit auth-block">
            <label class="auth-submit-checkbox__label" for="auth-submit-checkbox"><input id="auth-submit-checkbox" class="auth-submit-checkbox" type="checkbox" name="remember">Запомнить меня</input></label>
            <button class="auth-submit-button" type="submit">Войти</button>
        </div>

        <div class="auth-block">
            <?php
            $params = array(
                'client_id'     => '115360450264-0c5bm6ordjpp30h4dalte7aapue6qfq3.apps.googleusercontent.com',
                'redirect_uri'  => url(),
                'response_type' => 'code',
                'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
            );

            $url = 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($params));

            ?>
            <a class="auth-google" href="<?= $url ?>"><img class="auth-google__img" src=" img/google-plus.png" alt=""></a>
        </div>

    </form>
</body>

</html>