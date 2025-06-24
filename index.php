<?php 

require_once 'inc/db.php';

$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

if($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<h2>" . $row["title"] . "</h2>";
        echo "<p>" . $row["content"] . "</p>";
        echo "<hr>";
    }
} else {
    echo "Nessun post trovato";
}


$conn->close();


?>