communitApp.controller('createcommunityController', ['$scope', '$http', function($scope, $http) {

    $scope.owned_communities_counter = 0; // Used to keep track of how many communities user owns
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
                    if (value.privilege.trim() == "owner") {

                        $scope.owned_communities_counter++;
                        $scope.joined_communities_counter++;

                        if ($scope.owned_communities_counter >= 2) {
                            alert("You already own the max amount of communities.");
                            window.location.href = "myhome.php";
                        }

                        if ($scope.joined_communities_counter >= 4) {
                            alert("You are already apart of the max amount of communities.");
                            window.location.href = "myhome.php";
                        }

                    } else {

                        $scope.joined_communities_counter++;

                        if ($scope.joined_communities_counter >= 4) {
                            alert("You are already apart of the max amount of communities.");
                            window.location.href = "myhome.php";
                        }

                    }
                });
            }
        })
        .error(function(data, status, headers, config) {

        })

    $scope.postCreateCommunity = function() {


        var encodedData = 'inputUser=' +
            encodeURIComponent(localStorage.getItem("communit_user_id")) +
            '&inputCommunityName=' +
            encodeURIComponent($("#name").val()) +
            '&inputCommunityDescription=' +
            encodeURIComponent($("#description").val()) +
            '&inputDefaultPinColor=' +
            encodeURIComponent($("#pincolor").val()) +
            '&inputDefaultPinColorStatus=' +
            encodeURIComponent($("input:radio[name='default_pin_color_status']:checked").val()) +
            '&inputAllowUserPinColors=' +
            encodeURIComponent($("input:radio[name='allow_user_pin_colors']:checked").val()) +
            '&inputCommunityCity=' +
            encodeURIComponent($("#city").val()) +
            '&inputCommunityState=' +
            encodeURIComponent($("#state").val()) +
            '&inputCommunityCountry=' +
            encodeURIComponent($("#country").val());

        console.log(encodedData);

        $http({
                method: 'POST',
                url: './models/createcommunity_model.php',
                data: encodedData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .success(function(data, status, headers, config) {
                if (data.trim() === 'success') {
                    window.location.href = 'myhome.php';
                } else {
                    $scope.errorMsgModal = 'There was an error submitting the form.';
                }

            })
            .error(function(data, status, headers, config) {
                $scope.errorMsgModal = 'Unable to submit form';
            })
    }

}]);