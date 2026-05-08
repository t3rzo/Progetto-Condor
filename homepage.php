<?php
require_once 'utils.php';

richiediLogin();
$utente = utenteCorrente();
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
        <div id="timer">Caricamento countdown...</div>
        <p>Atleti pronti: <strong>Marco R., Sofia L., Alessio V.</strong></p>
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
const targetDate = Date.now() + 10 * 24 * 60 * 60 * 1000;

function updateCountdown() {
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

updateCountdown();
setInterval(updateCountdown, 1000);
</script>

</body>
</html>
