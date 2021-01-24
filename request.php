<?php
session_set_cookie_params(604800);
session_start();
include_once 'lib/wdn.php';
$fk = new formKey();
?>
<!DOCTYPE html>
<html>
    <head>
<?php
wdn::echo_metas("", "");
?>
        <title>Словарь WDN - <?php
        if (isset($_GET['u'])){
            echo "Исправление";
        }elseif (isset($_GET['v'])){
            echo "Просмотр";
        }elseif (isset($_GET['d'])){
            echo "Запрос на удаление записи";
        }elseif (isset($_GET['dd'])){
            echo "Запрос на удаление документа";
        }elseif (isset($_GET['f'])){
            echo "Добавление семйя";
        }elseif (isset($_GET['a'])){
            echo "Дополнение статей";
        }else{
            echo "Херня какая-то...";
        }
        ?></title>
    </head>
<?php include_once 'lib/head.php';?>
            <tbody align="center">
                <tr><td>
<?php
if (isset($_GET['v'])){ //view dictionary
    wdn::show_dictionary_selector();
}
if (isset($_SESSION['username'])){
    $db = new db();
    if(isset($_GET['u'])){//update record
        if (!is_numeric($_GET['u'])){
            $db->log_error(__FILE__, "update_record", __LINE__, $_GET['u']);
            db::harakiri($_GET, "request", ":update", "Shit in parameters");
        }
        if (!$urec = $db->get_by_wnum($_GET['u'])){
            $db->log_error(__FILE__, "", __LINE__, "update record: ".$_GET['u']);
            db::harakiri("", "request", ":update", "Bad record id ".$_GET['u']);
        }
?>
                    <form method="post" action="action.php?action=u" name="recordform">
                        <input type="hidden" name="wdnURL" value="<?php echo $_SERVER['HTTP_REFERER']; //whatever results this will give... ?>" />
<?php
                        $fk->outputKey();
                        wdn::fill_request_form($urec, TRUE);
?>
                    </form>
<?php
    }elseif(isset($_GET['d'])){//request deletion of a word
?>
        <form method="post" action="action.php?action=d">
            <input type="hidden" name="wdnURL" value="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['argv']; ?>" />
<?php
            $fk->outputKey();
            wdn::show_deletion_request_form($_GET['d']); //wnum is passed in the hidden field
?>
        </form>
<?php
    }elseif(isset($_GET['dd'])){//request deletion of a word
?>
        <form method="post" action="action.php?action=dd">
            <input type="hidden" name="wdnURL" value="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['argv']; ?>" />
<?php
            $fk->outputKey();
            wdn::show_docdeletion_request_form($_GET['dd']); //wnum is passed in the hidden field
?>
        </form>
<?php
    }elseif (isset($_GET['f'])) {#add semja
      ?>
        <form method="post" action="action.php?action=f" name="semjaform">
                <input type="hidden" name="wdnURL" value="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['argv']; ?>" />
<?php
                $fk->outputKey();
                wdn::show_semja_form();
?>
        </form>
      <?php
?>
<?php
    }elseif(isset($_GET['a'])){//add record
?>
            <form method="post" action="action.php?action=a" name="recordform">
                <input type="hidden" name="wdnURL" value="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['argv']; ?>" />
<?php
                $fk->outputKey();
                wdn::fill_request_form(null, FALSE);
?>
            </form>
<?php
    }
}else{
    header("Location: /fail.php?nu=1");
}
?>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>