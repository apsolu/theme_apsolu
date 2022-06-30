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
 *  Gère la configuration des couleurs et autres éléments de personnalisation de l'instance.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Authorisation accès admin, doit être appelé sur chaque page admin
admin_externalpage_setup('local_apsolu_appearance_customizer');

// On récupère le formulaire de personnalisation
require_once($CFG->dirroot . '/theme/apsolu/customizer/customizer_form.php');

// Construction du formulaire
$defaults = new stdClass();
$defaults->customization_enable = get_config('theme_apsolu', 'customization_enable');

$customdata = array($defaults);
// Instantiate the form that you defined.
$mform = new theme_apsolu_customizer_form(null, $customdata);
// Default 'action' for form is strip_querystring(qualified_me()).

// Définition des variables
$component = 'theme_apsolu';
$filearea = 'customizer';
$editoroptions = theme_apsolu_customizer_form::get_editor_options();
//$filemanageroptions = theme_apsolu_customizer_form::get_filemanager_options();
$syscontext = context_system::instance();

$settings = new theme_boost_admin_settingspage_tabs('themesettingboost', get_string('customizer_general', 'theme_boost'));
$page = new admin_settingpage('theme_boost_general', get_string('generalsettings', 'theme_boost'));

//Charge la couleur principale 1.

// Charge la couleur principale 2.

// Charge la couleur des liens.

// Charge le contenu pour les liens de la navbar.
// Lien 1.
// Lien 2.
// Lien 3.

// Charge le contenu de l'éditeur de texte pour les recommandations médicales.
$defaults->homepage_section1_text = get_config('theme_apsolu', 'medical_text');
$defaults->homepage_section1_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'medical_text', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_MEDICAL_TEXT);

// Charge le contenu de l'éditeur de texte pour les mentions légales.
$defaults->homepage_section1_text = get_config('theme_apsolu', 'notice_text');
$defaults->homepage_section1_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'notice_text', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_NOTICE_TEXT);

// Charge le contenu de l'éditeur de texte pour la politique de confidentialité.
$defaults->homepage_section1_text = get_config('theme_apsolu', 'confidential_text');
$defaults->homepage_section1_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'confidential_text', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_CONFIDENTIAL_TEXT);

// Charge le contenu de l'éditeur de texte pour le modal contact.
$defaults->homepage_section1_text = get_config('theme_apsolu', 'contact_text');
$defaults->homepage_section1_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'contact_text', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_CONTACT_TEXT);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('settings_configuration_customizer', 'theme_apsolu'));

// Traitement et affichage du formulaire
if ($mform->is_cancelled()) {
    // Si l'on a cliqué sur "annuler".
} else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
} else {
    // On éxécute ça si les données rentrées par l'utilisateur ne sont pas bonnes ou au premier affichage du formulaire.
    $mform->display();
};

echo $OUTPUT->footer();
