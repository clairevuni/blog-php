

<form action="register.php" method="POST">
    <h2>Registrati!</h2>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit"> Registrati </button>

</form>


<?php 
 
 require_once 'inc/db.php';

 if($_SERVER['REQUEST_METHOD'] == 'POST') {
     // Sanitize and validate input
     $username = trim($_POST['username']);
     $password = trim($_POST['password']);

     if(empty($username) || empty($password)) {
         echo "Username and password cannot be empty.";
     } else {
         // Hash the password
         $hashed_password = password_hash($password, PASSWORD_DEFAULT);

         // Prepare and bind
         $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
         $stmt->bind_param("ss", $username, $hashed_password);

         // Execute the statement
         if($stmt->execute()) {
             echo "Registration successful!";
             // si va a login.php
                header("Location: login.php");
         } else {
             echo "Error: " . $stmt->error;
         }

         // Close the statement
         $stmt->close();
     }
        // Close the connection
        $conn->close();
 }



?>