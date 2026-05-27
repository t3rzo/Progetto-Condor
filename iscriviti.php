<?php
require_once 'utils.php';

richiediLogin();

$db = connessioneDb();
$id_gara = (int) ($_GET['id_gara'] ?? 0);
$atleti = [];

if ($db) {
    $query = "SELECT nome, cognome, numero_tesseramento FROM atleti ORDER BY cognome ASC";
    $risultato = mysqli_query($db, $query);

    if ($risultato) {
        while ($riga = mysqli_fetch_assoc($risultato)) {
            $atleti[] = $riga;
        }
    }

    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iscrivi Atleti - ASD Condor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="page-content">
    <section class="form-container">
        <h2>Seleziona atleti</h2>

        <form action="salva_iscrizione.php" method="POST">
            <input type="hidden" name="id_gara" value="<?php echo e($id_gara); ?>">

            <label>Atleti disponibili</label>
            <div class="list-box">
                <?php foreach ($atleti as $atleta): ?>
                    <?php $nome_completo = $atleta['cognome'] . ' ' . $atleta['nome']; ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="atleti[]" value="<?php echo e($atleta['numero_tesseramento']); ?>">
                        <?php echo e($nome_completo); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="input-wrapper">
                <label>Allenatore responsabile</label>
                <input type="text" name="allenatore" placeholder="Nome dell'istruttore" required>
            </div>

            <button type="submit" class="btn-red">Conferma iscrizione</button>
        </form>
    </section>
</main>

</body>
</html>
