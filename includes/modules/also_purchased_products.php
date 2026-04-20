<?php
/* -----------------------------------------------------------------------------------------
   $Id: also_purchased_products.php 15236 2023-06-14 06:51:22Z GTB $
   XT-Commerce - community made shopping
   http://www.xt-commerce.com
   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(also_purchased_products.php,v 1.21 2003/02/12); www.oscommerce.com
   (c) 2003      nextcommerce (also_purchased_products.php,v 1.9 2003/08/17); www.nextcommerce.org
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   MRH v1.0.0 – 2026-04-20: Badge-Enrichment fuer also_purchased hinzugefuegt.
   Gleiche Logik wie in product_listing_content_ready/mrh_product_attributes_listing.php
   ---------------------------------------------------------------------------------------*/
// include needed functions
require_once (DIR_FS_INC.'get_pictureset_data.inc.php');
$module_smarty = new Smarty();
$module_smarty->assign('language', $_SESSION['language']);
$module_smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
// set cache ID
if (!CacheCheck()) {
  $cache = false;
  $module_smarty->caching = 0;
  $cache_id = null;
} else {
  $cache = true;
  $module_smarty->caching = 1;
  $module_smarty->cache_lifetime = CACHE_LIFETIME;
  $module_smarty->cache_modified_check = CACHE_CHECK == 'true';
  $cache_id = md5('lID:'.$_SESSION['language'].'|csID:'.$_SESSION['customers_status']['customers_status_id'].'|pID:'.$product->data['products_id'].'|curr:'.$_SESSION['currency'].'|country:'.((isset($_SESSION['country'])) ? $_SESSION['country'] : ((isset($_SESSION['customer_country_id'])) ? $_SESSION['customer_country_id'] : STORE_COUNTRY)));
}
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/also_purchased.html', $cache_id) || !$cache) {
  $data = $product->getAlsoPurchased();
  if (count($data) > 0
      && count($data) >= MIN_DISPLAY_ALSO_PURCHASED
      )
  {
    /* ── MRH Badge-Enrichment (v1.0.0) ──────────────────────────────
       Gleiche Logik wie mrh_product_attributes_listing.php:
       Fuegt MRH_BADGES und MRH_MINI_TABLE zu jedem Produkt hinzu,
       damit das Template die serverseitigen Icon-Badges anzeigen kann.
       ─────────────────────────────────────────────────────────────── */
    if (class_exists('MrhProductAttributes')) {
        $mrh_pa_lang_id = (int)$_SESSION['languages_id'];
        $mrh_pa_min_fields = (int)MrhProductAttributes::getConfig('min_fields_for_display', 3);

        foreach ($data as &$mrh_pa_item) {
            if (!isset($mrh_pa_item['PRODUCTS_ID'])) continue;

            $mrh_pa_pid = (int)$mrh_pa_item['PRODUCTS_ID'];
            $mrh_pa_attrs = MrhProductAttributes::getAttributes($mrh_pa_pid, $mrh_pa_lang_id);

            // 1. Structured badges from DB
            $mrh_pa_struct_badges = '';
            if ($mrh_pa_attrs && (int)($mrh_pa_attrs['fields_filled'] ?? 0) >= $mrh_pa_min_fields) {
                $mrh_pa_struct_badges = MrhProductAttributes::buildBadgeHTML($mrh_pa_attrs);
                $mrh_pa_item['MRH_MINI_TABLE'] = MrhProductAttributes::buildMiniTable($mrh_pa_attrs, 'listing');
                $mrh_pa_item['MRH_IS_SEED'] = (bool)($mrh_pa_attrs['is_seed'] ?? true);
            } else {
                $mrh_pa_item['MRH_MINI_TABLE'] = '';
                $mrh_pa_item['MRH_IS_SEED'] = true;
            }

            // 2. Legacy badges from short_description
            $mrh_pa_legacy_badges = '';
            if (function_exists('mrh_extract_legacy_badges') && !empty($mrh_pa_item['PRODUCTS_SHORT_DESCRIPTION'])) {
                $mrh_pa_exclude = function_exists('mrh_detect_struct_badge_types') ? mrh_detect_struct_badge_types($mrh_pa_struct_badges) : [];
                $mrh_pa_legacy_badges = mrh_extract_legacy_badges($mrh_pa_item['PRODUCTS_SHORT_DESCRIPTION'], $mrh_pa_exclude);
            }

            // 3. Merge: structured first, then legacy
            if (function_exists('mrh_merge_badge_html')) {
                $mrh_pa_item['MRH_BADGES'] = mrh_merge_badge_html($mrh_pa_struct_badges, $mrh_pa_legacy_badges);
            } else {
                $mrh_pa_item['MRH_BADGES'] = $mrh_pa_struct_badges;
            }
            $mrh_pa_item['MRH_HAS_ATTRS'] = !empty($mrh_pa_item['MRH_BADGES']) || !empty($mrh_pa_item['MRH_MINI_TABLE']);
        }
        unset($mrh_pa_item);
    }
    /* ── Ende MRH Badge-Enrichment ─────────────────────────────────── */

    $module_smarty->assign('module_content', $data);
    if (defined('PICTURESET_BOX')) {
      $module_smarty->assign('pictureset_box', get_pictureset_data(PICTURESET_BOX));
    }
    if (defined('PICTURESET_ROW')) {
      $module_smarty->assign('pictureset_row', get_pictureset_data(PICTURESET_ROW));
    }
  }
}
$module = $module_smarty->fetch(CURRENT_TEMPLATE.'/module/also_purchased.html', $cache_id);
$info_smarty->assign('MODULE_also_purchased', !empty($module) ? trim($module) : $module);
