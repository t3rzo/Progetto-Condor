<?php
require_once 'utils.php';

avviaSessione();
$is_logged = utenteLoggato();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASD Condor - Contatti</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<?php if (!$is_logged): ?>
    <?php mostraAccessoRiservato("Devi effettuare l'accesso per visualizzare i nostri contatti."); ?>
<?php else: ?>
    <main class="page-content contact-container">
        <header class="contact-header">
            <h1>Vieni a trovarci</h1>
            <p>Siamo a disposizione per informazioni sui corsi e sulle iscrizioni.</p>
        </header>

        <section class="contact-info-box">
            <div class="contact-item">
                <div class="contact-icon">A</div>
                <div class="contact-subtext">Indirizzo palestra</div>
                <div class="contact-text">Via Antonio Mosca 13<br>Casoria (NA), Italy</div>
            </div>

            <hr class="divider">

            <div class="contact-item">
                <div class="contact-icon">T</div>
                <div class="contact-subtext">Telefono</div>
                <div class="contact-text">081 757 5142</div>
            </div>
        </section>

        <div class="map-placeholder">
            <iframe src="https://maps.google.com/maps?q=Via%20Antonio%20Mosca%2013,%20Casoria&t=&z=16&ie=UTF8&iwloc=&output=embed" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </main>
<?php endif; ?>

</body>
</html>
