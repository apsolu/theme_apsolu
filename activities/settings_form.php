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
 * Définition du formulaire pour configurer l'affichage des formats de pratique.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/formslib.php');

/**
 * Classe définissant le formulaire pour configurer l'affichage des formats de pratique.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_apsolu_homepage_activities_form extends moodleform {
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
        $sectiontext = get_string('section_text', 'theme_apsolu');
        $infobox = get_string('homepage_activities_infobox', 'theme_apsolu');

        // Activation des chapeaux.
        $available = false;
        if ($available === true) {
            $activationarray = [];
            $mform->addElement('header', 'homepage_chapeaux', 'activer les chapeaux');
            $activationarray[] = $mform->addElement('checkbox'); // Section  Liste des activités.
            $activationarray[] = $mform->addElement('checkbox'); // Section 2 les stages.
            $activationarray[] = $mform->addElement('checkbox'); // Section 3 Evenements.
            $activationarray[] = $mform->addElement('checkbox'); // Section 4 Animations.
            $activationarray[] = $mform->addElement('checkbox'); // Section 5 Adhésion à l'AS.
            $mform->addGroup($activationarray, '');
        }

        // 1. Liste des activités.
        $title = get_string('the_activities_configuration', 'theme_apsolu');
        $mform->addElement('header', 'homepage_activities_list', $title);
        $mform->setExpanded('homepage_activities_list', $expanded = true);

        // 1a. Texte affiché.
        $mform->addElement('static', 'homepage_section2_text', $sectiontext, get_string('section2_text', 'theme_apsolu'));

        // Texte.
        $mform->addElement('editor', 'homepage_section2_activities_infobox_text_editor', $infobox, $attributes, $editoroptions);
        $mform->addHelpButton('homepage_section2_activities_infobox_text_editor', 'homepage_activities_infobox', 'theme_apsolu');
        $mform->setType('homepage_section2_activities_infobox_text', PARAM_RAW);

        // TODO : 2. Stages.
        // TODO : 3. Evenements.
        // TODO : 4. Animations.

        // 5. Pratiques autonomes.
        $title = get_string('practice', 'theme_apsolu');
        $mform->addElement('header', 'homepage_practice', $title);
        $mform->setExpanded('homepage_practice', $expanded = true);

        // Texte.
        $mform->addElement('editor', 'homepage_section2_practice_text_editor', $sectiontext, $attributes, $editoroptions);
        $mform->setType('homepage_section2_practice_text_editor', PARAM_RAW);

        // 6. Adhésion à l'association sportive.
        $title = get_string('sport_association', 'theme_apsolu');
        $mform->addElement('header', 'homepage_sport_association', $title);
        $mform->setExpanded('homepage_sport_association', $expanded = true);

        // Texte.
        $mform->addElement('editor', 'homepage_section2_association_text_editor', $sectiontext, $attributes, $editoroptions);
        $mform->setType('homepage_section2_association_text_editor', PARAM_RAW);

        // 7. Validation du formulaire.
        $buttonarray = [];
        $buttonarray[] =& $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
        $mform->addGroup($buttonarray, 'buttonar', '', [' '], false);
        $mform->closeHeaderBefore('buttonar');

        // Set default values.
        $this->set_data($defaults);
    }

    /**
     * Retourne les options passées aux éléments du formulaire de type editor.
     *
     * @return array
     */
    public static function get_editor_options() {
        $options = [];
        $options['subdirs'] = false;
        $options['maxbytes'] = 0; // Taille limite par défaut.
        $options['maxfiles'] = -1; // Nombre de fichiers attachés illimités.
        $options['context'] = context_system::instance();
        $options['noclean'] = true;
        $options['trusttext'] = false;

        return $options;
    }
}
