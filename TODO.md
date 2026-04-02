# MRH Modified Autoinclude - TODO Liste

**Stand:** 2026-04-02
**Legende:** `[ ]` Offen | `[x]` Erledigt | `[!]` Blockiert | `[-]` Entfaellt

---

## Phase 1: Grundlagen und Setup

### 1.1 Repository und Dokumentation
- [x] GitHub Repository erstellen (privat)
- [x] README.md mit Coding-Richtlinien
- [x] Ordnerstruktur anlegen
- [x] TODO.md erstellen
- [ ] CHANGELOG.md anlegen
- [ ] docs/CODING_GUIDELINES.md (ausfuehrliche Version)
- [ ] docs/SCHEMA_ORG_2026.md (Implementierungsguide)
- [ ] docs/MIGRATION_CHECKLIST.md (Schritt-fuer-Schritt)
- [ ] docs/HOOKPOINT_REFERENCE.md (alle 3.3.0 Hookpoints)

### 1.2 Basis-Infrastruktur
- [ ] `mrh_base.css` erstellen (CSS Custom Properties, Reset)
- [ ] `mrh_core.js` erstellen (MRH Namespace, Utilities)
- [ ] Sprachdateien-Grundgeruest (DE, EN, FR, ES)
- [ ] `.gitkeep` in leere Ordner

---

## Phase 2: Bestandsaufnahme mr-hanf.de (Live)

### 2.1 Autoinclude-Module identifizieren
- [ ] `includes/extra/` Ordner auf mr-hanf.de auflisten
- [ ] `admin/includes/extra/` Ordner auflisten
- [ ] `lang/*/extra/` Sprachdateien auflisten
- [ ] `includes/external/` eigene Module auflisten
- [ ] Template-Overrides in REVPLUS identifizieren

### 2.2 Abhaengigkeiten dokumentieren
- [ ] Welche Module voneinander abhaengen
- [ ] Welche DB-Tabellen von Modulen erstellt wurden
- [ ] Welche Konfigurationswerte in der DB stehen
- [ ] Welche externen APIs/Services genutzt werden

### 2.3 Template-Analyse REVPLUS
- [ ] Alle Smarty-Variablen in product_info.html dokumentieren
- [ ] Alle Smarty-Variablen in index.html dokumentieren
- [ ] Alle Smarty-Variablen in product_listing.html dokumentieren
- [ ] Alle Smarty-Includes identifizieren
- [ ] CSS-Dateien und deren Abhaengigkeiten
- [ ] JS-Dateien und deren Abhaengigkeiten

---

## Phase 3: Template-Migration (REVPLUS -> Bootstrap 5.3.0)

### 3.1 Basis-Templates
- [ ] `templates/revplus/css/mrh_base.css` - CSS Variables, Typography, Reset
- [ ] `templates/revplus/css/mrh_layout.css` - Grid, Container, Responsive
- [ ] `templates/revplus/javascript/mrh_core.js` - Namespace, Event-System

### 3.2 Header und Navigation
- [ ] Header-Template analysieren und migrieren
- [ ] Navigation auf Bootstrap 5 Navbar umstellen
- [ ] Mobile-Navigation (Offcanvas) implementieren
- [ ] Sprachwaehler integrieren
- [ ] Warenkorb-Widget im Header
- [ ] Suchfeld mit Autocomplete
- [ ] Inline-CSS entfernen -> `mrh_header.css`
- [ ] Inline-JS entfernen -> `mrh_header.js`
- [ ] Sprachkonstanten fuer Header (DE, EN, FR, ES)

### 3.3 Footer
- [ ] Footer-Template analysieren und migrieren
- [ ] Bootstrap 5 Grid fuer Footer-Spalten
- [ ] Newsletter-Formular
- [ ] Social-Media Links
- [ ] Payment-Icons
- [ ] Trust-Badges
- [ ] Inline-CSS entfernen -> `mrh_footer.css`
- [ ] Sprachkonstanten fuer Footer (DE, EN, FR, ES)

