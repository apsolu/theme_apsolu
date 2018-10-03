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

namespace theme_apsolu\output\core_user\myprofile;

use \core_user\output\myprofile as moodle;
use UniversiteRennes2\Apsolu\Payment;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/local/apsolu/classes/apsolu/payment.php');

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_bootstrap
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class renderer extends moodle\renderer {
    /**
     * Render the whole tree.
     *
     * @param tree $tree
     *
     * @return string
     */
    public function render_tree(moodle\tree $tree) {
        $return = \html_writer::start_tag('div', array('class' => 'profile_tree'));
        $categories = $tree->categories;
        foreach ($categories as $category) {
            $return .= $this->render($category);
        }
        $return .= \html_writer::end_tag('div');
        return $return;
    }

    /**
     * Render a category.
     *
     * @param category $category
     *
     * @return string
     */
    public function render_category(moodle\category $category) {
        global $CFG, $DB;

        $userid = \optional_param('id', null, PARAM_INT);
        $courseid = \optional_param('course', null, PARAM_INT);

        $classes = $category->classes;
        if (empty($classes)) {
            $return = \html_writer::start_tag('section', array('class' => 'node_category'));
        } else {
            $return = \html_writer::start_tag('section', array('class' => 'node_category ' . $classes));
        }
        $return .= \html_writer::tag('h3', $category->title);

        if ($category->name === 'contact') {
            if (isset($userid)) {
                $user = $DB->get_record('user', array('id' => $userid));
                if ($user) {
                    if (isset($courseid)) {
                        $context = \context_course::instance($courseid);
                        $canviewhiddenuserfields = \has_capability('moodle/course:enrolreview', $context);

                        $user->role = get_user_roles_in_course($userid, $courseid);
                    } else {
                        $context = \context_user::instance($userid);
                        $canviewhiddenuserfields = \has_capability('moodle/user:viewhiddendetails', $context);
                    }

                    if ($canviewhiddenuserfields) {
                        $parentcat = 'contact';
                        $place = null;
                        $url = null;
                        $picture = null;
                        $classes = 'contentnode';

                        // Classic fields.
                        $fields = array('auth', 'idnumber', 'institution', 'department', 'phone1', 'phone2', 'role');
                        foreach ($fields as $field) {
                            if (!empty($user->{$field})) {
                                $content = $user->{$field};

                                if ($field === 'auth') {
                                    $field = 'authentication';
                                    $content = get_string('pluginname', 'auth_'.$user->auth);
                                } else {
                                    $content = $user->{$field};
                                }

                                $title = get_string($field);

                                $node = new moodle\node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                                $category->add_node($node);
                            }
                        }

                        // Custom fields.
                        $customfields = profile_user_record($user->id);
                        $fields = array('apsoludoublecursus', 'apsolusesame', 'apsolucycle', 'apsoluufr', 'apsolusex', 'apsolubirthday', 'apsolufederationnumber', 'apsolumedicalcertificate', 'apsoluhighlevelathlete');
                        foreach ($fields as $field) {
                            if (isset($customfields->{$field})) {
                                if (in_array($field, array('apsoludoublecursus', 'apsolusesame', 'apsolumedicalcertificate', 'apsoluhighlevelathlete'), true)) {
                                    $attributes = array('disabled' => 1, 'readonly' => 1);
                                    if ($customfields->{$field}) {
                                        $content = \html_writer::checkbox($field, $customfields->{$field}, $checked = true, $label = '', $attributes);
                                    } else {
                                        $content = \html_writer::checkbox($field, $customfields->{$field}, $checked = false, $label = '', $attributes);
                                    }
                                } else {
                                    if (empty($customfields->{$field})) {
                                        continue;
                                    }
                                    $content = $customfields->{$field};
                                }

                                $title = get_string('fields_'.$field, 'local_apsolu');

                                $node = new moodle\node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                                $category->add_node($node);
                            }
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

                            $node = new moodle\node($parentcat, 'cards', get_string('cards', 'local_apsolu'), $place, $url, $content, $picture, $classes);
                            $category->add_node($node);
                        }
                    }
                }
            }

            // Ordre d'affichage des informations.
            $nodes = array (
                'editprofile' => '',
                'authentication' => '',
                'apsolusesame' => '',
                'apsoludoublecursus' => '',
                'apsoluhighlevelathlete' => '',
                'role' => '',
                'idnumber' => '',
                'email' => '',
                'phone1' => '',
                'phone2' => '',
                'apsolucycle' => '',
                'apsoluufr' => '',
                'department' => '',
                'institution' => '',
                'apsolusex' => '',
                'apsolubirthday' => '',
                'cards' => '',
                'apsolufederationnumber' => '',
                'apsolumedicalcertificate' => '',
            );

            // Ajoute les données.
            foreach ($category->nodes as $node) {
                if (isset($nodes[$node->name])) {
                    $nodes[$node->name] = $node;
                }
            }

            // Supprime les nodes vides.
            foreach ($nodes as $key => $node) {
                if ($node === '') {
                    unset($nodes[$key]);
                }
            }
        } else if ($category->name === 'coursedetails') {
            if ($userid) {
                if (isset($courseid)) {
                    $context = \context_course::instance($courseid);
                    $canviewhiddenuserfields = \has_capability('moodle/course:enrolreview', $context);
                } else {
                    $context = \context_user::instance($userid);
                    $canviewhiddenuserfields = \has_capability('moodle/user:viewhiddendetails', $context);
                }

                if ($canviewhiddenuserfields) {
                    // Get enrolments.
                    require_once(__DIR__.'/../../../enrol/select/lib.php');
                    require_once(__DIR__.'/../../../enrol/select/locallib.php');

                    $roles = role_fix_names($DB->get_records('role'));

                    $recordsets = \UniversiteRennes2\Apsolu\get_recordset_user_activity_enrolments($userid, $onlyactive = false);
                    $items = array();
                    foreach ($recordsets as $course) {
                        $enrolurl = new \moodle_url('/enrol/select/manage.php', array('enrolid' => $course->enrolid));
                        $courseurl = new \moodle_url('/user/view.php', array('id' => $userid, 'course' => $course->id));

                        $rolename = $roles[$course->roleid]->name;
                        $status = get_string(\enrol_select_plugin::$states[$course->status].'_list_abbr', 'enrol_select');

                        $items[] = '<li><a href="'.$courseurl.'">'.$course->fullname.'</a><br />'.
                            '<span class="course-profil-complementary-span">'.$course->enrolname.' : <a class="course-profil-complementary-a" href="'.$enrolurl.'">'.$rolename.' - '.$status.'</a></span></li>';
                    }

                    if (isset($items[0])) {
                        $parentcat = 'coursedetails';
                        $field = 'courses';
                        $place = null;
                        $url = null;
                        $picture = null;
                        $classes = '';//contentnode';
                        $title = get_string('courseprofiles');
                        $content = '<ul>'.implode('', $items).'</ul>';

                        $node = new moodle\node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                        $category->add_node($node);
                    }

                    // Get cohorts.
                    $sql = "SELECT c.*".
                        " FROM {cohort} c".
                        " JOIN {cohort_members} cm ON c.id = cm.cohortid AND cm.userid = :userid".
                        " ORDER BY c.name";
                    $cohorts = $DB->get_records_sql($sql, array('userid' => $userid));
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

                        $node = new moodle\node($parentcat, $field, $title, $place, $url, $content, $picture, $classes);
                        $category->add_node($node);
                    }
                }
            }

            $nodes = $category->nodes;
        } else {
            $nodes = $category->nodes;
        }

        if (empty($nodes)) {
            // No nodes, nothing to render.
            return '';
        }

        $return .= \html_writer::start_tag('ul');
        foreach ($nodes as $node) {
            $return .= $this->render($node);
        }
        $return .= \html_writer::end_tag('ul');
        $return .= \html_writer::end_tag('section');
        return $return;
    }

    public function render_node(moodle\node $node) {
        $return = '';
        if (is_object($node->url)) {
            $header = \html_writer::link($node->url, $node->title);
        } else {
            $header = $node->title;
        }
        $icon = $node->icon;
        if (!empty($icon)) {
            $header .= $this->render($icon);
        }
        $content = $node->content;
        $classes = $node->classes;
        if (!empty($content)) {
            // There is some content to display below this make this a header.
            $return = \html_writer::tag('dt', $header);
            $return .= \html_writer::tag('dd', $content);

            $return = \html_writer::tag('dl', $return);
            if ($classes) {
                $return = \html_writer::tag('li', $return, array('class' => 'contentnode ' . $classes));
            } else {
                $return = \html_writer::tag('li', $return, array('class' => 'contentnode'));
            }
        } else {
            $return = \html_writer::span($header);
            $return = \html_writer::tag('li', $return, array('class' => $classes));
        }

        return $return;
    }
}
