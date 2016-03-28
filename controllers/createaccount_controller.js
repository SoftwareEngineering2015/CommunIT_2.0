
indexApp.controller('createaccountController', ['$scope', '$http', function($scope, $http) {

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

	$scope.postCreateAccount = function() {
		$scope.errorMsgBirthDate = "";
		$scope.errorMsgPassword = "";
		$scope.errorMsgEmail = "";

		if ($("#inputBirthDate").val() === "") {
			$scope.errorMsgBirthDate = "Birth date is required.";
		} else if($scope.vaildPassword == false){
			$scope.errorMsgPassword = "Passwords do not match";
		}else{

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
          $scope.formSwitch = false;
          $scope.successMsg = 'Account Created Successfully, Please Log In.';
				}

		})
		.error(function(data, status, headers, config) {
			$scope.errorMsgForm = 'Unable to submit form';
		})
	}

}
}]);
