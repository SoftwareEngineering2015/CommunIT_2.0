<?php
require_once 'db_class.php';

//Get JSON data from controller
   $postdata = file_get_contents("php://input");
   //Decode JSON Data
   $request = json_decode($postdata);

   //Sanitizing inputs
   @$command = $request->command;
   $command = stripslashes($command);
   $command = mysql_real_escape_string($command);
   
   if ($command == "markers") {
      @$user = $request->user;
      $user = stripslashes($user);
      $user = mysql_real_escape_string($user);
   }

 //$command = "markers";
 //$user = "FE9EA169A531";
 //Gets all communities and the residents within a community.
if($command == "communities"){
    $communityQuerry = "SELECT community_id FROM users_to_communities WHERE user_id = '$user'";
    $communityCheck = $conn->query($communityQuerry) or exit("Error code ({$conn->errno}): {$conn->error}");

    if($communityCheck) {
        $communityCheck = $conn->query($communityQuerry) or exit("Error code ({$conn->errno}): {$conn->error}");
        
        //echo("This has at least one community");
        while($row = mysqli_fetch_assoc($communityCheck)) {
            $community_array = array (
                "community_id" => $row['community_id']
            );
            //json_encode($community_array, JSON_PRETTY_PRINT);
            foreach($community_array as $key => $community_id) { 
                
                //echo ($community_id . " ");
                //field : name of column // value : community_id
                $query = "SELECT
                            users.first_name AS 'firstname',
                            users.last_name AS 'lastname',
                            profiles.profile_id AS 'profile_id',
                            profiles.phone_01 AS 'phone_01',
                            profiles.phone_02 AS 'phone_02',
                            profiles.email_01 AS 'email_01',
                            profiles.email_02 AS 'email_02',
                            markers.location AS 'location',
                            communities.city AS 'community',
                            markers.name AS 'marker_name',
                            markers.marker_id AS 'marker_id',
                            users.user_id AS 'user_id',
                            markers.has_floorplan AS 'has_floorplan'
                        FROM
                            users,
                            profiles,
                            markers,
                            communities,
                            floor_plans,
                            markers_to_communities,
                            profiles_to_markers,
                            markers_to_floorplans,
                            floorplans_to_markers,
                            users_to_communities
                        WHERE 
                            users_to_communities.community_id = '$community_id'
                            AND profiles.community_id = users_to_communities.community_id
                            AND communities.community_id = users_to_communities.community_id
                            AND users_to_communities.user_id = users.user_id
                            AND profiles.user_id = users.user_id
                            AND floor_plans.floorplan_id = markers_to_floorplans.floorplan_id
                            AND profiles.profile_id = profiles_to_markers.profile_id
                            AND markers.marker_id = profiles_to_markers.marker_id
                    GROUP BY
                            users.user_id";
                            
                $result = $conn->query($query) or exit("Error code ({$conn->errno}): {$conn->error}");
            
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    
                    $profile_id = $row['profile_id'];
                    //echo ($profile_id . " ");
                    
                    $directory_array[$community_id]['community_id'] = $community_id;
                    $directory_array[$community_id]['community'] = $row['community'];
                    $directory_array[$community_id]['location'] = $row['location'];
                    
                    $directory_array[$community_id][$row['marker_id']]['marker_id'] = $row['marker_id'];
                    $directory_array[$community_id][$row['marker_id']]['marker_name'] = $row['marker_name'];
                    $directory_array[$community_id][$row['marker_id']]['community'] = $row['community'];
                    $directory_array[$community_id][$row['marker_id']]['location'] = $row['location'];
                    $directory_array[$community_id][$row['marker_id']]['has_floorplan'] = $row['has_floorplan'];
                    
                    $directory_array[$community_id][$row['marker_id']][$row['user_id']]['resident_id'] = $row['user_id'];
                    $directory_array[$community_id][$row['marker_id']][$row['user_id']]['firstname'] = $row['firstname'];
                    $directory_array[$community_id][$row['marker_id']][$row['user_id']]['lastname'] = $row['lastname'];
                    $directory_array[$community_id][$row['marker_id']][$row['user_id']]['phone_01'] = $row['phone_01'];
                    $directory_array[$community_id][$row['marker_id']][$row['user_id']]['phone_02'] = $row['phone_02'];
                    $directory_array[$community_id][$row['marker_id']][$row['user_id']]['email_01'] = $row['email_01'];
                    $directory_array[$community_id][$row['marker_id']][$row['user_id']]['email_02'] = $row['email_02'];
    
                    $residentQuery = "SELECT 
                                        residents.firstname AS 'firstname',
                                        residents.lastname AS 'lastname',
                                        residents.phone_01 AS 'phone_01',
                                        residents.phone_02 AS 'phone_02',
                                        residents.email_01 AS 'email_01',
                                        residents.email_02 AS 'email_02',
                                        residents.resident_id AS 'resident_id'
                                    FROM
                                        residents
                                    WHERE
                                        residents.profile_id = '$profile_id'
        
                                    GROUP BY 
                                        residents.resident_id";
                    $residentResult = $conn->query($residentQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
                    if (mysqli_num_rows($residentResult) > 0) {
                    
                        $residentResult = $conn->query($residentQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
                        while($row2 = mysqli_fetch_assoc($residentResult)) {
                    
                    $directory_array[$community_id][$row['marker_id']][$row2['resident_id']]['resident_id'] = $row2['resident_id'];
                    $directory_array[$community_id][$row['marker_id']][$row2['resident_id']]['firstname'] = $row2['firstname'];
                    $directory_array[$community_id][$row['marker_id']][$row2['resident_id']]['lastname'] = $row2['lastname'];
                    $directory_array[$community_id][$row['marker_id']][$row2['resident_id']]['phone_01'] = $row2['phone_01'];
                    $directory_array[$community_id][$row['marker_id']][$row2['resident_id']]['phone_02'] = $row2['phone_02'];
                    $directory_array[$community_id][$row['marker_id']][$row2['resident_id']]['email_01'] = $row2['email_01'];
                    $directory_array[$community_id][$row['marker_id']][$row2['resident_id']]['email_02'] = $row2['email_02'];
                    

                        } 
                    }
                    
                } 
                
            } 
        }      
        
        
    } else {
        $directory_array = array (
            "error" => "No Communities found."
        );
    }
    
    
//SOMETHINGELSE
} else if($command == "floorplans"){
   
   $floorplanQuerry = "SELECT 
	                        users.first_name AS 'firstname',
	                        users.last_name AS 'lastname',
                            users.user_id AS 'user_id',
	                        profiles.profile_id AS 'profile_id',
	                        profiles.phone_01 AS 'phone_01',
	                        profiles.phone_02 AS 'phone_02',
	                        profiles.email_01 AS 'email_01',
	                        profiles.email_02 AS 'email_02',
                            markers.marker_id AS 'marker_id',
	                        markers.location AS 'location',
                            markers.name AS 'marker_name',
                            floor_plans.floor AS 'floor',
                            floor_plans.floorplan_id AS 'floorplan_id'
                     FROM
	                        users,
                            profiles,
                            markers,
                            floorplans_to_markers,
                            markers_to_floorplans,
                            profiles_to_markers,
                            floor_plans
                     WHERE
	                        floorplans_to_markers.marker_id = 'AC47FE9C815F' AND
                            floorplans_to_markers.floorplan_id = markers_to_floorplans.floorplan_id AND
                            markers_to_floorplans.floorplan_id = floor_plans.floorplan_id AND
                            markers_to_floorplans.marker_id = profiles_to_markers.marker_id AND
                            profiles_to_markers.profile_id = profiles.profile_id AND
                            profiles_to_markers.marker_id = markers.marker_id AND
                            users.user_id = profiles.user_id
                     GROUP BY 
	                        users.user_id";
                            
    $floorcheck = $conn->query($floorplanQuerry) or exit("Error code ({conn->errno}): {$conn->error}");
   //communityQuerry = "SELECT community_id FROM users_to_communities WHERE user_id = 'FE9EA169A531'";
  //  $communityCheck = $conn->query($communityQuerry) or exit("Error code ({$conn->errno}): {$conn->error}");

    if($floorcheck) {
    echo ("passed the check: floorplan");
        while ($row = mysqli_fetch_assoc($floorcheck)) {
            
            $marker_id = $row['marker_id'];
            $profile_id = $row['profile_id'];
            
            $directory_array[$marker_id]['marker_id'] = $row['marker_id'];
            $directory_array[$marker_id]['marker_name'] = $row['marker_name'];
            $directory_array[$marker_id]['location'] = $row['location'];
            
            $directory_array[$marker_id][$row['floorplan_id']]['floorplan_id'] = $row['floorplan_id'];
            $directory_array[$marker_id][$row['floorplan_id']]['floor'] = $row['floor'];
            
            $directory_array[$marker_id][$row['floorplan_id']][$row['user_id']]['resident_id'] = $row['user_id'];
            $directory_array[$marker_id][$row['floorplan_id']][$row['user_id']]['firstname'] = $row['firstname'];
            $directory_array[$marker_id][$row['floorplan_id']][$row['user_id']]['lastname'] = $row['lastname'];
            $directory_array[$marker_id][$row['floorplan_id']][$row['user_id']]['phone_01'] = $row['phone_01'];
            $directory_array[$marker_id][$row['floorplan_id']][$row['user_id']]['phone_02'] = $row['phone_02'];
            $directory_array[$marker_id][$row['floorplan_id']][$row['user_id']]['email_01'] = $row['email_01'];
            $directory_array[$marker_id][$row['floorplan_id']][$row['user_id']]['email_02'] = $row['email_02'];
            
            $residentQuery = "SELECT 
                                        residents.firstname AS 'firstname',
                                        residents.lastname AS 'lastname',
                                        residents.phone_01 AS 'phone_01',
                                        residents.phone_02 AS 'phone_02',
                                        residents.email_01 AS 'email_01',
                                        residents.email_02 AS 'email_02',
                                        residents.resident_id AS 'resident_id'
                                    FROM
                                        residents
                                    WHERE
                                        residents.profile_id = '$profile_id'
        
                                    GROUP BY 
                                        residents.resident_id";
            
            $residentResult = $conn->query($residentQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
            
            if (mysqli_num_rows($residentResult) > 0) {
                    
                        $residentResult = $conn->query($residentQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
                        while($row2 = mysqli_fetch_assoc($residentResult)) {
                    
                    $directory_array[$marker_id][$row['floorplan_id']][$row2['resident_id']]['resident_id'] = $row2['resident_id'];
                    $directory_array[$marker_id][$row['floorplan_id']][$row2['resident_id']]['firstname'] = $row2['firstname'];
                    $directory_array[$marker_id][$row['floorplan_id']][$row2['resident_id']]['lastname'] = $row2['lastname'];
                    $directory_array[$marker_id][$row['floorplan_id']][$row2['resident_id']]['phone_01'] = $row2['phone_01'];
                    $directory_array[$marker_id][$row['floorplan_id']][$row2['resident_id']]['phone_02'] = $row2['phone_02'];
                    $directory_array[$marker_id][$row['floorplan_id']][$row2['resident_id']]['email_01'] = $row2['email_01'];
                    $directory_array[$marker_id][$row['floorplan_id']][$row2['resident_id']]['email_02'] = $row2['email_02'];
                    

                        } 
             }
             
            
        }
   
   } else {
        $directory_array = array (
            "error" => "Residents on selected floor."
        );
    }

//Here we grab all users/residents in all markers in each community they are 
//<--------------------------------------------------------------------------------------------------------------------------->\\  
} else if($command == "markers") {
    
    $communityQuery = "SELECT 
                            users_to_communities.community_id AS 'community_id',
                            config.community_name AS 'community_name'
                       FROM 
                            users_to_communities,
                            config
                       WHERE 
                            users_to_communities.user_id = '$user'
                            AND config.community_id = users_to_communities.community_id";
                            
    $communityCheck = $conn->query($communityQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
    
    if ($communityCheck) {
        while($row = mysqli_fetch_assoc($communityCheck)) {
           
           $community_id = $row["community_id"];
           $community_name = $row['community_name'];
           
           $directory_array[$community_id]['community_id'] = $row["community_id"];
           $directory_array[$community_id]['community_name'] = $row['community_name'];
           
           $markerQuery = "SELECT 
                                markers_to_communities.marker_id AS 'marker_id',
                                markers.name AS 'name',
                                markers.location AS 'location',
                                markers.has_floorplan AS 'has_floorplan' 
                           FROM 
                                markers_to_communities,
                                markers
                           WHERE 
                                markers_to_communities.community_id = '$community_id' AND
                                markers.marker_id = markers_to_communities.marker_id";
                                
           $markerCheck = $conn->query($markerQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
           
           if ($markerCheck) {
               while($row2 = mysqli_fetch_assoc($markerCheck)) {
                   
                   $marker_id = $row2["marker_id"];
                   $marker_name = $row2['name'];
                   $marker_location = $row2['location'];
                   $has_floorplan = $row2['has_floorplan'];
                   
                   $directory_array[$community_id][$marker_id]['marker_id'] = $row2['marker_id'];
                   $directory_array[$community_id][$marker_id]['name'] = $row2['name'];
                   $directory_array[$community_id][$marker_id]['location'] = $row2['location'];
                   
                   $userQuery = "SELECT 
	                                users.first_name AS 'firstname',
                                    users.last_name AS 'lastname',
                                    profiles.profile_id AS 'profile_id',
	                                profiles.phone_01 AS 'phone_01',
	                                profiles.phone_02 AS 'phone_02',
	                                profiles.email_01 AS 'email_01',
	                                profiles.email_02 AS 'email_02'
                                 FROM
	                                users,
                                    profiles,
                                    profiles_to_markers
                                 WHERE
	                                profiles_to_markers.marker_id = '$marker_id'
                                    AND profiles_to_markers.profile_id = profiles.profile_id 
                                    AND profiles.user_id = users.user_id";                  
                   
                   $userCheck = $conn->query($userQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
                   
                   if ($userCheck) {
                       while($row3 = mysqli_fetch_assoc($userCheck)) {
                           
                           $profile_id = $row3['profile_id'];
                           
                           $directory_array[$community_id][$marker_id][$profile_id]['user_id'] = $row3['profile_id'];
                           $directory_array[$community_id][$marker_id][$profile_id]['firstname'] = $row3['firstname'];
                           $directory_array[$community_id][$marker_id][$profile_id]['lastname'] = $row3['lastname'];
                           $directory_array[$community_id][$marker_id][$profile_id]['phone_01'] = $row3['phone_01'];
                           $directory_array[$community_id][$marker_id][$profile_id]['phone_02'] = $row3['phone_02'];
                           $directory_array[$community_id][$marker_id][$profile_id]['email_01'] = $row3['email_01'];
                           $directory_array[$community_id][$marker_id][$profile_id]['email_02'] = $row3['email_02'];
                           
                           $residentQuery = "SELECT 
                                                residents.firstname AS 'firstname',
                                                residents.lastname AS 'lastname',
                                                residents.phone_01 AS 'phone_01',
                                                residents.phone_02 AS 'phone_02',
                                                residents.email_01 AS 'email_01',
                                                residents.email_02 AS 'email_02',
                                                residents.resident_id AS 'resident_id'
                                            FROM
                                                residents
                                            WHERE
                                                residents.profile_id = '$profile_id'
                                            GROUP BY 
                                                residents.resident_id";
                                                
                          $residentCheck = $conn->query($residentQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
                          
                          if($residentCheck) {
                              while($row4 = mysqli_fetch_assoc($residentCheck)) {
                                  
                                  $resident_id = $row4['resident_id'];
                                  
                                  $directory_array[$community_id][$marker_id][$resident_id]['user_id'] = $row4['resident_id'];
                                  $directory_array[$community_id][$marker_id][$resident_id]['firstname'] = $row4['firstname'];
                                  $directory_array[$community_id][$marker_id][$resident_id]['lastname'] = $row4['lastname'];
                                  $directory_array[$community_id][$marker_id][$resident_id]['phone_01'] = $row4['phone_01'];
                                  $directory_array[$community_id][$marker_id][$resident_id]['phone_02'] = $row4['phone_02'];
                                  $directory_array[$community_id][$marker_id][$resident_id]['email_01'] = $row4['email_01'];
                                  $directory_array[$community_id][$marker_id][$resident_id]['email_02'] = $row4['email_02'];
                                   
                              }
                          }
                       //Still in the userCheck while loop....
                       
                       }
                        
                   }
                   //Still in the markerCheck while loop....
                   if ($has_floorplan == 1) {
                       $floorplanQuery = "SELECT
	                                        floorplans_to_markers.floorplan_id AS 'floorplan_id'
                                          FROM
	                                        floorplans_to_markers
                                          WHERE 
	                                        floorplans_to_markers.marker_id = '$marker_id'";
                                            
                      $floorplanCheck = $conn->query($floorplanQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
                      
                      if($floorplanCheck) {
                          while($row5 = mysqli_fetch_assoc($floorplanCheck)) {
                              
                              $floorplan_id = $row5["floorplan_id"];
                              
                              $floorplanUserQuery = "SELECT
	                                                    users.first_name AS 'firstname',
	                                                    users.last_name AS 'lastname',
	                                                    profiles.profile_id AS 'profile_id',
	                                                    profiles.phone_01 AS 'phone_01',
	                                                    profiles.phone_02 AS 'phone_02',
	                                                    profiles.email_01 AS 'email_01',
	                                                    profiles.email_02 AS 'email_02'
                                                    FROM 
	                                                    users,
                                                        profiles,
                                                        profiles_to_markers,
                                                        markers_to_floorplans
                                                    WHERE
	                                                    markers_to_floorplans.floorplan_id = '$floorplan_id'
                                                        AND markers_to_floorplans.marker_id = profiles_to_markers.marker_id
                                                        AND profiles_to_markers.profile_id = profiles.profile_id
                                                        AND profiles.user_id = users.user_id";
                             
                             $floorplanUserCheck = $conn->query($floorplanUserQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
                             
                             if ($floorplanUserCheck) {
                                 while($row6 = mysqli_fetch_assoc($floorplanUserCheck)) {
                                 
                                 $profile_id = $row6['profile_id'];
                           
                                 $directory_array[$community_id][$marker_id][$profile_id]['user_id'] = $row6['profile_id'];
                                 $directory_array[$community_id][$marker_id][$profile_id]['firstname'] = $row6['firstname'];
                                 $directory_array[$community_id][$marker_id][$profile_id]['lastname'] = $row6['lastname'];
                                 $directory_array[$community_id][$marker_id][$profile_id]['phone_01'] = $row6['phone_01'];
                                 $directory_array[$community_id][$marker_id][$profile_id]['phone_02'] = $row6['phone_02'];
                                 $directory_array[$community_id][$marker_id][$profile_id]['email_01'] = $row6['email_01'];
                                 $directory_array[$community_id][$marker_id][$profile_id]['email_02'] = $row6['email_02'];
                           
                                 $residentQuery = "SELECT 
                                                     residents.firstname AS 'firstname',
                                                     residents.lastname AS 'lastname',
                                                     residents.phone_01 AS 'phone_01',
                                                     residents.phone_02 AS 'phone_02',
                                                     residents.email_01 AS 'email_01',
                                                     residents.email_02 AS 'email_02',
                                                     residents.resident_id AS 'resident_id'
                                                   FROM
                                                     residents
                                                   WHERE
                                                     residents.profile_id = '$profile_id'
                                                   GROUP BY 
                                                     residents.resident_id";
                                
                                $residentCheck = $conn->query($residentQuery) or exit("Error code ({$conn->errno}): {$conn->error}");
                          
                                if($residentCheck) {
                                    while($row4 = mysqli_fetch_assoc($residentCheck)) {
                                  
                                    $resident_id = $row4['resident_id'];
                                  
                                    $directory_array[$community_id][$marker_id][$resident_id]['user_id'] = $row4['resident_id'];
                                    $directory_array[$community_id][$marker_id][$resident_id]['firstname'] = $row4['firstname'];
                                    $directory_array[$community_id][$marker_id][$resident_id]['lastname'] = $row4['lastname'];
                                    $directory_array[$community_id][$marker_id][$resident_id]['phone_01'] = $row4['phone_01'];
                                    $directory_array[$community_id][$marker_id][$resident_id]['phone_02'] = $row4['phone_02'];
                                    $directory_array[$community_id][$marker_id][$resident_id]['email_01'] = $row4['email_01'];
                                    $directory_array[$community_id][$marker_id][$resident_id]['email_02'] = $row4['email_02'];
                                    
                                    }       
                                }
                    }
                  }
                }
              } 
            } 
          }
        }
      }    
    }
    
    
} else {
       $directory_array = array (
          "error" => "Error: Please try again."
      );
}

echo json_encode($directory_array, JSON_PRETTY_PRINT);
?>