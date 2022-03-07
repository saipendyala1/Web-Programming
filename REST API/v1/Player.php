<?php 
  class Player {
    // DB stuff
    private $conn;
    private $table = 'player';

    // Post Properties
    public $id;
    public $surname;
    public $givenname;
    public $nationality;
    public $dob;
    public $team_id;

        // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Retrieve all players of a given team
    public function read_all() {
        // Create query
        $query = 'SELECT t.name as team_name, p.id, p.surname, p.givenname, p.nationality, p.dob, p.team_id  
                                  FROM ' . $this->table . ' p
                                  INNER JOIN 
                                   team t ON p.team_id = t.id
                                  WHERE
                                  t.id = ' . $this->team_id . '
                                  ORDER BY
                                    p.surname ASC';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
  
        // Execute query
        $stmt->execute();
  
        return $stmt;
      }

      public function read() {
        // Create query
        $query = 'SELECT t.name as team_name, p.id, p.surname, p.givenname, p.nationality, p.dob, p.team_id
                                  FROM ' . $this->table . ' p
                                  INNER JOIN 
                                   team t ON p.team_id = t.id
                                  WHERE
                                  p.id = ' . $this->id ;
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
  
        // Execute query
        $stmt->execute();
  
        return $stmt;
      }

      public function add() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . '(surname, givenname, nationality, dob, team_id) VALUES(:surname,:givenname,:nationality,:dob, :team_id)';
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->surname = htmlspecialchars(strip_tags($this->surname));
        $this->givenname = htmlspecialchars(strip_tags($this->givenname));
        $this->nationality = htmlspecialchars(strip_tags($this->nationality));
        $this->dob = htmlspecialchars(strip_tags($this->dob));
        $this->team_id = htmlspecialchars(strip_tags($this->team_id));
        // Bind data
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':givenname', $this->givenname);
        $stmt->bindParam(':nationality', $this->nationality);
        $stmt->bindParam(':dob', $this->dob);
        $stmt->bindParam(':team_id', $this->team_id);
        // Execute query
        try {
          if ($stmt->execute()) {
             return true;
          }
        } catch (Exception $e) {
            echo 'ERROR: '. $e->getMessage();
          }
        // Print error if something goes wrong
        //printf("Error: %s.\n", $stmt->error);
        return false;

    }

    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' 
        SET surname = :surname, givenname = :givenname, nationality = :nationality, dob = :dob, team_id = :team_id 
        WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->surname = htmlspecialchars(strip_tags($this->surname));
        $this->givenname = htmlspecialchars(strip_tags($this->givenname));
        $this->nationality = htmlspecialchars(strip_tags($this->nationality));
        $this->dob = htmlspecialchars(strip_tags($this->dob));
        $this->team_id = htmlspecialchars(strip_tags($this->team_id));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':givenname', $this->givenname);
        $stmt->bindParam(':nationality', $this->nationality);
        $stmt->bindParam(':dob', $this->dob);
        $stmt->bindParam(':team_id', $this->team_id);

        // Execute query
        if($stmt->execute()) {
          return true;
        }    
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;

    }

    public function delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if($stmt->execute()) {
          return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
  }

  }