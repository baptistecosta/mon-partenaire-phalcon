(function() {
    'use strict';

    var app = angular.module('myTennisPal', [
        'ngRoute'
    ]);

    app.run([
        '$rootScope',
        function($rootScope) {
            //
        }
    ]);

    app.config([
        '$locationProvider',
        '$routeProvider',
        function($locationProvider, $routeProvider) {
            //$locationProvider.html5Mode(true);

            $routeProvider
                .when('/', {
                    controller: 'IndexController',
                    templateUrl: '/js/src/ng/partial/index/index.html'
                })
                .otherwise({
                    redirectTo: '/'
                });
        }
    ]);

    app.service('url', [
        function() {
            this.queryParams = function(url) {
                if (!url) {
                    return {};
                }
                var vars = {}, pair;
                var pairs = url.slice(url.indexOf('?') + 1).split('&');
                for (var i = 0; i < pairs.length; i++) {
                    pair = pairs[i].split('=');
                    vars[pair[0]] = decodeURIComponent(pair[1]);
                }
                return vars;
            };
        }
    ]);

    app.directive('rightClick', [
        '$parse',
        function($parse) {
            return function(scope, element, attrs) {
                var fn = $parse(attrs.rightClick);
                element.bind('contextmenu', function(event) {
                    scope.$apply(function() {
                        event.preventDefault();
                        fn(scope, {$event: event});
                    });
                });
            };
        }
    ]);

})();
