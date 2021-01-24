<?php
include_once 'wdn.php';
include_once 'recaptchalib.php';
session_start();
$fk = new formKey();
if (!isset($_POST['form_key']) || !$fk->validate()){
    db::harakiri($_POST, __FILE__, __LINE__, "������� ��������� �������� ������!");
}
if (DEBUG == 0) {
    $recresp = recaptcha_check_answer(
            RECAPTCHA_PRIVATE_K,
            $_SERVER['REMOTE_ADDR'],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);
    if (!$recresp->is_valid) {
        wdn::redirect("�������� reCAPTCHA ������� �����������!", 1, "������������� �������...");
    }
}
if (isset($_POST['username'])){
    $mask = "[^A-Z�-ߨ0-9_\-]";
    $_str = $_POST['username'];
    if (!preg_match("/$mask/i", $_str)){
        wdn::redirect("��� ������������ �������� ������������ �������!", 2, "������������� ������� ...");
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        wdn::redirect("����� ����������� ����� ������ �������!", 2, "������������� ������� ...");
    }
    if (strcmp($_POST['pw'], $_POST['pw2']) <> 0){
        wdn::redirect("������ �� ��������� � ��������������!", 2, "������������� ������� ...");
    }
    if (isset($_POST['additional_data'])){
        $addata = filter_var($_POST['additional_data'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    $db = new db();
    $u = new wdnUser();
    if (!$addstatus = $u->add_user($_POST['username'], $_POST['email'], $_POST['pw'], $_POST['additional_data'])){
        $db->log_error(__FILE__, "", __LINE__, $addstatus);
        echo "<center><h2>���-�� ����� �� ���!!!</h2></center>";
        echo "������ �����, �� ��������� ������ ��� �����������.<br />���������� �� ������ ����������������� � ����� �������. <br />�������� ����� ��������� � ���������� �� ������ � ����������� ������!";
    }
}else{
    wdn::redirect("�� �� ������� ����� ������������!", 2, "������������� ������� ...");
}
?>