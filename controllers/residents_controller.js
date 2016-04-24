
communitApp.controller('residentsController', function($scope, $http) {

  $scope.user = localStorage.getItem('communit_user_id');
  $scope.userfirstname = localStorage.getItem('communit_user_first');
  $scope.userlastname = localStorage.getItem('communit_user_last');
  $scope.selectProfile;
  $scope.selectProfile_id;
  $scope.residentsSwitch = false;
  $scope.viewSwitch = true;
  $scope.successMsg = "";
  $scope.errorMsg = "";
  $scope.selectedResidentID;
  $scope.showDetailedResident = false;
  $scope.showEditResident = false;
  $scope.showDetailedProfile = false
  $scope.validPhone01 = true;
  $scope.validPhone02 = true;

  $scope.residentFirstName = "";
  $scope.residentLastName = "";
  $scope.phone_01 = "";
  $scope.phone_02 = "";
  $scope.email_01 = "";
  $scope.email_02 = "";

  $scope.user = localStorage.getItem('communit_user_id');

  $scope.changeInfo = function(selectedProfID) {
    $scope.selectProfile_id = selectedProfID;
    $scope.showDetailedResident = false;
    $scope.showEditResident = false;
    $scope.showInsertResident = false;
    $scope.showDeleteResident = false;
    $scope.showDetailedProfile = false;
    $scope.getResidents();
  };

  $scope.showInsert = function () {
    $scope.showEditResident = false;
    $scope.showDetailedResident = false;
    $scope.showDeleteResident = false;
    $scope.showDetailedProfile = false;
    $scope.showInsertResident = true;
  };

  $scope.showDetailed = function (selectedResID) {
    $scope.selectedResidentID = selectedResID;
    $scope.showEditResident = false;
    $scope.showInsertResident = false;
    $scope.showDeleteResident = false;
    $scope.showDetailedProfile = false;
    $scope.showDetailedResident = true;
  };

    $scope.showProfile = function () {
    $scope.showEditResident = false;
    $scope.showInsertResident = false;
    $scope.showDeleteResident = false;
    $scope.showDetailedResident = false;
    $scope.showDetailedProfile = true;
  };

  $scope.showDelete = function (selectedResID) {
    $scope.selectedResidentID = selectedResID;
    $scope.showEditResident = false;
    $scope.showInsertResident = false;
    $scope.showDetailedResident = false;
    $scope.showDetailedProfile = false;
    $scope.showDeleteResident = true;
  };

  $scope.showEdit = function (selectedResID) {
    $scope.selectedResidentID = selectedResID;
    $scope.showDetailedResident = false;
    $scope.showInsertResident = false;
    $scope.showDeleteResident = false;
    $scope.showDetailedProfile = false;
    $scope.showEditResident = true;

    $scope.residentFirstName = $scope.residents[$scope.selectedResidentID].firstname;
    $scope.residentLastName = $scope.residents[$scope.selectedResidentID].lastname;
    $scope.phone_01 = $scope.residents[$scope.selectedResidentID].phone_01;
    $scope.phone_02 = $scope.residents[$scope.selectedResidentID].phone_02;
    $scope.email_01 = $scope.residents[$scope.selectedResidentID].email_01;
    $scope.email_02 = $scope.residents[$scope.selectedResidentID].email_02;

  };

  $scope.clearEdit = function () {
    $scope.residentFirstName = $scope.residents[$scope.selectedResidentID].firstname;
    $scope.residentLastName = $scope.residents[$scope.selectedResidentID].lastname;
    $scope.phone_01 = $scope.residents[$scope.selectedResidentID].phone_01;
    $scope.phone_02 = $scope.residents[$scope.selectedResidentID].phone_02;
    $scope.email_01 = $scope.residents[$scope.selectedResidentID].email_01;
    $scope.email_02 = $scope.residents[$scope.selectedResidentID].email_02;
  };

  $scope.clearInsert = function () {
    $scope.residentFirstName = '';
    $scope.residentLastName = '';
    $scope.phone_01 = '';
    $scope.phone_02 = '';
    $scope.email_01 = '';
    $scope.email_02 = '';

  };

    $scope.checkPhoneNumber01 = function(){
    if($scope.phone_01 != '' && $scope.phone_01 != null){ 
      var phoneRegex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
      if(phoneRegex.test($scope.phone_01)) {
        $scope.errorPhone01Msg = "";
        $scope.validPhone01 = true;
      }else {  
        $scope.errorPhone01Msg = "Invalid Phone Number";
        $scope.validPhone01 = false;
      }
    }else{
      $scope.validPhone01 = true;
      $scope.errorPhone01Msg = "";
    }
  }

    $scope.checkPhoneNumber02 = function(){
      if($scope.phone_02 != '' && $scope.phone_02 != null){ 
          var phoneRegex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
          if(phoneRegex.test($scope.phone_02)) {
            $scope.errorPhone02Msg = "";
            $scope.validPhone02 = true;
          }else {  
            $scope.errorPhone02Msg = "Invalid Phone Number";
            $scope.validPhone02 = false;
          }
      }else{
        $scope.validPhone02 = true;
        $scope.errorPhone02Msg = "";
      }

  }


  $scope.getInfo = function () {
    var requestProfiles = $http({
      method : 'POST',
      url    : './models/residents_model.php',
      data: {
          user : $scope.user,
          command  : "profiles"
      },
      headers: { 'Content-Type' : 'application/json'}
    });
    requestProfiles.then(function (data, status, headers, config) {

      var index = $scope.selectProfile;
      $scope.profiles = data.data;

      if ($scope.profiles.error) {
          $scope.viewSwitch = false;
      } else {
      }
    });
  };

  $scope.getResidents = function () {

      var requestResidents = $http({
        method : 'POST',
        url    : './models/residents_model.php',
        data: {
            user : $scope.user,
            profile : $scope.selectProfile_id,
            command  : "residents"
        },
        headers: { 'Content-Type' : 'application/json'}
      });

      requestResidents.then(function (data, status, headers, config) {
        //console.log(data);
        $scope.residents = data.data;

        if ($scope.residents.error) {
            $scope.residentsSwitch = false;
        } else {
          $scope.residentsSwitch = true;
        }

      })
    };

    $scope.updateRes = function (resID) {

      $scope.checkPhoneNumber01();
      $scope.checkPhoneNumber02();
      if($scope.validPhone01 == false || $scope.validPhone02 == false ){
         //$scope.errorMsg = "Invalid Phone Number";
      }else{

          var updateResident = $http({
           method : 'POST',
           url    : './models/residents_model.php',
           data: {
               user : $scope.user,
               profile : $scope.selectProfile_id,
               resident : resID,
               firstname : $scope.residentFirstName,
               lastname : $scope.residentLastName,
               phone_01 : $scope.phone_01,
               phone_02 : $scope.phone_02,
               email_01 : $scope.email_01,
               email_02 : $scope.email_02,
               command  : "update"
           },
           headers: { 'Content-Type' : 'application/json'}

        });

        updateResident.success(function (data, status, headers, config) {

          $scope.updateObj = data;
          if ($scope.updateObj.error) {
            $scope.successMsg = '';
            $scope.errorMsg = "Error: please refresh and try again.";

          } else {
            $scope.errorMsg = '';
            $scope.successMsg = "Resident updated successfully.";
            $scope.getResidents();
          }
        });
      }
    };

    $scope.insertRes = function () {
      $scope.checkPhoneNumber01();
      $scope.checkPhoneNumber02();
      if($scope.validPhone01 == false || $scope.validPhone02 == false ){
         //$scope.errorMsg = "Invalid Phone Number";
      }else{
        $scope.errorMsg = "";
        var insertResident = $http({
           method : 'POST',
           url    : './models/residents_model.php',
           data: {
               user : $scope.user,
               profile : $scope.selectProfile_id,
               firstname : $scope.residentFirstName,
               lastname : $scope.residentLastName,
               phone_01 : $scope.phone_01,
               phone_02 : $scope.phone_02,
               email_01 : $scope.email_01,
               email_02 : $scope.email_02,
               command  : "insert"
           },
           headers: { 'Content-Type' : 'application/json'}
        });

        insertResident.then(function (data, status, headers, config) {
          $scope.insertObj = data.data;
          if ($scope.insertObj.error) {
            $scope.successMsg = '';
            //$scope.errorMsg = "Error: please refresh and try again.";
            $scope.errorMsg = $scope.insertObj.error;
          } else {
            $scope.errorMsg = '';
            $scope.successMsg = "Resident added successfully.";
            $scope.clearInsert();
            $scope.getResidents();
          }
        });
      }
    };

    $scope.deleteRes = function (resID) {

        var deleteResident = $http({
         method : 'POST',
         url    : './models/residents_model.php',
         data: {
             user : $scope.user,
             profile : $scope.selectProfile_id,
             resident : resID,
             command  : "delete"
         },
         headers: { 'Content-Type' : 'application/json'}
      });

      deleteResident.then(function (data, status, headers, config) {

        $scope.insertObj = data;

        if ($scope.insertObj.error) {
          $scope.successMsg = '';
          $scope.deleteMsg = true;
          $scope.errorMsg = "Error: please refresh and try again.";
        } else {
          $scope.showDeleteResident = false;
          $scope.errorMsg = '';
          $scope.deleteMsg = true;
          $scope.successMsg = "Resident removed successfully.";
          $scope.getResidents();
        }
      });
    };

});
