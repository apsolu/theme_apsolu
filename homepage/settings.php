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
 * Gère la configuration de la page d'accueil personnalisée.
 *
 * @package    theme_apsolu
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Setup admin access requirement.
admin_externalpage_setup('local_apsolu_appearance_homepage');

require_once($CFG->dirroot.'/theme/apsolu/homepage/settings_form.php');

$component = 'theme_apsolu';
$filearea = 'homepage';
$editoroptions = theme_apsolu_homepage_form::get_editor_options();
$filemanageroptions = theme_apsolu_homepage_form::get_filemanager_options();
$syscontext = context_system::instance();

// Build form.
$defaults = new stdClass();
$defaults->homepage_enable = get_config('theme_apsolu', 'homepage_enable');

// Charge le contenu de l'éditeur de texte de la section 1.
$defaults->homepage_section1_text = get_config('theme_apsolu', 'homepage_section1_text');
$defaults->homepage_section1_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'homepage_section1_text', $editoroptions, $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_1_TEXT);

// Charge l'image de fond de la section 1.
$defaults->homepage_section1_background_image = file_get_submitted_draft_itemid('homepage_section1_background_image');
if (empty($defaults->homepage_section1_background_image) === true) {
    // Récupère l'image originale si le brouillon est vide.
    file_prepare_draft_area($defaults->homepage_section1_background_image, $syscontext->id, $component, $filearea, THEME_APSOLU_BACKGROUND_IMAGE_1_ORIGINAL, $filemanageroptions);
}

// Charge la préférence sur l'affichage des crédits de la section 1.
$defaults->homepage_section1_show_credit = (empty(get_config('theme_apsolu', 'homepage_section1_image_credits')) === false);

// Charge l'image de fond de la section 2.
$defaults->homepage_section2_background_image = file_get_submitted_draft_itemid('homepage_section2_background_image');
if (empty($defaults->homepage_section2_background_image) === true) {
    // Récupère l'image originale si le brouillon est vide.
    file_prepare_draft_area($defaults->homepage_section2_background_image, $syscontext->id, $component, $filearea, THEME_APSOLU_BACKGROUND_IMAGE_2_ORIGINAL, $filemanageroptions);
}

// Charge la préférence sur l'affichage des crédits de la section 2.
$defaults->homepage_section2_show_credit = (empty(get_config('theme_apsolu', 'homepage_section2_image_credits')) === false);

// Charge le contenu de l'éditeur de texte de la section 3.
$defaults->homepage_section3_text = get_config('theme_apsolu', 'homepage_section3_text');
$defaults->homepage_section3_textformat = FORMAT_HTML;
$defaults = file_prepare_standard_editor($defaults, 'homepage_section3_text', $editoroptions, $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_3_TEXT);

// Charge l'image de fond de la section 3.
$defaults->homepage_section3_background_image = file_get_submitted_draft_itemid('homepage_section3_background_image');
if (empty($defaults->homepage_section3_background_image) === true) {
    // Récupère l'image originale si le brouillon est vide.
    file_prepare_draft_area($defaults->homepage_section3_background_image, $syscontext->id, $component, $filearea, THEME_APSOLU_BACKGROUND_IMAGE_3_ORIGINAL, $filemanageroptions);
}

// Charge la préférence sur l'affichage des crédits de la section 3.
$defaults->homepage_section3_show_credit = (empty(get_config('theme_apsolu', 'homepage_section3_image_credits')) === false);

// Charge les préférences sur les url.
$defaults->homepage_section4_institutional_account_url = get_config('theme_apsolu', 'homepage_section4_institutional_account_url');
$defaults->homepage_section4_non_institutional_account_url = get_config('theme_apsolu', 'homepage_section4_non_institutional_account_url');

$customdata = array($defaults);
$mform = new theme_apsolu_homepage_form(null, $customdata);

