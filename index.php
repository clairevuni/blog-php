<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog!</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Benvenuto nel Blog!</h1>
    <nav>
        <ul>
            <li><a href="admin/dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <h2>Community</h2>

    <?php 

require_once 'inc/db.php';

$sql = "SELECT posts.*, users.username FROM posts 
        JOIN users ON posts.author_id = users.id
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
        echo "<p>" . htmlspecialchars($row["content"]) . "</p>";
        echo "<p><em>Pubblicato il " . htmlspecialchars($row["created_at"]) . "</em></p>";
        // Mostra il vero autore del post
        echo "<p><strong>Autore:</strong> " . htmlspecialchars($row["username"]) . "</p>";
        echo "<hr>";
    }
} else {
    echo "Nessun post trovato";
}

$conn->close();
?>

</body>
</html>