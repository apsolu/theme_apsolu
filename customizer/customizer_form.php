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
 * Définition du formulaire pour gérer la personnalisation de l'instance APSOLU.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die; // On s'assure que la page est accessible seulement via Moodle.

require_once("$CFG->libdir/formslib.php");
//require("$CFG->libdir/javascript-static.js");

/**
 * Classe qui définit le formulaire pour personnaliser l'instance Apsolu.
 */
class theme_apsolu_customizer_form extends moodleform {

    /**
     * Définit les champs du formulaire.
     *
     * @param moodle_page $page
     * @return void
     */
    protected function definition() {
        global $CFG;
        global $page;
        // TODO: Implement definition() method.
        $mform = $this->_form; // On stocke une référence au formulaire dans la variable $mform.
        $editoroptions = self::get_editor_options();

        list($defaults) = $this->_customdata; // Assigne une liste de variables dans l'opération.
        $attributes = null;

        // 1. Général : activer la personnalisation de l'instance.
        $mform->addElement('header', 'customizer_general', get_string('general'));

        // 1a. Activation.
        $label = get_string('use_apsolu_customizer', 'theme_apsolu');
        $mform->addElement('checkbox', 'customizer_enable', $label, get_string('enable'));
        $mform->setType('customizer_enable', PARAM_INT);

        // 2. Jeux de couleurs.
        // TODO: Fieldset.
        $mform->addElement('header', 'customizer_colors', get_string('customizer_colors_label', 'theme_apsolu'));
        //$mform->setExpanded('customizer_colors', $expanded = true);

        // 2a. TODO: Couleur principale 1.
        $mform->addElement('text', 'brandcolor-1', get_string('brandcolor_1', 'theme_apsolu'));
        $mform->addElement('html','<div class="admin_colourpicker" id="brandcolor-1"></div>');
        $mform->addHelpButton('brandcolor-1','brandcolor_1','theme_apsolu');
        $mform->setDefault('', $this->_customdata['']);
        // 2b. TODO: Couleur principale 3.
        $mform->addElement('text', 'brandcolor-2', get_string('brandcolor_2', 'theme_apsolu'));
        $mform->addHelpButton('brandcolor-2','brandcolor_2','theme_apsolu');
        $mform->setDefault('', $this->_customdata['']);
        // 2c. TODO: Couleur des liens.
        $mform->addElement('text', 'brandcolor-link', get_string('brandcolor_links', 'theme_apsolu'));
        $mform->addHelpButton('brandcolor-link','brandcolor_links','theme_apsolu');
        $mform->setDefault('', $this->_customdata['']);

        // 3. TODO: Personnalisation des libellés (Optionnel).

        // 4. Personnalisation de la navbar apsolu.
        $mform->addElement('header', 'customizer_navbar_links', get_string('customizer_navbar_label', 'theme_apsolu'));

        // 4a. Lien n°1.
        $mform->addElement('text', 'navbar-link-1', get_string('customizer_navbar_link_1', 'theme_apsolu'));
        $mform->setType('navbar-link-1', PARAM_URL);
        // 4b. Lien n°2.
        $mform->addElement('text', 'navbar-link-2', get_string('customizer_navbar_link_2', 'theme_apsolu'));
        $mform->setType('navbar-link-2', PARAM_URL);
        // 4b. Lien n°3.
        $mform->addElement('text', 'navbar-link-2', get_string('customizer_navbar_link_3', 'theme_apsolu'));
        $mform->setType('navbar-link-3', PARAM_URL);

        // 5. Personnalisations des modals.
        // TODO: Fieldset.
        $mform->addElement('header', 'customizer_texts', get_string('legal_customizer_label', 'theme_apsolu'));

        // 5a. TODO: Recommandations médicales.
        $mform->addElement('editor', 'medical_text_editor', get_string('legal_customizer_medical', 'theme_apsolu'), $attributes,
            $editoroptions);
        $mform->addRule('medical_text_editor', get_string('required'), 'required', null, 'client');
        $mform->setType('medical_text_editor', PARAM_RAW);
        // 5b. TODO: Mentions légales.
        $mform->addElement('editor', 'notice_text_editor', get_string('legal_customizer_notice', 'theme_apsolu'), $attributes,
            $editoroptions);
        $mform->addRule('notice_text_editor', get_string('required'), 'required', null, 'client');
        $mform->setType('notice_text_editor', PARAM_RAW);
        // 5c. TODO: Politique de confidentialité.
        $mform->addElement('editor', 'confidential_text_editor', get_string('legal_customizer_confidential', 'theme_apsolu'),
            $attributes, $editoroptions);
        $mform->addRule('confidential_text_editor', get_string('required'), 'required', null, 'client');
        $mform->setType('confidential_text_editor', PARAM_RAW);
        // 5d. TODO: Nous contacter.
        $mform->addElement('editor', 'contact_text_editor', get_string('legal_customizer_contact', 'theme_apsolu'), $attributes,
            $editoroptions);
        $mform->addRule('contact_text_editor', get_string('required'), 'required', null, 'client');
        $mform->setType('contact_text_editor', PARAM_RAW);

        //6. Validation du formulaire.
        $this->add_action_buttons();

        // Set default values.
        $this->set_data($defaults);
    }

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
}
