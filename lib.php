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
 * Theme functions.
 *
 * @package   theme_apsolu
 * @copyright 2019 Université Rennes 2 {@link https://www.univ-rennes2.fr}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('THEME_APSOLU_HOMEPAGE_SECTION_1_TEXT', 1);
define('THEME_APSOLU_HOMEPAGE_SECTION_3_TEXT', 3);

define('THEME_APSOLU_BACKGROUND_IMAGE_1_ORIGINAL', '10');
define('THEME_APSOLU_BACKGROUND_IMAGE_1_240_160', '11');
define('THEME_APSOLU_BACKGROUND_IMAGE_1_480_320', '12');
define('THEME_APSOLU_BACKGROUND_IMAGE_1_960_640', '13');

define('THEME_APSOLU_BACKGROUND_IMAGE_2_ORIGINAL', '20');
define('THEME_APSOLU_BACKGROUND_IMAGE_2_240_160', '21');
define('THEME_APSOLU_BACKGROUND_IMAGE_2_480_320', '22');
define('THEME_APSOLU_BACKGROUND_IMAGE_2_960_640', '23');

define('THEME_APSOLU_BACKGROUND_IMAGE_3_ORIGINAL', '30');
define('THEME_APSOLU_BACKGROUND_IMAGE_3_240_160', '31');
define('THEME_APSOLU_BACKGROUND_IMAGE_3_480_320', '32');
define('THEME_APSOLU_BACKGROUND_IMAGE_3_960_640', '33');

define('THEME_APSOLU_MEDICAL_DOC_TEXT', '40');
define('THEME_APSOLU_LEGAL_NOTICE_DOC_TEXT', '41');
define('THEME_APSOLU_CONFIDENTIAL_DOC_TEXT', '42');
define('THEME_APSOLU_CONTACT_DOC_TEXT', '43');

define('THEME_APSOLU_HOMEPAGE_SECTION_2_ACTIVITIES_INFOBOX_TEXT', '50');
define('THEME_APSOLU_HOMEPAGE_SECTION_2_PRACTICE_TEXT', '51');
define('THEME_APSOLU_HOMEPAGE_SECTION_2_ASSOCIATION_TEXT', '52');

define('THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_1', '60');
define('THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_2', '61');
define('THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_3', '62');

define('THEME_APSOLU_HOMEPAGE_SECTION_1_WELCOME_TEXT', '70');
define('THEME_APSOLU_HOMEPAGE_FOOTER_TEXT', '71');

define('THEME_APSOLU_CUSTOM_BRANDCOLOR', '80');
define('THEME_APSOLU_CUSTOM_BRANDCOLOR_2', '81');
define('THEME_APSOLU_CUSTOM_BRANDCOLOR_3', '82');

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return string All fixed Sass for this theme.
 */
function theme_apsolu_get_main_scss_content($theme) {
    global $CFG;

    // Preset Apsolu.
    return file_get_contents($CFG->dirroot.'/theme/apsolu/scss/apsolu.scss');
}

/**
 * Récupère le SCSS à rajouter pour le schéma de couleurs personnalisé.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return array
 */
function theme_apsolu_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        // Clefs de config définies dans les settings.
        'custom_brandcolor' => ['custom-color-1'], // Couleur principale.
        'custom_brandcolor_2' => ['custom-color-2'], // Couleur de contraste.
        'custom_brandcolor_links' => ['custom-color-3'], // Couleur des liens.
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Loads the CSS Styles and replace the background images.
 * If background image not available in the settings take the default images.
 *
 * @param string $css
 * @param string $theme
 *
 * @return string $css
 */
function theme_apsolu_process_css($css, $theme = null) {
    global $CFG;

    $fs = get_file_storage();
    $context = context_system::instance();

    $files = $fs->get_area_files($context->id, 'theme_apsolu', 'homepage', $itemid = false);
    foreach ($files as $file) {
        if ($file->get_filename() === '.') {
            continue;
        }

        switch ($file->get_itemid()) {
            case THEME_APSOLU_BACKGROUND_IMAGE_1_240_160:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_1_240x160]]';
                break;
            case THEME_APSOLU_BACKGROUND_IMAGE_1_480_320:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_1_480x320]]';
                break;
            case THEME_APSOLU_BACKGROUND_IMAGE_1_960_640:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_1_960x640]]';
                break;
            case THEME_APSOLU_BACKGROUND_IMAGE_2_240_160:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_2_240x160]]';
                break;
            case THEME_APSOLU_BACKGROUND_IMAGE_2_480_320:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_2_480x320]]';
                break;
            case THEME_APSOLU_BACKGROUND_IMAGE_2_960_640:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_2_960x640]]';
                break;
            case THEME_APSOLU_BACKGROUND_IMAGE_3_240_160:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_3_240x160]]';
                break;
            case THEME_APSOLU_BACKGROUND_IMAGE_3_480_320:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_3_480x320]]';
                break;
            case THEME_APSOLU_BACKGROUND_IMAGE_3_960_640:
                $tag = '[[THEME_APSOLU:BACKGROUND_IMAGE_3_960x640]]';
                break;
            default:
                $tag = null;
        }

        if ($tag === null) {
            continue;
        }

        $url = $CFG->wwwroot.'/pluginfile.php/1/theme_apsolu/homepage/'.$file->get_itemid().'/'.$file->get_filename();

        $css = str_replace($tag, $url, $css);
    }

    return $css;
}

