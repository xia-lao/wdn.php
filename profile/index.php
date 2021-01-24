<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
if (!isset($_SESSION['username'])) {
    $_POST['wdnURL'] = $_SERVER['HTTP_HOST']."/../../login.php";
    wdn::redirect("����������� ������������", 2, "������� ��� ����� ������, ����������! �������������...");
}
$fk = new formKey();
if (isset($_GET['e'])) {
    if (!$fk->validate()){
        db::harakiri($_POST, __FILE__, __LINE__, "������� ��������� �������� ������!");
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WDN �������������� ������� - <?php echo $_SESSION['username']; ?></title>
        <?php wdn::echo_metas(); ?>
    </head>
<?php include_once '../lib/head.php';
?>
    <tr>
        <td colspan="2">
            <center><h2>WDN �������������� ������� - <?php echo $_SESSION['username']; ?></h2>
                <a href='/request.php?v'>��������� � �������</a>
            </center>
<?php
$u = new wdnUser();
$u->show_user_setup();
?>
        </td>
    </tr>
</table>