<?php
include_once 'lib/wdn.php';
if (!isset($_GET['nu'])){
    $_POST['wdnURL'] = "/login.php";
    wdn::redirect("WDN: �������� ������ �����������", 2, "<a href='/login.php'>���������� ��� ���</a><br />���� ��������� � ��������� ������� ����� ����...");
}else{
    wdn::redirect("WDN: ���������������� ������", 2, "�� ��������� � ��������� ������� ����� ����...");
}
?>