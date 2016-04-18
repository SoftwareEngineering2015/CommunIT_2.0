communitApp.controller('editProfileController', function($scope, $http) {

  //Here is the information that is assumed.
  //To test if a user doesn't have a profile just change the $scope.user to something silly.
  $scope.user = localStorage.getItem('communit_user_id');
  $scope.userfirstname = localStorage.getItem('communit_user_first');
  $scope.userlastname = localStorage.getItem('communit_user_last');
  $scope.selectProfile;
  $scope.passConfirm = "";
  $scope.emailConfirm = "";
  $scope.viewSwitch = true;
  $scope.vaildPassword = false;
  $scope.vaildPrimaryEmail = false;

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
        //alert("PLACEHOLDER ALERT BOX\n" + "Congratulations, you are now part of " + $scope.contents[i].community_name + " at " + $scope.contents[i].location + ".\nPlease take some time to fill out your profile for " + $scope.contents[i].community_name +".");
        alert("PLACEHOLDER ALERT BOX\n" + "Congratulations, you have joined community " + $scope.contents[i].community_name + ".\nPlease take some time to fill out your profile for " + $scope.contents[i].community_name +".");
        $scope.selectProfile = ""+i+"";
        //window.location.href = 'editprofile.php';
      }
    }

  });

  //This is what the cancel and select boxes use to change the default values
  $scope.changeInfo = function() {

    var index = $scope.selectProfile;
    $scope.successProfileMsg = '';
    $scope.phone_01 = $scope.contents[index].phone_01;
    $scope.phone_02 = $scope.contents[index].phone_02;
    $scope.email_01 = $scope.contents[index].email_01;
    $scope.email_02 = $scope.contents[index].email_02;
    $scope.pin_color = $scope.contents[index].pin_color;
    $scope.getAccount();
    //$scope.primaryEmail = $scope.contents[index].email;
    //$scope.password = $scope.contents[index].password;

    if ($scope.pin_color != "" && $scope.pin_color != null) {
      overalayColor($scope.pin_color);
      document.getElementById('house_pin').src = fullimg;
    }

  };

  $scope.changePinColor = function() {
    overalayColor($scope.pin_color);
    document.getElementById('house_pin').src = fullimg;
  }


  $scope.checkPassword = function() {
      $scope.successAccountMsg = '';
      if($scope.password != $scope.passConfirm){
        $scope.errorAccountPasswordMsg = "Passwords do not match.";
        $scope.vaildPassword = false;
      }
      if($scope.password === $scope.passConfirm){
        $scope.errorAccountPasswordMsg = '';
        $scope.vaildPassword = true;
      }
    }

    $scope.checkPrimaryEmail = function() {
        $scope.successAccountMsg = '';
        if($scope.primaryEmail != $scope.emailConfirm){
          $scope.errorAccountEmailMsg = "E-mails do not match.";
          $scope.vaildPrimaryEmail = false;
        }
        if($scope.primaryEmail === $scope.emailConfirm){
          $scope.errorAccountEmailMsg = '';
          $scope.vaildPrimaryEmail = true;
        }
      }

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
         $scope.getAccount();
        }
        }

    });
  };

  $scope.getAccount = function () {
      var request = $http({
        method : 'POST',
        url    : './models/account_model.php',
        data   : {
          user: $scope.user
        },
        headers: { 'Content-Type': 'application/json' }
      });

      //If it worked, then set the results equal to $scope.contents.
      request.success(function (data) {
        //console.log(data);
        $scope.account = data;

        //If there was an error do this
        if ($scope.account.error) {
            $scope.viewSwitch = false;
        //If everything is fine do this.
        } else {
        //  if ($scope.selectProfile != null){
           $scope.primaryEmail = $scope.account.email;
           //$scope.password = $scope.account.password;
        //  }
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
             user_id   : $scope.user
         },
         headers: { 'Content-Type' : 'application/json'}

      });

      updateInfo.success(function (data) {

          $scope.successProfileMsg = "Profile updated successfully."
          $scope.getInfo();

      });

    };

    //Passes account info, to update their account
    $scope.updateAccount = function () {
      if ($scope.vaildPassword === true && $scope.vaildPrimaryEmail === false){
          var encodedData = 'user=' +
            encodeURIComponent(localStorage.getItem("communit_user_id")) +
            '&password=' +
            encodeURIComponent($scope.password);

        $http({
          method: 'POST',
          url: './models/update_account_model.php',
          data: encodedData,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .success(function(data, status, headers, config) {
        //  console.log(data);
          $scope.accountContents = data;

          if (!$scope.accountContents.error) {
              $scope.passConfirm = '';
              $scope.password = '';
              $scope.emailConfirm = '';
              $scope.vaildPassword = false;
              $scope.vaildPrimaryEmail = false;
              $scope.getAccount();
              $scope.successAccountMsg = "Account password updated successfully!";
            } else {
              console.log($scope.accountContents.error);

            }

        });

    } else if ($scope.vaildPrimaryEmail === true && $scope.vaildPassword === false) {
      var encodedData = 'user=' +
        encodeURIComponent(localStorage.getItem("communit_user_id")) +
        '&email=' +
        encodeURIComponent($scope.primaryEmail);

    $http({
      method: 'POST',
      url: './models/update_account_model.php',
      data: encodedData,
      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    })
    .success(function(data, status, headers, config) {
    //  console.log(data);
      $scope.accountContents = data;

      if (!$scope.accountContents.error) {
        $scope.passConfirm = '';
        $scope.emailConfirm = '';
        $scope.vaildPassword = false;
        $scope.vaildPrimaryEmail = false;
        $scope.getAccount();
        $scope.successAccountMsg = "Account email updated successfully!";
        } else {
          //console.log($scope.accountContents.error);

        }
    });

  } else if ($scope.vaildPrimaryEmail === true && $scope.vaildPassword === true) {
        var encodedData = 'user=' +
          encodeURIComponent(localStorage.getItem("communit_user_id")) +
          '&password=' +
          encodeURIComponent($scope.password) +
          '&email=' +
          encodeURIComponent($scope.primaryEmail);

      $http({
        method: 'POST',
        url: './models/update_account_model.php',
        data: encodedData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      })
      .success(function(data, status, headers, config) {
      //  console.log(data);
        $scope.accountContents = data;

        if (!$scope.accountContents.error) {
          $scope.passConfirm = '';
          $scope.emailConfirm = '';
          $scope.vaildPassword = false;
          $scope.vaildPrimaryEmail = false;
          $scope.getAccount();
            $scope.successAccountMsg = "Account password and e-mail updated successfully!";
          } else {
          //console.log($scope.accountContents.error);

          }
      });

    }   else {
        //$scope.errorAccountMsg = "Nothing Changed."
    }

    };
});
