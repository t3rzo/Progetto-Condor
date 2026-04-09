<?php
session_start();
// Se l'utente ha già fatto il login, lo cacciamo via dal form e lo mandiamo in homepage!
if(isset($_SESSION['utente_loggato'])){
    header("Location: homepage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASD Condor - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-content">
    <div class="form-container">
        <h2>Accesso Area Riservata</h2>
        
        <form action="controllo.php" method="POST">
            <div class="input-wrapper">
                <label>Username</label>
                <input type="text" name="utente" required />
            </div>
            
            <div class="input-wrapper">
                <label>Password</label>
                <input type="password" name="pass" required />
            </div>
            
            <input type="submit" value="Accedi" />
        </form>

        <div class="form-footer">
            Non hai un account? <a href="registra.php">Registrati</a>
        </div>
    </div>
</div>

</body>
</html>