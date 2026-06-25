<?php
/**
 * Riconoscimento intent del chatbot Condor.
 *
 * Combina il database del sito (atleti, gare, contatti) con il modello
 * linguistico di Ollama: prima cerca risposte deterministiche nei dati
 * della palestra, poi passa il compito al modello solo se serve una
 * riformulazione in linguaggio naturale.
 */

require_once __DIR__ . '/config.php';
require_once dirname(__DIR__) . '/utils.php';

/**
 * Converte una stringa di data gara (es. "11 - 12 Aprile 2026")
 * in un oggetto DateTime o null se non parsabile.
 */
function condorChatbotParseData(string $testo): ?DateTime {
    $mesi = [
        'gennaio' => 1, 'febbraio' => 2, 'marzo' => 3, 'aprile' => 4,
        'maggio' => 5, 'giugno' => 6, 'luglio' => 7, 'agosto' => 8,
        'settembre' => 9, 'ottobre' => 10, 'novembre' => 11, 'dicembre' => 12,
    ];

    if (preg_match('/(\d{1,2})(?:\s*-\s*(\d{1,2}))?\s+([a-z]+)\s+(\d{4})/i', strtolower($testo), $m)) {
        $mese = $mesi[$m[3]] ?? null;
        if (!$mese) {
            return null;
        }
        $giorno = (int) $m[1];
        $anno = (int) $m[4];
        $inizio = DateTime::createFromFormat('!Y-m-d', sprintf('%04d-%02d-%02d', $anno, $mese, $giorno));
        return $inizio ?: null;
    }

    return null;
}

/**
 * Normalizza una stringa rimuovendo accenti e portandola in minuscolo.
 */
function condorChatbotNormalizza(string $testo): string {
    $ricerca = ['a' => '[aàáâãäå]', 'e' => '[eèéêë]', 'i' => '[iìíîï]', 'o' => '[oòóôõöø]',
                'u' => '[uùúûü]', 'n' => '[nñ]', 'c' => '[cç]', 'y' => '[yýÿ]'];
    $t = strtolower($testo);
    foreach ($ricerca as $lettera => $cls) {
        $t = preg_replace_callback('/' . $cls . '/u', function () use ($lettera) {
            return $lettera;
        }, $t);
    }
    $t = preg_replace('/[^a-z0-9]+/', ' ', $t);
    $t = trim(preg_replace('/\s+/', ' ', $t));
    return $t;
}

/**
 * Rileva l'intento principale della frase dell'utente.
 */
