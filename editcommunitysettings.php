
      <?php
         require_once( "template_class.php");       // css and headers
         $H = new template( "Edit Community Settings" );
         $H->show_template( );

         // Get the community id from the url for which community map to display
         if(isset($_GET["community"])) {
            $community = $_GET["community"];
         } else {
            header("location: myhome.php");
            exit;
         }

      ?>

   <style>
      html, body {
      height: 100%;
      width: 100%;
      }
      #informationField::-webkit-scrollbar-track {
           -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
           border-radius: 10px;
           background-color: rgba(0, 0, 0, .5);
       }

       #informationField::-webkit-scrollbar {
           width: 12px;
           background-color: rgba(0, 0, 0, .5);
       }

       #informationField::-webkit-scrollbar-thumb {
           border-radius: 10px;
           -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
           background-color: #1995dc;
       }
   </style>

   <!-- Google API KEY for accessing a broader spectrum of Google APIs-->
   <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
   <script src='js/colorpins.js'></script>
   <script src='controllers/editcommunitysettings_controller.js'></script>

   <body>
      <div style="width:100%; height:91%; " id="pageDiv">
        <div style="height: 100%; ">
          <div class="col-md-4"  style="background-color: #ffffff; max-height: 100%;">
            <div class="col-xs-12">
                <button type="button" class="btn btn-primary btn-sm" style="width:100%;" id="goToCommunity">Go To Community</button>
             </div>
             <div class="col-lg-6">
                <button type="button" class="btn btn-success btn-sm" style="width:100%;" id="editCommunitySettingsButton">Edit Community Settings</button>
             </div>
             <div class="col-lg-6">
                <button type="button" class="btn btn-info btn-sm" style="width:100%;" id="listResidentsButton">List All Residents</button>
             </div>
             <div class="col-lg-6">
                <button type="button" class="btn btn-success btn-sm" style="width:100%;" id="addMarkersButton">Add Markers</button>
             </div>
             <div class="col-lg-6">
                <button type="button" class="btn btn-info btn-sm" style="width:100%;" id="residentRequestsInviteButton">Resident Requests / Invite</button>
             </div>
             &nbsp
             <div id="informationField" style="height: 81%;  overflow:auto;">
             </div>
          </div>
          <!--Google Map Div-->
         <div class="col-md-8" id="googleMap" style="height:100%;" ></div>
        </div>
      </div>
   </body>

   <!-- Modal -->
<div id="deleteMarkerModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content -->
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Delete Marker</h3>
   </div>
   <div class="modal-body">
      <b> Are you sure you want to delete this marker? <br />
        <span style="color: red"> This action will permanently delete this marker and all information tied to it. This cannot be undone! </span> <br /><br />
        <span style="color: red" id="deleteMarkerModalMessage"> </span> </b>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="sumbitDeleteButton" name="sumbitDeleteButton">Delete Marker</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
</div>
</div>
</html>
