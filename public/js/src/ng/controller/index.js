(function() {
    'use strict';

    var app = angular.module('myTennisPal');

    app.controller('IndexController', [
        '$scope',
        '$http',
        'url',
        'navigatorGeolocation',
        'marker',
        'scrappedPlaceMarkerMapper',
        'placeMarkerMapper',
        function($scope, $http, url, navigatorGeolocation, markerService, scrappedPlaceMarkerMapper, placeMarkerMapper) {
            var map;

            var geoloc;

            var placeMarkers = [];
            var placeSmallMarkers = [];
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
                    zoom: 7,
                    center: new google.maps.LatLng(lat, lng)
                });

                google.maps.event.addListener(map, 'dragend', function() {
                    putPlaceSmallMarker();
                    putMarkers();
                });
                google.maps.event.addListener(map, 'zoom_changed', function() {
                    hideMenu();
                    putPlaceSmallMarker();
                    putMarkers();
                });
                google.maps.event.addListenerOnce(map, 'idle', function() {
                    putPlaceSmallMarker();
                    putScrappedPlaceMarkers();
                    putMarkers();
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

            function putPlaceSmallMarker() {
                $http
                    .get('/api/place-small-markers', {
                        params: getMapBounds()
                    })
                    .success(function(res) {
                        var placeSmallMarkersData = res;
                        markerService.deleteMany(placeSmallMarkers);
                        placeSmallMarkers = markerService.createMany(placeSmallMarkersData);
                        markerService.attachMany(placeSmallMarkers, map);
                    })
                    .error(console.error);
            }

            function putMarkers() {
                $scope.page = 1;

                if (map.getZoom() >= 10) {
                    requestPlaceMarkers()
                } else {
                    deletePlaceMarkers();
                }
            }

            function requestPlaceMarkers(link) {
                var promise = link ? $http.get(link) : getPlaceMarkersPromise();
                promise
                    .success(onPlaceMarkerRequestSuccess)
                    .error(console.error);
            }

            function getPlaceMarkersPromise() {
                return placeMarkerMapper.fetchMany(getMapBounds());
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

            function onPlaceMarkerRequestSuccess(res) {
                $scope.pageCount = res.page_count;
                $scope.pageSize = res.page_size;
                $scope.placesCount = res.total_items;
                $scope.links = res._links;
                $scope.markersData = res._embedded.place_marker;

                markerService.deleteMany(placeMarkers);
                placeMarkers = markerService.createMany($scope.markersData);
                markerService.attachMany(placeMarkers, map);
            }

            function deletePlaceMarkers() {
                markerService.deleteMany(placeMarkers);
            }

            $scope.pageChange = function(link) {
                var queryParams = url.queryParams(link);
                $scope.page = queryParams.page ? queryParams.page : 1;

                requestPlaceMarkers(link);
            };

            $scope.rowIndex = function($index) {
                return ($index + 1) + (($scope.page - 1) * $scope.pageSize);
            };

            $scope.onPlaceRowEnter = function($index) {
                var m = markerService.find($index, placeMarkers);
                if (m) {
                    m.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');
                }
            };

            $scope.onPlaceRowLeave = function($index) {
                var m = markerService.find($index, placeMarkers);
                if (m) {
                    m.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');
                }
            };

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