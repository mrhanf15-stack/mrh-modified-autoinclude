# MRH Modified Autoinclude Module

**Migration mr-hanf.de (Live) -> mr-hanf.at (Test) | modified-shop 3.3.0**

---

## Projektbeschreibung

Dieses Repository enthaelt alle **Autoinclude-Module** fuer den modified eCommerce Shop 3.3.0 von Mr. Hanf. Die Module erweitern den Shop ueber das offizielle Hook-Point-System, ohne Core-Dateien zu veraendern. Alle Aenderungen sind **updatesicher**.

**Quellshop:** mr-hanf.de (Live, modified 3.3.0, Template: REVPLUS)
**Zielshop:** mr-hanf.at (Test, modified 3.3.0, Template: REVPLUS)

---

## Inhaltsverzeichnis

1. [Ordnerstruktur](#ordnerstruktur)
2. [Coding-Richtlinien](#coding-richtlinien)
3. [Smarty-Template Regeln](#smarty-template-regeln)
4. [CSS-Richtlinien](#css-richtlinien)
5. [JavaScript-Richtlinien](#javascript-richtlinien)
6. [Schema.org 2026](#schemaorg-2026)
7. [Mehrsprachigkeit](#mehrsprachigkeit)
8. [Deployment](#deployment)
9. [Hookpoint-Referenz](#hookpoint-referenz)

---

## Ordnerstruktur

```
mrh-modified-autoinclude/
|
|-- README.md                          # Diese Datei
|-- TODO.md                            # Aufgabenliste (wird bei Abarbeitung aktualisiert)
|-- CHANGELOG.md                       # Aenderungsprotokoll
|-- docs/                              # Dokumentation
|   |-- CODING_GUIDELINES.md           # Ausfuehrliche Coding-Richtlinien
|   |-- SCHEMA_ORG_2026.md             # Schema.org Implementierungsguide
|   |-- MIGRATION_CHECKLIST.md         # Checkliste fuer Migration de -> at
|   |-- HOOKPOINT_REFERENCE.md         # Alle verfuegbaren Hookpoints
|
|-- includes/
|   |-- extra/
|   |   |-- functions/                 # Eigene PHP-Funktionen
|   |   |-- application_top/
|   |   |   |-- application_top_begin/ # Vor Shop-Init (Session, Config)
|   |   |   |-- application_top_end/   # Nach Shop-Init (Smarty-Variablen)
|   |   |-- application_bottom/        # Am Seitenende
|   |   |-- default/
|   |   |   |-- header/                # Header-Erweiterungen
|   |   |   |-- footer/                # Footer-Erweiterungen
|   |   |   |-- main_content/          # Content-Erweiterungen
|   |   |-- shopping_cart/             # Warenkorb-Logik
|   |   |-- cart_actions/              # Warenkorb-Aktionen
|   |   |-- checkout/                  # Checkout-Erweiterungen
|   |   |-- seo_url_mod/               # SEO-URL Modifikationen
|   |   |-- header/                    # HTML-Head Erweiterungen
|   |-- external/
|       |-- mrh_modules/               # Eigene Klassen und Bibliotheken
|
|-- admin/
|   |-- includes/
|       |-- extra/
|           |-- filenames/             # Admin-Dateinamen
|           |-- menu/                  # Admin-Menue Erweiterungen
|           |-- css/                   # Admin CSS
|           |-- javascript/            # Admin JavaScript
|           |-- footer/                # Admin Footer
|           |-- application_top/
|               |-- application_top_end/ # Admin Init
|
|-- lang/
|   |-- german/extra/                  # Deutsche Sprachkonstanten
|   |-- english/extra/                 # Englische Sprachkonstanten
|   |-- french/extra/                  # Franzoesische Sprachkonstanten
|   |-- spanish/extra/                 # Spanische Sprachkonstanten
|
|-- templates/
|   |-- revplus/
|       |-- smarty/                    # Smarty-Template Dateien (.html)
|       |-- css/                       # Template CSS-Dateien
|       |-- javascript/                # Template JS-Dateien
|       |-- img/                       # Template Bilder
|
|-- schema/                            # Schema.org JSON-LD Templates
```

---

## Coding-Richtlinien

### Grundregeln

| Regel | Beschreibung |
|---|---|
| **Praefix** | Alle Dateien, Variablen, Funktionen, DB-Tabellen mit `mrh_` praefix |
| **Keine Core-Dateien** | AUSSCHLIESSLICH Autoinclude-Ordner verwenden |
| **Kein Inline-CSS** | Alle Styles in separate `.css` Dateien in `templates/revplus/css/` |
| **Kein Inline-JS** | Alle Scripts in separate `.js` Dateien in `templates/revplus/javascript/` |
| **Keine Hardcodierung** | Texte IMMER ueber Sprachkonstanten, Pfade ueber Konstanten |
| **4 Sprachen** | Jede Sprachkonstante in DE, EN, FR, ES anlegen |
| **REVPLUS Smarty** | Bestehende Smarty-Variablen aus REVPLUS uebernehmen und erweitern |

### PHP-Regeln

```php
<?php
/**
 * MRH Autoinclude: [Modulname]
 * Hookpoint: [Hookpoint-Pfad]
 * Version: 1.0.0
 * Datum: 2026-04-02
 * Autor: MRH Team
 * 
 * Beschreibung: [Was macht dieses Modul]
 */

// Sicherheitscheck - PFLICHT in jeder Datei
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

// Praefix: mrh_ fuer alle Variablen und Funktionen
$mrh_variable = 'wert';

function mrh_meine_funktion() {
    // ...
}
```

**PHP-Standards:**
- PHP 8.1+ kompatibel
- Strict Types wo moeglich: `declare(strict_types=1);`
- Prepared Statements fuer alle DB-Queries
- Fehlerbehandlung mit try/catch
- Keine `@` Error-Suppression
- Keine `eval()` oder `exec()`
- Kommentare auf Deutsch

### Dateibenennnung

```
[Reihenfolge]_mrh_[modulname].php

Beispiele:
10_mrh_schema_org.php          # Frueh laden (Schema.org Init)
50_mrh_product_enhancement.php # Normal laden
90_mrh_tracking.php            # Spaet laden (Tracking)
```

Die Reihenfolge bestimmt die Ladereihenfolge (alphabetisch):
- `01-19`: Frueh (Init, Config, Konstanten)
- `20-49`: Mittel (Funktionen, Klassen)
- `50-79`: Normal (Business-Logik)
- `80-99`: Spaet (Output, Tracking, Cleanup)

---

## Smarty-Template Regeln

### REVPLUS-Kompatibilitaet

> **WICHTIG:** Alle bestehenden Smarty-Variablen aus dem REVPLUS-Template MUESSEN beibehalten werden. Neue Variablen werden mit dem `mrh_` Praefix ergaenzt.

**Bestehende Variablen beibehalten:**
```smarty
{* REVPLUS Original - NICHT aendern *}
{$PRODUCTS_NAME}
{$PRODUCTS_PRICE}
{$PRODUCTS_DESCRIPTION}
{$MODULE_product_options}
{$PRODUCTS_IMAGE}
```

**Neue Variablen ergaenzen:**
```smarty
{* MRH Erweiterung - NEU *}
{$mrh_schema_json_ld}
{$mrh_product_specs}
{$mrh_trust_badges}
{$mrh_breadcrumb_schema}
```

### Template-Datei Regeln

```smarty
{* 
  MRH Template: [Name]
  Basiert auf: REVPLUS [Original-Datei]
  Version: 1.0.0
  Aenderungen: [Was wurde geaendert/ergaenzt]
*}

{* Keine Inline-CSS! Stattdessen: *}
<div class="mrh-product-wrapper">

{* Keine Inline-JS! Stattdessen: *}
<button class="mrh-btn-cart" data-action="add-to-cart">

{* Keine Hardcodierung! Stattdessen Sprachkonstanten: *}
<h2>{#mrh_heading_description#}</h2>
<span>{#mrh_text_add_to_cart#}</span>

{* Bilder immer mit img-fluid und lazyloaded: *}
<img src="{$mrh_image_path}" class="img-fluid lazyloaded" alt="{$mrh_image_alt}" loading="lazy">

{* Bedingte Ausgabe - REVPLUS Smarty-Syntax: *}
{if $PRODUCTS_PRICE != ''}
  <span class="mrh-price">{$PRODUCTS_PRICE}</span>
{/if}
```

### Bootstrap 5.3.0 Integration

```smarty
{* Bootstrap 5.3.0 Klassen verwenden *}
<div class="container">
  <div class="row g-4">
    <div class="col-lg-5">
      {* Gallery *}
    </div>
    <div class="col-lg-7">
      {* Product Info *}
    </div>
  </div>
</div>

{* Bootstrap 5 Tabs statt Custom-Tabs *}
<ul class="nav nav-tabs mrh-product-tabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" data-bs-toggle="tab" 
            data-bs-target="#mrh-tab-desc" role="tab">
      {#mrh_tab_description#}
    </button>
  </li>
</ul>

{* Bootstrap 5 Accordion *}
<div class="accordion mrh-accordion" id="mrhAccordion">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" data-bs-toggle="collapse" 
              data-bs-target="#mrhCollapse1">
        {#mrh_accordion_manufacturer#}
      </button>
    </h2>
  </div>
</div>
```

---

## CSS-Richtlinien

### Dateistruktur

```
templates/revplus/css/
|-- mrh_base.css          # CSS Custom Properties, Reset, Basis
|-- mrh_product.css       # Produktseite
|-- mrh_category.css      # Kategorieseite
|-- mrh_checkout.css      # Checkout
|-- mrh_header.css        # Header/Navigation
|-- mrh_footer.css        # Footer
|-- mrh_schema.css        # Schema.org Badges/Widgets
```

### CSS-Regeln

```css
/* ============================================================
   MRH [Modulname] CSS
   Version: 1.0.0
   Abhaengigkeiten: Bootstrap 5.3.0, mrh_base.css
   ============================================================ */

/* CSS Custom Properties - IMMER in mrh_base.css definieren */
:root {
  --mrh-green: #16A34A;
  --mrh-green-dk: #15803D;
  /* ... */
}

/* BEM-Namenskonvention mit mrh- Praefix */
.mrh-product-wrapper { }
.mrh-product-wrapper__gallery { }
.mrh-product-wrapper__gallery--active { }

/* Keine !important ausser bei Bootstrap-Overrides */
.mrh-btn-cart {
  background-color: var(--mrh-green);
}

/* Responsive: Mobile-First */
.mrh-product-title {
  font-size: 1.25rem; /* Mobile */
}

@media (min-width: 768px) {
  .mrh-product-title {
    font-size: 1.5rem; /* Tablet */
  }
}

@media (min-width: 992px) {
  .mrh-product-title {
    font-size: 1.75rem; /* Desktop */
  }
}
```

**Verboten:**
- Kein `style=""` Attribut in HTML/Smarty
- Keine CSS in `<style>` Tags im Template
- Kein `!important` (Ausnahme: Bootstrap-Override mit Kommentar)
- Keine ID-Selektoren fuer Styling (nur fuer JS)
- Keine magischen Zahlen ohne Kommentar

---

## JavaScript-Richtlinien

### Dateistruktur

```
templates/revplus/javascript/
|-- mrh_core.js           # Basis-Utilities, Event-System
|-- mrh_product.js        # Produktseite (Gallery, Tabs, Cart)
|-- mrh_category.js       # Kategorieseite (Filter, Sort)
|-- mrh_checkout.js       # Checkout-Erweiterungen
|-- mrh_schema.js         # Schema.org dynamische Generierung
|-- mrh_tracking.js       # Analytics/Tracking
```

### JS-Regeln

```javascript
/**
 * MRH [Modulname] JavaScript
 * Version: 1.0.0
 * Abhaengigkeiten: Bootstrap 5.3.0
 * 
 * Vanilla JS - KEIN jQuery, KEIN React, KEIN Vue
 */

'use strict';

// Namespace: MRH
const MRH = window.MRH || {};

MRH.Product = {
  init() {
    this.bindEvents();
    this.initGallery();
  },

  bindEvents() {
    document.addEventListener('DOMContentLoaded', () => {
      // Event Delegation statt einzelne Listener
      document.body.addEventListener('click', (e) => {
        if (e.target.matches('.mrh-thumb-btn')) {
          this.switchImage(e.target);
        }
      });
    });
  },

  switchImage(thumb) {
    // ...
  }
};

// Init
MRH.Product.init();
```

**Verboten:**
- Kein `onclick=""`, `onchange=""` etc. in HTML/Smarty
- Kein `<script>` im Template (Ausnahme: JSON-LD)
- Kein jQuery (Vanilla JS only)
- Kein `document.write()`
- Keine globalen Variablen (nur `MRH` Namespace)

**Erlaubt:**
- `data-*` Attribute fuer JS-Interaktion
- `<script type="application/ld+json">` fuer Schema.org
- Bootstrap 5 `data-bs-*` Attribute

---

## Schema.org 2026

### Implementierungsstrategie

Alle Schema.org Markups werden als **JSON-LD** im `<head>` oder vor `</body>` eingefuegt. Kein Microdata, kein RDFa.

**Stand 2026 - Pflicht-Schemas:**

| Seite | Schema-Typ | Datei |
|---|---|---|
| Alle Seiten | `Organization`, `WebSite`, `BreadcrumbList` | `schema/mrh_global.json.tpl` |
| Produktseite | `Product`, `Offer`, `AggregateRating`, `Review` | `schema/mrh_product.json.tpl` |
| Kategorieseite | `CollectionPage`, `ItemList` | `schema/mrh_category.json.tpl` |
| Startseite | `WebPage`, `SiteNavigationElement` | `schema/mrh_home.json.tpl` |
| FAQ-Seiten | `FAQPage`, `Question`, `Answer` | `schema/mrh_faq.json.tpl` |
| Blog/Ratgeber | `Article`, `BlogPosting` | `schema/mrh_article.json.tpl` |

**Beispiel Product Schema 2026:**

```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "{$PRODUCTS_NAME}",
  "image": ["{$PRODUCTS_IMAGE}"],
  "description": "{$mrh_meta_description}",
  "sku": "{$PRODUCTS_MODEL}",
  "brand": {
    "@type": "Brand",
    "name": "{$PRODUCTS_MANUFACTURER}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{$mrh_canonical_url}",
    "priceCurrency": "EUR",
    "price": "{$mrh_price_raw}",
    "availability": "https://schema.org/InStock",
    "seller": {
      "@type": "Organization",
      "name": "Mr. Hanf"
    },
    "shippingDetails": {
      "@type": "OfferShippingDetails",
      "shippingRate": {
        "@type": "MonetaryAmount",
        "value": "0",
        "currency": "EUR"
      },
      "deliveryTime": {
        "@type": "ShippingDeliveryTime",
        "handlingTime": {
          "@type": "QuantitativeValue",
          "minValue": 0,
          "maxValue": 1,
          "unitCode": "DAY"
        },
        "transitTime": {
          "@type": "QuantitativeValue",
          "minValue": 1,
          "maxValue": 3,
          "unitCode": "DAY"
        }
      },
      "shippingDestination": {
        "@type": "DefinedRegion",
        "addressCountry": ["DE", "AT", "CH"]
      }
    },
    "hasMerchantReturnPolicy": {
      "@type": "MerchantReturnPolicy",
      "applicableCountry": ["DE", "AT"],
      "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
      "merchantReturnDays": 14,
      "returnMethod": "https://schema.org/ReturnByMail"
    }
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{$mrh_rating_avg}",
    "reviewCount": "{$mrh_review_count}",
    "bestRating": "5",
    "worstRating": "1"
  }
}
```

---

## Mehrsprachigkeit

### Sprachdatei-Struktur

Jede Sprachkonstante MUSS in allen 4 Sprachen angelegt werden:

```
lang/german/extra/mrh_[modul].php
lang/english/extra/mrh_[modul].php
lang/french/extra/mrh_[modul].php
lang/spanish/extra/mrh_[modul].php
```

### Beispiel Sprachdatei

```php
<?php
/**
 * MRH Sprachkonstanten: Produktseite
 * Sprache: Deutsch
 * Version: 1.0.0
 */
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

// Produktseite
define('MRH_HEADING_DESCRIPTION', 'Beschreibung');
define('MRH_HEADING_SPECIFICATIONS', 'Spezifikationen');
define('MRH_HEADING_REVIEWS', 'Bewertungen');
define('MRH_HEADING_GROWING_GUIDE', 'Anbau-Guide');

// Warenkorb
define('MRH_TEXT_ADD_TO_CART', 'In den Warenkorb');
define('MRH_TEXT_QUANTITY', 'Menge');
define('MRH_TEXT_IN_STOCK', 'Auf Lager');
define('MRH_TEXT_OUT_OF_STOCK', 'Nicht verfuegbar');

// Trust
define('MRH_TRUST_QUALITY', 'Qualitaetskontrolle');
define('MRH_TRUST_SHIPPING', 'Schneller Versand');
define('MRH_TRUST_SSL', 'SSL-verschluesselt');
define('MRH_TRUST_LOCATION', 'EU-Standort');

// Schema.org (nicht sichtbar, aber fuer Structured Data)
define('MRH_SCHEMA_SELLER', 'Mr. Hanf');
define('MRH_SCHEMA_CURRENCY', 'EUR');
```

### Verwendung in Smarty

```smarty
{* Sprachkonstanten in Smarty verwenden *}
<h2>{#MRH_HEADING_DESCRIPTION#}</h2>
<button>{#MRH_TEXT_ADD_TO_CART#}</button>

{* ODER ueber PHP-zugewiesene Smarty-Variable *}
{$mrh_lang.heading_description}
```

---

## Deployment

### Von GitHub auf den Server

```bash
# Auf dem Server (mr-hanf.at)
cd /pfad/zum/shop/

# Nur die Autoinclude-Dateien kopieren (NICHT das ganze Repo!)
# Einzelne Module:
curl -sL "https://raw.githubusercontent.com/mrhanf15-stack/mrh-modified-autoinclude/master/includes/extra/functions/10_mrh_schema_org.php" \
  -o includes/extra/functions/10_mrh_schema_org.php

# Sprachdateien:
curl -sL "https://raw.githubusercontent.com/mrhanf15-stack/mrh-modified-autoinclude/master/lang/german/extra/mrh_product.php" \
  -o lang/german/extra/mrh_product.php
```

### Reihenfolge beim Deployment

1. **Sprachdateien** zuerst (alle 4 Sprachen)
2. **CSS/JS** Dateien ins Template kopieren
3. **PHP Autoinclude** Dateien in die extra-Ordner
4. **Smarty Templates** ins Template-Verzeichnis
5. **OPcache leeren**
6. **Testen** auf mr-hanf.at

---

## Hookpoint-Referenz

Siehe [docs/HOOKPOINT_REFERENCE.md](docs/HOOKPOINT_REFERENCE.md) fuer die vollstaendige Liste aller verfuegbaren Hookpoints in modified-shop 3.3.0.

---

## Wichtige Hinweise

> **NIEMALS Core-Dateien bearbeiten!** Alle Aenderungen ausschliesslich ueber das Autoinclude-System.

> **NIEMALS Texte hardcodieren!** Immer Sprachkonstanten verwenden (DE, EN, FR, ES).

> **NIEMALS Inline-CSS oder Inline-JS!** Immer in separate Dateien auslagern.

> **IMMER `mrh_` Praefix verwenden!** Fuer Dateien, Variablen, Funktionen, CSS-Klassen, DB-Tabellen.

> **IMMER REVPLUS Smarty-Variablen beibehalten!** Nur ergaenzen, nicht ersetzen.

---

**Lizenz:** Proprietaer - Mr. Hanf GmbH
**Kontakt:** dev@mr-hanf.de
