<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once './Database.php';
  include_once './Player.php';
try {
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();
  
  // Instantiate player object
  $player = new Player($db);

  // Get raw player data
  $data = json_decode(file_get_contents("php://input"));

  $player->surname = $data->surname;
  $player->givenname = $data->givenname;
  $player->nationality = $data->nationality;
  $player->dob = $data->dob;
  $player->team_id = $data->teamid;
  // Create player
  if($player->add()) {
    $msg = array('message'=>'Player added to  team');
    echo json_encode($msg);
    
  } else {
    $msg = array('message'=>'Player not added to team');
    echo json_encode($msg);
  }
} catch (Exception $e) {
   
    print_r(json_encode(array('status' => 500, 'message' => 'Internal server error')));
}
