<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
if (!isset($_SESSION['username'])) {
    $_POST['wdnURL'] = $_SERVER['HTTP_HOST']."/../../login.php";
    wdn::redirect("Неизвестный пользователь", 2, "Войдите под своим именем, пожалуйста! Перенаправляю...");
}
$fk = new formKey();
if (isset($_GET['e'])) {
    if (!$fk->validate()){
        db::harakiri($_POST, __FILE__, __LINE__, "Попытка подсунуть неверные данные!");
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WDN Редактирование профиля - <?php echo $_SESSION['username']; ?></title>
        <?php wdn::echo_metas(); ?>
    </head>
<?php include_once '../lib/head.php';
?>
    <tr>
        <td colspan="2">
            <center><h2>WDN Редактирование профиля - <?php echo $_SESSION['username']; ?></h2>
                <a href='/request.php?v'>Вернуться к словарю</a>
            </center>
<?php
$u = new wdnUser();
$u->show_user_setup();
?>
        </td>
    </tr>
</table>