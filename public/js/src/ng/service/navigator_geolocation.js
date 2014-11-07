(function() {
    'use strict';

    var app = angular.module('myTennisPal');

    app.service('navigatorGeolocation', [
        function() {
            this.run = function(success, error) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        success(position.coords.latitude, position.coords.longitude);
                    }, error);
                } else {
                    error();
                }
            }
        }
    ]);

})();