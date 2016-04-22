communitApp.controller('profileController', function($scope, $http) {

    $scope.user = localStorage.getItem('communit_user_id');
    $scope.userfirstname = localStorage.getItem('communit_user_first');
    $scope.userlastname = localStorage.getItem('communit_user_last');
    $scope.viewSwitch = true;
    $scope.showEditProfile = false;
    $scope.newProfile = false;

    $scope.validPhone01 = true;
    $scope.validPhone02 = true;

    //Form Values
    $scope.phone_01 = "";
    $scope.phone_02 = "";
    $scope.email_01 = "";
    $scope.email_02 = "";
    $scope.miscinfo = "";
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
        $scope.newProfileMsg= "Congratulations, you have joined the community \"" + $scope.contents[i].community_name + "\".";
        $scope.newProfile = true;
        $scope.selectProfile = ""+i+"";
        $scope.viewSwitch = false;
        break;
        //window.location.href = 'editprofile.php';
      }
    }

  });


  $scope.checkPhoneNumber01 = function(){
    if($scope.phone_01 != ''){ 
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
      if($scope.phone_02 != ''){ 
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
    $scope.miscinfo = $scope.contents[index].miscinfo;
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
    $scope.miscinfo = $scope.contents[index].miscinfo;
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
         $scope.miscinfo = $scope.contents[index].miscinfo;
         $scope.pin_color = $scope.contents[index].pin_color;
         $scope.changePinColor();
        }
      }

    });
  };

    $scope.update = function () {
      $scope.checkPhoneNumber01();
      $scope.checkPhoneNumber02();
      if($scope.validPhone01 == false || $scope.validPhone02 == false ){
         //$scope.errorProfileMsg = "Invalid Phone Number";
      }else{
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
             miscinfo : $scope.miscinfo,
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
      }
    };
});
