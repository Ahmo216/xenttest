var macPrinterApp = angular.module('macPrinterApp', [
  'ngRoute',
  'macPrinterControllers'
]);

macPrinterApp.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {
  $routeProvider.when('/settings', {
    templateUrl: 'views/settings.html',
    controller: 'SettingsCtrl'
  })
  .when('/printers', {
    templateUrl: 'views/printers.html',
    controller: 'PrintersCtrl'
  })
  .when('/statistik', {
    templateUrl: 'views/statistik.html',
    controller: 'StatistikCtrl'
  })
  .when('/statistik/:printer', {
    templateUrl: 'views/printerstatistik.html',
    controller: 'PrinterStatistikCtrl'
  })
  .when('/chartstatistik', {
    templateUrl: 'views/chartstatistik.html',
    controller: 'ChartStatistikCtrl'
  })
  .when('/', {
    templateUrl: 'views/dashboard.html',
    controller: 'DashboardCtrl'
  })
  .otherwise({
    redirectTo: '/'
  });
  //$httpProvider.interceptors.push('httpInterceptor');
}]);

macPrinterApp.run(function($http, appData, $rootScope, settingscheck, $location, $timeout, api) {

  $http.get(api.url + '?do=getCustomerData').success(function(data,status) {
    appData.customerSettings = data;
  });

  $http.get(api.url + '?do=getPrinters').success(function(data,status) {
    appData.printers = data;
  });

  $rootScope.$on('$routeChangeSuccess', function(newVal, oldVal) {
    if (!settingscheck()) {
      $location.path('/settings');
    }
  });

  $rootScope.api = {
    status: false,
    message: ''
  };

  $rootScope.pollingData = {
    calls: 0
  };

  $rootScope.getJobs = function() {

    $http.get(api.url + '?do=getJobs').then(function(response, status) {

      if (response.data.status) {
        $rootScope.api.status = 'status_aktiv';
      } else {
        $rootScope.api.status = 'status_inaktiv';
      }

      $rootScope.api.message = response.data.message;

      $rootScope.pollingData.calls++;

      $http.get(api.url + '?do=getDashboardStatistik').success(function(data) {
        if (typeof data.all != 'undefined') {
          appData.statistik.anzahlJobsGesamt = data.all.anzahlJobs;
          appData.statistik.lastJobs = data.all.jobs;
        }
      });

      $timeout(function() {
        $rootScope.getJobs();
      }, 3000);
    });

  }

  $rootScope.getJobs();

})

macPrinterApp.constant('api', {
  url: 'api.php'
});

macPrinterApp.constant('appData', {
  printers: {},
  customerSettings: {
    url: null,
    serial: null,
    devicekey: null,
    chartybreakperiod: 5
  },
  statistik: {
    anzahlJobsGesamt: 0,
    lastJobs: {}
  }
});

macPrinterApp.factory('httpInterceptor', function ($q, $rootScope, $log) {
    var numLoadings = 0;
    return {
        request: function (config) {
            numLoadings++;
            // Show loader
            $rootScope.$broadcast("loader_show");
            return config || $q.when(config)

        },
        response: function (response) {
            if ((--numLoadings) === 0) {
                // Hide loader
                $rootScope.$broadcast("loader_hide");
            }
            return response || $q.when(response);
        },
        responseError: function (response) {
            if (!(--numLoadings)) {
                // Hide loader
                $rootScope.$broadcast("loader_hide");
            }
            return $q.reject(response);
        }
    };
});

macPrinterApp.directive("loader", function ($rootScope) {
    return function ($scope, element, attrs) {
        $scope.$on("loader_show", function () {
            return element.show();
        });
        return $scope.$on("loader_hide", function () {
            return element.hide();
        });
    };
});

macPrinterApp.factory('settingscheck', ['appData', function(appData) {
  return function() {
    var settingsCount = 0
    if (appData.customerSettings.url != null) {
      settingsCount++;
    }
    if (appData.customerSettings.serial != null) {
      settingsCount++;
    }
    if (appData.customerSettings.devicekey != null) {
      settingsCount++;
    }

    if (settingsCount >= 3) {
      return true;
    } else {
      return false;
    }

  };
}]);


$(document).ready(function() {
  $('.template_navigation ul li a').click(function() {
    $('.template_navigation ul li a').removeClass('aktiv');
    $(this).addClass('aktiv');
  })
})
