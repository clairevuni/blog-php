<?php
require_once '../inc/db.php';
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Se è stato inviato il form di aggiornamento
if(isset($_POST['update'])) {
    $id = trim($_POST['id']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if(empty($title) || empty($content)) {
        echo "<p>Il titolo e il contenuto non possono essere vuoti.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND author_id = ?");
        $stmt->bind_param("ssii", $title, $content, $id, $_SESSION['user_id']);
        if($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<p>Errore durante l'aggiornamento.</p>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html> 
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Post</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Modifica un Post</h1>
    <form action="edit_post.php" method="POST">
        <label for="id">Scegli il post da modificare:</label>
        <select id="id" name="id" required>
            <option value="">-- Seleziona un post --</option>
            <?php
            // Recupera i post dell'utente loggato
            $stmt = $conn->prepare("SELECT id, title FROM posts WHERE author_id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['title']) . "</option>";
            }
            $stmt->close();
            ?>
        </select>
        <br>
        <button type="submit">Modifica Post</button>
    </form>

    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = trim($_POST['id']);
        
        if(empty($id)) {
            echo "<p>L'ID del post non può essere vuoto.</p>";
        } else {
            // Recupera i dettagli del post selezionato
            $stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ? AND author_id = ?");
            $stmt->bind_param("ii", $id, $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0) {
                $post = $result->fetch_assoc();
                ?>
                <form action="edit_post.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <label for="title">Titolo:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                    <br>
                    <label for="content">Contenuto:</label>
                    <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    <br>
                    <button type="submit" name="update">Aggiorna Post</button>
                </form>
                <?php
            } else {
                echo "<p>Post non trovato o non sei l'autore del post.</p>";
            }
        }
    }


    ?>    <a href="dashboard.php">Torna alla Dashboard</a>
</body>
</html>