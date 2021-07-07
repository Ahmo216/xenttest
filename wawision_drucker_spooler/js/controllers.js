var macPrinterControllers = angular.module('macPrinterControllers', []);

macPrinterControllers.controller('MainCtrl', ['$scope', '$rootScope', 'appData', function($scope,$rootScope,appData) {
	
}]);

macPrinterControllers.controller('StatistikCtrl', ['$scope', '$http', 'api', function($scope, $http, api) {
	$scope.statistik = {};
	$http.get(api.url + '?do=getFullStatistik').success(function(data) {
		$scope.statistik = data;
	});
}]);

macPrinterControllers.controller('PrinterStatistikCtrl', ['$scope', '$http', 'api', '$routeParams', function($scope, $http, api, $routeParams) {

	$scope.drucker = $routeParams.printer;

	$scope.statistik = {};
	$scope.printerStatistik = {
		anzahlJobs: 0,
		jobs: {}
	};
	$http.get(api.url + '?do=getFullStatistik').success(function(data) {
		$scope.statistik = data;
		$scope.printerStatistik = $scope.statistik.drucker[$scope.drucker];

	});

}]);


macPrinterControllers.controller('ChartStatistikCtrl', ['$scope', 'appData', '$http', 'api', function($scope, appData, $http, api) {

	if (typeof appData.customerSettings.chartybreakperiod == null) {
		appData.customerSettings.chartybreakperiod = 5;
	}

	$http.get(api.url + '?do=getChartStatistik').success(function(data) {

		angular.forEach(data.drucker, function(value, drucker){
			$('.canvas_chart').append('<h3>' + drucker + '<h3><canvas id="graph_'+drucker+'" width="500" height="120" align="center"></canvas>');
			drawlineChart({
				node: 'graph_'+drucker,
				dataset: value.dataset,
				labels: value.labels,
				pathcolor: "#666666",
				fillcolor: "#333333",
				xPadding: 0,
				yPadding: 0,
				ybreakperiod: appData.customerSettings.chartybreakperiod
			});
		});

	});

}]);



macPrinterControllers.controller('DashboardCtrl', ['$scope', 'appData', function($scope, appData) {
	$scope.statistik = appData.statistik;
}]);



macPrinterControllers.controller('PrintersCtrl', ['$scope', 'appData', function($scope, appData) {
	$scope.printers = appData.printers;
}]);



macPrinterControllers.controller('SettingsCtrl', ['$scope', 'appData', '$http', 'api', function($scope, appData, $http, api) {

	$scope.url = appData.customerSettings.url;
	$scope.serial = appData.customerSettings.serial;
	$scope.devicekey = appData.customerSettings.devicekey;
	$scope.chartybreakperiod = appData.customerSettings.chartybreakperiod;

	$scope.saveSettings = function(url,serial,devicekey,chartybreakperiod) {

		$http({
			url: api.url,
			method: 'POST',
			data: {
				do: 'saveSettings',
				sendUrl: url,
				sendSerial: serial,
				sendDevicekey: devicekey,
				sendChartybreakperiod: chartybreakperiod
			},
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).success(function(data) {
			appData.customerSettings.url = data.url;
			appData.customerSettings.serial = data.serial;
			appData.customerSettings.devicekey = data.devicekey;
			appData.customerSettings.chartybreakperiod = data.chartybreakperiod;
		});

	}

}]);