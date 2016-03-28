
communitApp.controller('editProfileController', function($scope, $http) {

  //Here is the information that is assumed.
  //To test if a user doesn't have a profile just change the $scope.user to something silly.
  $scope.user = localStorage.getItem('communit_user_id');
  $scope.selectProfile = "0";
  $scope.passConfirm = "";
  $scope.emailConfirm = "";
  $scope.viewSwitch = true;

  //Here are the ./models for the forms.
  $scope.phone_01 = "";
  $scope.phone_02 = "";
  $scope.email_01 = "";
  $scope.email_02 = "";
  $scope.pin_color = "";
  $scope.password = "";
  $scope.primaryEmail = "";

  $scope.user = localStorage.getItem('communit_user_id');

  $http({
    method : 'POST',
    url    : './models/profile_model.php',
    data   : {
      user: $scope.user
    },
    headers: { 'Content-Type': 'application/json' }
  })
  .success(function (data) {
    //alert("sent the post");
    $scope.contents = data;

    for(var i = 0; i < $scope.contents.length; i++){
      if($scope.contents[i].has_edited == 0){
        alert("PLACEHOLDER ALERT BOX\n" + "Congratulations, you are now part of " + $scope.contents[i].community_name + " at " + $scope.contents[i].location + ".\nPlease take some time to fill out your profile for " + $scope.contents[i].community_name +".");
        $scope.selectProfile = ""+i+"";
        //window.location.href = 'editprofile.php';
      }
    }

  });

  //This is what the cancel and select boxes use to change the default values
  $scope.changeInfo = function() {

    var index = $scope.selectProfile;
    $scope.phone_01 = $scope.contents[index].phone_01;
    $scope.phone_02 = $scope.contents[index].phone_02;
    $scope.email_01 = $scope.contents[index].email_01;
    $scope.email_02 = $scope.contents[index].email_02;
    $scope.pin_color = $scope.contents[index].pin_color;
    $scope.primaryEmail = $scope.contents[index].email;
    $scope.password = $scope.contents[index].password;
  };

  //Here we grab the json object from profileModel.php.
  $scope.getInfo = function () {

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

      var index = $scope.selectProfile;
      //console.log(data);
      $scope.contents = data;

      //If there was an error do this
      if ($scope.contents.error) {

          $scope.viewSwitch = false;

      //If everything is fine do this.
      } else {
        if ($scope.selectProfile != null){
         $scope.phone_01 = $scope.contents[index].phone_01;
         $scope.phone_02 = $scope.contents[index].phone_02;
         $scope.email_01 = $scope.contents[index].email_01;
         $scope.email_02 = $scope.contents[index].email_02;
         $scope.pin_color = $scope.contents[index].pin_color;
         $scope.primaryEmail = $scope.contents[index].email;
         $scope.password = $scope.contents[index].password;
        }
        }

    });

  };

    //Do this function if on click of the submit value for the profile form.
    $scope.update = function () {
        var index = $scope.selectProfile;
        //alert(index);
        var updateInfo = $http({
         method : 'POST',
         url    : './models/editProfile_model.php',
         data   : {
             marker_id : $scope.contents[index].marker_id,
             profile_id : $scope.contents[index].profile_id,
             phone_01 : $scope.phone_01,
             phone_02 : $scope.phone_02,
             email_01 : $scope.email_01,
             email_02 : $scope.email_02,
             pin_color : $scope.pin_color,
             password  : $scope.password,
             email     : $scope.primaryEmail,
             user_id   : $scope.user
         },
         headers: { 'Content-Type' : 'applocation/json'}

      });

      updateInfo.success(function (data) {
          //alert("Sent the information to the model");
          //alert(data);
          $scope.getInfo();

      });

    };

    //Here we get the account info and pass it to the model.
    $scope.updateAccount = function () {
      //alert("Made it in the function");
      //If their passwords match but the emails don't do this.
      if ($scope.password === $scope.passConfirm & $scope.primaryEmail != $scope.emailConfirm) {
          //alert("Made it to check one");
          var requestPassUpdate = $http({
              method : 'POST',
              url    : './models/editProfile_model.php',
              data   : {
                   marker_id : "",
                   profile_id : "",
                   phone_01 : "",
                   phone_02 : "",
                   email_01 : "",
                   email_02 : "",
                   pin_color : "",
                   password  : "",
                   email     : "",
                   user_id   : $scope.user
              },
              headers : { 'Content-Type' : 'application/json'}
          });
          requestPassUpdate.success(function (data) {
              //alert("Check 1 Success");

          });

      //If their emails match but not thier passwords do this.
      } else if ($scope.primaryEmail === $scope.emailConfirm & $scope.password != $scope.passConfirm) {
          //alert("Made it to Check 2");
          var requestEmailUpdate = $http({
              method : 'POST',
              url    : './models/editProfile_model.php',
              data   : {
                   marker_id : "",
                   profile_id : "",
                   phone_01 : "",
                   phone_02 : "",
                   email_01 : "",
                   email_02 : "",
                   pin_color : "",
                   password  : "",
                   email     : $scope.primaryEmail,
                   user_id   : $scope.user
              },
              headers : {'Content-Type' : 'application/json'}
          });
          requestEmailUpdate.success(function (data) {
              //alert("Check 2 Success");
          });
    //If their emails and passwords both match do this.
    } else if ($scope.primaryEmail === $scope.emailConfirm & $scope.password === $scope.passConfirm) {
        //alert("Made it to Check 3");
        var requestBothUpdate = $http({
           method : 'POST',
           url    : './models/editProfile_model.php',
           data   : {
               marker_id : "",
               profile_id : "",
               phone_01 : "",
               phone_02 : "",
               email_01 : "",
               email_02 : "",
               pin_color : "",
               password  : $scope.password,
               email     : $scope.primaryEmail,
               user_id   : $scope.user_id
           },
           headers : {'Content-Type' : 'application/json'}
        });
        requestBothUpdate.success(function (data) {
           //alert("Check 3 Success");
        });
    //Anything else, spit out an error message. You can change it to be whatever you want.
    }   else {
        alert("Something doesn't quite match. Please try again");
    }

    };
});
