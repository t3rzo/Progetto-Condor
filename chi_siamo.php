<?php
session_start();
$is_logged = isset($_SESSION['utente_loggato']);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASD Condor - Chi Siamo</title>
    <link rel="stylesheet" href="css/style.css"> 
    <style>
        /* CSS BLINDATO: Forziamo il layout a colonna */
        .about-container {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            width: 100% !important;
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .about-header {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center;
            max-width: 800px;
            margin-bottom: 40px;
        }

        .about-header h1 {
            color: #fff;
            font-size: 3.5em; 
            margin-bottom: 20px;
        }

        .about-header p {
            color: #ccc;
            font-size: 1.3em;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* Stile per i bottoni Social dell'Associazione */
        .social-container {
            display: flex !important;
            flex-direction: row !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            gap: 20px;
            margin-bottom: 60px;
        }

        .btn-social {
            display: inline-block !important;
            padding: 15px 30px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: bold;
            transition: transform 0.3s, opacity 0.3s;
        }

        .btn-social:hover {
            transform: translateY(-5px);
            opacity: 0.9;
        }

        .bg-facebook { background-color: #1877F2; }
        .bg-instagram { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); }
        .bg-tiktok { background-color: #000000; border: 1px solid #333; }

        .divider {
            border: none;
            height: 1px;
            background: #333;
            width: 80%;
            margin: 20px auto 50px auto;
        }

        /* Griglia per i Maestri */
        .team-title {
            color: #d32f2f;
            font-size: 2.5em;
            margin-bottom: 40px;
            text-transform: uppercase;
        }

        .team-grid {
            display: flex !important;
            flex-direction: row !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            gap: 30px;
            width: 90%;
            max-width: 1000px;
        }

        .team-card {
            background: #151515;
            border: 1px solid #333;
            border-bottom: 4px solid #d32f2f;
            border-radius: 8px;
            padding: 30px 20px;
            text-align: center;
            width: 300px; /* Larghezza fissa per le card */
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }

        .team-photo {
            width: 120px;
            height: 120px;
            background: #333;
            border-radius: 50%;
            margin: 0 auto 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3em;
        }

        .team-card h3 {
            color: #fff;
            font-size: 1.8em;
            margin-bottom: 5px;
        }

        .team-card p {
            color: #aaa;
            font-size: 1.1em;
            margin-bottom: 15px;
            font-style: italic;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<?php if (!$is_logged): ?>
    <div class="page-content" style="animation: slideUpFade 0.8s ease-out forwards; display: flex; justify-content: center; align-items: center; min-height: 60vh;">
        <div class="form-container" style="text-align: center;">
            <h2 style="color: #d32f2f;">⚠️ Accesso Riservato</h2>
            <p style="margin-bottom: 25px; color: #ccc;">Devi effettuare l'accesso per visualizzare la pagina Chi Siamo.</p>
            
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="index.php" style="padding: 10px 20px; background: #d32f2f; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s;">Accedi</a>
                <a href="registra.php" style="padding: 10px 20px; background: #333; color: white; border: 1px solid #555; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s;">Registrati</a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="page-content about-container" style="animation: slideUpFade 0.8s ease-out forwards;">
        
        <div class="about-header">
            <h1>La Nostra Storia</h1>
            <p>Siamo la ASD Condor, una famiglia unita dalla passione per le arti marziali e lo sport. Da anni ci impegniamo a trasmettere i valori della disciplina, del rispetto e del superamento dei propri limiti sul tatami e nella vita di tutti i giorni.</p>
        </div>

        <div class="social-container">
            <a href="https://www.facebook.com/asdcondor/?locale=it_IT" target="_blank" class="btn-social bg-facebook">📘 Facebook</a>
            <a href="https://www.instagram.com/asd_condor_tkd/" target="_blank" class="btn-social bg-instagram">📸 Instagram</a>
            <a href="https://www.tiktok.com/@a.s.d.condor" target="_blank" class="btn-social bg-tiktok">🎵 TikTok</a>
        </div>

        <hr class="divider">

        <h2 class="team-title">Maestri</h2>
        
        <div class="team-grid">
            
            <div class="team-card">
                <div class="team-photo"><img src= images/laezza_pacifico.png></div> 
                <h3>Pacifico Laezza</h3>
                <p>Cintura Nera - 7°Dan</p>
                <div class="team-social">
                    <span style="color: #666; font-size: 0.9em;">https://www.instagram.com/pacifico.laezza
                    </span>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

</body>
</html>