function condorChatbotRilevaIntent(string $domanda): string {
    $t = condorChatbotNormalizza($domanda);

    $regole = [
        'saluto'         => ['ciao', 'salve', 'buongiorno', 'buonasera', 'buon pomeriggio', 'ehi', 'hey'],
        'presentazione'  => ['chi sei', 'come ti chiami', 'cosa sai fare', 'cosa puoi fare', 'che cosa fai', 'presentati'],
        'orari'          => ['orari', 'orario', 'a che ora', 'quando aprite', 'quando allenate', 'quando ci si allena'],
        'palestra_info'  => ['dove si trova', 'dove siete', 'dove e la palestra', 'indirizzo', 'posizione', 'come arrivo', 'mappa', 'dove vi trovate'],
        'contatti'       => ['telefono', 'recapito', 'contatto', 'numero di telefono', 'come vi contatto', 'cellulare', 'whatsapp'],
        'email'          => ['email', 'posta elettronica', 'mail'],
        'social'         => ['facebook', 'instagram', 'tiktok', 'social'],
        'maestro'        => ['maestro', 'allenatore', 'istruttore', 'coach', 'pacifico', 'laezza'],
        'specialita'     => ['specialita', 'disciplina', 'cosa si fa', 'tipo di sport'],
        'iscrizione'     => ['iscrizione', 'iscriversi', 'iscrivermi', 'come mi iscrivo', 'diventare socio'],
        'atleti_pres'    => ['atleti presenti', 'chi c e', 'chi ce', 'presenti', 'iscritti', 'tutti gli atleti', 'elenco atleti', 'lista atleti', 'atleti registrati', 'atleti iscritti', 'quanti atleti', 'cintura nera', 'cintura blu', 'cintura rossa', 'cintura verde', 'cintura gialla', 'cintura bianca'],
        'turni'           => ['turno 1', 'turno 2', 'turno 3', 'primo turno', 'secondo turno', 'terzo turno', '1 turno', '2 turno', '3 turno', 'turno kids', 'turno cadetti', 'turno junior', 'turno senior', 'atleti del turno', 'chi si allena il', 'chi si allena di', 'orari dei turni', 'elenco turni', 'turni', 'orari turni', 'corsi', 'suddivisione atleti', 'quando si allenano'],
        'atleti_assenze' => ['atleti assenti', 'chi non c e', 'chi non ce', 'assenti', 'mancanti'],
        'atleta_pres'    => ['e presente', 'c e anche', 'partecipa'],
        'atleta_assente' => ['non c e', 'non ce', 'non piu', 'si e dimesso', 'non fa piu', 'non si allena'],
        'atleti_info'    => ['chi e', 'chi e questo', 'chi e questa', 'dimmi di', 'parlami di', 'info su', 'informazioni su', 'grado di', 'quale cintura', 'cintura di', 'tesseramento di'],
        'gare_attuali'   => ['gare disponibili', 'gare attuali', 'gare in programma', 'prossime gare', 'gare del momento', 'quali gare ci sono', 'quali sono le gare', 'gare aperte', 'gare future', 'gare in arrivo', 'gare imminenti'],
        'gare_passate'   => ['gare passate', 'gare finite', 'gare concluse', 'gare archiviate', 'gare vecchie'],
        'gare_dettaglio' => ['quando la gara', 'quando si svolge', 'dove si svolge', 'dove e la gara', 'data della gara', 'luogo della gara'],
        'help'           => ['aiuto', 'help', 'cosa puoi dirmi', 'come funziona', 'non ho capito', 'comandi', 'istruzioni'],
    ];

    foreach ($regole as $intent => $chiavi) {
        foreach ($chiavi as $k) {
            if ($k !== '' && strpos($t, $k) !== false) {
                return $intent;
            }
        }
    }

    // euristica: se la frase contiene un nome proprio (maiuscola seguita da parola) prova atleta_info
    if (preg_match('/\b([A-Z][a-z\']+)\s+([A-Z][a-z\']+)\b/', $domanda, $m)) {
        return 'atleti_info';
    }

    return 'libera';
}

/**
 * Recupera dal DB tutti gli atleti ordinati per cognome.
 */
function condorChatbotCaricaAtleti(mysqli $db): array {
    $atleti = [];
    $ris = mysqli_query($db, 'SELECT nome, cognome, turno, numero_tesseramento, grado_cintura FROM atleti ORDER BY cognome ASC, nome ASC');
    if ($ris) {
        while ($r = mysqli_fetch_assoc($ris)) {
            $atleti[] = $r;
        }
    }
    return $atleti;
}

/**
 * Recupera dal DB tutte le gare.
 */
function condorChatbotCaricaGare(mysqli $db): array {
    $gare = [];
    $ris = mysqli_query($db, 'SELECT id_gara, titolo, data, luogo, specialita FROM gare ORDER BY id_gara ASC');
    if ($ris) {
        while ($r = mysqli_fetch_assoc($ris)) {
            $r['data_inizio_dt'] = condorChatbotParseData($r['data'] ?? '');
            $gare[] = $r;
        }
    }
    return $gare;
}

/**
 * Cerca atleti per nome/cognome con match approssimativo.
 */
function condorChatbotCercaAtleti(array $atleti, string $query): array {
    $q = condorChatbotNormalizza($query);
    if ($q === '') {
        return [];
    }
    $risultati = [];
    foreach ($atleti as $a) {
        $haystack = condorChatbotNormalizza(($a['cognome'] ?? '') . ' ' . ($a['nome'] ?? ''));
        if ($haystack === '') continue;
        // match parziale in entrambi i sensi
        if (strpos($haystack, $q) !== false || strpos($q, $haystack) !== false) {
            $risultati[] = $a;
        } else {
            // match per token (gestisce "castaldo michele" o "michele castaldo")
            $tokensA = explode(' ', $haystack);
            $tokensQ = explode(' ', $q);
            $comuni = array_intersect($tokensA, $tokensQ);
            if (!empty($comuni)) {
                $risultati[] = $a;
            }
        }
    }
    return $risultati;
}

