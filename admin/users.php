<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
if (!isset($_SESSION['acl']) || (isset($_SESSION['acl']) && $_SESSION['acl'] <> 0)) {
    $_POST['wdnURL'] = "/login.php";
    wdn::redirect("���������������� ������", 1, "������������� �� ����...");
}
if (!isset($_GET['do'])) {
?>
<!DOCTYPE html>
<html>
    <head>
        <?php wdn::echo_metas(); ?>
        <title>������� (������������): <?php echo $_SESSION['username'] ?></title>
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
                                        <td>��� ������������</td>
                                        <td>Email</td>
                                        <td>ACL</td>
                                        <td>�����</td>
                                    </tr>
                                </thead>
                                <tbody>
<?php $fk = new formKey(); $fk->outputKey(); //form verification
    $wu = new wdnUser(); $ulist = $wu->get_userlist();
    foreach ($ulist as $u) { ?>
                                    <tr>
                                        <td>���<br /><input type='text' name='username[]' value='<?php echo $u['username'] ?>'/></td>
                                        <td>����<br /><input type='text' name='email[]' value='<?php echo $u['email'] ?>' /></td>
                                        <td>�����<br /><input type='text' name='acl[]' value='<?php echo $u['acl'] ?>' /></td>
                                        <td>
                                            <?php if ($u['acl'] >= 0) { ?>
                                            <input type='checkbox' name='remove[]' />������� ������������<br />
                                            <?php } else { ?>
                                            <input type='checkbox' name='approve[]' />����������� ������������<br />
                                            <?php } ?>
                                            <!--input type='button' name='msg[]' />�������� ��������� ������������-->
                                        </td>
                                    </tr>
<?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan='3'>
                                            <input type='submit' value='��������� ��������� ���������' />
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
