<?php
require_once 'utils.php';

richiediLogin();

$dati_json = file_get_contents('gare.json');
$elenco_gare = json_decode($dati_json, true) ?: [];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gare ed Eventi - ASD Condor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="page-content column-layout fade-in">
    <header class="corsi-header">
        <h2>Gare ed eventi ufficiali</h2>
        <p>Ecco l'elenco delle prossime competizioni. Scegli la tua gara e iscriviti.</p>
    </header>

    <section class="gare-container">
        <?php foreach ($elenco_gare as $gara): ?>
            <article class="gara-card">
                <div class="gara-info">
                    <h3><?php echo e($gara['titolo']); ?></h3>
                    <p><strong>Data:</strong> <?php echo e($gara['data']); ?></p>
                    <p><strong>Luogo:</strong> <?php echo e($gara['luogo']); ?></p>
                    <p><strong>Specialit&agrave;:</strong> <?php echo e($gara['specialita']); ?></p>
                </div>

                <div class="gara-action">
                    <a href="iscriviti.php?id_gara=<?php echo e($gara['id_gara']); ?>" class="btn btn-red">Iscrivi atleti</a>
                    <a href="visualizza_iscritti.php?id_gara=<?php echo e($gara['id_gara']); ?>" class="btn btn-secondary">Visualizza iscritti</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>

    <div class="page-back">
        <a href="homepage.php" class="btn-link">Torna alla dashboard</a>
    </div>
</main>

</body>
</html>
