<?php
function avviaSessione() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function utenteLoggato() {
    avviaSessione();
    return isset($_SESSION['utente_loggato']);
}

function utenteCorrente() {
    avviaSessione();
    return $_SESSION['utente_loggato'] ?? '';
}

function richiediLogin() {
    if (!utenteLoggato()) {
        header('Location: index.php');
        exit;
    }
}

function e($valore) {
    return htmlspecialchars((string) $valore, ENT_QUOTES, 'UTF-8');
}

function connessioneDb() {
    mysqli_report(MYSQLI_REPORT_OFF);
    return mysqli_connect('127.0.0.1', 'root', '', 'accedi_condor');
}

function mostraAccessoRiservato($messaggio) {
?>
    <main class="page-content access-page">
        <section class="form-container access-card">
            <h2>Accesso riservato</h2>
            <p><?php echo e($messaggio); ?></p>
            <div class="access-actions">
                <a href="index.php" class="btn btn-primary">Accedi</a>
                <a href="registra.php" class="btn btn-secondary">Registrati</a>
            </div>
        </section>
    </main>
<?php
}
