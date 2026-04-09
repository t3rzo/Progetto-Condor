<?php
// Controlliamo se la sessione è già attiva, se non lo è la avviamo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Creiamo una variabile comoda per sapere se mostrare i menu pubblici o privati
$is_logged = isset($_SESSION['utente_loggato']);
?>

<header class="main-header">
    <div class="logo-container">
        <a href="<?php echo $is_logged ? 'homepage.php' : 'index.php'; ?>">
            <img src="images/logo.png" alt="ASD Condor">
        </a>
    </div>

    <nav>
        <ul class="nav-links">
            <?php if ($is_logged): ?>
                <li style="color: #fff; font-weight: bold; margin-right: 15px; text-transform: capitalize;">
                    👋 Ciao, <?php echo htmlspecialchars($_SESSION['utente_loggato']); ?>
                </li>
                <li><a href="homepage.php" style="color: #ffd700;">Area Utente</a></li>
                <li><a href="corsi.php">Corsi</a></li>
                <li><a href="chi_siamo.php">Chi Siamo</a></li>
                <li><a href="contatti.php">Contatti</a></li>
                <li><a href="logout.php" style="color: #d32f2f; font-weight: bold;">🚪 Esci</a></li>
            
            <?php else: ?>
                <li><a href="index.php">Login</a></li>
                <li><a href="registra.php">Registrati</a></li>
                <li><a href="corsi.php">Corsi</a></li>
                <li><a href="chi_siamo.php">Chi Siamo</a></li>
                <li><a href="contatti.php">Contatti</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>