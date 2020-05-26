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

    if (is_dir($cachedir) === true) {
        file_put_contents($sitescachefile, json_encode($sites));
        file_put_contents($activitiescachefile, json_encode($activities));
    }
} else {
    // Use cache.
    $sites = json_decode(file_get_contents($sitescachefile));
    $activities = json_decode(file_get_contents($activitiescachefile));
}

// Build variable for template.
$data = new stdClass();
$data->sites = $sites;
$data->activities = $activities;
$data->count_activities = count($activities);
$data->wwwroot = $CFG->wwwroot;
$data->is_siuaps_rennes = isset($CFG->is_siuaps_rennes);
$data->section1_text = get_config('theme_apsolu', 'homepage_section1_text');
$data->section1_image_credits = get_config('theme_apsolu', 'homepage_section1_image_credits');
$data->section2_image_credits = get_config('theme_apsolu', 'homepage_section2_image_credits');
$data->section3_text = get_config('theme_apsolu', 'homepage_section3_text');
$data->section3_image_credits = get_config('theme_apsolu', 'homepage_section3_image_credits');

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
