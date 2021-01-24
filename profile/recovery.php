<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
$fk = new formKey();
if (isset($_POST['form_key'])) { //there was an attempt
  if (!$fk->validate()){
      db::harakiri($_POST, __FILE__, __LINE__, "������� ��������� �������� ������!");
  }
  $_POST['wdnURL'] = "/profile/recovery.php";
  $u = new wdnUser();
  $un = 1;
  if (isset($_POST['username'])){
    $uid = $u->get_uid_by_name($_POST['username']);
  }elseif (isset($_POST['email'])) {
    $uid = $u->get_uid_by_email($_POST['email']);
  }else{
    wdn::redirect("������� ��� ��� ����� ����������� �����", 2);
  }
  if (!$uid) {
    wdn::redirect("������������ � ������ ������� �� ������", 2);
  }else{
    $udata = $u->get_user_by_id($uid);
    $udata['pw'] = wdnUser::generatePassword(8);
    $u->update_user($udata);
    //zero the session
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    //end zero the session
    ?>
    ������ ������������ <?php echo $udata['username'] ?> ������ �� <b><?php echo $udata['pw'] ?></b>.<br />
    ��������� ���� ������, �� �� ����� ����� ��������, ��� ��������� ������ �� ����������� ������� � ��������� ������� � ������� ������������.
    <a href='/login.php'>����� �� ���� WDN</a>
    <?php
  }
}else{ //new attempt
?>
  <!DOCTYPE html>
  <html>
      <head>
  <?php wdn::echo_metas(); ?>
          <title>WDN - �������������� ������</title>
      </head>
      <body>
  <table align='center'><tr><td align='left'>
      <h1 align="center">�������������� ������ WDN</h1>
      <form action="recovery.php" method="post">
          ������� <input type="text" size='25' name="username" placeholder="��� ������������"> ���
          <input type="text" size='25' name="email" placeholder="����� ����������� �����">
          <input type="submit" value="���������"><br />
          <?php $fk->outputKey(); ?>
      </form></td></tr>
  </table>
      </body>
  </html>
<?php
}
?>