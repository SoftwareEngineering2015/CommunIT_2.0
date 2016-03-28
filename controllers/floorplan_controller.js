communitApp.controller('floorplanController', ['$scope', '$http', function($scope, $http) {

      $scope.markers = data;

      $scope.loadInfoWindow = function(id){
      var dialogObjId = "#dialog_" + id;
      var markerObjId = "#marker_" + id;

      $(dialogObjId).dialog({
        height: 140,
        open: function(event, ui) {
          $('.ui-dialog').css('z-index', 10003);
        },
      }).html("<h4>Room: 01</h4><ul><li>Joey Calzone</li><li>Penni Pasta</li></ui>");
    }

    /*
       getMarkers = function() {
      		$http.get({

      			method: 'GET',
      			url: 'modelFloorplan.php',
      			headers: {'Content-Type': 'application/json'}
      		})
      		.success(function(data, status, headers, config) {
      			console.log(data);
      					$scope.markers = response.data.records;
      		})
      		.error(function(data, status, headers, config) {
      			console.log('Unable to submit form.');
      		})
      	}
    */
/*
//    function point_it(event) {
$scope.point_it = function(event){
      //pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("floorplan").offsetLeft;
      //pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("floorplan").offsetTop;
      $(document).mousemove(function(getCurrentPos) {
        var xCord = getCurrentPos.clientX;
        var yCord = getCurrentPos.clientY;
        //var xCord = event.pageX-document.getElementById("floorplan").offsetLeft
        //var yCord = event.pageY-document.getElementById("floorplan").offsetTop;
        var xPercent = xCord / window.innerWidth * 100;
        var yPercent = yCord / window.innerHeight * 100;
        vm.coords.form_y = xPercent;
        vm.coords.form_x = yPercent;
      });
    }

*/
    //function loadInfoWindow(id) {

/*

    function createMarkers() {
      var marker = [


        {
          latitude: 55.0884146341,
          longitude: 20.7356828193
        }, {
          latitude: 19.7896341463,
          longitude: 20.7356828193
        }, {
          latitude: 9.1189024390,
          longitude: 20.7356828193
        }, {
          latitude: 12.9298780487,
          longitude: 58.5726872246
        }, {
          latitude: 39.2951541850,
          longitude: 20.7356828193
        }, {
          latitude: 12.9298780487,
          longitude: 75.5518292682
        }, {
          latitude: 29.5154185022,
          longitude: 20.7356828193
        }
      ];
}

      var markersDisplay = "";
      var markersid = "";
      for (var i = 0; i < marker.length; i++) {

        //window.alert("Lat:" + marker[i].latitude + " Lng:" + marker[i].longitude );
        markerid = "marker_" + i;
        buttonid = "button_" + i;
        dialogid = "dialog_" + i;
        markersDisplay +=
          "<div id='" + dialogid + "' title='Room 0" + i + "' style='display: hidden;'></div>" +

          "<img src='images/house_pin02.png' id='" + markerid + "' style='display: block; position: absolute; left:" + marker[i].latitude + "%; top:" + marker[i].longitude + "%;' onclick='loadInfoWindow(" + i + ");'/>";

      }
      markersDisplay += "<img src='images/floorplans/AAAAAAAAAAAA.gif' id='floorplan' onmouseover='point_it(event)' style='height: 100%; width: 100%;'/>";
      document.getElementById("pointer_div").innerHTML = markersDisplay;
      //window.alert(markersDisplay);
    }
    */



  }]);
