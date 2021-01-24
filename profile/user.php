<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
$uid = -1;
if (!isset($_GET['u']) || !is_numeric($_GET['u'])) {
    $uid = $_SESSION['id'];
}else{
    $uid = $_GET['u'];
}
$u = new wdnUser();
$user = $u->get_user_by_id($uid);
$fk = new formKey();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WDN - Просмотр профиля пользователя <?php echo $user['username'] ?></title>
        <?php wdn::echo_metas(); ?>
    </head>
<?php include_once '../lib/head.php';?>
        <tbody>
            <tr>
                <td colspan="2">
                    <center><h2>WDN - Просмотр профиля пользователя <?php echo $user['username'] ?></h2></center>
                    <form action="index.php?e" method="post">
                        <?php $fk->outputKey(); ?>
                        <input type="hidden" name="wdnURL" value="user.php" />
<?php
                                    $u->show($user);
?>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
<?php
                    if (isset($_SESSION['acl']) && $_SESSION['acl'] == 0){
                        ?>
                    <center><sup><a href='/admin/'>Войти в административный раздел</sup></a></center>
                        <?php
                    }
?>
                </td>
            </tr>
        </tbody>
        </table>
    </body>
</html>
