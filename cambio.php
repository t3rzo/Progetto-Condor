<?php
session_start();
$messaggio = "";

// Recuperiamo l'utente dall'URL (es. cambio.php?utente=Mario) o dalla sessione
$utente_corrente = isset($_GET['utente']) ? $_GET['utente'] : (isset($_SESSION['utente_loggato']) ? $_SESSION['utente_loggato'] : '');

// Se viene premuto il bottone di aggiornamento
if(isset($_POST['aggiorna'])){
    $db = mysqli_connect("localhost", "root", "", "accedi_condor");

    if (!$db) {
        $messaggio = "<div style='background:red; color:white; padding:10px; text-align:center;'>❌ ERRORE CONNESSIONE: " . mysqli_connect_error() . "</div>";
    } else {
        $utente = mysqli_real_escape_string($db, $_POST['utente']);
        $vecchia_pass = $_POST['vecchia_pass'];
        $nuova_pass = $_POST['nuova_pass'];
        $conferma_pass = $_POST['conferma_pass'];

        // 1. Controlliamo se la vecchia password è corretta
        $query_check = "SELECT password FROM credenziali WHERE utente = '$utente'";
        $risultato = mysqli_query($db, $query_check);
        $riga = mysqli_fetch_assoc($risultato);

        if($riga && $riga['password'] === $vecchia_pass) {
            
            // 2. Controlliamo se le due nuove password coincidono
            if($nuova_pass === $conferma_pass) {
                
                // 3. Aggiorniamo la password nel database
                $query_update = "UPDATE credenziali SET password = '$nuova_pass' WHERE utente = '$utente'";
                if(mysqli_query($db, $query_update)){
                    $messaggio = "<div style='background:green; color:white; padding:15px; text-align:center; border: 2px solid white;'>
                                    <h2>✅ PASSWORD AGGIORNATA!</h2>
                                    <p>La tua password è stata cambiata con successo.</p>
                                  </div>";
                } else {
                    $messaggio = "<div style='background:red; color:white; padding:10px; text-align:center;'>❌ ERRORE MYSQL: " . mysqli_error($db) . "</div>";
                }
            } else {
                $messaggio = "<div style='background:orange; color:black; padding:10px; text-align:center;'>⚠️ Le nuove password non coincidono!</div>";
            }
        } else {
            $messaggio = "<div style='background:red; color:white; padding:10px; text-align:center;'>❌ La vecchia password è errata!</div>";
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
    <title>ASD Condor - Cambio Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<?php echo $messaggio; ?>

<div class="page-content">
    <div class="form-container">
        <h2>Gestione Sicurezza</h2>
        
        <form action="cambio.php" method="POST">
            
            <input type="hidden" name="utente" value="<?php echo htmlspecialchars($utente_corrente); ?>">

            <div class="input-wrapper">
                <label>Vecchia Password</label>
                <input type="password" name="vecchia_pass" required />
            </div>
            
            <div class="input-wrapper">
                <label>Nuova Password</label>
                <input type="password" name="nuova_pass" required />
            </div>

            <div class="input-wrapper">
                <label>Conferma Nuova Password</label>
                <input type="password" name="conferma_pass" required />
            </div>
            
            <input type="submit" value="Aggiorna Password" name="aggiorna" />
        </form>

        <div class="form-footer">
            <a href="homepage.php">⬅ Torna alla Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>