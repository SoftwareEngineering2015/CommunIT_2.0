indexApp.controller('loginController', ['$scope', '$http', function($scope, $http) {

  //Check to see if user is already logged in

$scope.checkLogin = function(){
  if ( !(localStorage.getItem("communit_user_id") === null) && !(localStorage.getItem("communit_user_token") === null) ){
    //alert("UserID: " + localStorage.getItem("communit_user_id") + "  UserToken: " + localStorage.getItem("communit_user_token"));
  			var encodedData = 'userid=' +
  				encodeURIComponent(localStorage.getItem("communit_user_id")) +
  				'&token=' +
  				encodeURIComponent(localStorage.getItem("communit_user_token"));

  		$http({
  			method: 'POST',
  			url: './models/authentication_model.php',
  			data: encodedData,
  			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
  		})
  		.success(function(data, status, headers, config) {
  			console.log(data);
        $scope.contents = data;

  			if (!$scope.contents.error) {
  					window.location.href = 'myhome.php';
  				} else {
  					$scope.tokenMsg = $scope.contents.error;
            localStorage.removeItem("communit_user_id");
            localStorage.removeItem("communit_user_token");

  				}
  		})

  }else {
    //alert("No userid or token found in storage.");
  }
}

	$scope.postLogin = function() {
    $scope.errorMsg = '';
			var encodedData = 'username=' +
				encodeURIComponent($scope.inputDataLogin.username) +
				'&password=' +
				encodeURIComponent($scope.inputDataLogin.password);

		$http({
			method: 'POST',
			url: './models/login_model.php',
			data: encodedData,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success(function(data, status, headers, config) {
			//console.log(data);
      $scope.contents = data;

			if (!$scope.contents.error) {
          var useridSession = $scope.contents.user_id;
          $scope.loginstorage(useridSession);
				} else {
					$scope.errorMsg = $scope.contents.error;
				}
		})
		.error(function(data, status, headers, config) {
			console.log('Unable to submit form.');
			$scope.errorMsg = 'Unable to submit form';
		})
	}

$scope.loginstorage = function(userid) {
    var encodedData = 'userid=' + encodeURIComponent(userid);

  $http({
    method: 'POST',
    url: './models/loginstorage_model.php',
    data: encodedData,
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
  })
  .then(function(data, status, headers, config) {
    console.log(data);

      $scope.contents = data.data;
      //console.log($scope.contents.user_id , $scope.contents.user_token);
      $scope.storage($scope.contents.user_id , $scope.contents.user_token);

  })
}

$scope.storage = function(user_id, user_token) {
  //Clear Storages
  localStorage.removeItem("communit_user_id");
  localStorage.removeItem("communit_user_token");
  //sessionStorage.removeItem("communit_user_id");
  //sessionStorage.removeItem("communit_user_token");
  //Set Storages
  localStorage.setItem("communit_user_id", user_id);
  localStorage.setItem("communit_user_token", user_token);
  //sessionStorage.setItem("communit_user_id", user_id);
  //sessionStorage.setItem("communit_user_token", user_token);

  window.location.href = 'myhome.php';
  //window.alert(localStorage.getItem("communit_user_id"));
}


}]);
