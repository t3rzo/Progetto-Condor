<?php
/**
 * Configurazione del chatbot Condor.
 *
 * Personalizza qui i parametri del modello Ollama e i dati statici della
 * palestra che il bot deve conoscere (indirizzo, recapiti, regole).
 * Il modello di linguaggio verra' interrogato solo per riformulare le
 * risposte partendo da dati reali provenienti dal database Condor.
 */

if (!defined('CONDOR_CHATBOT_BOOTSTRAP')) {
    define('CONDOR_CHATBOT_BOOTSTRAP', 1);
}

// Endpoint HTTP di Ollama (di default in ascolto sulla porta 11434).
$config_ollama_url = 'http://127.0.0.1:11434';

// Modello predefinito di Ollama. Sostituiscilo con quello che hai scaricato
// (es. llama3.2:3b, llama3.1:8b, qwen2.5:7b, ecc.). Se vuoto il chatbot
// prova a sceglierne uno tra quelli installati.
$config_ollama_modello = 'llama3.2:3b';

// Timeout in secondi per la chiamata a Ollama.
$config_ollama_timeout = 25;

// Abilita la riformulazione generativa di Ollama. Se false il bot
// risponde solo con i dati strutturati senza interpellare il modello.
$config_ollama_abilitato = true;

// Numero massimo di turni di conversazione da mantenere in memoria.
$config_chatbot_memoria_turni = 6;

// Dati statici della palestra, allineati a chi_siamo.php e contatti.php.
$config_palestra = [
    'nome'        => 'ASD Condor',
    'descrizione' => 'Palestra agonistica di arti marziali Taekwondo, guidata dal maestro Pacifico Laezza (cintura nera 7° Dan).',
    'indirizzo'   => 'Via Antonio Mosca 13, Casoria (NA), Italia',
    'telefono'    => '081 757 5142',
    'email'       => 'info@asdcondor.it',
    'orari'       => 'Lunedi, Mercoledi, Venerdi - turni 17:00-18:30 / 18:30-20:00 / 20:00-21:30',
    'social'      => [
        'Facebook'  => 'https://www.facebook.com/asdcondor/?locale=it_IT',
        'Instagram' => 'https://www.instagram.com/asd_condor_tkd/',
        'TikTok'    => 'https://www.tiktok.com/@a.s.d.condor',
    ],
    'maestro'     => 'Maestro Pacifico Laezza, cintura nera 7° Dan',
    'specialita'  => 'Taekwondo agonistico (forme, combattimento, parataekwondo)',
];

function condorChatbotConfig(): array {
    global $config_ollama_url, $config_ollama_modello, $config_ollama_timeout,
           $config_ollama_abilitato, $config_chatbot_memoria_turni, $config_palestra;

    return [
        'ollama_url'       => $config_ollama_url,
        'ollama_modello'   => $config_ollama_modello,
        'ollama_timeout'   => $config_ollama_timeout,
        'ollama_abilitato' => (bool) $config_ollama_abilitato,
        'memoria_turni'    => max(2, (int) $config_chatbot_memoria_turni),
        'palestra'         => $config_palestra,
    ];
}
