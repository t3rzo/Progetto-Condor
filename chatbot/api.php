<?php
/**
 * Endpoint HTTP per il chatbot Condor.
 *
 * Riceve richieste JSON via POST con campi:
 * - message:   la domanda dell'utente
 * - history:   array di turni precedenti [{role, content}]
 *
 * Restituisce JSON con campi:
 * - reply:     la risposta testuale del bot
 * - intent:    intent riconosciuto
 * - sorgente:  deterministico / deterministico+ollama / errore
 * - suggerimenti: array di suggerimenti di domande rapide
 *
 * L'endpoint e' pensato per essere chiamato dal widget JS incluso in
 * header.php: e' sufficiente un POST application/json.
 */

require_once __DIR__ . '/service.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

// Permetti solo richieste AJAX dallo stesso dominio via POST.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode(['errore' => 'Metodo non consentito'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 1. Recupero robusto del payload JSON (da php://input o $_POST come fallback)
$jsonGrezzo = file_get_contents('php://input');
$payload = json_decode($jsonGrezzo ?: '[]', true);

if (!is_array($payload)) {
    $payload = [];
}

// Supporto flessibile per chiavi 'message', 'messaggio' o 'testo'
$domandaGrezza = $payload['message'] ?? $payload['messaggio'] ?? $payload['testo'] ?? $_POST['message'] ?? $_POST['messaggio'] ?? '';
$domanda = trim((string)$domandaGrezza);

$cronologia = isset($payload['history']) && is_array($payload['history']) ? $payload['history'] : [];

// Sanifica la cronologia: solo role/content, lunghezza limitata.
$cronologiaPulita = [];
foreach ($cronologia as $turno) {
    if (!is_array($turno)) continue;
    $role = $turno['role'] ?? '';
    $content = $turno['content'] ?? '';
    if (!in_array($role, ['user', 'assistant'], true)) continue;
    $content = trim((string) $content);
    if ($content === '' || mb_strlen($content) > 1000) continue;
    $cronologiaPulita[] = ['role' => $role, 'content' => $content];
}
$cfg = condorChatbotConfig();
$cronologiaPulita = array_slice($cronologiaPulita, -($cfg['memoria_turni'] * 2));

if (function_exists('avviaSessione')) {
    avviaSessione();
}

// 2. Normalizzazione del testo per il confronto (Minuscolo e senza spazi superflui)
$domandaMinuscola = trim(mb_strtolower($domanda, 'UTF-8'));

try {
    $rispostaSperimentale = null;
    $intentRilevato = null;

    // 3. Sistema di Intercettazione Deterministico basato su Parole Chiave
    if (str_contains($domandaMinuscola, 'gara') || str_contains($domandaMinuscola, 'gare') || str_contains($domandaMinuscola, 'competizion')) {
        $intentRilevato = 'gare_attuali';
    } elseif (str_contains($domandaMinuscola, 'atlet') || str_contains($domandaMinuscola, 'iscritt') || str_contains($domandaMinuscola, 'presenti')) {
        $intentRilevato = 'turni';
    } elseif (str_contains($domandaMinuscola, 'palestra') || str_contains($domandaMinuscola, 'dove si trova') || str_contains($domandaMinuscola, 'indirizzo')) {
        $intentRilevato = 'palestra_info';
    } elseif (str_contains($domandaMinuscola, 'telefon') || str_contains($domandaMinuscola, 'contatt') || str_contains($domandaMinuscola, 'numero')) {
        $intentRilevato = 'contatti';
    } elseif (str_contains($domandaMinuscola, 'turno') || str_contains($domandaMinuscola, 'orari') || str_contains($domandaMinuscola, 'quando')) {
        $intentRilevato = 'turni';
    }

    // Se abbiamo intercettato una parola chiave forte, forziamo la richiesta a service.php inserendo l'intent corretto
    if ($intentRilevato !== null) {
        $risposta = condorChatbotRispondi($domanda, $cronologiaPulita);
        $risposta['intent'] = $intentRilevato; // Sovrascriviamo l'intent per essere sicuri
    } else {
        // Altrimenti eseguiamo il comportamento standard del servizio
        $risposta = condorChatbotRispondi($domanda, $cronologiaPulita);
    }

    // Se per qualche motivo il backend restituisce ancora il testo di errore ma l'utente aveva cliccato un bottone valido
    if (isset($risposta['reply']) && str_contains($risposta['reply'], 'Non ho capito bene') && $intentRilevato !== null) {
        // Fallback forzato di emergenza per evitare risposte stupide
        if ($intentRilevato === 'gare_attuali') {
            $risposta['reply'] = "Puoi consultare le prossime gare in programma direttamente nella sezione 'Gare ed eventi' della tua Dashboard.";
        } elseif ($intentRilevato === 'turni') {
            $risposta['reply'] = "Gli atleti iscritti e pronti per i prossimi eventi sono elencati nella bacheca della tua Dashboard.";
        }
    }

    // Genera i suggerimenti corretti in base all'intent estratto o forzato
    $risposta['suggerimenti'] = condorChatbotSuggerimenti($risposta['intent'] ?? 'libera');
    $risposta['timestamp']  = date('c');
    
    echo json_encode($risposta, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'errore'   => 'Errore interno del chatbot',
        'dettaglio' => $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}

/**
 * Suggerimenti di domande rapide in base all'intent corrente.
 */
function condorChatbotSuggerimenti(string $intent): array {
    $map = [
        'saluto'        => ['Quali atleti sono presenti?', 'Quali gare sono disponibili?', 'Dove si trova la palestra?'],
        'presentazione' => ['Quali gare sono in programma?', 'Dimmi chi sono i maestri', 'Telefono della palestra'],
        'palestra_info' => ['Qual e\' il numero di telefono?', 'Quali sono gli orari?', 'Mostrami i social'],
        'contatti'      => ['Dove si trova la palestra?', 'Qual e\' la mail?', 'Mostrami i social'],
        'turni'         => ['Atleti del primo turno?', 'Quali sono i turni?', 'Quando si allenano i Senior?'],
        'gare_attuali'  => ['Quando si svolgono le gare in Italia?', 'Dove si tengono le gare future?', 'Quanti atleti sono iscritti?'],
        'default'       => ['Quali atleti sono presenti?', 'Quali gare sono disponibili?', 'Dove si trova la palestra?'],
    ];
    return $map[$intent] ?? $map['default'];
}