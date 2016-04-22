communitApp.controller('myhomeController', ['$scope', '$http', function($scope, $http) {

            
            $scope.userfirstname = localStorage.getItem('communit_user_first');
            $scope.userlastname = localStorage.getItem('communit_user_last');

            $scope.owned_communities_array = [];
            $scope.joined_communities_array = [];

            $scope.owned_communities_counter = 0; // Used to keep track of how many communities user owns
            $scope.joined_communities_counter = 0; // Used to keep track of how many communities user is in

            $scope.hide_owned_communities_button = false; // Used to keep track of how many communities user owns
            $scope.hide_join_communities_button = false; // Used to keep track of how many communities user is in

            $scope.user = localStorage.getItem('communit_user_id');
/*
            $http({
              method : 'POST',
              url    : './models/profile_model.php',
              data   : {
                user: $scope.user
              },
              headers: { 'Content-Type': 'application/json' }
            })
            .success(function (data) {
              //alert("sent the post");
              $scope.contents = data;

              for(var i = 0; i < $scope.contents.length; i++){
                if($scope.contents[i].has_edited == 0){
                  //alert("no profile here");
                  //$scope.selectProfile = i;
                  window.location.href = 'profile.php';
                  exit(status);
                }
              }

            });
*/

            var temp_array = []; // Used in the foreach loop to create a multidimensional array

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
                                if (value.privilege.trim() == "creator") {
                                    temp_array['community_id'] = value.community_id;
                                    temp_array['community_name'] = value.community_name;
                                    temp_array['community_description'] = value.community_description;
                                    temp_array['privilege'] = value.privilege;
                                    $scope.owned_communities_array.push(temp_array);
                                    temp_array = {};

                                    $scope.owned_communities_counter++;
                                    $scope.joined_communities_counter++;

                                    if ($scope.owned_communities_counter >= 10) {
                                        $scope.hide_owned_communities_button = true;
                                        $scope.hide_join_communities_button = true; // This will make the create a community button go away
                                    }

                                    if ($scope.joined_communities_counter >= 10) {
                                        $scope.hide_owned_communities_button = true;
                                        $scope.hide_join_communities_button = true; // This will make the create a community button go away
                                    }

                                } else {
                                    temp_array['community_id'] = value.community_id;
                                    temp_array['community_name'] = value.community_name;
                                    temp_array['community_description'] = value.community_description;
                                    temp_array['privilege'] = value.privilege;
                                    $scope.joined_communities_array.push(temp_array);
                                    temp_array = {};

                                    $scope.joined_communities_counter++;

                                    if ($scope.joined_communities_counter >= 10) {
                                        $scope.hide_owned_communities_button = true;
                                        $scope.hide_join_communities_button = true; // This will make the create a community button go away
                                    }

                                }
                            });
                        }
                    })
                    .error(function(data, status, headers, config) {

                    })

                    $scope.show_delete_community_modal = function(community) {

                        $scope.deleteCommunityButton = community;
                        $('#delete_community_modal').modal('show');

                    };

                    $scope.delete_community = function() {

                        var encodedData = 'community=' +
                            encodeURIComponent($scope.deleteCommunityButton);

                        $http({
                                method: 'POST',
                                url: './models/delete_community_model.php',
                                data: encodedData,
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .success(function(data, status, headers, config) {
                                if (data.trim() == "success") {
                                    location.reload();
                                } else {
                                    $("#deleteCommunityMessage").html("There was an error deleting the community.");
                                }
                            })
                            .error(function(data, status, headers, config) {

                            })
                    };

                    $scope.show_leave_community_modal = function(community) {

                        $scope.leaveCommunityButton = community;
                        $('#leave_community_modal').modal('show');

                    };

                    $scope.leave_community = function() {

                        var encodedData = 'community=' +
                            encodeURIComponent($scope.leaveCommunityButton) +
                            '&user=' +
                            encodeURIComponent(localStorage.getItem("communit_user_id"));

                        $http({
                                method: 'POST',
                                url: './models/leave_community_model.php',
                                data: encodedData,
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .success(function(data, status, headers, config) {
                                if (data.trim() == "success") {
                                    location.reload();
                                } else {
                                    $("#leaveCommunityMessage").html("There was an error leaving the community.");
                                }
                            })
                            .error(function(data, status, headers, config) {

                            })
                    };

                    $("#delete_community_modal").on('hidden.bs.modal', function() {
                        $("#deleteCommunityMessage").empty(); // Clear the error message
                    });

                    $("#leave_community_modal").on('hidden.bs.modal', function() {
                        $("#leaveCommunityMessage").empty(); // Clear the error message
                    });

                }]);
