<?php
   if(isset($_REQUEST["community"])) {
     $community_id = $_REQUEST["community"];
   } else {
     echo "noCommunity";
     exit;
   }
   
   include("db_class.php");
   
   $sql_community_information = "SELECT community_name, community_description, default_pin_color, default_pin_color_status, allow_user_pin_colors, city, province, country FROM communities INNER JOIN config ON communities.community_id = config.community_id WHERE communities.community_id = '$community_id'";
   $community_information_result = mysqli_query($conn, $sql_community_information);
   
   while($row = $community_information_result->fetch_assoc()){
       $name = $row['community_name'];
       $description = $row['community_description'];
       $default_pin_color = $row['default_pin_color'];
       $default_pin_color_status = $row['default_pin_color_status'];
       $allow_user_pin_colors = $row['allow_user_pin_colors'];
       $city = $row['city'];
       $state = $row['province'];
       $country = $row['country'];
   }
   
   ?>
<script>
   pin_color = document.getElementById('pincolor').value;
   overalayColor(pin_color);
   document.getElementById('house_pin').src = fullimg;
   //Change pin color on change of color select
   $("#pincolor").change(function() {
       pin_color = document.getElementById('pincolor').value;
       overalayColor(pin_color);
       document.getElementById('house_pin').src = fullimg;
   });
   // Jquery Actions
   $(document).ready(function() {
    $("#country").val("<?php echo $country; ?>");
   
    $("#default_pin_color_status_true").click(function() {
        $("#allow_user_pin_colors_false").prop("checked", true);
    });
   
    $("#allow_user_pin_colors_true").click(function() {
        $("#default_pin_color_status_false").prop("checked", true);
    });
   
    $('#show_modal_button').click(function() {
        if ($.trim($('#name').val()) == '') {
            $("#errorMsgCommunityName").html("Community name is required.");
        } else if ($.trim($('#city').val()) == '') {
            $("#errorMsgCommunityName").empty();
            $("#errorMsgCommunityCity").html("Community city is required.");
        } else if ($.trim($('#state').val()) == '') {
            $("#errorMsgCommunityName").empty();
            $("#errorMsgCommunityCity").empty();
            $("#errorMsgCommunityState").html("Community state is required.");
        } else if ($.trim($('#country').val()) == '') {
            $("#errorMsgCommunityName").empty();
            $("#errorMsgCommunityCity").empty();
            $("#errorMsgCommunityState").empty();
            $("#errorMsgCommunityCountry").html("Community country is required.");
        } else {
            $("#errorMsgCommunityName").empty();
            $("#errorMsgCommunityCity").empty();
            $("#errorMsgCommunityState").empty();
            $("#errorMsgCommunityCountry").empty();
   
            $("#inputCommunityName").html($("#name").val());
            $("#inputCommunityDescription").html($("#description").val());
            $("#inputCommunity").html($("#city").val());
   
            pin_color = document.getElementById('pincolor').value;
            overalayColor(pin_color);
            document.getElementById('inputPinColor').src = fullimg;
   
            if ($('#default_pin_color_status_true').is(':checked')) {
                $("#inputDefaultPinColorStatus").html("Yes");
            } else {
                $("#inputDefaultPinColorStatus").html("No");
            }
   
            if ($('#allow_user_pin_colors_true').is(':checked')) {
                $("#inputAllowUserPinColors").html("Yes");
            } else {
                $("#inputAllowUserPinColors").html("No");
            }
   
            $("#inputCommunityCity").html($("#city").val());
            $("#inputCommunityState").html($("#state").val());
            $("#inputCommunityCountry").html($("#country").val());
            $('#review_information_modal').modal('show');
        }
    });
   
    $('#review_information_modal').on('show.bs.modal', function() {
        $(this).find('.modal-body').css({
            width: 'auto', //probably not needed
            height: 'auto', //probably not needed 
            'max-height': '100%'
        });
    });
   
    $("#sumbit").click(function(){
   
            if ($('#default_pin_color_status_true').is(':checked')) {
                var default_pin_color_status = 1;
            } else {
                var default_pin_color_status = 0;
            }
   
            if ($('#allow_user_pin_colors_true').is(':checked')) {
                var allow_user_pin_colors = 1;
            } else {
                var allow_user_pin_colors = 0;
            }
   
      $.post("./models/update_community_settings_model.php", 
         {
            community: $("#sumbit").val(),
            inputCommunityName: $("#name").val(),
            inputCommunityDescription: $("#description").val(),
            inputDefaultPinColor: $("#pincolor").val(),
            inputDefaultPinColorStatus: default_pin_color_status,
            inputAllowUserPinColors: allow_user_pin_colors,
            inputCommunityCity: $("#city").val(),
            inputCommunityState: $("#state").val(),
            inputCommunityCountry: $("#country").val()
         },
      function(data, status){
        if (data.trim() === "success") {
          $('#review_information_modal').modal('hide');
          community_marker_color = $("#pincolor").val();

          if (default_pin_color_status == 1) {
            default_pin_color = community_marker_color;
            for (i in marker_ids) {
              overalayColor(community_marker_color);
              markers[i].setIcon(fullimg);
            }
          } else {
            default_pin_color = "";
            for (i in marker_ids) {
              overalayColor(defined_marker_pin_colors[i]);
              markers[i].setIcon(fullimg);
            }
          }
        } else {
         $("#updateCommunitySettingsErrorMessage").html("There was an error updating the settings.");
        }
      });
   });
    $("#review_information_modal").on('hidden.bs.modal', function() {
      $("#updateCommunitySettingsErrorMessage").empty();
    });
   });
