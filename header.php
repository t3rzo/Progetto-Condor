<?php
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/chatbot/bootstrap.php';

avviaSessione();
$is_logged = utenteLoggato();
$utente = utenteCorrente();
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
                <li class="nav-user">Ciao, <?php echo e($utente); ?></li>
                <li><a href="homepage.php" class="nav-highlight">Area utente</a></li>
                <li><a href="corsi.php">Corsi</a></li>
                <li><a href="gare.php">Gare</a></li>
                <li><a href="chi_siamo.php">Chi siamo</a></li>
                <li><a href="contatti.php">Contatti</a></li>
                <li><a href="logout.php" class="nav-danger">Esci</a></li>
            <?php else: ?>
                <li><a href="index.php">Login</a></li>
                <li><a href="registra.php">Registrati</a></li>
                <li><a href="corsi.php">Corsi</a></li>
                <li><a href="chi_siamo.php">Chi siamo</a></li>
                <li><a href="contatti.php">Contatti</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<?php
// Widget chatbot Condor. Caricato su TUTTE le pagine (inclusa la login).
condorChatbotIncludi();
?>
