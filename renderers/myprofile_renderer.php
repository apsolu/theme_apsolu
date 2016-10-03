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

defined('MOODLE_INTERNAL') || die();

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
        global $DB;

        $userid = \optional_param('id', null, PARAM_INT);
        $courseid = \optional_param('course', null, PARAM_INT);

        $classes = $category->classes;
        if (empty($classes)) {
            $return = \html_writer::start_tag('section', array('class' => 'node_category'));
        } else {
            $return = \html_writer::start_tag('section', array('class' => 'node_category ' . $classes));
        }
        $return .= \html_writer::tag('h3', $category->title);

        var_dump($category->name);
        if ($category->name === 'contact') {
            if (isset($userid)) {
                $user = $DB->get_record('user', array('id' => $userid));
                if ($user) {
                    if (isset($courseid)) {
                        $context = \context_course::instance($courseid);
                        $canviewhiddenuserfields = \has_capability('moodle/course:viewhiddenuserfields', $context);
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

                        $fields = array('auth', 'idnumber', 'institution', 'department', 'phone1', 'phone2');
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
                    }
                }
            }

            // Ordre d'affichage des informations.
            $nodes = array (
                'editprofile' => '',
                'authentication' => '',
                'idnumber' => '',
                'email' => '',
                'phone1' => '',
                'phone2' => '',
                'custom_field_lmd' => '',
                'custom_field_ufr' => '',
                'department' => '',
                'institution' => '',
                'custom_field_sex' => '',
                'custom_field_birthday' => '',
                'custom_field_cardpaid' => '',
                'custom_field_muscupaid' => '',
                'custom_field_federationpaid' => '',
                'custom_field_validsesame' => '',
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