</script>
<table class="table table-striped table-hover table-condensed ">
   <tr>
      <th> Community Name </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Give your community a name."> </a>
      </td>
      <td> </td>
      <td> <input type="text" class="form-control input-md" id="name" placeholder="Community Name" value="<?php echo $name; ?>"> <span class="text-danger" id="errorMsgCommunityName"></span> </td>
   </tr>
   <tr>
      <th> Community Description </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Provide a description for your community."> </a>
      </td>
      <td> </td>
      <td> <textarea class="form-control" id="description" placeholder="Community Description" wrap="soft" rows="5" maxlength="255"><?php echo $description; ?></textarea> </td>
   </tr>
   <tr>
      <th> Default Pin Color </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Choose what color the pins in your community will display as."> </a>
      </td>
      <td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height;auto"> </td>
      <td> <input type="color" name="pincolor" id="pincolor" style="width: 100%" value="<?php echo $default_pin_color; ?>"> </td>
   </tr>
   <tr>
      <th> Default Pin Color Status </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Every pin in your community will use the default color."> </a>
      </td>
      <td> </td>
      <td> <label class="radio-inline"><input type="radio" name="default_pin_color_status" id="default_pin_color_status_true" <?php if ($default_pin_color_status == 1) { echo "checked='checked'"; } ?> value="1">Yes</label> <label class="radio-inline"><input type="radio" name="default_pin_color_status" id="default_pin_color_status_false" <?php if ($default_pin_color_status == 0) { echo "checked='checked'"; } ?>value="0">No</label></td>
   </tr>
   <tr>
      <th> Allow User Pin Colors </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Allow users to define their pin color."> </a>
      </td>
      <td> </td>
      <td> <label class="radio-inline"><input type="radio" name="allow_user_pin_colors" id="allow_user_pin_colors_true" <?php if ($allow_user_pin_colors == 1) { echo "checked='checked'"; } ?> value="1">Yes</label> <label class="radio-inline"><input type="radio" name="allow_user_pin_colors" id="allow_user_pin_colors_false" <?php if ($allow_user_pin_colors == 0) { echo "checked='checked'"; } ?> value="0">No</label> </td>
   </tr>
   <tr>
      <th> City </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Where is your community located."> </a>
      </td>
      <td> </td>
      <td> <input type="text" class="form-control input-md" id="city" placeholder="Community City" value="<?php echo $city; ?>"> <span class="text-danger" id="errorMsgCommunityCity"></span> </td>
   </tr>
   <tr>
      <th> State / <br /> Province </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Where is your community located."> </a>
      </td>
      <td> </td>
      <td> <input type="text" class="form-control input-md" id="state" placeholder="Community State / Province" value="<?php echo $state; ?>"> <span class="text-danger" id="errorMsgCommunityState"></span> </td>
   </tr>
   <tr>
      <th> Country </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Where is your community located."> </a>
      </td>
      <td> </td>
      <td>
         <select id="country" name="country" class="form-control">
            <option value="Afghanistan">Afghanistan</option>
            <option value="Åland Islands">Åland Islands</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="American Samoa">American Samoa</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Anguilla">Anguilla</option>
            <option value="Antarctica">Antarctica</option>
            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
            <option value="Argentina">Argentina</option>
            <option value="Armenia">Armenia</option>
            <option value="Aruba">Aruba</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Bahamas">Bahamas</option>
            <option value="Bahrain">Bahrain</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Barbados">Barbados</option>
            <option value="Belarus">Belarus</option>
            <option value="Belgium">Belgium</option>
            <option value="Belize">Belize</option>
            <option value="Benin">Benin</option>
            <option value="Bermuda">Bermuda</option>
            <option value="Bhutan">Bhutan</option>
            <option value="Bolivia">Bolivia</option>
            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
            <option value="Botswana">Botswana</option>
            <option value="Bouvet Island">Bouvet Island</option>
            <option value="Brazil">Brazil</option>
            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
            <option value="Brunei Darussalam">Brunei Darussalam</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burundi">Burundi</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Canada">Canada</option>
            <option value="Cape Verde">Cape Verde</option>
            <option value="Cayman Islands">Cayman Islands</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Christmas Island">Christmas Island</option>
            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Congo">Congo</option>
            <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
            <option value="Cook Islands">Cook Islands</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="Cote D'ivoire">Cote D'ivoire</option>
            <option value="Croatia">Croatia</option>
            <option value="Cuba">Cuba</option>
            <option value="Cyprus">Cyprus</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Denmark">Denmark</option>
            <option value="Djibouti">Djibouti</option>
            <option value="Dominica">Dominica</option>
            <option value="Dominican Republic">Dominican Republic</option>
            <option value="Ecuador">Ecuador</option>
            <option value="Egypt">Egypt</option>
            <option value="El Salvador">El Salvador</option>
            <option value="Equatorial Guinea">Equatorial Guinea</option>
            <option value="Eritrea">Eritrea</option>
            <option value="Estonia">Estonia</option>
            <option value="Ethiopia">Ethiopia</option>
            <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
            <option value="Faroe Islands">Faroe Islands</option>
            <option value="Fiji">Fiji</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="French Guiana">French Guiana</option>
            <option value="French Polynesia">French Polynesia</option>
            <option value="French Southern Territories">French Southern Territories</option>
            <option value="Gabon">Gabon</option>
            <option value="Gambia">Gambia</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Gibraltar">Gibraltar</option>
            <option value="Greece">Greece</option>
            <option value="Greenland">Greenland</option>
            <option value="Grenada">Grenada</option>
            <option value="Guadeloupe">Guadeloupe</option>
            <option value="Guam">Guam</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guernsey">Guernsey</option>
            <option value="Guinea">Guinea</option>
            <option value="Guinea-bissau">Guinea-bissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
            <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
            <option value="Honduras">Honduras</option>
            <option value="Hong Kong">Hong Kong</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
            <option value="Iraq">Iraq</option>
            <option value="Ireland">Ireland</option>
            <option value="Isle of Man">Isle of Man</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Jamaica">Jamaica</option>
            <option value="Japan">Japan</option>
            <option value="Jersey">Jersey</option>
            <option value="Jordan">Jordan</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Kenya">Kenya</option>
            <option value="Kiribati">Kiribati</option>
            <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
            <option value="Korea, Republic of">Korea, Republic of</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Macao">Macao</option>
            <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Martinique">Martinique</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mayotte">Mayotte</option>
            <option value="Mexico">Mexico</option>
            <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
            <option value="Moldova, Republic of">Moldova, Republic of</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montenegro">Montenegro</option>
            <option value="Montserrat">Montserrat</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="Netherlands Antilles">Netherlands Antilles</option>
            <option value="New Caledonia">New Caledonia</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="Niue">Niue</option>
            <option value="Norfolk Island">Norfolk Island</option>
            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palau">Palau</option>
            <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
            <option value="Panama">Panama</option>
            <option value="Papua New Guinea">Papua New Guinea</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Pitcairn">Pitcairn</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Puerto Rico">Puerto Rico</option>
            <option value="Qatar">Qatar</option>
            <option value="Reunion">Reunion</option>
            <option value="Romania">Romania</option>
            <option value="Russian Federation">Russian Federation</option>
            <option value="Rwanda">Rwanda</option>
            <option value="Saint Helena">Saint Helena</option>
            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
            <option value="Saint Lucia">Saint Lucia</option>
            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
            <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
            <option value="Samoa">Samoa</option>
            <option value="San Marino">San Marino</option>
            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Senegal">Senegal</option>
            <option value="Serbia">Serbia</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra Leone">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
            <option value="Spain">Spain</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
            <option value="Swaziland">Swaziland</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syrian Arab Republic">Syrian Arab Republic</option>
            <option value="Taiwan, Province of China">Taiwan, Province of China</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
            <option value="Thailand">Thailand</option>
            <option value="Timor-leste">Timor-leste</option>
            <option value="Togo">Togo</option>
            <option value="Tokelau">Tokelau</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="United States">United States</option>
            <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Viet Nam">Viet Nam</option>
            <option value="Virgin Islands, British">Virgin Islands, British</option>
            <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
            <option value="Wallis and Futuna">Wallis and Futuna</option>
            <option value="Western Sahara">Western Sahara</option>
            <option value="Yemen">Yemen</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option>
         </select>
      </td>
   </tr>
   <tr>
      <th> </th>
      <td> </td>
      <td> </td>
      <td> <button type="button" class="btn btn-primary btn-md" id="show_modal_button" style="width:100%"> Update </button> </td>
   </tr>
