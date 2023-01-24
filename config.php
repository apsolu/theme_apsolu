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
 * Theme config.
 *
 * @package   theme_apsolu
 * @copyright 2019 Université Rennes 2 {@link https://www.univ-rennes2.fr}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// The $THEME var. is defined before this page is included and we can define settings by adding properties to this global object.

// Nom du thème (identique au suffixe du plugin et au nom du dossier).
$THEME->name = 'apsolu';
// On laisse vide pour utiliser du scss.
$THEME->sheets = [];
// Critique : on hérite de Boost les templates, les styles ainsi qu'une partie de ses settings.
$THEME->parents = ['boost'];
// This is the function that returns the SCSS source for the main file in our theme.
$THEME->scss = function($theme) {
    return theme_apsolu_get_main_scss_content($theme);
};
// The content returned by this function is added before the main SCSS.
$THEME->prescsscallback = 'theme_apsolu_get_pre_scss';
// This is the function that all CSS should be passed to before being delivered.
$THEME->csspostprocess = 'theme_apsolu_process_css';
// Définit les règles de style de l'éditeur de texte.
$THEME->editor_sheets = array('editor');
// Pas de prise en charge des blocks dans Boost.
$THEME->enable_dock = false;
// This is an old setting used to load specific CSS for some YUI JS. Utile pour l'appel au colourpicker.
$THEME->yuicssmodules = array();
// Most themes will use this rendererfactory as this is the one that allows the theme to override any other renderer.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

$THEME->haseditswitch = false;

$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;

// Définition des templates qui vont structurer nos pages.
$THEME->layouts = array(
    // Most backwards compatible layout without the blocks - this is the layout used by default.
    'base' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array(),
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    // Main course page.
    'course' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'coursecategory' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // The site home page.
    'frontpage' => array(
        'theme' => 'apsolu',
        'file' => 'home.php',
        'regions' => array(),
    ),
    // Server administration scripts.
    'admin' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // My dashboard page.
    'mydashboard' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // My public page.
    'mypublic' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    'login' => array(
        'theme' => 'boost',
        'file' => 'login.php',
        'regions' => array(),
        'options' => array('langmenu' => true),
    ),
    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'theme' => 'boost',
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => true),
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'theme' => 'boost',
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array(
            'nofooter' => true,
            'nocoursefooter' => true,
            'activityheader' => array(
                'nocompletion' => true
            )
        ),
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => array(
        'theme' => 'boost',
        'file' => 'embedded.php',
        'regions' => array(),
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
    // Please be extremely careful if you are modifying this layout.
    'maintenance' => array(
        'theme' => 'boost',
        'file' => 'maintenance.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'theme' => 'boost',
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => false),
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'theme' => 'boost',
        'file' => 'embedded.php',
        'regions' => array(),
    ),
    // The pagelayout used for reports.
    'report' => array(
        'theme' => 'boost',
        'file' => 'drawers.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'theme' => 'boost',
        'file' => 'secure.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    )
);
