//var authenticationApp = angular.module('authenticationApp', []);

communitApp.controller('authenticationController', ['$scope', '$http', function($scope, $http) {

  $scope.newProfileCounter = 0;
  $scope.invitedCounter = 0;

  $scope.authenticater = function(){
  $scope.authenticated = false;
  $scope.authenticateCheck();
  $scope.authenticated = true;

  $scope.newProfileIndicator();
  $scope.newInviteIndicator();

//Uncomment to repeat the authentication every 5 seconds,
//this will prevent users from continuing on the page after they've
//somehow cleared their stored localStorage.
/*
  setInterval(function() {
    $scope.authenticateCheck();
  }, 5000);
*/

}

$scope.newProfileIndicator = function(){

  $http({
    method : 'POST',
    url    : './models/profile_model.php',
    data   : {
      user: localStorage.getItem("communit_user_id")
    },
    headers: { 'Content-Type': 'application/json' }
  })
  .success(function (data) {
    $scope.newProfiles = data;

    for(var i = 0; i < $scope.newProfiles.length; i++){
      if($scope.newProfiles[i].has_edited == 0){
        $scope.newProfileCounter++;
      }
    }

  });

}

$scope.newInviteIndicator = function(){

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
  .then(function(data, status, headers, config) {
    $scope.invites = data.data;
      for(var i = 0; i < $scope.invites.length; i++){
        if($scope.invites[i].requested_or_invited == 1){
          $scope.invitedCounter++;
        }
      }
  });

}

$scope.logout = function(){
  localStorage.removeItem("communit_user_id");
  localStorage.removeItem("communit_user_token");
  localStorage.removeItem("communit_user_first");
  localStorage.removeItem("communit_user_last");
  sessionStorage.removeItem("communit_user_id");
  sessionStorage.removeItem("communit_user_token");

  window.location.href = 'index.php';
  $scope.successMsg = "Successfully Logged Out";
}

$scope.authenticateCheck = function(){
  if ( !(localStorage.getItem("communit_user_id") === null) && !(localStorage.getItem("communit_user_token") === null) ){
    //alert("UserID: " + localStorage.getItem("communit_user_id") + "  UserToken: " + localStorage.getItem("communit_user_token"));
  			var encodedData = 'userid=' +
  				encodeURIComponent(localStorage.getItem("communit_user_id")) +
  				'&token=' + encodeURIComponent(localStorage.getItem("communit_user_token"));
  		$http({
  			method: 'POST',
  			url: './models/authentication_model.php',
  			data: encodedData,
  			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
  		})
  		.then(function(data, status, headers, config) {
  			//console.log(data);
        $scope.contents = data.data;

        if (!$scope.contents.error && !$scope.contents.inactivity) {
          localStorage.setItem("communit_user_first", $scope.contents.firstname);
          localStorage.setItem("communit_user_last", $scope.contents.lastname);
          $scope.authenticated = true;
          }

  			if ($scope.contents.error) {
          window.location.href = 'index.php';
          $scope.tokenMsg = $scope.contents.error;
  				}
        if ($scope.contents.inactivity) {
          localStorage.removeItem("communit_user_id");
          localStorage.removeItem("communit_user_token");
          sessionStorage.removeItem("communit_user_id");
          sessionStorage.removeItem("communit_user_token");
          window.location.href = 'index.php';
          $scope.tokenMsg = $scope.contents.inactivity;
  			}
  		})

  }else {
    window.location.href = 'index.php';
    $scope.tokenMsg = $scope.contents.error;
    //alert("No userid or token found in storage.");
  }
}

//$scope.authenticateCheck();

}]);
