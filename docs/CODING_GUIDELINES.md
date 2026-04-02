# MRH Coding Guidelines

Verbindliche Richtlinien fuer alle Entwickler die am MRH Modified Autoinclude Projekt arbeiten.

---

## Grundregeln

### 1. Keine Core-Dateien bearbeiten

Ausschliesslich das Autoinclude-System von modified eCommerce nutzen. Keine Dateien im Kern des Shops aendern. Alle Anpassungen erfolgen ueber die definierten Hookpoints.

### 2. Keine Hardcodierung in Templates

Alle Texte muessen ueber Sprachkonstanten (`define()`) in den Sprachdateien definiert werden. Keine deutschen, englischen oder sonstigen Texte direkt im Template-Code.

```smarty
{* FALSCH - Hardcodiert *}
<h2>Beschreibung</h2>

{* RICHTIG - Sprachkonstante *}
<h2>{$smarty.const.MRH_TAB_DESCRIPTION}</h2>
```

### 3. Kein Inline CSS oder JS

CSS gehoert in separate `.css` Dateien im Ordner `templates/revplus/css/`. JavaScript gehoert in separate `.js` Dateien im Ordner `templates/revplus/javascript/`.

```html
<!-- FALSCH -->
<div style="color: red; font-size: 14px;">Text</div>
<button onclick="doSomething()">Klick</button>

<!-- RICHTIG -->
<div class="mrh-text-error">Text</div>
<button class="mrh-btn" data-action="something">Klick</button>
```

### 4. Alle 4 Sprachen pflegen

Jede Sprachkonstante muss in allen 4 Sprachen vorhanden sein: Deutsch (`german`), Englisch (`english`), Franzoesisch (`french`), Spanisch (`spanish`).

### 5. Smarty-Variablen aus REVPLUS uebernehmen

Bestehende Smarty-Variablen des REVPLUS-Templates beibehalten und mit derselben Technik erweitern. Neue Variablen ueber Autoinclude-PHP-Dateien zuweisen.

---

## PHP-Richtlinien

### Sicherheitscheck

Jede PHP-Datei beginnt mit:

```php
<?php
defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');
```

### Namenskonventionen

| Element | Konvention | Beispiel |
|---|---|---|
| Dateiname | Nummer + Praefix `mrh_` | `10_mrh_schema_org.php` |
| Konstante | `MRH_` Praefix, UPPER_SNAKE | `MRH_TAB_DESCRIPTION` |
| Variable | `$mrh_` Praefix, snake_case | `$mrh_price_raw` |
| Klasse | `Mrh` Praefix, PascalCase | `MrhSchemaOrg` |
| Funktion | `mrh_` Praefix, snake_case | `mrh_get_product_data()` |
| CSS-Klasse | `mrh-` Praefix, kebab-case | `mrh-btn-primary` |
| JS-Namespace | `MRH.` Praefix, PascalCase | `MRH.Gallery` |

### Dateinummern (Ladereihenfolge)

| Bereich | Beschreibung |
|---|---|
| `01-09` | Basis-Konfiguration, Konstanten |
| `10-29` | Schema.org, SEO, Meta-Tags |
| `30-49` | Smarty-Erweiterungen, Template-Daten |
| `50-69` | Produkt-spezifische Module |
| `70-89` | Warenkorb, Checkout-Erweiterungen |
| `90-99` | Tracking, Analytics, Cleanup |

### Performance

Autoinclude-Dateien werden bei JEDEM Seitenaufruf geladen. Daher: Seitentyp-Check am Anfang (z.B. `if (basename($PHP_SELF) !== 'product_info.php') return;`), keine Datenbankabfragen wenn nicht noetig, Variablen nach Verwendung mit `unset()` aufraeumen.

---

## CSS-Richtlinien

### CSS Custom Properties verwenden

Alle Farben, Abstande und Schriften ueber CSS Custom Properties in `mrh_base.css` definieren. Keine hardcodierten Werte in Modul-CSS-Dateien.

