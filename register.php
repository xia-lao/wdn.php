<?php
include_once 'lib/wdn.php';
require_once ('lib/recaptchalib.php');
session_set_cookie_params(604800);
session_start();
$fk = new formKey();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WDN - ����������� ������ ������������</title>
        <?php wdn::echo_metas(); ?>
    </head>
<?php include_once 'lib/head.php'; ?>
    <tbody>
        <form action="/lib/registration.php" method="post">
            <input type="hidden" name="wdnURL" value="/register.php" />
            <?php $fk->outputKey(); ?>
            <tr>
                <td align="center"><h2>����������� ������ ������������ ������� WDN (Waer Dictionary New)</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <table align='center'><tr><td align='left'>
                    <br />��� ������������ ����� ��������� ��������� �/��� ������������ �������, ����� � ���� _.<br />
                    <input type="text" size="93" name="username" placeholder="��� ������������" />* - ������������ ����
                    <br />������� ���������� ����� ����������� �����. �� ���� ����� ������� ������ � ����� �������������.<br />
                    <input type="text" size="93" name="email" placeholder="����� ����������� �����" />* - ������������ ����
                    <br />������ ����� ��������� ��������� �/��� ������������ �������, ����� � ���� _.<br />
                    <input type="password" size="93" name="pw" placeholder="��� ������ (������������ ����������)" />* - ������������ ����
                    <br />
                    <input type="password" size="93" name="pw2" placeholder="��������� ��� ������ (������������ ����������)" />* - ������������ ����<br />
                    <br />���-������ ���?<br />
                    <textarea name="additional_data" cols="93" rows="9" placeholder="����� �������������� ������, ������� �� ������ �� �������� ��� � ����: ������� �� ���� � ��������, ���������� ����� ���������� ������� ��� ������� ������� ���������. ������������� � ����������."></textarea>
                    <br />
                    <?php
                    if (DEBUG==0){
                        echo recaptcha_get_html(RECAPTCHA_PUBLIC_K);
                    }
                    ?>
                    </td></tr>
                    <tr><td align="right">
                            <input type="submit" value="�����������!" />
                            <input type="reset" value="����� ������!" />
                    </td></tr>
                    </table>
                </td>
            </tr>
        </form>
    </tbody>
</table>
</body>
</html>
