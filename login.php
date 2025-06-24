<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Benvenuto nel Blog</h1>
    </header>
<form action="login.php" method="POST">
     <h2>Accedi</h2>
     <label for="username">Username:</label>
     <input type="text" id="username" name="username" required>
     <br>
     <label for="password">Password:</label>
     <input type="password" id="password" name="password" required>
     <br>
     <button type="submit">Login</button>
    <p>Non hai un account? <a href="register.php">Registrati</a></p>
</form>
</body>
</html>


<?php 
 //questo è il login.php 

 require_once 'inc/db.php';
 
 session_start();
 if($_SERVER['REQUEST_METHOD']=="POST"){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);



    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Controlliamo se l'utente esiste 
    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "Password inserita: " . $password . "<br>";
        echo "Hash nel database: " . $user['password'] . "<br>";


        //verifichiamo la password
        if(password_verify($password, $user['password'])) {
            // Password corretta, impostiamo la sessione
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php"); // reindirizza a index.php
            exit;
        } else {
            echo "Password errata. Riprova.";
        }
    } else {
        echo "Utente non trovato. Riprova.";
    }
        $stmt->close();
        $conn->close();
    }


?>