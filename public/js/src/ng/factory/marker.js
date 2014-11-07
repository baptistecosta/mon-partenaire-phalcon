(function() {
    'use strict';

    var app = angular.module('myTennisPal');

    app.factory('marker', [
        function() {
            var service = {};

            service.create = function(data, id, addListeners) {
                var marker = new google.maps.Marker({
                    title: data.title,
                    position: new google.maps.LatLng(data.latitude, data.longitude),
                    icon: data.icon
                });
                if (id) {
                    marker.set('id', id);
                }
                if (addListeners) {
                    addListeners(marker, data);
                }
                return marker;
            };

            service.createMany = function(markersData, addListeners) {
                var markers = [];
                markersData.forEach(function(markerData, index) {
                    var marker = service.create(markerData, String(index), addListeners);
                    markers.push(marker);
                });
                return markers;
            };

            service.attach = function(marker, map) {
                marker.setMap(map);
            };

            service.attachMany = function(markers, map) {
                markers.forEach(function(marker) {
                    service.attach(marker, map);
                });
            };

            service.find = function(id, markers) {
                for (var i = 0; i < markers.length; i++) {
                    if (id == markers[i].get('id')) {
                        return markers[i];
                    }
                }
                return null;
            };

            service.clear = function(marker) {
                marker.setMap(null);
            };

            service.clearMany = function(markers) {
                markers.forEach(function(marker) {
                    service.clear(marker);
                });
            };

            service.deleteMany = function(markers) {
                service.clearMany(markers);
                markers = [];
            };

            return service;
        }
    ]);

})();