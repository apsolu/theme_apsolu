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
 * Version theme.
 *
 * @package   theme_apsolu
 * @copyright 2019 Université Rennes 2 {@link https://www.univ-rennes2.fr}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// This is the version of the plugin.
$plugin->version = 2024100900;

$plugin->release = '4.4.4';

// Requires Moodle 4.4.0.
$plugin->requires = 2024042200;

// This is the component name of the plugin - it always starts with 'theme_'
// for themes and should be the same as the name of the folder.
$plugin->component = 'theme_apsolu';

// This is a list of plugins, this plugin depends on (and their versions).
$plugin->dependencies = [
    'local_apsolu' => 2023012500,
];

// This is a stable release.
$plugin->maturity = MATURITY_STABLE;

// This is the named version.
$plugin->release = 2.0;
