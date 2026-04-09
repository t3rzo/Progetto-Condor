<?php
session_start(); // Avvio sessione essenziale per l'auto-login

// --- PARTE PHP (Motore) ---
$messaggio = ""; // Variabile per stampare errori o successi

if(isset($_POST['submit'])){
    
    // 1. Connessione al database
    $db = mysqli_connect("localhost", "root", "", "accedi_condor");

    if (!$db) {
        $messaggio = "<div style='background:red; color:white; padding:10px; text-align:center;'>❌ ERRORE CONNESSIONE: " . mysqli_connect_error() . "</div>";
    } else {
        
        // 2. Recupero dati (con protezione base)
        $utente = mysqli_real_escape_string($db, $_POST['utente']);
        $pass = $_POST['pass'];
        $pass2 = $_POST['pass2'];
        $nome = mysqli_real_escape_string($db, $_POST['nome']);
        $cognome = mysqli_real_escape_string($db, $_POST['cognome']);
        $data = $_POST['data'];
        $telefono = mysqli_real_escape_string($db, $_POST['telefono']);
        $email = mysqli_real_escape_string($db, $_POST['email']);

        // 3. Controllo campi vuoti
        if(empty($utente) || empty($pass) || empty($nome) || empty($cognome) || empty($email)) {
             $messaggio = "<div style='background:orange; color:black; padding:10px; text-align:center;'>⚠️ Compila tutti i campi obbligatori!</div>";
        } 
        // 4. Controllo Password
        else if($pass !== $pass2){
            $messaggio = "<div style='background:red; color:white; padding:10px; text-align:center;'>❌ Le password non coincidono!</div>";
        } 
        else {
            // 5. Query di inserimento
            $query = "INSERT INTO credenziali (utente, password, nome, cognome, data_nascita, telefono, email) 
                      VALUES ('$utente', '$pass', '$nome', '$cognome', '$data', '$telefono', '$email')";

            if(mysqli_query($db, $query)){
                
                // === AUTO LOGIN E CREAZIONE MESSAGGIO DI SUCCESSO ===
                $_SESSION['utente_loggato'] = $nome; // Salviamo il NOME nella sessione per l'header
                $registrazione_successo = true; // Variabile per far sparire il form sotto

                $messaggio = "<div style='background:green; color:white; padding:20px; text-align:center; border: 2px solid white; border-radius: 8px; margin-bottom: 20px; max-width: 500px; margin-left: auto; margin-right: auto;'>
                                <h2>✅ REGISTRAZIONE RIUSCITA!</h2>
                                <p>Ciao <b>$nome</b>, il tuo profilo è stato creato correttamente e sei già connesso.</p>
                                <br>
                                <a href='homepage.php' style='display:inline-block; padding: 12px 25px; background: white; color: green; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s;'>Vai alla tua Area Utente ➔</a>
                              </div>";
            } else {
                $messaggio = "<div style='background:red; color:white; padding:10px; text-align:center;'>
                                ❌ ERRORE MYSQL: " . mysqli_error($db) . "
                              </div>";
            }
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
    <title>ASD Condor - Registrazione</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="page-content" style="padding-top: 40px;">
    <?php echo $messaggio; ?>

    <?php if(!isset($registrazione_successo)): ?>
        <div class="form-container wide">
            <h2>Crea il tuo profilo</h2>
            
            <form action="registra.php" method="POST">
                
                <div class="input-group-row">
                    <div class="input-wrapper">
                        <label>Nome</label>
                        <input type="text" name="nome" required />
                    </div>
                    <div class="input-wrapper">
                        <label>Cognome</label>
                        <input type="text" name="cognome" required />
                    </div>
                </div>

                <div class="input-group-row">
                    <div class="input-wrapper">
                        <label>Data di Nascita</label>
                        <input type="date" name="data" required />
                    </div>
                    <div class="input-wrapper">
                        <label>Telefono</label>
                        <input type="text" name="telefono" required />
                    </div>
                </div>

                <div class="input-wrapper">
                    <label>Email</label>
                    <input type="email" name="email" required />
                </div>

                <div class="input-wrapper">
                    <label>Username</label>
                    <input type="text" name="utente" required />
                </div>

                <div class="input-group-row">
                    <div class="input-wrapper">
                        <label>Password</label>
                        <input type="password" name="pass" required />
                    </div>
                    <div class="input-wrapper">
                        <label>Ripeti Password</label>
                        <input type="password" name="pass2" required />
                    </div>
                </div>

                <input type="submit" value="Registrati Ora" name="submit" />
            </form>
            
            <div class="form-footer">
                Sei già iscritto? <a href="index.php">Accedi</a>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>