/**
 * Condor Bot - widget di chat
 *
 * Si aggancia all\'endpoint chatbot/api.php via fetch JSON. Il markup del
 * widget viene iniettato dinamicamente, cosi\' l\'integrazione richiede
 * solo l\'inclusione di questo script in header.php.
 */
(function () {
    'use strict';

    const STYLESHEET_HREF = 'chatbot/chatbot.css';
    const SCRIPT_TAG_ID = 'condor-chatbot-script';
    const STORAGE_KEY = 'condor_chatbot_history_v1';
    const STORAGE_OPEN_KEY = 'condor_chatbot_open_v1';

    // Cerca lo script corrente se presente
    const SCRIPT_TAG = document.currentScript || document.getElementById(SCRIPT_TAG_ID);
    
    // Evita doppio bootstrap controllando se il widget grafico esiste già
    if (document.querySelector('.condor-chatbot')) {
        return;
    }
    
    if (SCRIPT_TAG) {
        SCRIPT_TAG.id = SCRIPT_TAG_ID;
    }

    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, function (c) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c];
        });
    }

    function formatText(str) {
        // Format semplice: nuove righe -> <br>, grassetto *testo*
        const safe = escapeHtml(str ?? '');
        const withBreaks = safe.replace(/\n/g, '<br>');
        return withBreaks.replace(/\*([^*\n]+)\*/g, '<strong>$1</strong>');
    }

    function loadHistory() {
        try {
            const raw = sessionStorage.getItem(STORAGE_KEY);
            if (!raw) return [];
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (err) {
            return [];
        }
    }

    function saveHistory(history) {
        try {
            const trimmed = history.slice(-20);
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(trimmed));
        } catch (err) {
            // sessionStorage non disponibile: ignora
        }
    }

    function readOpenState() {
        try {
            return sessionStorage.getItem(STORAGE_OPEN_KEY) === '1';
        } catch (err) {
            return false;
        }
    }

    function writeOpenState(isOpen) {
        try {
            sessionStorage.setItem(STORAGE_OPEN_KEY, isOpen ? '1' : '0');
        } catch (err) {
            // ignora
        }
    }

    function ensureStylesheet() {
        if (document.querySelector('link[data-condor-chatbot]')) return;
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = STYLESHEET_HREF;
        link.setAttribute('data-condor-chatbot', '1');
        document.head.appendChild(link);
    }

    function createWidget() {
        const widget = document.createElement('div');
        widget.className = 'condor-chatbot';
        widget.setAttribute('data-open', readOpenState() ? 'true' : 'false');
        widget.innerHTML = `
            <button class="condor-chatbot__toggle" type="button" aria-label="Apri il chatbot Condor" aria-expanded="false">
                <span class="condor-chatbot__toggle-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                </span>
                <span class="condor-chatbot__toggle-label">Chatta con noi</span>
            </button>
            <section class="condor-chatbot__panel" role="dialog" aria-label="Chatbot ASD Condor" aria-hidden="true">
                <header class="condor-chatbot__header">
                    <div class="condor-chatbot__avatar" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"></path>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="condor-chatbot__title">
                        <strong>Condor Bot</strong>
                        <span class="condor-chatbot__subtitle">Assistente ufficiale ASD Condor</span>
                    </div>
                    <div class="condor-chatbot__status" aria-live="polite">
                        <span class="condor-chatbot__dot"></span> Online
                    </div>
                    <button class="condor-chatbot__close" type="button" aria-label="Chiudi il chatbot">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </header>

                <div class="condor-chatbot__body" role="log" aria-live="polite">
                    <div class="condor-chatbot__messages" data-condor-messages></div>
                </div>

                <div class="condor-chatbot__suggestions" data-condor-suggestions aria-label="Suggerimenti rapidi"></div>

                <form class="condor-chatbot__form" autocomplete="off">
                    <label class="condor-chatbot__sr" for="condor-chatbot-input">Scrivi un messaggio</label>
                    <textarea class="condor-chatbot__input" id="condor-chatbot-input" rows="1" placeholder="Chiedi info su atleti, gare, contatti..." maxlength="500"></textarea>
                    <button class="condor-chatbot__send" type="submit" aria-label="Invia messaggio">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
                <footer class="condor-chatbot__footer">
                    Le risposte sono generate automaticamente a partire dai dati della palestra.
                </footer>
            </section>
        `;
        document.body.appendChild(widget);
        return widget;
    }

    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
    }

    function scrollToBottom(messagesEl) {
        requestAnimationFrame(function () {
            messagesEl.parentElement.scrollTop = messagesEl.parentElement.scrollHeight;
        });
    }

    function appendMessage(messagesEl, role, content, meta) {
        const wrapper = document.createElement('div');
        wrapper.className = 'condor-chatbot__message condor-chatbot__message--' + role;
        wrapper.innerHTML = `
            <div class="condor-chatbot__bubble">${formatText(content)}</div>
            ${meta && meta.timestamp ? `<time class="condor-chatbot__time">${escapeHtml(meta.timestamp)}</time>` : ''}
        `;
        messagesEl.appendChild(wrapper);
        scrollToBottom(messagesEl);
    }

    function appendLoading(messagesEl) {
        const wrapper = document.createElement('div');
        wrapper.className = 'condor-chatbot__message condor-chatbot__message--assistant condor-chatbot__message--loading';
        wrapper.innerHTML = `
            <div class="condor-chatbot__bubble">
                <span class="condor-chatbot__typing"><span></span><span></span><span></span></span>
            </div>
        `;
        messagesEl.appendChild(wrapper);
        scrollToBottom(messagesEl);
        return wrapper;
    }

    function renderSuggestions(container, suggestions) {
        if (!suggestions || !suggestions.length) {
            container.innerHTML = '';
            return;
        }
        container.innerHTML = '';
        suggestions.forEach(function (s) {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'condor-chatbot__chip';
            chip.textContent = s;
            chip.addEventListener('click', function () {
                const widget = document.querySelector('.condor-chatbot');
                if (!widget) return;
                const input = widget.querySelector('.condor-chatbot__input');
                input.value = s;
                autoResize(input);
                input.focus();
                const form = widget.querySelector('.condor-chatbot__form');
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            });
            container.appendChild(chip);
        });
    }

    function toggleWidget(widget, open) {
        const shouldOpen = typeof open === 'boolean' ? open : widget.getAttribute('data-open') !== 'true';
        widget.setAttribute('data-open', shouldOpen ? 'true' : 'false');
        widget.querySelector('.condor-chatbot__panel').setAttribute('aria-hidden', shouldOpen ? 'false' : 'true');
        widget.querySelector('.condor-chatbot__toggle').setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
        writeOpenState(shouldOpen);
        if (shouldOpen) {
            const input = widget.querySelector('.condor-chatbot__input');
            if (input) setTimeout(function () { input.focus(); }, 120);
        }
    }

    function setupWidget() {
        ensureStylesheet();
        const widget = createWidget();
        const toggleBtn = widget.querySelector('.condor-chatbot__toggle');
        const closeBtn = widget.querySelector('.condor-chatbot__close');
        const form = widget.querySelector('.condor-chatbot__form');
        const input = widget.querySelector('.condor-chatbot__input');
        const messagesEl = widget.querySelector('[data-condor-messages]');
        const suggestionsEl = widget.querySelector('[data-condor-suggestions]');

        const history = loadHistory();
        if (history.length === 0) {
            appendMessage(messagesEl, 'assistant', 'Ciao! Sono il Condor Bot, l\'assistente ufficiale della ASD Condor. Posso aiutarti con atleti, gare, contatti e orari della palestra.');
            history.push({ role: 'assistant', content: 'Ciao! Sono il Condor Bot, l\'assistente ufficiale della ASD Condor. Posso aiutarti con atleti, gare, contatti e orari della palestra.' });
        } else {
            history.forEach(function (msg) {
                appendMessage(messagesEl, msg.role, msg.content);
            });
        }
        renderSuggestions(suggestionsEl, [
            'Quali atleti sono presenti?',
            'Quali gare sono disponibili?',
            'Dove si trova la palestra?',
            'Numero di telefono?',
            'Quali sono i turni della palestra?'
        ]);

        toggleBtn.addEventListener('click', function () { toggleWidget(widget); });
        closeBtn.addEventListener('click', function () { toggleWidget(widget, false); });

        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape' && widget.getAttribute('data-open') === 'true') {
                toggleWidget(widget, false);
            }
        });

        input.addEventListener('input', function () { autoResize(input); });
        input.addEventListener('keydown', function (ev) {
            if (ev.key === 'Enter' && !ev.shiftKey) {
                ev.preventDefault();
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });

        form.addEventListener('submit', function (ev) {
            ev.preventDefault();
            const text = input.value.trim();
            if (!text) return;
            sendMessage(widget, messagesEl, suggestionsEl, history, text);
            input.value = '';
            autoResize(input);
        });

        if (readOpenState()) {
            toggleWidget(widget, true);
        }
    }

    function sendMessage(widget, messagesEl, suggestionsEl, history, text) {
        appendMessage(messagesEl, 'user', text);
        history.push({ role: 'user', content: text });
        saveHistory(history);

        const loader = appendLoading(messagesEl);

        const endpoint = (widget.dataset.endpoint || 'chatbot/api.php');
        const payload = {
            message: text,
            history: history.map(function (msg) { return { role: msg.role, content: msg.content }; })
        };
        
fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(function (res) {
                if (!res.ok) {
                    return res.json().catch(function () { return {}; }).then(function (data) {
                        throw new Error(data.dettaglio || ('Errore ' + res.status));
                    });
                }
                // Cambiato da res.json() a res.text() per poter pulire gli spazi bianchi
                return res.text();
            })
            .then(function (text) {
                // Rimuove gli spazi/caratteri invisibili prima e dopo, poi converte in oggetto JSON
                return JSON.parse(text.trim());
            })
            .then(function (data) {
                loader.remove();
                const reply = data.reply || 'Non ho una risposta al momento, riprova.';
                appendMessage(messagesEl, 'assistant', reply, { timestamp: formatTimestamp(data.timestamp) });
                history.push({ role: 'assistant', content: reply });
                saveHistory(history);
                renderSuggestions(suggestionsEl, data.suggerimenti || []);
            })
            .catch(function (err) {
                loader.remove();
                appendMessage(messagesEl, 'assistant', 'Si e\' verificato un errore di comunicazione: ' + (err.message || 'riprova piu\' tardi.'));
                history.push({ role: 'assistant', content: 'Si e\' verificato un errore di comunicazione.' });
                saveHistory(history);
            });
    }

    function formatTimestamp(ts) {
        if (!ts) return '';
        try {
            const d = new Date(ts);
            if (isNaN(d.getTime())) return '';
            return d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
        } catch (err) {
            return '';
        }
    }

    ready(setupWidget);
})();

