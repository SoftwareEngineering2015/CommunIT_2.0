<?php

// Get the community id from the post for which community map to display
if(isset($_REQUEST["marker"])) {
  $marker_id = $_REQUEST["marker"];
} else {
  echo "noMarker";
  exit;
}

include("db_class.php");

$sql_floorplans_in_marker = "SELECT * FROM floorplans_to_markers INNER JOIN floor_plans ON floorplans_to_markers.floorplan_id = floor_plans.floorplan_id WHERE floorplans_to_markers.marker_id = '$marker_id' ORDER BY floor";
$sql_floorplans_in_marker_result = mysqli_query($conn, $sql_floorplans_in_marker);

if (mysqli_num_rows($sql_floorplans_in_marker_result) == 0 ) {
  echo "<h3> Upload Floorplans </h3>
        <hr style='border: none; width: 1px;'>
        <form id='floorplanUploadForm' action='' method='post' enctype='multipart/form-data'>
        <table class='table table-striped table-hover table-condensed'>
          <tr>
            <th> Upload Floor Plan </th>
            <td> <input type='file' class='form-control input-sm' name='fileToUpload' id='fileToUpload'> </td>
          </tr>
          <tr>
            <th> Floor Name </th>
            <td> <input type='text' class='form-control input-sm' name='floor' id='floor'> </td>
          </tr>
          <tr>
            <td></td>
            <td> <button type='submit' id='uploadFloorPlan' name='submit' class='btn btn-primary btn-sm' value='" . $marker_id . "' style='width: 100%;'>Upload Floorplan</button> </td> 
          </tr>
          <tr>
            <th></th>
            <td id='floorplanUploadMessage'></td>
          </tr>
        </table>
        </form>
        <br />
        <h3> Floorplans </h3>
        <hr style='border: none; width: 1px;'>
        <table class='table table-striped table-hover table-condensed' id='floorplansTable'>
        <tbody>
        </tbody>
        </table>
        <b id='no_floorplans_message'> There are currently no floorplans for this marker. </b>";

} else { 

  echo "<h3> Upload Floorplans </h3>
        <hr style='border: none; width: 1px;'>
        <form id='floorplanUploadForm' action='' method='post' enctype='multipart/form-data'>
        <table class='table table-striped table-hover table-condensed'>
          <tr>
            <th> Upload Floor Plan </th>
            <td> <input type='file' class='form-control input-sm' name='fileToUpload' id='fileToUpload'> </td>
          </tr>
          <tr>
            <th> Floor Name </th>
            <td> <input type='text' class='form-control input-sm' name='floor' id='floor'> </td>
          </tr>
          <tr>
            <td></td>
            <td> <button type='submit' id='uploadFloorPlan' name='submit' class='btn btn-primary btn-sm' value='" . $marker_id . "' style='width: 100%;'>Upload Floorplan</button> </td> 
          </tr>
          <tr>
            <th></th>
            <td id='floorplanUploadMessage'></td>
          </tr>
        </table>
        </form>
        <br />
        <h3> Floorplans </h3>
        <hr style='border: none; width: 1px;'>
        <table class='table table-striped table-hover table-condensed' id='floorplansTable'>";

  $sql_floorplans_in_marker_result = mysqli_query($conn, $sql_floorplans_in_marker);
  while($row = $sql_floorplans_in_marker_result->fetch_assoc()){

    echo "<tr> <td> " . $row['floor'] . " </td> <td> <button type='button' class='btn btn-success btn-sm' onclick='load_edit_floorplan_modal(`" . $row['floorplan_id'] . "`)' style='width: 100%;'>Edit Floorplan</button> </td> <td> <button type='button' class='btn btn-danger btn-sm' onclick='show_delete_floorplan_modal(`" . $row['floorplan_id'] . "`)' style='width: 100%;'>Delete Floorplan</button> </td></tr>";

  }

  echo "</table><br /><br /><br />";
}

