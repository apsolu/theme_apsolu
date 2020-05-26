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
 * Test theme_apsolu module.
 *
 * @package    theme_apsolu
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/../lib.php');

/**
 * Classe PHPUnit permettant de tester les fichiers lib.php du module theme_apsolu.
 *
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_apsolu_lib_testcase extends advanced_testcase {
    protected function setUp() {
        parent::setUp();

        // Initialise les images de fond.
        theme_apsolu_initialise_homepage_background_images();

        $this->resetAfterTest();
    }

    /**
     * Test la fonction theme_apsolu_get_main_scss_content().
     *
     * @return void
     */
    public function test_theme_apsolu_get_main_scss_content() {
        // Vérifie que les CSS sont bien générées.
        $this->assertNotEmpty(theme_apsolu_get_main_scss_content());
    }

    /**
     * Test la fonction theme_apsolu_initialise_homepage_background_images().
     *
     * @return void
     */
    public function test_theme_apsolu_initialise_homepage_background_images() {
        // Vérifie que l'initialisation des images de fond ne pose aucun problème.
        $this->assertNull(theme_apsolu_initialise_homepage_background_images());
    }

    /**
     * Test la fonction theme_apsolu_pluginfile().
     *
     * @return void
     */
    public function test_theme_apsolu_pluginfile() {
        $course = 1;
        $cm = null;
        $context = context_system::instance();
        $filearea = 'homepage';
        $args = array(THEME_APSOLU_BACKGROUND_IMAGE_1_ORIGINAL);
        $forcedownload = true;

        // Test le bon fonctionnement du téléchargement d'une image.
        $this->assertNotFalse(theme_apsolu_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload));

        // Contrôle qu'une erreur est levée lorsque le contexte n'est pas valide.
        $this->assertFalse(theme_apsolu_pluginfile($course, $cm, context_user::instance(1), $filearea, $args, $forcedownload));

        // Contrôle qu'une erreur est levée lorsque le filearea n'est pas valide.
        $this->assertFalse(theme_apsolu_pluginfile($course, $cm, $context, $filearea = 'draft', $args, $forcedownload));

        // Contrôle qu'une erreur est levée lorsque l'itemid n'existe pas.
        $this->assertFalse(theme_apsolu_pluginfile($course, $cm, $context, $filearea, $args = array(99), $forcedownload));
    }

    /**
     * Test la fonction theme_apsolu_process_css().
     *
     * @return void
     */
    public function test_theme_apsolu_process_css() {
        $css = '';
        $this->assertEmpty(theme_apsolu_process_css($css));

        $css = file_get_contents(__DIR__.'/../style/apsolu.css');
        $this->assertNotEmpty(theme_apsolu_process_css($css));

        // Contrôle que les "constantes" sont bien remplacées dans le fichier CSS final.
        $this->assertStringNotContainsString('THEME_APSOLU:BACKGROUND_IMAGE', theme_apsolu_process_css($css));
    }
}
