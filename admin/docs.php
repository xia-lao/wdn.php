<?php
include_once '../lib/wdn.php';
$db = new db;
session_set_cookie_params(604800);
session_start();
if (!isset($_SESSION['acl']) || (isset($_SESSION['acl']) && $_SESSION['acl'] <> 0)) {
    $_POST['wdnURL'] = "/login.php";
    wdn::redirect("���������������� ������", 1, "������������� �� ����...");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php wdn::echo_metas(); ?>
        <title>������� (���������): <?php echo $_SESSION['username'] ?></title>
    </head>
<?php
include_once '../lib/head.php';
?>
<tbody>
  <tr>
    <td colspan="2">
        <table border="1" cellpadding="2" align='center'>
            <thead>
              <tr>
                <td colspan='3'>
                  <fieldset>
                    <legend>���������� �����������</legend>
                  </fieldset>
                </td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>���</td>
                <td>�����</td>
                <td>���� ���������</td>
              </tr>
<?php
$dlist = $db->get_doclist();
foreach($dlist as $name) {
?>
                <tr>
                    <td align='left'>
                      <?php
                      echo "<a href='";
                      echo "/viewdoc.php?id=".$name['id'];
                      echo "'>";
                      echo $name['name'];
                      echo "</a>";
                      ?>
                    </td>
                    <td align='left'>
                      <a href='/admin/edit.php?id=<?php echo $name['id']; ?>'>�������������</a><br />
                      <a href='/request.php?dd=<?php echo $name['id']; ?>'>��������� ��������</a><br />
                      <?php
                      ?>
                    </td>
                    <td align='left'>
                      <?php
                      echo $name['modified']
                      ?>
                    </td>
                </tr>
<?php
}
?>
            </tbody>
        </table>
    </td>
  </tr>
</tbody>
</table>
</body>
</html>
