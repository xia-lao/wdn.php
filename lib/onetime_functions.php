<?php
class onetimeclass {
    public function redo_base () {
        mysql_select_db("awerbase_n");
        $query = "SELECT * FROM entities";
        $result = mysql_query($query);
        $db = array();
        while ($rec = mysql_fetch_assoc($result)) {
            $db[] = $rec;
        }
        mysql_query("DROP DATABASE IF EXISTS ".MSQL_SERVER_BASE);
        $createbd_q = "CREATE DATABASE IF NOT EXISTS ". MSQL_SERVER_BASE.
                " CHARACTER SET cp1251 COLLATE cp1251_general_ci";
        mysql_query($createbd_q) or die ("Failed creating db: ".mysql_error()."<br />");
        mysql_select_db(MSQL_SERVER_BASE);
        //create new table
        $query_create =
                "CREATE TABLE IF NOT EXISTS wordlist (".
                "word_wa TEXT NOT NULL, wnum INT (11) UNSIGNED, word_ru TEXT NOT NULL,".
                "syns_wa TEXT NOT  NULL, syns_ru TEXT NOT NULL, morphid INT (1) UNSIGNED NOT NULL,".
                "waerid TEXT NOT NULL, description TEXT NOT NULL,".
                "acl INT (2) NOT NULL DEFAULT 0,". //privileges level for accessing word; when -1 - word not approved, -2 - rejected
                "creator TEXT NOT NULL,". //name of the user-contributor
                "contributors_list TEXT NOT NULL,". //names of all contributors
                "created TIMESTAMP DEFAULT '0000-00-00 00:00:00', modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP, id INT(11) AUTO_INCREMENT PRIMARY KEY".
                ") ENGINE = MYISAM CHARACTER SET cp1251 COLLATE cp1251_general_ci";
        mysql_query($query_create) or die ("Mysql failed creating wordlist: " . mysql_error());
        //each synonim is linked to the source word and has its own number, which is as wnum:id for unique identification
        //acl may be different from the source word
        $qsyn = "CREATE TABLE IF NOT EXISTS synonims (word TEXT NOT NULL, syn_to INT(11) UNSIGNED NOT NULL".
                ", waer_id TEXT NOT NULL, acl INT(2) NOT NULL DEFAULT 0".
                ", creator TEXT NOT NULL".
                ", contributors_list TEXT NOT NULL".
                ", created TIMESTAMP DEFAULT '0000-00-00 00:00:00', modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP".
                ", id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY".
                ") ENGINE=MYISAM CHARACTER SET cp1251 COLLATE cp1251_general_ci";
        //wrd snt wid acl crt ctl NULLNULLNULL
        mysql_query($qsyn) or die ("Failed to create synonims list: ".mysql_error()."<br />");
        //insert records, having them changed
        $searcan = array ("Ы","Ф","Г","О","Х",/*"У",*/"хГ","нд","нг","еа","ео","аэ","пп","ии","дх");
        $replace = array ("ы","ц","ь","ъ","ю",/*"В",*/"Х", "Д", "Н", "Е", "Ё", "Э", "Ф", "И", "З");
        foreach ($db as $record) {
            //change necessary letters
            $record['entity'] = str_replace($searcan, $replace, $record['entity']);
            $record['synonims_ae'] = str_replace($searcan, $replace, $record['synonims_ae']);
            //make new waerid
            $record['waer_id'] = ""; //clear waer id, we need to recreate it
            for ($i = 0; $i < strlen($record['entity']); $i++) {
                if (key_exists(substr($record['entity'], $i, 1), $this->waers)) {
                    $record['waer_id'] .= $this->waers[substr($record['entity'], $i, 1)];
                }
            }
            //split synonims
            if (strlen($record['synonims_ae'])>1){
                $synonims = explode(",", $record['synonims_ae']);
                foreach ($synonims as $syn) {
                    $synwid = $this->waer_id($syn);
                    $synins = "INSERT INTO synonims VALUES (".
                            "'".mysql_real_escape_string($syn).
                            "', ".$record['entity_number'].
                            ", '".mysql_real_escape_string($synwid).
                            "', 0, 'Любосвет', 'Любосвет', NULL, NULL, NULL)";
                    $syninsres = mysql_query($synins);
                    if ($syninsres == 0) {
                        die ("Failed to insert record into synonims: : ".mysql_error()."<br />");
                    }
                }
            }
            // insert into new db words
            //wwa wnum wru swa sru mid wid des acl cre cli ctd mdd id
            $q_upd = "INSERT INTO wordlist VALUES (".
                    "'". mysql_real_escape_string($record['entity']).
                    "', ".$record['entity_number'].
                    ", '".mysql_real_escape_string($record['translation']).
                    "', '".mysql_real_escape_string($record['synonims_ae']).
                    "', '".mysql_real_escape_string($record['synonims_ru']).
                    "', ".$record['morph_id'].
                    ", '".mysql_real_escape_string($record['waer_id']).
                    "', '".mysql_real_escape_string($record['description']).
                    "', 0, 'Любосвет', 'Любосвет'".
                    ", '2010-05-04 11:30:00', NULL, NULL". //set timestamps and id
                    ")";
            $wordinsres = mysql_query($q_upd);// or die("Failed to insert record in wordlist: ". mysql_error()."<br />");
            if ($wordinsres == 0) {
                die ("Failed to insert record into wordlist: : ".mysql_error()."<br />");
            }
        }
        $backupcreate_q = "CREATE TABLE  IF NOT EXISTS wordlist_version_control (".
                "user TEXT NOT NULL".
                ", recid INT (11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY".
                ", modifitation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP".
                ", old_data TEXT NOT NULL".
                ", acl INT(1) DEFAULT '-1'".
                ") ENGINE=MYISAM CHARACTER SET cp1251 COLLATE cp1251_general_ci";

        $backupsynonimscreate_q = "CREATE TABLE IF NOT EXISTS synonims_version_control (".
                "user TEXT NOT NULL".
                ", recid INT (11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY".
                ", modifitation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP".
                ", old_data TEXT NOT NULL".
                ", new_data TEXT NOT NULL".
                ", acl INT(1) DEFAULT '-1'".
                ") ENGINE=MYISAM CHARACTER SET cp1251 COLLATE cp1251_general_ci";

        $userscreate_q = "CREATE TABLE IF NOT EXISTS userlist (".
                "username TEXT NOT NULL".
                ", email TEXT NOT NULL".
                ", pw VARCHAR(32)".
                ", additional_data TEXT".
                ", access_level INT(1) DEFAULT 0".
                ", registered TIMESTAMP DEFAULT NOW()".
                ", id INT (11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY".
                ") ENGINE=MYISAM CHARACTER SET cp1251 COLLATE cp1251_general_ci";
        $addme_q = "INSERT INTO userlist VALUES('Любосвет', 'ljubosvet@gmail.com'".
                ", MD5('abc'), 'I was in the beginning!', 1, NULL, NULL".
                ")";
        mysql_query($backupcreate_q) or die ("Failed creating vc table: ".mysql_error()."<br />");
        mysql_query($userscreate_q) or die ("Failed creating users table: ".mysql_error()."<br />");
        mysql_query($addme_q) or die ("Failed adding myself: ".mysql_error()."<br />");
        $passtest = "SELECT * FROM userlist WHERE username='Любосвет' AND pw = MD5('abc')";
        mysql_query($passtest);
        if (mysql_affected_rows() == 0) {
            die ("User not found<br />");
        }
        echo "Finished";
    }
    public static function correct_descriptions1() {
        $my_connection = mysql_connect(MSQL_SERVER_NAME, MSQL_SERVER_USER, MSQL_SERVER_PASS);
        if (!$my_connection) {
            self::harakiri("", __FUNCTION__, __LINE__, mysql_error());
        }
        mysql_query("set names cp1251", $my_connection);
        mysql_select_db(MSQL_SERVER_BASE);
        $rh = mysql_query("SELECT * FROM wordlist  WHERE description LIKE '%{%}%'");
        while ($rech = mysql_fetch_assoc($rh)){
            $str = $rech['description'];
            $tokens = self::parse_string_by_tags("{", "}", $str);
            for ($i = 1; $i < count($tokens); $i+=2){
                if (!strstr($tokens[$i-1], "ивр")){
                    $tokens[$i] = "{-r ".$tokens[$i]."}";
                }else{
                    $tokens[$i] = "{-h ".$tokens[$i]."}";
                }
            }
            $str = join($tokens);
            $query = "UPDATE wordlist SET description='".db::escape_str($str)."' WHERE wnum=".$rech['wnum'];
            if(!$res = mysql_query($query)){
                db::harakiri($str, __FUNCTION__, __LINE__, mysql_error());
            }
        }
    }
    public static function correct_descriptions() {
        $my_connection = mysql_connect(MSQL_SERVER_NAME, MSQL_SERVER_USER, MSQL_SERVER_PASS);
        if (!$my_connection) {
            self::harakiri("", __FUNCTION__, __LINE__, mysql_error());
        }
        mysql_query("set names cp1251", $my_connection);
        mysql_select_db(MSQL_SERVER_BASE);
        $rh = mysql_query("SELECT * FROM wordlist  WHERE description LIKE '%{%}%'");
        $searcan = array ("Ы","Ф","Г","О","Х",/*"У",*/"хГ","нд","нг","еа","ео","аэ","пп","ии","дх", "Я");
        $replace = array ("ы","ц","ь","ъ","ю",/*"В",*/"Х", "Д", "Н", "Е", "Ё", "Э", "Ф", "И", "З", "я");
        $hebsearc = array("Ф");
        $hebreplc = array("T");
        while ($rech = mysql_fetch_assoc($rh)){
            $str = $rech['description'];
            $tokens = self::parse_string_by_tags("{", "}", $str);
            for ($i = 1; $i < count($tokens); $i+=2){
                switch (substr($tokens[$i], 0, 2)){
                    case "-r":
                        //$tokens[$i] = mb_convert_case($tokens[$i], MB_CASE_LOWER);
                        $tokens[$i] = str_replace($searcan, $replace, $tokens[$i]);
                        break;
                    case "-h":
                        $tokens[$i] = str_replace($hebsearc, $hebreplc, $tokens[$i]);
                        break;
                    default:
                        echo "Strange inclusion: ".$rech['wnum']."<br />";
                        db::debug($rech);
                        echo "<hr />";
                }
                $tokens[$i] = "{".$tokens[$i]."}";
            }
            $str = join($tokens);
            $query = "UPDATE wordlist SET description='".db::escape_str($str)."' WHERE wnum=".$rech['wnum'];
            if(!$res = mysql_query($query)){
                db::harakiri($str, __FUNCTION__, __LINE__, mysql_error());
            }
        }
    }
}
?>
