# Deployment Guide

Anleitung zur Installation und Aktualisierung der MRH Autoinclude-Module.

---

## Voraussetzungen

- modified eCommerce 3.3.0
- PHP 8.1+
- Bootstrap 5.3.0 (im REVPLUS Template)
- SSH-Zugang zum Server

---

## Verzeichnisstruktur auf dem Server

```
/home/www/doc/28856/dcp288560004/mr-hanf.de/www/    (Live: mr-hanf.de)
/home/www/doc/28856/dcp288560004/mr-hanf.at/www/    (Test: mr-hanf.at)
```

---

## Erstinstallation

### 1. Repository klonen (lokal)

```bash
git clone git@github.com:mrhanf15-stack/mrh-modified-autoinclude.git
cd mrh-modified-autoinclude
```

### 2. Dateien auf Server kopieren

Die Dateien werden in die bestehende Shop-Struktur kopiert. Keine bestehenden Dateien werden ueberschrieben, da alles in Autoinclude-Ordner geht.

```bash
# Auf dem Server (Test-Shop zuerst!)
cd /home/www/doc/28856/dcp288560004/mr-hanf.at/www/

# PHP Autoinclude-Module
cp -r includes/extra/application_top/application_top_end/10_mrh_*.php \
      /pfad/zum/shop/includes/extra/application_top/application_top_end/

cp -r includes/extra/application_top/application_top_end/50_mrh_*.php \
      /pfad/zum/shop/includes/extra/application_top/application_top_end/

# Externe Klassen
mkdir -p /pfad/zum/shop/includes/external/mrh_modules/
cp -r includes/external/mrh_modules/*.php \
      /pfad/zum/shop/includes/external/mrh_modules/

# Sprachdateien
cp lang/german/extra/mrh_*.php  /pfad/zum/shop/lang/german/extra/
cp lang/english/extra/mrh_*.php /pfad/zum/shop/lang/english/extra/
cp lang/french/extra/mrh_*.php  /pfad/zum/shop/lang/french/extra/
cp lang/spanish/extra/mrh_*.php /pfad/zum/shop/lang/spanish/extra/

# CSS und JS
cp templates/revplus/css/mrh_*.css       /pfad/zum/shop/templates/revplus/css/
cp templates/revplus/javascript/mrh_*.js /pfad/zum/shop/templates/revplus/javascript/
```

### 3. CSS und JS im Template einbinden

In der Template-Hauptdatei (`templates/revplus/index.html` oder Header-Include):

```html
<!-- MRH CSS (nach Bootstrap) -->
<link rel="stylesheet" href="{$tpl_path}css/mrh_base.css">
<link rel="stylesheet" href="{$tpl_path}css/mrh_product.css">

<!-- MRH JS (vor </body>) -->
<script src="{$tpl_path}javascript/mrh_core.js"></script>
```

### 4. OPcache leeren

```bash
curl -s "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset"
```

---

## Update (einzelne Module)

```bash
# Beispiel: Nur Schema.org Modul aktualisieren
cd /home/www/doc/28856/dcp288560004/mr-hanf.at/www/

curl -sL "https://raw.githubusercontent.com/mrhanf15-stack/mrh-modified-autoinclude/master/includes/extra/application_top/application_top_end/10_mrh_schema_org.php" \
  -o includes/extra/application_top/application_top_end/10_mrh_schema_org.php

curl -s "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset"
```

---

## Workflow: Test -> Live

1. Aenderungen immer zuerst auf **mr-hanf.at** (Test) deployen
2. Testen: Schema.org mit Google Rich Results Test validieren
3. Testen: Alle 4 Sprachen pruefen (DE, EN, FR, ES)
4. Testen: Mobile und Desktop pruefen
5. Wenn alles OK: Auf **mr-hanf.de** (Live) deployen

---

## Rollback

Falls ein Modul Probleme verursacht, einfach die Datei loeschen oder umbenennen:

```bash
# Modul deaktivieren (nicht loeschen)
mv includes/extra/application_top/application_top_end/10_mrh_schema_org.php \
   includes/extra/application_top/application_top_end/10_mrh_schema_org.php.disabled

# OPcache leeren
curl -s "https://mr-hanf.de/opcache_reset.php?token=MrHanf2024Reset"
```
