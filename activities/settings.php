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
 * Gère la configuration de l'affichage des formats de pratique dans la page d'accueil APSOLU.
 *
 * Permet la customisation du contenu et de l'affichage ou non des formats de pratique :
 *  - Liste des activités (générée automatiquement),
 *  - Stages,
 *  - Evénements,
 *  - Animations,
 *  - Pratiques autonomes,
 *  - Adhésion à l'Association Sportive.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Standard GPL and phpdocs
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_apsolu_appearance_activities');

require_once($CFG->dirroot.'/theme/apsolu/activities/settings_form.php');

// Définition des variables.
$component = 'theme_apsolu';
$filearea = 'homepage';
$editoroptions = theme_apsolu_homepage_activities_form::get_editor_options();
$syscontext = context_system::instance();

$description = get_string('settings_configuration_homepage_activities_desc', 'theme_apsolu');
$plugin = 'theme_apsolu';

// Construction du formulaire.
$defaults = new stdClass();

// Charge le contenu de la liste des activité.
$defaults->homepage_section2_activities_infobox_text = get_config('theme_apsolu','homepage_section2_activities_infobox_text');
$defaults->homepage_section2_activities_infobox_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'homepage_section2_activities_infobox_text', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_ACTIVITIES_INFOBOX_TEXT);

// Charge le contenu de l'éditeur de texte pour les pratiques autonomes.
$defaults->homepage_section2_practice_text = get_config($plugin,'homepage_section2_practice_text');
$defaults->homepage_section2_practice_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'homepage_section2_practice_text', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_PRACTICE_TEXT);

// Charge le contenu de l'éditeur de texte pour l'Adhésion à l'Association sportive.
$defaults->homepage_section2_association_text = get_config($plugin,'homepage_section2_association_text');
$defaults->homepage_section2_association_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'homepage_section2_association_text', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_ASSOCIATION_TEXT);

// TODO : Charger les stages.
// TODO : Charger les événements.
// TODO : Charger les animations.

$customdata = array($defaults);

// On instancie le formulaire.
$mform = new theme_apsolu_homepage_activities_form(null, $customdata);

//On lui accorde des valeurs par défaut.
$mform->set_data($customdata);

$notification = '';
if ($data = $mform->get_data()) {
    // Gère les pièces attachées des éditeurs de texte.
    $data = file_postupdate_standard_editor($data, 'homepage_section2_activities_infobox_text', $editoroptions,
        $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_ACTIVITIES_INFOBOX_TEXT);
    $data = file_postupdate_standard_editor($data, 'homepage_section2_practice_text', $editoroptions,
        $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_PRACTICE_TEXT);
    $data = file_postupdate_standard_editor($data, 'homepage_section2_association_text', $editoroptions,
        $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_ASSOCIATION_TEXT);

    set_config('homepage_section2_activities_infobox_text', $data->homepage_section2_activities_infobox_text, 'theme_apsolu');
    set_config('homepage_section2_practice_text', $data->homepage_section2_practice_text, 'theme_apsolu');
    set_config('homepage_section2_association_text', $data->homepage_section2_association_text, 'theme_apsolu');


    // Retourne une notification après enregistrement.
    $returnurl = new moodle_url('/theme/apsolu/activities/settings.php');
    $message = get_string('changessaved');
    redirect($returnurl, $message, $delay = null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('settings_configuration_homepage_activities', 'theme_apsolu'));
echo $description;
$mform->display();
echo $OUTPUT->footer();
