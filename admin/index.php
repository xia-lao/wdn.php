<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
if (!isset($_SESSION['acl']) || (isset($_SESSION['acl']) && $_SESSION['acl'] <> 0)) {
    $_POST['wdnURL'] = "/login.php";
    wdn::redirect("���������������� ������", 1, "������������� �� ����...");
}
?>
<?php ?>
<!DOCTYPE html>
<html>
    <head>
        <?php wdn::echo_metas(); ?>
        <title>������� - <?php echo $_SESSION['username'] ?></title>
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
                            <legend>���������������� �����</legend>
                        </fieldset>
                        <ul>
                            <li><a href='users.php'>���������� ��������������</a></li>
                            <li><a href='words.php'>������������� �����</a></li>
                            <li><a href='errors.php'>������</a></li>
                            <li><a href='edit.php'>������� �������� (��)</a></li>
                            <li><a href='docs.php'>���������� ����������� (��)</a></li>
                            <li><a>�������� ������ (FTP)</a></li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </td></tr></tbody>
</table>
    </body>
</html>
