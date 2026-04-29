<?php
/**
 * age_verification.php
 *
 * Age verification modal – BS5.3 + Vanilla JS rewrite
 * Now with multi-language support and browser language detection
 *
 * @version     2.1.0 - 29. Apr 2026
 * @author      Original: Jens Justen <support@web-looks.de>
 *              Rewrite:  MRH N-Trade GmbH (BS5.3 + Vanilla JS + i18n)
 * @copyright   Copyright (c) 2019-2026
 * @link        http://www.web-looks.de
 * @package     age verification
 * @since       Version 1.0
 */

// check for bots
$is_bot = (!empty($truncate_session_id) ? true : (xtc_check_agent() == 1 ? true : false));

if (defined('MODULE_AGE_VERIFICATION_STATUS')
        && MODULE_AGE_VERIFICATION_STATUS == 'true'
        && empty($_COOKIE['age_verification'])
        && !$is_bot
) {
        $output = '';

        // set expires date
        $expires = (MODULE_AGE_VERIFICATION_DAYS != '' ? (int)MODULE_AGE_VERIFICATION_DAYS : 0);
        $expires_js = '';
        if ($expires > 0) {
                $date = new Datetime('+'.$expires.' days');
                $expires_js = $date->format('r');
        }

        // Resolve template image path dynamically
        $tpl_dir = defined('CURRENT_TEMPLATE') ? CURRENT_TEMPLATE : 'tpl_mrh_2026';
        $logo_path = '/templates/' . $tpl_dir . '/img/logo_head.png';

        // ── i18n: Use language constants from the active session language ──
        // The shop loads lang/{language}/extra/age_verification.php automatically,
        // so TEXT_AGE_VERIFICATION_* constants match the current session language.
        $title    = defined('TEXT_AGE_VERIFICATION_TITLE') ? TEXT_AGE_VERIFICATION_TITLE : 'Bitte bestätige dein Alter';
        $subtitle = (defined('TEXT_AGE_VERIFICATION_SUBTITLE') && TEXT_AGE_VERIFICATION_SUBTITLE != '') ? TEXT_AGE_VERIFICATION_SUBTITLE : 'Der Inhalt ist nur für Erwachsene ab <span class="text-danger fw-bold">18+</span> bestimmt.';
        $btn_ok   = defined('TEXT_AGE_VERIFICATION_BUTTON_CONFIRM') ? TEXT_AGE_VERIFICATION_BUTTON_CONFIRM : 'Ich bin 18 oder älter';
        $btn_no   = defined('TEXT_AGE_VERIFICATION_BUTTON_CANCEL') ? TEXT_AGE_VERIFICATION_BUTTON_CANCEL : 'Ich bin unter 18';

        // Strip <br> / <br /> tags from title for cleaner display in BS5 modal
        $title_clean = trim(strip_tags($title, '<span><strong><b><em>'));

        // Escape for safe JS embedding
        $title_js    = addslashes($title_clean);
        $subtitle_js = addslashes($subtitle);
        $btn_ok_js   = addslashes($btn_ok);
        $btn_no_js   = addslashes($btn_no);

        // ── Build translation map for browser-language fallback ──
        // When the session language is "german" but the browser prefers "fr",
        // the JS will swap the texts client-side.
        $translations_json = json_encode([
                'de' => [
                        'title'    => trim(strip_tags(defined('TEXT_AGE_VERIFICATION_TITLE') ? TEXT_AGE_VERIFICATION_TITLE : 'Bitte bestätige dein Alter', '<span><strong><b><em>')),
                        'subtitle' => 'Der Inhalt ist nur für Erwachsene ab <span class="text-danger fw-bold">18+</span> bestimmt.',
                        'btn_ok'   => 'Ich bin 18 oder älter',
                        'btn_no'   => 'Ich bin unter 18',
                ],
                'en' => [
                        'title'    => 'Please confirm your age',
                        'subtitle' => 'The content is intended for adults <span class="text-danger fw-bold">18+</span> only.',
                        'btn_ok'   => 'I am 18 or older',
                        'btn_no'   => 'I am under 18',
                ],
                'fr' => [
                        'title'    => 'Confirmez votre âge',
                        'subtitle' => 'Le contenu est réservé aux adultes de plus de <span class="text-danger fw-bold">18+</span>.',
                        'btn_ok'   => 'J\'ai 18 ans ou plus',
                        'btn_no'   => 'J\'ai moins de 18 ans',
                ],
                'es' => [
                        'title'    => 'Confirme su edad',
                        'subtitle' => 'El contenido está dirigido exclusivamente a adultos <span class="text-danger fw-bold">18+</span>.',
                        'btn_ok'   => 'Tengo 18 años o más',
                        'btn_no'   => 'Tengo menos de 18 años',
                ],
                'tr' => [
                        'title'    => 'Yaşını Onayla',
                        'subtitle' => 'İçerik sadece <span class="text-danger fw-bold">18+</span> yaşındaki yetişkinler içindir.',
                        'btn_ok'   => '18 yaşındayım veya daha büyüğüm',
                        'btn_no'   => '18 yaşından küçüğüm',
                ],
                'it' => [
                        'title'    => 'Conferma la tua età',
                        'subtitle' => 'Il contenuto è destinato esclusivamente agli adulti <span class="text-danger fw-bold">18+</span>.',
                        'btn_ok'   => 'Ho 18 anni o più',
                        'btn_no'   => 'Ho meno di 18 anni',
                ],
                'nl' => [
                        'title'    => 'Bevestig je leeftijd',
                        'subtitle' => 'De inhoud is alleen bedoeld voor volwassenen van <span class="text-danger fw-bold">18+</span>.',
                        'btn_ok'   => 'Ik ben 18 of ouder',
                        'btn_no'   => 'Ik ben jonger dan 18',
                ],
        ], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);

        // Determine current shop language code for JS
        $current_lang_code = 'de';
        if (isset($_SESSION['language'])) {
                $lang_map = [
                        'german'  => 'de',
                        'english' => 'en',
                        'french'  => 'fr',
                        'spanish' => 'es',
                        'turkye'  => 'tr',
                        'italian' => 'it',
                        'dutch'   => 'nl',
                ];
                $current_lang_code = isset($lang_map[$_SESSION['language']]) ? $lang_map[$_SESSION['language']] : 'de';
        }

        /**
         * default modal (modalBox) – legacy, kept for backwards compatibility
         */
        if (MODULE_AGE_VERIFICATION_MODAL == 'default') {

                // include modalBox script
                $output .= '<script src="'.DIR_WS_BASE.'includes/javascript/modalBox.min.js" type="text/javascript"></script>'."\n";

                $output .= '<script type="text/javascript">'."\n";
                $output .= '$(document).ready(function () {';
                $output .= 'if (document.cookie.indexOf("age_verification=true") < 0) {';
                $output .= 'var modalContent = \'<div class="content"><h3 class="title">'.$title_js.'</h3>'.($subtitle_js ? '<div class="subtitle">'.$subtitle_js.'</div>' : '').'<button class="button-confim">'.$btn_ok_js.'</button><div class="button-cancel-wrap"><a href="javascript:history.back()" class="button-cancel">'.$btn_no_js.'</a></div></div>\';';
                $output .= '$("html").addClass("no-scroll");';
                $output .= 'var modalDiv = $("<div />").attr("id", "ageVerification").html(modalContent);';
                $output .= '$("body").append(modalDiv);';
                $output .= '$("#ageVerification").modalBox({iconClose:false,keyClose:false,bodyClose:false,width:'.MODULE_AGE_VERIFICATION_WIDTH.',height:'.MODULE_AGE_VERIFICATION_HEIGHT.'});';
                $output .= '$("#ageVerification .button-confim").click(function(){';
                $output .= 'document.cookie="age_verification=true; expires='.$expires_js.'; path=/";';
                $output .= '$("#ageVerification").modalBox("close");';
                $output .= '$("html").removeClass("no-scroll");';
                $output .= '});';
                $output .= '}';
                $output .= '});';
                $output .= '</script>'."\n";


        /**
         * Bootstrap 5.3 modal – Vanilla JS, Wireframe design
         * Now uses language constants + browser language detection
         */
        } else if (MODULE_AGE_VERIFICATION_MODAL == 'bootstrap') {

                $output .= <<<AGEHTML

<!-- Age Verification Modal (BS5.3 / Vanilla JS / v2.1.0 – i18n) -->
<div class="modal fade" id="ageVerification" tabindex="-1" aria-labelledby="ageVerificationLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px; margin:1rem auto;">
    <div class="modal-content border-0 shadow-lg" style="border-radius:1rem;">
      <div class="modal-body text-center px-4 pt-4 pb-3">
        <img src="{$logo_path}" alt="Mr. Hanf" class="img-fluid mb-3" style="max-height:120px;">
        <h4 class="fw-bold mb-2" id="ageVerificationLabel">{$title_clean}</h4>
        <p class="text-secondary mb-1" id="ageVerificationSubtitle">{$subtitle}</p>
      </div>
      <div class="modal-footer flex-column border-0 px-4 pb-4 pt-0 gap-2">
        <button type="button" class="btn btn-success w-100 fw-semibold age-confirm" style="font-size:1.15rem; border-radius:0.5rem;">{$btn_ok}</button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary w-100 fw-semibold age-cancel" style="font-size:1.15rem; border-radius:0.5rem;">{$btn_no}</a>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  if(document.cookie.indexOf('age_verification=true')>=0) return;
  var el=document.getElementById('ageVerification');
  if(!el) return;

  /* ── Language detection: URL > Session > Browser ── */
  var translations = {$translations_json};
  var shopLang = '{$current_lang_code}';

  // 1. Detect language from URL path (highest priority)
  var urlLang = null;
  var pathMatch = window.location.pathname.match(/^\/([a-z]{2})\//i);
  if (pathMatch) {
    var urlCode = pathMatch[1].toLowerCase();
    if (translations[urlCode]) urlLang = urlCode;
  }

  // 2. Detect browser preferred language (fallback)
  var browserLangs = navigator.languages || [navigator.language || navigator.userLanguage || ''];
  var browserLang = null;
  for (var i = 0; i < browserLangs.length; i++) {
    var code = browserLangs[i].substring(0, 2).toLowerCase();
    if (translations[code]) {
      browserLang = code;
      break;
    }
  }

  // Priority: URL language > Shop session language > Browser language
  var useLang = urlLang || shopLang || browserLang || 'de';

  // Apply translations if different from server-rendered language
  if (useLang !== shopLang && translations[useLang]) {
    var t = translations[useLang];
    var titleEl = el.querySelector('#ageVerificationLabel');
    var subtitleEl = el.querySelector('#ageVerificationSubtitle');
    var confirmBtn = el.querySelector('.age-confirm');
    var cancelBtn = el.querySelector('.age-cancel');
    if (titleEl) titleEl.innerHTML = t.title;
    if (subtitleEl) subtitleEl.innerHTML = t.subtitle;
    if (confirmBtn) confirmBtn.textContent = t.btn_ok;
    if (cancelBtn) cancelBtn.textContent = t.btn_no;
  }

  var m=new bootstrap.Modal(el,{keyboard:false,backdrop:'static'});
  m.show();
  el.querySelector('.age-confirm').addEventListener('click',function(){
    document.cookie='age_verification=true; expires={$expires_js}; path=/; SameSite=Lax';
    m.hide();
  });
  el.addEventListener('hidden.bs.modal',function(){
    el.remove();
    var bd=document.querySelector('.modal-backdrop');
    if(bd) bd.remove();
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
  });
})();
</script>
<!-- / Age Verification Modal -->
AGEHTML;

        }


    // output
        echo "\n<!-- Diese Seite nutzt die Alterspruefung v2.1.0 (BS5.3 + i18n) - Original: https://www.web-looks.de --> \n".
                 $output.
                 "\n<!-- / Alterspruefung -->\n";
}

?>
