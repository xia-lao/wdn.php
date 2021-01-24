<?php

include_once "const.php";
include_once "Integer.php";

class wdn {

    public static function recognize_formula($string, $pattern=0){ //let's give $pattern a default value to simply ignore it
        $heblist = array (
            "a" => "&#1488;", "b" => "&#1489;", "g" => "&#1490;", "d" => "&#1491;", "h" => "&#1492;", "v" => "&#1493;", "z" => "&#1494;", "x" => "&#1495;", "t" => "&#1496;", "y" => "&#1497;", "i" => "&#1497;",
            "K" => "&#1498;", "k" => "&#1499;", "l" => "&#1500;", "M" => "&#1501;","m" => "&#1502;", "N" => "&#1503;", "n" => "&#1504;", "s" => "&#1505;", "o" => "&#1506;", "P" => "&#1507;", "p" => "&#1508;", "C" => "&#1509;", "c" => "&#1510;", "q" => "&#1511;", "r" => "&#1512;",
            "S" => "&#1513;", "T" => "&#1514;", " " => " "
        );
        $grklist = array (
            "a" => "&alpha;", "b" => "&beta;", "g" => "&gamma;", "d" => "&delta;", "e" => "&epsilon;", "z" => "&zeta;", "h" => "&eta;", "t" => "&theta;", "i" => "&iota;",
            "k" => "&kappa;", "l" => "&lambda;", "m" => "&mu;", "n" => "&nu;", "X" => "&xi;", "o" => "&omicron;", "p" => "&pi;", "r"=>"&rho;", "S" => "&sigmaf;", "s" => "&sigma;", "T" => "&tau;",
            "y" => "&upsilon;", "f" => "&phi;", "x" => "&chi;", "P" => "&psi;", "O" => "&omega;", " " => " "
        );
        if (strstr($string, "{") != false) {//if we meet a token start char
            $start = explode("{", $string); //part before separator
            $mids = array(); $counter = 0;
            for ($i=1;$i<count($start);$i++) { //we get tokens
                $tmp = explode("}", $start[$i]);
                $mids[$counter] = $tmp[0]; //part in the middle of the tag, the token itself
                $mids[$counter+1] = $tmp[1]; //part after the separator, until the next opening tag
                $counter += 2;
            }
            for ($i=0;$i<count($mids);$i+=2){ //walk through the tokens (in the array they are in the center)
                $mid = &$mids[$i]; //create a pointer, rather than work with copy
                //here let us understand, which model we need: simple, hebrew, runic or ... "to be continued"
                $newmid_arr = str_split(substr($mid, 2)); //use only non-technical part of middle string
                if (strcmp($newmid_arr[0], " ") == 0) { //remove left trailing space
                    array_splice($newmid_arr, 0, 1);
                }
                $newstring = array();
                switch (substr($mid, 1, 1)) {
                    case "h":
                        foreach($newmid_arr as &$char) {
                            if (array_key_exists($char, $heblist)){//case the symbol is defined, replace it, otherwise leave as is
                                $newstring[] = $heblist[$char];
                            }
                        }
                        $mid = join("", $newstring);
                        break;
                    case "g":
                        $uc = false;
                        $i2 = 0; //need it for sigma->sigmaf
                        foreach($newmid_arr as $char) {
                            /*if (strcmp($char, "/") == 0) {//usual (OBSOLETE!) for hoggvograph mode of making letters uppercase, screeneing with '/'
                                $uc = true;
                                $i2++; //since we don`t get to the end of foreach, but need a real counter
                                continue;
                            }*/
                            if (array_key_exists($char, $grklist)){//case the symbol is defined, replace it, otherwise leave as is
                                if (strcmp($char, "s") == 0){ //implicitly convert last sigma before end or space to final sigma
                                    if ($i2 < count($newmid_arr)){ //before space of punc. mark
                                        if (strcmp($newmid_arr[$i+1], " ") == 0 ||
                                            strcmp($newmid_arr[$i+1], ",") == 0 ||
                                            strcmp($newmid_arr[$i+1], ".") == 0 ||
                                            strcmp($newmid_arr[$i+1], ":") == 0 ||
                                            strcmp($newmid_arr[$i+1], "?") == 0 ||
                                            strcmp($newmid_arr[$i+1], "!") == 0
                                                ){ //note, that some signs are not used in greek, some are not present
                                            $newstring[] = "S";
                                        }
                                    }elseif($i2 == count ($newmid_arr)){//at the end of line
                                        $newstring[] = "S";
                                    }
                                }
                                $newstring[] = $grklist[$char];//replacement itself
                                /*if ($uc == true) {//uppercase convertion
                                    $char[1] = ucfirst($char[1]);
                                    $uc = false;
                                }*/
                            }
                            $i2++;//increase explicit array pointer for sigma conversion
                        }
                        $mid = join("", $newstring);
                        break;
                    case "r":
                        $param = substr($mids[$i], 2);
                        $mids[$i] = db::wfi($param);
                        break;
                }
                $mid = "<font color='green'><b>".$mid."</b></font>";
            }
            $result = join("", array($start[0], join("", $mids)));
            return $result;
        }else {
            return $string;
        }
    }

    /*private static function waer_num ($decimal) {
      $alphabet = "эыьязаъмюгктвущбоРшлсержйпхёиВдцн";
      $dd = Integer::convert($decimal, 10, 12);
      $letters = array_reverse(str_split($dd)); //the last will be the eldest digit
      $digparts = str_split($alphabet, 11);
      $counter = -1; $num = array(); $zc = false;
      $errposition = -1;
      foreach ($letters as $dig) {
          $counter++;
          if (strcmp($dig, 0) <> 0) {
              if (strcmp($dig, "A") == 0) $dig = "10";
              if (strcmp($dig, "B") == 0) $dig = "11";
              $num[] = $digparts[$counter][$dig-1];
          }else{
              $errposition++;
              if (($errposition == 2) && ($counter == 2)){
                  $num[] = "ф";
              }
          }
          if ($counter == 2) {$counter = -1; $errposition = -1;}
      }
      $num = array_reverse($num);
      return db::wfi($num);
    }*/


    public static function waer_int ($integer) {
      $alphabet = array(1=>"эыьязаъмюгк",2=>"твущбоРшлсе", 3=>"ржйпхёиВдцн");
      $t = base_convert($integer, 10, 12);
      $counter = 0; $newint = array();
      for ($i=0; $i<strlen($t); $i++) //change all 000 from mod3 positions to affas
      {
        if ($t[$i] != "0") {
          $newint[] = $t[$i];
          continue;
        }else{
          if ($counter > 0) {
            if ($counter == 3) {
              $counter = 0;
              $newint[] = "ф";
              continue;
            }
          }
          $counter++;
        }
      }
      $counter = 0; $t = join ($newint);
      for ($i = 0; $i<=strlen($t); $i++) //replace numbers on each digit with corresponding hoggva
      {
        if (strcasecmp($t[$i], "ф") == 0) {
          $counter = 0; //just in case
          $newint[$i] = "ф";
          continue; // "ф" occupies 3 digits
        }
        $counter++;
        if (strcasecmp($t[$i], "a") == 0) {
          $needle = 10;
        }elseif (strcasecmp($t[$i], "b") == 0) {
          $needle = 11;
        }
        $needle = $newint[$i];
        $newint[$i] = $alphabet[$counter][$needle-1];
        if ($counter == 3) {$counter = 0;}
      }
      return join ($newint);
    }

    public static function tolve_grund ($integer) {
      return Integer::convert($integer, 10, 12);
    }

    /**
     * Function fills in necessary meta elements and initializes functional elements.
     * @param type $kw - if we have meta keywords, we load them here
     * @param type $ds - if we have meta description, we load it here
     * @param type $we - if we show editor, we set $we to 1
     */
    public static function echo_metas($kw = "", $ds = ""){
?>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <meta name="generator" content="Любосветовы ручки">
        <meta name="author" content="Любосвет Лавров" />
        <meta name="description" content="WDN - Waer Dictionary New<?php if ($ds <> ""){echo ", $ds";} ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <meta name="keywords" content="ваэр, словарь, <?php if ($kw <> "") {echo $kw.", ";} ?>ваэр-русский словарь, русско-ваэрский словарь" />
        <link href="css/wdn.css" type="text/css" rel="stylesheet" />
        <link href="editor/roc/rocanvas.css?v=1.0" type="text/css" rel="stylesheet"
<?php
    }

