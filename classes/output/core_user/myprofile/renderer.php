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
 * myprofile renderer.
 *
 * @package   theme_apsolu
 * @copyright 2019 Université Rennes 2 {@link https://www.univ-rennes2.fr}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_apsolu\output\core_user\myprofile;

use core_user\output\myprofile\category;
use core_user\output\myprofile\node;

use stdClass;
use context_course;
use context_user;
use moodle_url;
use html_writer;
use enrol_select_plugin;
use UniversiteRennes2\Apsolu\Payment;
use local_apsolu\core\attendance as Attendance;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/local/apsolu/classes/apsolu/payment.php');

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package   theme_apsolu
 * @copyright 2019 Université Rennes 2 {@link https://www.univ-rennes2.fr}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \core_user\output\myprofile\renderer {
    /**
     * Render a category.
     *
     * @param category $category
     *
     * @return string
     */
    public function render_category(category $category) {
        global $CFG, $DB, $USER;

        $userid = optional_param('id', null, PARAM_INT);
        $courseid = optional_param('course', null, PARAM_INT);

        if ($userid === null) {
            $userid = $USER->id;
        }

        $classes = $category->classes;
        if (empty($classes)) {
            $return = html_writer::start_tag('section', ['class' => 'node_category']);
        } else {
            $return = html_writer::start_tag('section', ['class' => 'node_category ' . $classes]);
        }
        $return .= html_writer::tag('h3', $category->title);

        if (in_array($category->name, ['privacyandpolicies', 'mobile']) === true) {
            return '';
        }

        if (isset($courseid)) {
            // Dans un cours, les personnes ayant la capacité de voir les inscriptions peuvent voir les profils.
            $context = context_course::instance($courseid);
            $canviewuserprofile = has_capability('moodle/course:enrolreview', $context);
        } else {
            // Dans tout autre contexte, seuls les utilisateurs pouvant voir les détails cachés peuvent consulter les profils (ex:
            // les gestionnaires).
            $context = context_user::instance($userid);
            $canviewuserprofile = has_capability('moodle/user:viewhiddendetails', $context);
        }

        // Capacité dans APSOLU permettant de cacher certains champs aux enseignants.
        $canviewhiddenuserfields = has_capability('local/apsolu:viewuserhiddendetails', $context);

        // Liste des champs à masquer dans APSOLU.
        $userhiddenfields = get_config('local_apsolu', 'userhiddenfields');

        if (empty($userhiddenfields) === true) {
            $userhiddenfields = [];
        } else {
            $userhiddenfields = explode(',', $userhiddenfields);
        }

        if ($category->name === 'contact') {
            $othercustomfields = [];

            $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
            if ($canviewuserprofile) {
                $parentcat = 'contact';
                $place = null;
                $url = null;
                $picture = null;
                $classes = 'contentnode';

                // Ajoute des champs qui ne commencent pas par apsolu.
                $usercustomfields = profile_get_user_fields_with_data($user->id);
                foreach ($usercustomfields as $field) {
                    if (!$field->is_visible()) {
                        continue;
                    }

                    if (in_array($field, $userhiddenfields, $strict = true) && !$canviewhiddenuserfields) {
                        continue;
                    }

                    if ($field->is_user_object_data() && substr($field->field->shortname, 0, 6) !== 'apsolu') {
                        $title = $field->field->name;
                        $shortname = $field->field->shortname;
                        $data = $field->data;
                        if ($field->field->datatype === 'checkbox') {
                            if ($data) {
                                $checked = true;
                            } else {
                                $checked = false;
                            }
                            $attributes = ['disabled' => 1, 'readonly' => 1];
                            $content = html_writer::checkbox($shortname, $data, $checked, $label = '', $attributes);
                        } else {
                             $content = $data;
                        }
                        if (!empty($content)) {
                            $node = new node($parentcat, $shortname, $title, $place, $url, $content, $picture, $classes);
                            $category->add_node($node);
                            $othercustomfields[$node->name] = $node;
                        }
                    }
                }

                // Classic fields.
                $fields = ['auth', 'idnumber', 'institution', 'department', 'address', 'phone1', 'phone2', 'role'];
                foreach ($fields as $field) {
                    if (empty($user->{$field})) {
                        continue;
                    }

                    if (in_array($field, $userhiddenfields, $strict = true) && !$canviewhiddenuserfields) {
                        continue;
                    }

                    $content = $user->{$field};

                    if ($field === 'auth') {
                        $field = 'authentication';
                        $content = get_string('pluginname', 'auth_'.$user->auth);
                    } else {
                        $content = $user->{$field};
                    }

                    $title = get_string($field);

                    $node = new node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                    $category->add_node($node);
                }

                // Custom fields.
                $customfields = profile_user_record($user->id);
                $fields = ['apsoludoublecursus', 'apsolusesame', 'apsoluusertype', 'apsolucycle', 'apsolupostalcode',
                    'apsoluufr', 'apsolusex', 'apsolubirthday', 'apsoluhighlevelathlete', ];
                $checkboxfields = ['apsoludoublecursus', 'apsolusesame', 'apsoluhighlevelathlete'];
                foreach ($fields as $field) {
                    if (!isset($customfields->{$field})) {
                        continue;
                    }

                    if (in_array($field, $userhiddenfields, $strict = true) && !$canviewhiddenuserfields) {
                        continue;
                    }

                    $value = $customfields->{$field};
                    if (in_array($field, $checkboxfields, $strict = true)) {
                        $label = '';
                        $attributes = ['disabled' => 1, 'readonly' => 1];
                        if ($customfields->{$field}) {
                            $content = html_writer::checkbox($field, $value, $checked = true, $label, $attributes);
                        } else {
                            $content = html_writer::checkbox($field, $value, $checked = false, $label, $attributes);
                        }
                    } else {
                        if (empty($value)) {
                            continue;
                        }
                        $content = $value;
                    }

                    $title = get_string('fields_'.$field, 'local_apsolu');

                    $node = new node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                    $category->add_node($node);
                }

                // Numéro FFSU.
                $adhesion = $DB->get_record('apsolu_federation_adhesions', ['userid' => $userid]);
                if ($adhesion !== false && empty($adhesion->federationnumber) === false) {
                    $field = 'apsolufederationnumber';
                    $title = get_string('federation_number', 'local_apsolu');
                    $content = $adhesion->federationnumber;
                    $node = new node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                    $category->add_node($node);
                }

                // Cards.
                $cards = Payment::get_user_cards($userid);
                $paymentsimages = Payment::get_statuses_images();
                if (count($cards) > 0) {
                    $content = '<ul>';
                    foreach ($cards as $card) {
                        $card->status = Payment::get_user_card_status($card, $userid);

                        $content .= '<li>'.$paymentsimages[$card->status]->image.' '.$card->name.'</li>';
                    }
                    $content .= '</ul>';

                    $label = get_string('cards', 'local_apsolu');
                    $node = new node($parentcat, 'cards', $label, $place, $url, $content, $picture, $classes);
                    $category->add_node($node);
                }
            }

            // Ordre d'affichage des informations.
            $nodes = [
                'editprofile' => '',
                'authentication' => '',
                'apsoluusertype' => '',
                'apsolusesame' => '',
                'apsoludoublecursus' => '',
                'apsoluhighlevelathlete' => '',
                'apsolufederationnumber' => '',
                'role' => '',
                'idnumber' => '',
                'email' => '',
                'address' => '',
                'apsolupostalcode' => '',
                'city' => '',
                'phone1' => '',
                'phone2' => '',
                'apsolucycle' => '',
                'apsoluufr' => '',
                'department' => '',
                'institution' => '',
                'apsolusex' => '',
                'apsolubirthday' => '',
                'cards' => '',
            ];

            // Ajoute les données.
            foreach ($category->nodes as $node) {
                if (isset($nodes[$node->name])) {
                    $nodes[$node->name] = $node;
                }
            }

            $nodes = $nodes + $othercustomfields;

            // Supprime les nodes vides.
            foreach ($nodes as $key => $node) {
                if ($node === '') {
                    unset($nodes[$key]);
                }
            }
        } else if ($category->name === 'coursedetails' && $canviewuserprofile) {
            // Get enrolments.
            require_once($CFG->dirroot.'/enrol/select/lib.php');
            require_once($CFG->dirroot.'/enrol/select/locallib.php');

            $roles = role_fix_names($DB->get_records('role'));
            $presences = Attendance::getUserPresences($userid);

            $recordset = enrol_select_get_recordset_user_activity_enrolments($userid, $onlyactive = false);
            $items = [];
            foreach ($recordset as $course) {
                $enrolurl = new moodle_url('/enrol/select/manage.php', ['enrolid' => $course->enrolid]);
                $courseurl = new moodle_url('/user/view.php', ['id' => $userid, 'course' => $course->id]);

                $rolename = $roles[$course->roleid]->name;
                $status = get_string(enrol_select_plugin::$states[$course->status].'_list_abbr', 'enrol_select');

                if (isset($presences[$course->enrolid]) === false) {
                    $presences[$course->enrolid] = new stdClass();
                    $presences[$course->enrolid]->total = 0;
                }

                $presence = $presences[$course->enrolid]->total.' présences';
                if ($presences[$course->enrolid]->total < 2) {
                    $presence = $presences[$course->enrolid]->total.' présence';
                }

                $items[] = '<li>'.
                    '<p class="my-0"><a href="'.$courseurl.'">'.$course->fullname.'</a></p>'.
                    '<dl>'.
                        '<dt class="font-weight-normal ml-3">'.$course->enrolname.'</dt>'.
                        '<dd class="ml-5">'.
                            '<ul class="list-inline">'.
                                '<li class="list-inline-item">'.
                                    '<a class="text-danger" href="'.$enrolurl.'">'.$rolename.' - '.$status.'</a>'.
                                '</li>'.
                                '<li class="list-inline-item">'.
                                    '<span class="small">('.$presence.')</span>'.
                                '</li>'.
                            '</ul>'.
                        '</dd>'.
                    '</dl>'.
                    '</li>';
            }
            $recordset->close();

            if (isset($items[0])) {
                $parentcat = 'coursedetails';
                $field = 'courses';
                $place = null;
                $url = null;
                $picture = null;
                $classes = '';
                $title = get_string('courseprofiles');
                $content = '<ul>'.implode('', $items).'</ul>';

                $node = new node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                $category->add_node($node);
            }

            // Get cohorts.
            $sql = "SELECT c.*".
                " FROM {cohort} c".
                " JOIN {cohort_members} cm ON c.id = cm.cohortid AND cm.userid = :userid".
                " ORDER BY c.name";
            $cohorts = $DB->get_records_sql($sql, ['userid' => $userid]);
            if (count($cohorts) > 0) {
                $parentcat = 'coursedetails';
                $field = 'cohorts';
                $place = null;
                $url = null;
                $picture = null;
                $classes = 'contentnode';
                $title = get_string('cohorts', 'cohort');

                $content = '<ul>';
                foreach ($cohorts as $cohort) {
                    $content .= '<li>'.$cohort->name.'</li>';
                }
                $content .= '</ul>';

                $node = new node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                $category->add_node($node);
            }

            $nodes = $category->nodes;
        } else {
            $nodes = $category->nodes;
        }

        if (empty($nodes)) {
            // No nodes, nothing to render.
            return '';
        }

        $return .= html_writer::start_tag('ul');
        foreach ($nodes as $node) {
            $return .= $this->render($node);
        }
        $return .= html_writer::end_tag('ul');
        $return .= html_writer::end_tag('section');

        return $return;
    }
}
