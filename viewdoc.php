<?php
session_set_cookie_params(604800);
session_start();
include_once 'lib/wdn.php';
$db = new db();
/*if (!isset($_GET['id'])) {
  $_POST['wdnURL'] = "/";
  wdn::redirect("Не указан документ для просмотра", 1, "Перенаправляю в начало...");
}*/
$doc = $db->get_doc($_GET['id']);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WDN - <?php echo $doc['name'] ?></title>
        <?php wdn::echo_metas($doc['kw'], $doc['ds']); ?>
    </head>
<?php include_once 'lib/head.php';?>
                <tr>
                <table border="1" align="center">
                  <thead>
                    <tr>
                      <td>
                        <fieldset>
                          <legend><h1><?php echo $doc['name'];?></h1></legend>
                          Автор: <?php echo $doc['author']; ?>, опубликовано: <?php echo $doc['modified']; ?>
                        </fieldset>
                      </td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <?php
                        echo $doc['content'];
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input type="button" value="Редактировать" onclick="window.location='/admin/edit.php?id=<?php
                          echo $doc['id'];
                        ?>'"
                      </td>
                    </tr>
                  </tbody>
                </table>
                </tr>
            </tbody>
</table>
</body>
</html>