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
 * APSOLU theme, based on Bootstrap theme.
 *
 * @package    theme_apsolu
 * @copyright  2016 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Invert Navbar to dark background.
    $name = 'theme_apsolu/invert';
    $title = get_string('invert', 'theme_apsolu');
    $description = get_string('invertdesc', 'theme_apsolu');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Logo file setting.
    $name = 'theme_apsolu/logo';
    $title = get_string('logo', 'theme_apsolu');
    $description = get_string('logodesc', 'theme_apsolu');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Custom CSS file.
    $name = 'theme_apsolu/customcss';
    $title = get_string('customcss', 'theme_apsolu');
    $description = get_string('customcssdesc', 'theme_apsolu');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Footnote setting.
    $name = 'theme_apsolu/footnote';
    $title = get_string('footnote', 'theme_apsolu');
    $description = get_string('footnotedesc', 'theme_apsolu');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
}
