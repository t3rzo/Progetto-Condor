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
    <title>ASD Condor - Chi Siamo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<?php if (!$is_logged): ?>
    <?php mostraAccessoRiservato("Devi effettuare l'accesso per visualizzare la pagina Chi siamo."); ?>
<?php else: ?>
    <main class="page-content about-container">
        <header class="about-header">
            <h1>La nostra storia</h1>
            <p>Siamo la ASD Condor, una famiglia unita dalla passione per le arti marziali e lo sport. Da anni trasmettiamo disciplina, rispetto e voglia di superare i propri limiti sul tatami e nella vita di tutti i giorni.</p>
        </header>

        <section class="social-container" aria-label="Social ASD Condor">
            <a href="https://www.facebook.com/asdcondor/?locale=it_IT" target="_blank" rel="noopener" class="btn-social bg-facebook">Facebook</a>
            <a href="https://www.instagram.com/asd_condor_tkd/" target="_blank" rel="noopener" class="btn-social bg-instagram">Instagram</a>
            <a href="https://www.tiktok.com/@a.s.d.condor" target="_blank" rel="noopener" class="btn-social bg-tiktok">TikTok</a>
        </section>

        <hr class="divider">

        <h2 class="team-title">Maestri</h2>

        <section class="team-grid" aria-label="Maestri ASD Condor">
            <article class="team-card">
                <div class="team-photo">
                    <img src="images/laezza_pacifico.png" alt="Pacifico Laezza">
                </div>
                <h3>Pacifico Laezza</h3>
                <p>Cintura nera - 7&deg; Dan</p>
                <div class="team-social">instagram.com/pacifico.laezza</div>
            </article>
        </section>
    </main>
<?php endif; ?>

</body>
</html>
