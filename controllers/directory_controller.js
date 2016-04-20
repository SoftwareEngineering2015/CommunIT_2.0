communitApp.controller('directory_controller', function($scope, $http) {
    //
    //alert("entered the controller");
    //This is for grabing the info from local storage.....
    $scope.user = localStorage.getItem('communit_user_id');
    //$scope.user = 'FE9EA169A531';
    //Show switch for simple view
    $scope.showSimple = false;
    //Show switch for detailed view
    $scope.showDetailed = false;
    //Show switch for error view
    $scope.showError = false;
    //Model for community checkbox
    $scope.selectCommunity;
    $scope.selectedCommunity;
    
    $scope.changeCommunity = function(selectCommunity) {
        //alert(selectCommunity);
        $scope.selectedCommunity = $scope.communities[selectCommunity];
        
    };
    
    $scope.getCommunities = function() {
      var requestProfiles = $http({
      method : 'POST',
      url    : './models/directory_model.php',
      data: {
          //user : $scope.user,
          user     : $scope.user,
          command  : "markers"
      },
      headers: { 'Content-Type' : 'application/json'}
    });
        requestProfiles.then(function(data, status, headers, config) {
            
            $scope.communities = data.data;
            
            
            if ($scope.communities.error) {
                //alert("An error has occured");
                $scope.showSimple = false;
                $scope.showDetailed = false;
                $scope.showError = true;
            } else {
                //alert("Found the communities");
                $scope.showSimple = true;
            }
        });
    }
});