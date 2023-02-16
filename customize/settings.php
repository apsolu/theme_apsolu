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
 * theme_apsolu
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/filelib.php');

// Setup admin access requirement.
admin_externalpage_setup('local_apsolu_appearance_customize');

require_once($CFG->dirroot . '/theme/apsolu/customize/settings_form.php');

$component = 'theme_apsolu';
$filearea = 'homepage';
$editoroptions = theme_apsolu_customize_form::get_editor_options();
$filemanageroptions = theme_apsolu_customize_form::get_filemanager_options();
$syscontext = context_system::instance();

$description = '<p class="lead">Vous pouvez ici personnaliser les options d\'affichage du thème APSOLU.</p>';

// Construction du formulaire.
$defaults = new stdClass();

// Charge les couleurs personnalisées.
$defaults->custom_brandcolor = get_config($component, 'custom_brandcolor');
$defaults->custom_brandcolor_2 = get_config($component, 'custom_brandcolor_2');
$defaults->custom_brandcolor_links = get_config($component, 'custom_brandcolor_links');

// Charge les urls de la barre de liens.
$defaults->nav_link_1_url = get_config($component, 'nav_link_1_url');
$defaults->nav_link_1_text = get_config($component, 'nav_link_1_text');

$defaults->nav_link_2_url = get_config($component, 'nav_link_2_url');
$defaults->nav_link_2_text = get_config($component, 'nav_link_2_text');

$defaults->nav_link_3_url = get_config($component, 'nav_link_3_url');
$defaults->nav_link_3_text = get_config($component, 'nav_link_3_text');

// TODO: Charger les logos.
$defaults->homepage_footer_logo_1 = file_get_submitted_draft_itemid('homepage_footer_logo_1');
if (empty($defaults->homepage_footer_logo_1) === true) {
    file_prepare_draft_area($defaults->homepage_footer_logo_1, $syscontext->id,
        $component, $filearea, THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_1, $filemanageroptions);
}
$defaults->homepage_footer_logo_2 = file_get_submitted_draft_itemid('homepage_footer_logo_2');
if (empty($defaults->homepage_footer_logo_2) === true) {
    file_prepare_draft_area($defaults->homepage_footer_logo_2, $syscontext->id, 'theme_apsolu', 'homepage',
        THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_2, $filemanageroptions);
}
$defaults->homepage_footer_logo_3 = file_get_submitted_draft_itemid('homepage_footer_logo_3');
if (empty($defaults->homepage_footer_logo_3) === true) {
    file_prepare_draft_area($defaults->homepage_footer_logo_3, $syscontext->id, 'theme_apsolu', 'homepage',
        THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_3, $filemanageroptions);
}

// Charge la note de pied de page.
$defaults->footer_text_section = get_config($component, 'footer_text_section');
$defaults->footer_text_section_textformat = FORMAT_HTML;
$defaults->footer_text_section = file_prepare_standard_editor($defaults, 'footer_text_section', $editoroptions,
    $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_FOOTER_TEXT);

$customdata = array($defaults);

// On instancie le formulaire.
$mform = new theme_apsolu_customize_form(null, $customdata);

$notification = '';

if ($data = $mform->get_data()) {
    // Gère les pièces attachées des éditeurs de texte.
    $data = file_postupdate_standard_editor($data, 'footer_text_section', $editoroptions,
        $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_FOOTER_TEXT);
    set_config('footer_text_section', $data->footer_text_section, $component);

    // Sauvegarde les brouillons vers leur emplacement final.
    file_save_draft_area_files($defaults->homepage_footer_logo_1, $syscontext->id, $component, $filearea,
        THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_1, $filemanageroptions);
    file_save_draft_area_files($defaults->homepage_footer_logo_2, $syscontext->id, $component, $filearea,
        THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_2, $filemanageroptions);
    file_save_draft_area_files($defaults->homepage_footer_logo_3, $syscontext->id, $component, $filearea,
        THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_3, $filemanageroptions);

    // Gère le dépot des images.
    $fs = get_file_storage();
    $filepath = '/';
    $files = $fs->get_area_files($syscontext->id, 'theme_apsolu', 'homepage', $itemid = false);
    foreach ($files as $file) {
        if ($file->get_filename() === '.') {
            continue;
        }
        switch ($file->get_itemid()) {
            case THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_1:
                set_config('homepage_footer_logo_1', $filepath.$file->get_filename(), $component);
                break;
            case THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_2:
                set_config('homepage_footer_logo_2', $filepath.$file->get_filename(), $component);
                break;
            case THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_3:
                set_config('homepage_footer_logo_3', $filepath.$file->get_filename(), $component);
                break;
        }
    }

    // Gère les couleurs et les liens hypertextes personnalisés.
    set_config('custom_brandcolor', $data->custom_brandcolor, $component);
    set_config('custom_brandcolor_2', $data->custom_brandcolor_2, $component);
    set_config('custom_brandcolor_links', $data->custom_brandcolor_links, $component);

    set_config('nav_link_1_url', $data->nav_link_1_url, $component);
    set_config('nav_link_1_text', $data->nav_link_1_text, $component);

    set_config('nav_link_2_url', $data->nav_link_2_url, $component);
    set_config('nav_link_2_text', $data->nav_link_2_text, $component);

    set_config('nav_link_3_url', $data->nav_link_3_url, $component);
    set_config('nav_link_3_text', $data->nav_link_3_text, $component);

    // Vide le cache du thème à l'enregistrement.
    theme_reset_all_caches();

    // Retourne une notification de confirmation à l'enregistrement.
    $returnurl = new moodle_url('/theme/apsolu/customize/settings.php');
    $message = get_string('changessaved');
    redirect($returnurl, $message, $delay = null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('settings_configuration_customize', $component));
echo $description;

$mform->display();

echo $OUTPUT->footer();


