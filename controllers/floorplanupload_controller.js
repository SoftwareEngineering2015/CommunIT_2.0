communitApp.controller('floorplanuploadController', ['$scope', '$http', 'Upload', function($scope, $http, Upload) {

  //Check to see if user is already logged in

$scope.postFloorPlan = function(file){

      $scope.message = "Clicked.";

       if (!file) {
         $scope.message = "No File.";
         return;
       }

       Upload.upload({
           url: './models/floorplanupload_model.php',
           data: {file: file, floor: $scope.floor, marker_id: $scope.marker_id}
         }).then(function(resp) {
           // file is uploaded successfully
           $scope.message = "Done.";
           console.log(resp.data);
         });
     };
}]);



/*
//if ( !(localStorage.getItem("communit_user_id") === null) && !(localStorage.getItem("communit_user_token") === null) ){
//  $scope.onFileSelect = function(file) {
    //alert("UserID: " + localStorage.getItem("communit_user_id") + "  UserToken: " + localStorage.getItem("communit_user_token"));
  			var encodedData = fileToUpload=encodeURIComponent(fileToUpload)+'floor=' +
  				encodeURIComponent(localStorage.getItem("communit_user_id")) +
  				'&markerid=' +
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
  		});

  }else {

    //alert("No userid or token found in storage.");
  }
}
*/
//}]);
