<?php
session_start(); // FONDAMENTALE: Inizia la sessione per "ricordare" l'utente

$db=mysqli_connect("localhost","root","","accedi_condor") or die ("impossibile connettersi al database".mysqli_connect_error());

$comando="SELECT * FROM credenziali";
$comando1=mysqli_query($db,$comando);
$nome=$_POST['utente'];
$pass=$_POST['pass'];
$flag=false;

while($riga1=mysqli_fetch_array($comando1)){
    if($nome == $riga1['utente'] && $pass == $riga1['password']){
         // Credenziali corrette! 
         // 1. Salviamo il nome utente in una "memoria" del browser (la sessione)
         $_SESSION['utente_loggato'] = $nome;
         
         // 2. REINDIRIZZIAMO l'utente alla nuova pagina grafica
         header("Location: homepage.php");
         exit; // Ferma l'esecuzione della pagina
         
    } else if ($nome == $riga1['utente'] && $pass != $riga1['password']){
        // Manteniamo il tuo messaggio di errore, ma un po' più pulito
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
        echo "<h2>Password sbagliata.</h2>";
        echo "<a href='index.php' style='color:red;'>Torna indietro e riprova</a>";
        echo "</div>";
        $flag=true;
        break; // Trovato l'utente ma password errata, fermiamo il ciclo
    }
}

// Se il ciclo finisce e non ha trovato l'utente, e non è loggato
if(!$flag && !isset($_SESSION['utente_loggato'])){ 
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
    echo "<h2>Utente non esistente.</h2>";
    echo "<a href='registra.php' style='color:red;'>Registrati ora</a>";
    echo "</div>";
}

mysqli_close($db);
?>