    public static function parse_string_by_tags($opening, $closing, $string) {
        $start = explode($opening, $string); //part before separator
        $mids = array(); $counter = 0;
        for ($i=1;$i<count($start);$i++) { //we get tokens
            $tmp = explode($closing, $start[$i]);
            $mids[$counter] = $tmp[0]; //part in the middle of the tag, the token itself
            $mids[$counter+1] = $tmp[1]; //part after the separator, until the next opening tag
            $counter += 2;
        }
        $result = array();
        //идиотское решение... но других в голову не пришло
        $result[0] = $start[0];
        foreach ($mids as $string){
            $result[] = $string;
        }
        return $result;
    }

    public static function fill_request_form($record, $filled) {
        ?>
                <tr><td>
                    <table align="center"><tr><td align="left">
                    <input type="text" placeholder="гоВре ыэр"
                           value="<?php if ($filled)
                               echo $record['word_wa']; ?>"
                           style="font-family: 'WaerCursive';"
                           name="word_wa" size="97" spellcheck="false"> (<?php echo db::wfi("_ыэУр"); ?>-слово)
                    <br />
                    <input type="text" placeholder="гоВрёУн-ёщы ыэрёУну" name="syns_wa"
                            style="font-family: 'WaerCursive';"
                           size="97" class="waertext" spellcheck="false"
                           value="<?php if ($filled) echo $record['syns_wa']; ?>" /> (<?php echo db::wfi("_ыэУр"); ?> синонимы)
                    <br />
                    <input type="text" placeholder="перевод" name="word_ru" size="93"
                           value="<?php if ($filled) echo $record['word_ru']; ?>" /> (Перевод)
                    <br />
                    <input type="text" placeholder="русские синонимы" name="syns_ru" size="93"
                           value="<?php if ($filled) echo $record['syns_ru']; ?>" /> (Русские синонимы)
                    <br />
                    <input type="text"
                           placeholder="Проверьте здесь транслитерацию хоггв, вставив содержимое полей с хоггвами"
                           size="93" />
                    </td></tr></table>
                    <table align="center" border="0">
                    <tr><td align="left">
                        (Номер записи)<input type="text" name="wnum"
                                             value="<?php
                                             if ($filled){echo $record['wnum'];}
                                             ?>" />
                        <?php if (DEBUG==1){$type = "text";}else{$type="hidden";}?>
                        (Создатель)<input type="<?php echo $type; ?>" name="creator"
                                          value="<?php
                                             if ($filled){
                                                 if (strcmp($record['creator'], "Любосвет") <> 0) {
                                                    echo $record['creator'];
                                                 }
                                             };
                                                  ?>" />
                        (Внесли вклад)<input type="<?php echo $type; ?>" name="contributors_list"
                                             value="<?php
                                             if ($filled){
                                                 if (strcmp($record['contributors_list'], "Любосвет") <> 0) {
                                                    echo $record['contributors_list'];
                                                 }
                                             };
                                                     ?>" />
                        (ID)<input type="<?php echo $type; ?>" disabled name="waerid"
                                   value="<?php
                                   if ($filled) echo $record['waerid']
                                           ?>" />
                        <br /><input type="checkbox" name="wonder">
                    <b>Попросить совета по структуре формулы</b><br />
                    (модераторы учтут Ваше сомнение в правильности формулы и обсудят с Вами в переписке формулу, предложив свои версии)
                    <br />
                    <b>Морфемная функция слова</b>:<br /><label for='cb_root'><input type="checkbox" name='cb_root' id='cb_root'
                                                    <?php if ($filled) {if (($record['morphid'] & M_ROOT)==M_ROOT) echo "checked";} ?>
                                                    /><?php echo _I18N_ROOT; ?></label>
                        <label for='cb_prep'><input type="checkbox" name='cb_prep' id='cb_prep'
                                                    <?php if ($filled) {if (($record['morphid'] & M_PREPOSITION)==M_PREPOSITION) echo "checked";} ?>
                                                    /><?php echo _I18N_PREP; ?></label>
                        <label for='cb_post'><input type="checkbox" name='cb_post' id='cb_post'
                                                    <?php if ($filled) {if (($record['morphid'] & M_POSTPOSITION)==M_POSTPOSITION) echo "checked";} ?>
                                                    /><?php echo _I18N_POST; ?></label>
                        <label for='cb_pref'><input type="checkbox" name='cb_pref' id='cb_pref'
                                                    <?php if ($filled) {if (($record['morphid'] & M_PREFIX)==M_PREFIX) echo "checked";} ?>
                                                    /><?php echo _I18N_PREF; ?></label>
                        <label for='cb_posf'><input type="checkbox" name='cb_posf' id='cb_posf'
                                                    <?php if ($filled) {if (($record['morphid'] & M_POSTFIX)==M_POSTFIX) echo "checked";} ?>
                                                    /><?php echo _I18N_POSF; ?></label>
                        <label for='cb_phrs'><input type="checkbox" id='cb_phrs' name='cb_phrs'
                                                    <?php if ($filled) {if (($record['morphid'] & M_PHRASE)==M_PHRASE) echo "checked";} ?>
                                                            onchange='setcbs();' /><?php echo _I18N_PHRS; ?></label>
                        <script>
                            function setcbs(){
                                if (cb_phrs.checked==true){
                                    cb_root.disabled = true;
                                    cb_prep.disabled = true;
                                    cb_post.disabled = true;
                                    cb_pref.disabled = true;
                                    cb_posf.disabled = true;
                                }else{
                                    cb_root.disabled = false;
                                    cb_prep.disabled = false;
                                    cb_post.disabled = false;
                                    cb_pref.disabled = false;
                                    cb_posf.disabled = false;
                                }
                            }
                        </script>
                        </td></tr></table>
                    <textarea name="description"
                              placeholder="Пояснения к формуле. Для включения текста, не кириллическими/латинскими символами, заключите нужные слова в фигурные скобки и поставьте после скобки -r для хоггв, -h для иврита, -g для греческих букв. Латиница включается как есть."
                              cols="93" rows="9"><?php if ($filled) echo $record['description']; ?></textarea>
                <tr align="right"><td>
                    <input type="submit"
                           value="Отправить слово <?php if (isset($_SESSION['acl']) && ($_SESSION['acl'] <> 0)) echo "на проверку"; ?>" />
                    <input type='reset' value="Обновить форму" />
                </td></tr>
<?php
    }

    public static function show_record_wa($rec, $show_manage) {
        echo db::wfi($rec['word_wa'])."</i></b>, (".$rec['word_ru']."). ".db::morpheme($rec['morphid']).".";
        if (strlen($rec['description'])>0){
            echo "<br />".wdn::recognize_formula($rec['description']);
        }
        if (strlen($rec['syns_wa'])>0){
            echo "<br /><i>Синонимы ваэ.:".db::wfi($rec['syns_wa']).". </i>";
        }
        if (strlen($rec['syns_ru'])>0){
            echo "<br /><i>Синонимы рус.: ".$rec['syns_ru'].". </i>";
        }
        if (isset($_SESSION['acl']) && $show_manage == true){
            echo "<p align='right'>Вы можете: <a href='request.php?u=".$rec['wnum']."'>исправить запись</a> или ";
            echo "<a href='request.php?d=".$rec['wnum']."'>запросить удаление записи</a></p>";
        }
    }

    public static function show_record_ru ($rec, $show_manage) {
        echo $rec['word_ru']."</i></b>, (".db::wfi($rec['word_wa'])."). ".db::morpheme($rec['morphid']).".";
        if (strlen($rec['description'])>0){
            echo "<br />".wdn::recognize_formula($rec['description']);
        }
        if (strlen($rec['syns_wa'])>0){
            echo "<br /><i>Синонимы ваэ.:".db::wfi($rec['syns_wa']).". </i>";
        }
        if (strlen($rec['syns_ru'])>0){
            echo "<br /><i>Синонимы рус.: ".$rec['syns_ru'].". </i>";
        }
        if (isset($_SESSION['acl']) && $show_manage == true){
            echo "<p align='right'>Вы можете: <a href='request.php?u=".$rec['wnum']."'>исправить запись</a> или ";
            echo "<a href='request.php?d=".$rec['wnum']."'>запросить удаление записи</a></p>";
        }
    }

