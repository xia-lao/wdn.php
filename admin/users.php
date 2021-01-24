<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
if (!isset($_SESSION['acl']) || (isset($_SESSION['acl']) && $_SESSION['acl'] <> 0)) {
    $_POST['wdnURL'] = "/login.php";
    wdn::redirect("Неавторизованный доступ", 1, "Перенаправляю на вход...");
}
if (!isset($_GET['do'])) {
?>
<!DOCTYPE html>
<html>
    <head>
        <?php wdn::echo_metas(); ?>
        <title>Админка (Пользователи): <?php echo $_SESSION['username'] ?></title>
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
<!-- cut here to all docs -->
                        <form method='post' action='users.php?do=them'>
                            <input type='hidden' name='wdnURL' value='/admin/users.php' />
                            <table align='center' border='1' cellpadding='2'>
                                <thead>
                                    <tr>
                                        <td>Имя пользователя</td>
                                        <td>Email</td>
                                        <td>ACL</td>
                                        <td>Опции</td>
                                    </tr>
                                </thead>
                                <tbody>
<?php $fk = new formKey(); $fk->outputKey(); //form verification
    $wu = new wdnUser(); $ulist = $wu->get_userlist();
    foreach ($ulist as $u) { ?>
                                    <tr>
                                        <td>Имя<br /><input type='text' name='username[]' value='<?php echo $u['username'] ?>'/></td>
                                        <td>Мыло<br /><input type='text' name='email[]' value='<?php echo $u['email'] ?>' /></td>
                                        <td>Права<br /><input type='text' name='acl[]' value='<?php echo $u['acl'] ?>' /></td>
                                        <td>
                                            <?php if ($u['acl'] >= 0) { ?>
                                            <input type='checkbox' name='remove[]' />Удалить пользователя<br />
                                            <?php } else { ?>
                                            <input type='checkbox' name='approve[]' />Подтвердить пользователя<br />
                                            <?php } ?>
                                            <!--input type='button' name='msg[]' />Написать сообщение пользователю-->
                                        </td>
                                    </tr>
<?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan='3'>
                                            <input type='submit' value='Применить выбранные изменения' />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </td></tr></tbody></table>
    </body>
</html>
<?php
} else { //lets find, what we want to w/our users

} ?>
