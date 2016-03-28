$.urlParam = function(name) {
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
}

$.post(
        "models/check_user_privilege_for_edit_community.php", {
            community: $.urlParam("community"), // Community ID
            user: localStorage.getItem("communit_user_id") // User ID
        },
        function(data) {
            if (data.trim() === "noMatch") {
                window.location.href = "myhome.php";
            }
        }
    );


var clicked_marker; // Used to hold the clicked marker that will be used for different queries
var placedMarker = []; // This is the marker that is placed when adding markers to the map

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

//Holds values for the dropdown menu
var divOptions = [];
var optionsDiv = [];
var options = [];

loadCommunity($.urlParam("community"));

// Function to load the map in angular
function loadCommunity(community) {

    $.post("models/load_edit_community_settings_map_model.php", {
        community: community
    }, function(data) {
        if (data === 'noCommunity') {
            window.location.href = 'myhome.php';
        } else {
            var obj = jQuery.parseJSON(data);
            if (obj == "") {
                initializeNewCommunity();
            } else {
                $.each(obj, function(key, value) {
                    //populates the marker arrays with the data from the database
                    marker_ids.push(value.marker_id);
                    marker_names.push(value.name);
                    marker_latitudes.push(value.latitude);
                    marker_longitudes.push(value.longitude);
                    marker_latlngs.push(new google.maps.LatLng(value.latitude, value.longitude));
                    marker_locations.push(value.location);
                    marker_pin_colors.push(value.pin_color);
                    marker_has_floorplans.push(value.has_floorplan);
                });

                // Load Google Maps
                initializeFilledCommunity();
                //google.maps.event.addDomListener(window, 'load', initialize);
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
    map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

    google.maps.event.addDomListener(window, "resize", function() {
        google.maps.event.trigger(map, "resize");
        map.panTo(myCenter);
    });

    geocoder = new google.maps.Geocoder();

}
// End of the initialize function

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

    geocoder = new google.maps.Geocoder();

    //this loop will create all of the markers, then invoke the addlistener function
    for (i in marker_ids) {
        //extend the bounds object ` fit the iterated marker
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
                content: "<b> Name: " + marker_names[i] + " <br /> Location: " + marker_locations[i] + " </b> <br /> <a onclick='edit_marker(`" + marker_ids[i] + "`, `" + i + "`)'> Edit Marker </a> <br /> <a onclick='load_floor_plans(`" + marker_ids[i] + "`)'> Load Floorplans </a>  <br /> <a onclick='delete_marker(`" + marker_ids[i] + "`)'> Delete Marker </a>"
            });
            addFloorplanListener(i);
        } else {
            infowindows[i] = new google.maps.InfoWindow({
                content: "<b> Name: " + marker_names[i] + " <br /> Location: " + marker_locations[i] + " </b> <br /> <a onclick='edit_marker(`" + marker_ids[i] + "`, `" + i + "`)'> Edit Marker </a> <br /> <a onclick='add_remove_residents(`" + marker_ids[i] + "`)'> Add / Remove Residents </a> <br /> <a onclick='delete_marker(`" + marker_ids[i] + "`)'> Delete Marker </a>"
            });
            addProfileListener(i);
        }
    };
    map.fitBounds(bounds);

}
// End of the initialize function

// Add a listener to the marker for a floorplan
function addFloorplanListener(i) {
    markers[i].addListener('click', function() {
        clicked_marker = markers[i];

        if (typeof placedMarker[0] !== 'undefined') {
            placedMarker[0].setMap(null);
            placedMarker.pop();
        }

        $("#informationField").empty();

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
        clicked_marker = markers[i];

        if (typeof placedMarker[0] !== 'undefined') {
            placedMarker[0].setMap(null);
            placedMarker.pop();
        }

        $("#informationField").empty();

        if (prev_infowindow) {
            prev_infowindow.close();
        }

        prev_infowindow = infowindows[i];

        infowindows[i].open(map, markers[i]);

        map.panTo(marker_latlngs[i]);
    });
    google.maps.event.addListener(map, 'click', function() {
        if (prev_infowindow) {
            prev_infowindow.close();
        }
    });
}

