communitApp.controller('directoryController', function($scope, $http) {
    //
    //alert("entered the controller");
    //This is for grabing the info from local storage.....
    $scope.user = localStorage.getItem('communit_user_id');
    //$scope.user = 'FE9EA169A531';
    //Stuff for Community Show
    $scope.showCommunitySelect = false;
    $scope.showCommunity = false;
    //Stuff for Marker Show
    $scope.showMarkerSelect = false;
    $scope.showMarker = false;
    //Stuff for floorplan show 
    $scope.showSelectFloor = false;
    $scope.showFloor = false;
    //Stuff for room show 
    $scope.showSelectRoom = false;
    $scope.showRoom = false;
    //Stuff for modal....
    $scope.showEmailButton = false;
    //Show switch for detailed view
    $scope.showDetailed = false;
    //Show switch for error view
    $scope.showError = false;
    //Model for community checkbox
    $scope.primaryEmail = false;
    $scope.secondaryEmail = false;
    
    $scope.selectCommunity;
    $scope.selectMarker;
    $scope.selectFloor;
    $scope.selectRoom;
    $scope.selectedCommunity;
    $scope.selectedMarker;
    
    $scope.openEmailModal = function(){
       $('#emailListModal').modal('show');
       $scope.primaryEmail = true;
       $scope.secondaryEmail = false;
    };
    
    $scope.showSecondaryEmail = function() {
        $scope.secondaryEmail = true;
        $scope.primaryEmail = false;
    };
    
    $scope.showPrimaryEmail = function() {
        $scope.primaryEmail = true;
        $scope.secondaryEmail = false;
    };
    
    
    $scope.changeCommunity = function(selectCommunity) {
        $scope.showCommunity = true;
        $scope.showMarkerSelect = true;
        $scope.showEmailButton = true;
        $scope.showMarker = false;
        $scope.showFloor = false;
        $scope.showRoom = false;
        $scope.showSelectFloor = false;
        $scope.showSelectRoom = false;
    };
    
    $scope.changeMarker = function() {
        $scope.showSelectRoom = false;
        $scope.showSelectFloor = false;
        $scope.showCommunity = false;
        $scope.showMarker = true;
        $scope.showFloor = false;
        $scope.showRoom = false;
        $scope.getFloorplan();
    };
    
    $scope.changeFloor = function() {
        $scope.showSelectRoom = true;
        $scope.showCommunity = false;
        $scope.showMarker = false;
        $scope.showFloor = true;
        $scope.showRoom = false;
    };
    
    $scope.changeRoom = function() {
        $scope.showCommunity = false;
        $scope.showMarker = false;
        $scope.showFloor = false;
        $scope.showRoom = true;
    };
    
    $scope.getFloorplan = function(value) {
        //alert(value);
        if (value == 1) {

        var requestFloorplan = $http({
            method : 'POST',
            url    : './models/directory_model.php',
            data   : {
                marker  : $scope.selectMarker,
                command : "floorplans"
            },
            headers : {'Content-Type' : 'application/json'}
        });
            requestFloorplan.then(function(data, status, headers, config) {
                
                $scope.floorplans = data.data;
                
                if ($scope.communities.error) {
                    $scope.showError = true;
                }   else {
                    //alert("Floorplans, Success!");
                    $scope.showSelectFloor = true;   
                }
                
            });
        }
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
                $scope.showError = true;
            } else {
                //alert("Found the communities");
                $scope.showCommunitySelect = true;
            }
        });
    }
});