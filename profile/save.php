<?php
include_once '../lib/wdn.php';
session_set_cookie_params(604800);
session_start();
$fk = new formKey();
if (!isset($_POST['form_key']) || !$fk->validate()){
    db::harakiri($_POST, __FILE__, __LINE__, "Попытка подсунуть неверные данные!");
}
$u = new wdnUser();
$him = $u->get_user_by_id($_SESSION['id']);
$mask = "[^A-ZА-ЯЁ0-9_\-]";
$_str = $_POST['username'];
if (!preg_match("/$mask/i", $_str)){
    wdn::redirect("Имя пользователя содержит некорректные символы!", 2, "Перенаправляю обратно ...");
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    wdn::redirect("Адрес электронной почты указан неверно!", 2, "Перенаправляю обратно ...");
}
if (strcmp($_POST['pw'], $_POST['pw2']) <> 0){
    wdn::redirect("Пароль не совпадает с подтверждением!", 2, "Перенаправляю обратно ...");
}
if (isset($_POST['additional_data'])){
    $addata = filter_var($_POST['additional_data'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}

if (strcmp($him['email'], $_POST['email']) <> 0){
    $u->update_user($_POST);
    $u->verify_mail($_POST);
    $u->change_acl($_POST['id'], -1);
    wdn::redirect("Вы поменяли адрес электронной почты.", 4, " Ваш аккаунт заблокирован<br />
до подтверждения Вами нового адреса электронной почты.<br />
<b>Проверьте Ваш почтовый ящик ".$_POST['email']."</b> и выполните повторную авторизацию по индивидуальному коду.<br />
Выполняется выход (ждите 4 секунды)...
        ", true);
}else{
    $_POST['id'] = $him['id'];
    if ($u->update_user($_POST)) { //we return to user.php
        $_POST['wdnURL'] = "/profile/user.php";
        wdn::redirect("Ваши данные успешно сохранены", 1, "");
    }else {
        db::harakiri($_POST, __FILE__, __LINE__, "Не удалось сохранить данные пользователя");
    }
}
?>
