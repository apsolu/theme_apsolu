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
 * Définition du formulaire pour configurer la page d'accueil du site.
 *
 * @package    theme_apsolu
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * Classe définissant le formulaire pour configurer la page d'accueil du site.
 *
 * @copyright  2020 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_apsolu_homepage_form extends moodleform {
    /**
     * Définit les champs du formulaire.
     *
     * @return void
     */
    protected function definition() {
        $mform = $this->_form;

        list($defaults) = $this->_customdata;

        $attributes = null;
        $editoroptions = self::get_editor_options();
        $filemanageroptions = self::get_filemanager_options();

        // 1. Général.
        $mform->addElement('header', 'homepage_general', get_string('general'));

        // Active.
        $label = get_string('use_apsolu_homepage', 'theme_apsolu');
        $mform->addElement('checkbox', 'homepage_enable', $label, get_string('enable'));
        $mform->setType('homepage_enable', PARAM_INT);

        // 2. Accueil.
        $label = get_string('named_section', 'theme_apsolu', get_string('home', 'theme_apsolu'));
        $mform->addElement('header', 'homepage_section1', $label);
        $mform->setExpanded('homepage_section1', $expanded = true);

        // Message affiché sur la section 'accueil'.
        $label = get_string('section_text', 'theme_apsolu');
        $mform->addElement('editor', 'homepage_section1_text_editor', $label, $attributes, $editoroptions);
        $mform->addRule('homepage_section1_text_editor', get_string('required'), 'required', null, 'client');
        $mform->setType('homepage_section1_text_editor', PARAM_RAW);

        // Encart d'accueil.
        $label = 'Encart accueil';
        $mform->addElement('editor', 'homepage_section1_welcome_text_editor', $label, $attributes, $editoroptions);
        $mform->setType('homepage_section1_welcome_text_editor', PARAM_RAW);

        // Image de fond.
        $label = get_string('background_image', 'theme_apsolu');
        $mform->addElement('filemanager', 'homepage_section1_background_image', $label, $attributes, $filemanageroptions);
        $mform->addHelpButton('homepage_section1_background_image', 'background_image', 'theme_apsolu');
        $mform->addRule('homepage_section1_background_image', get_string('required'), 'required', null, 'client');

        // Afficher les crédits.
        $label = get_string('show_credit', 'theme_apsolu');
        $mform->addElement('checkbox', 'homepage_section1_show_credit', $label, get_string('enable'));
        $mform->addHelpButton('homepage_section1_show_credit', 'show_credit', 'theme_apsolu');
        $mform->setType('homepage_section1_show_credit', PARAM_INT);

        // 3. Les activités.
        $label = get_string('named_section', 'theme_apsolu', get_string('the_activities', 'theme_apsolu'));
        $mform->addElement('header', 'homepage_section2', $label);
        $mform->setExpanded('homepage_section2', $expanded = true);

        // Texte affiché.
        $label = get_string('section_text', 'theme_apsolu');
        $mform->addElement('static', 'homepage_section2_text', $label, get_string('section2_text', 'theme_apsolu'));

        // Image de fond.
        $label = get_string('background_image', 'theme_apsolu');
        $mform->addElement('filemanager', 'homepage_section2_background_image', $label, $attributes, $filemanageroptions);
        $mform->addHelpButton('homepage_section2_background_image', 'background_image', 'theme_apsolu');
        $mform->addRule('homepage_section2_background_image', get_string('required'), 'required', null, 'client');

        // Afficher les crédits.
        $label = get_string('show_credit', 'theme_apsolu');
        $mform->addElement('checkbox', 'homepage_section2_show_credit', $label, get_string('enable'));
        $mform->addHelpButton('homepage_section2_show_credit', 'show_credit', 'theme_apsolu');
        $mform->setType('homepage_section2_show_credit', PARAM_INT);

        // 4. S'inscrire.
        $label = get_string('named_section', 'theme_apsolu', get_string('signup', 'theme_apsolu'));
        $mform->addElement('header', 'homepage_section3', $label);
        $mform->setExpanded('homepage_section3', $expanded = true);

        // Texte affiché.
        $label = get_string('section_text', 'theme_apsolu');
        $mform->addElement('editor', 'homepage_section3_text_editor', $label, array('cols' => '48'), $editoroptions);
        $mform->addRule('homepage_section3_text_editor', get_string('required'), 'required', null, 'client');
        $mform->setType('homepage_section3_text_editor', PARAM_RAW);

        // Image de fond.
        $label = get_string('background_image', 'theme_apsolu');
        $mform->addElement('filemanager', 'homepage_section3_background_image', $label, $attributes, $filemanageroptions);
        $mform->addHelpButton('homepage_section3_background_image', 'background_image', 'theme_apsolu');
        $mform->addRule('homepage_section3_background_image', get_string('required'), 'required', null, 'client');

        // Afficher les crédits.
        $label = get_string('show_credit', 'theme_apsolu');
        $mform->addElement('checkbox', 'homepage_section3_show_credit', $label, get_string('enable'));
        $mform->addHelpButton('homepage_section3_show_credit', 'show_credit', 'theme_apsolu');
        $mform->setType('homepage_section3_show_credit', PARAM_INT);

        // 5. Se connecter.
        $label = get_string('named_section', 'theme_apsolu', get_string('login', 'theme_apsolu'));
        $mform->addElement('header', 'homepage_section4', $label);
        $mform->setExpanded('homepage_section4', $expanded = true);

        // URL pour s'authentifier avec le compte institutionnel.
        $label = get_string('institutional_account_authentification_url', 'theme_apsolu');
        $mform->addElement('text', 'homepage_section4_institutional_account_url', $label, array('size' => '100'));
        $mform->setType('homepage_section4_institutional_account_url', PARAM_URL);

        // URL pour s'authentifier avec un compte générique.
        $label = get_string('non_institutional_account_authentification_url', 'theme_apsolu');
        $mform->addElement('text', 'homepage_section4_non_institutional_account_url', $label, array('size' => '100'));
        $mform->setType('homepage_section4_non_institutional_account_url', PARAM_URL);

        // 6. Validation du formulaire.
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');

        // Hidden fields.
        $mform->addElement('hidden', 'page', 'homepage');
        $mform->setType('page', PARAM_ALPHANUM);

        // Set default values.
        $this->set_data($defaults);
    }

    /**
     * Retourne les options passées aux éléments du formulaire de type editor.
     *
     * @return array
     */
    public static function get_editor_options() {
        $options = array();
        $options['subdirs'] = false;
        $options['maxbytes'] = 0; // Taille limite par défaut.
        $options['maxfiles'] = -1; // Nombre de fichiers attachés illimités.
        $options['context'] = context_system::instance();
        $options['noclean'] = true;
        $options['trusttext'] = false;

        return $options;
    }

    /**
     * Retourne les options passées aux éléments du formulaire de type filemanager.
     *
     * @return array
     */
    public static function get_filemanager_options() {
        $options = array();
        $options['subdirs'] = 0;
        $options['maxbytes'] = 0;
        $options['areamaxbytes'] = FILE_AREA_MAX_BYTES_UNLIMITED;
        $options['maxfiles'] = 1;
        $options['accepted_types'] = array('.jpg', '.png');
        $options['context'] = context_system::instance();

        return $options;
    }
}