    public static function show_deletion_request_form($wnum){
        $db = new db();
        $rec = $db->get_by_wnum($wnum);
        echo "<tr><td>";
        wdn::show_record_wa($rec);
        echo "</tr></td>";
        ?>
                <tr>
                    <td align="left">
                        <input type="text" disabled value="<?php if (isset($_SESSION['username'])) echo $_SESSION['username'] ?>" />
                        <input type="hidden" value="<?php echo $wnum; ?>" name="wnum" />
                        <br />
                        <textarea name="reason" cols="93" rows="11" placeholder="Напишите причину, по которой Вы жалаете удалить формулу из словаря. Удаление не произойдёт без рассмотрения Вашей причины и обсуждения её с Вами, в случае несогласия администраторов с Вашей позицией"></textarea>
                        <br />
                        <input type="submit" value="Отправить" />
                        <input type="reset" value="Очистить" />
                    </td>
                </tr>
        <?php
    }

    public static function show_docdeletion_request_form($document_id){
        ?>
                <tr>
                    <td align="left">
                        <input type="text" disabled value="<?php
                          if (isset($_SESSION['username'])) echo $_SESSION['username'];
                          ?>" />
                        <input type="hidden" value="<?php echo $document_id; ?>" name="document_id" />
                        <br />
                        <textarea name="reason" cols="93" rows="11" placeholder="Напишите причину, по которой Вы жалаете удалить документ. Удаление не произойдёт без рассмотрения Вашей причины и обсуждения её с Вами, в случае несогласия администраторов с Вашей позицией"></textarea>
                        <br />
                        <input type="submit" value="Отправить" />
                        <input type="reset" value="Очистить" />
                    </td>
                </tr>
        <?php
    }

    public static function show_semja_form ($semja = null) {
    ?>
      <fieldset align="center" >
        <legend><?php echo db::wfi("_семйяр _цэкдеУсёУн:0")?> (Члены семйя: )</legend>
        <table border="0" align="center">
          <tr><td>Задача <?php echo db::wfi("_семйяр:0")?></td><td><input name="shelij" type="text" placeholder="Цель семйяр" /></td></tr>
          <tr><td><?php echo db::wfi("итемну:0")?></td><td><input class="waer" name="itemnu" type="text" /></td></tr>
          <tr><td><?php echo db::wfi("_биалы:0")?></td><td><input class="waer" name="bialwo" type="text" maxlength="1" /></td></tr>
          <tr><td><?php echo db::wfi("_хорз:0")?></td><td><input class="waer" name="xorz" type="text" maxlength="1" /></td></tr>
          <tr><td><?php echo db::wfi("_ыбюё:0")?></td><td><input class="waer" name="wobxreo" type="text" maxlength="2" /></td></tr>
          <tr><td><?php echo db::wfi("_кхЭрн:0")?></td><td><input class="waer" name="kxaern" type="text" maxlength="3" /></td></tr>
          <tr><td><?php echo db::wfi("_ныъбти:0")?></td><td><input class="waer" name="nwaobti" type="text" maxlength="1" /></td></tr>
          <tr><td>Комментарии<br />(исп. {-r|h|g})</td><td><textarea id="editor" name="comment" cols="56" rows="8"></textarea></td></tr>
          <tr><td><?php echo "Нарисовать ".db::wfi("_меды0:0")?></td>
            <td>
              <input type="button" onclick="open(); return false;" value="Создать графику" />
              <!--a onclick="open();" target="_blank" >Создать графику <?php echo db::wfi("_меды0:0")?></a-->
              <script>
                function open(){
                  win = window.open("editor/medaw.php", "", "width=300, height=300, status=0, resizable=0, scrollbars=0");
                  if (win == null) {
                    alert ("Fail!");
                  }
                  win.focus();
                }
              </script>
              <input type="hidden" name="medaw" />
            </td>
          </tr>
          <tr><td  colspan="2" align="left"><hr /><input type="submit" value="Сохранить"/></td></tr>
        </table>
      </fieldset>
    <?php
    }

    public static function show_dictionary_selector() {
?>
        <p align='center'><h2>Выберите букву для просмотра словаря:</h2></p>
        <p align="center">Кириллическая сортировка<br />Нажмите <a href="/view.php?lang=r&let=0">здесь для просмотра русско-ваэрского словаря целиком</a></p>
        <table align="center" width="100%" cellpadding="1">
            <tr>
            <?php
            $rus = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
            $waer = "эыьязаъмюгктвущбоРшлсёржйпхеиВдцн";
            $alp = str_split($rus);
            $counter=1;
            foreach($alp as $letter){
                echo "<td><a href='view.php?lang=r&let=$counter'>$letter</a></td>";
                $counter++;
            }
            ?>
            </tr>
        </table>
        <p align="center"><span style="font-family: 'WaerCursive';">_ыэр-</span>сортировка<br />Нажмите <a href="view.php?lang=w&let=0">здесь для просмотра ваэрско-русского словаря целиком</a></p>
        <table align="center" width="100%" cellpadding="1">
            <tr>
            <?php
            $alp = str_split($waer);
            $counter=1;
            foreach($alp as $letter){
                echo "<td><a href='view.php?lang=w&let=$counter'>".
                     db::wfi($letter)."</a></td>";
                $counter++;
            }
            ?>
            </tr>
        </table>
<?php
    }

    public static function show_dictionary_selector_short () {
?>
        <table align="center" cellpadding="1">
            <tr>
              <td><a href="view.php?lang=r&let=0">Весь словарь Р=>В</a></td>
            <?php
            $rus = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
            $waer = "эыьязаъмюгктвущбоРшлсёржйпхеиВдцн";
            $alp = str_split($rus);
            $counter=1;
            foreach($alp as $letter){
                echo "<td><a href='view.php?lang=r&let=$counter'>$letter</a></td>";
                $counter++;
            }
            ?>
              <td>
                <input type="button" value="Добавить семйя" onclick="window.location='request.php?f'" />
              </td>
            </tr>
            <tr>
              <td><a href="view.php?lang=w&let=0">Весь словарь В=>Р</a></td>
              <?php
              $alp = str_split($waer);
              $counter=1;
              foreach($alp as $letter){
                  echo "<td><a href='view.php?lang=w&let=$counter'>".
                       db::wfi($letter)."</a></td>";
                  $counter++;
              }
              ?>
              <td>
                <input type="button" value="Добавить слово" onclick="window.location='request.php?a'" />
              </td>
            </tr>
        </table>
<?php
    }

    /**
     * Function sould be inserted before any data is sent to browser, since it includes headers into the html
     * Relies on hidden input, so it must be initialized beforehand and page is passed in
     * the $_POST['wdnURL'] parameter.
     * If field is not initialized, function redirects to $_SERVER['HTTP_HOST'] address
     *
     * @param string $main_comment message, shown to user in h2 tags
     * @param integer $timeout optional, time lapse before redirection
     * @param string $secondary_comment optional, comment on the following action or anything, to be shown in simple text
     * @param bool $get_out optional, whether this redirect is made for leaving site (syntactic sugar)
     */
    public static function redirect($main_comment, $timeout=0, $secondary_comment="", $get_out = false){
        if (!isset($_POST['wdnURL'])) {
            $_POST['wdnURL']= "index.php";
        }
?>
        <META HTTP-EQUIV="refresh" CONTENT="<?php echo $timeout; ?>;URL=<?php if (!$get_out){echo $_POST['wdnURL'];}
        else{echo "sortir.php";}?>" />
        <center><h2><?php echo $main_comment; ?></h2></center>
<?php
        echo $secondary_comment;
    }

}

/**
 * Class for actions with users. User information relies on $_SESSION['username']
 */
class wdnUser{
    private $conn;
    private $email;
    private $additional_data;
    private $username;
    private $acl;
    private $regdate;

    function __construct() {
        $this->conn = $this->_connect(MSQL_SERVER_NAME, MSQL_SERVER_USER, MSQL_SERVER_PASS, MSQL_SERVER_BASE);
        if (!$this->conn) {
            db::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
        }
        $this->_query("SET NAMES cp1251");
        $this->_select_db(MSQL_SERVER_BASE);
        if (isset($_SESSION['username'])) {
            $this->init_user();
        }
    }

    /**
     * Function, filling fields for user, relies on $_SESSION['username']
     */
    private function init_user () {
        if (!isset($_SESSION['username'])) return false;
        $q = "SELECT * FROM userlist WHERE username='".$_SESSION['username']."'";
        if (!$r = $this->_query($q)) {
            $db = new db();
            $db->log_error(__FILE__, __FUNCTION__, __LINE__, $q);
        }
        $data = $this->_fetch_assoc($r);
        $this->username = $data['username'];
        $this->acl = $data['acl'];
        $this->email = $data['email'];
        $this->additional_data = $data['additional_data'];
        $this->regdate = $data['registered'];
        return true;
    }

