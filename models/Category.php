<?php

class Category
{
  // DB Stuff
  private $conn;
  private $table = 'categories';

  // Category Properties
  public $id;
  public $name;
  public $created_at;

  // Constructor with DB
  public function __construct($db) {
    $this->conn = $db;
  }

  // Get Categories
  public function read() {
    // Create Query
    $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

  public function read_single() {
    // Create Query
    $query = 'SELECT *  FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';
    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->id);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Set Properties
    $this->name = $row['name'];
    $this->created_at = $row['created_at'];

    return $stmt;
  }

  // Create Category
  public function create() {
    $query = 'INSERT INTO ' . $this->table . '
    SET 
      name = :name
    ';
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->name = htmlspecialchars(strip_tags($this->name));

    // Bind Data
    $stmt->bindParam(':name', $this->name);

    // Execute Query
    if($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);
    return false;
  }

  // Update Category
  public function update() {
    $query = 'Update ' . $this->table . ' SET name = :name WHERE id = :id';
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind Data
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':id', $this->id);

    // Execute Query
    if($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);
    return false;
  }

  public function delete() {
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $this->id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(':id', $this->id);

    // Execute Query
    if($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);
    return false;
  }
}
