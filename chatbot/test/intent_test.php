<?php
/**
 * Test deterministico del chatbot Condor (senza DB ne Ollama).
 *
 * Verifica che intent detection e risposte deterministiche funzionino
 * correttamente anche in modalita offline.
 */

require_once __DIR__ . '/../config.php';

// Simuliamo le dipendenze (DB) usando uno stub.
function connessioneDb() { return null; }
function utenteLoggato() { return false; }
function mostraAccessoRiservato($msg) {}

require_once __DIR__ . '/../intent.php';

// Carichiamo il service in modalita stub (non chiama Ollama).
$GLOBALS['config_ollama_abilitato'] = false;
require_once __DIR__ . '/../service.php';

$domande = [
    ['testo' => 'Ciao!', 'intent_atteso' => 'saluto'],
    ['testo' => 'Quali atleti sono presenti?', 'intent_atteso' => 'atleti_pres'],
    ['testo' => 'Dove si trova la palestra?', 'intent_atteso' => 'palestra_info'],
    ['testo' => "Qual e' il numero di telefono?", 'intent_atteso' => 'contatti'],
    ['testo' => 'Mostrami i social', 'intent_atteso' => 'social'],
    ['testo' => 'Quando ci sono gare disponibili?', 'intent_atteso' => 'gare_attuali'],
    ['testo' => 'Quali sono gli orari?', 'intent_atteso' => 'orari'],
    ['testo' => 'Il maestro Pacifico Laezza', 'intent_atteso' => 'maestro'],
    ['testo' => 'Come mi iscrivo?', 'intent_atteso' => 'iscrizione'],
    ['testo' => 'aiuto', 'intent_atteso' => 'help'],
];

$ok = 0; $tot = count($domande);
foreach ($domande as $d) {
    $intent = condorChatbotRilevaIntent($d['testo']);
    $pass = $intent === $d['intent_atteso'];
    $ok += $pass ? 1 : 0;
    echo ($pass ? '[OK]   ' : '[FAIL] ') . $d['testo'] . "  ->  $intent (atteso: " . $d['intent_atteso'] . ")\n";
}

echo "\nRisultato: $ok / $tot intent corretti.\n";
exit($ok === $tot ? 0 : 1);
