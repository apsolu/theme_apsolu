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
 * Procédure de mise à jour.
 *
 * @package    theme_apsolu
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/apsolu/lib.php');

/**
 * Procédure de mise à jour.
 *
 * @param int $oldversion Numéro de la version du module theme_apsolu actuellement installé.
 *
 * @return bool
 */
function xmldb_theme_apsolu_upgrade($oldversion = 0) {
    global $CFG;

    $result = true;

    $version = 2020052600;
    if ($result && $oldversion < $version) {
        // Récupère la configuration du module local_apsolu.
        $settings = [];
        $settings['homepage_enable'] = 1;
        $settings['homepage_section1_text'] = get_string('default_homepage_section1_text', 'theme_apsolu');
        $settings['homepage_section1_welcome_text'] = '';
        $settings['homepage_section2_activities_infobox_text'] = '';
        $settings['homepage_section2_practice_text'] = '';
        $settings['homepage_section2_association_text'] = '';
        $settings['homepage_section3_text'] = '';
        $settings['homepage_section4_institutional_account_url'] = '';
        $settings['homepage_section4_non_institutional_account_url'] = '';
        $settings['confidential_doc_text'] = '';
        $settings['contact_doc_text'] = '';
        $settings['legal_notice_doc_text'] = '';
        $settings['medical_doc_text'] = '';

        foreach ($settings as $settingname => $default) {
            $value = get_config('local_apsolu', $settingname);
            if (empty($value) === true) {
                $value = $default;
            }
            set_config($settingname, $value, 'theme_apsolu');
            unset_config($settingname, 'local_apsolu');
        }

        if (empty(get_config('theme_apsolu', 'homepage_enable')) === true) {
            set_config('customfrontpageinclude', '');
        } else {
            set_config('customfrontpageinclude', $CFG->dirroot . '/theme/apsolu/index.php');
        }

        // Initialise les images de fond.
        theme_apsolu_initialise_homepage_background_images();

        // Savepoint reached.
        upgrade_plugin_savepoint(true, $version, 'theme', 'apsolu');
    }

    $version = 2020071600;
    if ($result && $oldversion < $version) {
        // Convertit les images d'arrière-plan en jpg.
        $quality = null;
        $sizes = ['240x160', '480x320', '960x640'];

        $fs = get_file_storage();
        $syscontext = context_system::instance();

        foreach (range(1, 3) as $sectionid) {
            // Récupère le fichier original au format png.
            $itemid = constant('THEME_APSOLU_BACKGROUND_IMAGE_' . $sectionid . '_ORIGINAL');

            $filename = 'background_' . $sectionid . '.png';
            $originalfile = $fs->get_file($syscontext->id, 'theme_apsolu', 'homepage', $itemid, '/', $filename);

            if (!$originalfile) {
                // L'ancienne source png n'existe pas ?
                mtrace('Étrange... le fichier background_' . $sectionid . '.png ne semble pas exister.');
                continue;
            }

            $file = [
                'contextid' => $syscontext->id,
                'component' => 'theme_apsolu',
                'filearea' => 'homepage',
                'itemid' => constant('THEME_APSOLU_BACKGROUND_IMAGE_' . $sectionid . '_ORIGINAL'),
                'filepath' => '/',
                'filename' => 'background_' . $sectionid . '.jpg',
                'userid' => null,
                'mimetype' => 'image/jpeg',
                'status' => $originalfile->get_status(),
                'source' => $originalfile->get_source(),
                'author' => $originalfile->get_author(),
                'license' => $originalfile->get_license(),
                'timecreated' => $originalfile->get_timecreated(),
                'timemodified' => $originalfile->get_timemodified(),
                'sortorder' => $originalfile->get_sortorder(),
            ];

            // On enregistre l'ancienne source png au format jpg.
            $fs->convert_image($file, $originalfile, $newwidth = null, $newheight = null, $keepaspectratio = false, $quality);

            // On convertit toutes les autres tailles au format jpg.
            foreach ($sizes as $size) {
                list($newwidth, $newheight) = explode('x', $size);

                $oldfilename = 'background_' . $sectionid . '_' . $size . '.png';

                $file['itemid']++;
                $file['filename'] = 'background_' . $sectionid . '_' . $size . '.jpg';

                $existingfile = $fs->get_file($file['contextid'], $file['component'], $file['filearea'],
                    $file['itemid'], $file['filepath'], $oldfilename);
                if ($existingfile) {
                    // Supprime le précédent fichier.
                    $existingfile->delete();
                }

                // Taux de compression de 0 à 100 du fichier jpg. La valeur 0 signifie aucune compression.
                $fs->convert_image($file, $originalfile, $newwidth, $newheight, $keepaspectratio = false, $quality);
            }

            // On supprime l'ancienne source png.
            $originalfile->delete();
        }

        // Savepoint reached.
        upgrade_plugin_savepoint(true, $version, 'theme', 'apsolu');
    }

    $version = 2024121100;
    if ($result && $oldversion < $version) {
        $settings = [];
        $settings['accessibility_status'] = get_string('accessibility_status_default', 'theme_apsolu');
        $settings['accessibility_doc_text'] = get_string('accessibility_doc_text_default', 'theme_apsolu');

        foreach ($settings as $settingname => $default) {
            $value = get_config('local_apsolu', $settingname);
            if (empty($value) === true) {
                $value = $default;
            }
            set_config($settingname, $value, 'theme_apsolu');
            unset_config($settingname, 'local_apsolu');
        }

        // Savepoint reached.
        upgrade_plugin_savepoint(true, $version, 'theme', 'apsolu');
    }

    return $result;
}
