# Hookpoint-Referenz modified-shop 3.3.0

Vollstaendige Liste aller verfuegbaren Autoinclude-Hookpoints fuer modified eCommerce 3.3.0.

---

## Frontend Hookpoints

### Application Lifecycle

| Hookpoint-Pfad | Beschreibung | Verfuegbar ab |
|---|---|---|
| `includes/extra/functions/` | Eigene PHP-Funktionen (frueh geladen) | 2.0.0.0 |
| `includes/extra/application_top/application_top_begin/` | Vor Shop-Initialisierung | 2.0.0.0 |
| `includes/extra/application_top/application_top_end/` | Nach Shop-Initialisierung | 2.0.0.0 |
| `includes/extra/application_bottom/` | Am Seitenende | 2.0.0.0 |

### Template / Content

| Hookpoint-Pfad | Beschreibung | Verfuegbar ab |
|---|---|---|
| `includes/extra/default/header/` | Header-Erweiterungen | 2.0.0.0 |
| `includes/extra/default/footer/` | Footer-Erweiterungen | 2.0.0.0 |
| `includes/extra/default/main_content/` | Hauptinhalt-Erweiterungen | 2.0.0.0 |
| `includes/extra/header/` | HTML-Head Erweiterungen | 2.0.0.0 |

### Warenkorb

| Hookpoint-Pfad | Beschreibung | Verfuegbar ab |
|---|---|---|
| `includes/extra/cart_actions/add_product_prepare_post/` | Vor Produkt hinzufuegen | 2.0.0.0 |
| `includes/extra/cart_actions/add_product_before_redirect/` | Nach Produkt hinzufuegen | 2.0.0.0 |
| `includes/extra/cart_actions/update_product_prepare_post/` | Vor Produkt aktualisieren | 2.0.0.0 |
| `includes/extra/cart_actions/update_product_before_redirect/` | Nach Produkt aktualisieren | 2.0.0.0 |
| `includes/extra/cart_actions/remove_product_prepare_get/` | Vor Produkt entfernen | 2.0.3.0 |
| `includes/extra/cart_actions/remove_product_before_redirect/` | Nach Produkt entfernen | 2.0.3.0 |
| `includes/extra/cart_actions/buy_now_prepare_get/` | Express-Kauf | 2.0.4.0 |
| `includes/extra/cart_actions/custom/` | Eigene Warenkorb-Aktionen | 2.0.1.0 |
| `includes/extra/shopping_cart/cart_requirements/` | Warenkorb-Anforderungen | 2.0.1.0 |

### Checkout

| Hookpoint-Pfad | Beschreibung | Verfuegbar ab |
|---|---|---|
| `includes/extra/checkout/checkout_requirements/` | Checkout-Voraussetzungen | 2.0.1.0 |
| `includes/extra/checkout/checkout_process_products/` | Bestellte Produkte verarbeiten | 2.0.0.0 |
| `includes/extra/checkout/checkout_process_attributes/` | Produktattribute verarbeiten | 2.0.0.0 |
| `includes/extra/checkout/checkout_process_download/` | Downloads verarbeiten | 2.0.0.0 |
| `includes/extra/checkout/checkout_process_products_end/` | Nach Produktverarbeitung | 3.0.0 |
| `includes/extra/checkout/checkout_process_order/` | Bestellung verarbeiten | 2.0.6.0 |
| `includes/extra/checkout/checkout_process_end/` | Nach Bestellabschluss | 2.0.0.0 |

### Account / Registrierung (NEU in 3.3.0)

| Hookpoint-Pfad | Beschreibung | Verfuegbar ab |
|---|---|---|
| `includes/extra/account/create_account_check_data` | Registrierungsdaten pruefen | 3.3.0 |
| `includes/extra/account/create_account_customer_data` | Kundendaten erweitern | 3.3.0 |
| `includes/extra/account/create_account_address_data` | Adressdaten erweitern | 3.3.0 |
| `includes/extra/account/create_account_before_redirect` | Vor Weiterleitung | 3.3.0 |
| `includes/extra/account/create_account_smarty_data` | Smarty-Variablen | 3.3.0 |

### SEO

| Hookpoint-Pfad | Beschreibung | Verfuegbar ab |
|---|---|---|
| `includes/extra/seo_url_mod/` | SEO-URL Modifikationen | 2.0.1.0 |

### Sonstige

| Hookpoint-Pfad | Beschreibung | Verfuegbar ab |
|---|---|---|
| `includes/extra/db_data/` | Datenbank-Daten erweitern | 3.2.0 |
| `includes/extra/db_query/` | Datenbank-Queries erweitern | 2.0.7.0 |
| `includes/extra/php_mail/` | E-Mail-Versand erweitern | 2.0.3.0 |
| `includes/extra/validate_password/` | Passwort-Validierung | 2.0.2.0 |
| `includes/extra/modules/wishlist_content/` | Wunschliste erweitern | 2.0.0.0 |

---

## Admin Hookpoints

| Hookpoint-Pfad | Beschreibung | Verfuegbar ab |
|---|---|---|
| `admin/includes/extra/menu/` | Admin-Menue erweitern | 2.0.0.0 |
| `admin/includes/extra/filenames/` | Admin-Dateinamen definieren | 2.0.0.0 |
| `admin/includes/extra/css/` | Admin CSS hinzufuegen | 2.0.0.0 |
| `admin/includes/extra/javascript/` | Admin JS hinzufuegen | 2.0.0.0 |
| `admin/includes/extra/footer/` | Admin Footer | 2.0.0.0 |
| `admin/includes/extra/application_top/application_top_end/` | Admin Init | 2.0.0.0 |
| `admin/includes/extra/modules/orders/orders_action/` | Bestellaktionen | 2.0.0.0 |
| `admin/includes/extra/modules/orders/orders_edit_products/action/` | Bestellung bearbeiten | 3.3.0 |
| `admin/includes/extra/modules/orders/orders_edit_products/data/` | Bestelldaten | 3.3.0 |
| `admin/includes/extra/modules/orders/orders_print/` | Bestellung drucken | 2.0.6.0 |
| `admin/includes/extra/csrf_exclusion/` | CSRF-Ausnahmen | 2.0.1.0 |

---

## Sprachdateien

| Hookpoint-Pfad | Beschreibung |
|---|---|
| `lang/german/extra/` | Deutsche Sprachkonstanten |
| `lang/english/extra/` | Englische Sprachkonstanten |
| `lang/french/extra/` | Franzoesische Sprachkonstanten |
| `lang/spanish/extra/` | Spanische Sprachkonstanten |

---

## Eigene Klassen und Bibliotheken

| Pfad | Beschreibung |
|---|---|
| `includes/external/mrh_modules/` | Eigene PHP-Klassen (manuell einbinden) |

---

## Wichtige Hinweise

Dateien in den Hookpoint-Ordnern werden **alphabetisch** geladen. Die Reihenfolge wird ueber den Dateinamen gesteuert. Alle Dateien MUESSEN den Sicherheitscheck `defined('_VALID_XTC') or die(...)` enthalten. Variablen aus dem aufrufenden Scope sind in den Autoinclude-Dateien verfuegbar (z.B. `$smarty`, `$product`, `$order`).
