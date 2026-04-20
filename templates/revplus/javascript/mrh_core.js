/**
 * MRH Core JavaScript
 * Version: 1.1.0
 * Datum: 2026-04-20
 * Abhaengigkeiten: KEINE (reines Vanilla JS)
 *
 * Beschreibung:
 * Globaler MRH Namespace, Event-System, Utility-Funktionen.
 * Wird auf ALLEN Seiten geladen (vor allen anderen MRH JS-Dateien).
 *
 * Vanilla JS - KEIN jQuery, KEIN React, KEIN Vue
 */

'use strict';

// ---------------------------------------------------------------
// 1. Globaler Namespace
// ---------------------------------------------------------------
const MRH = window.MRH || {};
window.MRH = MRH;

/**
 * Versionsinformation
 */
MRH.version = '1.1.0';

// ---------------------------------------------------------------
// 2. Event-System (Pub/Sub)
// ---------------------------------------------------------------
MRH.Events = {
  _listeners: {},

  /**
   * Event-Listener registrieren
   * @param {string} event - Event-Name (z.B. 'cart:updated')
   * @param {Function} callback - Callback-Funktion
   */
  on(event, callback) {
    if (!this._listeners[event]) {
      this._listeners[event] = [];
    }
    this._listeners[event].push(callback);
  },

  /**
   * Event-Listener entfernen
   * @param {string} event - Event-Name
   * @param {Function} callback - Callback-Funktion
   */
  off(event, callback) {
    if (!this._listeners[event]) return;
    this._listeners[event] = this._listeners[event].filter(cb => cb !== callback);
  },

  /**
   * Event ausloesen
   * @param {string} event - Event-Name
   * @param {*} data - Event-Daten
   */
  emit(event, data) {
    if (!this._listeners[event]) return;
    this._listeners[event].forEach(cb => {
      try {
        cb(data);
      } catch (err) {
        console.error('[MRH] Event error:', event, err);
      }
    });
  }
};

// ---------------------------------------------------------------
// 3. DOM Utilities
// ---------------------------------------------------------------
MRH.DOM = {
  /**
   * Element per Selektor finden (Kurzform)
   * @param {string} selector - CSS-Selektor
   * @param {Element} [parent=document] - Eltern-Element
   * @returns {Element|null}
   */
  qs(selector, parent = document) {
    return parent.querySelector(selector);
  },

  /**
   * Alle Elemente per Selektor finden
   * @param {string} selector - CSS-Selektor
   * @param {Element} [parent=document] - Eltern-Element
   * @returns {Element[]}
   */
  qsa(selector, parent = document) {
    return Array.from(parent.querySelectorAll(selector));
  },

  /**
   * Event-Delegation: Listener auf Parent mit Selektor-Filter
   * @param {Element} parent - Eltern-Element
   * @param {string} eventType - Event-Typ (z.B. 'click')
   * @param {string} selector - CSS-Selektor fuer Target
   * @param {Function} handler - Event-Handler
   */
  delegate(parent, eventType, selector, handler) {
    parent.addEventListener(eventType, (e) => {
      const target = e.target.closest(selector);
      if (target && parent.contains(target)) {
        handler.call(target, e, target);
      }
    });
  },

  /**
   * Element erstellen mit Attributen und Inhalt
   * @param {string} tag - HTML-Tag
   * @param {Object} [attrs={}] - Attribute
   * @param {string|Element|Element[]} [content] - Inhalt
   * @returns {Element}
   */
  create(tag, attrs = {}, content) {
    const el = document.createElement(tag);
    Object.entries(attrs).forEach(([key, val]) => {
      if (key === 'class') {
        el.className = val;
      } else if (key === 'dataset') {
        Object.entries(val).forEach(([dk, dv]) => {
          el.dataset[dk] = dv;
        });
      } else {
        el.setAttribute(key, val);
      }
    });
    if (content) {
      if (typeof content === 'string') {
        el.innerHTML = content;
      } else if (Array.isArray(content)) {
        content.forEach(child => el.appendChild(child));
      } else {
        el.appendChild(content);
      }
    }
    return el;
  }
};