/**
 * Formatta un atleta in stringa leggibile.
 */
function condorChatbotFormattaAtleta(array $a): string {
    $cognome = trim($a['cognome'] ?? '');
    $nome = trim($a['nome'] ?? '');
    $cintura = trim($a['grado_cintura'] ?? '');
    $tess = trim($a['numero_tesseramento'] ?? '');
    $turno = trim((string)($a['turno'] ?? ''));
    $parti = [];
    if ($cognome !== '' || $nome !== '') {
        $parti[] = trim($cognome . ' ' . $nome);
    }
    if ($cintura !== '') {
        $parti[] = 'cintura ' . $cintura;
    }
    if ($turno !== '') {
        $parti[] = 'turno ' . $turno;
    }
    if ($tess !== '') {
        $parti[] = 'tess. ' . $tess;
    }
    return implode(', ', $parti);
}

/**
 * Formatta una gara in stringa leggibile.
 */
function condorChatbotFormattaGara(array $g): string {
    $righe = [];
    $righe[] = $g['titolo'] ?? 'Gara senza titolo';
    if (!empty($g['data'])) {
        $righe[] = 'Data: ' . $g['data'];
    }
    if (!empty($g['luogo'])) {
        $righe[] = 'Luogo: ' . $g['luogo'];
    }
    if (!empty($g['specialita'])) {
        $righe[] = 'Specialita: ' . $g['specialita'];
    }
    return implode(' | ', $righe);
}

/**
 * Determina se una gara e' passata, in corso o futura, rispetto ad oggi.
 */
function condorChatbotStatoGara(array $g, ?DateTime $oggi = null): string {
    $oggi = $oggi ?: new DateTime('today');
    $dt = $g['data_inizio_dt'] ?? null;
    if (!$dt) {
        return 'sconosciuto';
    }
    $diff = (int) $oggi->diff($dt)->format('%r%a');
    if ($diff > 7) return 'futura';
    if ($diff >= 0) return 'imminente';
    return 'passata';
}

/**
 * Costruisce un fallback "deterministico" che non usa il modello LLM.
 */
