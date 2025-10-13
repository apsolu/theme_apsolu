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
 * A drawer based layout for the boost theme.
 *
 * @package   theme_apsolu
 * @copyright 2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Commenting.TodoComment.MissingInfoInline

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING')) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();

    // NOTE: workaround pour afficher le fil d'ariane sur les pages de type : user/index.php?id=<courseid>
    // TODO: creuser et déclarer éventuellement le bug chez Moodle.
    $OUTPUT->page->navbar->get_items();

    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primarymenu = theme_apsolu_get_primary_menu();

$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$renderer = $PAGE->get_renderer('core');
$headercontent = $header->export_for_template($renderer);

// Données pour le footer.
// Fenêtres modales.
$context = context_system::instance();
$component = 'theme_apsolu';
$filearea = 'homepage';
$options = ['context' => $context, 'clean' => false];

$footer = new stdClass();
$footer->accessibility_status = get_config($component, 'accessibility_status');
$text = get_config($component, 'accessibility_doc_text');
$content = file_rewrite_pluginfile_urls(
    $text,
    'pluginfile.php',
    $context->id,
    $component,
    $filearea,
    THEME_APSOLU_ACCESSIBILITY_DOC_TEXT
);
$footer->accessibility_doc_text = format_text($content, FORMAT_HTML, $options);

$text = get_config($component, 'legal_notice_doc_text');
$content = file_rewrite_pluginfile_urls(
    $text,
    'pluginfile.php',
    $context->id,
    $component,
    $filearea,
    THEME_APSOLU_LEGAL_NOTICE_DOC_TEXT
);
$footer->legal_notice_doc_text = format_text($content, FORMAT_HTML, $options);

$text = get_config($component, 'confidential_doc_text');
$content = file_rewrite_pluginfile_urls(
    $text,
    'pluginfile.php',
    $context->id,
    $component,
    $filearea,
    THEME_APSOLU_CONFIDENTIAL_DOC_TEXT
);
$footer->confidential_doc_text = format_text($content, FORMAT_HTML, $options);

$text = get_config($component, 'contact_doc_text');
$content = file_rewrite_pluginfile_urls(
    $text,
    'pluginfile.php',
    $context->id,
    $component,
    $filearea,
    THEME_APSOLU_CONTACT_DOC_TEXT
);
$footer->contact_doc_text = format_text($content, FORMAT_HTML, $options);

if (empty(get_config('theme_apsolu', 'enable_rewrite_rules')) === true) {
    // Affiche les vraies URL.
    $footer->accessibility_doc_url = new moodle_url('/theme/apsolu/documents/public/accessibility.php');
    $footer->legal_notice_doc_url = new moodle_url('/theme/apsolu/documents/public/legal_notice.php');
    $footer->confidential_doc_url = new moodle_url('/theme/apsolu/documents/public/confidential.php');
    $footer->contact_doc_url = new moodle_url('/theme/apsolu/documents/public/contact.php');
} else {
    // Affiche les URL réécrites.
    $footer->accessibility_doc_url = new moodle_url('/accessibilite');
    $footer->legal_notice_doc_url = new moodle_url('/mentions_legales');
    $footer->confidential_doc_url = new moodle_url('/confidentialite');
    $footer->contact_doc_url = new moodle_url('/contact');
}

// Liens personnalisés.
$footer->nav_link_1_url = get_config($component, 'nav_link_1_url');
$footer->nav_link_1_text = get_config($component, 'nav_link_1_text');

$footer->nav_link_2_url = get_config($component, 'nav_link_2_url');
$footer->nav_link_2_text = get_config($component, 'nav_link_2_text');

$footer->nav_link_3_url = get_config($component, 'nav_link_3_url');
$footer->nav_link_3_text = get_config($component, 'nav_link_3_text');

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'apsolu_footer' => $footer,
];

echo $OUTPUT->render_from_template('theme_boost/drawers', $templatecontext);