    public function get_user_by_id($uid) {
        $q = "SELECT * FROM userlist WHERE id=$uid";
        if (!$r = $this->_query($q)) {
            $this->log_error(__FILE__, __FUNCTION__, __LINE__, "$q ||| ".$this->_error($this->conn));
        }
        return $this->_fetch_assoc($r);
    }

    public function show_user_setup(){
        if (!isset($_SESSION['username'])) {
            return false;
        }
        $fk = new formKey();
        ?>
        <table border="1" width="100%">
            <thead></thead>
            <tbody>
                <form method="post" action="save.php">
                <input type="hidden" name="wdnURL" value="profile/index.php" />
                <?php $fk->outputKey(); ?>
                <tr><td align="center">
                    Вас зовут
                    <br />
                    <input type="text" size="93" name="username" value="<?php echo $this->username; ?>" />
                    <br />
<!---------------------------------->
                    Ваш пароль и подтверждение<br />
                    Если Вы не желаете менять пароль, не вводите никаких данных в эти два поля
                    <br />
                    <input type="password" name="pw" placeholder="Пароль"/>
                    <input type="password" name="pw2" placeholder="Снова пароль" />
                    <br />
<!---------------------------------->

                    Ваш адрес электронной почты
                    <br />
                    <input type="text" size="93" name="email" value="<?php echo $this->email; ?>" />
                    <br />
<!---------------------------------->
                    Что бы Вы хотели, чтобы о Вас знали
                    <br />
                    <textarea name="additional_data" placeholder="Расскажите нам о себе"
                              cols="93" rows="11"><?php echo $this->additional_data; ?></textarea>
                </td></tr>
                <tr>
                    <td align="right">
                        <input type="submit" value="Сохранить" />
                        <input type="reset" value="Отменить изменения" />
                    </td>
                </tr>
                </form>
            </tbody>
        </table>
        <?php
    }

    public function get_uid_by_name ($username) {
        $req = "SELECT id FROM userlist WHERE username='$username'";
        if (!$res = $this->_query($req)) {
            //$this->log_error(__FILE__, __FUNCTION__, __LINE__, "$req ||| ".$this->_error($this->conn));
            return false;
        }
        $result = $this->_fetch_assoc($res);
        return $result['id'];
    }

    public function get_uid_by_email ($email) {
        $req = "SELECT id FROM userlist WHERE email='$email'";
        if (!$res = $this->_query($req)) {
            //$this->log_error(__FILE__, __FUNCTION__, __LINE__, "$req ||| ".$this->_error($this->conn));
            return false;
        }
        $result = $this->_fetch_assoc($res);
        return $result['id'];
    }

    public function show($user) {
?>
<table align='center'>
    <tr>
        <td align='left'>
            <b>
            <?php
            echo $user['username'];
            ?>
            </b> <br />
            <?php
            if (isset($_SESSION['acl']) && $_SESSION['acl'] == 0) {
                echo str_replace("@", "{at}", $user['email']);
            }else{
                echo "Email скрыт";
            }
            ?>
            <br />
            <i>
            <?php
            echo wdn::recognize_formula(html_entity_decode($user['additional_data']));
            ?>
            </i>
            <?php
            $db = new db();
            $user_recs = $db->get_list_by_creator($user['username']);
            $user_contributed_recs = $db->get_list_by_contributor($user['username']);
            $recs = array();
            foreach ($user_recs as $urec) {
                foreach ($user_contributed_recs as &$ucrec) {
                    //if urec us in $user_contributed_recs, remove it from $user_contributed_recs
                    if (strcmp($urec['word_wa'], $ucrec['word_wa']) == 0) {
                        unset($ucrec);
                    }
                }
            }
            echo "<h3>Формулы пользователя</h3>";
            if ((count($user_recs) > 0 || count($user_contributed_recs) > 0) && ($user['id'] != 1 /*skip Ljubosvet*/)) {
            ?>
            <table width='100%' style="opacity:0,75;" align="left" border="0">
                <thead>
                    <tr><td>Созданные пользователем</td>
                        <td>В которых пользователь принял участие</td><!--td>Которые пользователь спонсирует</td-->
                    </tr>
                </thead>
                <tbody>
            <?php
            //show words
            foreach($user_recs as $recs) {
            ?>
                    <tr><td>
            <?php
                echo "<a href='view.php?lang=w&wnum=".$recs['wnum']."'>".
                        db::wfi($recs['word_wa'])."</a> (".$recs['word_ru'].")<br />";
            }
            ?>
                    </td></tr>
                    <tr><td>
            <?php
            //show words
            foreach($user_contributed_recs as $recs) {
                echo "<a href='view.php?lang=w&wnum=".$recs['wnum']."'>".
                        db::wfi($recs['word_wa'])."</a> (".$recs['word_ru']."<br />";
            }
            ?>
                    </td></tr>
                </tbody>
            </table>
            <hr width="78%" />
        <?php
            } else {
                if ($user['id'] <> 1) {
                    echo "Пользователь ещё не принимал участия в создании формул ".db::wfi("_ыэрдыъ")."<br />";
                } else {
                    echo db::wfi("_ыэргйо , _бази ХоУгВаюир , _тваргйо , _хор лаь_оР")."<br />";
                }
            }
        if ($_SESSION['id'] == $user['id']) { ?>
        </td>
        <tr>
            <td align="right">
                <input type="submit" value="Изменить данные" />
            </td>

        </tr>
        <?php }?>
    </tr>
</table>

<?php
    }

    public function update_user($userdata) {
        $q = "UPDATE userlist SET `username`='".$userdata['username'].
            "', `additional_data`='".$userdata['additional_data']."', ";
        if (isset($userdata['pw']) && (strlen($userdata['pw']) <> 0)) {
          $q .= "`pw`=MD5('".$userdata['pw']."'), ";
        }
        $q .= "`email`='".$userdata['email']."' WHERE `id`=".$userdata['id'];
        if (!$r = $this->_query($q)) {
            $this->log_error(__FILE__, __FUNCTION__, __LINE__, "$q ||| ".$this->_error($this->conn));
            return false;
        }
        return true;
    }

    public function change_acl($uid, $new_acl) {
        $q = "UPDATE userlist SET acl=$new_acl WHERE id=$uid";
        if (!$r = $this->_query($q)) {
            $this->log_error(__FILE__, __FUNCTION__, __LINE__, "$q ||| ".$this->_error($this->conn));
            return false;
        }
        return true;
    }

    public function verify_mail ($userdata) {
        $hash = md5($userid);
        $letter = "Уважаемый пользователь ".$userdata['username'].
                ", Вы изменили Ваш адрес электронной почты на сайте словаря WDN. \n".
                "Благодарим за Ваше внимание и просим подтвердить новый адрес. \n".
                "Для этого перейдите по ссылке (или скопируйте её в адресную строку браузера) \n".
                "http://".$_SERVER['HTTP_HOST']."/lib/auth.php?em=1&hash=$hash&uid=$userid\n".
                "Не отвечайте на это письмо - не услышим!\n".
                "\n\nС уважением, Администрация словаря Waer Dictionary New";
        if (DEBUG==1){$sent=true;}else{
        $sent = mail($email, "WDN - подтверждение регистрации", $letter, "From: ".WDN_ADMIN_EMAIL);}
        return $sent;
    }

    public function add_user($uname, $email, $pass, $addata){
        $req = "INSERT INTO userlist VALUES('".
                $this->_real_escape_string($uname).
                "', '".$email.//it was already validated, no need to escape ot
                "', MD5('".$pass.
                "'), '".$this->_real_escape_string($addata).
                "', NULL, NULL, NULL)";
        if (!$res = $this->_query($req)){
            $this->log_error("", __FUNCTION__, __LINE__, "$req ||| ".$this->_error($this->conn));
            self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
        }
        $q = "SELECT id FROM userlist WHERE username='".$this->_real_escape_string($this->conn,$uname)."'";
        $r = $this->_query($q);
        $user = $this->_fetch_assoc($r);
        $hash = md5($user['id']);
        $letter = "Уважаемый пользователь $uname, Вы зарегистрировались на сайте словаря WDN. \n".
                "Благодарим за Ваше внимание и просим подтвердить регистрацию. \n".
                "Для этого перейдите по ссылке (или скопируйте её в адресную строку браузера) \n".
                "http://".$_SERVER['HTTP_HOST']."/lib/au.php?hash=$hash&uid=".$user['id']."\n".
                "Не отвечайте на это письмо - не услышим!\n".
                "\n\nС уважением, Администратор словаря Waer Dictionary New";
        if (DEBUG==1){
            $sent=true;
        }else{
            $sent = mail($email, "WDN - подтверждение регистрации", $letter, "From: ".WDN_ADMIN_EMAIL);
        }
        return $sent;
    }

