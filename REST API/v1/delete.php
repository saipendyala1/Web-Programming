<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once './Database.php';
  include_once './Player.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate player object
  $player = new Player($db);

  // Get raw player data
  $data = json_decode(file_get_contents("php://input"));

  // Set ID to update
  $player->id = $data->id;

  // Delete player
  if($player->delete()) {
    echo json_encode(
      array('message' => 'Player Deleted')
    );
  } else {
    echo json_encode(
      array('message' => 'Player Not Deleted')
    );
  }

