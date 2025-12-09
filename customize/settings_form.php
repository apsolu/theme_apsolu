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
 * Définition du formulaire pour personnaliser le thème APSOLU.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

use theme_apsolu\color;

require_once($CFG->libdir . '/formslib.php');

/**
 * Classe définissant le formulaire pour personnaliser le thème APSOLU.
 *
 * @package    theme_apsolu
 * @copyright  2022 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_apsolu_customize_form extends moodleform {
    /**
     * Définit les champs du formulaire.
     *
     * @throws coding_exception
     * @throws moodle_exception
     *
     * @return void
     */
    protected function definition() {
        global $PAGE;

        $mform = $this->_form; // On stocke une référence au formulaire dans la variable $mform.

        [$defaults] = $this->_customdata; // Assigne une liste de variables dans l'opération.

        $PAGE->requires->js_call_amd('local_apsolu/colorpicker', 'initialise'); // Initialise les colorpickers.

        $attributes = null;
        $editoroptions = self::get_editor_options();
        $filemanageroptions = self::get_filemanager_options();

        // Variables.
        $component = 'theme_apsolu';
        $urllabel = get_string('nav_url_label', $component);
        $textlabel = get_string('nav_text_label', $component);
        $classnames = 'd-block form-text mb-5 text-muted';
        $colorinfo = [
            get_string('brandcolor_1_help', 'theme_apsolu'),
            get_string('brandcolor_2_help', 'theme_apsolu'),
            get_string('brandcolor_links_help', 'theme_apsolu'),
        ];

        // 1. Personnaliser les couleurs.
        $mform->addElement('header', 'theme_apsolu_customize_colors', get_string('customizer_colors_label', $component));

        // 1a. TODO: Couleur principale 1.
        $attributes = ['data-apsolu' => 'colorpicker', 'aria-describedby' => 'colorHelp1'];
        $mform->addElement('text', 'custom_brandcolor', get_string('brandcolor_1_label', 'theme_apsolu'), $attributes);
        $mform->addElement('html', '<small id="colorHelp1" class="' . $classnames . '">' . $colorinfo[0] . '</small>');
        $mform->setType('custom_brandcolor', PARAM_TEXT);

        // 1b. TODO: Couleur principale 2.
        $attributes = ['data-apsolu' => 'colorpicker', 'aria-describedby' => 'colorHelp2'];
        $mform->addElement('text', 'custom_brandcolor_2', get_string('brandcolor_2_label', 'theme_apsolu'), $attributes);
        $mform->addElement('html', '<small id="colorHelp2" class="' . $classnames . '">' . $colorinfo[1] . '</small>');
        $mform->setType('custom_brandcolor_2', PARAM_TEXT);

        // 1c. TODO: Couleur des liens.
        $attributes = ['data-apsolu' => 'colorpicker', 'aria-describedby' => 'colorHelp3'];
        $mform->addElement('text', 'custom_brandcolor_links', get_string('brandcolor_links_label', 'theme_apsolu'), $attributes);
        $mform->addElement('html', '<small id="colorHelp3" class="' . $classnames . '">' . $colorinfo[2] . '</small>');
        $mform->setType('custom_brandcolor_links', PARAM_TEXT);

        // 2. Personnaliser la barre de liens.
        $mform->addElement('header', 'theme_apsolu_customize_links', get_string('customizer_links_label', $component));
        $mform->closeHeaderBefore('theme_apsolu_customize_footer');
        $information = get_string('customize_links_desc', 'theme_apsolu');
        $mform->addElement('html', $information);

        // 2a. Lien n°1.
        $url1 = [
            $mform->createElement('text', 'nav_link_1_url', null, ["placeholder" => $urllabel]),
            $mform->createElement('text', 'nav_link_1_text', null, ["placeholder" => $textlabel]),
        ];
        $mform->setType('nav_link_1_url', PARAM_URL);
        $mform->setType('nav_link_1_text', PARAM_RAW_TRIMMED);
        $mform->addGroup($url1, 'nav_link_1', 'Lien n°1', '', false);

        // 2b. Lien n°2.
        $url2 = [
            $mform->createElement('text', 'nav_link_2_url', null, ["placeholder" => $urllabel]),
            $mform->createElement('text', 'nav_link_2_text', null, ["placeholder" => $textlabel]),
        ];
        $mform->setType('nav_link_2_url', PARAM_URL);
        $mform->setType('nav_link_2_text', PARAM_RAW_TRIMMED);
        $mform->addGroup($url2, 'nav_link_2', 'Lien n°2', '', false);

        // 2c. Lien n°3.
        $url3 = [
            $mform->createElement('text', 'nav_link_3_url', null, ["placeholder" => $urllabel]),
            $mform->createElement('text', 'nav_link_3_text', null, ["placeholder" => $textlabel]),
        ];
        $mform->setType('nav_link_3_url', PARAM_URL);
        $mform->setType('nav_link_3_text', PARAM_RAW_TRIMMED);
        $mform->addGroup($url3, 'nav_link_3', 'Lien n°3', '', false);

        // 2d. Afficher le lien vers apsolu.fr.
        $mform->addElement(
            'advcheckbox',
            'show_logo',
            get_string('link_4', 'theme_apsolu'),
            get_string('show_apsolu_project_logo', 'theme_apsolu'),
            null,
            [0, 1]
        );

        // 3. Personnaliser le pied de page.
        $mform->addElement('header', 'theme_apsolu_customize_footer', get_string('customize_footer', $component));
        $mform->closeHeaderBefore('buttonar');
        $information = get_string('customize_footer_desc', $component);
        $mform->addElement('html', $information);

        // 3a. Bloc 1 : Logos.
        $title = get_string('footer_block', 'theme_apsolu') . ' 1';
        $mform->addElement('html', '<h4>' . $title . '</h4>');
        $information = 'Vous pouvez placer jusqu\'à 3 logos dans le pied de page';
        $mform->addElement('html', $information);

        $logos = [
            "footer_logo_1" => [THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_1, 'homepage_footer_logo_1', 'Logo n°1'],
            "footer_logo_2" => [THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_2, 'homepage_footer_logo_2', 'Logo n°2'],
            "footer_logo_3" => [THEME_APSOLU_HOMEPAGE_FOOTER_LOGO_3, 'homepage_footer_logo_3', 'Logo n°3'],
        ];

        $mform->addElement('filemanager', $logos["footer_logo_1"][1], $logos["footer_logo_1"][2], $attributes, $filemanageroptions);
        $mform->addElement('filemanager', $logos["footer_logo_2"][1], $logos["footer_logo_2"][2], $attributes, $filemanageroptions);
        $mform->addElement('filemanager', $logos["footer_logo_3"][1], $logos["footer_logo_3"][2], $attributes, $filemanageroptions);

        // 3b. Bloc 2 : Note de bas de page.
        $title = get_string('footer_block', $component) . ' 2';
        $mform->addElement('html', '<h4>' . $title . '</h4>');

        $label = 'Note de pied de page';
        $mform->addElement('editor', 'footer_text_section_editor', $label, $attributes, $editoroptions);
        $mform->setType('footer_text_section_editor', PARAM_TEXT);

        // 3c. Bloc 3 : Liens hypertexte.
        $title = get_string('footer_block', $component) . ' 3';
        $mform->addElement('html', '<h4>' . $title . '</h4>');
        $mform->addElement('static', 'Liens du pied de page');
        $description = '<div class="alert alert-secondary d-flex align-items-center">
            <i class="fa fa-info-circle me-3" aria-hidden="true"></i>
            <p class="mb-0">Affiche les liens hypertexte déjà définis dans la barre de liens.</p>
            </div>';
        $mform->addElement('html', $description);

        // 4. TODO: Personnaliser les libellés.
        $available = false;
        if ($available === true) {
            $mform->addElement('header', 'theme_apsolu_customize_labels', get_string('customize_labels', $component));
            $mform->closeHeaderBefore('buttonar');
            $information = get_string('customize_labels_desc', $component);
            $mform->addElement('html', $information);
        }

        // 5. Validation du formulaire.
        $buttonarray = [];
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));

        $mform->addGroup($buttonarray, 'buttonar', '', [' '], false);
        $mform->closeHeaderBefore('buttonar');

        // Hidden fields.
        $mform->addElement('hidden', 'page', 'customize');
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
        $options = [];
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
        $options = [];
        $options['subdirs'] = 0;
        $options['maxbytes'] = 0;
        $options['areamaxbytes'] = FILE_AREA_MAX_BYTES_UNLIMITED;
        $options['maxfiles'] = 1;
        $options['accepted_types'] = ['.jpg', '.png'];
        $options['context'] = context_system::instance();

        return $options;
    }

    /**
     * Valide les données envoyées dans le formulaire.
     *
     * @param array $data
     * @param array $files
     *
     * @return array The errors that were found.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Contrôle le contraste de la couleur principale.
        if (color::has_sufficient_contrast('#000', $data['custom_brandcolor']) === false) {
            $errors['custom_brandcolor'] = get_string('brandcolor_1_error', 'theme_apsolu');
        }

        // Contrôle le contraste de la couleur secondaire.
        if (color::has_sufficient_contrast('#FFF', $data['custom_brandcolor_2']) === false) {
            $errors['custom_brandcolor_2'] = get_string('brandcolor_2_error', 'theme_apsolu');
        }

        // Contrôle le contraste de la couleur des liens.
        if (color::has_sufficient_contrast($data['custom_brandcolor_links'], '#FFF') === false) {
            $errors['custom_brandcolor_links'] = get_string('brandcolor_links_error', 'theme_apsolu');
        }

        return $errors;
    }
}
