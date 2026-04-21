<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace theme_apsolu\hook;

use context_system;
use core\hook\output\after_standard_main_region_html_generation;
use stdClass;
use moodle_url;

/**
 * Class to handle hook callbacks.
 *
 * @package    theme_apsolu
 * @copyright  2026 Université Rennes 2
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class output {
    /**
     * Fixture for adding a heading after the standard main region HTML generation.
     *
     * @param after_standard_main_region_html_generation $hook
     *
     * @return void
     */
    public static function after_standard_main_region_html_generation(after_standard_main_region_html_generation $hook): void {
        global $CFG, $OUTPUT;

        if ($CFG->theme !== 'apsolu') {
            // Applique le hook uniquement lorsque le thème APSOLU est utilisé. Cela évite d'obtenir une erreur fatale lorsque
            // le thème courant n'est pas le thème APSOLU.
            return;
        }

        if ($hook->renderer->is_homepage() === true) {
            // Le footer est déjà appliqué sur la page d'accueil.
            return;
        }

        // Données pour le footer.
        // Fenêtres modales.
        $context = context_system::instance();
        $component = 'theme_apsolu';
        $filearea = 'homepage';
        $options = ['context' => $context, 'clean' => false];

        $footer = new stdClass();
        $footer->accessibility_status = get_config($component, 'accessibility_status');
        $text = get_config($component, 'accessibility_doc_text');
        $content = file_rewrite_pluginfile_urls(
            $text,
            'pluginfile.php',
            $context->id,
            $component,
            $filearea,
            THEME_APSOLU_ACCESSIBILITY_DOC_TEXT
        );
        $footer->accessibility_doc_text = format_text($content, FORMAT_HTML, $options);

        $text = get_config($component, 'legal_notice_doc_text');
        $content = file_rewrite_pluginfile_urls(
            $text,
            'pluginfile.php',
            $context->id,
            $component,
            $filearea,
            THEME_APSOLU_LEGAL_NOTICE_DOC_TEXT
        );
        $footer->legal_notice_doc_text = format_text($content, FORMAT_HTML, $options);

        $text = get_config($component, 'confidential_doc_text');
        $content = file_rewrite_pluginfile_urls(
            $text,
            'pluginfile.php',
            $context->id,
            $component,
            $filearea,
            THEME_APSOLU_CONFIDENTIAL_DOC_TEXT
        );
        $footer->confidential_doc_text = format_text($content, FORMAT_HTML, $options);

        $text = get_config($component, 'contact_doc_text');
        $content = file_rewrite_pluginfile_urls(
            $text,
            'pluginfile.php',
            $context->id,
            $component,
            $filearea,
            THEME_APSOLU_CONTACT_DOC_TEXT
        );
        $footer->contact_doc_text = format_text($content, FORMAT_HTML, $options);

        if (empty(get_config('theme_apsolu', 'enable_rewrite_rules')) === true) {
            // Affiche les vraies URL.
            $footer->accessibility_doc_url = new moodle_url('/theme/apsolu/documents/public/accessibility.php');
            $footer->legal_notice_doc_url = new moodle_url('/theme/apsolu/documents/public/legal_notice.php');
            $footer->confidential_doc_url = new moodle_url('/theme/apsolu/documents/public/confidential.php');
            $footer->contact_doc_url = new moodle_url('/theme/apsolu/documents/public/contact.php');
        } else {
            // Affiche les URL réécrites.
            $footer->accessibility_doc_url = new moodle_url('/accessibilite');
            $footer->legal_notice_doc_url = new moodle_url('/mentions_legales');
            $footer->confidential_doc_url = new moodle_url('/confidentialite');
            $footer->contact_doc_url = new moodle_url('/contact');
        }

        // Liens personnalisés.
        $footer->nav_link_1_url = get_config($component, 'nav_link_1_url');
        $footer->nav_link_1_text = get_config($component, 'nav_link_1_text');

        $footer->nav_link_2_url = get_config($component, 'nav_link_2_url');
        $footer->nav_link_2_text = get_config($component, 'nav_link_2_text');

        $footer->nav_link_3_url = get_config($component, 'nav_link_3_url');
        $footer->nav_link_3_text = get_config($component, 'nav_link_3_text');

        $hook->add_html($OUTPUT->render_from_template('theme_apsolu/components/footer_light', $footer));
    }
}
