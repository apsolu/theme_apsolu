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
 * Formulaire d'édition des informations de contact.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/formslib.php");

/**
 * Formulaire d'édition des informations de contact.
 *
 * @return void
 */
class theme_apsolu_contact_form extends moodleform {
    /**
     * Définit les champs du formulaire.
     */
    protected function definition() {
        $mform = $this->_form;

        list($defaults) = $this->_customdata;

        $editoroptions = self::get_editor_options();

        $component = 'theme_apsolu';
        $document = 'contact';

        // 1. Général.
        $mform->addElement('header', 'general', get_string('general'));

        // 1a. Active les informations de contact.
        $mform->addElement('checkbox', 'custom_'.$document.'_active', 'Activer l\'édition');
        $mform->setType('custom_'.$document.'_active', PARAM_INT);

        // 2. Editer les informations de contact.
        $mform->addElement('header', $document.'_edit', get_string('customize_'.$document, $component));

        // 2a. Editeur de texte.
        $mform->addElement('editor', $document.'_doc_text_editor', get_string('document_text', $component), null, $editoroptions);
        $mform->addRule($document.'_doc_text_editor', get_string('required'), 'required', null, 'client');
        $mform->setType($document.'_doc_text', PARAM_RAW);
        $mform->addHelpButton($document.'_doc_text_editor', 'modal_content', 'theme_apsolu');

        // 2b. Désactive l'éditeur si les informations de contact ne sont pas activées.
        $mform->disabledIf($document.'_doc_text_editor', 'custom_'.$document.'_active');

        // 3. Validation du formulaire.
        // Ajoute le bouton submit.
        $this->add_action_buttons(0);

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
        $options['maxfiles'] = 0; // Aucun fichier autorisé.
        $options['context'] = context_system::instance();
        $options['noclean'] = true;
        $options['trusttext'] = false;
        $options['enable_filemanagement'] = false; // On désactive les boutons de gestion de fichiers.

        return $options;
    }
}