// ---------------------------------------------------------------
// 4. Fetch Utilities (AJAX ohne jQuery)
// ---------------------------------------------------------------
MRH.Ajax = {
  /**
   * GET-Request
   * @param {string} url - URL
   * @param {Object} [params={}] - Query-Parameter
   * @returns {Promise<*>}
   */
  async get(url, params = {}) {
    const queryString = new URLSearchParams(params).toString();
    const fullUrl = queryString ? `${url}?${queryString}` : url;
    const response = await fetch(fullUrl, {
      method: 'GET',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return response.json();
  },

  /**
   * POST-Request
   * @param {string} url - URL
   * @param {Object|FormData} data - Daten
   * @returns {Promise<*>}
   */
  async post(url, data) {
    const isFormData = data instanceof FormData;
    const response = await fetch(url, {
      method: 'POST',
      headers: isFormData ? { 'X-Requested-With': 'XMLHttpRequest' }
        : { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: isFormData ? data : JSON.stringify(data)
    });
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    return response.json();
  }
};

// ---------------------------------------------------------------
// 5. Format Utilities
// ---------------------------------------------------------------
MRH.Format = {
  /**
   * Preis formatieren
   * @param {number} price - Preis als Zahl
   * @param {string} [currency='EUR'] - Waehrung
   * @param {string} [locale='de-DE'] - Locale
   * @returns {string}
   */
  price(price, currency = 'EUR', locale = 'de-DE') {
    return new Intl.NumberFormat(locale, {
      style: 'currency',
      currency: currency
    }).format(price);
  },

  /**
   * Datum formatieren
   * @param {string|Date} date - Datum
   * @param {string} [locale='de-DE'] - Locale
   * @returns {string}
   */
  date(date, locale = 'de-DE') {
    return new Intl.DateTimeFormat(locale, {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    }).format(new Date(date));
  },

  /**
   * Text kuerzen mit Ellipsis
   * @param {string} text - Text
   * @param {number} maxLength - Maximale Laenge
   * @returns {string}
   */
  truncate(text, maxLength = 100) {
    if (!text || text.length <= maxLength) return text;
    return text.substring(0, maxLength).trim() + '...';
  }
};

// ---------------------------------------------------------------
// 6. Debounce / Throttle
// ---------------------------------------------------------------
MRH.Utils = {
  /**
   * Debounce: Funktion erst nach Pause ausfuehren
   * @param {Function} fn - Funktion
   * @param {number} delay - Verzoegerung in ms
   * @returns {Function}
   */
  debounce(fn, delay = 300) {
    let timer;
    return function (...args) {
      clearTimeout(timer);
      timer = setTimeout(() => fn.apply(this, args), delay);
    };
  },

  /**
   * Throttle: Funktion maximal alle X ms ausfuehren
   * @param {Function} fn - Funktion
   * @param {number} limit - Mindestabstand in ms
   * @returns {Function}
   */
  throttle(fn, limit = 200) {
    let inThrottle;
    return function (...args) {
      if (!inThrottle) {
        fn.apply(this, args);
        inThrottle = true;
        setTimeout(() => { inThrottle = false; }, limit);
      }
    };
  },

  /**
   * Lazy Loading fuer Bilder (Fallback fuer aeltere Browser)
   */
  initLazyLoad() {
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.classList.add('lazyloaded');
              observer.unobserve(img);
            }
          }
        });
      }, { rootMargin: '200px' });

      MRH.DOM.qsa('img[data-src]').forEach(img => observer.observe(img));
    }
  }
};

