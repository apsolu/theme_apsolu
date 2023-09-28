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

/**
 * Gère l'édition des mentions légales.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* You will process some page parameters at the top here and get the info about
 what instance of your module and what course you're in etc. Make sure you
 include hidden variable in your forms which have their defaults set in set_data
 which pass these variables from page to page.*/

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Authorisation accès admin, doit être appelé sur chaque page admin.
admin_externalpage_setup('theme_apsolu_documents_legal_notice');

// On récupère le formulaire de personnalisation.
require_once($CFG->dirroot . '/theme/apsolu/documents/form/legal_notice_form.php');

// Définition des variables.
$component = 'theme_apsolu';
$filearea = 'homepage';
$editoroptions = theme_apsolu_legal_notice_form::get_editor_options();
$syscontext = context_system::instance();

// Construction du formulaire.
$defaults = new stdClass();
$defaults->custom_legal_notice_active = get_config('theme_apsolu', 'custom_legal_notice_active');

// Charge le contenu de l'éditeur de texte.
$defaults->legal_notice_doc_text = get_config('theme_apsolu', 'legal_notice_doc_text');
$defaults->legal_notice_doc_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'legal_notice_doc_text', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_LEGAL_NOTICE_DOC_TEXT);

$customdata = [$defaults];
// On instancie le formulaire.
$mform = new theme_apsolu_legal_notice_form(null, $customdata);

// On lui accorde des valeurs par défaut.
$mform->set_data($customdata);

$notification = '';
if ($data = $mform->get_data()) {

    $data = file_postupdate_standard_editor($data, 'legal_notice_doc_text', $editoroptions,
        $syscontext, $component, $filearea, THEME_APSOLU_LEGAL_NOTICE_DOC_TEXT);
    set_config('custom_legal_notice_active', intval(isset($data->custom_legal_notice_active)), 'theme_apsolu');

    set_config('legal_notice_doc_text', $data->legal_notice_doc_text, 'theme_apsolu');

    // Retourne une notification après enregistrement.
    $returnurl = new moodle_url('/theme/apsolu/documents/legal_notice_settings.php');
    $message = get_string('changessaved');
    redirect($returnurl, $message, $delay = null, \core\output\notification::NOTIFY_SUCCESS);

}

// Affiche le formulaire.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('legal_notice', 'theme_apsolu'));
$mform->display();
echo $OUTPUT->footer();
