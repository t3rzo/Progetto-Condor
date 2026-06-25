<?php
/**
 * Servizio chatbot Condor.
 *
 * Espone la funzione condorChatbotRispondi(), che:
 *   1. riconosce l\'intent della domanda
 *   2. costruisce una risposta deterministica basata sui dati del DB
 *   3. opzionalmente riformula la risposta tramite Ollama per renderla
 *      piu\' naturale
 *
 * La logica deterministica e\' sufficiente anche senza LLM: in questo
 * modo il bot continua a funzionare se Ollama non e\' raggiungibile.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/intent.php';

/**
 * Esegue una richiesta HTTP a Ollama (/api/chat) e ne ritorna la risposta.
 * Restituisce null in caso di errore o se Ollama non risponde.
 */
function condorChatbotChiamaOllama(array $messaggi, array $opzioni = []): ?array {
    $cfg = condorChatbotConfig();
    if (!$cfg['ollama_abilitato']) {
        return null;
    }

    $url = rtrim($cfg['ollama_url'], '/') . '/api/chat';
    $modello = $cfg['ollama_modello']; // <-- Sostituisci con questo!

    $payload = json_encode([
        'model'    => $modello,
        'messages' => $messaggi,
        'stream'   => false,
        'options'  => array_merge([
            'temperature' => 0.4,
            'num_predict' => 220,
            'top_p'       => 0.9,
        ], $opzioni),
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => max(5, $cfg['ollama_timeout']),
    ]);
    $risposta = curl_exec($ch);
    $http     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errore   = curl_error($ch);
    curl_close($ch);

    if ($risposta === false || $http !== 200) {
        error_log('[Condor chatbot] Ollama non raggiungibile: HTTP ' . $http . ' errore=' . $errore);
        return null;
    }

    $dati = json_decode($risposta, true);
    if (!is_array($dati) || empty($dati['message']['content'])) {
        return null;
    }
    return $dati['message'];
}

/**
 * Costruisce il system prompt con tutti i dati contestuali della palestra.
 */
function condorChatbotSystemPrompt(array $contesto): string {
    $palestra = $contesto['palestra'];
    $atleti = $contesto['atleti'];
    $gare = $contesto['gare'];

    $righeAtleti = array_map(
        fn($a) => sprintf('- %s %s (cintura %s, tess. %s, turno %s)',
            $a['cognome'] ?? '', $a['nome'] ?? '',
            $a['grado_cintura'] ?? 'n/d', $a['numero_tesseramento'] ?? 'n/d', $a['turno'] ?? 'n/d'),
        $atleti
    );
    $righeGare = array_map(
        fn($g) => sprintf('- [%d] %s | %s | %s | %s',
            $g['id_gara'] ?? 0, $g['titolo'] ?? '', $g['data'] ?? '', $g['luogo'] ?? '', $g['specialita'] ?? ''),
        $gare
    );

    $prompt = "Sei il chatbot ufficiale della ASD Condor, una palestra agonistica di Taekwondo.\n"
        . "Rispondi SEMPRE in italiano, in modo cordiale, sintetico e professionale.\n"
        . "Non inventare MAI dati che non siano presenti nel contesto fornito qui sotto: se non trovi la risposta, dillo chiaramente e rimanda alla pagina Corsi/Contatti del sito.\n"
        . "Se elenchi atleti o gare, mantieni il formato della lista data nel contesto.\n"
        . "\n=== Informazioni sulla palestra ===\n"
        . "Nome: " . $palestra['nome'] . "\n"
        . "Descrizione: " . $palestra['descrizione'] . "\n"
        . "Indirizzo: " . $palestra['indirizzo'] . "\n"
        . "Telefono: " . $palestra['telefono'] . "\n"
        . "Email: " . $palestra['email'] . "\n"
        . "Orari: " . $palestra['orari'] . "\n"
        . "Maestro: " . $palestra['maestro'] . "\n"
        . "Specialita: " . $palestra['specialita'] . "\n"
        . "Social: " . json_encode($palestra['social'], JSON_UNESCAPED_UNICODE) . "\n"
        . "\n=== Atleti presenti nel database (" . count($atleti) . ") ===\n"
        . (empty($righeAtleti) ? '(nessun atleta registrato)' : implode("\n", $righeAtleti))
        . "\n\n=== Gare in calendario (" . count($gare) . ") ===\n"
        . (empty($righeGare) ? '(nessuna gara registrata)' : implode("\n", $righeGare))
        . "\n\nUsa esclusivamente queste informazioni. Non menzionare dati inventati, numeri di tesseramento fasulli o contatti non in elenco.";

    return $prompt;
}

