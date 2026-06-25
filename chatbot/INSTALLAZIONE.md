# Chatbot ASD Condor - Riepilogo installazione

Il chatbot e\' pronto per l\'uso. Riassunto dei passaggi necessari sul
server dove gia\' gira il sito Progetto-Condor.

## 1. Avvia Ollama
Verifica che Ollama sia in esecuzione:

```
ollama serve
```

Scarica almeno un modello (uno solo):

```
ollama pull llama3.2:3b
```

## 2. Cartella chatbot
La cartella `chatbot/` contiene tutti i file del widget. Non serve
copiare altrove: il sito include gia\' i percorsi corretti.

## 3. Header integrato
Il file `header.php` del progetto e\' gia\' stato aggiornato per
includere il widget su tutte le pagine interne (la login page resta
pulita). Se l\'header originale fosse stato sovrascritto, ricordati di
aggiungere:

```php
require_once __DIR__ . \'/chatbot/bootstrap.php\';
// ...
<?php condorChatbotIncludi(); ?>
```

## 4. Apri il sito
Dopo aver riavviato il web server, apri una qualsiasi pagina del sito:
in basso a destra trovi il pulsante "Chatta con noi".

## Cosa chiedere al bot

Esempi di domande che funzionano:

- "Ciao" / "Chi sei?"
- "Quali atleti sono presenti?"
- "Dimmi di Castaldo Michele"
- "Quanti atleti hanno la cintura nera?"
- "Quali sono i turni?" / "Atleti del primo turno"
- "Quando si allenano i senior?"
- "Quali gare sono disponibili?"
- "Quando ci sono gare in futuro?"
- "Dove si trova la palestra?"
- "Qual e\' il numero di telefono?"
- "Mostrami i social"
- "Quali sono gli orari?"
- "Il maestro Pacifico Laezza"
- "Come mi iscrivo?"

Per i test automatici c\'e\' `chatbot/test/intent_test.php` (eseguibile
con `php chatbot/test/intent_test.php`).
