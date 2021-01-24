<?php
include_once 'lib/wdn.php';
session_set_cookie_params(604800);
session_start();
$fk = new formKey();
if (isset($_GET['l'])){
    if (!isset($_POST['form_key']) || !$fk->validate()){
        db::harakiri($_POST, __FILE__, __LINE__, "������� ��������� �������� ������!");
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
            wdn::redirect("������������, ".$_SESSION['username']."!", 0);
        }
    }else{
?>
<!DOCTYPE html>
<html>
    <head>
<?php wdn::echo_metas(); ?>
        <title>WDN - ����</title>
    </head>
    <body>
<table align='center'><tr><td align='left'>
    <h1 align="center">���� � ������� WDN</h1>
    <i>
    <h5>������ ����� ���������. ��� ����, ����� ������� ��� ������, <br />
        ������� "�����" � ������ ������� ���� �� ����� ��������</h5>
    </i>
    <form action="login.php?l" method="post">
        <?php $fk->outputKey(); ?>
        <input type="text" name="username" placeholder="��� ������������">
        <input type="password" name="password" placeholder="������">
        <input type="submit" value="�����"><br />
        ������ ������? <a href='/profile/recovery.php'>������������ ������</a>
    </form></td></tr>
</table>
    </body>
</html>
<?php
    }
}
?>