    public static function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    public function log_error($file, $function, $line, $data){
        $username = ""; $acl = -1;
        if (isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $acl = $_SESSION['acl'];
        }else{
            $username="anonymous";
            $acl=-1;
        }
        $req = "INSERT INTO errorlog VALUES(".
                "NULL, '$file', '$function', '$line', '$username', $acl, NULL, '".
                $this->_real_escape_string($data)."')";
        if (!$res = $this->_query($req)){
            return false;
        }
        if (DEBUG == 1){
            echo $req;
        }
        return true;
    }

    public function get_userlist () {
        $req = "SELECT * FROM userlist";
        if (!$res = $this->_query($req)) {
            $this->log_error(__FILE__, __FUNCTION__, __LINE__, "$req ||| ".$this->_error($this->conn));
            return false;
        }
        $ret = array();
        while ($str = $this->_fetch_assoc($res)) {
            $ret[] = $str;
        }
        return $ret;
    }

    public function approve_user($uid, $hash){
        if (strcmp($hash, md5($uid))<>0){
            return false;
        }else{
            $request = "UPDATE userlist SET acl=1 WHERE id=$uid";
            if (!$result = $this->_query($request)){
                self::harakiri($uid, __FUNCTION__, __LINE__, $this->_error($this->conn));
            }
            return true;
        }
        return true;
    }

    function __destruct() {
        $this->_close($this->conn);
    }

    public function show_userlist () {
        $q = "SELECT * FROM userlist ORDER BY username ASC LIMIT 11";
        if (!$r = $this->_query($q)) {
            $this->log_error(__FILE__, __FUNCTION__, __LINE__, "$q ||| ".$this->_error($this->conn));
            return false;
        }
        while ($str = $this->_fetch_assoc($r)){
            $ul[] = $str;
        }
        foreach ($ul as $user) {
            if ($user['acl'] == 0) {
                echo db::wfi( db::bleed( "_ы" ) );
            }else {
                echo "&nbsp&nbsp&nbsp";
            }
            echo "<a href='/profile/user.php?u=".$user['id']."'> ".$user['username']."</a><br />";
?>
<?php
        }
    }

}

/**
 * Class, owning database operation methods
 *
 * @author al
 * @version 2.0.0
 */

class db {

  /* database connection part*/
  private $conn;

  function __construct() {
    $this->conn = mysqli_connect(MSQL_SERVER_NAME, MSQL_SERVER_USER, MSQL_SERVER_PASS, MSQL_SERVER_BASE);
    mysqli_query($this->conn, "SET NAMES cp1251");
    mysqli_select_db($this->conn, MSQL_SERVER_BASE);
  }

  function __destruct() {
    mysqli_close($this->conn);
  }

  function _query ($query) {
    return mysqli_query($this->conn, $query);
  }

