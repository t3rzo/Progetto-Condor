<?php
session_start();
$is_logged = isset($_SESSION['utente_loggato']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASD Condor - Contatti</title>
    <link rel="stylesheet" href="css/style.css"> 
    <style>
        /* CSS BLINDATO: Forziamo il layout a colonna per battere il tuo style.css */
        .contact-container {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            width: 100% !important;
            padding-top: 50px;
        }
        
        .contact-header {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center;
            margin-bottom: 50px;
            width: 100%;
        }

        .contact-header h1 {
            color: #fff;
            font-size: 3.5em; 
            margin-bottom: 10px;
            display: block !important;
        }

        .contact-header p {
            color: #aaa;
            font-size: 1.5em;
            display: block !important;
        }

        .contact-info-box {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            background: #151515;
            border: 1px solid #333;
            border-top: 4px solid #d32f2f;
            border-radius: 8px;
            padding: 60px 40px;
            text-align: center;
            width: 90%;
            max-width: 800px; 
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.8);
        }

        .contact-item {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            margin: 30px 0;
            width: 100%;
        }

        .contact-icon {
            font-size: 4em; 
            margin-bottom: 15px;
        }

        .contact-subtext {
            font-size: 1.2em;
            color: #d32f2f;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .contact-text {
            font-size: 2.5em; 
            color: #ffffff;
            font-weight: bold;
            line-height: 1.4;
            text-align: center;
        }

        .map-placeholder {
            width: 90%;
            max-width: 800px; 
            height: 450px; 
            border: 2px solid #333; 
            background: #0a0a0a;
            border-radius: 8px;
            margin-bottom: 60px;
            padding: 0; 
            overflow: hidden; 
            display: flex !important; 
        }
        
        /* La mappa riempie il 100% dello spazio del contenitore */
        .map-placeholder iframe {
            width: 100% !important;
            height: 100% !important;
            border: 0 !important;
        }
        
        .divider {
            border: none;
            height: 1px;
            background: #333;
            width: 80%;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<?php if (!$is_logged): ?>
    <div class="page-content" style="animation: slideUpFade 0.8s ease-out forwards; display: flex; justify-content: center; align-items: center; min-height: 60vh;">
        <div class="form-container" style="text-align: center;">
            <h2 style="color: #d32f2f;">⚠️ Accesso Riservato</h2>
            <p style="margin-bottom: 25px; color: #ccc;">Devi effettuare l'accesso per visualizzare i nostri contatti.</p>
            
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="index.php" style="padding: 10px 20px; background: #d32f2f; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s;">Accedi</a>
                <a href="registra.php" style="padding: 10px 20px; background: #333; color: white; border: 1px solid #555; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s;">Registrati</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="page-content contact-container" style="animation: slideUpFade 0.8s ease-out forwards;">
        
        <div class="contact-header">
            <h1>Vieni a Trovarci</h1>
            <p>Siamo sempre a disposizione per informazioni sui corsi e iscrizioni.</p>
        </div>

        <div class="contact-info-box">
            
            <div class="contact-item">
                <div class="contact-icon">📍</div>
                <div class="contact-subtext">Indirizzo della Palestra</div>
                <div class="contact-text">Via Antonio Mosca 13<br>Casoria (NA), Italy</div>
            </div>

            <hr class="divider">

            <div class="contact-item">
                <div class="contact-icon">📞</div>
                <div class="contact-subtext">Chiamaci Subito</div>
                <div class="contact-text">081 757 5142</div>
            </div>

        </div>

        <div class="map-placeholder">
            <iframe src="https://maps.google.com/maps?q=Via%20Antonio%20Mosca%2013,%20Casoria&t=&z=16&ie=UTF8&iwloc=&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

    </div>
<?php endif; ?>

</body>
</html>