```css
/* FALSCH */
.my-element { color: #16A34A; }

/* RICHTIG */
.mrh-element { color: var(--mrh-green); }
```

### Bootstrap 5.3.0 Klassen bevorzugen

Wo moeglich Bootstrap-Klassen nutzen. Eigene CSS-Klassen nur fuer Elemente die Bootstrap nicht abdeckt.

### Dateistruktur

| Datei | Inhalt |
|---|---|
| `mrh_base.css` | Custom Properties, Typography, Buttons, Badges, Cards |
| `mrh_product.css` | Produktseite: Gallery, Tabs, Options, Trust |
| `mrh_category.css` | Kategorieseite: Listing, Filter, Pagination |
| `mrh_checkout.css` | Checkout: Steps, Summary, Payment |

---

## JavaScript-Richtlinien

### Vanilla JS (kein jQuery)

Alle neuen Module in reinem JavaScript (ES2020+). Kein jQuery, kein React, kein Vue. Bootstrap 5.3.0 JS-Komponenten (Modals, Tabs, Tooltips) ueber die Bootstrap-API nutzen.

### MRH Namespace

Alle Funktionen und Objekte im `MRH` Namespace registrieren. Keine globalen Variablen.

```javascript
// FALSCH
function openGallery() { ... }
var galleryIndex = 0;

// RICHTIG
MRH.Gallery = {
  currentIndex: 0,
  open() { ... }
};
```

### Event-Delegation

Events auf Container-Elemente binden, nicht auf einzelne Elemente. Nutze `MRH.DOM.delegate()` aus `mrh_core.js`.

### Data-Attribute statt onclick

Interaktionen ueber `data-*` Attribute steuern, nicht ueber `onclick` Handler.

```html
<!-- FALSCH -->
<button onclick="MRH.Gallery.open(3)">Bild 3</button>

<!-- RICHTIG -->
<button data-mrh-gallery="open" data-index="3">Bild 3</button>
```

---

## Smarty-Template-Richtlinien

### Bestehende REVPLUS-Variablen beibehalten

Alle Smarty-Variablen aus dem REVPLUS-Template muessen erhalten bleiben. Neue Variablen werden ueber PHP-Autoinclude-Dateien zugewiesen.

### Bedingte Ausgabe

Smarty-Variablen immer auf Existenz pruefen:

```smarty
{if $mrh_product_schema_ld}
  {$mrh_product_schema_ld}
{/if}
```

### Keine PHP-Logik im Template

Komplexe Logik gehoert in die PHP-Autoinclude-Dateien. Templates enthalten nur Ausgabe-Logik (`{if}`, `{foreach}`, `{$variable}`).

---

## Schema.org Richtlinien (2026)

### Pflicht-Schemas

| Schema | Seite | Pflicht ab |
|---|---|---|
| Organization | Alle | 2024 |
| BreadcrumbList | Alle mit Breadcrumb | 2024 |
| Product + Offer | Produktseiten | 2024 |
| ShippingDetails | Produktseiten | 2025 |
| MerchantReturnPolicy | Produktseiten | 2025 |
| CollectionPage | Kategorieseiten | 2025 |
| FAQPage | FAQ-Seiten | 2024 |

### JSON-LD Format

Immer JSON-LD verwenden (nicht Microdata oder RDFa). Die `MrhSchemaOrg` Klasse nutzen fuer konsistente Generierung.

---

## Git-Workflow

### Commit-Messages

Format: `[Modul] Kurzbeschreibung`

Beispiele:
- `[Schema] Product Schema mit ShippingDetails erweitert`
- `[Lang] Franzoesische Uebersetzungen fuer Checkout`
- `[CSS] Trust-Bar Responsive-Fix`
- `[JS] Gallery Keyboard-Navigation`

### Branch-Strategie

| Branch | Beschreibung |
|---|---|
| `master` | Stabile Version (= Live-Shop) |
| `develop` | Entwicklungsstand (= Test-Shop mr-hanf.at) |
| `feature/xxx` | Feature-Branches |
| `hotfix/xxx` | Dringende Fixes |
