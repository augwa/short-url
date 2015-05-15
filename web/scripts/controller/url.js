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
            templateUrl: 'templates/url/list.html'
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

        $scope.createSubmit = function Create($item, $event) {
            $http.post(
                '/api/url',
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