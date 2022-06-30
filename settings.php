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
 * Add page to admin menu.
 *
 * @package    theme_apsolu
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'moodle/category:manage',
    'moodle/course:create',
);

if ($hassiteconfig or has_any_capability($capabilities, context_system::instance())) {
    // Ajoute un noeud Apsolu au menu d'administration.
    if (empty($ADMIN->locate('apsolu')) === true) {
        // Crée le noeud.
        $apsoluroot = new admin_category('apsolu', get_string('settings_root', 'theme_apsolu'));
        // Tri les enfants par ordre alphabétique.
        $apsoluroot->set_sorting($sort = true);
        // Place le noeud Apsolu avant le noeud Utilisateurs de Moodle.
        $ADMIN->add('root', $apsoluroot, 'users');
    }

    // Présentation.
    if (empty($ADMIN->locate('local_apsolu_appearance')) === true) {
        $ADMIN->add('apsolu', new admin_category('local_apsolu_appearance', get_string('appearance', 'admin')));
    }

    // Présentation > Page d'accueil.
    $str = get_string('settings_configuration_homepage', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/homepage/settings.php');
    $ADMIN->add('local_apsolu_appearance', new admin_externalpage('local_apsolu_appearance_homepage', $str, $url, $capabilities));

    // Présentation > Personnalisation de l'instance.
    $str = get_string('settings_configuration_customizer', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/customizer/settings.php');
    $ADMIN->add('local_apsolu_appearance', new admin_externalpage('local_apsolu_appearance_customizer', $str, $url, $capabilities));
}

if ($ADMIN->fulltree) {
    // Ajoute une information sur la page du menu Administration du site > Présentation > Thèmes > Apsolu.
    $settings = new admin_settingpage('themesettingapsolu', get_string('configtitle', 'theme_apsolu'));

    $heading = new admin_setting_heading('theme_apsolu_heading', get_string('settings'), '', 0);
    $settings->add($heading);

    $name = 'theme_apsolu_description';
    $title = get_string('description');
    $description = get_string('choosereadme', 'theme_apsolu');
    $setting = new admin_setting_description($name, $title, $description, 0);
    $settings->add($setting);
}
