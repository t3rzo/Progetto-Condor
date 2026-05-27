<?php
require_once 'utils.php';

richiediLogin();

$messaggio = '';
$tipo_messaggio = 'error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = connessioneDb();

    if ($db) {
        $nome = mysqli_real_escape_string($db, trim($_POST['nome'] ?? ''));
        $cognome = mysqli_real_escape_string($db, trim($_POST['cognome'] ?? ''));
        $turno = mysqli_real_escape_string($db, trim($_POST['turno'] ?? ''));
        $grado_cintura = mysqli_real_escape_string($db, trim($_POST['grado_cintura'] ?? ''));

        if ($nome === '' || $cognome === '' || $turno === '' || $grado_cintura === '') {
            $messaggio = 'Compila tutti i campi obbligatori.';
        } else {
            $numero_tesseramento = substr(time(), -5) . rand(100, 999);

            $query = "INSERT INTO atleti (numero_tesseramento, nome, cognome, turno, grado_cintura)
                      VALUES ('$numero_tesseramento', '$nome', '$cognome', '$turno', '$grado_cintura')";

            if (mysqli_query($db, $query)) {
                $tipo_messaggio = 'success';
                $messaggio = 'Atleta aggiunto con successo! Tesseramento: ' . $numero_tesseramento;
            } else {
                $messaggio = 'Errore nell\'inserimento. Riprova.';
            }
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
    <title>Aggiungi Atleta - ASD Condor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="page-content column-layout animated-page">
    <section class="form-container">
        <?php if ($messaggio): ?>
            <div class="alert alert-<?php echo e($tipo_messaggio); ?>">
                <p><?php echo e($messaggio); ?></p>
            </div>
        <?php endif; ?>
        <div class="access-actions">
            <a href="corsi.php" class="btn btn-primary">Torna ai corsi</a>
        </div>
    </section>
</main>

</body>
</html>
