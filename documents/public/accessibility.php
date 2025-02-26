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
 * Affiche une page d'accessibilité.
 *
 * @package    theme_apsolu
 * @copyright  2024 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir .'/filelib.php');
require_once(__DIR__ .'/../../lib.php');

defined('MOODLE_INTERNAL') || die();

// Variable for template.
$context = context_system::instance();
$options = ['context' => context_system::instance(), 'clean' => false];
$component = 'theme_apsolu';
$filearea = 'homepage';
$url = new moodle_url($CFG->wwwroot.'/theme/apsolu/documents/public/accessibility.php');

$PAGE->set_url($url);
$PAGE->set_title(get_string('accessibility', $component));
$PAGE->set_heading(get_string('accessibility', $component));
$PAGE->set_pagelayout('home');


// Contenu de la déclaration d'accessibilité.
$data = new stdClass();
$data->public_page_heading = get_config($component, 'accessibility_status');
$text = get_config($component, 'accessibility_doc_text');
$content = file_rewrite_pluginfile_urls($text, '/../../pluginfile.php', $context->id,
    $component, $filearea, THEME_APSOLU_ACCESSIBILITY_DOC_TEXT);
$data->public_page_content = format_text($content, FORMAT_HTML, $options);

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_apsolu/publicpage', $data);
echo $OUTPUT->footer();
