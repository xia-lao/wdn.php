<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sample of Using RoCanvas.js</title>
  <link rel="stylesheet" href="rocanvas.css?v=1.0">
  <style>
  body
  {
  		font-family: verdana, sans-serif;
  }
  </style>
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
	<h1>Example of Using RoCanvas.js</h1>

	<p>For more info how this works see <a href="http://re.trotoys.com/article/rocanvas/" target="_blank">RoCanvas Documentation</a>.</p>

	<h2>Default Settings Sample</h2>

	<p>Below is just a canvas element that's turned into RoCanvas using the default settings.</p>

	<div id="rodiv1"><canvas id="sampleBoard" width="500" height="500" style="border:1pt solid black"></canvas></div>

	<h3>Source:</h3>
	<code><pre>
	var r=new RoCanvas;
	r.RO("sampleBoard");
	</pre></code>

	<h2>Custom Settings Sample</h2>

	<div id="rodiv2"><canvas id="sampleBoard2" width="700" height="500" style="border:1pt solid red"></canvas></div>

	<h3>Source:</h3>
	<code><pre>
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
	</pre></code>

  <script src="rocanvas.js"></script>
  <script>
   var r=new RoCanvas;
	r.RO("sampleBoard");

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

	function testSave(instance)
	{
	   var data = instance.serialize();

	   // send ajax request to some URL using the data
	   // to do...
	}
  </script>
</body>
</html>