function load_floor_plans(marker) {
    clicked_marker = marker;
    $('#select_floorplans').empty();
    $('#floorplan_div').empty();
    $.post("models/floorplan_model.php", {
        input_marker: marker
    }, function(json) {
        var array_counter = 0;
        $.each(json, function(index, data) {
            if (array_counter == 0) {
                var display_first_floor_plan = true;
                $.each(data, function(index, value) {
                    $('#select_floorplans').append($("<option/>", {
                        value: index, //value.floorplan_id,
                        text: value.floor
                    }));
                    if (display_first_floor_plan) {
                        document.getElementById("floorplan").src = value.image_location;
                        display_first_floor_plan = false;
                    }
                });
                array_counter++;
            } else {
                $.each(data, function(index, value) {
                    $("#floorplan_div").append('<img src="images/house_pin02.png" id="marker_' + value.marker_id + '" style="display: block; position: absolute; left:' + value.latitude + '%; top:' + value.longitude + '%;" title="' + value.location + '" onclick="loadInfoWindow(`' + value.marker_id + '`,`' + value.name + '`)"/>');
                    colorPins(value.pin_color, "marker_" + value.marker_id);
                });
            }
        });
        $('#floorplans_model').modal('show'); // Clear the div when they change their selection for which community profile they would like to edit
    });
}

// Process to color the icons for the floorplan markers; alittle different from the google maps marker pins
// Function to fill the color of generated image
function Color(path, id) {
    color = path;

    if (!originalPixels) return; // Check if image has loaded
    var newColor = HexToRGB(color);

    for (var I = 0, L = originalPixels.data.length; I < L; I += 4) {
        if (currentPixels.data[I + 3] > 0) {
            currentPixels.data[I] = originalPixels.data[I] / 255 * newColor.R;
            currentPixels.data[I + 1] = originalPixels.data[I + 1] / 255 * newColor.G;
            currentPixels.data[I + 2] = originalPixels.data[I + 2] / 255 * newColor.B;
        }
    }

    ctx.putImageData(currentPixels, 0, 0);
    document.getElementById(id).src = canvas.toDataURL("image/house_pin.png");

}

// Function for draw a image
function colorPins(color, id) {
    //fullimg = document.getElementsByTagName('img')[0];
    selectImg = img;
    //alert(img.src);
    //alert(img.src);
    canvas.width = selectImg.width;
    canvas.height = selectImg.height;

    ctx.drawImage(selectImg, 0, 0, selectImg.naturalWidth, selectImg.naturalHeight, 0, 0, selectImg.width, selectImg.height);
    originalPixels = ctx.getImageData(0, 0, selectImg.width, selectImg.height);
    currentPixels = ctx.getImageData(0, 0, selectImg.width, selectImg.height);

    selectImg.onload = null;
    Color(color, id);
}
//End of the color process for the floor plan markers

function loadInfoWindow(id, name) {
    var panel = $('#infowindowPanel');
    if ($.active > 0) {
        profile_request.abort();
    }
    profile_request = $.post("models/jquery_floor_plan_dialog_load.php", {
            marker_id: id,
            marker_name: name
        },
        function(data) {
            $("#infowindow").dialog(data);
        }
    );
}

function edit_marker(id, i) {

    for (x in markers) {
        markers[x].setDraggable(false);
    }

    $("#informationField").empty();
    $.post(
        "models/jquery_load_edit_marker_form.php", {
            marker: id, // Id of the marker that is being edited
            marker_clicked: i // Specify which marker was clicked to edit it later
        },
        function(data) {
            $("#informationField").html(data);
        }
    );
    markers[i].setDraggable(true);

    google.maps.event.addListener(markers[i], 'dragend', function(event) {
        document.getElementById("latitude").value = this.getPosition().lat();
        document.getElementById("longitude").value = this.getPosition().lng();
    });
}

function add_remove_residents(id) {

    for (x in markers) {
        markers[x].setDraggable(false);
    }

    $("#informationField").empty();
    $.post(
        "models/jquery_add_remove_residents_form.php", {
            community: $.urlParam('community'), // Get the community id from the url
            marker: id // Id of the marker that is being edited
        },
        function(data) {
            $("#informationField").html(data);
        }
    );
}

