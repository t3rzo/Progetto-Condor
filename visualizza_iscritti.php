<?php
$db = mysqli_connect("localhost", "root", "", "accedi_condor");
$id_gara = $_GET['id_gara'];

// CORREZIONE: Recuperiamo gli iscritti da gara_iscrizioni
$query = "SELECT * FROM gara_iscrizioni WHERE id_gara = '$id_gara'";
$iscritti = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Iscritti Gara</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="page-content column-layout" style="max-width: 800px; margin: 0 auto; padding-top: 50px;">
        <h2>Atleti Iscritti alla Gara #<?php echo htmlspecialchars($id_gara); ?></h2>
        
        <table style="width: 100%; border-collapse: collapse; background: #1a1a1a; margin-top: 20px;">
            <thead>
                <tr style="border-bottom: 2px solid #d32f2f;">
                    <th style="padding: 10px; text-align: left;">Atleta</th>
                    <th style="padding: 10px; text-align: left;">Allenatore</th>
                    <th style="padding: 10px; text-align: center;">Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($iscritti)): ?>
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="padding: 10px;"><?php echo htmlspecialchars($row['nome_atleta']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($row['allenatore']); ?></td>
                        <td style="padding: 10px; text-align: center;">
                            <a href="annulla_iscrizione.php?id=<?php echo $row['id']; ?>&id_gara=<?php echo $id_gara; ?>" 
                               onclick="return confirm('Vuoi davvero annullare questa iscrizione?')"
                               style="color: #d32f2f; text-decoration: none; font-weight: bold;">Annulla</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <p style="margin-top: 30px; text-align: center;">
            <a href="gare.php" style="color: #aaa; text-decoration: none;">&larr; Torna alle gare</a>
        </p>
    </div>
</body>
</html>
