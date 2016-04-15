communitApp.controller('joincommunityrequestsController', ['$scope', '$http', function($scope, $http) {

            $scope.requested_array = [];
            $scope.invited_array = [];

            var temp_array = []; // Used in the foreach loop to create a multidimensional array

            var encodedData = 'user=' +
                encodeURIComponent(localStorage.getItem("communit_user_id"));

            $http({
                    method: 'POST',
                    url: './models/show_community_requests_model.php',
                    data: encodedData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .success(function(data, status, headers, config) {
                        if (data == "noRequests") {
                            document.getElementById("requests").innerHTML = "<h4 style='color: black;'> You currently have no requests to join a community. </h4>";
                            document.getElementById("invites").innerHTML = "<h4 style='color: black;'>  You currently have no invites to join a community. </h4>";
                        } else {
                            angular.forEach(data, function(value, key) {
                                if (value.requested_or_invited.trim() == 0) {
                                    temp_array['community_id'] = value.community_id;
                                    temp_array['community_name'] = value.community_name;
                                    temp_array['community_description'] = value.community_description;
                                    temp_array['date_created'] = value.date_created;
                                    $scope.requested_array.push(temp_array);
                                    temp_array = {};

                                } else {
                                    temp_array['community_id'] = value.community_id;
                                    temp_array['community_name'] = value.community_name;
                                    temp_array['community_description'] = value.community_description;
                                    temp_array['date_created'] = value.date_created;
                                    $scope.invited_array.push(temp_array);
                                    temp_array = {};

                                }
                            });
                            
                            if ($scope.requested_array.length == 0) {
                                document.getElementById("requests").innerHTML = "<h4 style='color: black;'> You currently have no requests to join a community. </h4>";
                            } 
                            if ($scope.invited_array.length == 0) {
                                document.getElementById("invites").innerHTML = "<h4 style='color: black;'>  You currently have no invites to join a community. </h4>";
                            }
                        }
                    })
                    .error(function(data, status, headers, config) {

                    })

                    $scope.show_delete_request_to_community_modal = function(community) {

                        $scope.deleteRequestToCommunityButton = community;
                        $('#delete_request_to_community_modal').modal('show');

                    };

                    $scope.delete_request_to_community = function() {
                        $scope.requested_or_invited = 'requested';

                        var encodedData = 'user=' +
                            encodeURIComponent(localStorage.getItem("communit_user_id")) +
                            '&community=' +
                            encodeURIComponent($scope.deleteRequestToCommunityButton) +
                            '&requested_or_invited=' +
                            encodeURIComponent($scope.requested_or_invited);

                        $http({
                                method: 'POST',
                                url: './models/delete_request_model.php',
                                data: encodedData,
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .success(function(data, status, headers, config) {
                                if (data.trim() == "success") {
                                    location.reload();
                                } else {
                                    $("#deleteRequestMessage").html("There was an error deleting the request.");
                                }
                            })
                            .error(function(data, status, headers, config) {

                            })
                    };

                    $scope.show_accept_invite_to_community_modal = function(community) {

                        $scope.acceptInviteToCommunityButton = community;
                        $('#accept_invite_to_community_modal').modal('show');

                    };

                    $scope.accept_invite_to_community = function() {

                        var encodedData = 'user=' +
                            encodeURIComponent(localStorage.getItem("communit_user_id")) +
                            '&community=' +
                            encodeURIComponent($scope.acceptInviteToCommunityButton);

                        $http({
                                method: 'POST',
                                url: './models/accept_invite_into_community.php',
                                data: encodedData,
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .success(function(data, status, headers, config) {
                                if (data.trim() == "success") {
                                    location.reload();
                                } else {
                                    $("#acceptInvitedMessage").html("There was an error accepting the invite.");
                                }
                            })
                            .error(function(data, status, headers, config) {

                            })
                    };

                    $scope.show_delete_invite_to_community_modal = function(community) {

                        $scope.deleteInviteToCommunityButton = community;
                        $('#delete_invite_to_community_modal').modal('show');

                    };

                    $scope.delete_invite_to_community = function() {
                        $scope.requested_or_invited = 'invited';

                        var encodedData = 'user=' +
                            encodeURIComponent(localStorage.getItem("communit_user_id")) +
                            '&community=' +
                            encodeURIComponent($scope.deleteInviteToCommunityButton) +
                            '&requested_or_invited=' +
                            encodeURIComponent($scope.requested_or_invited);

                        $http({
                                method: 'POST',
                                url: './models/delete_request_model.php',
                                data: encodedData,
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .success(function(data, status, headers, config) {
                                if (data.trim() == "success") {
                                    location.reload();
                                } else {
                                    $("#deleteInvitedMessage").html("There was an error deleting the invite.");
                                }
                            })
                            .error(function(data, status, headers, config) {

                            })
                    };

                    $("#delete_request_to_community_modal").on('hidden.bs.modal', function() {
                        $("#deleteRequestMessage").empty(); // Clear the error message
                    });

                    $("#delete_invite_to_community_modal").on('hidden.bs.modal', function() {
                        $("#deleteInvitedMessage").empty(); // Clear the error message
                    });

                    $("#accept_invite_to_community_modal").on('hidden.bs.modal', function() {
                        $("#acceptInvitedMessage").empty(); // Clear the error message
                    });

                }]);