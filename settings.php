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

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'moodle/category:manage',
    'moodle/course:create',
);

$filearea = 'homepage';
$options = array('maxfiles' => 1, 'accepted_types' => array('.jpg','.png'));

/**
 * Ajoute des noeuds au menu d'administration.
 */
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

    // Présentation > Formats de pratique.
    $str = get_string('settings_configuration_homepage_activities', 'theme_apsolu');
    $url = new moodle_url('/theme/apsolu/activities/settings.php');
    $ADMIN->add('local_apsolu_appearance', new admin_externalpage('local_apsolu_appearance_activities', $str, $url, $capabilities));

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

/**
 * Ajoute les paramètres personnalisables du thème.
 */
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

    // 2. Personnaliser les couleurs.
    $page = new admin_settingpage('theme_apsolu_customize_colors', get_string('customizer_colors_label', 'theme_apsolu'));

    // 2b. Couleur principale.
    $name = 'theme_apsolu/custom_brandcolor_1_heading';
    $heading = get_string('brandcolor_1_label', 'theme_apsolu');
    $information = get_string('brandcolor_1_help', 'theme_apsolu');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    $name = 'theme_apsolu/custom_brandcolor';
    $title = '';
    $description = '';
    $color = get_string('brandcolor_1_default', 'theme_apsolu');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $color);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // 2c. Couleur secondaire.
    $name = 'theme_apsolu/custom_brandcolor_2_heading';
    $heading = get_string('brandcolor_2_label', 'theme_apsolu');
    $information = get_string('brandcolor_2_help', 'theme_apsolu');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    $name = 'theme_apsolu/custom_brandcolor_2';
    $title = '';
    $description = 'Prévoir une couleur plus foncée.';
    $color = get_string('brandcolor_2_default', 'theme_apsolu');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $color);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // 2d. Couleur des liens.
    $name = 'theme_apsolu/custom_brandcolor_links_heading';
    $heading = get_string('brandcolor_links_label', 'theme_apsolu');
    $information = get_string('brandcolor_links_help', 'theme_apsolu');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    $name = 'theme_apsolu/custom_brandcolor_links';
    $title = '';
    $description = '';
    $color = get_string('brandcolor_links_default', 'theme_apsolu');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $color);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // 3. Personnaliser la barre de liens.
    $page = new admin_settingpage('theme_apsolu_customize_links', get_string('customizer_links_label', 'theme_apsolu'));

    $name = 'theme_apsolu/customize_links_heading';
    $heading = '';
    $information = get_string('customize_links_desc', 'theme_apsolu');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    //3a. URL 1.
    $name = 'theme_apsolu/nav_link_1_heading';
    $number = 'n°1';
    $heading = get_string('nav_text_label', 'theme_apsolu') . ' ' . $number;
    $setting = new admin_setting_heading($name, $heading, '');
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_1_url';
    $setting = new admin_setting_configtext($name, get_string('nav_url_label', 'theme_apsolu'), null, null, PARAM_URL);
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_1_text';
    $setting = new admin_setting_configtext($name, get_string('nav_text_label', 'theme_apsolu'), null, null, PARAM_RAW_TRIMMED);
    $page->add($setting);

    //3b. URL 2.
    $name = 'theme_apsolu/nav_link_2_heading';
    $number = 'n°2';
    $heading = get_string('nav_text_label', 'theme_apsolu') . ' ' . $number;
    $setting = new admin_setting_heading($name, $heading, '');
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_2_url';
    $setting = new admin_setting_configtext($name, get_string('nav_url_label', 'theme_apsolu'), null, null, PARAM_URL);
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_2_text';
    $setting = new admin_setting_configtext($name, get_string('nav_text_label', 'theme_apsolu'), null, null, PARAM_RAW_TRIMMED);
    $page->add($setting);

    //3c. URL 3.
    $name = 'theme_apsolu/nav_link_3_heading';
    $number = 'n°3';
    $heading = get_string('nav_text_label', 'theme_apsolu') . ' ' . $number;
    $setting = new admin_setting_heading($name, $heading, '');
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_3_url';
    $setting = new admin_setting_configtext($name, get_string('nav_url_label', 'theme_apsolu'), null, null, PARAM_URL);
    $page->add($setting);

    $name = 'theme_apsolu/nav_link_3_text';
    $setting = new admin_setting_configtext($name, get_string('nav_text_label', 'theme_apsolu'), null, null, PARAM_RAW_TRIMMED);
    $page->add($setting);

    $settings->add($page);

    // 4. Personnaliser le pied de page.
    $page = new admin_settingpage('theme_apsolu_customize_footer', get_string('customize_footer', 'theme_apsolu'));

    $name = 'theme_apsolu/footer_desc';
    $heading = '';
    $information = get_string('customize_footer_desc', 'theme_apsolu');
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    $name = 'theme_apsolu/footer_general';
    $heading = get_string('general');
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    // Activer le pied de page personnalisé.
    /*$name = 'theme_apsolu/footer_activate';
    $title = get_string('footer_active', 'theme_apsolu');
    $description = '<div class="alert alert-secondary d-flex align-items-center"><i class="fa fa-info-circle mr-3" aria-hidden="true"></i>Si le pied de page personnalisé est désactivé, seuls les boutons "mentions légales", "politique de confidentialité" et "contact" seront affichés sur la page d\'accueil.</div>';
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);*/

    // Blac 1 : Logos.
    $name = 'theme_apsolu/footer_1_heading';
    $heading = get_string('footer_block', 'theme_apsolu') . ' 1 ';
    $information = 'Vous pouvez placer jusqu\'à 3 logos dans le pied de page';
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    $name = 'theme_apsolu/homepage_footer_logo_1';
    $title = 'Logo 1';
    $description = '';
    $setting = new admin_setting_configstoredfile($name, $title, $description, $filearea,60 ,$options);
    $page->add($setting);

    $name = 'theme_apsolu/homepage_footer_logo_2';
    $title = 'Logo 2';
    $description = '';
    $setting = new admin_setting_configstoredfile($name, $title, $description, $filearea, 61,$options);
    $page->add($setting);

    $name = 'theme_apsolu/homepage_footer_logo_3';
    $title = 'Logo 3';
    $description = '';
    $setting = new admin_setting_configstoredfile($name, $title, $description, $filearea, 62,$options);
    $page->add($setting);

    // Blac 2 : Note de bas de page.
    $name = 'theme_apsolu/footer_2_heading';
    $heading = get_string('footer_block', 'theme_apsolu') . ' 2';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    $name = 'theme_apsolu/footer_text_section';
    $title = 'Note de pied de page';
    $setting = new admin_setting_configtextarea($name, $title, '', '©  2018 Université de Rennes 2. All Rights Reserved. ', PARAM_TEXT);
    $page->add($setting);

    // Bloc 3 : Liens hypertexte.
    $name = 'theme_apsolu/footer_3_heading';
    $heading = get_string('footer_block', 'theme_apsolu') . ' 3';
    $information = '';
    $setting = new admin_setting_heading($name, $heading, $information);
    $page->add($setting);

    $name = 'theme_apsolu/footer_3_description';
    $title = get_string('description');
    $description =
        '<div class="alert alert-secondary d-flex align-items-center"><i class="fa fa-info-circle mr-3" aria-hidden="true"></i><p class="mb-0">Affiche les liens hypertexte déjà définis dans la barre de liens.</p></div>';
    $setting = new admin_setting_description($name, $title, $description, 0);
    $page->add($setting);

    $settings->add($page);

    // 5. TODO: Personnalisation des libellés.
    //$page = new admin_settingpage('theme_apsolu_customize_labels', get_string('customize_labels', 'theme_apsolu'));
    //
    //$name = 'theme_apsolu/customize_labels_description';
    //$information = get_string('customize_labels_desc', 'theme_apsolu');
    //$heading = '';
    //$setting = new admin_setting_heading($name, $heading, $information);
    //$page->add($setting);
    //
    //$name = 'theme_apsolu/customize_labels_heading';
    //$information = '';
    //$heading = get_string('general');
    //$setting = new admin_setting_heading($name, $heading, $information);
    //$page->add($setting);
    //
    //// 4a. Activités sportives.
    //$name = 'local_apsolu/categories';
    //$setting = new admin_setting_configtext($name, get_string('categories', 'local_apsolu'), null,
    //    get_string('categories_default', 'theme_apsolu'), PARAM_TEXT);
    //$page->add($setting);

    //$settings->add($page);
}
