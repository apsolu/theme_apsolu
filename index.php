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
 * Affiche une page d'accueil personnalisée.
 *
 * @package    theme_apsolu
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use UniversiteRennes2\Apsolu as apsolu;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/homepage/settings_form.php');

// Cache stuff.
$cachedir = $CFG->dataroot.'/apsolu/theme_apsolu/cache/homepage';
$sitescachefile = $cachedir.'/sites.json';
$activitiescachefile = $cachedir.'/activities.json';

$now = new DateTime();

if (is_file($activitiescachefile)) {
    $cache = new DateTime(date('F d Y H:i:s.', filectime($activitiescachefile)));
} else {
    $cache = $now;
}

// Génère la liste des activités.
if ($cache <= $now->sub(new DateInterval('PT5M'))) {
    // Rebuild cache.

    // Get sites.
    $sites = $DB->get_records('apsolu_cities', $conditions = array(), $sort = 'name');

    // Get activities.
    $sql = "SELECT DISTINCT cc.id, cc.name, cc.description".
        " FROM {course_categories} cc".
        " JOIN {course} c ON cc.id = c.category".
        " JOIN {apsolu_courses} ac ON ac.id = c.id".
        " WHERE c.visible = 1".
        " AND ac.on_homepage = 1".
        " ORDER BY cc.name";
    $activities = $DB->get_records_sql($sql);

    // Mise en cache.
    $sites = array_values($sites);
    $activities = array_values($activities);

    $iscachedirexists = is_dir($cachedir);
    if ($iscachedirexists === false) {
        $iscachedirexists = mkdir($cachedir, $CFG->directorypermissions, $recursive = true);
    }

    if ($iscachedirexists === true) {
        file_put_contents($sitescachefile, json_encode($sites));
        file_put_contents($activitiescachefile, json_encode($activities));
    }
} else {
    // Use cache.
    $sites = json_decode(file_get_contents($sitescachefile));
    $activities = json_decode(file_get_contents($activitiescachefile));
}

// Build variable for template.
$context = context_system::instance();
$component = 'theme_apsolu';
$filearea = 'homepage';
$options = theme_apsolu_homepage_form::get_editor_options();

$data = new stdClass();
$data->sites = $sites;
$data->multisites = (count($sites) > 1);
$data->activities = $activities;
$data->count_activities = count($activities);
$data->wwwroot = $CFG->wwwroot;
$data->is_siuaps_rennes = isset($CFG->is_siuaps_rennes);

// Sections personnalisées.
// Section 1.
$text = get_config('theme_apsolu', 'homepage_section1_text');
$content = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_1_TEXT);
$data->section1_text = format_text($content, FORMAT_HTML, $options);

// Section 2.
$text = get_config('theme_apsolu', 'homepage_section2_activities_infobox_text');
$content = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_ACTIVITIES_INFOBOX_TEXT);
$data->homepage_section2_activities_infobox_text = format_text($content, FORMAT_HTML, $options);

$text = get_config('theme_apsolu', 'homepage_section2_practice_text');
$content = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_PRACTICE_TEXT);
$data->homepage_section2_practice_text = format_text($content, FORMAT_HTML, $options);

$text = get_config('theme_apsolu', 'homepage_section2_association_text');
$content = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_2_ASSOCIATION_TEXT);
$data->homepage_section2_association_text = format_text($content, FORMAT_HTML, $options);

// Section 3.
$text = get_config('theme_apsolu', 'homepage_section3_text');
$content = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_HOMEPAGE_SECTION_3_TEXT);
$data->section3_text = format_text($content, FORMAT_HTML, $options);

// Images de section.
$data->section1_image_credits = get_config('theme_apsolu', 'homepage_section1_image_credits');
$data->section2_image_credits = get_config('theme_apsolu', 'homepage_section2_image_credits');
$data->section3_image_credits = get_config('theme_apsolu', 'homepage_section3_image_credits');

// Fenêtes modales.
$text = get_config('theme_apsolu', 'legal_notice_doc_text');
$content = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_LEGAL_NOTICE_DOC_TEXT);
$data->legal_notice_doc_text = format_text($content, FORMAT_HTML, $options);

$text = get_config('theme_apsolu', 'confidential_doc_text');
$content = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_CONFIDENTIAL_DOC_TEXT);
$data->confidential_doc_text = format_text($content, FORMAT_HTML, $options);

$text = get_config('theme_apsolu', 'contact_doc_text');
$content = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_CONTACT_DOC_TEXT);
$data->contact_doc_text = format_text($content, FORMAT_HTML, $options);

// Set last menu link.
if (isloggedin() && !isguestuser()) {
    // Si l'utilisateur est déjà authentifié, on le renvoie vers son tableau de bord.
    $data->dashboard_link = $CFG->wwwroot.'/my/';
} else {
    $noauth = true;

    $data->institutional_account_url = get_config('theme_apsolu', 'homepage_section4_institutional_account_url');
    if (empty($data->institutional_account_url) === false) {
        $data->institutional_account_url = new moodle_url($data->institutional_account_url);
        $noauth = false;
    }

    $data->non_institutional_account_url = get_config('theme_apsolu', 'homepage_section4_non_institutional_account_url');
    if (empty($data->non_institutional_account_url) === false) {
        $data->non_institutional_account_url = new moodle_url($data->non_institutional_account_url);
        $noauth = false;
    }

    if ($noauth === true) {
        $data->login_link = $CFG->wwwroot.'/login/index.php';
    } else {
        $data->login_link = $CFG->wwwroot.'/#authentification';
    }
}

$PAGE->set_pagelayout('base'); // Désactive l'affichage des blocs (ou pas).

// Call template.
echo $OUTPUT->render_from_template('theme_apsolu/homepage', $data);
