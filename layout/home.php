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
 * Layout spécifique à la page d'accueil Apsolu. Reprend en grande partie la configuration du layout columns1 du thème Boost.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$isloggedin = (isloggedin() && !isguestuser());
if ($isloggedin) {
    // Menu principal d'un utilisateur authentifié.
    $primarymenu = theme_apsolu_get_primary_menu();
    $menu = $primarymenu['moremenu'];
} else {
    // Menu principal d'un utilisateur non authentifié.
    $menu = [];
    for ($i = 1; $i <= 3; $i++) {
        $url = get_config('theme_apsolu', sprintf('nav_link_%s_url', $i));
        $text = get_config('theme_apsolu', sprintf('nav_link_%s_text', $i));
        if (empty($url) === true || empty($text) === true) {
            continue;
        }

        $menu[] = ['text' => $text, 'url' => $url];
    }
}

$bodyattributes = $OUTPUT->body_attributes([]);
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'isloggedin' => $isloggedin,
    'menu' => $menu,
    'homepage_section2_association_text' => get_config('theme_apsolu', 'homepage_section2_association_text'),
    'homepage_section2_practice_text' => get_config('theme_apsolu', 'homepage_section2_practice_text'),
];

echo $OUTPUT->render_from_template('theme_apsolu/home', $templatecontext);
