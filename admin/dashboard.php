<?php 
 //dashboard.php
require_once '../inc/db.php';
session_start();


if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1> Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
<nav>
    <ul>
        <li><a href="../index.php">Home</a></li>
        <li><a href="create_post.php">Crea Post</a></li>
        <li><a href="delete_post.php">Elimina Post</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>
    <h2>I tuoi post</h2>
<?php
require_once '../inc/db.php';
    $sql = "SELECT * FROM posts WHERE author_id = ? ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows >0){
        while($row = $result->fetch_assoc()) {
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['content']) . "</p>";
            echo "<p><em>Pubblicato il " . htmlspecialchars($row['created_at']) . "</em></p>";
            echo "<p><strong>Autore:</strong> " . htmlspecialchars($_SESSION['username']) . "</p>";
            echo "<hr>";
        }
    } else {
        echo "<p>Nessun post trovato.</p>";
    }

    // Chiudiamo la connessione
    $stmt->close();
    $conn->close();
?>
</body>
</html>