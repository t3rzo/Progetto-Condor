<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "accedi_condor");

$id_gara = $_GET['id_gara'];
// Prendiamo tutti gli atleti della palestra
$res = mysqli_query($db, "SELECT nome, cognome FROM atleti_corsi ORDER BY cognome ASC");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Iscrivi Atleti</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="page-content">
        <h2>Seleziona Atleti da Iscrivere</h2>
        <form action="salva_iscrizione.php" method="POST" class="form-container">
            <input type="hidden" name="id_gara" value="<?php echo $id_gara; ?>">
            
            <label>Seleziona i ragazzi:</label>
            <div style="background: #222; padding: 10px; border-radius: 5px; max-height: 200px; overflow-y: auto;">
                <?php while($atleta = mysqli_fetch_assoc($res)): 
                    $nome_completo = $atleta['cognome'] . " " . $atleta['nome']; ?>
                    <label style="display: block; padding: 5px;">
                        <input type="checkbox" name="atleti[]" value="<?php echo $nome_completo; ?>"> <?php echo $nome_completo; ?>
                    </label>
                <?php endwhile; ?>
            </div>

            <label style="margin-top: 15px; display: block;">Allenatore Responsabile:</label>
            <input type="text" name="allenatore" placeholder="Nome dell'istruttore" required>

            <button type="submit" class="btn-red" style="margin-top: 20px;">Conferma Iscrizione</button>
        </form>
    </div>
</body>
</html>