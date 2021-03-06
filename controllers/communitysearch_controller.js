communitApp.controller('communitysearchController', ['$scope', '$http', function($scope, $http) {

    $scope.can_join = true;

    $scope.joined_communities_counter = 0; // Used to keep track of how many communities user is in

    $scope.communities = []; // Holds the return json of the communities the user searched for

    $scope.has_results = false;

    $scope.displayed_communities = []; // Holds the return json of the communities the user searched for
    $scope.range = 0; // Used to limit the number of results shown 
    $scope.hide_next = false; // Used to hide the next button
    $scope.hide_last = true; // Used to hide the last button 
    $scope.show_side_version = false;
    $scope.row_clikced = 0;

    $scope.max_shown = 10; // Used to hold the number of communities displayed

    $scope.search_section = 0; // This is the section of the contents of the searched data (e.g. 1,2,3...)

    var encodedData = 'user=' +
        encodeURIComponent(localStorage.getItem("communit_user_id"));

    $http({
            method: 'POST',
            url: './models/load_myhome_model.php',
            data: encodedData,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .success(function(data, status, headers, config) {
            if (data == "noCommunities") {

            } else {
                angular.forEach(data, function(value, key) {

                    $scope.joined_communities_counter++;

                    if ($scope.joined_communities_counter >= 10) {
                        $scope.can_join = false;
                        $("#maxJoinedAlertModal").modal("show");
                    }
                });
            }
        })
        .error(function(data, status, headers, config) {

        })

    $scope.submit_search = function() {

        var name = document.getElementById("name").value; // Get the value of the selection they chose
        var city = document.getElementById("city").value; // Get the value of the selection they chose
        var state = document.getElementById("state").value; // Get the value of the selection they chose
        var country = document.getElementById("country").value; // Get the value of the selection they chose


        encodedData = 'name=' +
            encodeURIComponent(name) +
            '&city=' +
            encodeURIComponent(city) +
            '&state=' +
            encodeURIComponent(state) +
            '&country=' +
            encodeURIComponent(country) +
            '&can_join=' +
            encodeURIComponent($scope.can_join);

        $http({
                method: 'POST',
                url: './models/communitysearch_model.php',
                data: encodedData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .success(function(data, status, headers, config) {
                if (data[0].status.trim() == "success") {
                    $scope.displayed_communities.length = 0;
                    $scope.has_results = true;
                    $scope.communities = data;

                    $scope.hide_next = false;
                    $scope.hide_last = true;

                    $scope.range = 0;
                    counter = 0;
                    while (counter < $scope.max_shown && $scope.range < $scope.communities.length) {
                        $scope.displayed_communities.push($scope.communities[$scope.range]);
                        $scope.range++;
                        counter++;
                    }

                    if ($scope.range >= $scope.communities.length) {
                        $scope.hide_next = true;
                    }
                } else {
                    $scope.has_results = false;
                    $scope.communities = data;
                }  
            })
            .error(function(data, status, headers, config) {
                $scope.has_results = false;
            })
    }

    $scope.load_map_into_modal = function (community) {

        $("#community_map").empty(); // Clear the div when they change their selection for which community profile they would like to edit

        encodedData = 'community=' +
            encodeURIComponent(community);

        $http({
                method: 'POST',
                url: './models/jquery_community_map_load.php',
                data: encodedData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .success(function(data, status, headers, config) {
                $("#community_map").html(data);
                $('#view_community_modal').on('shown.bs.modal', function() {
                    google.maps.event.trigger(map, 'resize');
                    map.fitBounds(bounds);
                });
            })
            .error(function(data, status, headers, config) {

            })
    }

    $scope.show_join_community_modal = function(community) {
        document.getElementById("joinCommunityButton").value = community;
        $("#join_community_modal").modal("show");
    }

    $scope.next = function() {
        $scope.displayed_communities.length = 0;

        $scope.search_section = $scope.search_section + $scope.max_shown;

        counter = 0;
        while (counter < $scope.max_shown && $scope.range < $scope.communities.length) {
            index = counter + $scope.range;
            if ($scope.communities[index]) {
                $scope.displayed_communities.push($scope.communities[index]);
            }
            counter++;
        }

        $scope.range = $scope.range + $scope.max_shown;

        if ($scope.range >= $scope.communities.length) {
            $scope.hide_next = true;
        }

        if ($scope.range != 0) {
            $scope.hide_last = false;
        }
    }

    $scope.last = function() {
        $scope.displayed_communities.length = 0;

        $scope.search_section = $scope.search_section - $scope.max_shown;

        $scope.range = $scope.range - ($scope.max_shown * 2);

        if ($scope.range <= 0) {
            $scope.hide_last = true;
        }

        counter = 0;
        while (counter < $scope.max_shown) {
            $scope.displayed_communities.push($scope.communities[$scope.range]);
            $scope.range++;
            counter++;
        }

        if ($scope.range < $scope.communities.length) {
            $scope.hide_next = false;
        }
    }

    $scope.showSideVersion = function(index) {
        $scope.show_side_version = true;
        $scope.row_clicked = index + $scope.search_section;
        
    }

    $scope.search = function() {
        $scope.show_side_version = false;
    }

}]);

$(document).ready(function() {

    $("#joinCommunityButton").click(function(event) {

        $.post(
            "models/join_community_model.php", {
                community: $(this).val(),
                user: localStorage.getItem("communit_user_id")
            },
            function(data) {
                if (data.trim() == "alreadyJoined") {
                    $("#joinCommunitySuccessMessage").empty();
                    $("#joinCommunityErrorMessage").html("You are already apart of this community.");
                } else if (data.trim() == "alreadyRequested") {
                    $("#joinCommunitySuccessMessage").empty();
                    $("#joinCommunityErrorMessage").html("There is already a request for you joining this community.");
                } else if (data.trim() == "success") {
                    $("#joinCommunitySuccessMessage").empty();
                    $("#joinCommunityErrorMessage").empty();
                    $("#joinCommunitySuccessMessage").html("You're request to join the community has been sent to the owner.");
                } else {
                    $("#joinCommunitySuccessMessage").empty();
                    $("#joinCommunityErrorMessage").html("There was an error submitting the request.");
                }
            }
        );

    });

    $('#join_community_modal').on('hidden.bs.modal', function() {
        $("#joinCommunityErrorMessage").empty();
        $("#joinCommunitySuccessMessage").empty();
    })
});