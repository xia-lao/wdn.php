<?php
include_once 'lib/wdn.php';
session_set_cookie_params(604800);
session_start();
$fk = new formKey();
if (isset($_GET['l'])){
    if (!isset($_POST['form_key']) || !$fk->validate()){
        db::harakiri($_POST, __FILE__, __LINE__, "Попытка подсунуть неверные данные!");
    }
}
if (isset($_SESSION['username'])){
    $_POST['wdnURL'] = $_SERVER['HTTP_REFERER'];
    wdn::redirect("",0,"");
}else{
    if (isset($_POST['username'])){
        $wdb = new db();
        if (!$user = $wdb->verify_user($_POST['username'], $_POST['password'])){
            header("Location: fail.php?nu");
        }else{
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['acl'] = $user['acl'];
            $_SESSION['id'] = $user['id'];
            wdn::redirect("Здравствуйте, ".$_SESSION['username']."!", 0);
        }
    }else{
?>
<!DOCTYPE html>
<html>
    <head>
<?php wdn::echo_metas(); ?>
        <title>WDN - Вход</title>
    </head>
    <body>
<table align='center'><tr><td align='left'>
    <h1 align="center">Вход в словарь WDN</h1>
    <i>
    <h5>Сессия будет сохранена. Для того, чтобы система Вас забыла, <br />
        нахмите "Выйти" в правом верхнем углу на любой странице</h5>
    </i>
    <form action="login.php?l" method="post">
        <?php $fk->outputKey(); ?>
        <input type="text" name="username" placeholder="Имя пользователя">
        <input type="password" name="password" placeholder="Пароль">
        <input type="submit" value="Войти"><br />
        Забыли пароль? <a href='/profile/recovery.php'>Восстановить пароль</a>
    </form></td></tr>
</table>
    </body>
</html>
<?php
    }
}
?>
