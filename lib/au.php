<?php
include_once 'wdn.php';
session_start();
if (!isset($_GET['hash'])){
    db::harakiri($_GET, __FILE__, __LINE__, "");
}else{
    if (!is_numeric($_GET['uid'])){
        db::harakiri($_GET['uid'], __FILE__, __LINE__, "");
    }
}
$db = new db();
$u = new wdnUser();
if (!$u->approve_user($_GET['uid'], $_GET['hash'])){
    echo "<center><h2>��������� ����������� �� �����-�� ������� �� �������!</h2></center>";
    echo "������ ����������������� � ����� ������� ����. �� ���� �������� �� ������ �������� ��������������: ".WDN_ADMIN_EMAIL;
    $db->log_error(__FILE__, __FUNCTION__, __LINE__, "uid='".$_GET['uid']."'%hash='".$_GET['hash']."'");
}else{
    $_POST['wdnURL'] = "/index.php";
    wdn::redirect("���� ����������� ��������!", 3, "������������� � ������...");
}
?>