</table>
<br />
<br />
<br />
<!-- Modal -->
<div id="review_information_modal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Community Information</h3>
   </div>
   <div class="modal-body">
      <table class="table table-striped table-hover">
         <tr>
            <th> Community Name </th>
            <td id="inputCommunityName"> </td>
         </tr>
         <tr>
            <th> Community Description </th>
            <td id="inputCommunityDescription"> </td>
         </tr>
         <tr>
            <th> Default Pin Color </th>
            <td> <img src="images/house_pin.png" id="inputPinColor" alt="" style="width:auto; height;auto"> </td>
         </tr>
         <tr>
            <th> Default Pin Color Status </th>
            <td id="inputDefaultPinColorStatus"> </td>
         </tr>
         <tr>
            <th> Allow User Pin Colors </th>
            <td id="inputAllowUserPinColors"> </td>
         </tr>
         <tr>
            <th> City </th>
            <td id="inputCommunityCity"> </td>
         </tr>
         <tr>
            <th> State / <br /> Province </th>
            <td id="inputCommunityState"> </td>
         </tr>
         <tr>
            <th> Country </th>
            <td id="inputCommunityCountry"> </td>
         </tr>
      </table>
      <div style="font-weight: bold; color: red;" id="updateCommunitySettingsErrorMessage"> </div>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="sumbit" value="<?php echo $community_id; ?>">Update Community Settings</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>