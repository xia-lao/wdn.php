<?php
//die();
define ("DEBUG", 0);//TODO: comment out for production site

define ("MSQL_SERVER_NAME", "localhost"); //
define ("MSQL_SERVER_USER", "root"); //waeruser
define ("MSQL_SERVER_PASS", "123"); //xYF4cZfm
define ("MSQL_SERVER_BASE", "waerdb_u8");
define ("WDN_ADMIN_EMAIL",  "ljubosvet@gmail.com");

define ("M_ROOT",           1);
define ("M_PREFIX",         2);
define ("M_POSTFIX",        8);
define ("M_PHRASE",         16); //flag, which voids the other flags
define ("M_PREPOSITION",    32);
define ("M_POSTPOSITION",   64);
define ("M_TEXT",           128); //flag, which voids the other flags

define ("M_RO",     "������, ");
define ("M_PRF",    "�������, ");
define ("M_POF",    "��������, ");
define ("M_PP",     "��������, ");
define ("M_PHR",    "�����");

define ("LOG_ACTION_DELETE",    1);
define ("LOG_ACTION_CHANGE",    0);
define ("LOG_ACTION_REMEMBER",  2);

define ("_I18N_ROOT",   "������");
define ("_I18N_PREF",   "���������");
define ("_I18N_PREP",   "�������");
define ("_I18N_POSF",   "���������");
define ("_I18N_POST",   "��������");
define ("_I18N_PHRS",   "�����");

define ("RECAPTCHA_PUBLIC_K", "6Lf15fISAAAAAGKORsOE-NWCQ_ADALKaynPCPTrF");
define ("RECAPTCHA_PRIVATE_K", "6Lf15fISAAAAAF9-0zLgUD5T4Yxu8i_MmehNXc25");

//define ("", "");

?>
