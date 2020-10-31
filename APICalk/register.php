<?php
$login = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
$pass = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

if (mb_strlen($login) < 1 || mb_strlen($login) > 90) {
    echo "Недопустимая длинна логина";
    exit();
} elseif ((mb_strlen($pass) < 1 || mb_strlen($pass) > 16)) {
    echo "Недопустимая длинна пароля";
    exit();
}

$pass = md5("CPT".$pass."CPT");

$mysql = new mysqli('localhost:3307', 'root', 'root', 'register-bd');
$mysql->query("INSERT INTO `users` (`login`, `pass`)
    VALUES('$login','$pass')");
$mysql->close();
header('Location: /');
?>