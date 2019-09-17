<?php
include __DIR__."/core.php"; 	// Тут мы подключаем все необходимое и
// проверяем залогинен ли пользователь:
// если нет — выводим окошко авторизации и дальше не идем


unset($_SESSION['user']);
session_destroy();
header("Location: ../index.php");

?>
