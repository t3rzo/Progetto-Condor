<?php
session_start(); // Richiama la sessione attuale
session_destroy(); // DISTRUGGE il "timbro" e tutti i dati salvati
header("Location: index.php"); // Ti riporta alla pagina di login
exit;
?>