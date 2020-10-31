<?php
header('Access-Control-Expose-Headers: Access-Control-Allow-Origin', false);
header('Access-Control-Allow-Origin: *', false);
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept', false);
header('Access-Control-Allow-Credentials: true');

session_start(['cookie_lifetime' => 86400,]);

$login = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
$pass = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

$pass = md5("CPT" . $pass . "CPT");

$mysql = new mysqli('localhost:3307', 'root', 'root', 'register-bd');

$result = $mysql->query("SELECT * FROM `users` WHERE `login`='$login' AND `pass`='$pass'");

$user = $result->fetch_assoc();
if (count($user) == 0) {
    echo "Пользователь не найден";
    exit();
}
if (empty($_SESSION['auth'])) {
    $_SESSION['auth'] = true;}

$mysql->close();
header('Location: \APICalk\Calk.php');
?>