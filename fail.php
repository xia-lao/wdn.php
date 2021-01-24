<?php
include_once 'lib/wdn.php';
if (!isset($_GET['nu'])){
    $_POST['wdnURL'] = "/login.php";
    wdn::redirect("WDN: Неверные данные авторизации", 2, "<a href='/login.php'>Попробуйте ещё раз</a><br />либо вернитесь к просмотру словаря через пару...");
}else{
    wdn::redirect("WDN: Неавторизованный доступ", 2, "Вы вернитесь к просмотру словаря через пару...");
}
?>