<?php
require_once 'utils.php';

richiediLogin();

$db = connessioneDb();
$id_gara = (int) ($_GET['id_gara'] ?? 0);
$iscritti = [];

if ($db) {
    $risultato = mysqli_query($db, "SELECT gi.id, a.nome, a.cognome, gi.allenatore FROM gara_iscrizioni gi LEFT JOIN atleti a ON gi.numero_tesseramento = a.numero_tesseramento WHERE gi.id_gara = $id_gara ORDER BY a.cognome ASC, a.nome ASC");

    if ($risultato) {
        while ($riga = mysqli_fetch_assoc($risultato)) {
            $iscritti[] = $riga;
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
    <title>Iscritti Gara - ASD Condor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="page-content column-layout animated-page">
    <header class="corsi-header">
        <h2>Atleti iscritti alla gara #<?php echo e($id_gara); ?></h2>
    </header>

    <section class="gare-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Atleta</th>
                    <th>Allenatore</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($iscritti)): ?>
                    <tr>
                        <td colspan="3" class="empty-msg">Nessun atleta iscritto a questa gara.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($iscritti as $row): ?>
                        <tr>
                            <td><?php echo e(trim($row['cognome'] . ' ' . $row['nome'])); ?></td>
                            <td><?php echo e($row['allenatore']); ?></td>
                            <td>
                                <a href="annulla_iscrizione.php?id=<?php echo e($row['id']); ?>&id_gara=<?php echo e($id_gara); ?>" onclick="return confirm('Vuoi davvero annullare questa iscrizione?')" class="btn-link text-red">Annulla</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <div class="page-back">
        <a href="gare.php" class="btn-link">Torna alle gare</a>
    </div>
</main>

</body>
</html>