### 3.4 Produktseite (product_info.html)
- [ ] Bildergalerie mit Thumbnails (Vanilla JS)
- [ ] Produkttitel (H1) mit Smarty-Variable
- [ ] Sterne-Bewertung (dynamisch)
- [ ] Short Description
- [ ] Quick-Spec Chips/Tags
- [ ] Preis-Bereich (aktuell, alt, VPE, Steuer)
- [ ] Versand-Status mit Tooltip
- [ ] Produktoptionen (Packungsgroessen)
- [ ] Staffelpreise
- [ ] Mengensteuerung (+/-)
- [ ] Warenkorb-Button (Bootstrap 5)
- [ ] Wunschliste-Button
- [ ] Express-Checkout / PayPal
- [ ] Trust-Bar (4 Icons)
- [ ] Tab: Beschreibung
- [ ] Tab: Spezifikationen (Tabelle)
- [ ] Tab: Bilder/Galerie
- [ ] Tab: Media (Videos)
- [ ] Tab: Bewertungen mit Rating-Bars
- [ ] Tab: Cross-Selling
- [ ] Tab: Auch gekauft
- [ ] Hersteller-Accordion (EU-Pflicht)
- [ ] Trusted Shops Widget
- [ ] Inline-CSS entfernen -> `mrh_product.css`
- [ ] Inline-JS entfernen -> `mrh_product.js`
- [ ] Sprachkonstanten Produktseite (DE, EN, FR, ES)
- [ ] Schema.org Product JSON-LD
- [ ] Schema.org BreadcrumbList JSON-LD

### 3.5 Kategorieseite (product_listing.html)
- [ ] Kategorie-Header mit Beschreibung
- [ ] Filter-Sidebar (Bootstrap 5 Offcanvas mobile)
- [ ] Produktkarten (Grid/Liste umschaltbar)
- [ ] Sortierung (Dropdown)
- [ ] Pagination (Bootstrap 5)
- [ ] Inline-CSS entfernen -> `mrh_category.css`
- [ ] Inline-JS entfernen -> `mrh_category.js`
- [ ] Sprachkonstanten Kategorieseite (DE, EN, FR, ES)
- [ ] Schema.org CollectionPage JSON-LD
- [ ] Schema.org ItemList JSON-LD

### 3.6 Startseite (index.html)
- [ ] Hero-Banner / Slider
- [ ] Kategorie-Highlights
- [ ] Bestseller-Karussell
- [ ] Neuheiten
- [ ] Trust-Bereich
- [ ] SEO-Text Bereich
- [ ] Inline-CSS entfernen -> `mrh_home.css`
- [ ] Inline-JS entfernen -> `mrh_home.js`
- [ ] Sprachkonstanten Startseite (DE, EN, FR, ES)
- [ ] Schema.org WebPage JSON-LD

### 3.7 Checkout
- [ ] Checkout-Steps analysieren
- [ ] Bootstrap 5 Stepper/Progress
- [ ] Formular-Validierung (Vanilla JS)
- [ ] Zahlungsarten-Auswahl
- [ ] Bestelluebersicht
- [ ] Inline-CSS entfernen -> `mrh_checkout.css`
- [ ] Inline-JS entfernen -> `mrh_checkout.js`
- [ ] Sprachkonstanten Checkout (DE, EN, FR, ES)

---

## Phase 4: Schema.org 2026

### 4.1 Globale Schemas
- [ ] Organization Schema (alle Seiten)
- [ ] WebSite Schema mit SearchAction (alle Seiten)
- [ ] BreadcrumbList Schema (alle Seiten)
- [ ] SiteNavigationElement Schema (Startseite)

### 4.2 Produkt-Schemas
- [ ] Product Schema mit allen Pflichtfeldern
- [ ] Offer Schema mit Preis, Verfuegbarkeit, Waehrung
- [ ] AggregateRating Schema
- [ ] Review Schema (einzelne Bewertungen)
- [ ] ShippingDetails Schema (Lieferzeit, Kosten)
- [ ] MerchantReturnPolicy Schema (Rueckgabe)
- [ ] Brand Schema

### 4.3 Kategorie-Schemas
- [ ] CollectionPage Schema
- [ ] ItemList Schema mit ListItems
- [ ] Offer Aggregate (Preisspanne)

### 4.4 Content-Schemas
- [ ] FAQPage Schema fuer FAQ-Seiten
- [ ] Article/BlogPosting Schema fuer Ratgeber
- [ ] HowTo Schema fuer Anbau-Guides

### 4.5 Validierung
- [ ] Google Rich Results Test fuer Produktseiten
- [ ] Google Rich Results Test fuer Kategorieseiten
- [ ] Schema.org Validator fuer alle Typen
- [ ] Google Search Console Monitoring einrichten

---

## Phase 5: SEO-Optimierung 2026

### 5.1 Technisches SEO
- [ ] Canonical-Tags korrekt setzen (Autoinclude)
- [ ] Hreflang-Tags fuer DE/EN/FR/ES
- [ ] Meta-Robots pro Seitentyp
- [ ] Open Graph Tags (Facebook, LinkedIn)
- [ ] Twitter Card Tags
- [ ] Preload/Prefetch fuer kritische Ressourcen
- [ ] Lazy Loading fuer Bilder (native + Fallback)