  function _fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
  }
  
  function _fetch_array($result) {
      return mysqli_fetch_array($result);
  }

  function _real_escape_string ($text) {
    return mysqli_real_escape_string($this->conn, $text);
  }

  /* end database connection part*/

    private $waers=array(
        "э"=>"a","ы"=>"b","ь"=>"c","я"=>"d","з"=>"e","а"=>"f","ъ"=>"g","м"=>"h","ю"=>"i","г"=>"j","к"=>"k",
        "т"=>"l",  "в"=>"m",  "у"=>"n",  "щ"=>"n",  "б"=>"o",  "о"=>"p",  "Р"=>"q",  "ш"=>"r",  "л"=>"s",  "с"=>"t",  "ё"=>"u",
        "р"=>"v",  "ж"=>"w",  "й"=>"x",  "п"=>"y",  "х"=>"z",  "е"=>"а",  "и"=>"б",  "В"=>"в",  "д"=>"г",  "ц"=>"д",  "н"=>"е",
        "Х"=>"zc", "Д"=>"ег", "Е"=>"аf", "Ё"=>"аp", "З"=>"гz", "И"=>"бб", "Н"=>"еj", "Э"=>"fa");

    /**
     * Gets from DB a document
     * @param type $id number of the document to get
     * @param type $for_editor whether to return entized text or not
     * @return array document
     */
    public function get_doc($id, $for_editor = false) {
      $q = "SELECT * FROM documents WHERE id=$id";
      if (!$r = $this->_query($q)) {
        $this->log_error(__FILE__, __FUNCTION__, __LINE__, $q);
        return false;
      }
      $result = $this->_fetch_assoc($r);
      if ($this->doc_get_deletedP($result['name'])) {
        return false;
      }
      return $result;
    }

    /**
     * Function saves or updates document in DB
     * @param document_record $doc
     * @return boolean/integer Rather false or insert id
     */
    public function save_doc($doc) {
      if (!is_numeric($doc['id']) || $doc['id'] == '') {
        //CREATE NEW
        $q = "INSERT INTO documents VALUES(".
          "NULL, '".
          $this->_real_escape_string($doc['name'])."', '".
          $this->_real_escape_string($doc['content'])."', '".
          $this->get_CTS()."', '".
          $this->get_CTS()."', '".
          $this->_real_escape_string($doc['url']). "', '".
          $this->_real_escape_string($doc['kw']). "', '".
          $this->_real_escape_string($doc['ds']). "', '".
          $this->_real_escape_string($doc['author']). "'".
          ")";
        if (!$r = $this->_query($q)){
          $this->log_error(__FILE__, __FUNCTION__, __LINE__, $q);
          return false;
        }
      }else{
        //UPDATE EXISTING
        $q = "UPDATE documents SET".
          "`name`='".$this->_real_escape_string($doc['name'])."',".
          "`content`='".$this->_real_escape_string($doc['name'])."',".
          "`modified`='".$this->get_CTS()."', ".
          "`url`='".$this->_real_escape_string($doc['url'])."', ".
          "`kw`='".$this->_real_escape_string($doc['kw'])."', ".
          "`ds`='".$this->_real_escape_string($doc['ds'])."', ".
          "`author`='".$this->_real_escape_string($doc['author'])."' WHERE id=".$doc['id'];
        if (!$r = $this->_query($q)){
          $this->log_error(__FILE__, __FUNCTION__, __LINE__, $q);
          return false;
        }
      }
      return $this->_insert_id($this->conn);
    }

    /**
     * Gets a list of documents.
     * @return array False on error or a list of id, name, url for each doc in DB
     */
    public function get_doclist() {
      $q = "SELECT * FROM documents";
      if (!$r = $this->_query($q)){
        $this->log_error(__FILE__, __FUNCTION__, __LINE__, $q);
        return false;
      }
      $ret;
      while ($str = $this->_fetch_assoc($r)) {
        if (!$this->doc_get_deletedP($str['name'])){
          $ret[] = $str;
        }
      }
      return $ret;
    }

    public function log_error($file, $function, $line, $data){
        $username = ""; $acl = -1;
        if (isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $acl = $_SESSION['acl'];
        }
        $req = "INSERT INTO errorlog VALUES(".
                "NULL, '$file', '$function', '$line', '$username', $acl, NULL, '".
                $this->_real_escape_string($data)."')";
        if (!$res = $this->_query($req)){
            return false;
        }
        if (DEBUG == 1){
            echo $req;
        }
        return true;
    }

    public function verify_user($username, $password) {
        $uquery = "SELECT * FROM userlist WHERE username='".$this->_real_escape_string($username)."' AND ".
                "pw=MD5('".$password."')";
        if(!$result = $this->_query($uquery)){
            if (DEBUG==1){
                db::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
            }
            return false;
        } else {
            $returnval = $this->_fetch_assoc($result);
            if ($returnval['acl'] < 0) {
                return false;
            }
            return $returnval;
        }
        //we never come here
        self::harakiri("Unusual operation", __FUNCTION__, __LINE__, $this->_error($this->conn));
    }

    public function find_if_deletion_requested($wnum){
        $req = "SELECT * FROM deletion_queue WHERE wnum=$wnum";
        if (!$result = $this->_query($req)){
            return false;
        }
        $delreq = $this->_fetch_assoc($result);
        if(is_null($delreq)){return false;}
        return $delreq;
    }

    public function get_all(){
        $result = array();
        $query = "SELECT * FROM wordlist";
        $re = $this->_query($query);
        if (!$re){
            self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
        }
        while($string = $this->_fetch_assoc($re)) {
            $string['description'] = self::unescape_str($string['description']);
            $result[] = $string;
        }
        return $result;
    }

    public function get_all_by_russian(){
        $acl=0;
        if (isset($_SESSION['acl'])) {$acl=$_SESSION['acl'];}
        $q = "SELECT * FROM wordlist WHERE acl<=".$acl." ORDER BY word_ru";
        if (!$res = $this->_query($q)){
            self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
        }
        $data = array();
        while($str = $this->_fetch_assoc($res)){
            $str['description'] = self::unescape_str($str['description']);
            $data[] = $str;
        }
        return $data;
    }

    public function get_all_by_waergowr(){
        $acl=0;
        if (isset($_SESSION['acl'])) {$acl=$_SESSION['acl'];}
        $q = "SELECT * FROM wordlist WHERE acl<=".$acl." ORDER BY waerid";
        if (!$res = $this->_query($q)){
            self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
        }
        $data = array();
        while($str = $this->_fetch_assoc($res)){
            $str['description'] = self::unescape_str($str['description']);
            $data[] = $str;
        }
        return $data;
    }

    public function get_by_wnum($wnum){
        $query = "SELECT * FROM wordlist WHERE wnum='".  $this->_real_escape_string($wnum)."'";
        if (!$res = $this->_query($query)){
            return false;
        }
        $str = $this->_fetch_assoc($res);
        $str['description'] = self::unescape_str($str['description']);
        return $str;
    }

    public function get_by_russ_letter($letter){
        $acl=0;
        if (isset($_SESSION['acl'])){
                $acl = $_SESSION['acl'];
        } //$q = "SELECT * FROM wordlist WHERE word_ru LIKE '$letter%' AND acl<=$acl ORDER BY word_ru ASC";
        $q = "SELECT * FROM wordlist WHERE word_ru LIKE '$letter%' ORDER BY word_ru ASC";
        if (!$res = $this->_query($q)){
            self::harakiri($q, __FUNCTION__, __LINE__, $this->_error($this->conn));
        }
        $result = array();
        while($string = $this->_fetch_assoc($res)){
            $string['description'] = self::unescape_str($string['description']);
            $result[] = $string;
        }
        return $result;
    }

    public function get_by_waer_hongwa($hongwa){
        $acl=0;
        if (isset($_SESSION['acl'])){
                $acl = $_SESSION['acl'];
        }
        $q = "SELECT * FROM wordlist WHERE word_wa LIKE '$hongwa%' ORDER BY waerid ASC";
        if (!$res = $this->_query($q)){
            self::harakiri($hongwa, __FUNCTION__, __LINE__, $this->_error());
        }
        $result = array();
        while($string = $this->_fetch_assoc($res)){
            $string['description'] = self::unescape_str($string['description']);
            $result[] = $string;
        }
        return $result;
    }
    
    private function _error() {
        return mysqli_error($this->conn);
    }

    public function get_by_gowr ($word){
        $query = "SELECT * FROM wordlist WHERE word_wa='".  $this->_real_escape_string($word)."'";
        if (!$res = $this->_query($query)){
            return false;
        }
        $str = $this->_fetch_assoc($res);
        $str['description'] = self::unescape_str($str['description']);
        return $str;
    }

    public function get_by_russ($word){
        $query = "SELECT * FROM wordlist WHERE word_ru='".  $this->_real_escape_string($word)."'";
        if (!$res = $this->_query($query)){
            return false;
        }
        $str = $this->_fetch_assoc($res);
        $str['description'] = self::unescape_str($str['description']);
        return $str;
    }

    public function get_by_acl($acl){
        $query = "SELECT * FROM wordlist WHERE acl='".  $this->_real_escape_string($acl)."'";
        if (!$res = $this->_query($query)){
            return false;
        }
        $str = $this->_fetch_assoc($res);
        $str['description'] = self::unescape_str($str['description']);
        return $str;
    }

    public function get_list_by_creator($username){
        $query = "SELECT * FROM wordlist WHERE creator='".  $this->_real_escape_string($username)."'";
        if (!$res = $this->_query($query)){
            return false;
        }
        $result = array();
        while($string = $this->_fetch_assoc($res)){
            $string['description'] = self::unescape_str($string['description']);
            $result[] = $string;
        }
        return $result;
    }

    public function get_list_by_contributor($username){
        $query = "SELECT * FROM wordlist WHERE INSTR('contributors_list', '{".
                $this->_real_escape_string($username)."}') > 0";
        if (!$res = $this->_query($query)){
            return false;
        }
        $result = array();
        while($string = $this->_fetch_assoc($res)){
            $string['description'] = self::unescape_str($string['description']);
            $result[] = $string;
        }
        return $result;
    }

    public function get_by_morpheme ($morpheme) {
        $query = "SELECT * FROM wordlist WHERE (morphid & $morpheme)";
        $qr = $this->_query($query);
        if (!$qr) {return false;}
        $result = array();
        while ($string = $this->_fetch_assoc($qr)){
            $string['description'] = self::unescape_str($string['description']);
            $result[] = $string;
        }
        return $result;
    }

    private function write_log_final($record){
        $query = "SELECT * FROM wordlist WHERE wnum=".$this->_real_escape_string($record['wnum']);
        if (!$re = $this->_query($query)){
            return false;
        }
        $olddata = "";
        foreach ($record as $key=>$value) {
            if (strcmp($value, $oldrec[$key])!=0){
                $olddata .= $key."=".$oldrec[$key]."%%%";
            }
        }
        $commit = "INSERT INTO wordlist_version_control VALUES('".
                $_SESSION['username'].
                "', '".$this->_real_escape_string($record['wnum']).
                "', NULL, '".$this->_real_escape_string($olddata).
                "', '".$_SESSION['acl'].
                "', 'administrative action', '".LOG_ACTION_CHANGE."', NULL)"; // 0 for action is @change@, 1 for action is @deletion@
        if (!$cre = $this->_query($commit)){
            self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
        }
        return $this->_insert_id($this->conn);
    }

    /**
     * Function to get current timestamp
     * @return string MySQL timestamp of current time
     */
    public function get_CTS(){
        $q = "SELECT CURRENT_TIMESTAMP()";
        $result = $this->_query($q);
        $timestamp = $this->_fetch_array($result)[0];
        return $timestamp;
    }

    private function set_synonims_wa($record){
        $synonims = explode(",", $record['syns_wa']);
        $q = "SELECT CURRENT_TIMESTAMP()";
        $result = $this->_query($q);
        $timestamp = $this->_fetch_array($result)[0];
        foreach ($synonims as $word){
            $query = "INSERT INTO synonims VALUES('".
                    $this->_real_escape_string($word).
                    "', '".$this->_real_escape_string($record['wnum']).
                    "', '".$this->waer_id($word).
                    "', '".$_SESSION['acl'].
                    "', '".$_SESSION['username'].
                    "', '".$this->_real_escape_string ($record['created']).
                    "', '".$timestamp.
                    "', NULL)";
            if (!$insres = $this->_query($query)){
                self::harakiri($query, __FUNCTION__, __LINE__, $this->_error($this->conn));
            }
        }
    }

    private function set_synonims_ru($record){
        $synonims = explode(",", $record['syns_ru']);
        $q = "SELECT CURRENT_TIMESTAMP()";
        $result = $this->_query($q);
        $timestamp = $this->_fetch_array($result)[0];
        foreach ($synonims as $word){
            $query = "INSERT INTO synonims VALUES('".
                    $this->_real_escape_string($word).
                    "', '".$this->_real_escape_string($record['wnum']).
                    "', '".$this->waer_id($word).
                    "', '".$_SESSION['acl'].
                    "', '".$_SESSION['username'].
                    "', '".$this->_real_escape_string($record['created']).
                    "', '".$timestamp.
                    "', NULL)";
            if (!$insres = $this->_query($query)){
                self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
            }
        }
    }

    public function find_free_wnum(){
//        $newnumber = 0;
//        $req1 = "SELECT * FROM wnums WHERE reserved=0";
//        if (!$res1 = $this->_query($req1)){
//            $rc = $this->_query("SELECT wnum FROM wordlist");
//            if (!$rc){self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));}
//            $wholedb=array(); //just numbers of entities, not IDs or content
//            while ($dbline = $this->_fetch_assoc($rc)){
//                $wholedb[] = $dbline;
//            }
//            /* here we find an empty entity_number to fill in, for the database to be +- not fragmentized
//             */
//            for($i=1;$i<=count($wholedb);$i++){
//                if (!in_array($i, $wholedb)){
//                    $newnumber=$i;
//                    break;
//                }
//            }
//            if ($newnumber == 0) {
                $rc = $this->_query("SELECT MAX(wnum) FROM wordlist");
                $dbline=$this->_fetch_assoc($rc);
                $newnumber = $dbline['MAX(wnum)']+1;
//            }
//            $resqu = "INSERT INTO wnums VALUES($newnumber, 1)";
//            $resre = $this->_query($resqu);
//        }else{
//            $newnumber = $this->_fetch_array($res1)[0];
//            $resqu = "UPDATE wnums SET reserved=1 WHERE wnum=$newnumber";
//            $resre = $this->_query($resqu);
//        }
        return $newnumber;
    }

    public function serialize_rec($record) {
        $rec = "";
        foreach ($record as $key=>$value) {
            $rec .= $key."=".$value."%%%";
        }
        $rec = substr($rec, 0, -3);
        return $rec;
    }

    public function deserialize_rec($string){
        $prev = explode("%%%", $string);
        $result = array();
        foreach($prev as $line){
            $str = explode("=", $line);
            $result[$str[0]] = $str[1];
        }
    }

    public function get_random_record() {
      $q = "SELECT MAX(id) FROM wordlist";
      $r = $this->_query($q);
      $max = $this->_fetch_array($r)[0];
      while (1) {
        $random = mt_rand(0, $max);
        $q = "SELECT * FROM wordlist WHERE id=$random";
        if (!$res = $this->_query($q)) {
          //if id was deleted, e.g.
          continue;
        }else{
          break;
        }
      }
      $record = $this->_fetch_assoc($res);
      return $record;
    }

    public function remove_rec($wnum) {
        if (!isset($_SESSION['username'])){
            return false;
        }
        if (!is_numeric($wnum)){
            return false;
        }
        $record = $this->get_by_wnum($wnum);
        if ($_SESSION['acl'] == 0) {
            $request = "DELETE FROM wordlist WHERE wnum=".$wnum;
            if (!$res = $this->_query($request)){
                $this->log_error(__FILE__, __FUNCTION__, __LINE__, "$q ||| ".$this->_error($this->conn));
                self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
            }
            $dlr = "INSERT INTO wordlist_version_control VALUES('".
                    $_SESSION['username'].
                    "', ".$wnum.
                    ", NULL".
                    ", '".$this->serialize_rec($record).
                    "', 0, '', ".LOG_ACTION_REMEMBER.", NULL)";
            if (!$dlres = $this->_query($dlr)){
                $this->log_error(__FILE__, __FUNCTION__, __LINE__, "$q ||| ".$this->_error($this->conn));
                //self::debug($record);
                echo self::bleed("<b>Failed to save deleted entry</b><br />");
                return false;
            }
            return true;
        }else{
            $dlr = "INSERT INTO wordlist_version_control VALUES('".
                    $_SESSION['username'].
                    "', '".$wnum.
                    "', NULL".
                    ", '".$this->serialize_rec($record).
                    "', '".$_SESSION['acl'].
                    "', '".$this->_real_escape_string($_POST['reason']).
                    "', ".LOG_ACTION_DELETE.", NULL)";
            if (!$dlrq = $this->_query($dlr)) {
                self::harakiri($_SESSION['deletion_reason'], __FUNCTION__, __LINE__, $this->_error($this->conn));
            }
            $dqq = "INSERT INTO deletion_queue VALUES(NULL, '".$wnum."', '".$_SESSION['username']."', NULL, ".$this->_insert_id($this->conn).")";
            if (!$dqqr = $this->_query($dqq)){
                self::harakiri($_SESSION['deletion_reason'], __FUNCTION__, __LINE__, $this->_error($this->conn));
            }
            return true;
        }
    }

    private function doc_set_deleted($name) {
      return "NULL_$name";
    }

    private function doc_get_deletedP($name) {
      if (strstr($name, "NULL_") <> 0) {
        return true;
      }
      return false;
    }

    private function doc_unset_deleted($name) {
      if ($this->doc_get_deletedP($name)) {
        return substr($name, 5);
      }
      return $name;
    }

    public function remove_doc($doc_id) {
        if (!isset($_SESSION['username'])){
            return false;
        }
        if (!is_numeric($doc_id)){
            return false;
        }
        if ($_SESSION['acl'] == 0) {
            $request = "DELETE FROM documents WHERE id=".$doc_id;
            if (!$res = $this->_query($request)){
                $this->log_error(__FILE__, __FUNCTION__, __LINE__, "$q ||| ".$this->_error($this->conn));
                self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
            }
            return true;
        }else{
            $doc = $this->get_doc($doc_id);
            $doc['name'] = $this->doc_set_deleted($doc['name']);
            $this->save_doc($doc);
            $dqq = "INSERT INTO documents_deletion_queue VALUES(NULL, '".$doc_id.
              "', '".$_SESSION['username']."', NULL, NULL)";
            return true;
        }
    }

    /**
     * Shows short spoilers of the documents with creation dates
     * @param type $count how many
     * @param type $page return value of the previous call, calculated in pages of size $count
     */
    public function show_last_documents_short($count, $page){
      $start_rec = ($page-1) * $count;
      $q = "SELECT * FROM documents ORDER BY created ASC LIMIT $start_rec, $count";
      if (!$r = $this->_query($q) ){
        $this->log_error(__FILE__, __FUNCTION__, __LINE__, $q);
        db::bleed("SHIT HAPPENS...");
        return false;
      }
        ?>
        <table>
        <?php while ($doc = $this->_fetch_assoc($r)) { ?>
          <tr border="1">
            <td>
              <?php
              echo $doc['created'];
              if ($doc['created'] <> $doc['modified']) {echo "<i><sub>modified: ".$doc['modified']."</sub></i>"; }
              echo "-&gt".$doc['author']."&lt-<hr />";
              echo "<h3>".db::bleed($doc['name'])."</h3>";
              echo $doc['spoiler'];
              echo "<a href='/viewdoc.php?id=".$doc['id']."'>Читать далее...</a>";
              ?>
            </td>
          </tr>
        <?php } ?>
        </table>
<?php
      return $page+1;
    }

    public function docpages_count ($pagesize) {
      $q = "SELECT COUNT(name) FROM documents";
      $r = $this->_query($q);
      $res = $this->_fetch_array($r)[0];
      $totalpages = ceil($res/$pagesize);
      return $totalpages;
    }

    /**
     * Function, called either for new record and for updating old one
     * @param type $record
     * @return boolean
     */
    public function save_rec($record) {
        if (!isset($_SESSION['username'])) {
            return false; //no unauthorized corrections
        }
        $q = "SELECT CURRENT_TIMESTAMP()";
        $tres = $this->_query($q);
        $timestamp = $this->_fetch_assoc($tres)[0];
        if (!isset($record['wnum'])) { //new record
            $record['wnum'] = $this->find_free_wnum();
            $wq = "INSERT INTO wordlist VALUES (".
                    "'"                 .$this->_real_escape_string($record['word_wa']).
                    "', "               .$this->_real_escape_string($record['wnum']).
                    ", '"               .$this->_real_escape_string($record['word_ru']).
                    "', '"              .$this->_real_escape_string($record['syns_wa']).
                    "', '"              .$this->_real_escape_string($record['syns_ru']).
                    "', "               .$this->_real_escape_string($record['morphid']).
                    ", '"               .$record['waerid'].
                    "', '"              .$this->_real_escape_string($record['description']).
                    //first we need to check for user acl, whether to set acl to approved or for check
                    "', "               .$_SESSION['acl'].
                    ", '"               .$_SESSION['username'].
                     //first we need to search for the existing name, or append new contributor on the base of current user
                    "', '"              .$_SESSION['username'].
                    "', '$timestamp', '$timestamp', NULL";
                ")";
            $lcq = "INSERT INTO change_queue VALUES(NULL, ".
                    $this->_real_escape_string($record['wnum']).
                    ", '".$_SESSION['username'].
                    "', NULL, ";
        }else{ // existing record
            $contributors = explode(",", $record['contributors_list']);
            $uexists = false;
            foreach ($contributors as $name) {
                if (strcmp($name, $_SESSION['username']) == 0){
                    $uexists = true;
                    break;
                }
            }
            if ($uexists == false) {
                $record['contributors_list'] .= ",".$_SESSION['username'];
            }
            if (!is_numeric($record['acl'])) {
                $record['acl'] = 2;
            }
            if (!is_numeric($record['morphid'])) {
                $record['acl'] = 2; //we set acl to "check required", since we have no idea, what morpheme really this one is
                $record['morphid'] = M_ROOT;
            }
            $wq = "UPDATE wordlist SET ".
                    "word_wa='"                 .$this->_real_escape_string($record['word_wa']).
                    "', word_ru='"               .$this->_real_escape_string($record['word_ru']).
                    "', syns_wa='"              .$this->_real_escape_string($record['syns_wa']).
                    "', syns_ru='"              .$this->_real_escape_string($record['syns_ru']).
                    "', morphid="               .$record['morphid'].
                    ", waerid='"                .$this->waer_id($record['waerid']).
                    "', description='"          .$this->_real_escape_string($record['description']).
                    //first we need to check for user acl, whether to set acl to approved or for check
                    "', acl="                   .$record['acl'].
                     //first we need to search for the existing name, or append new contributor on the base of current user
                    ", contributors_list='"    .$this->_real_escape_string($record['contributors_list']).
                    "', modified='$timestamp' WHERE wnum=".$record['wnum'];
            $lcqend = $this->write_log_final($record);
            $lcq = "INSERT INTO change_queue VALUES(NULL, ".
                    $this->_real_escape_string($record['wnum']).
                    ", '".$_SESSION['username'].
                    "', NULL, $lcqend)";
        }
        if (!$wres = $this->_query($wq)) {
            $this->log_error(__FILE__, __FUNCTION__, __LINE__, $this->_error($this->conn)."| $wq");
            $this->save_to_file($this->serialize_rec($record));
            return false;
        }
        $this->set_synonims_wa($record);
        $this->set_synonims_ru($record);
        if (!$res = $this->_query($lcq)){
            $this->log_error(__FILE__, __FUNCTION__, __LINE__, "FINAL LOG | ".$this->_error($this->conn));
        }
        return true;
    }

    /**
     * saves serialized data to a file, named after current user
     */
    private function save_to_file ($serialized_data){
      //create userdir if not exists
      if (!file_exists("/".$_SESSION['username'])) {
        mkdir("/".$_SESSION['username']);
      }
      if (!$fp = fopen($_SESSION['username']."/saved_data.".$_SESSION['username'], "a+")){
        $this->log_error(__FILE__, __FUNCTION__, __LINE__, $serialized_data);
      }
      fwrite($fp, "\n$serialized_data");
      flose($fp);
    }

    public static function morpheme($num_dsc){
        if (!is_numeric($num_dsc)) {
            return false;
        }
        $result = "";
        if ($num_dsc != 0){
            if (($num_dsc & M_PHRASE) != M_PHRASE){
                //$result = "может быть ";
                if (($num_dsc & M_ROOT) == M_ROOT){
                    //$result .= "самостоятельным корнем, ";
                    $result .= M_RO;
                }
                if (($num_dsc & M_PREFIX) == M_PREFIX){
                    //$result .= "префиксом, ";
                    $result .= M_PRF;
                }
                if (($num_dsc & M_PREPOSITION) == M_PREPOSITION){
                    //$result .= "предлогом, ";
                    $result .= M_PRF;
                }
                if (($num_dsc & M_POSTFIX) == M_POSTFIX){
                    //$result .= "постфиксом, ";
                    $result .= M_POF;
                }
                if (($num_dsc & M_POSTPOSITION) == M_POSTPOSITION){
                    //$result .= "послелогом, ";
                    $result .= M_PP;
                }
                $result = substr($result, 0, -2);
            }else{
                $result = M_PHR;
            }
        }
        return $result;
    }

    public function waer_id($word) {
        $result = "";
        for($i = 0; $i < strlen($word); $i++){
            if (key_exists(substr($word, $i, 1), $this->waers)){
                $result .= $this->waers[substr($word, $i, 1)];
            }
        }
        return $result;
    }

    public function search_word ($_word) {
        $result = array();
        $rc = $this->_query("SELECT * FROM wordlist ".
                             "WHERE MATCH(word_wa, word_ru, syns_ru, syns_wa) ".
                             "AGAINST('".$_word."')");
        if (!rc){
            self::harakiri("", __FUNCTION__, __LINE__, $this->_error($this->conn));
        }
        $occurrences_counter = $this->_affected_rows($this->conn);
        if ($occurrences_counter == 0){return false;}
        while ($entry = $this->_fetch_assoc($rc)){
            if (preg_match("/$_word/i", $entry['word_wa']) == 0) {
                if (preg_match("/$_word/i", $entry['word_ru']) == 0) {
                    if (preg_match("/$_word/i", $entry['syns_ru']) == 0) {
                        if (preg_match("/$_word/i", $entry['syns_wa']) != 0) {
                            $entry['found_in'] = 'syns_wa';
                            $result[$occurrences_counter] = $entry;
                        }
                    }else{
                        $entry['found_in'] = 'syns_ru';
                        $result[$occurrences_counter] = $entry;
                    }
                }else{
                    $entry['found_in'] = 'word_ru';
                    $result[$occurrences_counter] = $entry;
                }
            }else{
                $entry['found_in'] = 'word_wa';
                $result[$occurrences_counter] = $entry;
            }
        }
        return array("result" => $result, "occurrences" => $occurrences_counter);
    }

    public static function escape_str($value) {
        $search = array("\x00",	"\n", "\r", "\\", "'", "\"", "\x1a");
        $replace = array("\\x00", "\\n", "\\r", "\\\\" ,"\'", "\\\"", "\\\x1a");
        return str_replace($search, $replace, $value);
    }

    public static function unescape_str($value) {
        $replace = array("\x00",	"\n", "\r", "\\", "'", "\"", "\x1a");
        $search = array("\\x00", "\\n", "\\r", "\\\\" ,"\'", "\\\"", "\\\x1a");
        return str_replace($search, $replace, $value);
    }

    public static function wfi ($string) {
        $str = "<span style=\"font-family:'WaerCursive';\">$string</span>";
        return $str;
    }

    public static function wfr ($string) {
        $str = "<span style=\"font-family:'Waer';\">$string</span>";
        return $str;
    }

    public static function bleed ($string){
        return "<font color='red'>".$string."</font>";
    }

    public static function debug($object){
        echo "<pre>";
        var_dump($object);
        echo "</pre><br />";

    }

    public static function harakiri($data, $function, $line, $error){
        self::debug($data);
        die(self::bleed("<b>Failed to $function on line $line: $error</b><br .>"));
    }
}

