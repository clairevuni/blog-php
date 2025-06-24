<?php 

// logout.php
session_start();
// Distruggi la sessione per disconnettere l'utente
session_unset();
session_destroy();
header("Location: index.php"); // Reindirizza alla home page
exit;

?>