<?php
require_once 'utils.php';

richiediLogin();

$messaggio = '';
$utente_corrente = utenteCorrente();

if (isset($_POST['aggiorna'])) {
    $db = connessioneDb();

    if (!$db) {
        $messaggio = '<div class="alert alert-error">Connessione al database non riuscita.</div>';
    } else {
        $utente = mysqli_real_escape_string($db, $utente_corrente);
        $vecchia_pass = $_POST['vecchia_pass'] ?? '';
        $nuova_pass_originale = $_POST['nuova_pass'] ?? '';
        $nuova_pass = mysqli_real_escape_string($db, $nuova_pass_originale);
        $conferma_pass = $_POST['conferma_pass'] ?? '';

        $query_check = "SELECT password FROM credenziali WHERE utente = '$utente' LIMIT 1";
        $risultato = mysqli_query($db, $query_check);
        $riga = $risultato ? mysqli_fetch_assoc($risultato) : null;

        if (!$riga || $riga['password'] !== $vecchia_pass) {
            $messaggio = '<div class="alert alert-error">La vecchia password non &egrave; corretta.</div>';
        } elseif ($nuova_pass_originale !== $conferma_pass) {
            $messaggio = '<div class="alert alert-warning">Le nuove password non coincidono.</div>';
        } else {
            $query_update = "UPDATE credenziali SET password = '$nuova_pass' WHERE utente = '$utente'";
            $messaggio = mysqli_query($db, $query_update)
                ? '<div class="alert alert-success">Password aggiornata correttamente.</div>'
                : '<div class="alert alert-error">Aggiornamento non riuscito.</div>';
        }

        mysqli_close($db);
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASD Condor - Cambio Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="page-content">
    <section class="form-container">
        <?php echo $messaggio; ?>
        <h2>Gestione sicurezza</h2>

        <form action="cambio.php" method="POST">
            <div class="input-wrapper">
                <label>Vecchia password</label>
                <input type="password" name="vecchia_pass" required>
            </div>

            <div class="input-wrapper">
                <label>Nuova password</label>
                <input type="password" name="nuova_pass" required>
            </div>

            <div class="input-wrapper">
                <label>Conferma nuova password</label>
                <input type="password" name="conferma_pass" required>
            </div>

            <input type="submit" value="Aggiorna password" name="aggiorna">
        </form>

        <div class="form-footer">
            <a href="homepage.php">Torna alla dashboard</a>
        </div>
    </section>
</main>

</body>
</html>
