<!DOCTYPE html>
<html>
<head>

<?php

require_once( "template_class.php");       // css and headers
$H = new template( "FloorPlan Uploader");
$H->show_template();

?>
</head>
<body>
<div
<div class="container">

<form action="models/floorplanupload_model.php" method="post" enctype="multipart/form-data">
    <div class="row centered-form col-xs-6">
    <br /><br />
    Select a floorplan to upload:

    <div class="form-group">
      <label for="fileToUpload" class="col-xs-4 control-label">Upload Floor Plan</label>
      <div class="col-lg-8">
        <input type="file" class="form-control input-sm" name="fileToUpload" id="fileToUpload">
      </div>
    </div>

    <div class="form-group">
      <label for="floor" class="col-xs-4 control-label">Floor Name</label>
      <div class="col-lg-8">
        <input type="text" class="form-control input-sm" name="floor" id="floor">
      </div>
    </div>

    <div class="form-group">
          <label for="marker_id" class="col-xs-4 control-label">Marker(Temporary: Attach floorplan to)</label>
      <div class="col-lg-8">
        <input type="text" class="form-control input-sm col-xs-10" name="marker_id" id="marker_id">
      </div>
    </div>

    <br /> <br /><br /> <br />

    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <button type="submit" class="btn btn-primary" style="text-align: center" name="submit">Submit</button>
      </div>
    </div>

      </div>
</form>

</div>
</div>
</body>
</html>
