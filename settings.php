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

    // Documents.
    if (empty($ADMIN->locate('theme_apsolu_documents')) === true) {
        $ADMIN->add('apsolu', new admin_category('theme_apsolu_documents', get_string('documents_settings', 'theme_apsolu')));
    }

    // Documents > Mentions légales.
    $str = get_string('legal_notice', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/documents/legal_notice_settings.php');
    $ADMIN->add('theme_apsolu_documents', new admin_externalpage('theme_apsolu_documents_legal_notice', $str, $url, $capabilities));

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

if ($ADMIN->fulltree) {

    // 1. Paramètres.
    // Ajoute une information sur la page du menu Administration du site > Présentation > Thèmes > Apsolu.
    $page = new admin_settingpage('themesettingapsolu', get_string('settings'));
    $settings = new theme_boost_admin_settingspage_tabs('themesettingapsolu', get_string('configtitle', 'theme_apsolu'));

    $name = 'theme_apsolu_description';
    $title = get_string('description');
    $description = get_string('choosereadme', 'theme_apsolu');
    $setting = new admin_setting_description($name, $title, $description, 0);
    $page->add($setting);

    $settings->add($page);

    // 2. Personnaliser les couleurs.
    $page = new admin_settingpage('theme_apsolu_customize_colors', get_string('customizer_colors_label', 'theme_apsolu'));

    // 2b. Couleur principale.
    $name = 'theme_apsolu/custom_brandcolor';
    $title = get_string('brandcolor_1_label', 'theme_apsolu');
    $description = get_string('brandcolor_1_help', 'theme_apsolu');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // 2c. Couleur secondaire.
    $name = 'theme_apsolu/custom_brandcolor_2';
    $title = get_string('brandcolor_2_label', 'theme_apsolu');
    $description = get_string('brandcolor_2_help', 'theme_apsolu');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // 2d. Couleur des liens.
    $name = 'theme_apsolu/custom_brandcolor_links';
    $title = get_string('brandcolor_links_label', 'theme_apsolu');
    $description = get_string('brandcolor_links_help', 'theme_apsolu');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // 3. Personnalisation du menu.
    $page = new admin_settingpage('theme_apsolu_customize_links', get_string('customizer_navbar_label', 'theme_apsolu'));

    //3a. URL 1.
    $name = 'theme_apsolu/nav_link_1_url';
    $number = 'n°1';
    $setting = new admin_setting_configtext($name,get_string('nav_url_label', 'theme_apsolu').' '.$number,null,null,PARAM_URL);
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_1_text';
    $number = 'n°1';
    $setting = new admin_setting_configtext($name,get_string('nav_text_label', 'theme_apsolu').' '.$number,null,null,PARAM_RAW_TRIMMED);
    $page->add($setting);

    //3b. URL 2.
    $name = 'theme_apsolu/nav_link_2_url';
    $number = 'n°2';
    $setting = new admin_setting_configtext($name,get_string('nav_url_label', 'theme_apsolu').' '.$number,null,null,PARAM_URL);
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_2_text';
    $number = 'n°2';
    $setting = new admin_setting_configtext($name,get_string('nav_text_label', 'theme_apsolu').' '.$number,null,null,PARAM_RAW_TRIMMED);
    $page->add($setting);

    //3c. URL 3.
    $name = 'theme_apsolu/nav_link_3_url';
    $number = 'n°3';
    $setting = new admin_setting_configtext($name,get_string('nav_url_label', 'theme_apsolu').' '.$number,null,null,PARAM_URL);
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_3_text';
    $number = 'n°3';
    $setting = new admin_setting_configtext($name,get_string('nav_text_label', 'theme_apsolu').' '.$number,null,null,PARAM_RAW_TRIMMED);
    $page->add($setting);

    $settings->add($page);

    // 4. TODO: Personnalisation des libellés.
    $page = new admin_settingpage('theme_apsolu_customize_labels', get_string('customize_labels', 'theme_apsolu'));

    $name = 'theme_apsolu/customize_labels_description';
    $title = get_string('description');
    $description = get_string('customize_labels_desc', 'theme_apsolu');
    $setting = new admin_setting_description($name, $title, $description, 0);
    $page->add($setting);

    // 4a. Activités sportives.
    $name = 'local_apsolu/categories';
    $setting = new admin_setting_configtext($name,get_string('categories','local_apsolu'),null,get_string('categories_default','theme_apsolu'),PARAM_TEXT);
    $page->add($setting);

    //4b.

    $settings->add($page);
}