/**
 * Componi la risposta finale: prima il fallback deterministico,
 * poi eventuale riformulazione tramite Ollama.
 */
function condorChatbotRispondi(string $domanda, array $cronologia = []): array {
    $domanda = trim($domanda);
    if ($domanda === '') {
        return [
            'reply'    => 'Non ho ricevuto alcuna domanda. Scrivi pure cosa vorresti sapere sulla ASD Condor.',
            'intent'   => 'vuoto',
            'sorgente' => 'validazione',
        ];
    }

    $db = connessioneDb();
    if (!$db) {
        return [
            'reply'    => 'Al momento non riesco a raggiungere il database della palestra. Riprova tra poco.',
            'intent'   => 'errore',
            'sorgente' => 'database',
        ];
    }

    $atleti = condorChatbotCaricaAtleti($db);
    $gare = condorChatbotCaricaGare($db);
    mysqli_close($db);

    $cfg = condorChatbotConfig();
    $contesto = [
        'palestra' => $cfg['palestra'],
        'atleti'   => $atleti,
        'gare'     => $gare,
    ];

    $intent = condorChatbotRilevaIntent($domanda);

    // Prima tenta una risposta mirata sul tipo di richiesta
    if (in_array($intent, ['atleti_info', 'atleta_pres', 'atleta_assente'], true)) {
        $risposta = condorChatbotAtletaSpecifico($intent, $domanda, $atleti);
        if ($risposta !== null) {
            return [
                'reply'    => condorChatbotRiformula($risposta, $domanda, $cronologia, $intent, $contesto),
                'intent'   => $intent,
                'sorgente' => 'deterministico+ollama',
            ];
        }
    }

    if ($intent === 'gare_dettaglio') {
        $risposta = condorChatbotGaraSpecifica($domanda, $gare);
        if ($risposta !== null) {
            return [
                'reply'    => condorChatbotRiformula($risposta, $domanda, $cronologia, $intent, $contesto),
                'intent'   => $intent,
                'sorgente' => 'deterministico+ollama',
            ];
        }
    }

    $risposta = condorChatbotRispostaDeterministica($intent, $domanda, $contesto);
    if ($risposta === '') {
        $risposta = 'Non ho capito bene la domanda. Posso aiutarti con atleti, gare, orari e contatti della ASD Condor. Prova a riformulare la richiesta.';
    }

    $finale = condorChatbotRiformula($risposta, $domanda, $cronologia, $intent, $contesto);
    return [
        'reply'    => $finale,
        'intent'   => $intent,
        'sorgente' => strpos($finale, $risposta) === 0 ? 'deterministico' : 'deterministico+ollama',
    ];
}

/**
 * Riformula la risposta deterministica tramite Ollama se disponibile.
 * Se Ollama fallisce o e\' disabilitato, restituisce il testo originale.
 */
function condorChatbotRiformula(string $testo, string $domanda, array $cronologia, string $intent, array $contesto): string {
    $cfg = condorChatbotConfig();
    if (!$cfg['ollama_abilitato']) {
        return $testo;
    }

    $messaggi = [];
    $messaggi[] = ['role' => 'system', 'content' => condorChatbotSystemPrompt($contesto)];

    // inietta la risposta deterministica come "contesto di fatto" che il modello
    // puo\' parafrasare, cosi\' non ha spazio per inventare dati nuovi.
    $messaggi[] = [
        'role' => 'system',
        'content' => "Risposta di fatto gia\' verificata sul database (riformulala in italiano cordiale, max 3 frasi, senza aggiungere dati nuovi):\n" . $testo,
    ];

    $ultimi = array_slice($cronologia, -($cfg['memoria_turni'] * 2));
    foreach ($ultimi as $turno) {
        if (!isset($turno['role'], $turno['content'])) continue;
        if (!in_array($turno['role'], ['user', 'assistant'], true)) continue;
        $messaggi[] = ['role' => $turno['role'], 'content' => (string) $turno['content']];
    }
    $messaggi[] = ['role' => 'user', 'content' => $domanda];

    $risposta = condorChatbotChiamaOllama($messaggi);
    if ($risposta === null) {
        return $testo;
    }
    $testoModello = trim((string) ($risposta['content'] ?? ''));
    if ($testoModello === '') {
        return $testo;
    }
    // se il modello ha risposto in modo troppo corto o identico, usa il fallback
    if (mb_strlen($testoModello) < 5) {
        return $testo;
    }
    return $testoModello;
}
