<?php
/**
 * MRH Autoinclude: Schema.org 2026 Generator
 * Hookpoint: ~/includes/extra/application_top/application_top_end/
 * Version: 1.0.0
 * Datum: 2026-04-02
 * Autor: MRH Team
 *
 * Beschreibung:
 * Generiert Schema.org JSON-LD Structured Data fuer alle Seitentypen.
 * Wird nach der Shop-Initialisierung geladen, damit alle Smarty-Variablen
 * und Produktdaten bereits verfuegbar sind.
 *
 * Abhaengigkeiten:
 * - modified-shop 3.3.0
 * - REVPLUS Template
 * - Sprachdatei: lang/[SPRACHE]/extra/mrh_schema.php
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

// Klasse aus includes/external laden
if (file_exists(DIR_FS_EXTERNAL . 'mrh_modules/MrhSchemaOrg.php')) {
    require_once(DIR_FS_EXTERNAL . 'mrh_modules/MrhSchemaOrg.php');
}

/**
 * Schema.org Daten als Smarty-Variable bereitstellen.
 * Wird spaeter im Template als {$mrh_schema_json_ld} ausgegeben.
 */
if (isset($smarty) && is_object($smarty)) {

    // Globales Organization Schema (auf jeder Seite)
    $mrh_schema_organization = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => defined('MRH_SCHEMA_SELLER') ? MRH_SCHEMA_SELLER : STORE_NAME,
        'url' => xtc_href_link(FILENAME_DEFAULT, '', 'SSL', false),
        'logo' => HTTPS_SERVER . DIR_WS_CATALOG . 'images/banner/mrh_logo.png',
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'availableLanguage' => ['German', 'English', 'French', 'Spanish'],
        ],
        'sameAs' => [
            'https://www.facebook.com/mrhanf',
            'https://www.instagram.com/mr.hanf',
        ],
    ];

    // WebSite Schema mit SearchAction (auf jeder Seite)
    $mrh_schema_website = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => defined('MRH_SCHEMA_SELLER') ? MRH_SCHEMA_SELLER : STORE_NAME,
        'url' => xtc_href_link(FILENAME_DEFAULT, '', 'SSL', false),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => xtc_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords={search_term_string}', 'SSL', false),
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];

    // BreadcrumbList Schema (auf jeder Seite mit Breadcrumb)
    $mrh_schema_breadcrumb = [];
    if (isset($breadcrumb) && is_object($breadcrumb)) {
        $mrh_breadcrumb_items = [];
        $mrh_bc_position = 1;
        // Breadcrumb-Trail aus dem Shop-Objekt lesen
        if (method_exists($breadcrumb, 'trail')) {
            foreach ($breadcrumb->trail() as $bc_item) {
                $mrh_breadcrumb_items[] = [
                    '@type' => 'ListItem',
                    'position' => $mrh_bc_position,
                    'name' => $bc_item['title'],
                    'item' => $bc_item['link'] ?: null,
                ];
                $mrh_bc_position++;
            }
        }
        if (!empty($mrh_breadcrumb_items)) {
            $mrh_schema_breadcrumb = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $mrh_breadcrumb_items,
            ];
        }
    }

    // Alle Schemas zusammenfuegen
    $mrh_schema_all = [$mrh_schema_organization, $mrh_schema_website];
    if (!empty($mrh_schema_breadcrumb)) {
        $mrh_schema_all[] = $mrh_schema_breadcrumb;
    }

    // JSON-LD generieren (ohne HTML-Escaping, UTF-8)
    $mrh_schema_json_ld = '';
    foreach ($mrh_schema_all as $mrh_schema_item) {
        $mrh_schema_json_ld .= '<script type="application/ld+json">'
            . json_encode($mrh_schema_item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            . '</script>' . PHP_EOL;
    }

    // Smarty-Variable zuweisen
    $smarty->assign('mrh_schema_json_ld', $mrh_schema_json_ld);

    // Aufraeumen - keine globalen Variablen hinterlassen
    unset(
        $mrh_schema_organization,
        $mrh_schema_website,
        $mrh_schema_breadcrumb,
        $mrh_breadcrumb_items,
        $mrh_bc_position,
        $mrh_schema_all,
        $mrh_schema_item
    );
}
