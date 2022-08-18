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
 * Language file.
 *
 * @package   theme_apsolu
 * @copyright 2019 Universite Rennes 2
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// Nom du thème.
$string['pluginname'] = 'Apsolu';

// Chaines de caractères.
// 1. Général.
$string['activities'] = 'Les activités';
$string['activities_list'] = 'Liste des activités';
$string['background_image'] = 'Image de fond';
$string['background_image_help'] = 'Image de fond placée en arrière-plan sur la page d\'accueil. L\'image sera redimentionnée par le serveur aux formats suivants : 240x160, 480x320 et 960x640.';
$string['choosereadme'] = '<p>Apsolu est un thème basé sur le thème Moodle "Boost".</p>
<p>
Contact : contact@apsolu.fr<br>
Site officiel : <a href="https://www.apsolu.fr/">www.apsolu.fr</a><br>
</p>';
$string['configtitle'] = 'Apsolu';
$string['default_homepage_section1_text'] = '<p>Bienvenue sur le site de gestion des inscriptions au service des sports.</p>'.
    '<p>Ce service propose à l’ensemble des étudiants et des personnels une formation pour tous les niveaux à la pratique des activités physiques et sportives.</p>';
$string['institutional_account_authentification_url'] = 'URL d\'authentification des comptes institutionnels';
$string['home'] = 'Accueil';
$string['i_have_an_institutional_account'] = 'J\'ai un compte université';
$string['i_have_not_an_institutional_account'] = 'Je n\'ai pas de compte université';
$string['login'] = 'Se connecter';
$string['named_section'] = 'Section "{$a}"';
$string['non_institutional_account_authentification_url'] = 'URL d\'authentification des comptes non institutionnels';
$string['privacy:metadata'] = 'Le thème Apsolu n\'enregistre aucune donnée personnelle.';
$string['section_text'] = 'Texte de la section';
$string['section2_text'] = '<div class="alert alert-info">Le tableau des activités est généré automatiquement.</div>';
$string['settings_configuration_homepage'] = 'Page d\'accueil';
$string['settings_root'] = 'APSOLU'; // TODO: chaîne temporaire ; à supprimer dans le fichier settings.php.
$string['show_credit'] = 'Afficher les crédits photographiques';
$string['show_credit_help'] = 'Affiche le nom de l\'auteur et le type de licence de la photographie.';
$string['signup'] = 'S\'inscrire';
$string['the_activities'] = 'Les activités';
$string['use_apsolu_homepage'] = 'Utiliser la page d\'accueil APSOLU';
$string['settings_configuration_customizer'] = 'Personnalisation';
$string['use_apsolu_customizer'] = 'Personnaliser l\'instance APSOLU';

// 2. APSOLU > Documents.
$string['confidential'] = 'Politique de confidentialité';
$string['legal_notice'] = 'Mentions légales';
$string['medical_rec'] = 'Recommandations médicales';
$string['contact_us'] = 'Nous contacter';
$string['documents_settings'] = 'Documents';
$string['customize_label'] = 'Personnalisation des infos légales';
$string['customize_medical'] = 'Éditer les recommandations médicales';
$string['customize_confidential'] = 'Éditer la politique de confidentialité';
$string['customize_legal_notice'] = 'Éditer les mentions légales';
$string['customize_contact'] = 'Éditer les informations de contact';
$string['document_text'] = 'Contenu du document';
$string['modal_content_help'] = 'Ce document sera affiché dans une fenêtre modale, au clic sur le lien approprié dans le footer.';

//$string['default_'] = '';

// 3. Personnalisation des couleurs.
$string['customizer_colors_label'] = 'Personnaliser les couleurs';
$string['customizer_colors_desc'] = 'Sélectionnez le schéma de couleurs que vous voulez avoir pour votre site.';
$string['brandcolor_1_label'] = 'Couleur principale';
$string['brandcolor_2_label'] = 'Couleur secondaire';
$string['brandcolor_links_label'] = 'Couleur des liens';
$string['brandcolor_1'] = '$brandcolor-1';
$string['brandcolor_2'] = '$brandcolor-2';
$string['brandcolor_links'] = '$brandcolor-link';
$string['brandcolor_1_help'] = 'La couleur affichée en arrière-plan du nom du site et en bordure.';
$string['brandcolor_2_help'] = 'La couleur affichée en arrière-plan de la barre de navigation et du pied-de-page.';
$string['brandcolor_links_help'] = 'La couleur utilisée pour les liens hypertexte et les boutons du site.';

// 4. Personnalisation de la navbar.
$string['customizer_navbar_label'] = 'Personnaliser la barre de liens';
$string['nav_url_label'] = 'Lien menu';
$string['nav_text_label'] = 'Texte à afficher pour le lien';

// 5. Personnalisation des libellés.
$string['customize_labels'] = 'Personnaliser les libellés du site';
$string['customize_labels_desc'] = '<div class="alert alert-info"><p>Si vous utilisez APSOLU autrement que pour la gestion d\'activités sportives, vous pouvez personnaliser les libellés affichés dans l\'administration ainsi que sur la page d\'accueil. Par défaut, le contexte sportif est utilisé. Pour modifier d\'autres libellés absents sur cette page, rendez-vous dans le menu "Langue > Personnalisation de la langue".</p></div>';
// 5a. Personnalisation des libellés > Libellés par défaut.
$string['add_category_default'] = 'Ajouter une activité physique';
$string['category_has_been_deleted_default'] = 'L\'activité physique a été supprimée.';
$string['category_must_be_parent_of_a_grouping_of_sports_activities_default'] = 'La catégorie « {$a} » doit être placée dans un groupement d\'activités sportives.';
$string['category_saved_default'] = 'Activité physique enregistrée.';
$string['category_updated_default'] = 'Activité physique modifiée.';
$string['course_has_been_moved_to_because_selected_category_did_not_match_to_grouping_of_sports_activities_default'] = 'Le cours « {$a->fullname} » a été déplacé dans la catégorie « {$a->category} », car la catégorie sélectionnée ne correspondait pas à une catégorie d\'activité sportive.';
$string['do_you_want_to_delete_category_default'] = 'Voulez-vous supprimer l\'activité physique "{$a}" ?';
$string['edit_category_default'] = 'Modifier une activité physique';
$string['grouping_cannot_be_deleted_default'] = 'Le groupement d\'activités "{$a}" ne peut pas être supprimé, car il est rattaché aux activités physiques suivantes :';
$string['settings_activities_default'] = 'Activités physiques';
$string['categories_default'] = 'Activités sportives';
$string['categories_list_default'] = 'Liste des activités sportives';
$string['category_add_default'] = 'Ajouter une activité sportive';
$string['no_category_default'] = 'Aucune activité sportive';
$string['federation_default'] = 'FFSU';
$string['settings_activities_default'] = 'Activités physiques';

// 5. Personnalisation du footer.
$string['customize_footer'] = 'Personnaliser le pied de page';
$string['customize_footer_desc'] = '<div class="alert alert-info"><p>Vous pouvez activer un pied de page supplémentaire personnalisé sur la page d\'accueil.</p></div>';
$string['footer_active'] = 'Activer le pied de page personnalisé';
$string['add_footer_text'] = '';
$string['add_footer_logo_1'] = 'Personnaliser le pied de page';
