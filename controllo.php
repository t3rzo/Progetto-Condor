<?php
require_once 'utils.php';

avviaSessione();

$utente = trim($_POST['utente'] ?? '');
$pass = $_POST['pass'] ?? '';
$titolo = 'Accesso non riuscito';
$messaggio = 'Username o password non validi.';
$link = 'index.php';
$linkTesto = 'Torna al login';

if ($utente !== '' && $pass !== '') {
    $db = connessioneDb();

    if ($db) {
        $utenteDb = mysqli_real_escape_string($db, $utente);
        $query = "SELECT utente, password FROM credenziali WHERE utente = '$utenteDb' LIMIT 1";
        $risultato = mysqli_query($db, $query);
        $riga = $risultato ? mysqli_fetch_assoc($risultato) : null;

        if ($riga && $riga['password'] === $pass) {
            $_SESSION['utente_loggato'] = $riga['utente'];
            header('Location: homepage.php');
            exit;
        }

        if (!$riga) {
            $messaggio = 'Utente non esistente.';
            $link = 'registra.php';
            $linkTesto = 'Registrati ora';
        }

        mysqli_close($db);
    } else {
        $messaggio = 'Connessione al database non riuscita.';
    }
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
    <section class="form-container access-card">
        <h2><?php echo e($titolo); ?></h2>
        <p><?php echo e($messaggio); ?></p>
        <div class="access-actions">
            <a href="<?php echo e($link); ?>" class="btn btn-primary"><?php echo e($linkTesto); ?></a>
        </div>
    </section>
</main>

</body>
</html>
