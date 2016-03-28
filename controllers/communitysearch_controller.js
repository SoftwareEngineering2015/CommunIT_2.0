communitApp.controller('communitysearchController', ['$scope', '$http', function($scope, $http) {

    $scope.joined_communities_counter = 0; // Used to keep track of how many communities user is in

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

                    if ($scope.joined_communities_counter >= 4) {
                        alert("You are already apart of the max amount of communities.");
                        window.location.href = "myhome.php";
                    }
                });
            }
        })
        .error(function(data, status, headers, config) {

        })

}]);

$(document).ready(function() {
    $("#submit_search").click(function(event) {

        $("#search_results_div").empty().html("<h1> Searching... </h1>"); // Clear the div when they change their selection for which community profile they would like to edit

        var name = document.getElementById("name").value; // Get the value of the selection they chose
        var city = document.getElementById("city").value; // Get the value of the selection they chose
        var state = document.getElementById("state").value; // Get the value of the selection they chose
        var country = document.getElementById("country").value; // Get the value of the selection they chose

        $.post(
            "models/communitysearch_model.php", {
                name: name,
                city: city,
                state: state,
                country: country
            },
            function(data) {
                $("#search_results_div").empty().html(data);
            }
        );

    });

    $("#joinCommunityButton").click(function(event) {

        $.post(
            "models/join_community_model.php", {
                community: $(this).val(),
                user: localStorage.getItem("communit_user_id")
            },
            function(data) {
                if (data.trim() == "alreadyJoined") {
                    $("#joinCommunityErrorMessage").html("You are already apart of this community.");
                } else if (data.trim() == "alreadyRequested") {
                    $("#joinCommunityErrorMessage").html("There is already a request for you joining this community.");
                } else if (data.trim() == "success") {
                    $("#joinCommunityErrorMessage").empty();
                    $("#joinCommunitySuccessMessage").html("You're request to join the community has been sent to the owner.");
                } else {
                    $("#joinCommunityErrorMessage").html("There was an error submitting the request.");
                }
            }
        );

    });

    $('#join_community_modal').on('hidden.bs.modal', function () {
        $("#joinCommunityErrorMessage").empty();
        $("#joinCommunitySuccessMessage").empty();
    })
});

function load_map_into_modal(community) {
    $("#community_map").empty(); // Clear the div when they change their selection for which community profile they would like to edit
    $.post(
        "models/jquery_community_map_load.php", {
            community: community
        },
        function(data) {
            $("#community_map").html(data);
            $('#view_community_modal').on('shown.bs.modal', function() {
                google.maps.event.trigger(map, 'resize');
                map.setCenter(myCenter);
            });
        }
    );
}

function show_join_community_modal(community) {
    document.getElementById("joinCommunityButton").value = community;
    $("#join_community_modal").modal("show");
}