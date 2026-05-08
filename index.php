<?php
require_once 'utils.php';

avviaSessione();

if (utenteLoggato()) {
    header('Location: homepage.php');
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

<main class="page-content">
    <section class="form-container">
        <h2>Accesso area riservata</h2>

        <form action="controllo.php" method="POST">
            <div class="input-wrapper">
                <label>Username</label>
                <input type="text" name="utente" required>
            </div>

            <div class="input-wrapper">
                <label>Password</label>
                <input type="password" name="pass" required>
            </div>

            <input type="submit" value="Accedi">
        </form>

        <div class="form-footer">
            Non hai un account? <a href="registra.php">Registrati</a>
        </div>
    </section>
</main>

</body>
</html>
