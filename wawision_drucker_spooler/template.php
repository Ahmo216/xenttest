<!doctype html>
<html lang="de" ng-app="macPrinterApp">
	<head>
		<title>Mac Printer</title>
		<link rel="stylesheet" type="text/css" href="css/app.css">
	</head>
	<body>

	<div class="template" ng-controller="MainCtrl">
		<div class="template_header">

			<img src="images/wawision_logo_gruen_weiss2.gif">

		</div>

		<div class="template_content">
			<div class="template_navigation">
				<ul>
					<li><a href="#/">Dashboard</a></li>
					<li><a href="#/printers">Drucker</a></li>
					<li>
						<a href="#/statistik">Statistik</a>
						<ul>
					<!--		<li>
								<a href="#/chartstatistik/">Charts</a>
							</li>-->
						</ul>
					</li>
					<li><a href="#/settings">Einstellungen</a></li>
				</ul>

				<div class="api_status {{api.status}}">{{api.message}}</div>

			</div>
			<div class="template_main">
				<div ng-view></div>
			</div>
			<div class="clear"></div>
		</div>

	</div>

	<div id="loaderDiv" loader>
	    <img src="images/ajax-loader.gif" class="ajax-loader"/>
	</div>

		<script type="text/javascript" src="js/jquery-1.12.2.min.js"></script>
		<script type="text/javascript" src="js/angular.js"></script>
		<script type="text/javascript" src="js/angular-route.js"></script>
		<script type="text/javascript" src="js/app.js"></script>
		<script type="text/javascript" src="js/controllers.js"></script>
		<script type="text/javascript" src="js/topup.js"></script>
	</body>
</html>
