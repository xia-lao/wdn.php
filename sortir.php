<?php
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
include_once 'lib/wdn.php';
$_POST['wdnURL'] = "/index.php";
wdn::redirect("Спасибо за Ваше внимание!", 1, "Перенаправляю обратно...");
?>
