<?php
/**
 * MRH Autoinclude: Produkt-Smarty-Erweiterungen
 * Hookpoint: ~/includes/extra/application_top/application_top_end/
 * Version: 1.0.0
 * Datum: 2026-04-02
 * Autor: MRH Team
 *
 * Beschreibung:
 * Stellt zusaetzliche Smarty-Variablen fuer die Produktseite bereit.
 * Beinhaltet: Schema.org Product, erweiterte Produktdaten, Trust-Badges.
 * Wird NUR auf Produktseiten ausgefuehrt (Performance).
 *
 * Abhaengigkeiten:
 * - 10_mrh_schema_org.php (globale Schemas)
 * - Sprachdatei: lang/[SPRACHE]/extra/mrh_product.php
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

// Nur auf Produktseiten ausfuehren
if (basename($PHP_SELF) !== FILENAME_PRODUCT_INFO . '.php'
    && basename($PHP_SELF) !== 'product_info.php') {
    return;
}

if (isset($smarty) && is_object($smarty) && isset($product) && is_object($product)) {

    // ---------------------------------------------------------------
    // 1. Erweiterte Produktdaten fuer Template
    // ---------------------------------------------------------------

    // Preis ohne HTML-Tags fuer Schema.org
    $mrh_price_raw = '';
    if (isset($product->data['products_price'])) {
        $mrh_price_raw = number_format((float) $product->data['products_price'], 2, '.', '');
    }

    // Bewertungsdaten
    $mrh_rating_avg = '0';
    $mrh_review_count = '0';
    if (function_exists('xtc_get_products_reviews_average')) {
        $mrh_rating_data = xtc_get_products_reviews_average($product->data['products_id']);
        if ($mrh_rating_data) {
            $mrh_rating_avg = number_format((float) $mrh_rating_data['average'], 1, '.', '');
            $mrh_review_count = (string) (int) $mrh_rating_data['count'];
        }
    }

    // Canonical URL
    $mrh_canonical_url = xtc_href_link(
        FILENAME_PRODUCT_INFO,
        xtc_product_link($product->data['products_id'], $product->data['products_name']),
        'SSL',
        false
    );

    // Hauptbild URL
    $mrh_product_image = '';
    if (!empty($product->data['products_image'])) {
        $mrh_product_image = HTTPS_SERVER . DIR_WS_CATALOG
            . DIR_WS_IMAGES . 'product_images/original_images/'
            . $product->data['products_image'];
    }

    // Hersteller
    $mrh_manufacturer_name = '';
    if (!empty($product->data['manufacturers_name'])) {
        $mrh_manufacturer_name = $product->data['manufacturers_name'];
    }

    // Verfuegbarkeit
    $mrh_availability = 'https://schema.org/InStock';
    if (isset($product->data['products_quantity']) && $product->data['products_quantity'] <= 0) {
        $mrh_availability = 'https://schema.org/OutOfStock';
    }

    // SKU / Modellnummer
    $mrh_sku = $product->data['products_model'] ?? '';

    // ---------------------------------------------------------------
    // 2. Schema.org Product JSON-LD (2026 Standard)
    // ---------------------------------------------------------------

    $mrh_schema_product = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->data['products_name'],
        'description' => strip_tags($product->data['products_short_description'] ?? $product->data['products_description'] ?? ''),
        'sku' => $mrh_sku,
        'image' => [$mrh_product_image],
        'url' => $mrh_canonical_url,
    ];

    // Brand hinzufuegen
    if (!empty($mrh_manufacturer_name)) {
        $mrh_schema_product['brand'] = [
            '@type' => 'Brand',
            'name' => $mrh_manufacturer_name,
        ];
    }

    // Offer mit Shipping und Return Policy (2026 Pflicht)
    $mrh_schema_product['offers'] = [
        '@type' => 'Offer',
        'url' => $mrh_canonical_url,
        'priceCurrency' => defined('MRH_SCHEMA_CURRENCY') ? MRH_SCHEMA_CURRENCY : 'EUR',
        'price' => $mrh_price_raw,
        'availability' => $mrh_availability,
        'seller' => [
            '@type' => 'Organization',
            'name' => defined('MRH_SCHEMA_SELLER') ? MRH_SCHEMA_SELLER : STORE_NAME,
        ],
        'shippingDetails' => [
            '@type' => 'OfferShippingDetails',
            'shippingRate' => [
                '@type' => 'MonetaryAmount',
                'value' => '0',
                'currency' => defined('MRH_SCHEMA_CURRENCY') ? MRH_SCHEMA_CURRENCY : 'EUR',
            ],
            'deliveryTime' => [
                '@type' => 'ShippingDeliveryTime',
                'handlingTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 0,
                    'maxValue' => 1,
                    'unitCode' => 'DAY',
                ],
                'transitTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 1,
                    'maxValue' => 3,
                    'unitCode' => 'DAY',
                ],
            ],
            'shippingDestination' => [
                '@type' => 'DefinedRegion',
                'addressCountry' => ['DE', 'AT', 'CH'],
            ],
        ],
        'hasMerchantReturnPolicy' => [
            '@type' => 'MerchantReturnPolicy',
            'applicableCountry' => ['DE', 'AT'],
            'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
            'merchantReturnDays' => 14,
            'returnMethod' => 'https://schema.org/ReturnByMail',
        ],
    ];

    // AggregateRating (nur wenn Bewertungen vorhanden)
    if ((int) $mrh_review_count > 0) {
        $mrh_schema_product['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $mrh_rating_avg,
            'reviewCount' => $mrh_review_count,
            'bestRating' => '5',
            'worstRating' => '1',
        ];
    }

    // JSON-LD generieren
    $mrh_product_schema_ld = '<script type="application/ld+json">'
        . json_encode($mrh_schema_product, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        . '</script>';

    // ---------------------------------------------------------------
    // 3. Smarty-Variablen zuweisen
    // ---------------------------------------------------------------

    $smarty->assign([
        'mrh_price_raw'          => $mrh_price_raw,
        'mrh_rating_avg'         => $mrh_rating_avg,
        'mrh_review_count'       => $mrh_review_count,
        'mrh_canonical_url'      => $mrh_canonical_url,
        'mrh_product_image'      => $mrh_product_image,
        'mrh_manufacturer_name'  => $mrh_manufacturer_name,
        'mrh_availability'       => $mrh_availability,
        'mrh_sku'                => $mrh_sku,
        'mrh_product_schema_ld'  => $mrh_product_schema_ld,
    ]);

    // Aufraeumen
    unset(
        $mrh_price_raw, $mrh_rating_avg, $mrh_review_count,
        $mrh_canonical_url, $mrh_product_image, $mrh_manufacturer_name,
        $mrh_availability, $mrh_sku, $mrh_schema_product, $mrh_product_schema_ld,
        $mrh_rating_data
    );
}
