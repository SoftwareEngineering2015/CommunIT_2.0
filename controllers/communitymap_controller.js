communitApp.controller('communitymapController', ['$scope', '$http', function($scope, $http) {

    $.urlParam = function(name) {
        var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    var encodedData = 'user=' +
        encodeURIComponent(localStorage.getItem("communit_user_id")) +
        '&community=' +
        encodeURIComponent($.urlParam("community"));

    $http({
            method: 'POST',
            url: './models/check_user_in_community.php',
            data: encodedData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .success(function(data, status, headers, config) {
            if (data.trim() === "noMatch") {
                window.location.href = "myhome.php";
            }
        })
        .error(function(data, status, headers, config) {

        })

    $scope.profiles_array = []; // Holds the profile data from the marker
    $scope.noProfiles = true; // This will hide the profiles table until there is a profile to display

    $scope.marker_name; // This show the name of the clicked marker when there is a profile
    $scope.marker_location; // This show the location of the clicked marker when there is a profile
    $scope.marker_misc; // This show the location of the clicked marker when there is a profile

    $scope.misc_panel = false;
    $scope.showMarkerInfoButton = false;

    $scope.marker_clicked_for_weather_information;

    var profile_request; // Varaible that will hold the request to get the profile of a marker; needed for aborted requests

    var clicked_marker; // Used to hold the clicked marker that will be used for different queries

    var map;
    var streetview = new google.maps.StreetViewService();
    var myCenter = new google.maps.LatLng(41.7605556, -88.3200)

    var markers = []; // This is the array for the markers
    var marker_ids = []; //Marker information
    var marker_names = []; //Marker name
    var marker_miscinfos = []; //Marker misc
    var marker_latitudes = []; //holds marker latitude
    var marker_longitudes = []; //holds marker longitude
    var marker_latlngs = []; //holds parsed latlng marker data
    var marker_locations = []; //holds marker location
    var marker_pin_colors = []; //holds marker pin color
    var default_pin_color;
    var default_pin_color_status;
    var marker_has_floorplans = []; //Specifies that the marker has floorplans

    var infowindows = [];
    var prev_infowindow = false; // Varaible to check to

    //creates a bounds object that is extended in the main loop
    var bounds = new google.maps.LatLngBounds();

    //Holds values for the dropdown menu
    var divOptions = [];
    var optionsDiv = [];
    var options = [];

    loadCommunity($.urlParam("community"));

    // Function to load the map in angular
    function loadCommunity(community) {

        encodedData = 'community=' +
            encodeURIComponent(community);

        $http({
                method: 'POST',
                url: './models/load_community_map_model.php',
                data: encodedData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .success(function(json, status, headers, config) {
                if (json === 'noCommunity') {
                    window.location.href = 'myhome.php';
                } else {
                    if (json == "") {
                        initializeNewCommunity();
                    } else {
                        angular.forEach(json, function(data, key) {
                            if (key == 'community_information') {
                                document.getElementById("community_name").innerHTML = data.community_name;
                            } else {
                                if (key == "no_markers") {
                                    initializeNewCommunity();
                                    document.getElementById("street-view").innerHTML = "There are no residences in the community.";
                                } else {
                                    angular.forEach(data, function(value, key) {
                                        marker_ids.push(value.marker_id);
                                        marker_names.push(value.name);
                                        marker_miscinfos.push(value.miscinfo)
                                        marker_latitudes.push(value.latitude);
                                        marker_longitudes.push(value.longitude);
                                        marker_latlngs.push(new google.maps.LatLng(value.latitude, value.longitude));
                                        marker_locations.push(value.location);
                                        marker_pin_colors.push(value.pin_color);
                                        if (value.default_pin_color_status == 1) {
                                            default_pin_color_status = true;
                                            default_pin_color = value.pin_color;
                                        } else {
                                            default_pin_color_status = false;
                                            default_pin_color = "";
                                        }
                                        marker_has_floorplans.push(value.has_floorplan);
                                    });

                                    // Load Google Maps
                                    initializeFilledCommunity();
                                    //google.maps.event.addDomListener(window, 'load', initialize);
                                }
                            }
                        });
                    }
                }
            })
            .error(function(data, status, headers, config) {

            })
    }

    function initializeNewCommunity() {
        var mapProp = {
            center: myCenter,
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        google.maps.event.addDomListener(window, "resize", function() {
            google.maps.event.trigger(map, "resize");
            map.fitBounds(bounds);
        });

        bounds.extend(new google.maps.LatLng(28.70, -127.50));
        bounds.extend(new google.maps.LatLng(48.85, -55.90));

        map.fitBounds(bounds);

    }

    function initializeFilledCommunity() {
        var mapProp = {
            center: myCenter,
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        google.maps.event.addDomListener(window, "resize", function() {
            google.maps.event.trigger(map, "resize");
            map.fitBounds(bounds);
        });

        //this loop will create all of the markers, then invoke the addlistener function
        for (i in marker_ids) {
            //extend the bounds object to fit the iterated marker
            bounds.extend(new google.maps.LatLng(marker_latitudes[i], marker_longitudes[i]));

            //Change the color of each image through this function
            overalayColor(marker_pin_colors[i]);
            //creates a marker in the markers array
            markers.push(new google.maps.Marker({
                map: map,
                position: marker_latlngs[i],
                title: (marker_names[i] + "\n" + marker_locations[i]),
                //Place changed image as the icon
                icon: fullimg,
                animation: google.maps.Animation.DROP
            }));

            // If the marker has a floorplan add the listener for the floorplan, otherwise add a listener to get the profile of the marker
            if (marker_has_floorplans[i] == 1) {
                infowindows[i] = new google.maps.InfoWindow({
                    content: "<b> Name: " + marker_names[i] + " <br /> Location: " + marker_locations[i] + " </b>"
                });
                addFloorplanListener(i);
            } else {
                infowindows[i] = new google.maps.InfoWindow({
                    content: "<b> Name: " + marker_names[i] + " <br /> Location: " + marker_locations[i] + " </b>"
                });
                addProfileListener(i);
            }
        };

        //these next four lines are for the weather div
        var createWeatherDiv = document.createElement('div');
        var weather = new weatherDiv(createWeatherDiv, map);
        createWeatherDiv.index = 1;
        //puts the div on the map
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(createWeatherDiv);

        //these next four lines are for the centering button
        var centerControlDiv = document.createElement('div');
        var centerControl = new centerbutton(centerControlDiv, map);
        centerControlDiv.index = 1;
        //puts the centering button on the map
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);

        //these next four lines are for the select box
        var centerSelectlDiv = document.createElement('div');
        var selectControl = new residenceSelectBox(centerSelectlDiv, map, marker_names, marker_has_floorplans);
        centerSelectlDiv.index = 1;
        //puts the centering button on the map
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerSelectlDiv);

        map.fitBounds(bounds);

    }
    // End of the initialize function

    // Add a listener to the marker for a floorplan
    function addFloorplanListener(i) {
        markers[i].addListener('click', function() {

            $scope.showMarkerInfoButton = true;

            $scope.marker_clicked_for_weather_information = i;

            if (prev_infowindow) {
                prev_infowindow.close();
                $("#weatherDiv").empty();
            }

            prev_infowindow = infowindows[i];

            infowindows[i].open(map, markers[i]);

            if ($.active > 0) {
                profile_request.abort();
            }

            $scope.marker_name = marker_names[i];
            $scope.marker_misc = marker_miscinfos[i];
            $scope.marker_location = marker_locations[i];

            encodedData = 'marker_id=' +
                encodeURIComponent(marker_ids[i]) +
                '&marker_name=' +
                encodeURIComponent(marker_names[i]);

            profile_request = $http({
                    method: 'POST',
                    url: './models/load_floorplans_in_marker.php',
                    data: encodedData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .success(function(data, status, headers, config) {

                    $scope.profiles_array = data;

                    if ($scope.profiles_array.no_profiles) {
                        $scope.noInformation = true;
                    } else {
                        $scope.noInformation = false;
                        $scope.noProfiles = true;
                        $scope.hasFloorplans = true;
                    }
                    streetview.getPanoramaByLocation(marker_latlngs[i], 50, function(data, status) {
                        if (status == 'OK') {
                            document.getElementById('street-view').style.display = 'block';
                            //configure panorama
                            panorama = new google.maps.StreetViewPanorama(
                                document.getElementById('street-view'), {
                                    position: marker_latlngs[i],
                                    pov: {
                                        heading: 0,
                                        pitch: 0
                                    },
                                    zoom: 1,
                                    linksControl: false,
                                    addressControl: false
                                });
                        } else {
                            document.getElementById('street-view').style.display = 'none';
                        }
                    });

                    var url = "http://api.openweathermap.org/data/2.5/weather?lat=" + marker_latitudes[i] + "&" + "lon=" + marker_longitudes[i] + "&APPID=cd4eda95a76d3de65a551a892bf8ce41&units=imperial";

                    $http({
                            method: 'GET',
                            url: url,
                        })
                        .success(function(data, status, headers, config) {
                            $("#weatherDiv").html("<img id='weatherPic' /> <span style='color: #19A3FF; font-size: 18px;'> " + data.main.temp + "&degF </span> <br /> Click For More Information");
                            document.getElementById("weatherPic").src = "images/weather/" + data.weather[0].icon + ".png";
                        })
                        .error(function(data, status, headers, config) {
                            $("#weatherDiv").html("<h4 style='color: red;'> No Weather </h4>");
                        })
                })
                .error(function(data, status, headers, config) {

                })

            map.panTo(marker_latlngs[i]);
        });

        google.maps.event.addListener(map, 'click', function() {
            if (prev_infowindow) {
                prev_infowindow.close();
                $("#weatherDiv").empty();
            }

        });
    }


    // Add a click event listener to the marker for the profile
    function addProfileListener(i) {
        markers[i].addListener('click', function() {

            $scope.showMarkerInfoButton = true;

            $scope.marker_clicked_for_weather_information = i;

            if (prev_infowindow) {
                prev_infowindow.close();
                $("#weatherDiv").empty();
            }

            prev_infowindow = infowindows[i];

            infowindows[i].open(map, markers[i]);

            if ($.active > 0) {
                profile_request.abort();
            }

            $scope.marker_name = marker_names[i];
            $scope.marker_misc = marker_miscinfos[i];
            $scope.marker_location = marker_locations[i];

            encodedData = 'marker_id=' +
                encodeURIComponent(marker_ids[i]) +
                '&marker_name=' +
                encodeURIComponent(marker_names[i]);

            profile_request = $http({
                    method: 'POST',
                    url: './models/load_profiles_in_marker.php',
                    data: encodedData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .success(function(data, status, headers, config) {
                    $scope.profiles_array = data;

                    if ($scope.profiles_array.no_profiles) {
                        $scope.noInformation = true;
                    } else {
                        $scope.noInformation = false;
                        $scope.noProfiles = false;
                        $scope.hasFloorplans = false;
                    }

                    streetview.getPanoramaByLocation(marker_latlngs[i], 50, function(data, status) {
                        if (status == 'OK') {
                            document.getElementById('street-view').style.display = 'block';
                            //configure panorama
                            panorama = new google.maps.StreetViewPanorama(
                                document.getElementById('street-view'), {
                                    position: marker_latlngs[i],
                                    pov: {
                                        heading: 0,
                                        pitch: 0
                                    },
                                    zoom: 1,
                                    linksControl: false,
                                    addressControl: false
                                });
                        } else {
                            document.getElementById('street-view').style.display = 'none';
                        }
                    });

                    var url = "http://api.openweathermap.org/data/2.5/weather?lat=" + marker_latitudes[i] + "&" + "lon=" + marker_longitudes[i] + "&APPID=cd4eda95a76d3de65a551a892bf8ce41&units=imperial";

                    $http({
                            method: 'GET',
                            url: url,
                        })
                        .success(function(data, status, headers, config) {
                            $("#weatherDiv").html("<img id='weatherPic' /> <span style='color: #19A3FF; font-size: 18px;'> " + data.main.temp + "&degF </span> <br /> Click For More Information");
                            document.getElementById("weatherPic").src = "images/weather/" + data.weather[0].icon + ".png";
                        })
                        .error(function(data, status, headers, config) {
                            $("#weatherDiv").html("<h4 style='color: red;'> No Weather </h4>");
                        })
                })
                .error(function(data, status, headers, config) {

                })

            map.panTo(marker_latlngs[i]);
        });

        google.maps.event.addListener(map, 'click', function() {
            if (prev_infowindow) {
                prev_infowindow.close();
                $("#weatherDiv").empty();
            }

        });
    }

    function centerbutton(controlDiv, map) {
        // Set CSS for the control border.
        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = '#3399FF';
        controlUI.style.border = '2px solid #00000';
        controlUI.style.borderRadius = '3px';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.cursor = 'pointer';
        controlUI.style.marginBottom = '22px';
        controlUI.style.textAlign = 'center';
        controlUI.title = 'Click To Recenter The Map On Your Community';
        controlDiv.appendChild(controlUI);

        // Set CSS for the control interior.
        var controlText = document.createElement('div');
        controlText.style.color = 'rgb(250,250,250)';
        controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
        controlText.style.fontSize = '16px';
        controlText.style.lineHeight = '38px';
        controlText.style.paddingLeft = '5px';
        controlText.style.paddingRight = '5px';
        controlText.innerHTML = 'Center Map On Community';
        controlUI.appendChild(controlText);

        // Setup the click event listeners: calls the centermap function
        controlUI.addEventListener('click', function() {
            map.fitBounds(bounds);
        });
    }

    function residenceSelectBox(controlDiv, map, name_of_markers, has_floorplans) {
        // Set CSS for the control border.
        var controlUI = document.createElement('select');
        controlUI.style.backgroundColor = '#3399FF';
        controlUI.style.border = '2px solid #00000';
        controlUI.style.borderRadius = '3px';
        controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlUI.style.cursor = 'pointer';
        controlUI.style.marginTop = '0px';
        controlUI.style.textAlign = 'center';
        controlUI.style.marginRight = '-150px';
        controlUI.style.color = 'rgb(250,250,250)';
        controlUI.style.fontFamily = 'Roboto,Arial,sans-serif';
        controlUI.style.fontSize = '16px';
        controlUI.style.lineHeight = '38px';
        controlUI.style.paddingLeft = '10px';
        controlUI.style.paddingRight = '10px';
        controlUI.title = 'Go To Marker';
        controlUI.id = 'residentSelectBox';
        controlDiv.appendChild(controlUI);

        var option = document.createElement("option");
        var name = document.createTextNode("Go To Marker");
        option.setAttribute("value", "first_select_option");
        option.appendChild(name);

        controlUI.appendChild(option);
        for (i in name_of_markers) {
            var option = document.createElement("option");
            option.setAttribute("value", i + "," + has_floorplans[i]);
            var name = document.createTextNode(name_of_markers[i]);
            option.appendChild(name);

            controlUI.appendChild(option);
        }

        $("#googleMap").on("change", "#residentSelectBox", function() {
            if ($("#residentSelectBox option[value='first_select_option']").length > 0) {
                $("#residentSelectBox").find("option").eq(0).remove();
            }
            var i = this.value.substr(0, this.value.indexOf(","));

            $scope.marker_clicked_for_weather_information = i;

            if (this.value.substr(this.value.indexOf(",") + 1) == 0) {

                if (prev_infowindow) {
                    prev_infowindow.close();
                    $("#weatherDiv").empty();
                }

                prev_infowindow = infowindows[i];

                infowindows[i].open(map, markers[i]);

                if ($.active > 0) {
                    profile_request.abort();
                }

                $scope.marker_name = marker_names[i];
                $scope.marker_misc = marker_miscinfos[i];
                $scope.marker_location = marker_locations[i];

                encodedData = 'marker_id=' +
                    encodeURIComponent(marker_ids[i]) +
                    '&marker_name=' +
                    encodeURIComponent(marker_names[i]);

                profile_request = $http({
                        method: 'POST',
                        url: './models/load_profiles_in_marker.php',
                        data: encodedData,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .success(function(data, status, headers, config) {
                        $scope.profiles_array = data;

                        if ($scope.profiles_array.no_profiles) {
                            $scope.noInformation = true;
                        } else {
                            $scope.noInformation = false;
                            $scope.noProfiles = false;
                            $scope.hasFloorplans = false;
                        }

                        streetview.getPanoramaByLocation(marker_latlngs[i], 50, function(data, status) {
                            if (status == 'OK') {
                                document.getElementById('street-view').style.display = 'block';
                                //configure panorama
                                panorama = new google.maps.StreetViewPanorama(
                                    document.getElementById('street-view'), {
                                        position: marker_latlngs[i],
                                        pov: {
                                            heading: 0,
                                            pitch: 0
                                        },
                                        zoom: 1,
                                        linksControl: false,
                                        addressControl: false
                                    });
                            } else {
                                document.getElementById('street-view').style.display = 'none';
                            }
                        });

                        var url = "http://api.openweathermap.org/data/2.5/weather?lat=" + marker_latitudes[i] + "&" + "lon=" + marker_longitudes[i] + "&APPID=cd4eda95a76d3de65a551a892bf8ce41&units=imperial";

                        $http({
                                method: 'GET',
                                url: url,
                            })
                            .success(function(data, status, headers, config) {
                                $("#weatherDiv").html("<img id='weatherPic' /> <span style='color: #19A3FF; font-size: 18px;'> " + data.main.temp + "&degF </span> <br /> Click For More Information");
                                document.getElementById("weatherPic").src = "images/weather/" + data.weather[0].icon + ".png";
                            })
                            .error(function(data, status, headers, config) {
                                $("#weatherDiv").html("<h4 style='color: red;'> No Weather </h4>");
                            })
                    })
                    .error(function(data, status, headers, config) {

                    })

                map.panTo(marker_latlngs[i]);
            } else {

                if (prev_infowindow) {
                    prev_infowindow.close();
                    $("#weatherDiv").empty();
                }

                prev_infowindow = infowindows[i];

                infowindows[i].open(map, markers[i]);

                if ($.active > 0) {
                    profile_request.abort();
                }

                $scope.marker_name = marker_names[i];
                $scope.marker_misc = marker_miscinfos[i];
                $scope.marker_location = marker_locations[i];

                encodedData = 'marker_id=' +
                    encodeURIComponent(marker_ids[i]) +
                    '&marker_name=' +
                    encodeURIComponent(marker_names[i]);

                profile_request = $http({
                        method: 'POST',
                        url: './models/load_floorplans_in_marker.php',
                        data: encodedData,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .success(function(data, status, headers, config) {

                        $scope.profiles_array = data;

                        if ($scope.profiles_array.no_profiles) {
                            $scope.noInformation = true;
                        } else {
                            $scope.noInformation = false;
                            $scope.noProfiles = true;
                            $scope.hasFloorplans = true;
                        }
                        streetview.getPanoramaByLocation(marker_latlngs[i], 50, function(data, status) {
                            if (status == 'OK') {
                                document.getElementById('street-view').style.display = 'block';
                                //configure panorama
                                panorama = new google.maps.StreetViewPanorama(
                                    document.getElementById('street-view'), {
                                        position: marker_latlngs[i],
                                        pov: {
                                            heading: 0,
                                            pitch: 0
                                        },
                                        zoom: 1,
                                        linksControl: false,
                                        addressControl: false
                                    });
                            } else {
                                document.getElementById('street-view').style.display = 'none';
                            }
                        });

                        var url = "http://api.openweathermap.org/data/2.5/weather?lat=" + marker_latitudes[i] + "&" + "lon=" + marker_longitudes[i] + "&APPID=cd4eda95a76d3de65a551a892bf8ce41&units=imperial";

                        $http({
                                method: 'GET',
                                url: url,
                            })
                            .success(function(data, status, headers, config) {
                                $("#weatherDiv").html("<img id='weatherPic' /> <span style='color: #19A3FF; font-size: 18px;'> " + data.main.temp + "&degF </span> <br /> Click For More Information");
                                document.getElementById("weatherPic").src = "images/weather/" + data.weather[0].icon + ".png";
                            })
                            .error(function(data, status, headers, config) {
                                $("#weatherDiv").html("<h4 style='color: red;'> No Weather </h4>");
                            })
                    })
                    .error(function(data, status, headers, config) {

                    })

                map.panTo(marker_latlngs[i]);
            }
        });

    }

    function weatherDiv(controlDiv, map) {
        // Set CSS for the control border.
        var controlUI = document.createElement('div');
        controlUI.style.backgroundColor = 'rgba(0,0,0,0)';
        controlUI.style.textStyle = 'bold';
        controlUI.style.textShadow = "1px -1px 0 #ffffff, 1px -1px 0 #ffffff, -1px 1px 0 #ffffff, 1px 1px 0 #ffffff";
        controlUI.style.fontWeight = 'bold';
        controlUI.style.marginLeft = '-150px';
        controlUI.style.cursor = 'pointer';
        controlUI.id = 'weatherDiv';
        controlDiv.appendChild(controlUI);

        // Setup the click event listeners: calls the centermap function
        controlUI.addEventListener('click', function() {
            var moreWeatherURL = "http://api.openweathermap.org/data/2.5/weather?lat=" + marker_latitudes[$scope.marker_clicked_for_weather_information] + "&" + "lon=" + marker_longitudes[$scope.marker_clicked_for_weather_information] + "&APPID=cd4eda95a76d3de65a551a892bf8ce41&units=imperial";

            $http({
                    method: 'GET',
                    url: moreWeatherURL,
                })
                .success(function(data, status, headers, config) {
                    $("#currentWeatherPic").html("<img src='images/weather/" + data.weather['0']['icon'] + ".png' />");
                    $("#currentWeatherDescription").html(data.weather['0']['description']);
                    $("#currentWeatherTemp").html(data.main['temp'] + "&degF");
                    $("#currentWeatherHumidity").html(data.main['humidity']+"%");
                    $("#currentWeatherWind").html(data.wind['speed']+" mph");

                    city_id = data.sys.id;

                    var forecastURL = "http://api.openweathermap.org/data/2.5/forecast?lat=" + marker_latitudes[$scope.marker_clicked_for_weather_information] + "&" + "lon=" + marker_longitudes[$scope.marker_clicked_for_weather_information] + "&APPID=cd4eda95a76d3de65a551a892bf8ce41&units=imperial";

                    $http({
                            method: 'GET',
                            url: forecastURL,
                        })
                        .success(function(data, status, headers, config) {
                            angular.forEach(data.list, function(value, key) {
                                var dayOfWeek = Date.parse(value.dt_txt);
                                if (value.dt_txt.indexOf("12:00:00") > 0) {
                                    $("#dayOfWeekForWeather").append("<td> " + dayOfWeek.toString('M/d<br />ddd<br />12:00 tt') + " </td>");
                                    $("#weatherForForecast").append("<td><img src='images/weather/" + value.weather['0']['icon'] + ".png' /></td>");
                                    $("#tempForForecast").append("<td> " + value.main['temp'] + "&degF</td>");
                                    $("#descriptionForForecast").append("<td> " + value.weather['0']['description'] + "</td>");
                                } else if (value.dt_txt.indexOf("00:00:00") > 0) {
                                    $("#dayOfWeekForWeather").append("<td> " + dayOfWeek.toString('M/d<br />ddd<br />12:00 tt') + " </td>");
                                    $("#weatherForForecast").append("<td><img src='images/weather/" + value.weather['0']['icon'] + ".png' /></td>");
                                    $("#tempForForecast").append("<td> " + value.main['temp'] + "&degF</td>");
                                    $("#descriptionForForecast").append("<td> " + value.weather['0']['description'] + "</td>");
                                }
                            });

                        })
                        .error(function(data, status, headers, config) {
                            if (status == 429) {
                                $("#weatherError").html("<h4 style='color: red;'> Could Not Get Weather For Location (Too Many Requests) </h4>");
                            } else {
                                $("#weatherError").html("<h4 style='color: red;'> Could Not Get Weather For Location </h4>");
                            }
                        })

                    $("#markerNameWeather").html("Weather For " + data.name);
                })
                .error(function(data, status, headers, config) {
                    if (status == 429) {
                        $("#weatherError").html("<h4 style='color: red;'> Could Not Get Weather For Location (Too Many Requests) </h4>");
                    } else {
                        $("#weatherError").html("<h4 style='color: red;'> Could Not Get Weather For Location </h4>");
                    }
                })
            $("#weatherModal").modal("show");
        });
    }

    $scope.load_view_floorplan = function(floorplan) {
        if ($.active > 0) {
            profile_request.abort();
        }

        encodedData = 'floorplan=' +
            encodeURIComponent(floorplan) +
            '&default_pin_color=' +
            encodeURIComponent(default_pin_color);

        profile_request = $http({
                method: 'POST',
                url: './models/load_floorplans_map_model.php',
                data: encodedData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .success(function(json, status, headers, config) {
                angular.forEach(json, function(data, key) {
                    if (key == 'floorplan_information') {
                        document.getElementById("floorplanName").innerHTML = data.marker_name + ": " + data.floor;
                        document.getElementById("floorplanImage").src = data.image_location;
                    } else {
                        if (key == "no_markers") {

                        } else {
                            angular.forEach(data, function(value, key) {
                                overalayColor(value.pin_color);
                                $("#floorplanModalDiv").append('<img src=' + fullimg + ' class="markers_on_floorplan" id="marker_' + value.marker_id + '" style="display: block; position: absolute; left:' + value.latitude + '%; top:' + value.longitude + '%;" title="' + value.name + '\n' + value.location + '" onclick="loadInfoWindow(`' + value.marker_id + '`, `' + value.name + '`)"/>');
                            });
                        }
                    }
                });
                $("#viewFloorplanModal").modal("show");
            })
            .error(function(data, status, headers, config) {

            })
    }

    $scope.showMiscInfo = function() {
        $scope.showMarkerInfoButton = false;
        $scope.misc_panel = true;
    }

    $scope.hideMiscInfo = function() {
        $scope.misc_panel = false;
        $scope.showMarkerInfoButton = true;
    }

}]);

$(document).ready(function() {

    $("#weatherModal").on('hidden.bs.modal', function() {
        $("#weatherError").empty(); // Clear the error message
        $("#weatherForForecast").empty(); // Clear the error message
        $("#dayOfWeekForWeather").empty(); // Clear the error message
        $("#tempForForecast").empty(); // Clear the error message
        $("#descriptionForForecast").empty(); // Clear the error message
    });

    $("#viewFloorplanModal").on("hidden.bs.modal", function() {
        //$("#floorplanInformationField").empty();
        $("#floorplanInformationField").html("<h3 style='text-align: center;'>Select a Residence</h3>");
        $(".markers_on_floorplan").remove();
    });

    $("#floorplanInformationField").on("click", "#showFloorplanMarkerInfo", function() {
        $("#floorplanMarkerInfo").show();
        $("#hideFloorplanMarkerInfo").show();
        $("#showFloorplanMarkerInfo").hide();
    });
    $("#floorplanInformationField").on("click", "#hideFloorplanMarkerInfo", function() {
        $("#floorplanMarkerInfo").hide();
        $("#hideFloorplanMarkerInfo").hide();
        $("#showFloorplanMarkerInfo").show();
    });

});

function loadInfoWindow(marker, name) {
  //$("#floorplanInformationField").html("");

    $.post("./models/load_profiles_in_marker.php", {
            marker_id: marker,
            marker_name: name
        },
        function(data) {
            data = jQuery.parseJSON(data);
            console.log(data);
            $("#floorplanInformationField").empty();
            if (data.no_profiles) {
                if (data.marker_miscinfo) {
                    $("#floorplanInformationField").append("<h4 id='profileRow' style='font-weight: bold;'>" + data.marker_name + "<h4><h5 id='profileRow' style='font-weight: bold;'>" + data.marker_location + "</h5> <button class='btn btn-info btn-xs' id='showFloorplanMarkerInfo'> Show Info </button> <button class='btn btn-info btn-xs' id='hideFloorplanMarkerInfo' style='display: none;'> Hide Info </button> <textarea style='width: 100%; display:none;' rows='4' id='floorplanMarkerInfo'> " + data.marker_miscinfo + " </textarea> <hr />");
                } else {
                    $("#floorplanInformationField").append("<h4 id='profileRow' style='font-weight: bold;'>" + data.marker_name + "<h4><h5 id='profileRow' style='font-weight: bold;'>" + data.marker_location + "</h5> <hr />");
                }
                $("#floorplanInformationField").append("<h3>" + data.no_profiles + "</h3>");
            } else {
                if (data[0].marker_miscinfo) {
                    $("#floorplanInformationField").append("<h4 id='profileRow' style='font-weight: bold;'>" + data[0].marker_name + "<h4><h5 id='profileRow' style='font-weight: bold;'>" + data[0].marker_location + "</h5> <button class='btn btn-info btn-xs' id='showFloorplanMarkerInfo'> Show Info </button> <button class='btn btn-info btn-xs' id='hideFloorplanMarkerInfo' style='display: none;'> Hide Info </button> <textarea style='width: 100%; display:none;' rows='4' id='floorplanMarkerInfo'> " + data[0].marker_miscinfo + " </textarea> <hr />");
                } else {
                    $("#floorplanInformationField").append("<h4 id='profileRow' style='font-weight: bold;'>" + data[0].marker_name + "<h4><h5 id='profileRow' style='font-weight: bold;'>" + data[0].marker_location + "</h5> <hr />");
                }
                $.each(data, function(key, value) {
                    $("#floorplanInformationField").append("<h4 id='profileRow' style='font-weight: bold;'>"+value.residents_name+"</h4><table class='table table-hover table-striped' id='floorplanInformationFieldTable" + key + "'> </table>");
                    if (value.phone_01) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td id='profileRow'> Primary Phone: </td><td> " + value.phone_01 + " </td></tr>");
                    }
                    if (value.phone_02) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td id='profileRow'> Secondary Phone: </td><td> " + value.phone_02 + " </td></tr>");
                    }
                    if (value.email_01) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td id='profileRow'> Primary E-mail: </td><td> " + value.email_01 + " </td></tr>");
                    }
                    if (value.email_02) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td id='profileRow'> Secondary E-mail: </td><td> " + value.email_02 + " </td></tr>");
                    }
                    if (value.miscinfo) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td id='profileRow'> Misc Info: </td><td><textarea style='width: 100%;' rows='4'> " + value.email_02 + " </textarea> </td></tr>");
                    }
                });
                /*
                $.each(data, function(key, value) {
                    $("#floorplanInformationField").append("<h4 id='profileRow'>"+value.residents_name+"</h4><table class='table table-hover' id='floorplanInformationFieldTable" + key + "'> </table>");
                    $("#floorplanInformationFieldTable" + key + "").append("<tr><td style='color: #006699; font-weight: bold;'> Resident: </td><td> " + value.residents_name + " </td></tr>");
                    if (value.phone_01) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td style='color: #006699; font-weight: bold;'> Primary Phone: </td><td> " + value.phone_01 + " </td></tr>");
                    }
                    if (value.phone_02) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td style='color: #006699; font-weight: bold;'> Secondary Phone: </td><td> " + value.phone_02 + " </td></tr>");
                    }
                    if (value.email_01) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td style='color: #006699; font-weight: bold;'> Primary E-mail: </td><td> " + value.email_01 + " </td></tr>");
                    }
                    if (value.email_02) {
                        $("#floorplanInformationFieldTable" + key + "").append("<tr><td style='color: #006699; font-weight: bold;'> Secondary E-mail: </td><td> " + value.email_02 + " </td></tr>");
                    }
                });
                */
            }
        });
}
