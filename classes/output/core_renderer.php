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
 * ${PLUGINNAME} file description here.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_apsolu\output;

defined('MOODLE_INTERNAL') || die;

/**
 * Custom Core renderer.
 */
class core_renderer extends \theme_boost\output\core_renderer {
    public function nav_link_1_text():string {
        return "Université Rennes 1";
    }

    public function nav_link_2_text():string {
        return "Université Rennes 2";
    }

    public function nav_link_3_text():string {
        return "SIUAPS Rennes";
    }

    public function nav_link_1_url():string {
        return "https://www.univ-rennes1.fr/";
    }

    public function nav_link_2_url():string {
        return "https://www.univ-rennes2.fr/";
    }

    public function nav_link_3_url():string {
        return "https://siuaps.univ-rennes.fr/";
    }

}
