<?php require 'api/init.php'; ?>

<!doctype html>
<html ng-app="ncn">
<head>
	<title>Nano Carbon Now!</title>
	<meta charset='utf-8'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="assets/index.css">
</head>
<body>

<div id="w" 
	ng-controller="NcnCtrl"
	ng-init="initNcn()">
	
	<header 
		id="mh"
		class="animated fadeInDown">
		<div id="mh-heading">
			<!--h1>Nano Carbon Now foundation</h1-->
		</div>
	</header>
	
	<div id="mc">
		<?php require $model->view; ?>
	</div>

	<div id="mn">
		<ul class="animated fadeInRight">
			<li><a href="/">Fundacja</a></li>
			<li><a href="/news">Aktualności</a></li>
			<li><a>Oferta</a></li>
			<li><a>Partnerzy</a></li>
			<li><a>Biuro prasowe</a></li>
			<li><a>Multimedia</a></li>
			<li><a href="/gallery">Galeria</a></li>
			<li><a>{{item.name}}</a></li>
		</ul>
	</div>
	<!--ul id="x-socials">
		<li><a href="" class="icon-facebook-squared facebook"></a></li>
		<li><a href="" class="icon-twitter-squared twitter"></a></li>
		<li><a href="" class="icon-gplus-squared gplus"></a></li>
		<li><a href="" class="icon-pinterest-squared pinterest"></a></li>
	</ul-->
	<footer 
		id="mf"
		class="animated fadeInUp">
		
	</footer>
</div>

<script type="text/javascript" src="app/vendor/angular.js"></script>
<script type="text/javascript" src="app/vendor/angular-animate.js"></script>
<script type="text/javascript" src="app/main.js"></script>
</body>
</html>