<?php
include_once 'wdn.php';
include_once 'recaptchalib.php';
session_start();
$fk = new formKey();
if (!isset($_POST['form_key']) || !$fk->validate()){
    db::harakiri($_POST, __FILE__, __LINE__, "Попытка подсунуть неверные данные!");
}
if (DEBUG == 0) {
    $recresp = recaptcha_check_answer(
            RECAPTCHA_PRIVATE_K,
            $_SERVER['REMOTE_ADDR'],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);
    if (!$recresp->is_valid) {
        wdn::redirect("Значение reCAPTCHA введено неправильно!", 1, "Перенаправляю обратно...");
    }
}
if (isset($_POST['username'])){
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
    $db = new db();
    $u = new wdnUser();
    if (!$addstatus = $u->add_user($_POST['username'], $_POST['email'], $_POST['pw'], $_POST['additional_data'])){
        $db->log_error(__FILE__, "", __LINE__, $addstatus);
        echo "<center><h2>Что-то пошло не так!!!</h2></center>";
        echo "Данные верны, но произошла ошибка при регистрации.<br />Информация об ошибке запротоколирована и будет изучена. <br />Приносим нашим извинения и благодарим за помощь в исправлении ошибки!";
    }
}else{
    wdn::redirect("Вы не указали имени пользователя!", 2, "Перенаправляю обратно ...");
}
?>