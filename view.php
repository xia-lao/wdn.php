<?php
include_once 'lib/wdn.php';
session_set_cookie_params(604800);
session_start();
if (!isset($_GET['lang'])){
    wdn::redirect("Неопределённое действие", 2, "Перенаправляю обратно...");
    exit();
}else{
    if((strcmp($_GET['lang'], "r") != 0) && (strcmp($_GET['lang'], "w") != 0)){
        db::harakiri("Vncnowne langvage parametre", "", "", "");
    }
}
if (isset($_GET['let']) || isset($_GET['wnum'])){
    if ((isset($_GET['let']) && !is_numeric($_GET['let'])) ||
        (isset($_GET['wnum']) && !is_numeric($_GET['wnum']))){
        db::harakiri("Not numeric letter parameter", "", "", "");
    }
    if (isset($_GET['let']) && $_GET['let'] > 33){//easter egg
        //TODO: easter egg, like show Henig Me'aruthya in white letters on black background
        exit();
    }
    $rus = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";//30, 29, 28 are not used
    $waer = "эыьязаъмюгктвущбоРшлсёржйпхеиВдцн";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <title>WDN - Просмотр</title>
        <?php wdn::echo_metas(); ?>
        <link href="css/wdn.css" type="text/css" rel="stylesheet" />
    </head>
<?php
include_once 'lib/head.php';
$let = "";
$db = new db();
$list = array();
if ($_GET['let'] > 0){
    if (strcmp($_GET['lang'],"r")==0){
        $let = substr($rus, $_GET['let']-1, 1);
        $list = $db->get_by_russ_letter($let);
    }else{
        $let = substr($waer, $_GET['let']-1, 1);
        $list = $db->get_by_waer_hongwa($let);
    }
}elseif (isset($_GET['let']) && $_GET['let'] == 0){//get the whole dictionary by language
    if (strcmp($_GET['lang'], "r")==0){
        $list = $db->get_all_by_russian();
    }else{
        $list = $db->get_all_by_waergowr();
    }
}elseif (isset($_GET['wnum'])){//wnum case or let=negative
    if (!is_numeric($_GET['wnum'])) {//some strange word number or no number at all
        db::harakiri("Strange letter variable '".$_GET['wnum']."'", "", "", "");
    }else{ //show only one word - in waer-russian form
        if (!$list = $db->get_by_wnum($_GET['wnum'])) {
            header("HTTP/1.1 404 Слово с номером (".$_GET['wnum'].") не найдено");
            exit;
        }
    }
}else{
    db::harakiri($_GET, __FILE__, __LINE__, "Strange data in GET");
}
$counter=0;
if (!isset($_GET['wnum'])) {
    foreach ($list as $rec){
        echo "<tr><td>";
        $counter++;
        echo "<b> ".db::wfi("Ц").db::wfi(wdn::waer_int($counter))." ($counter)) </b>";
        if ($delreq = $db->find_if_deletion_requested($rec["wnum"])) {
            $delusr = new wdnUser();
            $delreq['delusr'] = $delusr->get_uid_by_name($delreq['username']);
            echo db::bleed("<sup>Запись была помечена на удаление пользователем ".
                    "<a href='/profile/user.php?u=".$delreq['delusr']."'>".
                    $delreq['username'].
                    "</a></sup>");
        }
        echo "<b><i>";
        if (strcmp($_GET['lang'], "r") ==0) {
            wdn::show_record_ru($rec, true);
        }else{
            wdn::show_record_wa($rec, true);
        }
        echo "<hr align='center' width='78%' /></td></tr>";
    }
}else {
    echo "<tr><td>";
    wdn::show_record_wa($list, true);
    echo "<hr align='center' width='78%' /></td></tr>";
}
?>
        </table>
<?php
}
?>
    </body>
</html>
