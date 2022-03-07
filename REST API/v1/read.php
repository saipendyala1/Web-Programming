<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'Database.php';
  include_once 'Team.php';

//  function console_log($output, $with_script_tags = true) {
  //  $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
//');';
 //   if ($with_script_tags) {
   //     $js_code = '<script>' . $js_code . '</script>';
    //}
   // echo $js_code;
 // }
  
  //console_log("Inside read.php");
  
  // Instantiate DB & connect
  $database = new Database();
  
  
  $db = $database->connect();


  
  // Instantiate team object
  $team = new Team($db);

  // Team read query
  $result = $team->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any teams
  if($num > 0) {
        // Team array
        $team_arr = array();
        $team_arr['teams'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $team_item = array(
            'id' => $id,
            'name' => $name,
            'sport' => $sport
          );

          // Push to "teams"
          array_push($team_arr['teams'], $team_item);
        }

        // Turn to JSON & output
        echo json_encode($team_arr);

  } else {
        // No Teams
        echo json_encode(
          array('message' => 'No Teams Found')
        );
  }
