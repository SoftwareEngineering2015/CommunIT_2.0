<?php

  if(!isset($_REQUEST['community'])) {
    echo "noCommunity"; exit();
  } else {
    $community_id = $_REQUEST['community'];
  }

?>

    <script>
        var map;
        var streetview = new google.maps.StreetViewService();
        var myCenter = new google.maps.LatLng(41.7605556, -88.3200)

        var markers = []; // This is the array for the markers
        var marker_ids = []; //Marker information
        var marker_names = []; //Marker name
        var marker_latitudes = []; //holds marker latitude
        var marker_longitudes = []; //holds marker longitude
        var marker_latlngs = []; //holds parsed latlng marker data
        var marker_locations = []; //holds marker location
        var marker_pin_colors = []; //holds marker pin color
        var marker_has_floorplans = []; //Specifies that the marker has floorplans

        var infowindows = [];
        var prev_infowindow = false; // Varaible to check to 

        //creates a bounds object that is extended in the main loop
        var bounds = new google.maps.LatLngBounds();

        loadCommunity('<?php echo $community_id; ?>');

        // Function to load the map in angular
        function loadCommunity(community) {

            $.post("models/load_community_map_model.php", {
                community: community
            }, function(json) {
                if (json === 'noCommunity') {
                    return;
                } else {
                    var json = jQuery.parseJSON(json);
                    if (json == "") {
                        initializeNewCommunity();
                    } else {
                        $.each(json, function(key, data) {
                            if (key == 'community_information') {
                                document.getElementById("community_name").innerHTML = data.community_name;
                            } else {
                                if (key == "no_markers") {
                                    initializeNewCommunity();
                                } else {
                                    $.each(data, function(key, value) {
                                        marker_ids.push(value.marker_id);
                                        marker_names.push(value.name);
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

            });
        }

        function initializeNewCommunity() {
            var mapProp = {
                center: myCenter,
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("community_map"), mapProp);

            google.maps.event.addDomListener(window, "resize", function() {
                google.maps.event.trigger(map, "resize");
                map.panTo(myCenter);
            });

            bounds.extend(new google.maps.LatLng(28.70, -127.50));
            bounds.extend(new google.maps.LatLng(48.85, -55.90));

        }

        function initializeFilledCommunity() {
            var mapProp = {
                center: myCenter,
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("community_map"), mapProp);

            google.maps.event.addDomListener(window, "resize", function() {
                google.maps.event.trigger(map, "resize");
                map.panTo(myCenter);
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
                        content: "<b>Location:<br />" + marker_locations[i] + "</b>"
                    });
                    addFloorplanListener(i);
                } else {
                    infowindows[i] = new google.maps.InfoWindow({
                        content: "<b>Location:<br />" + marker_locations[i] + "</b>"
                    });
                    addProfileListener(i);
                }
            };
            myCenter = map.getCenter();

        }
        // End of the initialize function

        // Add a listener to the marker for a floorplan
        function addFloorplanListener(i) {
            markers[i].addListener('click', function() {

                if (prev_infowindow) {
                    prev_infowindow.close();
                }

                prev_infowindow = infowindows[i];

                infowindows[i].open(map, markers[i]);

                map.panTo(marker_latlngs[i]);
            });
        }


        // Add a click event listener to the marker for the profile
        function addProfileListener(i) {
            markers[i].addListener('click', function() {

                if (prev_infowindow) {
                    prev_infowindow.close();
                }

                prev_infowindow = infowindows[i];

                infowindows[i].open(map, markers[i]);

                map.panTo(marker_latlngs[i]);
            });
        }
    </script>