<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once 'Database.php';
  include_once 'Player.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate player object
  $player = new Player($db);

    // Get raw data
  $id = $_GET['id'];

  // Set team id to read
  $player->team_id = $id;
  
  // player query
  $result = $player->read_all();
  // Get row count
  $num = $result->rowCount();

  // Check if any players
  if($num > 0) {
    // Players array
    $players_arr = array();
    // $players_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $player_item = array(
        'id' => $id,
        'surname' => $surname,
        'givenname' => $givenname,
        'nationality' => $nationality,
        'dob' => $dob,
        'team_id' => $team_id,
        'team_name' => $team_name
      );

      // Push to "data"
      array_push($players_arr, $player_item);
      // array_push($players_arr['data'], $player_item);
    }

    // Turn to JSON & output
    echo json_encode($players_arr);

  } else {
    // No Players
    echo json_encode(
      array('message' => 'No Players Found')
    );
  }