?>

    <script>
        var floorplan_being_edited; // Holds the id of the floorplan being edited
        var tableRowClicked; // Holds the index of the table row clicked so to remove it if the user deletes a floorplan
        var floorplanMarkerBeingEdited; // Keeps track of the floorplan marker being edited so that we can add back the onclick events

        $("#floorplanUploadForm").on('submit', function(event) {
            event.preventDefault();
            if ($("#fileToUpload").val() == "") {
                $("#floorplanUploadMessage").html("<span style='color: red;'> A file to upload is required. </span>");
            } else if ($("#floor").val() == "") {
                $("#floorplanUploadMessage").html("<span style='color: red;'> A floorplan name is required. </span>");
            } else {
                $.ajax({
                    url: 'models/floorplanupload_model.php', //Server script to process data
                    type: 'POST',
                    // Form data
                    data: new FormData(this),
                    //Options to tell jQuery not to process data or worry about content-type.
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) // A function to be called if request succeeds
                        {
                            data = jQuery.parseJSON(data);
                            if (data.status.trim() == "success") {
                                $("#floorplanUploadMessage").html("<span style='color: green;'> Floor plan added successfully. </span>");
                                $('#floorplansTable > tbody:last-child').append("<tr> <td> " + data.floor + " </td> <td> <button type='button' class='btn btn-success btn-sm' onclick='load_edit_floorplan_modal(`" + data.floorplan_id + "`)' style='width: 100%;'>Edit Floorplan</button> </td> <td> <button type='button' class='btn btn-danger btn-sm' onclick='show_delete_floorplan_modal(`" + data.floorplan_id + "`)' style='width: 100%;'>Delete Floorplan</button> </td></tr>");
                                if ( $( "#no_floorplans_message" ).length ) {
                                    $( "#no_floorplans_message" ).remove();
                                }
                                $("#fileToUpload").val("");
                                $("#floor").val("");
                            } else {
                                $("#floorplanUploadMessage").html("<span style='color: red;'> " + data.message + "</span>");
                            }
                        }
                });
            }
        });

        function load_edit_floorplan_modal(floorplan) {
            floorplan_being_edited = floorplan;

            $.post(
                "models/load_edit_floorplan.php", {
                    floorplan: floorplan_being_edited,
                    default_pin_color: default_pin_color
                },
                function(json) {
                    parsedJson = jQuery.parseJSON(json);
                    $.each(parsedJson, function(key, data) {
                        if (key == 'floorplan_information') {
                            document.getElementById("floorplanName").value = data.floor;
                            document.getElementById("floorplanImage").src = data.image_location;
                        } else {
                            if (key == "no_markers") {

                            } else {
                                $.each(data, function(index, value) {
                                    colorPins(value.pin_color);
                                    $("#floorplanModalDiv").append('<img src=' + fullimg + ' class="markers_on_floorplan" id="marker_' + value.marker_id + '" style="display: block; position: absolute; left:' + value.latitude + '%; top:' + value.longitude + '%;" title="' + value.name + '\n' + value.location + '" onclick="marker_actions(`' + value.marker_id + '`)"/>');
                                });
                            }
                        }
                    });
                 }
            );

            $("#editFloorplanModal").modal("show");
        }

        //PRocess to change the colors of each pin
        //Variables to store each process
        selectImg = '';
        canvas = document.createElement("canvas");
        ctx = canvas.getContext("2d");
        originalPixels = null;
        currentPixels = null;
        color = '';
        fullimg = '';
        img = new Image();
        img.src = "images/house_pin.png";

        // Function for convert Hexdecimal code into RGB color
        function HexToRGB(Hex) {
            var Long = parseInt(Hex.replace(/^#/, ""), 16);
            return {
                R: (Long >>> 16) & 0xff,
                G: (Long >>> 8) & 0xff,
                B: Long & 0xff
            };
        }
        // Function to fill the color of generated image
        function fillColor(path) {
            color = path;

            if (!originalPixels) return; // Check if image has loaded
            var newColor = HexToRGB(color);

            for (var I = 0, L = originalPixels.data.length; I < L; I += 4) {
                if (currentPixels.data[I + 3] > 0) {
                    currentPixels.data[I] = originalPixels.data[I] / 255 * newColor.R;
                    currentPixels.data[I + 1] = originalPixels.data[I + 1] / 255 * newColor.G;
                    currentPixels.data[I + 2] = originalPixels.data[I + 2] / 255 * newColor.B;
                }
            }

            ctx.putImageData(currentPixels, 0, 0);
            fullimg = canvas.toDataURL("image/house_pin.png");
        }

        // Function for draw a image
        function colorPins(color) {
            //fullimg = document.getElementsByTagName('img')[0];
            selectImg = img;
            //alert(img.src);
            //alert(img.src);
            canvas.width = selectImg.width;
            canvas.height = selectImg.height;

            ctx.drawImage(selectImg, 0, 0, selectImg.naturalWidth, selectImg.naturalHeight, 0, 0, selectImg.width, selectImg.height);
            originalPixels = ctx.getImageData(0, 0, selectImg.width, selectImg.height);
            currentPixels = ctx.getImageData(0, 0, selectImg.width, selectImg.height);

            selectImg.onload = null;
            fillColor(color);
        }
        //End of the color process

        function add_marker_on_floorplan_image() {
            $("#floorplanFormButtons").empty();
            if ($("#floorplan_marker_being_added")) {
                $("#floorplan_marker_being_added").remove();
            }
            if (floorplanMarkerBeingEdited) {
                addMarkerOnClickEvent(floorplanMarkerBeingEdited);
            }
            $.post(
                "models/jquery_load_add_markers_on_floorplan_form.php", {
                    floorplan: floorplan_being_edited
                },
                function(data) {
                    $("#floorplanFormsField").html(data);
                }
            );

            overalayColor(community_marker_color);

            var img = $('<img id="floorplan_marker_being_added">');
            img.attr('src', fullimg);
            img.css("display", "block");
            img.css("position", "absolute");
            img.css("top", "50%");
            img.css("left", "50%");
            img.css("cursor", "move");
            //img.css("z-index", "1000");
            img.attr("onmousedown", "_move_item(this)");
            img.appendTo('#floorplanModalDiv');

        }


        //object of the element to be moved
        _item = null;

        //stores x & y co-ordinates of the mouse pointer
        mouse_x = 0;
        mouse_y = 0;

        // stores top,left values (edge) of the element
        ele_x = 0;
        ele_y = 0;

        move_init();

        //bind the functions
        function move_init() {
            document.onmousemove = _move;
            document.onmouseup = _stop;
        }

        //destroy the object when we are done
        function _stop() {
            _item = null;
        }

        //main functions which is responsible for moving the element (div in our example)
        function _move(e) {
            mouse_x = document.all ? window.event.clientX : e.pageX;
            mouse_y = document.all ? window.event.clientY : e.pageY;
            if (_item != null) {
                _item.style.left = (mouse_x - ele_x) + "px";
                _item.style.top = (mouse_y - ele_y) + "px";
            }
        }

        //will be called when use starts dragging an element
        function _move_item(ele) {
            //store the object of the element which needs to be moved
            _item = ele;
            ele_x = mouse_x - _item.offsetLeft;
            ele_y = mouse_y - _item.offsetTop;

        }

        function marker_actions(marker) {
            $("#floorplanFormsField").empty();
            $("#floorplanFormButtons").html("<button type='button' class='btn btn-info btn-sm' onclick='edit_floorplan_marker(`" + marker + "`)'>Edit Marker</button> <button type='button' class='btn btn-info btn-sm' onclick='add_remove_residents_to_floorplan_marker(`" + marker + "`)'>Add / Remove Residents</button> <button type='button' class='btn btn-info btn-sm' onclick='show_delete_floorplan_marker(`" + marker + "`)'>Delete Marker</button>")
            if ($("#floorplan_marker_being_added")) {
                $("#floorplan_marker_being_added").remove();
            }
            if (floorplanMarkerBeingEdited) {
                addMarkerOnClickEvent(floorplanMarkerBeingEdited);
            }
        }

        function addMarkerOnClickEvent(marker) {
            $("#marker_" + marker + "").attr("onclick", "marker_actions('" + marker +"')");
            $("#marker_" + marker + "").removeAttr("onmousedown");
        }

        function edit_floorplan_marker(marker) {
            $("#marker_" + marker + "").attr("onmousedown", "_move_item(this)");
            $("#marker_" + marker + "").removeAttr("onclick");

            if ($("#floorplan_marker_being_added")) {
                $("#floorplan_marker_being_added").remove();
            }

            $.post(
                "models/jquery_load_edit_floorplan_marker_form.php", {
                    marker: marker, // Id of the marker that is being edited
                },
                function(data) {
                    $("#floorplanFormsField").html(data);
                }
            );
        }

        function add_remove_residents_to_floorplan_marker(id) {

            if ($("#floorplan_marker_being_added")) {
                $("#floorplan_marker_being_added").remove();
            }

            if (floorplanMarkerBeingEdited) {
                addMarkerOnClickEvent(floorplanMarkerBeingEdited);
            }

            $.post(
                "models/jquery_add_remove_residents_to_floorplan_form.php", {
                    community: $.urlParam('community'), // Get the community id from the url
                    marker: id // Id of the marker that is being edited
                },
                function(data) {
                    $("#floorplanFormsField").html(data);
                }
            );
        }

        function show_delete_floorplan_marker(marker) {
            if ($("#floorplan_marker_being_added")) {
                $("#floorplan_marker_being_added").remove();
            }

            $("#floorplanFormsField").html("<h3> Are you sure you want to delete this marker? </h3> <button class='btn btn-primary btn-sm' onclick='delete_floorplan_marker(`" + marker + "`)'> Yes </button> <button class='btn btn-danger btn-sm' onclick=$('#floorplanFormsField').empty()> No </button>")
        }

        function delete_floorplan_marker(marker) {
            $.post(
                "models/delete_floorplan_marker_model.php", {
                    marker: marker, // Id of the marker that is being edited
                },
                function(data) {
                    if (data.trim() == "success") {
                        $("#floorplanFormButtons").empty();
                        $("#floorplanFormsField").empty();
                        $("#marker_" + marker + "").remove();
                    }
                }
            );
        }



        // This is used to get the table row clicked
        $("#floorplansTable").on("click", "tr", function(e) {
            tableRowClicked = ($(e.currentTarget).index());
        });

        function show_delete_floorplan_modal(floorplan) {
            $("#floorplanFormsField").empty();
            document.getElementById("deleteFloorplan").value = floorplan;
            $("#deleteFloorPlanModal").modal("show");
        }

        $("#deleteFloorplan").click(function(event) {
            $.post(
                "models/delete_floorplan_model.php", {
                    floorplan: $(this).val()
                },
                function(data) {
                    if (data.trim() == "success") {
                        $("#deleteFloorPlanModal").modal("hide");
                        $("#floorplansTable tr:eq(" + tableRowClicked + ")").remove();
                    } else {
                        $("#deleteFloorPlanErrorMessage").html("There was an error deleting the floorplan.");
                    }
                }
            );
        });

        $("#updateFloorplanName").click(function(event) {
            if ($("#floorplanName").val() != "") {
                $.post(
                    "models/update_floorplan_model.php", {
                        floorplan: floorplan_being_edited,
                        floor: $("#floorplanName").val()
                    },
                    function(data) {
                        if (data.trim() == "success") {
                            $("#updateFloorplanNameMessage").html("<b style='color:green'> Floorplan name successfully updated. </b>");
                        } else {
                            $("#updateFloorplanNameMessage").html("<b style='color:red'> There was an error updating the floorplan name. </b>");
                        }
                    }
                );
            } else {
                $("#updateFloorplanNameMessage").html("<b style='color:red'> A name for the floorplan is required. </b>");
            }
        });

        $("#changeFloorplanImage").click(function(event) {
            $("#floorplanFormButtons").empty();
            if ($("#floorplan_marker_being_added")) {
                $("#floorplan_marker_being_added").remove();
            }
            if (floorplanMarkerBeingEdited) {
                addMarkerOnClickEvent(floorplanMarkerBeingEdited);
            }
            $("#floorplanFormsField").html("<form id='updateFloorplanImage' action='' method='post' enctype='multipart/form-data'><table class='table table-striped table-hover table-condensed'> <tr> <th> Upload Floor Plan </th> <td> <input type='file' class='form-control input-sm' name='fileToUpload' id='newFile'> </td> </tr> <tr> <td></td> <td> <button type='submit' name='submit' value='" + floorplan_being_edited + "' class='btn btn-primary btn-sm' style='width: 100%;'>Upload Floorplan</button> </td> </tr> <tr> <th></th> <td id='floorplanChangeMessage'></td> </tr> </table> </form>");
        });

        $('#floorplanFormsField').on('submit', '#updateFloorplanImage', function(event) {
            event.preventDefault();
            if ($("#newFile").val() == "") {
                $("#floorplanChangeMessage").html("<span style='color: red;'> A file to upload is required. </span>");
            } else {
                $.ajax({
                    url: 'models/update_floorplan_model.php', //Server script to process data
                    type: 'POST',
                    // Form data
                    data: new FormData(this),
                    //Options to tell jQuery not to process data or worry about content-type.
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(json) // A function to be called if request succeeds
                        {
                            data = jQuery.parseJSON(json);
                            if (data.status.trim() == "success") {
                                $("#floorplanChangeMessage").html("<span style='color: green;'> Floor plan updated successfully. </span>");
                                $("#newFile").val("");
                                document.getElementById("floorplanImage").src = data.message;
                            } else {
                                $("#floorplanChangeMessage").html("<span style='color: red;'> " + data.message + "</span>");
                            }
                        }
                });
            }
        });

        $('#editFloorplanModal').on('hidden.bs.modal', function() {
            $("#floorplanFormButtons").empty();
            $("#updateFloorplanNameMessage").empty();
            $("#floorplanFormsField").empty();
            $(".markers_on_floorplan").remove();
            if ($("#floorplan_marker_being_added")) {
                $("#floorplan_marker_being_added").remove();
            }
        })

        $("#deleteFloorPlanModal").on('hidden.bs.modal', function() {
            $("#deleteFloorPlanErrorMessage").empty(); // Clear the error message
        });

        
    </script>

    <style>
        @media (max-width: @screen-xs-min) {
            .modal-xs {
                width: @modal-sm;
            }
        }
        

        #editFloorplanModal-modal-dialog{
            position: relative;
            display: table;
            overflow-y: auto;    
            overflow-x: auto;
            width: 90%;
            height: 90%; 
        }

        #editFloorplanModal-modal-content {
              height: 99%;
            }

        #floorplanFormsField::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: rgba(0, 0, 0, .5);
        }

        #floorplanFormsField::-webkit-scrollbar {
            width: 12px;
            background-color: rgba(0, 0, 0, .5);
        }

        #floorplanFormsField::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            background-color: #1995dc;
        }
    </style>

    <!-- Modal -->
    <div id="editFloorplanModal" class="modal fade" role="dialog">
        <div class="modal-dialog" id="editFloorplanModal-modal-dialog">
            <!-- Modal content -->
            <div class="modal-content" id="editFloorplanModal-modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-12 col-md-4" class="container-fluid">
                        <input type='text' class='form-control input-sm' id='floorplanName' placeholder='Floorplan Name'> <br />
                        <button type='button' class='btn btn-primary btn-sm' id='updateFloorplanName'> Update Floor Name </button>
                        <button type='button' class='btn btn-success btn-sm' id='changeFloorplanImage'> Change Floorplan Image </button>
                        <button type='button' class='btn btn-success btn-sm' onclick='add_marker_on_floorplan_image()'> Add Marker </button>
                        <br />
                        <span id='updateFloorplanNameMessage'> </span>
                        <br />
                        <div id='floorplanFormButtons' style="height:auto; overflow:auto;"> </div>
                        <br />
                        <div id='floorplanFormsField' style="height:auto; overflow:auto;"> </div>
                    </div>
                    <div id='floorplanModalDiv' class="col-sm-12 col-md-8" class="container-fluid">
                        <img id='floorplanImage' style='width: 100%; height: auto%;'>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="deleteFloorPlanModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Delete Floorplan</h3>
                </div>
                <div class="modal-body">
                    <b> Are you sure you want to delete this floorplan?
                    <br />
                    <span style="color: red"> This will delete all information tied to this floorplan. This action cannot be undone! </span><br /><br />
                    <span style="color: red" id="deleteFloorPlanErrorMessage"> </span></b>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="deleteFloorplan">Delete Floorplan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>