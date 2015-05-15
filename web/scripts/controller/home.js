app

    .config(function config($stateProvider) {
        $stateProvider.state('home', {
            url: '',
            controller: 'HomeCtrl as homer',
            templateUrl: 'templates/index.html'
        })
    })

    .controller('HomeCtrl', [ '$scope', function HomeCtrl($scope, $data) {

    }])