communitApp.controller('directory_controller', function($scope, $http) {
    //
    ////alert("entered the controller");
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
    $scope.noRoom = false;
    $scope.noUsers = false;
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
       
       if ($scope.showCommunity == true) {
       $('#emailListModal').modal('show');
       $scope.primaryEmail = true;
       $scope.secondaryEmail = false;
       
       } else if ($scope.showMarker == true) {
       $('#emailMarkerList').modal('show');
       $scope.primaryEmail = true;
       $scope.secondaryEmail = false;    
       
       } else if ($scope.showFloor == true) {
       $('#emailFloorList').modal('show');
       $scope.primaryEmail = true;
       $scope.secondaryEmail = false; 
          
       } else if ($scope.showRoom == true) {
       $('#emailRoomList').modal('show');
       $scope.primaryEmail = true;
       $scope.secondaryEmail = false;    
       }
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
        var count = 0;
        
        for (var key in $scope.communities[selectCommunity]) {
           if ($scope.communities[selectCommunity].hasOwnProperty(key)) {
                count++;
            }
        }
        ////alert(count);
        if (count > 2) {
            $scope.showMarkerSelect = true;
            $scope.showCommunity = true;
            $scope.showEmailButton = true;
            $scope.showMarker = false;
            $scope.showFloor = false;
            $scope.showRoom = false;
            $scope.showSelectFloor = false;
            $scope.showSelectRoom = false;
            $scope.noMarkers = false;
            $scope.noRooms = false;
            $scope.noUsers = false;
            $scope.selectMarker = "";
            $scope.selectFloor = "";
            $scope.selectRoom = "";
            $scope.floorplans = null;
            
        } else {
            $scope.showEmailButton = false;
            $scope.showMarkerSelect = false;
            $scope.showSelectFloor = false;
            $scope.showSelectRoom = false;
            $scope.showCommunity = false;
            $scope.showMarker = false;
            $scope.showFloor = false;
            $scope.showRoom = false;
            $scope.noMarkers = true;
            $scope.noRooms = false;
            $scope.noUsers = false;
            $scope.selectMarker = "";
            $scope.selectFloor = "";
            $scope.selectRoom = "";
            $scope.floorplans = null;
        }
        
        count = 0; 
        
    };
    
    $scope.changeMarker = function(selectMarker, selectCommunity) {
        ////alert(selectMarker);
        ////alert(selectCommunity);
        count = 0;
          
        
        for(var key in $scope.communities[selectCommunity][selectMarker]) {
            if($scope.communities[selectCommunity][selectMarker].hasOwnProperty(key)) {
                count++;
            }
        }
        ////alert(count);
        
        
        if (count > 4) {
        
        $scope.floorplans = null;   
        $scope.showSelectRoom = false;
        $scope.showSelectFloor = false;
        $scope.showCommunity = false;
        $scope.showMarker = true;
        $scope.showFloor = false;
        $scope.showRoom = false;
        $scope.noRooms = false;
        $scope.noMarkers = false;
        $scope.noUsers = false;
        $scope.selectFloor = "";
        $scope.selectRoom = "";
        $scope.floorplans = null;
        $scope.getFloorplan();
        
        } else if (count <= 4) {
        
        $scope.floorplans = null;  
        $scope.showSelectRoom = false;
        $scope.showSelectFloor = false;
        $scope.showCommunity = false;
        $scope.showMarker = false;
        $scope.showFloor = false;
        $scope.showRoom = false;
        $scope.noMarkers = false;
        $scope.noRooms = false;
        $scope.noUsers = true;
        $scope.selectFloor = "";
        $scope.selectRoom = "";
        $scope.floorplans = null;
        $scope.getFloorplan();
          
        }
        
        count = 0;
    };
    
    $scope.changeFloor = function(selectFloor) {
        
        ////alert(selectFloor);
        var count = 0;
        
        for (var key in $scope.floorplans[selectFloor]) {
           if ($scope.floorplans[selectFloor].hasOwnProperty(key)) {
                ////alert(key + " -> " + $scope.floorplans[selectFloor][key]);
                count++;
            }
        }
        
        //alert(count);
        
        if (count > 2) {
           $scope.showEmailButton = true;
           $scope.showSelectRoom = true;
           $scope.showCommunity = false;
           $scope.showMarker = false;
           $scope.showFloor = true;
           $scope.showRoom = false;
           $scope.noRooms = false;
           $scope.noUsers = false;
           $scope.selectRoom = "";
            
        } else if (count <= 2) {
           $scope.showSelectRoom = false;
           $scope.showEmailButton = false;
           $scope.showCommunity = false;
           $scope.showFloor = false;
           $scope.showRoom = false;
           $scope.showMarker = false;
           $scope.noRooms = true;
           $scope.noUsers = false;
           $scope.selectRoom = "";
        }
           
    };
    
    $scope.changeRoom = function() {
        $scope.showCommunity = false;
        $scope.showMarker = false;
        $scope.showFloor = false;
        $scope.showRoom = true;
        $scope.noUsers = false;
        $scope.noRooms = false;
        $scope.noMarker = false;
    };
    
    $scope.getFloorplan = function(value) {
        ////alert(value);
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
                    ////alert("Floorplans, Success!");
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
                ////alert("An error has occured");
                $scope.showError = true;
            } else {
                ////alert("Found the communities");
                $scope.showCommunitySelect = true;
            }
        });
    }
});