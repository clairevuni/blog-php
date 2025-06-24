<?php
require_once '../inc/db.php';
session_start();

// Recupera i post dell'utente loggato
$posts = [];
if(isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT id, title FROM posts WHERE author_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="it">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elimina Post</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Vuoi eliminare un post?</h1>
    <form action="delete_post.php" method="POST">
        <label for="id">Scegli il post da eliminare:</label>
        <select id="id" name="id" required>
            <option value="">-- Seleziona un post --</option>
            <?php foreach($posts as $post): ?>
                <option value="<?php echo $post['id']; ?>">
                    <?php echo htmlspecialchars($post['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit">Elimina Post</button>
    </form> 
    <a href="dashboard.php">Torna alla Dashboard</a>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = trim($_POST['id']);

    if(empty($id)) {
        echo "<p>L'ID del post non può essere vuoto.</p>";
    } else {
        // Controlla che l'utente sia loggato
        if(!isset($_SESSION['user_id'])) {
            echo "<p>Errore: utente non autenticato.</p>";
        } else {
            $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND author_id = ?");
            $stmt->bind_param("ii", $id, $_SESSION['user_id']);

            if($stmt->execute()) {
                if($stmt->affected_rows > 0) {
                    echo "<p>Post eliminato con successo!</p>";
                } else {
                    echo "<p>Nessun post trovato con questo ID o non sei l'autore del post.</p>";
                }
            } else {
                echo "<p>Errore: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }
    }
}
?>
</body>
</html>
