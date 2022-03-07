<?php 
  class Team {
    // DB stuff
    private $conn;
    private $table = 'team';

    // Post Properties
    public $id;
    public $name;
    public $sport;

        // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Retrieve all team names
    public function read() {
        // Create query
        $query = 'SELECT t.id, t.name, t.sport 
                                  FROM ' . $this->table . ' t
                                  ORDER BY
                                    t.name ASC';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
  
        // Execute query
        $stmt->execute();
  
        return $stmt;
      }
  
  }