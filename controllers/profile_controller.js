//This controller gets profile Information for each marker for a particluar user_id.
//alert("Entered the controller.");


communitApp.controller('profileCtrl', function($scope, $http) {

    $scope.user = localStorage.getItem('communit_user_id');
    $scope.userfirstname = localStorage.getItem('communit_user_first');
    $scope.userlastname = localStorage.getItem('communit_user_last');
    $scope.marker = "";
    $scope.selectProfile;
    $scope.viewSwitch = false;

    //Here we grab the json object from profileModel.php.
    var request = $http({
      method : 'POST',
      url    : './models/profile_model.php',
      data   : {
        user: $scope.user
      },
      headers: { 'Content-Type': 'application/json' }
    });

    //If it worked, then set the results equal to $scope.contents.
    request.success(function (data) {
      //alert("sent the post");
      $scope.contents = data;


      for(var i = 0; i < $scope.contents.length; i++){
        if($scope.contents[i].has_edited == 0){
          //alert("no profile here");
          //$scope.selectProfile = i;
          window.location.href = 'editprofile.php';
          exit(status);
        }
        $scope.noProfileMessage = true;
      }
          $scope.viewSwitch = true;

      if ($scope.contents.error) {
          $scope.viewSwitch = false;
      }

    });

});
