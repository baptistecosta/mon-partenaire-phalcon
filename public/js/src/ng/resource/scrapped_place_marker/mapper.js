(function() {
    'use strict';

    var app = angular.module('myTennisPal');

    app.factory('scrappedPlaceMarkerMapper', [
        '$http',
        function($http) {
            return {
                fetchMany: function(params) {
                    return $http
                        .get('/api/scrapped-place-markers')
                }
            };
        }
    ]);

})();