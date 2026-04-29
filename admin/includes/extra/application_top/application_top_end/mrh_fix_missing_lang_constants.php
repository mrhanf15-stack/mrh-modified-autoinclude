<?php
/**
 * Fix: Fehlende Sprachkonstanten für column_left.php
 * Problem: Bei MailBeez-AJAX-Requests (run_observer.php) wird column_left.php
 * eingebunden, aber die Admin-Sprachdatei ist zu diesem Zeitpunkt nicht geladen.
 * Lösung: Fallback-Definitionen für die betroffenen Konstanten.
 * Stand: 2026-04-29
 */
if (!defined('BOX_GOOGLE_ANALYTICS')) {
    define('BOX_GOOGLE_ANALYTICS', 'Google Analytics');
}
if (!defined('BOX_MATOMO_ANALYTICS')) {
    define('BOX_MATOMO_ANALYTICS', 'Matomo Analytics');
}
if (!defined('BOX_FACEBOOK_PIXEL')) {
    define('BOX_FACEBOOK_PIXEL', 'Facebook Pixel');
}
