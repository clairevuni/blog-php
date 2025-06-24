 <?php 
 
// Connect to the database trough phpMyAdmin

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog_db";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection

if($conn ->connect_error){
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}


?>