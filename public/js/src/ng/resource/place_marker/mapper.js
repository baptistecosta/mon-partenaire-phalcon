(function() {
    'use strict';

    var app = angular.module('myTennisPal');

    app.factory('placeMarkerMapper', [
        '$http',
        function($http) {
            return {
                fetchMany: function(params) {
                    return $http
                        .get('/api/place-markers', {
                            params: params
                        });
                }
            };
        }
    ]);

})();