$notification = '';
if ($data = $mform->get_data()) {
    // Gère les pièces attachées des éditeurs de texte.
    $data = file_postupdate_standard_editor($data, 'homepage_section1_text', $editoroptions, $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_1_TEXT);
    $data = file_postupdate_standard_editor($data, 'homepage_section3_text', $editoroptions, $syscontext, $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_3_TEXT);

    // Gère le dépot des images.
    $fs = get_file_storage();
    $usercontext = context_user::instance($USER->id);

    foreach (range(1, 3) as $sectionid) {
        $fieldname = 'homepage_section'.$sectionid.'_background_image';

        $files = $fs->get_area_files($usercontext->id, 'user', 'draft', $defaults->$fieldname, $sort = 'itemid, filepath, filename', $includedirs = false);
        $draftfile = current($files);

        if ($draftfile === false) {
            // Problème d'upload ?
            continue;
        }

        // Toujours réenregistrer l'auteur et la licence de l'image.
        $author = $draftfile->get_author().' ('.get_string($draftfile->get_license(), 'license').')';
        set_config('homepage_section'.$sectionid.'_image_credits', $author, 'theme_apsolu');

        $contextid = $syscontext->id;
        $component = 'theme_apsolu';
        $filearea = 'homepage';
        $itemid = constant('THEME_APSOLU_BACKGROUND_IMAGE_'.$sectionid.'_ORIGINAL');

        $filename = 'background_'.$sectionid.'.jpg';
        $originalfile = $fs->get_file($contextid, $component, $filearea, $itemid, '/', $filename);

        if ($originalfile->get_contenthash() === $draftfile->get_contenthash()) {
            // L'image de fond n'a pas été modifiée.
            debugging('L\'image de fond de la section '.$sectionid.' n\'a pas été modifiée', DEBUG_DEVELOPER);
            continue;
        }

        // On génère les images en différents formats.
        $sizes = array();
        $sizes['ORIGINAL'] = $filename;
        $sizes['240_160'] = 'background_'.$sectionid.'_240x160.jpg';
        $sizes['480_320'] = 'background_'.$sectionid.'_480x320.jpg';
        $sizes['960_640'] = 'background_'.$sectionid.'_960x640.jpg';

        foreach ($sizes as $size => $filename) {
            if ($size === 'ORIGINAL') {
                $newwidth = null;
                $newheight = null;

                debugging('L\'image originale de la section '.$sectionid.' est convertie au format jpg.', DEBUG_DEVELOPER);
            } else {
                list($newwidth, $newheight) = explode('_', $size);

                debugging('L\'image originale de la section '.$sectionid.' est convertie au format '.$newwidth.'x'.$newheight.'.', DEBUG_DEVELOPER);
            }

            $itemid = constant('THEME_APSOLU_BACKGROUND_IMAGE_'.$sectionid.'_'.$size);

            $file = $fs->get_file($contextid, $component, $filearea, $itemid, '/', $filename);
            if ($file) {
                // Supprime le précédent fichier.
                $file->delete();
            }

            $resizedfile = array();
            $resizedfile['contextid'] = $contextid;
            $resizedfile['component'] = $component;
            $resizedfile['filearea'] = $filearea;
            $resizedfile['itemid'] = $itemid;
            $resizedfile['userid'] = $USER->id;

            $resizedfile['filename'] = $filename;
            $resizedfile['filepath'] = '/';
            $resizedfile['mimetype'] = 'image/jpeg';
            $resizedfile['timemodified'] = time();

            $resizedfile['source'] = $draftfile->get_source();
            $resizedfile['author'] = $draftfile->get_author();
            $resizedfile['license'] = $draftfile->get_license();
            $resizedfile['status'] = $draftfile->get_status();
            $resizedfile['sortorder'] = $draftfile->get_sortorder();

            // Taux de compression de 0 à 100 du fichier jpg. La valeur 0 signifie aucune compression.
            $quality = null;
            $fs->convert_image($resizedfile, $draftfile, $newwidth, $newheight, $keepaspectratio = false, $quality);
        }

        // On supprime le brouillon.
        $draftfile->delete();
    }

    set_config('homepage_enable', intval(isset($data->homepage_enable)), 'theme_apsolu');

    set_config('homepage_section1_text', $data->homepage_section1_text, 'theme_apsolu');
    if (isset($data->homepage_section1_show_credit) === false) {
        set_config('homepage_section1_image_credits', '', 'theme_apsolu');
    }

    if (isset($data->homepage_section2_show_credit) === false) {
        set_config('homepage_section2_image_credits', '', 'theme_apsolu');
    }

    set_config('homepage_section3_text', $data->homepage_section3_text, 'theme_apsolu');
    if (isset($data->homepage_section3_show_credit) === false) {
        set_config('homepage_section3_image_credits', '', 'theme_apsolu');
    }

    set_config('homepage_section4_institutional_account_url', $data->homepage_section4_institutional_account_url, 'theme_apsolu');
    set_config('homepage_section4_non_institutional_account_url', $data->homepage_section4_non_institutional_account_url, 'theme_apsolu');

    if ($defaults->homepage_enable !== $data->homepage_enable) {
        if (empty($data->homepage_enable) === true) {
            set_config('customfrontpageinclude', '');
        } else {
            set_config('customfrontpageinclude', $CFG->dirroot.'/theme/apsolu/index.php');
        }
    }

    $returnurl = new moodle_url('/theme/apsolu/homepage/settings.php');
    $message = get_string('changessaved');
    redirect($returnurl, $message, $delay = null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('settings_configuration_homepage', 'theme_apsolu'));

// Affiche le formulaire.
$mform->display();

echo $OUTPUT->footer();