/**
 * Construit le menu de navigation principal pour un utilisateur authentifié.
 *
 * @return array Retourne un tableau contenant la structure du menu.
 */
function theme_apsolu_get_primary_menu() {
    global $PAGE;

    $primary = new core\navigation\output\primary($PAGE);
    $renderer = $PAGE->get_renderer('core');
    $primarymenu = $primary->export_for_template($renderer);

    // Supprime le premier élément du menu qui correspond à l'index du site.
    array_shift($primarymenu['moremenu']['nodearray']);

    // Remplace l'url du tableau de bord.
    if (isset($primarymenu['moremenu']['nodearray'][1]) === true) {
        $primarymenu['moremenu']['nodearray'][1]['url'] = new moodle_url('/my/#courses');
    }

    // Remplace l'url de l'administration.
    if (isset($primarymenu['moremenu']['nodearray'][2]) === true) {
        $primarymenu['moremenu']['nodearray'][2]['url'] = new moodle_url('/admin/search.php#linkapsolu');
    }

    // Duplique le contenu dans l'index mobileprimarynav.
    $primarymenu['mobileprimarynav'] = $primarymenu['moremenu']['nodearray'];

    return $primarymenu;
}

/**
 * Gère les contrôles d'accès pour la diffusion des fichiers du module theme_apsolu.
 *
 * @param stdClass $course        Course object.
 * @param stdClass $cm            Course module object.
 * @param stdClass $context       Context object.
 * @param string   $filearea      File area.
 * @param array    $args          Extra arguments.
 * @param boolean     $forcedownload Whether or not force download.
 * @param array    $options       Additional options affecting the file serving.
 *
 * @return void|boolean Retourne False en cas d'erreur.
 */
function theme_apsolu_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        debugging('Wrong contextlevel: '.$context->contextlevel, DEBUG_DEVELOPER);
        return false;
    }

    if ($filearea != 'homepage') {
        debugging('Wrong filearea: '.$filearea, DEBUG_DEVELOPER);
        return false;
    }

    $itemid = (int) array_shift($args);

    $fs = get_file_storage();

    $filename = array_pop($args);
    if (empty($args) === true) {
        $filepath = '/';
    } else {
        $filepath = '/'.implode('/', $args).'/';
    }

    $file = $fs->get_file($context->id, 'theme_apsolu', $filearea, $itemid, $filepath, $filename);
    if ($file === false) {
        debugging(get_string('filenotfound', 'error'), DEBUG_DEVELOPER);
        return false;
    }

    // Finally send the file.
    send_stored_file($file, 0, 0, true, $options); // Download MUST be forced - security!
}

/**
 * Initialise les images d'arrière-plan par défaut sur la page d'accueil personnalisée.
 *
 * @return void
 */
function theme_apsolu_initialise_homepage_background_images() {
    global $CFG;

    $quality = null;
    $sizes = ['240x160', '480x320', '960x640'];

    $licenses = [];
    $licenses[1] = ['Marie-Lan Nguyen', 'cc']; // Escrime.
    $licenses[2] = ['Clément Bucco-Lechat', 'cc-sa']; // Rugby.
    $licenses[3] = ['Tsutomu Takasu', 'cc']; // Gymnastique.

    $fs = get_file_storage();
    $syscontext = context_system::instance();

    // Initialise les images du fond 1.
    foreach (range(1, 3) as $sectionid) {
        list($author, $license) = $licenses[$sectionid];

        $filepath = $CFG->dirroot.'/theme/apsolu/images/background_'.$sectionid.'.jpg';

        $file = [
            'contextid' => $syscontext->id,
            'component' => 'theme_apsolu',
            'filearea'  => 'homepage',
            'itemid'    => constant('THEME_APSOLU_BACKGROUND_IMAGE_'.$sectionid.'_ORIGINAL'),
            'filepath'  => '/',
            'filename'  => 'background_'.$sectionid.'.jpg',
            'userid'    => null,
            'mimetype'  => 'image/jpeg',
            'status' => 0,
            'source' => null,
            'author' => $author,
            'license' => $license,
            'timecreated' => time(),
            'timemodified' => time(),
            'sortorder' => 0,
        ];

        $existingfile = $fs->get_file(
            $file['contextid'],
            $file['component'],
            $file['filearea'],
            $file['itemid'],
            $file['filepath'],
            $file['filename']
        );
        if ($existingfile) {
            // Supprime le précédent fichier.
            $existingfile->delete();
        }
        $originalfile = $fs->create_file_from_pathname($file, $filepath);

        $credits = $author.' ('.get_string($license, 'license').')';
        set_config('homepage_section'.$sectionid.'_image_credits', $credits, 'theme_apsolu');

        foreach ($sizes as $size) {
            list($newwidth, $newheight) = explode('x', $size);

            $file['itemid']++;
            $file['filename'] = 'background_'.$sectionid.'_'.$size.'.jpg';

            $existingfile = $fs->get_file(
                $file['contextid'],
                $file['component'],
                $file['filearea'],
                $file['itemid'],
                $file['filepath'],
                $file['filename']
            );
            if ($existingfile) {
                // Supprime le précédent fichier.
                $existingfile->delete();
            }

            // Taux de compression de 0 à 100 du fichier jpg. La valeur 0 signifie aucune compression.
            $fs->convert_image($file, $originalfile, $newwidth, $newheight, $keepaspectratio = false, $quality);
        }
    }
}
