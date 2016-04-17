communitApp.controller('profileController', function($scope, $http) {

    $scope.user = localStorage.getItem('communit_user_id');
    $scope.userfirstname = localStorage.getItem('communit_user_first');
    $scope.userlastname = localStorage.getItem('communit_user_last');
    $scope.viewSwitch = true;
    $scope.showEditProfile = false;

    //Form Values
    $scope.phone_01 = "";
    $scope.phone_02 = "";
    $scope.email_01 = "";
    $scope.email_02 = "";
    $scope.pin_color = "";

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

    $scope.changePinColor = function() {
      if ($scope.contents[$scope.selectProfile].pin_color != "" && $scope.contents[$scope.selectProfile].pin_color != null) {
        overalayColor($scope.contents[$scope.selectProfile].pin_color);
        document.getElementById('house_pin').src = fullimg;
        document.getElementById('house_pinEdit').src = fullimg;
      }
    }

    $scope.changePinColorEdit = function() {
      overalayColor($scope.pin_color);
      document.getElementById('house_pinEdit').src = fullimg;
    }

  $scope.changeInfo = function() {
    var index = $scope.selectProfile;
    $scope.successProfileMsg = '';
    $scope.phone_01 = $scope.contents[index].phone_01;
    $scope.phone_02 = $scope.contents[index].phone_02;
    $scope.email_01 = $scope.contents[index].email_01;
    $scope.email_02 = $scope.contents[index].email_02;
    $scope.pin_color = $scope.contents[index].pin_color;
    $scope.changePinColor();
  };

    $scope.cancelEdit = function() {
    var index = $scope.selectProfile;
    $scope.successProfileMsg = '';
    $scope.phone_01 = $scope.contents[index].phone_01;
    $scope.phone_02 = $scope.contents[index].phone_02;
    $scope.email_01 = $scope.contents[index].email_01;
    $scope.email_02 = $scope.contents[index].email_02;
    $scope.pin_color = $scope.contents[index].pin_color;
    $scope.showEditProfile = false;
    $scope.changePinColor();
  };

  $scope.getInfo = function () {

    var request = $http({
      method : 'POST',
      url    : './models/profile_model.php',
      data   : {
        user: $scope.user
      },
      headers: { 'Content-Type': 'application/json' }
    });

    request.success(function (data) {

      var index = $scope.selectProfile;
      $scope.contents = data;
      if ($scope.contents.error) {
        $scope.viewSwitch = false;
      } else {
        if ($scope.selectProfile != null){
         $scope.phone_01 = $scope.contents[index].phone_01;
         $scope.phone_02 = $scope.contents[index].phone_02;
         $scope.email_01 = $scope.contents[index].email_01;
         $scope.email_02 = $scope.contents[index].email_02;
         $scope.pin_color = $scope.contents[index].pin_color;
         $scope.changePinColor();
        }
      }

    });
  };

    $scope.update = function () {
        var index = $scope.selectProfile;
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
          $scope.showEditProfile = false;

      });
    };
});
