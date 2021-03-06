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
 * Post installation hook for adding data.
 *
 * @package    theme_apsolu
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/theme/apsolu/lib.php');

/**
 * Initialise les variables après l'installation.
 *
 * @return bool
 */
function xmldb_theme_apsolu_install() {
    // Initialise les variables du plugin.
    set_config('homepage_enable', 0, 'theme_apsolu');

    set_config('homepage_section1_text', get_string('default_homepage_section1_text', 'theme_apsolu'), 'theme_apsolu');
    set_config('homepage_section1_image_credits', '', 'theme_apsolu');

    set_config('homepage_section2_image_credits', '', 'theme_apsolu');

    set_config('homepage_section3_text', '', 'theme_apsolu');
    set_config('homepage_section3_image_credits', '', 'theme_apsolu');

    set_config('homepage_section4_institutional_account_url', '', 'theme_apsolu');
    set_config('homepage_section4_non_institutional_account_url', '', 'theme_apsolu');

    theme_apsolu_initialise_homepage_background_images();

    return true;
}
