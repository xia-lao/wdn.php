    <body
        style="/*background: url(/img/edom-shining.png);*/ background-repeat: no-repeat; background-size:cover ;"
        >
        <table border="0" cellpadding="1" width="100%">
            <thead>
                <tr style='opacity: 0.72;'>
                    <td align="right" colspan="2"
                        style="background: #F1F5F6"
                        >

                      <table align="left">
                          <tr align="center" style='opacity: 0.72;'>
                            <td>
                                <?php
                                include_once 'lib/wdn.php';
                                wdn::show_dictionary_selector_short();
                                ?>
                            </td>
                        </tr>
                      </table>

                        <?php if (!isset ($_SESSION['username'])) {echo
                            "<a href='/register.php'>Зарегистрироваться</a>".
                                " | <a href='/login.php'>Войти</a>";
                        }else{
                          if ($_SESSION['acl'] == 0) {
                            echo "<a href='/admin/'>Админка (".$_SESSION['username'].")</a>".
                              "</a> | <a href='/sortir.php'>Выйти</a>";
                          }else{
                            echo "<a href='/profile/user.php?u=".$_SESSION['id'].
                              "'>Вы вошли как пользователь ".$_SESSION['username'].
                                  "</a> | <a href='/sortir.php'>Выйти</a>";
                          }
                        }?>
                      <br />
                      <b><a href='/index.php'>В начало</a></b>
                    </td>
                </tr>
            </thead>
