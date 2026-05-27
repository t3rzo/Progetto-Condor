<?php
require_once 'utils.php';

avviaSessione();

$messaggio = '';
$registrazione_successo = false;

if (isset($_POST['submit'])) {
 $db = connessioneDb();

 if (!$db) {
  $messaggio = '<div class="alert alert-error">Connessione al database non riuscita.</div>';
} else {
   $utente = mysqli_real_escape_string($db, trim($_POST['utente'] ?? ''));
    $pass = $_POST['pass'] ?? '';
    $pass2 = $_POST['pass2'] ?? '';
    $nome = mysqli_real_escape_string($db, trim($_POST['nome'] ?? ''));
    $cognome = mysqli_real_escape_string($db, trim($_POST['cognome'] ?? ''));
     $data = mysqli_real_escape_string($db, $_POST['data'] ?? '');
     $telefono = mysqli_real_escape_string($db, trim($_POST['telefono'] ?? ''));
     $email = mysqli_real_escape_string($db, trim($_POST['email'] ?? ''));

     if ($utente === '' || $pass === '' || $nome === '' || $cognome === '' || $email === '') {
       $messaggio = '<div class="alert alert-warning">Compila tutti i campi obbligatori.</div>';
      } elseif ($pass !== $pass2) {
        $messaggio = '<div class="alert alert-error">Le password non coincidono.</div>';
     } else {
         $passDb = mysqli_real_escape_string($db, $pass);
            
         $numero_tesseramento = substr(time(), -5) . rand(100, 999);
            
         $queryAtleta = "INSERT INTO atleti (numero_tesseramento, nome, cognome, turno, "
     . "grado_cintura, data_nascita, telefono, email) "
     . "VALUES ('$numero_tesseramento', '$nome', '$cognome', '0', 'Bianca', "
     . "'$data', '$telefono', '$email')";
            
           
     $queryCredenziali = "INSERT INTO credenziali (utente, password, numero_tesseramento) "
    . "VALUES ('$utente', '$passDb', '$numero_tesseramento')";

    if (mysqli_query($db, $queryAtleta) && mysqli_query($db, $queryCredenziali)) {
     $_SESSION['utente_loggato'] = $utente;
     $registrazione_successo = true;
     $messaggio = '<div class="alert alert-success">
  <h2>Registrazione riuscita</h2>
  <p>Ciao <strong>' . e($nome) . '</strong>, il tuo profilo &egrave; stato creato correttamente.</p>
  <a href="homepage.php" class="success-link">Vai alla tua area utente</a>
                </div>';
   } 
    else
     {
                $messaggio = '<div class="alert alert-error">Registrazione non riuscita. Controlla i dati e riprova.</div>';
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
<main class="page-content registration-page">
<div>
<?php echo $messaggio; ?>
<?php if (!$registrazione_successo): ?>
<section class="form-container wide">
<h2>Crea il tuo profilo</h2>
<form action="registra.php" method="POST">
<div class="input-group-row">
<div class="input-wrapper">
<label>Nome</label>
<input type="text" name="nome" required>
</div>
<div class="input-wrapper">
<label>Cognome</label>
<input type="text" name="cognome" required>
</div>
</div>
<div class="input-group-row">
<div class="input-wrapper">
<label>Data di nascita</label>
<input type="date" name="data" required>
</div>
<div class="input-wrapper">
<label>Telefono</label>
<input type="text" name="telefono" required>
</div>
</div>
<div class="input-wrapper">
<label>Email</label>
<input type="email" name="email" required>
</div>
<div class="input-wrapper">
<label>Username</label>
<input type="text" name="utente" required>
</div>
<div class="input-group-row">
<div class="input-wrapper">
<label>Password</label>
<input type="password" name="pass" required>
</div>
<div class="input-wrapper">
<label>Ripeti password</label>
<input type="password" name="pass2" required>
</div>
</div>
<input type="submit" value="Registrati ora" name="submit">
</form>
<div class="form-footer">
Sei gi&agrave; iscritto? <a href="index.php">Accedi</a>
</div>
</section>
<?php endif; ?>
</div>
</main>
</body>
</html>
