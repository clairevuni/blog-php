<doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Post</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>
    <h1>Crea un Nuovo Post</h1>
    <form action="create_post.php" method="POST">
        <label for="title">Titolo:</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="content">Contenuto:</label>
        <textarea id="content" name="content" required></textarea>
        <br>
        <button type="submit">Pubblica</button>
    </form>

    <a href="dashboard.php">Torna alla Dashboard</a>

<?php
require_once '../inc/db.php';
session_start();

    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        if(empty($title) || empty($content)) {
            echo "<p>Il titolo e il contenuto non possono essere vuoti.</p>";
        } else {
            // Controlla che l'utente sia loggato
            if(!isset($_SESSION['user_id'])) {
                echo "<p>Errore: utente non autenticato.</p>";
            } else {
                $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $title, $content, $_SESSION['user_id']); // <-- correzione qui

                if($stmt->execute()) {
                    echo "<p>Post creato con successo!</p>";
                } else {
                    echo "<p>Errore: " . $stmt->error . "</p>";
                }

                $stmt->close();
            }
        }
    }
    
    $conn->close();
    ?>