function delete_marker(id) {

    for (x in markers) {
        markers[x].setDraggable(false);
    }

    $("#informationField").empty();
    document.getElementById("sumbitDeleteButton").value = id;
    $("#deleteMarkerModal").modal("show");
}

// Jquery Actions
$(document).ready(function() {
    $.urlParam = function(name) {
        var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }


    $("#editCommunitySettingsButton").click(function(event) {
        $("#informationField").empty();
        for (i in markers) {
            markers[i].setDraggable(false);
        }
        $.post(
            "models/jquery_load_edit_community_settings_form.php", {
                community: $.urlParam('community') // Get the community id from the url
            },
            function(data) {
                $("#informationField").html(data);
            }
        );
    });

    $("#listResidentsButton").click(function(event) {
        $("#informationField").empty();
        for (i in markers) {
            markers[i].setDraggable(false);
        }
        $.post(
            "models/jquery_load_residents_in_community.php", {
                community: $.urlParam('community') // Get the community id from the url
            },
            function(data) {
                $("#informationField").html(data);
            }
        );
    });

    $("#residentRequestsInviteButton").click(function(event) {
        $("#informationField").empty();
        for (i in markers) {
            markers[i].setDraggable(false);
        }
        $.post(
            "models/jquery_load_resident_requests_invites_form.php", {
                community: $.urlParam('community') // Get the community id from the url
            },
            function(data) {
                $("#informationField").html(data);
            }
        );
    });

    $("#addMarkersButton").click(function(event) {
        $("#informationField").empty();
        for (i in markers) {
            markers[i].setDraggable(false);
        }
        $.post(
            "models/jquery_load_add_community_markers_form.php", {
                community: $.urlParam('community') // Get the community id from the url
            },
            function(data) {
                $("#informationField").html(data);
            }
        );
    });

    $("#sumbitDeleteButton").click(function(event) {
        $.post(
            "models/delete_marker_model.php", {
                marker: $(this).val()
            },
            function(data) {
                if (data.trim() == "success") {
                    $("#deleteMarkerModal").modal("hide");
                    clicked_marker.setMap(null);
                } else {
                    alert("There was an error deleting the marker.");
                }
            }
        );
    });

    $('#deleteMarkerModal').on('show.bs.modal', function() {
        $(this).find('.modal-body').css({
            width: 'auto', //probably not needed
            height: 'auto', //probably not needed 
            'max-height': '100%'
        });
    });

    $("#addMarkersButton, #editCommunitySettingsButton").click(function(event) {
        if (typeof placedMarker[0] !== 'undefined') {
            placedMarker[0].setMap(null);
            placedMarker.pop();
        }
    });

    $("#floorplans_model").on('hidden.bs.modal', function() {
        $("#floorplan_div").empty(); // Clear the modal
        $("#select_floorplans").empty(); // Clear the select box
    });
    $("#select_floorplans").on("change", function() {
        var selected_floorplan = this.value;
        $('#floorplan_div').empty();
        $.post("models/floorplan_model.php", {
            input_marker: clicked_marker,
            floorplan: selected_floorplan
        }, function(json) {
            var array_counter = 0;
            $.each(json, function(index, data) {
                if (array_counter == 0) {
                    var display_foor_plan = 0;
                    $.each(data, function(index, value) {
                        if (display_foor_plan == selected_floorplan) {
                            document.getElementById("floorplan").src = value.image_location;
                        }
                        display_foor_plan++;
                    });
                    array_counter++;
                } else {
                    $.each(data, function(index, value) {
                        $("#floorplan_div").append('<img src="images/house_pin02.png" id="marker_' + value.marker_id + '" style="display: block; position: absolute; left:' + value.latitude + '%; top:' + value.longitude + '%;" title="' + value.location + '" onclick="loadInfoWindow(' + value.marker_id + ')"/>');
                        colorPins(value.pin_color, "marker_" + value.marker_id);
                    });
                }
            });
        });
    });
});