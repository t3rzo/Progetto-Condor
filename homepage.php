<?php
session_start();
// Controllo se l'utente ha fatto il login, altrimenti torna alla home
if(!isset($_SESSION['utente_loggato'])) {
    header("Location: index.php");
    exit;
}
$utente = $_SESSION['utente_loggato'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ASD Condor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-content column-layout">
    <div class="welcome-header">
        <h2>Bentornato, <span class="text-red"><?php echo htmlspecialchars($utente); ?></span>!</h2>
        <p>Questa è la tua area personale. Seleziona un'opzione per continuare.</p>
    </div>

    <div class="dashboard-grid">
        <a href="corsi.php" class="dash-box">
            <div class="dash-icon">🥋</div>
            <h3>Corsi e Turni</h3>
            <p>Visualizza gli atleti iscritti e gli orari dei vari turni agonisti e senior.</p>
        </a>

        <a href="gare.php" class="dash-box"> <div class="dash-icon">🏆</div>
            <h3>Gare ed Eventi</h3>
            <p>Consulta il calendario e iscriviti alle prossime competizioni.</p>
        </a>

        <a href="cambio.php?utente=<?php echo urlencode($utente); ?>" class="dash-box">
            <div class="dash-icon">⚙️</div>
            <h3>Gestione Profilo</h3>
            <p>Modifica le tue credenziali e aggiorna la password di accesso.</p>
        </a>
    </div>
</div>

</body>
</html>