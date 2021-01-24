<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
$fk = new formKey();
if (isset($_POST['form_key'])) { //there was an attempt
  if (!$fk->validate()){
      db::harakiri($_POST, __FILE__, __LINE__, "Попытка подсунуть неверные данные!");
  }
  $_POST['wdnURL'] = "/profile/recovery.php";
  $u = new wdnUser();
  $un = 1;
  if (isset($_POST['username'])){
    $uid = $u->get_uid_by_name($_POST['username']);
  }elseif (isset($_POST['email'])) {
    $uid = $u->get_uid_by_email($_POST['email']);
  }else{
    wdn::redirect("Укажите имя или адрес электронной почты", 2);
  }
  if (!$uid) {
    wdn::redirect("Пользователь с такими данными не найден", 2);
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
    Пароль пользователя <?php echo $udata['username'] ?> изменён на <b><?php echo $udata['pw'] ?></b>.<br />
    Запомните этот пароль, он не будет более отображён, для изменения пароля на собственный зайдите с указанным паролем в профиль пользователя.
    <a href='/login.php'>Войти на сайт WDN</a>
    <?php
  }
}else{ //new attempt
?>
  <!DOCTYPE html>
  <html>
      <head>
  <?php wdn::echo_metas(); ?>
          <title>WDN - Восстановление пароля</title>
      </head>
      <body>
  <table align='center'><tr><td align='left'>
      <h1 align="center">Восстановление пароля WDN</h1>
      <form action="recovery.php" method="post">
          Введите <input type="text" size='25' name="username" placeholder="Имя пользователя"> или
          <input type="text" size='25' name="email" placeholder="Адрес электронной почты">
          <input type="submit" value="Напомнить"><br />
          <?php $fk->outputKey(); ?>
      </form></td></tr>
  </table>
      </body>
  </html>
<?php
}
?>