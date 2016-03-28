//var authenticationApp = angular.module('authenticationApp', []);

communitApp.controller('authenticationController', ['$scope', '$http', function($scope, $http) {

$scope.authenticater = function(){
  $scope.authenticated = false;
  $scope.authenticateCheck();
//Uncomment to repeat the authentication every 5 seconds,
//this will prevent users from continuing on the page after they've
//somehow cleared their stored localStorage.
/*
  setInterval(function() {
    $scope.authenticateCheck();
  }, 5000);
*/

  $scope.authenticated = true;


}

$scope.logout = function(){
  localStorage.removeItem("communit_user_id");
  localStorage.removeItem("communit_user_token");
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
