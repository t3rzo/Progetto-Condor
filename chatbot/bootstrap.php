<?php
/**
 * Helper per includere il chatbot Condor in header.php o nelle pagine
 * che vogliono esporlo. Mantiene la configurazione in un unico punto.
 *
 * Utilizzo da header.php (o da qualsiasi template):
 *   require_once __DIR__ . '/chatbot/bootstrap.php';
 *   condorChatbotIncludi();
 *
 * Parametri accettati da condorChatbotIncludi():
 *   - disabilitato: true per saltare del tutto l\'inclusione
 *   - solo_loggato: true per mostrare il widget solo agli utenti loggati
 *   - endpoint:     percorso API personalizzato (default chatbot/api.php)
 */
require_once __DIR__ . '/config.php';

function condorChatbotIncludi(array $opzioni = []): void {
    if (!empty($opzioni['disabilitato'])) {
        return;
    }

    if (!empty($opzioni['solo_loggato']) && !utenteLoggato()) {
        return;
    }

    $endpoint = $opzioni['endpoint'] ?? 'chatbot/api.php';
    $percorsoJs = $opzioni['js'] ?? 'chatbot/chatbot.js';

    // Echo del tag <script> che il widget JS leggera\' per il proprio endpoint.
    echo '<script id="condor-chatbot-script" src="' . htmlspecialchars($percorsoJs, ENT_QUOTES, 'UTF-8') . '" data-endpoint="' . htmlspecialchars($endpoint, ENT_QUOTES, 'UTF-8') . '"></script>';
}
