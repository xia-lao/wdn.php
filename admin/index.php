<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
if (!isset($_SESSION['acl']) || (isset($_SESSION['acl']) && $_SESSION['acl'] <> 0)) {
    $_POST['wdnURL'] = "/login.php";
    wdn::redirect("Неавторизованный доступ", 1, "Перенаправляю на вход...");
}
?>
<?php ?>
<!DOCTYPE html>
<html>
    <head>
        <?php wdn::echo_metas(); ?>
        <title>Админка - <?php echo $_SESSION['username'] ?></title>
    </head>
        <?php
include_once '../lib/head.php';
        ?>
    <tbody><tr><td colspan="2">
        <table border="1" cellpadding="2" align='center'>
            <thead></thead>
            <tbody>
                <tr>
                    <td align='left'>
                        <fieldset>
                            <legend>Административные опции</legend>
                        </fieldset>
                        <ul>
                            <li><a href='users.php'>Управление пользователями</a></li>
                            <li><a href='words.php'>Непроверенные слова</a></li>
                            <li><a href='errors.php'>Ошибки</a></li>
                            <li><a href='edit.php'>Создать документ (БД)</a></li>
                            <li><a href='docs.php'>Управление документами (БД)</a></li>
                            <li><a>Просмотр файлов (FTP)</a></li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </td></tr></tbody>
</table>
    </body>
</html>
