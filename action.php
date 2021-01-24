<?php
include_once 'lib/wdn.php';
wdn::echo_metas();
session_set_cookie_params(604800);
session_start();
$db = new db();
$fk = new formKey();
if (!isset($_POST['form_key']) || !$fk->validate()){
    db::harakiri($_POST, __FILE__, __LINE__, "Попытка подсунуть неверные данные!");
}
if (!isset($_GET['action'])){
    $_POST['wdnURL'] = $_SERVER['HTTP_REFERER']; //whatever this will mean, no access here without parameters
    wdn::redirect("Никаких действий запрошено не было!", 2);
}else{
    if (strcmp($_GET['action'], "d") == 0){ //deletion request
        if (! $res = $db->remove_rec($_POST['wnum'])) {
            $db->log_error(__FILE__, "", __LINE__, "Deletion request for ".$_POST['wnum']);
        }else{
            wdn::redirect("Удаление/запрос на удаление: действие выполнено в соответствии с Вашим уровнем доступа", 2);
        }
    }elseif ((strcmp($_GET['action'], "dd") == 0)){ //delete document

      if (!$res = $db->remove_doc($_POST['document_id'])) {
            $db->log_error(__FILE__, "", __LINE__, "Deletion request for ".$_POST['wnum']);
        }else{
            wdn::redirect("Удаление/запрос на удаление: действие выполнено в соответствии с Вашим уровнем доступа", 2);
      }
    }elseif ((strcmp($_GET['action'], "u") == 0) || (strcmp($_GET['action'], "a") == 0)){ //update or addition requests
        if (isset($_POST['wonder'])) {$record['acl'] = 2;}
        else{$record['acl'] = $_SESSION['acl'];}
        $record['word_wa'] = $_POST['word_wa'];
        $record['word_ru'] = $_POST['word_ru'];
        if (is_numeric($_POST['wnum'])) {$record['wnum'] = $_POST['wnum'];}
        $record['syns_ru'] = $_POST['syns_ru'];
        $record['syns_wa'] = $_POST['syns_wa'];
        $record['description'] = $_POST['description'];
        $record['waerid'] = $db->waer_id($record['word_wa']);
        if (strlen($_POST['creator']) == 0 && (strcmp($_GET['action'], "a") == 0)) {
            $record['creator'] = $_SESSION['username'];
        } elseif (strlen($_POST['creator']) == 0 && (strcmp($_GET['action'], "u") == 0)) {
            $record['creator'] = "Любосвет"; //on update we do not show the basic author, but have to restore him
        } else { //but this branch never runs, I think
            $record['creator'] = $_POST['creator'];
        }
        if (strlen($_POST['contributors_list']) == 0 && (strcmp($_GET['action'], "a") == 0)) {
            $record['contributors_list'] = $_SESSION['username'];
        } elseif (strlen($_POST['contributors_list']) == 0 && (strcmp($_GET['action'], "u") == 0)) {
            $record['contributors_list'] = "Любосвет"; //on update we do not show the basic author, but have to restore him
        } else { //but this branch never runs, I think
            $record['contributors_list'] = $_POST['contributors_list'];
        }
        if (isset($_POST['cb_root'])) $record['morphid'] ^= M_ROOT;
        if (isset($_POST['cb_prep'])) $record['morphid'] ^= M_PREPOSITION;
        if (isset($_POST['cb_post'])) $record['morphid'] ^= M_POSTPOSITION;
        if (isset($_POST['cb_pref'])) $record['morphid'] ^= M_PREFIX;
        if (isset($_POST['cb_posf'])) $record['morphid'] ^= M_POSTFIX;
        if (isset($_POST['cb_phrs'])) $record['morphid'] ^= M_PHRASE;
        if (!isset($_POST['cb_root']) & !isset($_POST['cb_prep']) & !isset($_POST['cb_post'])
                 & !isset($_POST['cb_pref']) & !isset($_POST['cb_posf']) & !isset($_POST['cb_phrs'])){
            $record['morphid'] = M_ROOT;
        }
        if ($db->save_rec($record)){
            //send to the preview of the word
            $_POST['wdnURL'] = "/view.php?lang=w&wnum=".$record['wnum'];
            wdn::redirect("Данные успешно сохранены для слова ".db::wfi($record['word_wa']), 2, "");
        }else{
            db::harakiri($record, __FILE__, __LINE__, "Не удалось сохранить изменения!");
        }
    }else {
        wdn::redirect("Запрошено неизвестное действие!", 2);
    }
}
?>