class formKey
{
	//Here we store the generated form key
	private $formKey;

	//Here we store the old form key (more info at step 4)
	private $old_formKey;

	//The constructor stores the form key (if one excists) in our class variable
	function __construct()
	{
		//We need the previous key so we store it
		if(isset($_SESSION['form_key']))
		{
			$this->old_formKey = $_SESSION['form_key'];
		}
	}

	//Function to generate the form key
	private function generateKey()
	{
		//Get the IP-address of the user
		$ip = $_SERVER['REMOTE_ADDR'];

		//We use mt_rand() instead of rand() because it is better for generating random numbers.
		//We use 'true' to get a longer string.
		//See http://www.php.net/mt_rand for a precise description of the function and more examples.
		$uniqid = uniqid(mt_rand(), true);

		//Return the hash
		return md5($ip . $uniqid);
	}


	//Function to output the form key
	public function outputKey()
	{
		//Generate the key and store it inside the class
		$this->formKey = $this->generateKey();
		//Store the form key in the session
		$_SESSION['form_key'] = $this->formKey;

		//Output the form key
		echo "<input type='hidden' name='form_key' id='form_key' value='".$this->formKey."' />";
	}


	//Function that validated the form key POST data
	public function validate()
	{
		//We use the old formKey and not the new generated version
		if($_POST['form_key'] == $this->old_formKey)
		{
			//The key is valid, return true.
			return true;
		}
		else
		{
			//The key is invalid, return false.
			return false;
		}
	}
}
?>
