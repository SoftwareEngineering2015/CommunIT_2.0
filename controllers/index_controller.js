var indexApp = angular.module('indexApp', []);

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

/*
}]);

indexApp.controller('createaccountController', ['$scope', '$http', function($scope, $http) {
*/
	$(function() {
			$( "#inputBirthDate" ).datepicker({
					//altFormat: "MM dd, yy",
					//altField: "#inputBirthDate",
					dateFormat: "yy-mm-dd",
					changeMonth: true,
					changeYear: true,
					yearRange: "-200:+0", // last two hundred years
					maxDate: new Date(),
					showAnim: "slideDown" });
	});

	$scope.checkPassword = function() {
			if($scope.inputData.password != $scope.inputData.passwordConfirm){
				$scope.errorMsgPassword = "Passwords do not match.";
				$scope.vaildPassword = false;
			}
			if($scope.inputData.password === $scope.inputData.passwordConfirm){
				$scope.errorMsgPassword = '';
				$scope.vaildPassword = true;
			}
		}

  $scope.checkUsername = function(){
      if (!(/^[a-zA-Z0-9]*$/.test($scope.inputData.username))) {
          //alert("Please use only Alphanumeric characters ")
          $scope.errorMsgUsername = "Only Alphanumeric characters allowed.";
          $scope.vaildUsername = false;
      }else{
        $scope.errorMsgUsername = '';
        $scope.vaildUsername = true;
      }
  }

     $scope.clearForm = function() {
      $scope.inputData.username = '';
      $scope.inputData.email = '';
      $scope.inputData.firstName = '';
      $scope.inputData.middleInitial = '';
      $scope.inputData.lastName = '';
      $scope.inputData.password = '';
      $scope.inputData.passwordConfirm = '';
     }

    $scope.postCreateAccount = function() {
    $scope.errorMsgBirthDate = "";
    $scope.errorMsgPassword = "";
    $scope.errorMsgEmail = "";
    //$scope.inputData.middleInitial = $scope.inputData.middleInitial.toUpperCase();
    if (/\s/.test($scope.inputData.username)){ //|| (!(/[a-zA-Z0-9]/.test($scope.inputData.username)))){
    $scope.errorMsgUsername = "There cannot be spaces in the username.";
    } else if ($("#inputBirthDate").val() === "") {
    $scope.errorMsgBirthDate = "Birth date is required.";
    } else if($scope.vaildPassword == false){
    $scope.errorMsgPassword = "Passwords do not match";
    }else if($scope.vaildUsername == false){
    $scope.errorMsgUsername = "Username not valid.";
    } else{
    var encodedData = 'inputUsername=' +
    encodeURIComponent($scope.inputData.username) +
    '&inputEmail=' +
    encodeURIComponent($scope.inputData.email) +
    '&inputFirstName=' +
    encodeURIComponent($scope.inputData.firstName) +
    '&inputMiddleInitial=' +
    encodeURIComponent($scope.inputData.middleInitial) +
    '&inputLastName=' +
    encodeURIComponent($scope.inputData.lastName) +
    '&inputGender=' +
    encodeURIComponent($scope.inputData.gender) +
    '&inputBirthDate=' +
    encodeURIComponent($("#inputBirthDate").val()) +
    '&inputPassword=' +
    encodeURIComponent($scope.inputData.password);
    $http({
    method: 'POST',
    url: './models/createaccount_model.php',
    data: encodedData,
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    })
    .success(function(data, status, headers, config) {
    console.log(data);
    $scope.errorMsgUsername = "";
    $scope.errorMsgEmail = "";
    if ( data.trim() === 'usernameExists') {
    $scope.errorMsgUsername = "That Username Already Exists";
    }
    if ( data.trim() === 'emailExists') {
    $scope.errorMsgEmail = "That Email Already Exists";
    }
    if ( data.trim() === 'success') {
    //window.location.href = 'index.php';
    $scope.clearForm();
    $scope.formSwitch = false;
    $scope.errorMsgForm = '';
    $scope.successMsg = 'Account Created Successfully, Please Log In.';
    }
    })
    .error(function(data, status, headers, config) {
    $scope.errorMsgForm = 'Unable to submit form';
    })
    }





}
}]);
