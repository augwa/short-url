app

    .config(function config($stateProvider) {
        $stateProvider.state('userlogin', {
            url: '/login',
            controller: 'UserCtrl as login',
            templateUrl: 'templates/user/login.html'
        })
    })

    .config(function config($stateProvider) {
        $stateProvider.state('usersignup', {
            url: '/signup',
            controller: 'UserCtrl as signup',
            templateUrl: 'templates/user/signup.html'
        })
    })

    .controller('UserCtrl', [ '$scope', function UserCtrl($scope, $data) {
        var user = this;
    }])