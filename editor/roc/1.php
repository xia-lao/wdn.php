<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="rocanvas.css?v=1.0">
</head>
<body>
<table border=1><tr><td>
	<div id="rodiv1"><canvas id="sampleBoard2" width="500" height="500" style="border:1pt solid black"></canvas></div>
	</td></tr></table>

  <script src="rocanvas.js"></script>
  <script>
   var r2=new RoCanvas;
	r2.RO("sampleBoard2", {
		"toolbar": {
		   colors: ["pink", "#FFF","#000","#FF0000","#00FF00","#0000FF","#FFFF00","#00FFFF"],
		   custom_color: false,
			tools: ['path', 'circle', 'rectangle'],
			sizes: null,
			saveButton: {"text": "Save", "callback": "testSave(r2);"}
		},
		"settings": {
			color: 'pink'
		}
	});
	</script>

</body>
</html>
