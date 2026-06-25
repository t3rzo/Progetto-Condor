# Chatbot Condor - Setup rapido

1. Installa Ollama e scarica un modello (es. `ollama pull llama3.2:3b`).
2. Copia la cartella `chatbot/` dentro `Progetto-Condor/`.
3. Apri `header.php` del progetto e, se non l\'hai gia\' integrato, aggiungi
   in cima al file:
   ```php
   require_once __DIR__ . \'/chatbot/bootstrap.php\';
   ```
   e richiama `condorChatbotIncludi();` dove preferisci (la nostra
   versione modificata di `header.php` lo fa automaticamente dopo
   `<header class="main-header">`).
4. Apri il sito: il bottone "Chatta con noi" appare in basso a destra.

## File inclusi

| File | Ruolo |
| --- | --- |
| `chatbot/config.php` | Configurazione Ollama + dati statici palestra |
| `chatbot/intent.php` | Riconoscimento intent + query DB |
| `chatbot/service.php` | Orchestrazione risposta + chiamata Ollama |
| `chatbot/api.php` | Endpoint JSON (`POST`) usato dal widget |
| `chatbot/chatbot.js` | Widget lato client (markup + fetch) |
| `chatbot/chatbot.css` | Stili coerenti con `css/style.css` |
| `chatbot/bootstrap.php` | Helper da includere in `header.php` |
| `chatbot/README.md` | Documentazione architetturale completa |
| `chatbot/INSTALLAZIONE.md` | Comandi Ollama passo-passo |

## Suggerimenti per il modello

- macchine leggere / senza GPU: `llama3.2:3b`, `gemma2:2b`
- macchine con GPU: `llama3.1:8b`, `qwen2.5:7b`, `mistral:7b`
- italiano di qualita\': `llama3.1:8b`, `qwen2.5:7b`

Una volta scaricato un modello, aggiorna `$config_ollama_modello` in
`chatbot/config.php`. Il widget funziona anche senza Ollama: il fallback
deterministico usa il DB della palestra.
