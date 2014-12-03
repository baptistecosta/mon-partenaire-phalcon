(function() {
    'use strict';

    var app = angular.module('myTennisPal');

    app.controller('SignInController', [
        '$scope',
        '$http',
        '$location',
        function($scope, $http, $location) {
            $scope.email = '';
            $scope.password = '';

            $scope.requestAccessToken = function(email, password) {
                $http.post('/api/auth', {
                    grantType: 'password',
                    clientId: 'mytennispal.frontend-client',
                    email: email,
                    password: password
                }).success(function(res) {
                    if (res.message === 'success') {
                        localStorage.setItem('accessToken', res.accessToken.id);
                        $location.path('/');
                    }
                }).error(function(err) {
                    console.error(err);
                });
            };
        }
    ]);

})();