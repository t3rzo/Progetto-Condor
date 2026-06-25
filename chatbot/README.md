# Chatbot ASD Condor

Widget di chat integrato nel sito della ASD Condor, alimentato da Ollama
per la riformulazione in linguaggio naturale e da PHP/MySQL per le
risposte "di fatto" basate sul database degli atleti e delle gare.

## Cosa fa

- Risponde a domande su **atleti presenti/assenti** leggendo la tabella
  `atleti`.
- Elenca le **gare disponibili, future e passate** consultando la
  tabella `gare` e calcolando lo stato rispetto alla data odierna.
- Fornisce **indirizzo, telefono, email, orari e social** della
  palestra (dati sincronizzati con `chi_siamo.php` e `contatti.php`).
- Permette di interrogare i singoli **maestri/allenatori** e le
  **specialita\'** della scuola.
- Suggerisce automaticamente alcune domande rapide contestuali.

L\'interazione funziona anche **senza Ollama**: il layer deterministico
fornisce sempre una risposta corretta; Ollama aggiunge solo una
riformulazione in linguaggio naturale quando e\' disponibile.

## Architettura

```
chatbot/
  config.php       # configurazione modello + dati statici palestra
  intent.php       # riconoscimento intento + accesso DB
  service.php      # orchestrazione risposta + chiamata Ollama
  api.php          # endpoint JSON usato dal widget JS
  chatbot.js       # widget lato client (HTML iniettato + fetch)
  chatbot.css      # stili dedicati, allineati al tema del sito
  bootstrap.php    # helper per includere il widget da header.php
  INSTALLAZIONE.md # comandi Ollama per setup locale
```

## Installazione rapida

1. Installa Ollama (vedi `INSTALLAZIONE.md`) e scarica un modello:
   ```
   ollama pull llama3.2:3b
   ```
2. Avvia il server Ollama (default su `http://127.0.0.1:11434`).
3. Verifica che il sito PHP sia raggiungibile (es. `http://localhost/Progetto-Condor/`).
4. Apri qualsiasi pagina del sito: il pulsante "Chatta con noi" appare
   in basso a destra.

## Integrazione in header.php

Il widget si include con due righe in `header.php`:

```php
require_once __DIR__ . '/chatbot/bootstrap.php';
condorChatbotIncludi(); // aggiunge <script src="chatbot/chatbot.js">
```

L\'helper inietta automaticamente il tag `<script>` e il file JS si
occupa di caricare `chatbot/chatbot.css` e l\'endpoint `chatbot/api.php`.

## Configurazione

Tutti i parametri sono in `chatbot/config.php`:

| Parametro | Default | Note |
| --- | --- | --- |
| `$config_ollama_url` | `http://127.0.0.1:11434` | Endpoint Ollama |
| `$config_ollama_modello` | `llama3.2:3b` | Modello predefinito |
| `$config_ollama_timeout` | 25 | Secondi per la risposta |
| `$config_ollama_abilitato` | true | Se false il bot non chiama Ollama |
| `$config_chatbot_memoria_turni` | 6 | Turni di conversazione da ricordare |
| `$config_palestra` | array | Dati statici (indirizzo, tel, social) |

Se il modello configurato non e\' installato, il bot cerca un modello
della stessa famiglia e, in ultima istanza, usa il primo disponibile.

## API

`POST chatbot/api.php` accetta JSON:

```json
{
  "message": "Quali atleti sono presenti?",
  "history": [
    { "role": "user", "content": "Ciao" },
    { "role": "assistant", "content": "Ciao! Come posso aiutarti?" }
  ]
}
```

Risposta:

```json
{
  "reply": "Nel nostro database sono presenti ...",
  "intent": "atleti_pres",
  "sorgente": "deterministico+ollama",
  "suggerimenti": ["..."],
  "timestamp": "2026-06-25T10:30:00+02:00"
}
```

`intent` puo\' assumere i valori: `saluto`, `presentazione`, `orari`,
`palestra_info`, `contatti`, `email`, `social`, `maestro`, `specialita`,
`iscrizione`, `atleti_pres`, `atleti_assenze`, `atleta_pres`,
`atleta_assente`, `atleti_info`, `gare_attuali`, `gare_passate`,
`gare_dettaglio`, `help`, `libera`, `vuoto`, `errore`.

## Personalizzazione

- Modifica la palette editando le variabili CSS in `chatbot/chatbot.css`
  (sono mappate sugli stessi colori del sito: `--cb-accent`,
  `--cb-panel`, ecc.).
- Cambia l\'endpoint passando `endpoint => 'percorso/custom.php'` a
  `condorChatbotIncludi()`.
- Per disabilitare temporaneamente il widget chiama
  `condorChatbotIncludi(['disabilitato' => true])`.