### 5.2 Core Web Vitals
- [ ] LCP optimieren (Hauptbild Preload)
- [ ] CLS vermeiden (Bildgroessen definieren)
- [ ] INP optimieren (Event-Handler effizient)
- [ ] Font-Display: swap fuer Web-Fonts
- [ ] CSS Critical Path (Above-the-fold inline)

### 5.3 Content-SEO
- [ ] H1-Struktur pro Seitentyp pruefen
- [ ] Heading-Hierarchie (H1 > H2 > H3)
- [ ] Alt-Texte fuer alle Bilder
- [ ] Interne Verlinkung in Templates
- [ ] Breadcrumb-Navigation (visuell + Schema)

---

## Phase 6: Autoinclude PHP-Module

### 6.1 Basis-Module
- [ ] `10_mrh_config.php` - Konfiguration und Konstanten
- [ ] `10_mrh_functions.php` - Hilfsfunktionen
- [ ] `20_mrh_schema_org.php` - Schema.org Generator-Klasse

### 6.2 Template-Erweiterungen
- [ ] `50_mrh_product_smarty.php` - Zusaetzliche Smarty-Variablen Produktseite
- [ ] `50_mrh_category_smarty.php` - Zusaetzliche Smarty-Variablen Kategorieseite
- [ ] `50_mrh_header_smarty.php` - Header Smarty-Variablen
- [ ] `50_mrh_footer_smarty.php` - Footer Smarty-Variablen

### 6.3 SEO-Module
- [ ] `30_mrh_canonical.php` - Canonical-Tag Logik
- [ ] `30_mrh_hreflang.php` - Hreflang-Tag Generierung
- [ ] `30_mrh_meta_tags.php` - Meta-Tags (OG, Twitter, Robots)
- [ ] `30_mrh_breadcrumb.php` - Breadcrumb-Generierung

### 6.4 FPC-Integration
- [ ] FPC-Modul Kompatibilitaet pruefen
- [ ] Cache-Invalidierung bei Aenderungen
- [ ] Preloader-Kompatibilitaet

---

## Phase 7: Migration mr-hanf.de -> mr-hanf.at

### 7.1 Vorbereitung
- [ ] mr-hanf.at Zugang pruefen (SSH, Admin)
- [ ] modified-shop Version auf mr-hanf.at pruefen (3.3.0)
- [ ] REVPLUS Template auf mr-hanf.at pruefen
- [ ] Backup von mr-hanf.at erstellen
- [ ] Datenbank-Dump von mr-hanf.at

### 7.2 Deployment
- [ ] Sprachdateien deployen (DE, EN, FR, ES)
- [ ] CSS-Dateien ins Template kopieren
- [ ] JS-Dateien ins Template kopieren
- [ ] PHP Autoinclude-Dateien deployen
- [ ] Smarty-Templates deployen
- [ ] Schema.org Templates deployen
- [ ] OPcache leeren
- [ ] Smarty-Cache leeren

### 7.3 Testing
- [ ] Startseite pruefen (Desktop + Mobile)
- [ ] Produktseite pruefen (Desktop + Mobile)
- [ ] Kategorieseite pruefen (Desktop + Mobile)
- [ ] Warenkorb-Funktion testen
- [ ] Checkout-Funktion testen
- [ ] Sprachumschaltung testen (DE/EN/FR/ES)
- [ ] Schema.org mit Google Rich Results Test
- [ ] Core Web Vitals mit PageSpeed Insights
- [ ] Broken Links pruefen
- [ ] Console-Errors pruefen (Browser DevTools)

### 7.4 Go-Live Checkliste
- [ ] Alle Tests bestanden
- [ ] Redirects von alten URLs eingerichtet
- [ ] Google Search Console verifiziert
- [ ] Sitemap.xml aktualisiert
- [ ] robots.txt geprueft
- [ ] SSL-Zertifikat aktiv
- [ ] Analytics/Tracking aktiv

---

## Notizen

### Bekannte Probleme mr-hanf.de
- Inline-CSS in vielen Templates
- Inline-JS mit jQuery-Abhaengigkeiten
- Fehlende Sprachkonstanten (hardcodierte deutsche Texte)
- Veraltetes Schema.org (Microdata statt JSON-LD)
- Keine Hreflang-Tags
- Bootstrap 4 -> Migration auf 5.3.0 noetig

### Prioritaeten
1. **Hoch:** Produktseite (umsatzrelevant)
2. **Hoch:** Schema.org (SEO-Impact)
3. **Mittel:** Kategorieseite
4. **Mittel:** Header/Footer
5. **Niedrig:** Startseite (wird seltener geaendert)
6. **Niedrig:** Checkout (funktioniert bereits)
