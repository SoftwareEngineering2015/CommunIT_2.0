<html >
  <head>
    <?php

    require_once( "template_class.php");       // css and headers
    $H = new template( "CommunIT Residents" );
    $H->show_template( );

    ?>
	<script src="controllers/residents_controller.js"></script>
  <style>
    #profileRow{
      color: #006699;
      font-weight: bold;
    }
    #notAvailable{
      color: #bebebe;
    }
  </style>
  </head>
      <body ng-controller="residentsController" ng-init="checkHasEdited(); getInfo();" ng-click="successMsg = null; errorMsg = null; deleteMsg = false;">
          <div id="welcomejumbotron" class="jumbotron" ng-show="viewSwitch ">
          <div ng-show="!selectProfile" style="padding-left: 5%;">
            <h2 id="welcomejumbotrontext">Welcome {{userfirstname}} {{userlastname}}!</h2>
            <h4 id="welcomejumbotrontext">Please select a community</h4>
          </div>
          <div id="welcomejumbotron" ng-show="selectProfile" style="padding-left: 5%;">
            <h2 id="welcomejumbotrontext">Welcome {{userfirstname}} {{userlastname}}!</h2>
            <h4 id="welcomejumbotrontext" ng-show="profiles[selectProfile].marker_name">Here are your residents for <b>{{profiles[selectProfile].marker_name}}</b>, at <b>{{profiles[selectProfile].community_name}}</b>.</h4>
            <h4 id="welcomejumbotrontext" ng-show="!profiles[selectProfile].marker_name">No place of residence set at <b>{{profiles[selectProfile].community_name}}</b>.</h4>
          </div>
        </div>
          <div id="communitySelecter" class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3" ng-show="!selectProfile && viewSwitch">
            <h2>Community</h2>
            <div class="col-xs-12" style="text-align: center; font-weight: bold;" ng-show="!selectProfile">
              Please Select a Community.
              <br /><br />
            </div>
            <table class="col-xs-12 table table-hover">
              <tr>
                <td id="profileRow">Selected Community: &nbsp</td>
                <td style="float: left;">
                  <select id="selectMarker" ng-model="selectProfile" class="form-control" ng-change="changeInfo(profiles[selectProfile].profile_id);">
                      <option ng-repeat="markers in profiles track by $index" value="{{$index}}">{{markers.community_name}}</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>

          <div class="col-sm-5" class="container-fluid" ng-show="viewSwitch">
              <div id="communitySelecter" ng-show="selectProfile">
                <h2>Community</h2>
                <table class="col-xs-12 table table-hover">
                  <tr>
                    <td id="profileRow">Selected Community: &nbsp</td>
                    <td style="float: left;">
                      <select id="selectMarker" ng-model="selectProfile" class="form-control" ng-change="changeInfo(profiles[selectProfile].profile_id);">
                          <option ng-repeat="markers in profiles track by $index" value="{{$index}}">{{markers.community_name}}</option>
                      </select>
                    </td>
                  </tr>
                  <tr ng-show="selectProfile">
                    <td id="profileRow">Location: </td>
                    <td ng-show="profiles[selectProfile].marker_name"><b>{{profiles[selectProfile].marker_name}}</b></td>
                    <td ng-show="!profiles[selectProfile].marker_name"><b>No place of residence set.</b></td>
                  </tr>
                </table>
              </div>
              <br /><br /><br />

            <div id="detailedResident" ng-show="!showDetailedResident && !showEditResident && !showInsertResident && !showDeleteResident && selectProfile">
              <h2>Please Select a Resident</h2>
            </div>
          <div id="detailedResident" ng-show="showDetailedResident">
            <h2>{{residents[selectedResidentID].firstname}} {{residents[selectedResidentID].lastname}}</h2>
            <table class="table table-striped table-hover ">
              <tr>
                <td id="profileRow">Primary Phone: </td>
                <td ng-show="residents[selectedResidentID].phone_01"> {{residents[selectedResidentID].phone_01}} </td>
                <td id="notAvailable" ng-show="!residents[selectedResidentID].phone_01"> N/A </td>
              </tr>
              <tr>
                <td id="profileRow">Secondary Phone: </td>
                <td ng-show="residents[selectedResidentID].phone_02"> {{residents[selectedResidentID].phone_02}} </td>
                <td id="notAvailable" ng-show="!residents[selectedResidentID].phone_02"> N/A </td>
              </tr>
              <tr>
                <td id="profileRow">Primary Email: </td>
                <td ng-show="residents[selectedResidentID].email_01"> {{residents[selectedResidentID].email_01}} </td>
                <td id="notAvailable" ng-show="!residents[selectedResidentID].email_01"> N/A </td>
              </tr>
              <tr>
                <td id="profileRow">Secondary Email: </td>
                <td ng-show="residents[selectedResidentID].email_02"> {{residents[selectedResidentID].email_02}} </td>
                <td id="notAvailable" ng-show="!residents[selectedResidentID].email_02"> N/A </td>
              </tr>
            </table>
          </div>

          <div id="editResident" ng-show="showEditResident">
          <form class="form-vertical" ng-submit="updateRes(selectedResidentID);" ng-click="successMsg = null; errorMsg = null;">
            <h2>{{residents[selectedResidentID].firstname}} {{residents[selectedResidentID].lastname}}</h2>
              <table class="table" ng-show="successMsg || errorMsg">
                <tr style="text-align: center; font-weight: bold;" ng-show="successMsg">
                  <td colspan="2">
                    <div class="alert alert-success" ng-show="successMsg">{{successMsg}}</div>
                  </td>
                </tr>
                <tr style="text-align: center; font-weight: bold;" ng-show="errorMsg">
                  <td colspan="2">
                    <div class="alert alert-danger" ng-show="errorMsg">{{errorMsg}}</div>
                  </td>
                </tr>
              </table>
              <table class="table table-striped table-hover" ng-show="selectProfile" >
                <tr>
                  <td id="profileRow">First Name: </td>
                  <td><input type="text" class="form-control" id="inputFirstName" placeholder="First Name" value="{{residents[selectedResidentID].firstname}}" ng-model="residentFirstName" required></td>
                </tr>
                <tr>
                  <td id="profileRow">Last Name: </td>
                  <td><input type="text" class="form-control" id="inputLastName" placeholder="Last Name" value="{{residents[selectedResidentID].lastname}}" ng-model="residentLastName" required></td>
                </tr>
                <tr>
                  <td id="profileRow">Primary Phone: </td>
                  <td><input type="tel" class="form-control" id="inputPhone01" placeholder="Primary Phone Number" value="{{residents[selectedResidentID].phone_01}}" ng-model="phone_01" minlength="10"></td>
                </tr>
                <tr>
                  <td id="profileRow">Secondary Phone: </td>
                  <td><input type="tel" class="form-control" id="inputPhone02" placeholder="Secondary Phone Number" value="{{residents[selectedResidentID].phone_02}}" ng-model="phone_02" minlength="10"></td>
                </tr>
                <tr>
                  <td id="profileRow">Primary Email: </td>
                  <td><input type="email" class="form-control" id="inputEmail01" placeholder="Primary E-mail Address" value="{{residents[selectedResidentID].email_01}}" ng-model="email_01" minlength="5"></td>
                </tr>
                <tr>
                  <td id="profileRow">Secondary Email: </td>
                  <td><input type="email" class="form-control" id="inputEmail02" placeholder="Secondary E-mail Address" value="{{residents[selectedResidentID].email_02}}" ng-model="email_02" minlength="5"></td>
                </tr>
              </table>
              <div class="col-xs-12" ng-show="selectProfile">
                <button type="submit" class="col-xs-5 col-xs-offset-1 btn btn-lg btn-primary" >Update Profile</button>
                <span class="col-xs-5 col-xs-offset-1 btn btn-lg btn-danger" ng-click="clearEdit();">Cancel</span>
              <!--  <button ng-click="changeInfo()" class="col-xs-5 col-xs-offset-1 btn btn-lg btn-danger">Cancel</button> -->
              </div>
              <br /> <br /><br />
            </form>
          </div>

          <div id="insertResident" ng-show="showInsertResident">
          <form class="form-vertical" ng-submit="insertRes();" ng-click="successMsg = null; errorMsg = null;">
            <h2>Add New Resident</h2>
              <table class="table" ng-show="successMsg || errorMsg">
                <tr style="text-align: center; font-weight: bold;" ng-show="successMsg">
                  <td colspan="2">
                    <div class="alert alert-success" ng-show="successMsg">{{successMsg}}</div>
                  </td>
                </tr>
                <tr style="text-align: center; font-weight: bold;" ng-show="errorMsg">
                  <td colspan="2">
                    <div class="alert alert-danger" ng-show="errorMsg">{{errorMsg}}</div>
                  </td>
                </tr>
              </table>
              <table class="table table-striped table-hover" ng-show="selectProfile" >
                <tr>
                  <td id="profileRow">First Name: </td>
                  <td><input type="text" class="form-control" id="inputFirstName" placeholder="First Name" ng-model="residentFirstName" required></td>
                </tr>
                <tr>
                  <td id="profileRow">Last Name: </td>
                  <td><input type="text" class="form-control" id="inputLastName" placeholder="Last Name" ng-model="residentLastName" required></td>
                </tr>
                <tr>
                  <td id="profileRow">Primary Phone: </td>
                  <td><input type="tel" class="form-control" id="inputPhone01" placeholder="Primary Phone Number"  ng-model="phone_01" minlength="10"></td>
                </tr>
                <tr>
                  <td id="profileRow">Secondary Phone: </td>
                  <td><input type="tel" class="form-control" id="inputPhone02" placeholder="Secondary Phone Number"  ng-model="phone_02" minlength="10"></td>
                </tr>
                <tr>
                  <td id="profileRow">Primary Email: </td>
                  <td><input type="email" class="form-control" id="inputEmail01" placeholder="Primary E-mail Address" ng-model="email_01" minlength="5"></td>
                </tr>
                <tr>
                  <td id="profileRow">Secondary Email: </td>
                  <td><input type="email" class="form-control" id="inputEmail02" placeholder="Secondary E-mail Address"  ng-model="email_02" minlength="5"></td>
                </tr>
              </table>
              <div class="col-xs-12" ng-show="selectProfile">
                <button type="submit" class="col-xs-5 col-xs-offset-1 btn btn-lg btn-primary" >Add Resident</button>
                <span class="col-xs-5 col-xs-offset-1 btn btn-lg btn-danger" ng-click="clearInsert();">Cancel</span>
              <!--  <button ng-click="changeInfo()" class="col-xs-5 col-xs-offset-1 btn btn-lg btn-danger">Cancel</button> -->
              </div>
              <br /> <br /><br />
            </form>
          </div>
          <div ng-show="deleteMsg">
          <table class="table" ng-show="successMsg || errorMsg">
            <tr style="text-align: center; font-weight: bold;" ng-show="successMsg">
              <td colspan="2">
                <div class="alert alert-success" ng-show="successMsg">{{successMsg}}</div>
              </td>
            </tr>
            <tr style="text-align: center; font-weight: bold;" ng-show="errorMsg">
              <td colspan="2">
                <div class="alert alert-danger" ng-show="errorMsg">{{errorMsg}}</div>
              </td>
            </tr>
          </table>
        </div>

        <div id="deleteResident" ng-show="showDeleteResident">
          <h2 style="text-align: center;">Are you sure you want to delete <br /> "{{residents[selectedResidentID].firstname}} {{residents[selectedResidentID].lastname}}"?</h2>
          <span class="col-xs-5 col-xs-offset-1 btn btn-primary" ng-click="deleteRes(selectedResidentID);"> YES </span>
          <span class="col-xs-5 col-xs-offset-1 btn btn-danger" ng-click="showDetailed(selectedResidentID);"> NO </span>
        </div>

         </div>

         <!-- Here we have the form for editting password and primary email -->
         <div class="col-sm-7" class="container-fluid" ng-show="viewSwitch" ng-init="getInfo();">
          <h2 ng-show="selectProfile">Residents</h2>
          <table class="table table-striped table-hover" ng-show="selectProfile">
            <tr id="profileRow">
              <th >Name</th><th>Primary Phone</th><th>Primary E-mail</th><th>Edit</th><th>Remove</th>
            </tr>
            <tr>
              <td colspan="5" style="text-align: center;" ng-show="residents.error">
                No Residents Found
              </td>
            </tr>
            <tr ng-repeat="resident in residents track by $index" ng-show="!residents.error">
                <td class="col-xs-3"  ng-click="showDetailed(resident.resident_id);"> {{resident.firstname}} {{resident.lastname}} </td>
                <td ng-show="resident.phone_01"  ng-click="showDetailed(resident.resident_id);"> {{resident.phone_01}} </td>
                <td id="notAvailable" ng-show="!resident.phone_01"  ng-click="showDetailed(resident.resident_id);"> N/A </td>
                <td ng-show="resident.email_01"  ng-click="showDetailed(resident.resident_id);"> {{resident.email_01}} </td>
                <td id="notAvailable" ng-show="!resident.email_01"  ng-click="showDetailed(resident.resident_id);"> N/A </td>
                <td class="col-xs-1 btn btn-primary" ng-click="showEdit(resident.resident_id);"> Edit </td>
                <td class="col-xs-1 btn btn-danger" ng-click="showDelete(resident.resident_id);"> Remove </td>
            </tr>
          </table>
          <div ng-show="selectProfile">
            <button class="col-xs-6 col-xs-offset-3 btn btn-lg btn-primary" ng-click="showInsert(); clearInsert();">Add Resident</button>
          </div>

          </div>
         <!-- These only show if they don't have a profile -->
         <div class="jumbotron" ng-show="!viewSwitch" style="padding-left: 5%;">
           <h1> Whoops, looks like you have no profiles. </h1>
           <h3> Why not search for a community to join or create one for your community? </h3>
         </div>
         <div class="container-fluid" ng-show="!viewSwitch">
             <a href="communitysearch.php" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-1 btn btn-lg btn-primary"> Join a Community </a>
             <a href="createcommunity.php" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-2 btn btn-lg btn-primary"> Create a Community </a>
         </div>
      </body>
</htmL>
