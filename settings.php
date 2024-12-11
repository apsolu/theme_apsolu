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
 * Gère la configuration des paramètres du thème APSOLU.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Commenting.TodoComment.MissingInfoInline

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'moodle/category:manage',
    'moodle/course:create',
];

$filearea = 'homepage';
$options = ['maxfiles' => 1, 'accepted_types' => ['.jpg', '.png']];

// Ajoute des noeuds au menu d'administration.
if ($hassiteconfig || has_any_capability($capabilities, context_system::instance())) {
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

    // Présentation > Formats de pratique.
    $str = get_string('settings_configuration_homepage_activities', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/activities/settings.php');
    $ADMIN->add('local_apsolu_appearance', new admin_externalpage('local_apsolu_appearance_activities', $str, $url, $capabilities));

    // Présentation > Formats de pratique.
    $str = get_string('settings_configuration_customize', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/customize/settings.php');
    $ADMIN->add('local_apsolu_appearance', new admin_externalpage('local_apsolu_appearance_customize', $str, $url, $capabilities));

    // Documents.
    if (empty($ADMIN->locate('theme_apsolu_documents')) === true) {
        $ADMIN->add('apsolu', new admin_category('theme_apsolu_documents', get_string('documents_settings', 'theme_apsolu')));
    }

    // Documents > Mentions légales.
    $str = get_string('legal_notice', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/documents/legal_notice_settings.php');
    $ADMIN->add('theme_apsolu_documents', new admin_externalpage('theme_apsolu_documents_legal_notice', $str, $url, $capabilities));

    // Documents > Accessibilité.
    $str = get_string('accessibility', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/documents/accessibility_settings.php');
    $ADMIN->add('theme_apsolu_documents', new admin_externalpage('theme_apsolu_documents_accessibility',
        $str, $url, $capabilities));

    // Documents > Politique de confidentialité.
    $str = get_string('confidential', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/documents/confidential_settings.php');
    $ADMIN->add('theme_apsolu_documents', new admin_externalpage('theme_apsolu_documents_confidential', $str, $url, $capabilities));

    // Documents > Recommandations médicales.
    $str = get_string('medical_rec', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/documents/medical_settings.php');
    $ADMIN->add('theme_apsolu_documents', new admin_externalpage('theme_apsolu_documents_medical', $str, $url, $capabilities));

    // Documents > Nous contacter.
    $str = get_string('contact_us', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/documents/contact_settings.php');
    $ADMIN->add('theme_apsolu_documents', new admin_externalpage('theme_apsolu_documents_contact', $str, $url, $capabilities));

    // TODO: Documents > Droit à l'image.
}

// Ajoute les paramètres personnalisables du thème.
if ($ADMIN->fulltree) {

    // 1. Description.
    // Ajoute une information sur la page du menu Administration du site > Présentation > Thèmes > Apsolu.
    $page = new admin_settingpage('themesettingapsolu', get_string('description'));
    $settings = new theme_boost_admin_settingspage_tabs('themesettingapsolu', get_string('configtitle', 'theme_apsolu'));

    $name = 'theme_apsolu_description';
    $heading = '';
    $information = get_string('choosereadme', 'theme_apsolu');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    $settings->add($page);
}
