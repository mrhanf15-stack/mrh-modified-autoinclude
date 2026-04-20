/**
 * MRH Core JavaScript
 * Version: 1.0.0
 * Datum: 2026-04-02
 * Abhaengigkeiten: Bootstrap 5.3.0
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
MRH.version = '1.0.0';

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
// 7. BS4 → BS5 Data-Attribute Bridge
// Konvertiert alte BS4-Attribute (data-toggle, data-target,
// data-parent, data-dismiss) in BS5-Pendants (data-bs-*),
// damit bestehende Content-HTML-Snippets funktionieren.
// Nach der Konvertierung werden Bootstrap-Komponenten manuell
// initialisiert, da BS5 sich bereits vor dieser Bridge geladen hat.
// ---------------------------------------------------------------
MRH.Utils.bs4Bridge = function() {
  var map = [
    ['data-toggle',  'data-bs-toggle'],
    ['data-target',  'data-bs-target'],
    ['data-parent',  'data-bs-parent'],
    ['data-dismiss', 'data-bs-dismiss']
  ];
  var converted = [];
  map.forEach(function(pair) {
    document.querySelectorAll('[' + pair[0] + ']').forEach(function(el) {
      if (!el.hasAttribute(pair[1])) {
        el.setAttribute(pair[1], el.getAttribute(pair[0]));
        converted.push(el);
      }
    });
  });

  // Bootstrap 5 manuell auf konvertierte Elemente initialisieren
  // (BS5 hat sich bereits initialisiert bevor die Bridge lief)
  if (typeof bootstrap !== 'undefined' && converted.length > 0) {
    converted.forEach(function(el) {
      var toggleType = el.getAttribute('data-bs-toggle');
      try {
        if (toggleType === 'collapse') {
          // Collapse: Click-Handler manuell binden
          var targetSel = el.getAttribute('data-bs-target');
          if (targetSel) {
            var targetEl = document.querySelector(targetSel);
            if (targetEl && !bootstrap.Collapse.getInstance(targetEl)) {
              var parentSel = el.getAttribute('data-bs-parent');
              var opts = { toggle: false };
              if (parentSel) opts.parent = parentSel;
              new bootstrap.Collapse(targetEl, opts);
            }
            // Click-Handler hinzufuegen
            el.addEventListener('click', function(e) {
              e.preventDefault();
              var collapseInstance = bootstrap.Collapse.getInstance(targetEl)
                || new bootstrap.Collapse(targetEl, { toggle: false });
              collapseInstance.toggle();
              // collapsed-Klasse auf Button toggeln
              var isShown = targetEl.classList.contains('show');
              // Timeout damit BS5 die Klasse erst setzen kann
              setTimeout(function() {
                if (targetEl.classList.contains('show') || targetEl.classList.contains('collapsing')) {
                  el.classList.remove('collapsed');
                  el.setAttribute('aria-expanded', 'true');
                } else {
                  el.classList.add('collapsed');
                  el.setAttribute('aria-expanded', 'false');
                }
              }, 50);
            });
          }
        } else if (toggleType === 'modal') {
          new bootstrap.Modal(document.querySelector(el.getAttribute('data-bs-target')));
        } else if (toggleType === 'tab') {
          new bootstrap.Tab(el);
        }
      } catch (err) {
        // Stille Fehlerbehandlung – Element ggf. schon initialisiert
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
    MRH.Utils.bs4Bridge();
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
