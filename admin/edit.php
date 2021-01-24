<?php
include_once '../lib/wdn.php';
$db = new db;
session_set_cookie_params(604800);
session_start();
if (!isset($_SESSION['acl']) || (isset($_SESSION['acl']) && $_SESSION['acl'] <> 0)) {
    $_POST['wdnURL'] = "/login.php";
    wdn::redirect("���������������� ������", 1, "������������� �� ����...");
}
if (!isset($_GET['save'])) {
?>
<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript">
         _editor_url  = "/admin/editor/"   // (preferably absolute) URL (including trailing slash) where Xinha is installed
         _editor_lang = "ru";       // And the language we need to use in the editor.
         _editor_skin = "blue-metallic";    // If you want use a skin, add the name (of the folder) here
         _editor_icons = "Crystal"; // If you want to use a different iconset, add the name (of the folder, under the `iconsets` folder) here
        </script>
        <script type="text/javascript" src="/admin/editor/XinhaCore.js"></script>
        <script type="text/javascript" src="/admin/editor/econf.js"></script>
        <script type="text/javascript" src="/lib/jquery.js"></script>
        <?php wdn::echo_metas(); ?>
        <title>������� (���������): <?php echo $_SESSION['username'] ?></title>
    </head>
<?php
include_once '../lib/head.php';
?>
    <tbody><tr><td colspan="2">
          <form action='edit.php?save' method='POST' id='frm_editor' name='frm_editor' >
            <table border="1" cellpadding="2" align='center'>
            <thead></thead>
            <tbody>
                <tr>
                    <td align='left'>
<!-- cut here to all editor docs -->
<input type='hidden' name='id' value='<?php
if (isset($_GET['id']) && $_GET['id'] <> 0 && is_numeric($_GET['id'])) {
  $text = $db->get_doc($_GET['id'], true);
  echo $text['id'];
}
?>' />
<input type='text' name='name' placeholder='��������� ���������' size='122' value='<?php
if (isset($_GET['id']) && $_GET['id'] <> 0 && is_numeric($_GET['id'])) {
  echo $text['name'];
}
?>' /><br />
<input type='text' name='url' placeholder='����������� �������� (���)' size='122' value='<?php
if (isset($_GET['id']) && $_GET['id'] <> 0 && is_numeric($_GET['id'])) {
  echo $text['url'];
}
?>' /><br />
<input type='text' name='author' placeholder='��� ������' size='122' value='<?php
if (isset($_GET['id']) && $_GET['id'] <> 0 && is_numeric($_GET['id'])) {
  echo $text['author'];
}
?>' /><br />
<textarea name='document' id='editor' cols='93' rows='11'><?php
if (isset($_GET['id']) && $_GET['id'] <> 0 && is_numeric($_GET['id'])) {
  echo $text['content'];
}
?></textarea>
META Keywords: <input type='text' size='102' placeholder='�������� �����' name='kw' value='<?php
if (isset($_GET['id']) && $_GET['id'] <> 0 && is_numeric($_GET['id'])) {
  echo $text['kw'];
}
?>' /><br />
META Description: <input type='text' size='101' placeholder='��������' name='ds' value='<?php
if (isset($_GET['id']) && $_GET['id'] <> 0 && is_numeric($_GET['id'])) {
  echo $text['ds'];
}
?>' />
                <tr>
                    <td align='center'>
                        <input type='submit' value='���������' />
                    </td>
                </tr>
            </tbody>
            <tfoot>
            </tfoot>
        </table>
          </form>
    </td></tr></tbody></table>
    </body>
</html>
<?php } else { //save document
$doc = array('name' => $_POST['name'],
  'id' => $_POST['id'],
  'content' => $_POST['document'],
  'url' => $_POST['url'],
  'kw' => $_POST['kw'],
  'ds' => $_POST['ds'],
  'author' => $_POST['author'],
  );
if ($db->save_doc($doc)) {
  $_POST['wdnURL'] = "/admin/index.php";
  wdn::redirect("�������� <b>".$doc['name']."</b> �������.", 2, "������������� � ������ ����������������� �������...");
}else{
  //failed to save document
  if ($doc['id'] <> "") {
    $_POST['wdnURL'] = "/admin/edit.php?id=".$doc['id'];
    wdn::redirect("�������� <b>".$doc['name']."</b> �� ������� ���������.", 1);
  }else{
    echo db::bleed("<h1>���������� ��������� �� �������</h1><p>���� ��������� ����� ����: ��������� ��� � �����. ������ �����������������, �� ��������, ��� ����� �� ���.<hr />");
    echo "<pre>".htmlspecialchars($doc['content'])."</pre>";
  }
}
}
?>
