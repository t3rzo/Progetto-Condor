<?php
require_once 'utils.php';

avviaSessione();
$is_logged = utenteLoggato();
$turni = [
    '1' => [
        'titolo' => '1&deg; Turno',
        'badge' => 'Kids / Cadetti',
        'orario' => 'Luned&igrave;, Mercoled&igrave;, Venerd&igrave; | 17:00 - 18:30',
        'elite' => false,
        'atleti' => []
    ],
    '2' => [
        'titolo' => '2&deg; Turno',
        'badge' => 'Cadetti / Junior',
        'orario' => 'Luned&igrave;, Mercoled&igrave;, Venerd&igrave; | 18:30 - 20:00',
        'elite' => false,
        'atleti' => []
    ],
    '3' => [
        'titolo' => '3&deg; Turno',
        'badge' => 'Senior',
        'orario' => 'Luned&igrave;, Mercoled&igrave;, Venerd&igrave; | 20:00 - 21:30',
        'elite' => false,
        'atleti' => []
    ],
    '4' => [
        'titolo' => 'Allenatori/Tecnici',
        'badge' => 'Maestro',
        'orario' => '',
        'elite' => true,
        'atleti' => []
    ]
];

function getAtletiByTurno($db, $turno) {
    $turno = mysqli_real_escape_string($db, $turno);
    $query = "SELECT nome, cognome, numero_tesseramento, grado_cintura FROM atleti_corsi WHERE turno = '$turno' ORDER BY cognome ASC";
    $risultato = mysqli_query($db, $query);
    $atleti = [];

    if ($risultato) {
        while ($riga = mysqli_fetch_assoc($risultato)) {
            $atleti[] = $riga;
        }
    }

    return $atleti;
}

function classeCintura($grado) {
    $grado = strtolower($grado ?? '');

    if (strpos($grado, 'bianca') !== false) return 'belt-bianca';
    if (strpos($grado, 'gialla') !== false) return 'belt-gialla';
    if (strpos($grado, 'verde') !== false) return 'belt-verde';
    if (strpos($grado, 'blu') !== false) return 'belt-blu';
    if (strpos($grado, 'rossa') !== false) return 'belt-rossa';
    if (strpos($grado, 'nera') !== false) return 'belt-nera';

    return 'belt-default';
}

if ($is_logged) {
    $db = connessioneDb();

    if ($db) {
        foreach ($turni as $numero => $turno) {
            $turni[$numero]['atleti'] = getAtletiByTurno($db, $numero);
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
    <title>ASD Condor - Corsi e Turni</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<?php if (!$is_logged): ?>
    <?php mostraAccessoRiservato("Devi effettuare l'accesso per visualizzare l'elenco dei corsi e degli atleti iscritti."); ?>
<?php else: ?>
    <main class="page-content column-layout animated-page">
        <header class="corsi-header">
            <h2>Corsi agonistici e turni</h2>
            <p>Seleziona un corso per visualizzare gli atleti iscritti</p>
        </header>

        <section class="turni-container">
            <?php foreach ($turni as $turno): ?>
                <article class="turno-card" onclick="toggleCard(this)">
                    <div class="turno-header">
                        <div class="turno-info">
                            <h3>
                                <?php echo $turno['titolo']; ?>
                                <span class="badge <?php echo $turno['elite'] ? 'elite' : ''; ?>"><?php echo e($turno['badge']); ?></span>
                            </h3>
                            <?php if ($turno['orario'] !== ''): ?>
                                <p class="orario"><?php echo $turno['orario']; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="turno-icon">v</div>
                    </div>

                    <div class="turno-content-wrapper">
                        <div class="turno-content">
                            <table class="atleti-table">
                                <thead>
                                    <tr>
                                        <th>Atleta</th>
                                        <th>Cintura</th>
                                        <th>Tesseramento FITA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($turno['atleti'])): ?>
                                        <tr>
                                            <td colspan="3" class="empty-msg">Nessun atleta iscritto a questo turno.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($turno['atleti'] as $atleta): ?>
                                            <tr>
                                                <td><?php echo e($atleta['cognome'] . ' ' . $atleta['nome']); ?></td>
                                                <td>
                                                    <span class="belt-dot <?php echo classeCintura($atleta['grado_cintura']); ?>"></span>
                                                    <strong><?php echo e($atleta['grado_cintura'] ?: '-'); ?></strong>
                                                </td>
                                                <td><span class="tessera-id"><?php echo e($atleta['numero_tesseramento']); ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>

    <script>
    function toggleCard(cardElement) {
        cardElement.classList.toggle('active');
    }
    </script>
<?php endif; ?>

</body>
</html>