// ---------------------------------------------------------------
// 7. Vanilla JS Accordion / Collapse Handler
// ---------------------------------------------------------------
// Ersetzt die BS4→BS5 Bridge komplett.
// Reagiert auf data-toggle="collapse" (BS4-Syntax aus CMS-Content)
// und implementiert Collapse-Logik in reinem Vanilla JS.
// Keinerlei Abhaengigkeit von Bootstrap Collapse JS.
// ---------------------------------------------------------------
MRH.Collapse = {

  /** Animations-Dauer in ms */
  DURATION: 350,

  /**
   * Initialisierung: Event-Delegation auf document
   * Faengt alle Klicks auf [data-toggle="collapse"] ab
   */
  init: function() {
    // Event-Delegation: ein einziger Listener auf document
    document.addEventListener('click', function(e) {
      // Finde den naechsten Button/Link mit data-toggle="collapse"
      var trigger = e.target.closest('[data-toggle="collapse"]');
      if (!trigger) return;

      e.preventDefault();

      var targetSel = trigger.getAttribute('data-target');
      if (!targetSel) return;

      var targetEl = document.querySelector(targetSel);
      if (!targetEl) return;

      var parentSel = trigger.getAttribute('data-parent');
      var isOpen = targetEl.classList.contains('show');

      // Accordion-Verhalten: andere Panels schliessen
      if (parentSel && !isOpen) {
        MRH.Collapse._closeOthers(parentSel, targetSel);
      }

      // Toggle
      if (isOpen) {
        MRH.Collapse._hide(targetEl, trigger);
      } else {
        MRH.Collapse._show(targetEl, trigger);
      }
    });
  },

  /**
   * Panel oeffnen mit Animation
   * @param {Element} el - Das .collapse Element
   * @param {Element} trigger - Der Button/Link
   */
  _show: function(el, trigger) {
    // Hoehe messen
    el.style.display = 'block';
    el.style.overflow = 'hidden';
    el.style.height = '0px';
    el.classList.remove('collapse');
    el.classList.add('collapsing');

    var scrollH = el.scrollHeight;

    // requestAnimationFrame fuer saubere Animation
    requestAnimationFrame(function() {
      el.style.height = scrollH + 'px';
      el.style.transition = 'height ' + MRH.Collapse.DURATION + 'ms ease';
    });

    // Nach Animation: Klassen setzen
    setTimeout(function() {
      el.classList.remove('collapsing');
      el.classList.add('collapse', 'show');
      el.style.height = '';
      el.style.overflow = '';
      el.style.display = '';
      el.style.transition = '';
    }, MRH.Collapse.DURATION);

    // Trigger-Button aktualisieren
    trigger.classList.remove('collapsed');
    trigger.setAttribute('aria-expanded', 'true');
  },

  /**
   * Panel schliessen mit Animation
   * @param {Element} el - Das .collapse Element
   * @param {Element} trigger - Der Button/Link
   */
  _hide: function(el, trigger) {
    // Aktuelle Hoehe setzen fuer Animation
    el.style.height = el.scrollHeight + 'px';
    el.style.overflow = 'hidden';
    el.classList.remove('collapse', 'show');
    el.classList.add('collapsing');

    // Auf 0 animieren
    requestAnimationFrame(function() {
      el.style.height = '0px';
      el.style.transition = 'height ' + MRH.Collapse.DURATION + 'ms ease';
    });

    // Nach Animation: Klassen setzen
    setTimeout(function() {
      el.classList.remove('collapsing');
      el.classList.add('collapse');
      el.style.height = '';
      el.style.overflow = '';
      el.style.display = '';
      el.style.transition = '';
    }, MRH.Collapse.DURATION);

    // Trigger-Button aktualisieren
    trigger.classList.add('collapsed');
    trigger.setAttribute('aria-expanded', 'false');
  },

  /**
   * Accordion: Alle anderen offenen Panels im selben Parent schliessen
   * @param {string} parentSel - Selektor des Accordion-Containers
   * @param {string} exceptSel - Selektor des Panels das NICHT geschlossen wird
   */
  _closeOthers: function(parentSel, exceptSel) {
    var parent = document.querySelector(parentSel);
    if (!parent) return;

    // Alle offenen .collapse.show im Parent finden
    var openPanels = parent.querySelectorAll('.collapse.show');
    openPanels.forEach(function(panel) {
      // Nicht das Panel schliessen das gerade geoeffnet wird
      if ('#' + panel.id === exceptSel) return;

      // Zugehoerigen Trigger finden
      var panelTrigger = parent.querySelector(
        '[data-toggle="collapse"][data-target="#' + panel.id + '"]'
      );

      if (panelTrigger) {
        MRH.Collapse._hide(panel, panelTrigger);
      } else {
        // Fallback: Direkt schliessen ohne Animation
        panel.classList.remove('show');
        panel.style.height = '';
        panel.style.overflow = '';
      }
    });
  }
};

// ---------------------------------------------------------------
// 8. Init
// ---------------------------------------------------------------
// Init: Sofort ausfuehren wenn DOM bereits ready, sonst auf DOMContentLoaded warten
(function() {
  function init() {
    MRH.Collapse.init();
    MRH.Utils.initLazyLoad();
    MRH.Events.emit('mrh:ready');
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    // DOM ist bereits geladen (Script am Ende der Seite)
    init();
  }
})();
