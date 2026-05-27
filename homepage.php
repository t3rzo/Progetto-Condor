<?php
require_once 'utils.php';

richiediLogin();
$utente = utenteCorrente();

function condorPeriodoGara($dataGara) {
    $mesi = [
        'gennaio' => '01',
        'febbraio' => '02',
        'marzo' => '03',
        'aprile' => '04',
        'maggio' => '05',
        'giugno' => '06',
        'luglio' => '07',
        'agosto' => '08',
        'settembre' => '09',
        'ottobre' => '10',
        'novembre' => '11',
        'dicembre' => '12'
    ];

    if (!preg_match('/(\d{1,2})(?:\s*-\s*(\d{1,2}))?\s+([a-z]+)\s+(\d{4})/i', strtolower($dataGara), $parti)) {
        return null;
    }

    $mese = $mesi[$parti[3]] ?? null;
    if (!$mese) {
        return null;
    }

    $giornoInizio = (int) $parti[1];
    $giornoFine = isset($parti[2]) && $parti[2] !== '' ? (int) $parti[2] : $giornoInizio;
    $anno = (int) $parti[4];

    return [
        'inizio' => DateTime::createFromFormat('!Y-m-d', sprintf('%04d-%02d-%02d', $anno, (int) $mese, $giornoInizio)),
        'fine' => DateTime::createFromFormat('!Y-m-d', sprintf('%04d-%02d-%02d', $anno, (int) $mese, $giornoFine))
    ];
}

function condorProssimaGara($gare) {
    $oggi = new DateTime('today');
    $prossima = null;

    foreach ($gare as $gara) {
        $periodo = condorPeriodoGara($gara['data'] ?? '');

        if (!$periodo || !$periodo['inizio'] || !$periodo['fine'] || $periodo['fine'] < $oggi) {
            continue;
        }

        if (!$prossima || $periodo['inizio'] < $prossima['data_inizio']) {
            $gara['data_inizio'] = $periodo['inizio'];
            $gara['data_fine'] = $periodo['fine'];
            $prossima = $gara;
        }
    }

    return $prossima;
}

function condorAtletiIscrittiGara($db, $idGara) {
    $atleti = [];
    $idGara = (int) $idGara;
    $query = "SELECT DISTINCT a.cognome, a.nome FROM gara_iscrizioni gi "
           . "INNER JOIN atleti a ON gi.numero_tesseramento = a.numero_tesseramento "
           . "WHERE gi.id_gara = $idGara ORDER BY a.cognome ASC, a.nome ASC";
    $risultato = mysqli_query($db, $query);

    if ($risultato) {
        while ($riga = mysqli_fetch_assoc($risultato)) {
            $nome = $riga['cognome'] . ' ' . $riga['nome'];
            $atleti[] = $nome;
        }
    }

    return $atleti;
}

$db = connessioneDb();
$elencoGare = [];
$prossimaGara = null;
$atletiPronti = [];

if ($db) {
    $risultato = mysqli_query($db, "SELECT id_gara, titolo, `data`, luogo, specialita FROM gare");

    if ($risultato) {
        while ($riga = mysqli_fetch_assoc($risultato)) {
            $elencoGare[] = $riga;
        }
    }

    mysqli_close($db);
}

$prossimaGara = condorProssimaGara($elencoGare);

if ($prossimaGara) {
    $db = connessioneDb();

    if ($db) {
        $atletiPronti = condorAtletiIscrittiGara($db, $prossimaGara['id_gara']);
        mysqli_close($db);
    }
}

$targetCountdown = $prossimaGara ? $prossimaGara['data_inizio']->format('c') : '';
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

<main class="page-content column-layout dashboard-page fade-in">
    <header class="welcome-header">
        <h2>Bentornato, <span class="text-red"><?php echo e($utente); ?></span>!</h2>
        <p>Pronto per l'allenamento di oggi?</p>
    </header>

    <section class="gym-intro">
        <div class="condor-wrapper">
            <div class="condor-media">
                <video class="condor-video" controls preload="metadata">
                    <source src="videos/Video Condor.mp4" type="video/mp4">
                    Il tuo browser non supporta il video.
                </video>
            </div>

            <div class="condor-text">
                <h2>ASD Condor</h2>
                <p>La nostra palestra non &egrave; solo un luogo di allenamento, ma una famiglia. Qui si costruiscono carattere e tecnica sotto la guida del maestro <strong>Pacifico Laezza</strong>.</p>
                <p class="quote">"Il sudore di oggi &egrave; il successo di domani."</p>
            </div>
        </div>
    </section>

    <section class="live-updates">
        <h3>Prossima gara tra:</h3>
        <?php if ($prossimaGara): ?>
            <p><strong><?php echo e($prossimaGara['titolo']); ?></strong></p>
            <div id="timer">Caricamento countdown...</div>
            <p>
                Atleti pronti:
                <strong>
                    <?php echo empty($atletiPronti) ? 'Nessun atleta iscritto a questa gara.' : e(implode(', ', $atletiPronti)); ?>
                </strong>
            </p>
        <?php else: ?>
            <div id="timer">Nessuna gara programmata.</div>
            <p>Atleti pronti: <strong>Nessuna gara selezionata.</strong></p>
        <?php endif; ?>
    </section>

    <section class="dashboard-grid" aria-label="Sezioni principali">
        <a href="corsi.php" class="dash-box">
            <div class="dash-icon">C</div>
            <h3>Corsi e turni</h3>
            <p>Visualizza gli atleti iscritti e gli orari dei vari turni.</p>
        </a>

        <a href="gare.php" class="dash-box">
            <div class="dash-icon">G</div>
            <h3>Gare ed eventi</h3>
            <p>Consulta il calendario e iscriviti alle prossime competizioni.</p>
        </a>

        <a href="cambio.php" class="dash-box">
            <div class="dash-icon">P</div>
            <h3>Profilo</h3>
            <p>Modifica le credenziali e aggiorna la password.</p>
        </a>
    </section>

    <h2 class="section-title">Bacheca dei traguardi</h2>

    <section class="trophy-wall" aria-label="Traguardi ASD Condor">
        <article class="trophy-item">
            <div class="trophy-medal">1</div>
            <h4>Regionali 2024</h4>
            <p>1&deg; posto squadra</p>
        </article>

        <article class="trophy-item">
            <div class="trophy-medal">2</div>
            <h4>Coppa Italia</h4>
            <p>Miglior atleta junior</p>
        </article>

        <article class="trophy-item">
            <div class="trophy-medal">3</div>
            <h4>Trofeo Condor</h4>
            <p>Evento sociale</p>
        </article>
    </section>
</main>

<script>
const timer = document.getElementById('timer');
const targetDate = <?php echo $targetCountdown ? 'new Date(' . json_encode($targetCountdown) . ').getTime()' : 'null'; ?>;

function updateCountdown() {
    if (!targetDate) {
        return;
    }

    const distance = targetDate - Date.now();

    if (distance <= 0) {
        timer.textContent = 'GARA IN CORSO!';
        return;
    }

    const days = Math.floor(distance / 86400000);
    const hours = Math.floor((distance % 86400000) / 3600000);
    const minutes = Math.floor((distance % 3600000) / 60000);
    const seconds = Math.floor((distance % 60000) / 1000);

    timer.textContent = `${days}g ${hours}o ${minutes}m ${seconds}s`;
}

if (targetDate) {
    updateCountdown();
    setInterval(updateCountdown, 1000);
}
</script>

</body>
</html>
