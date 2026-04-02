<?php
/**
 * MRH Schema.org Helper Klasse
 * Version: 1.0.0
 * Datum: 2026-04-02
 * Autor: MRH Team
 *
 * Beschreibung:
 * Zentrale Klasse fuer Schema.org JSON-LD Generierung.
 * Wird von den Autoinclude-Modulen verwendet.
 *
 * Verwendung:
 *   require_once(DIR_FS_EXTERNAL . 'mrh_modules/MrhSchemaOrg.php');
 *   $schema = MrhSchemaOrg::product($productData);
 *   echo MrhSchemaOrg::toJsonLd($schema);
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

class MrhSchemaOrg
{
    /**
     * JSON-LD Script-Tag generieren
     *
     * @param array $data Schema.org Daten als Array
     * @return string HTML Script-Tag mit JSON-LD
     */
    public static function toJsonLd(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        return '<script type="application/ld+json">'
            . json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            )
            . '</script>';
    }

    /**
     * Organization Schema generieren
     *
     * @param string $name Firmenname
     * @param string $url Shop-URL
     * @param string $logo Logo-URL
     * @param array $sameAs Social-Media URLs
     * @return array Schema.org Organization
     */
    public static function organization(
        string $name,
        string $url,
        string $logo = '',
        array $sameAs = []
    ): array {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => $url,
        ];

        if (!empty($logo)) {
            $schema['logo'] = $logo;
        }

        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        $schema['contactPoint'] = [
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'availableLanguage' => ['German', 'English', 'French', 'Spanish'],
        ];

        return $schema;
    }

    /**
     * BreadcrumbList Schema generieren
     *
     * @param array $items Array von ['name' => '', 'url' => '']
     * @return array Schema.org BreadcrumbList
     */
    public static function breadcrumbList(array $items): array
    {
        if (empty($items)) {
            return [];
        }

        $listItems = [];
        foreach ($items as $position => $item) {
            $listItem = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
            ];
            if (!empty($item['url'])) {
                $listItem['item'] = $item['url'];
            }
            $listItems[] = $listItem;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }

    /**
     * Product Schema generieren (2026 Standard)
     *
     * @param array $data Produktdaten
     * @return array Schema.org Product
     */
    public static function product(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $data['name'] ?? '',
            'url' => $data['url'] ?? '',
        ];

        // Optionale Felder
        if (!empty($data['description'])) {
            $schema['description'] = strip_tags($data['description']);
        }
        if (!empty($data['sku'])) {
            $schema['sku'] = $data['sku'];
        }
        if (!empty($data['image'])) {
            $schema['image'] = is_array($data['image']) ? $data['image'] : [$data['image']];
        }
        if (!empty($data['brand'])) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $data['brand'],
            ];
        }

        // Offer (Pflicht 2026)
        if (!empty($data['price'])) {
            $schema['offers'] = self::offer($data);
        }

        // AggregateRating
        if (!empty($data['rating_avg']) && !empty($data['review_count']) && (int) $data['review_count'] > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $data['rating_avg'],
                'reviewCount' => $data['review_count'],
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        return $schema;
    }

    /**
     * Offer Schema mit ShippingDetails und ReturnPolicy (2026 Pflicht)
     *
     * @param array $data Produktdaten
     * @return array Schema.org Offer
     */
    public static function offer(array $data): array
    {
        $currency = $data['currency'] ?? (defined('MRH_SCHEMA_CURRENCY') ? MRH_SCHEMA_CURRENCY : 'EUR');
        $seller = $data['seller'] ?? (defined('MRH_SCHEMA_SELLER') ? MRH_SCHEMA_SELLER : STORE_NAME);

        $offer = [
            '@type' => 'Offer',
            'priceCurrency' => $currency,
            'price' => number_format((float) ($data['price'] ?? 0), 2, '.', ''),
            'availability' => $data['availability'] ?? 'https://schema.org/InStock',
            'seller' => [
                '@type' => 'Organization',
                'name' => $seller,
            ],
        ];

        if (!empty($data['url'])) {
            $offer['url'] = $data['url'];
        }

        // ShippingDetails (2026 Pflicht fuer Google Shopping)
        $offer['shippingDetails'] = [
            '@type' => 'OfferShippingDetails',
            'shippingRate' => [
                '@type' => 'MonetaryAmount',
                'value' => $data['shipping_cost'] ?? '0',
                'currency' => $currency,
            ],
            'deliveryTime' => [
                '@type' => 'ShippingDeliveryTime',
                'handlingTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => (int) ($data['handling_min'] ?? 0),
                    'maxValue' => (int) ($data['handling_max'] ?? 1),
                    'unitCode' => 'DAY',
                ],
                'transitTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => (int) ($data['transit_min'] ?? 1),
                    'maxValue' => (int) ($data['transit_max'] ?? 3),
                    'unitCode' => 'DAY',
                ],
            ],
            'shippingDestination' => [
                '@type' => 'DefinedRegion',
                'addressCountry' => $data['shipping_countries'] ?? ['DE', 'AT', 'CH'],
            ],
        ];

        // MerchantReturnPolicy (2026 Pflicht)
        $offer['hasMerchantReturnPolicy'] = [
            '@type' => 'MerchantReturnPolicy',
            'applicableCountry' => $data['return_countries'] ?? ['DE', 'AT'],
            'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
            'merchantReturnDays' => (int) ($data['return_days'] ?? 14),
            'returnMethod' => 'https://schema.org/ReturnByMail',
        ];

        return $offer;
    }

    /**
     * CollectionPage Schema fuer Kategorieseiten
     *
     * @param string $name Kategoriename
     * @param string $url Kategorie-URL
     * @param string $description Kategoriebeschreibung
     * @param array $items Produkte als Array von ['name' => '', 'url' => '']
     * @return array Schema.org CollectionPage
     */
    public static function collectionPage(
        string $name,
        string $url,
        string $description = '',
        array $items = []
    ): array {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $name,
            'url' => $url,
        ];

        if (!empty($description)) {
            $schema['description'] = strip_tags($description);
        }

        if (!empty($items)) {
            $listItems = [];
            foreach ($items as $position => $item) {
                $listItems[] = [
                    '@type' => 'ListItem',
                    'position' => $position + 1,
                    'url' => $item['url'] ?? '',
                    'name' => $item['name'] ?? '',
                ];
            }
            $schema['mainEntity'] = [
                '@type' => 'ItemList',
                'numberOfItems' => count($items),
                'itemListElement' => $listItems,
            ];
        }

        return $schema;
    }

    /**
     * FAQPage Schema
     *
     * @param array $faqs Array von ['question' => '', 'answer' => '']
     * @return array Schema.org FAQPage
     */
    public static function faqPage(array $faqs): array
    {
        if (empty($faqs)) {
            return [];
        }

        $mainEntity = [];
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }
}
