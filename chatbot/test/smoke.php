<?php
/**
 * Smoke test locale del chatbot Condor.
 *
 * Non richiede un web server: simula il flusso di condorChatbotRispondi()
 * usando il database accedi_condor e mostra a terminale le risposte.
 *
 * Requisiti: PHP installato sul sistema e DB disponibile su 127.0.0.1.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Questo script va lanciato da CLI.\n");
}

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../intent.php';
require_once __DIR__ . '/../service.php';

$domande = [
    'Ciao!',
    'Chi sono gli atleti presenti?',
    'Dimmi di Castaldo Michele',
    'Castaldo',
    'Quando ci sono gare disponibili?',
    'Qual e\' il numero di telefono?',
    'Dove si trova la palestra?',
    'Mostrami i social',
    'Quali sono gli orari?',
    'Il maestro Pacifico Laezza',
    'Non esisto nel database',
    'Quanti atleti hanno la cintura nera?',
];

foreach ($domande as $d) {
    echo "------------------------------------------\n";
    echo "DOMANDA: " . $d . "\n";
    try {
        $r = condorChatbotRispondi($d);
        echo "INTENT:  " . $r['intent'] . " (sorgente: " . $r['sorgente'] . ")\n";
        echo "RISPOSTA:\n" . $r['reply'] . "\n";
    } catch (Throwable $e) {
        echo "ERRORE: " . $e->getMessage() . "\n";
    }
}
