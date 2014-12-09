(function() {
    'use strict';

    var app = angular.module('myTennisPal', [
        'ngRoute'
    ]);

    app.config([
        '$locationProvider',
        '$routeProvider',
        function($locationProvider, $routeProvider) {
            $locationProvider.html5Mode(true);
            $routeProvider
                .when('/', {
                    controller: 'IndexController',
                    templateUrl: '/js/src/ng/partial/index/index.html'
                    //resolve: {
                    //    me: [
                    //        '$q',
                    //        'me',
                    //        function($q, me) {
                    //            var defer = $q.defer();
                    //            me.request(function() {
                    //                defer.resolve();
                    //            });
                    //            return defer.promise;
                    //        }
                    //    ]
                    //}
                })
                .when('/sign-in', {
                    controller: 'SignInController',
                    templateUrl: '/js/src/ng/partial/auth/sign_in.html'
                })
                .when('/place', {
                    controller: 'PlaceController',
                    templateUrl: '/js/src/ng/partial/place/index.html'
                })
                .otherwise({
                    redirectTo: '/'
                });
        }
    ]);

    app.run([
        '$rootScope',
        '$location',
        function($rootScope, $location) {
            $rootScope.$on('$locationChangeStart', function(scope, to, from) {
                $rootScope.$emit('flash.unauthorized.hide');
                //if (!auth.isAllowed(me.getRole(), $location.path())) {
                //    $location.path('/');
                //    $rootScope.$emit('flash.unauthorized.show');
                //}
            });
        }]
    );

/*    app.factory('accessToken', [
        '$http',
        function($http) {
            return {
                request: function(email, password) {
                    return $http.post('/api/auth', {
                        grantType: 'password',
                        clientId: 'mytennispal.frontend-client',
                        email: email,
                        password: password
                    });
                },
                get: function() {
                    return localStorage.getItem('accessToken');
                },
                set: function(accessToken) {
                    localStorage.setItem('accessToken', accessToken);
                }
            }
        }
    ]);*/

/*    app.factory('me', [
        '$http',
        'accessToken',
        function($http, accessToken) {
            var data;

            var me = {
                init: function() {
                    data = {
                        email: '',
                        role: ''
                    }
                },
                request: function(callback) {
                    var accessToken = accessToken.get();
                    if (!accessToken) {
                        return null;
                    }
                    $http.get('/api/me', {
                        params: {accessToken: accessToken.get()}
                    }).success(function(res) {
                        console.log(res);
                    }).error(function(err) {
                        console.error(err);
                    });
                },
                getEmail: function() {
                    return data.email;
                },
                getRole: function() {
                    return data.role;
                }
            };

            me.reset();
            return me;
        }
    ]);*/

    app.value('acl', {
        '/': '*',
        '/sign-in': '*',
        '/sign-out': '*',
        '/place': [
            //'pal',
            'club'
        ]
    });

/*    app.factory('auth', [
        'acl',
        function(acl) {
            return {
                isAllowed: function(role, requestedResource) {
                    for (var resource in acl) {
                        if (resource === requestedResource) {
                            var roles = acl[resource];
                            if (typeof roles === 'string') {
                                return roles === '*' || roles === role;
                            } else {
                                for (var i = 0; i < roles.length; i++) {
                                    if (role === roles[i]) {
                                        return true;
                                    }
                                }
                                return false;
                            }
                        }
                    }
                }
            }
        }
    ]);*/

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

    app.directive('flashUnauthorized', [
        function() {
            return {
                restrict: 'E',
                templateUrl: '/js/src/ng/partial/flash/unauthorized.html',
                scope: {},
                controller: [
                    '$rootScope',
                    '$scope',
                    function($rootScope, $scope) {
                        $scope.show = false;

                        $rootScope.$on('flash.unauthorized.show', function() {
                            $scope.show = true;
                        });

                        $rootScope.$on('flash.unauthorized.hide', function() {
                            $scope.show = false;
                        });
                    }
                ]
            }
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
