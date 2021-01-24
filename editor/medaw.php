<?php
include_once '../lib/wdn.php';
//$db = new db;
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
      <title>WDN - Создание Медаw</title>
      <?php wdn::echo_metas(); ?>
      <link href="/editor/roc/rocanvas.css?v=1.0" type="text/css" rel="stylesheet">

  </head>
  <body>
      <canvas
      width="156"
      height="156"
      style="border:1pt dotted red"
      id="medawdrawer"
      >Не сработает в вашем браузере... Соррьки..</canvas>
      <script src="/editor/roc/rocanvas.js"></script>
      <script>
        var r=new RoCanvas;
        r.RO("medawdrawer", {'toolbar': {saveButton: {"text": "Сохранить", "callback": "fnSave(r);"}}});
        function fnSave(instance)
        {
           var data = instance.serialize();
           window.opener.semjaform.medaw.value = data;
           window.close();
        }
      </script>
  </body>
