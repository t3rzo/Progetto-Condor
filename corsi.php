<?php
// FONDAMENTALE: Avvia la sessione per leggere il "timbro" dell'utente
session_start();

// Variabile per capire se siamo loggati (true o false)
$is_logged = isset($_SESSION['utente_loggato']);

// Inizializziamo gli array vuoti per evitare errori
$turno1 = []; $turno2 = []; $turno3 = []; $turno4 = [];

// Ci connettiamo al DB e carichiamo i dati SOLO se l'utente è loggato
if ($is_logged) {
    $db = mysqli_connect("localhost", "root", "", "accedi_condor");

    if ($db) {
        function getAtletiByTurno($db, $turno) {
            $query = "SELECT nome, cognome, numero_tesseramento, grado_cintura FROM atleti_corsi WHERE turno = '$turno' ORDER BY cognome ASC";
            $risultato = mysqli_query($db, $query);
            $atleti = [];
            if ($risultato && mysqli_num_rows($risultato) > 0) {
                while($riga = mysqli_fetch_assoc($risultato)) {
                    $atleti[] = $riga;
                }
            }
            return $atleti;
        }
        
        function getColoreCintura($grado) {
            $grado = strtolower($grado ?? ''); 
            if (strpos($grado, 'bianca') !== false) return '#ffffff';
            if (strpos($grado, 'gialla') !== false) return '#ffd700';
            if (strpos($grado, 'verde') !== false) return '#008000';
            if (strpos($grado, 'blu') !== false) return '#0000ff';
            if (strpos($grado, 'rossa') !== false) return '#d32f2f';
            if (strpos($grado, 'nera') !== false) return '#222222';
            return 'transparent'; 
        }

        $turno1 = getAtletiByTurno($db, '1');
        $turno2 = getAtletiByTurno($db, '2');
        $turno3 = getAtletiByTurno($db, '3');
        $turno4 = getAtletiByTurno($db, '4');
        mysqli_close($db);
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASD Condor - Corsi e Turni</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>

<?php include 'header.php'; ?>

<?php if (!$is_logged): ?>
    <div class="page-content" style="animation: slideUpFade 0.8s ease-out forwards; display: flex; justify-content: center; align-items: center; min-height: 60vh;">
        <div class="form-container" style="text-align: center;">
            <h2 style="color: #d32f2f;">⚠️ Accesso Riservato</h2>
            <p style="margin-bottom: 25px; color: #ccc;">Devi effettuare l'accesso per visualizzare l'elenco dei corsi e degli atleti iscritti.</p>
            
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="index.php" style="padding: 10px 20px; background: #d32f2f; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s;">Accedi</a>
                <a href="registra.php" style="padding: 10px 20px; background: #333; color: white; border: 1px solid #555; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s;">Registrati</a>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="page-content column-layout" style="animation: slideUpFade 0.8s ease-out forwards;">
        <div class="corsi-header">
            <h2>Corsi Agonistici e Turni</h2>
            <p>Seleziona un corso per visualizzare gli atleti iscritti</p>
        </div>

        <div class="turni-container">
            
            <div class="turno-card" onclick="toggleCard(this)">
                <div class="turno-header">
                    <div class="turno-info">
                        <h3>1° Turno <span class="badge">Kids / Cadetti</span></h3>
                        <p class="orario">Lunedì, Mercoledì, Venerdì | 17:00 - 18:30</p>
                    </div>
                    <div class="turno-icon">▼</div>
                </div>
                
                <div class="turno-content-wrapper">
                    <div class="turno-content">
                        <table class="atleti-table">
                            <thead>
                                <tr>
                                    <th>Atleta</th>
                                    <th>Cintura</th> <th>Tesseramento FITA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($turno1)): ?>
                                    <tr><td colspan="3" class="empty-msg">Nessun atleta iscritto a questo turno.</td></tr>
                                <?php else: ?>
                                    <?php foreach($turno1 as $atleta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($atleta['cognome'] . " " . $atleta['nome']); ?></td>
                                            <td>
                                                <span style="display:inline-block; width:14px; height:14px; background-color:<?php echo getColoreCintura($atleta['grado_cintura']); ?>; border:1px solid #555; border-radius:3px; vertical-align:middle; margin-right:8px;"></span>
                                                <strong><?php echo isset($atleta['grado_cintura']) ? htmlspecialchars($atleta['grado_cintura']) : '-'; ?></strong>
                                            </td>
                                            <td><span class="tessera-id"><?php echo htmlspecialchars($atleta['numero_tesseramento']); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="turno-card" onclick="toggleCard(this)">
                <div class="turno-header">
                    <div class="turno-info">
                        <h3>2° Turno <span class="badge">Cadetti / Junior</span></h3>
                        <p class="orario">Lunedì, Mercoledì, Venerdì | 18:30 - 20:00</p>
                    </div>
                    <div class="turno-icon">▼</div>
                </div>
                
                <div class="turno-content-wrapper">
                    <div class="turno-content">
                        <table class="atleti-table">
                            <thead>
                                <tr>
                                    <th>Atleta</th>
                                    <th>Cintura</th> <th>Tesseramento FITA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($turno2)): ?>
                                    <tr><td colspan="3" class="empty-msg">Nessun atleta iscritto a questo turno.</td></tr>
                                <?php else: ?>
                                    <?php foreach($turno2 as $atleta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($atleta['cognome'] . " " . $atleta['nome']); ?></td>
                                            <td>
                                                <span style="display:inline-block; width:14px; height:14px; background-color:<?php echo getColoreCintura($atleta['grado_cintura']); ?>; border:1px solid #555; border-radius:3px; vertical-align:middle; margin-right:8px;"></span>
                                                <strong><?php echo isset($atleta['grado_cintura']) ? htmlspecialchars($atleta['grado_cintura']) : '-'; ?></strong>
                                            </td>
                                            <td><span class="tessera-id"><?php echo htmlspecialchars($atleta['numero_tesseramento']); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="turno-card" onclick="toggleCard(this)">
                <div class="turno-header">
                    <div class="turno-info">
                        <h3>3° Turno <span class="badge">Senior</span></h3>
                        <p class="orario">Lunedì, Mercoledì, Venerdì | 20:00 - 21:30</p>
                    </div>
                    <div class="turno-icon">▼</div>
                </div>
                
                <div class="turno-content-wrapper">
                    <div class="turno-content">
                        <table class="atleti-table">
                            <thead>
                                <tr>
                                    <th>Atleta</th>
                                    <th>Cintura</th> <th>Tesseramento FITA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($turno3)): ?>
                                    <tr><td colspan="3" class="empty-msg">Nessun atleta iscritto a questo turno.</td></tr>
                                <?php else: ?>
                                    <?php foreach($turno3 as $atleta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($atleta['cognome'] . " " . $atleta['nome']); ?></td>
                                            <td>
                                                <span style="display:inline-block; width:14px; height:14px; background-color:<?php echo getColoreCintura($atleta['grado_cintura']); ?>; border:1px solid #555; border-radius:3px; vertical-align:middle; margin-right:8px;"></span>
                                                <strong><?php echo isset($atleta['grado_cintura']) ? htmlspecialchars($atleta['grado_cintura']) : '-'; ?></strong>
                                            </td>
                                            <td><span class="tessera-id"><?php echo htmlspecialchars($atleta['numero_tesseramento']); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="turno-card" onclick="toggleCard(this)">
                <div class="turno-header">
                    <div class="turno-info">
                        <h3>Allenatori/Tecnici <span class="badge elite">Maestro</span></h3>
                    </div>
                    <div class="turno-icon">▼</div>
                </div>
                
                <div class="turno-content-wrapper">
                    <div class="turno-content">
                        <table class="atleti-table">
                            <thead>
                                <tr>
                                    <th>Atleta</th>
                                    <th>Cintura</th> <th>Tesseramento FITA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($turno4)): ?>
                                    <tr><td colspan="3" class="empty-msg">Nessun atleta iscritto a questo turno.</td></tr>
                                <?php else: ?>
                                    <?php foreach($turno4 as $atleta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($atleta['cognome'] . " " . $atleta['nome']); ?></td>
                                            <td>
                                                <span style="display:inline-block; width:14px; height:14px; background-color:<?php echo getColoreCintura($atleta['grado_cintura']); ?>; border:1px solid #555; border-radius:3px; vertical-align:middle; margin-right:8px;"></span>
                                                <strong><?php echo isset($atleta['grado_cintura']) ? htmlspecialchars($atleta['grado_cintura']) : '-'; ?></strong>
                                            </td>
                                            <td><span class="tessera-id"><?php echo htmlspecialchars($atleta['numero_tesseramento']); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> 
    </div> 

    <script>
        function toggleCard(cardElement) {
            cardElement.classList.toggle('active');
        }
    </script>
<?php endif; ?>

</body>
</html>