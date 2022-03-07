<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once 'Database.php';
  include_once 'Player.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate player object
  $player = new Player($db);

  // Get raw player data
  $data = json_decode(file_get_contents("php://input"));

  $player->id = $data->id;
  $player->surname = $data->surname;
  $player->givenname = $data->givenname;
  $player->nationality = $data->nationality;
  $player->dob = $data->dob;
  $player->team_id = $data->teamid;

  // Updated player
  if($player->update()) {
    echo json_encode(
      array('message' => 'Player info is updated')
    );
  } else {
    echo json_encode(
      array('message' => 'Player info is not updated')
    );
  }

