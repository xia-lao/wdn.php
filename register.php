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
        <title>WDN - Регистрация нового пользователя</title>
        <?php wdn::echo_metas(); ?>
    </head>
<?php include_once 'lib/head.php'; ?>
    <tbody>
        <form action="/lib/registration.php" method="post">
            <input type="hidden" name="wdnURL" value="/register.php" />
            <?php $fk->outputKey(); ?>
            <tr>
                <td align="center"><h2>Регистрация нового пользователя словаря WDN (Waer Dictionary New)</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <table align='center'><tr><td align='left'>
                    <br />Имя пользователя может содержать латинские и/или кирилличесие символы, цифры и знак _.<br />
                    <input type="text" size="93" name="username" placeholder="Имя пользователя" />* - обязательное поле
                    <br />Введите правильный адрес электронной почты. На него будет выслано письмо с кодом подтверждения.<br />
                    <input type="text" size="93" name="email" placeholder="Адрес электронной почты" />* - обязательное поле
                    <br />Пароль может содержать латинские и/или кирилличесие символы, цифры и знак _.<br />
                    <input type="password" size="93" name="pw" placeholder="Ваш пароль (отображается звёздочками)" />* - обязательное поле
                    <br />
                    <input type="password" size="93" name="pw2" placeholder="Повторите Ваш пароль (отображается звёздочками)" />* - обязательное поле<br />
                    <br />Что-нибудь ещё?<br />
                    <textarea name="additional_data" cols="93" rows="9" placeholder="Любые дополнительные данные, которые Вы хотели бы сообщить нам о себе: начиная от пола и возраста, заканчивая Вашим магическим девизом или любимой породой каракатиц. Необязательно к заполнению."></textarea>
                    <br />
                    <?php
                    if (DEBUG==0){
                        echo recaptcha_get_html(RECAPTCHA_PUBLIC_K);
                    }
                    ?>
                    </td></tr>
                    <tr><td align="right">
                            <input type="submit" value="Регистрируй!" />
                            <input type="reset" value="Нужен ластик!" />
                    </td></tr>
                    </table>
                </td>
            </tr>
        </form>
    </tbody>
</table>
</body>
</html>
