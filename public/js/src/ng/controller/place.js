(function() {
    'use strict';

    var app = angular.module('myTennisPal');

    app.controller('PlaceController', [
        '$scope',
        '$http',
        'url',
        'navigatorGeolocation',
        'marker',
        'scrappedPlaceMarkerMapper',
        function($scope, $http, url, navigatorGeolocation, markerService, scrappedPlaceMarkerMapper) {

            var map;

            var geoloc;

            var placeHintMarkers = [];
            var scrappedPlaceMarkers = [];

            var menu = document.getElementById('dropDownMenu');

            $scope.markersData = [];
            $scope.links = {};
            $scope.page = 1;
            $scope.pageCount = null;
            $scope.pageSize = null;
            $scope.placesCount = null;

            function initMap(lat, lng) {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 11,
                    center: new google.maps.LatLng(lat, lng)
                });

                google.maps.event.addListener(map, 'dragend', function() {
                    putPlaceHintMarkers();
                });
                google.maps.event.addListener(map, 'zoom_changed', function() {
                    hideMenu();
                    putPlaceHintMarkers();
                });
                google.maps.event.addListenerOnce(map, 'idle', function() {
                    putPlaceHintMarkers();
                    putScrappedPlaceMarkers();
                });
                google.maps.event.addListener(map, 'click', hideMenu);
                google.maps.event.addListener(map, 'dragstart', hideMenu);
                google.maps.event.addListener(map, 'rightclick', function(e) {
                    showMenu();
                    setMenuPosition(e.pixel.x, e.pixel.y + menu.clientHeight);
                    geoloc = e.latLng;
                });
            }

            function setMenuPosition(x, y) {
                menu.style.left = x + 'px';
                menu.style.top = y + 'px';
            }

            function showMenu(e) {
                menu.style.display = 'block';
            }

            function hideMenu() {
                menu.style.display = 'none';
            }

            function putScrappedPlaceMarkers() {
                scrappedPlaceMarkerMapper
                    .fetchMany()
                    .success(function(res) {
                        markerService.deleteMany(scrappedPlaceMarkers);

                        scrappedPlaceMarkers = markerService.createMany(res, null);
                        markerService.attachMany(scrappedPlaceMarkers, map);
                    })
                    .error(console.error);
            }

            function putPlaceHintMarkers() {
                $http
                    .get('/api/place-hint-markers', {
                        params: $.extend(getMapBounds(), {
                            zoom: map.getZoom()
                        })
                    })
                    .success(function(res) {
                        var placeHintMarkersData = res;
                        markerService.deleteMany(placeHintMarkers);
                        placeHintMarkers = markerService.createMany(placeHintMarkersData);
                        markerService.attachMany(placeHintMarkers, map);
                    })
                    .error(console.error);
            }

            function getMapBounds() {
                var bounds = map.getBounds(),
                    ne = bounds.getNorthEast(),
                    sw = bounds.getSouthWest();

                return {
                    'south-west-bound': sw.lat() + ',' + sw.lng(),
                    'north-east-bound': ne.lat() + ',' + ne.lng()
                }
            }

            $scope.scrapPlace = function() {
                hideMenu();
                if (geoloc) {
                    document.body.style.cursor = "wait";
                    $http
                        .post('/api/place/find', {
                            geolocation: geoloc.lat() + ',' + geoloc.lng()
                        })
                        .success(function(res) {
                            document.body.style.cursor = "default";
                            putScrappedPlaceMarkers();
                        })
                        .error(console.error);
                }
            };

            //initMap(43.1143760, 5.9416940);

            navigatorGeolocation.run(function(lat, lng) {
                initMap(lat, lng);
            }, function() {
                initMap(43.1143760, 5.9416940);
            });
        }
    ]);

})();