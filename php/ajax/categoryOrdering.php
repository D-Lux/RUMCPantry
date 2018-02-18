  <?php
  
  $conn = connectDB();
  
  // Get our category information
  $sql = "SELECT categoryID, name, order, isDeleted
          FROM category
          ORDER BY order";
  if (($results = runQuery($conn, $query)) === FALSE) {
    die("No categories in the database.");
  };
  
  closeDB($conn);
  
  
  
  
  ?>