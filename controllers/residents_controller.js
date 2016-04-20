
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
    $scope.getResidents();
  };

  $scope.showInsert = function () {
    $scope.showEditResident = false;
    $scope.showDetailedResident = false;
    $scope.showDeleteResident = false;
    $scope.showInsertResident = true;
  };

  $scope.showDetailed = function (selectedResID) {
    $scope.selectedResidentID = selectedResID;
    $scope.showEditResident = false;
    $scope.showInsertResident = false;
    $scope.showDeleteResident = false;
    $scope.showDetailedResident = true;
  };

  $scope.showDelete = function (selectedResID) {
    $scope.selectedResidentID = selectedResID;
    $scope.showEditResident = false;
    $scope.showInsertResident = false;
    $scope.showDetailedResident = false;
    $scope.showDeleteResident = true;
  };

  $scope.showEdit = function (selectedResID) {
    $scope.selectedResidentID = selectedResID;
    $scope.showDetailedResident = false;
    $scope.showInsertResident = false;
    $scope.showDeleteResident = false;
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
    };

    $scope.insertRes = function () {

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
