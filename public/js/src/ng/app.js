(function() {
    'use strict';

    /**
     * Bootstrap
     */
    angular.element(document).ready(function() {
        var injector = angular.injector(['ng', 'authentication']),
            meLoader = injector.get('meLoader');

        meLoader.request().then(function() {
            console.log('Bootstrapping application...');
            angular.bootstrap(document, ['myTennisPal']);
        });
    });

    /**
     * Utils module
     */
    var utils = angular.module('utils', []);

    utils.factory('storage', [
        function() {
            return {
                get: function(name) {
                    localStorage.getItem(name);
                },
                set: function(name, value) {
                    localStorage.setItem(name, value);
                },
                remove: function() {
                    localStorage.removeItem(name);
                }
            }
        }
    ]);

    /**
     * Authentication module
     */
    var authentication = angular.module('authentication', [
        'utils'
    ]);

    authentication.value('acl', {
        '/': '*',
        '/sign-in': '*',
        '/sign-out': '*',
        '/place': [
            'pal',
            'club'
        ]
    });

    authentication.factory('authorization', [
        'acl',
        function(acl) {
            return {
                isAllowed: function(role, requestedResource) {
                    for (var resource in acl) {
                        if (resource === requestedResource) {
                            var allowedRoles = acl[resource];
                            if (typeof allowedRoles === 'string') {
                                return allowedRoles === '*' || allowedRoles === role;
                            } else {
                                for (var i = 0; i < allowedRoles.length; i++) {
                                    if (role === allowedRoles[i]) {
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
    ]);

    authentication.factory('accessToken', [
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
                }
            }
        }
    ]);

    authentication.factory('meLoader', [
        '$http',
        '$q',
        'accessToken',
        'storage',
        function($http, $q, accessToken, storage) {
            return {
                request: function() {
                    var deferred = $q.defer(),
                        token = storage.get('accessToken');

                    if (!token) {
                        deferred.resolve();                    } else {
                        $http.get('/api/me', {
                            params: {accessToken: token}
                        }).success(function(data, status, headers, config) {
                            storage.set('email', data.email);
                            storage.set('role', data.role);

                            deferred.resolve();
                        }).error(function(data, status, headers, config) {
                            if (status == 401) {
                                storage.remove('accessToken');
                                storage.remove('email');
                                storage.remove('role');
                            }
                            deferred.resolve();
                        });
                    }
                    return deferred.promise;
                }
            };
        }
    ]);

    /**
     * Application module
     */
    var app = angular.module('myTennisPal', [
        'ngRoute',
        'authentication',
        'utils'
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

    app.factory('me', [
        'storage',
        function(storage) {
            var email,
                role;

            var me = {
                init: function() {
                    email = me.getEmail();
                    role = me.getRole();
                },
                getEmail: function() {
                    if (!email) {
                        email = storage.get('email');
                    }
                    return email;
                },
                getRole: function() {
                    if (!role) {
                        role = storage.get('role');
                    }
                    return role;
                }
            };

            return me;
        }
    ]);

    app.run([
        '$rootScope',
        '$location',
        'authorization',
        'me',
        function($rootScope, $location, authorization, me) {
            me.init();

            $rootScope.$on('$locationChangeStart', function(scope, to, from) {
                $rootScope.$emit('flash.unauthorized.hide');
                if (!authorization.isAllowed(me.getRole(), $location.path())) {
                    $location.path('/sign-in');
                    $rootScope.$emit('flash.unauthorized.show');
                }
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
