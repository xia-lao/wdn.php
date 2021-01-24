<?php
session_set_cookie_params(604800);
session_start();
include_once 'lib/wdn.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WDN - Начало</title>
        <?php wdn::echo_metas(); ?>
    </head>
<?php include_once 'lib/head.php';?>
            <tbody align="center" >
                <tr>
                    <td colspan="2" align="center"
                        style="background: #F4F1F8; opacity: 0.72;"
                        >
                        Новости
                        <?php
                        $d = new db;
                        if (isset($_GET['page'])) {
                          $page = $GET['page'];
//                          if (!is_numeric($page)) {
//                            $d->bleed('<center><h3>Ну ёбаный насос, хорэ!</h3></center>');
//                            die();
//                          }
                        }else {
                          $page=1;
                        }
                        //
                        // LOOKHERE 1
                        //
                        $d->show_last_documents_short(11, $page);
                        $c = $d->docpages_count(11);
                        for ($i=0;$i<=$c;$i++) {
                          echo "<a href='index.php?page=".$i.">$i </a>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td width="50%" align="left">
                        <h3>Случайная формула</h3>
                        <?php
                        $db = new db();
                        wdn::show_record_wa($db->get_random_record(), false);
                        ?>
                    </td>
                    <td width="15%" align="left">
                      <h3>Пользователи</h3>
                    <?php
                        $u = new wdnUser();
                        $u->show_userlist();
                    ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        © <a href='http://любосвет.рф'>Любосвет Лавров</a> 1998-2012
                    </td>
                </tr>
            </tbody>
        </table>

<?php
?>
    </body>
</html>
