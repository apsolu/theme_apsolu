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

require_once($CFG->dirroot.'/theme/apsolu/lib.php');

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
        $settings = array();
        $settings['homepage_enable'] = 1;
        $settings['homepage_section1_text'] = get_string('default_homepage_section1_text', 'theme_apsolu');
        $settings['homepage_section3_text'] = '';
        $settings['homepage_section4_institutional_account_url'] = '';
        $settings['homepage_section4_non_institutional_account_url'] = '';

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
            set_config('customfrontpageinclude', $CFG->dirroot.'/theme/apsolu/index.php');
        }

        // Initialise les images de fond.
        theme_apsolu_initialise_homepage_background_images();
    }

    return $result;
}