function condorChatbotRispostaDeterministica(string $intent, string $domanda, array $contesto): string {
    $palestra = $contesto['palestra'];
    $atleti = $contesto['atleti'];
    $gare = $contesto['gare'];

    switch ($intent) {
        case 'saluto':
            return 'Ciao! Sono il Condor Bot, l\'assistente virtuale della ASD Condor. Posso aiutarti con atleti, gare, contatti e orari. Cosa vorresti sapere?';

        case 'presentazione':
            return 'Sono il chatbot ufficiale della ASD Condor, palestra di Taekwondo agonistico a Casoria. Posso dirti chi sono gli atleti iscritti, quali gare sono in programma, passate o future, e fornirti i contatti della palestra. Chiedimi pure!';

        case 'orari':
            return 'Alleniamo il ' . $palestra['orari'] . '. I turni sono Kids/Cadetti, Cadetti/Junior e Senior, piu\' il turno dedicato ad allenatori e tecnici.';

        case 'palestra_info':
            return 'La palestra si trova in ' . $palestra['indirizzo'] . '. Sulla pagina Chi siamo del sito trovi anche la mappa e i nostri social.';

        case 'contatti':
            return 'Puoi contattarci al numero ' . $palestra['telefono'] . ' oppure passare in palestra in ' . $palestra['indirizzo'] . '. I riferimenti sono riportati anche nella pagina Contatti del sito.';

        case 'email':
            return 'La nostra email di riferimento e\' ' . $palestra['email'] . '. Per iscrizioni e informazioni e\' preferibile contattarci anche via telefono.';

        case 'social':
            $sociali = [];
            foreach ($palestra['social'] as $nome => $link) {
                $sociali[] = $nome . ': ' . $link;
            }
            return 'Ci trovi sui social: ' . implode(' | ', $sociali) . '.';

        case 'maestro':
            return 'Il nostro maestro e\' ' . $palestra['maestro'] . '. E\' il riferimento tecnico della palestra e segue direttamente tutti i turni.';

        case 'specialita':
            return 'La ASD Condor pratica ' . $palestra['specialita'] . '. L\'attivita\' agonistica spazia da forme e freestyle fino al combattimento e al parataekwondo.';

        case 'iscrizione':
            return 'Per iscriverti vai alla pagina Registrati del sito: ti verra\' chiesto di creare username e password e associare i tuoi dati anagrafici. Una volta loggato potrai consultare i corsi e iscriverti alle gare.';

        case 'atleti_pres':
            if (empty($atleti)) {
                return 'Al momento non risultano atleti iscritti nel database. Prova ad aggiungerli dalla pagina Corsi.';
            }
            $nomi = array_map(function ($a) { return condorChatbotFormattaAtleta($a); }, $atleti);
            return 'Nel nostro database sono presenti ' . count($atleti) . ' atleti: ' . implode('; ', $nomi) . '.';

        case 'turni':
            $nomiTurni = [1 => '1° Turno (Kids / Cadetti, 17:00 - 18:30)', 2 => '2° Turno (Cadetti / Junior, 18:30 - 20:00)', 3 => '3° Turno (Senior, 20:00 - 21:30)', 4 => 'Allenatori/Tecnici'];
            $domandaNorm = condorChatbotNormalizza($domanda);
            $turnoTrovato = null;
            if (strpos($domandaNorm, 'turno 1') !== false || strpos($domandaNorm, '1 turno') !== false || strpos($domandaNorm, 'primo turno') !== false || strpos($domandaNorm, 'kids') !== false || strpos($domandaNorm, 'cadetti') !== false) {
                $turnoTrovato = 1;
            } elseif (strpos($domandaNorm, 'turno 2') !== false || strpos($domandaNorm, '2 turno') !== false || strpos($domandaNorm, 'secondo turno') !== false || strpos($domandaNorm, 'junior') !== false) {
                $turnoTrovato = 2;
            } elseif (strpos($domandaNorm, 'turno 3') !== false || strpos($domandaNorm, '3 turno') !== false || strpos($domandaNorm, 'terzo turno') !== false || strpos($domandaNorm, 'senior') !== false) {
                $turnoTrovato = 3;
            } elseif (strpos($domandaNorm, 'allenatori') !== false || strpos($domandaNorm, 'tecnici') !== false || strpos($domandaNorm, 'maestri') !== false) {
                $turnoTrovato = 4;
            }
            if ($turnoTrovato !== null) {
                $atletiTurno = array_values(array_filter($atleti, fn($a) => (int)($a['turno'] ?? 0) === $turnoTrovato));
                if (empty($atletiTurno)) {
                    return 'Nel ' . $nomiTurni[$turnoTrovato] . ' al momento non risultano atleti iscritti.';
                }
                $nomi = array_map(fn($a) => condorChatbotFormattaAtleta($a), $atletiTurno);
                return 'Atleti del ' . $nomiTurni[$turnoTrovato] . ': ' . implode('; ', $nomi) . '.';
            }
            $righeTurno = [];
            foreach ($atleti as $a) {
                $righeTurno[(int)($a['turno'] ?? 0)][] = condorChatbotFormattaAtleta($a);
            }
            $outTurni = 'Ecco come sono suddivisi gli atleti per turno:';
            foreach ($nomiTurni as $num => $nomeTurno) {
                $lista = $righeTurno[$num] ?? [];
                $outTurni .= PHP_EOL . '- ' . $nomeTurno . ': ' . (empty($lista) ? 'nessun atleta iscritto.' : implode(', ', $lista)) . '.';
            }
            return $outTurni;

        case 'atleti_assenze':
            return 'Al momento tutti gli atleti registrati risultano presenti nel database. Se qualcuno si e\' assentato temporaneamente, puoi aggiornare la sua iscrizione dalla pagina Corsi.';

        case 'gare_attuali':
            $oggi = new DateTime('today');
            $attuali = array_values(array_filter($gare, fn($g) => condorChatbotStatoGara($g, $oggi) !== 'passata'));
            if (empty($attuali)) {
                return 'Al momento non risultano gare attive o programmate nel calendario.';
            }
            $righe = array_map(fn($g) => '- ' . condorChatbotFormattaGara($g), $attuali);
            return 'Ecco le gare disponibili al momento (passate incluse se vuoi consultarle):\n' . implode("\n", $righe);

        case 'gare_passate':
            $oggi = new DateTime('today');
            $passate = array_values(array_filter($gare, fn($g) => condorChatbotStatoGara($g, $oggi) === 'passata'));
            if (empty($passate)) {
                return 'Non ci sono gare passate registrate.';
            }
            $righe = array_map(fn($g) => '- ' . condorChatbotFormattaGara($g), $passate);
            return 'Gare passate:\n' . implode("\n", $righe);

        case 'help':
            return 'Posso rispondere a domande come: "Chi sono gli atleti presenti?", "Quali gare ci sono?", "Dove si trova la palestra?", "Qual e\' il numero di telefono?", "Dimmi di Castaldo Michele". Prova a chiedermi qualcosa!';

        default:
            return '';
    }
}

