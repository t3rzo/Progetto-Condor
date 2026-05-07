<?php
session_start();
if(!isset($_SESSION['utente_loggato'])) {
    header("Location: index.php");
    exit;
}
$utente = $_SESSION['utente_loggato'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ASD Condor</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Animazione Fade-in */
        @keyframes fadeInPage {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInPage 0.8s ease-out; }

        /* Sezione Video + Descrizione */
        .gym-intro {
            display: flex;
            gap: 30px;
            align-items: center;
            background: #1a1a1a;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
            border: 1px solid #333;
        }
        .video-box { flex: 1; border-radius: 10px; overflow: hidden; line-height: 0; }
        .text-box { flex: 1; }
        .text-box h2 { color: #d32f2f; margin-top: 0; }

        /* Sezione Countdown e Atleti */
        .live-updates {
            background: linear-gradient(45deg, #d32f2f, #8b0000);
            padding: 20px;
            border-radius: 15px;
            color: white;
            margin-bottom: 40px;
            text-align: center;
        }
        #timer { font-weight: bold; font-size: 1.5em; letter-spacing: 2px; }

        /* Bacheca Trofei */
        .trophy-wall {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 40px;
            text-align: center;
        }
        .trophy-item {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            border-bottom: 3px solid #d32f2f;
            transition: 0.3s;
        }
        .trophy-item:hover { transform: translateY(-5px); background: #252525; }
        .trophy-item img { width: 60px; filter: drop-shadow(0 0 5px gold); }

        /* Responsive */
        @media (max-width: 768px) {
            .gym-intro { flex-direction: column; }
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-content column-layout fade-in">
    
    <div class="welcome-header" style="text-align: center; margin-bottom: 40px;">
        <h2>Bentornato, <span class="text-red"><?php echo htmlspecialchars($utente); ?></span>!</h2>
        <p>Pronto per l'allenamento di oggi?</p>
    </div>

    <section class="gym-intro">
        <div class="video-box">
            <iframe width="50%" height="250" src="" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="text-box">
            <h2>ASD Condor</h2>
            <p>La nostra palestra non è solo un luogo di allenamento, ma una famiglia. Qui forgiate il carattere e la tecnica sotto la guida del maestro Pacifico Laezza.</p>
            <p><i>"Il sudore di oggi è il successo di domani."</i></p>
        </div>
    </section>

    <section class="live-updates">
        <h3>🚀 Prossima Gara tra:</h3>
        <div id="timer">Caricamento countdown...</div>
        <p style="margin-top: 10px; opacity: 0.9;">
            Atleti pronti: <strong>Marco R., Sofia L., Alessio V.</strong>
        </p>
    </section>

    <div class="dashboard-grid">
        <a href="corsi.php" class="dash-box">
            <div class="dash-icon">🥋</div>
            <h3>Corsi e Turni</h3>
            <p>Visualizza gli atleti iscritti e gli orari dei vari turni.</p>
        </a>

        <a href="gare.php" class="dash-box"> 
            <div class="dash-icon">🏆</div>
            <h3>Gare ed Eventi</h3>
            <p>Consulta il calendario e iscriviti alle prossime competizioni.</p>
        </a>

        <a href="cambio.php?utente=<?php echo urlencode($utente); ?>" class="dash-box">
            <div class="dash-icon">⚙️</div>
            <h3>Gestione Profilo</h3>
            <p>Modifica le tue credenziali e aggiorna la password.</p>
        </a>
    </div>

    <h2 style="text-align: center; margin-top: 60px;">🥇 Bacheca dei Traguardi</h2>
    <section class="trophy-wall">
        <div class="trophy-item">
            <img src="img/trofeo.png" alt="Trofeo"> <h4>Regionali 2024</h4>
            <p style="font-size: 0.8em; color: #aaa;">1° Posto Squadra</p>
        </div>
        <div class="trophy-item">
            <img src="img/trofeo.png" alt="Trofeo">
            <h4>Coppa Italia</h4>
            <p style="font-size: 0.8em; color: #aaa;">Miglior Atleta Junior</p>
        </div>
        <div class="trophy-item">
            <img src="img/trofeo.png" alt="Trofeo">
            <h4>Trofeo Condor</h4>
            <p style="font-size: 0.8em; color: #aaa;">Evento Sociale</p>
        </div>
    </section>

</div>

<script>
    // Semplice Timer per la prossima gara (Esempio: tra 10 giorni)
    const countDownDate = new Date().getTime() + (10 * 24 * 60 * 60 * 1000);

    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = countDownDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML = days + "g " + hours + "o " + minutes + "m " + seconds + "s ";

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "GARA IN CORSO!";
        }
    }, 1000);
</script>


</body>
</html>