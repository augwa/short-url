app

    .config(function config($stateProvider) {
        $stateProvider.state('urlcreate', {
            url: '/url/create',
            controller: 'UrlCtrl',
            templateUrl: 'templates/url/create.html'
        })
    })

    .config(function config($stateProvider) {
        $stateProvider.state('urllist', {
            url: '/url/list',
            controller: 'UrlCtrl',
            templateUrl: 'templates/url/list.html',
            resolve: {
                urlList: function(mySvc) {
                    console.log(mySvc);
                }
            }
        })
    })

    .config(function config($stateProvider) {
        $stateProvider.state('urlstats', {
            url: '/url/stats',
            controller: 'UrlCtrl',
            templateUrl: 'templates/url/stats.html'
        })
    })

    .config(function config($stateProvider) {
        $stateProvider.state('urlview', {
            url: '/url/view',
            controller: 'UrlCtrl',
            templateUrl: 'templates/url/view.html'
        })
    })

    .controller('UrlCtrl', function UrlCtrl($scope, $http, $window) {
        var url = this;
        $scope.error = null;
        $scope.response = null;
        $scope.location = $window.location;

        $scope.urlList = function List($item, $event) {
            $http.get(
                '/app_dev.php/api/url/list',
                {
                    page: $scope.page
                }
            ).success(function(data, status, headers, config) {
                $scope.error = null;
                $scope.response = data;
            }).
            error(function(data, status, headers, config) {
                $scope.error = data;
            });
        };

        $scope.createSubmit = function Create($item, $event) {
            $http.post(
                '/app_dev.php/api/url',
                {
                    url: $scope.full_url,
                    title: $scope.title,
                    description: $scope.description,
                    email_address: $scope.email_address
                }
            ).success(function(data, status, headers, config) {
                $scope.error = null;
                $scope.response = data;
            }).
            error(function(data, status, headers, config) {
                $scope.error = data;
            });
        }
    })