/**
 * Verifica se un atleta singolo e\' presente (esiste nel DB).
 */
function condorChatbotVerificaAtleta(array $atleti, string $query): ?array {
    $match = condorChatbotCercaAtleti($atleti, $query);
    if (count($match) === 1) {
        return $match[0];
    }
    if (count($match) > 1) {
        return $match; // ambiguo
    }
    return null;
}

/**
 * Risposta basata sui dati quando l\'intent e\' mirato su un atleta specifico.
 */
function condorChatbotAtletaSpecifico(string $intent, string $domanda, array $atleti): ?string {
    $match = condorChatbotVerificaAtleta($atleti, $domanda);
    if ($match === null) {
        // nessun match per nome
        if (in_array($intent, ['atleti_info', 'atleta_pres', 'atleta_assente'], true)) {
            $pulita = trim(preg_replace('/(chi e|dimmi di|parlami di|info su|informazioni su|quale cintura|grado di|cintura di|e presente|non c e|non ce)/i', '', $domanda));
            if ($pulita !== '') {
                return 'Non trovo atleti che corrispondono a "' . trim($pulita) . '" nel nostro database. Vuoi controllare la pagina Corsi per l\'elenco completo?';
            }
        }
        return null;
    }

    if (is_array($match) && isset($match[0]) && is_array($match[0])) {
        // ambiguo: piu\' atleti
        $nomi = array_map(fn($a) => condorChatbotFormattaAtleta($a), $match);
        return 'Ho trovato piu\' atleti che corrispondono alla tua ricerca: ' . implode('; ', $nomi) . '. Puoi essere piu\' preciso indicando nome e cognome completi?';
    }

    // match singolo
    $a = $match;
    $descrizione = condorChatbotFormattaAtleta($a);

    switch ($intent) {
        case 'atleta_pres':
        case 'atleti_info':
            return 'Si, ' . $descrizione . '. Risulta regolarmente presente nel nostro database.';
        case 'atleta_assente':
            return 'Da quello che vedo, ' . $descrizione . ' risulta ancora presente nel database. Se vuoi segnalare un\'assenza temporanea, puoi farlo dalla pagina Corsi.';
        default:
            return 'Atleta trovato: ' . $descrizione . '.';
    }
}

/**
 * Risposta su una gara specifica (intent gare_dettaglio).
 */
function condorChatbotGaraSpecifica(string $domanda, array $gare): ?string {
    $q = condorChatbotNormalizza($domanda);
    if ($q === '') return null;
    $trovate = [];
    foreach ($gare as $g) {
        $haystack = condorChatbotNormalizza(($g['titolo'] ?? '') . ' ' . ($g['luogo'] ?? '') . ' ' . ($g['data'] ?? ''));
        foreach (explode(' ', $haystack) as $token) {
            if (strlen($token) >= 4 && strpos($q, $token) !== false) {
                $trovate[] = $g;
                break;
            }
        }
    }
    if (empty($trovate)) {
        return null;
    }
    if (count($trovate) > 1) {
        $righe = array_map(fn($g) => '- ' . condorChatbotFormattaGara($g), $trovate);
        return 'Ho trovato piu\' gare che corrispondono: ' . "\n" . implode("\n", $righe);
    }
    return 'Ecco i dettagli: ' . condorChatbotFormattaGara($trovate[0]) . '.';
}




















