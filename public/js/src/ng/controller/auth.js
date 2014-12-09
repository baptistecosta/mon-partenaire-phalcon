(function() {
    'use strict';

    var app = angular.module('myTennisPal');

    app.controller('SignInController', [
        '$scope',
        '$http',
        '$location',
        'accessToken',
        function($scope, $http, $location, accessToken) {
            $scope.email = '';
            $scope.password = '';

            $scope.requestAccessToken = function(email, password) {
                accessToken.request(email, password).success(function(res) {
                    if (res.message === 'success') {
                        accessToken.set(res.accessToken.id);
                        $location.path('/');
                    }
                }).error(function(err) {
                    console.error(err);
                });
            };
        }
    ]);

})();