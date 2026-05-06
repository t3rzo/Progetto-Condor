<?php
session_start();
// Controllo se l'utente ha fatto il login
if(!isset($_SESSION['utente_loggato'])) {
    header("Location: index.php");
    exit;
}
$utente = $_SESSION['utente_loggato'];

// --- MAGIA DELL'API SIMULATA ---
// 1. Il server "legge" i dati dal file esterno
$dati_json = file_get_contents('gare.json');

// 2. Li decodifica in un formato leggibile da PHP (un Array)
$elenco_gare = json_decode($dati_json, true);
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

<div class="page-content column-layout">
    <div class="corsi-header">
        <h2>Gare ed Eventi Ufficiali</h2>
        <p>Ecco l'elenco delle prossime competizioni. Scegli la tua gara e iscriviti!</p>
    </div>

    <div class="gare-container" style="display: flex; flex-direction: column; gap: 15px; max-width: 800px; margin: 0 auto;">
        
        <?php foreach($elenco_gare as $gara): ?>
            <div class="gara-card" style="background-color: #1a1a1a; border: 1px solid #333; border-left: 5px solid #d32f2f; border-radius: 8px; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                
                <div class="gara-info">
                    <h3 style="margin-top: 0; color: #fff;"><?php echo htmlspecialchars($gara['titolo']); ?></h3>
                    <p style="margin: 5px 0; color: #ccc;">📅 <strong>Data:</strong> <?php echo htmlspecialchars($gara['data']); ?></p>
                    <p style="margin: 5px 0; color: #ccc;">📍 <strong>Luogo:</strong> <?php echo htmlspecialchars($gara['luogo']); ?></p>
                    <p style="margin: 5px 0; color: #ccc;">🥋 <strong>Specialità:</strong> <?php echo htmlspecialchars($gara['specialita']); ?></p>
                </div>
                
                <div class="gara-action" style="display: flex; flex-direction: column; gap: 10px; min-width: 150px;">
                    <a href="iscriviti.php?id_gara=<?php echo $gara['id_gara']; ?>" style="background-color: #d32f2f; color: white; padding: 10px; text-decoration: none; border-radius: 5px; font-weight: bold; text-align: center; transition: 0.3s;">
                        Iscrivi Atleti
                    </a>
                    <a href="visualizza_iscritti.php?id_gara=<?php echo $gara['id_gara']; ?>" style="border: 1px solid #555; color: #ccc; padding: 8px; text-decoration: none; border-radius: 5px; font-size: 0.9em; text-align: center; transition: 0.3s;">
                        Visualizza Iscritti
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="homepage.php" style="color: #aaa; text-decoration: none;">&larr; Torna alla Dashboard</a>
    </div>
</div>

</body>
</html>