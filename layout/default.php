<?php
// This file is part of The Bootstrap Moodle theme
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
 * Layout for APSOLU theme.
 *
 * @package    theme_apsolu
 * @copyright  2016 Université Rennes 2 <dsi-contact@univ-rennes2.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

$knownregionpre = $PAGE->blocks->is_known_region('side-pre');
$knownregionpost = $PAGE->blocks->is_known_region('side-post');

$regions = bootstrap_grid($hassidepre, $hassidepost);
$PAGE->set_popup_notification_allowed(false);

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimal-ui">
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header pull-left">
            <a class="navbar-brand" href="<?php echo $CFG->wwwroot;?>"><?php echo $SITE->shortname; ?></a>
        </div>
        <div class="navbar-header pull-right">
            <?php echo $OUTPUT->user_menu(); ?>
        </div>
    </div>
</nav>
<header class="moodleheader">
    <div class="container-fluid">
    <a href="<?php echo $CFG->wwwroot ?>" class="logo"></a>
    <?php echo $OUTPUT->page_heading(); ?>
    </div>
</header>

<div id="page" class="container-fluid">
    <header id="page-header" class="clearfix">
        <div id="page-navbar" class="clearfix">
            <nav class="breadcrumb-nav" role="navigation" aria-label="breadcrumb"><?php echo $OUTPUT->navbar(); ?></nav>
            <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
        </div>

        <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
        </div>
    </header>

    <div id="page-content" class="row">
        <div id="region-main" class="<?php echo $regions['content']; ?>">
            <?php
            echo $OUTPUT->course_content_header();

            echo $OUTPUT->main_content();
            echo $OUTPUT->course_content_footer();
            ?>
        </div>

        <?php
        if ($knownregionpre) {
            echo $OUTPUT->blocks('side-pre', $regions['pre']);
        }?>
        <?php
        if ($knownregionpost) {
            echo $OUTPUT->blocks('side-post', $regions['post']);
        }?>
    </div>
</div>

<footer id="page-footer" class="row">
    <div class="col-md-8 page-footer-flex-center">
        <a class="col-xs-2 text-center logosiuaps" href="http://www.siuaps.univ-rennes1.fr">
            <img src="<?php echo $OUTPUT->pix_url('logo_siuaps_white_with_text', 'theme_apsolu');?>" alt="S.I.U.A.P.S. de Rennes" width="82px" height="82px" />
        </a>
        <a class="col-xs-2 text-center logour1" href="https://www.univ-rennes1.fr">
            <img src="<?php echo $OUTPUT->pix_url('logo_ur1_white', 'theme_apsolu');?>" alt="Université de Rennes 1" width="163px" height="60px" />
        </a>
        <a class="col-xs-2 text-center logour2" href="https://www.univ-rennes2.fr">
            <img src="<?php echo $OUTPUT->pix_url('logo_ur2_white', 'theme_apsolu');?>" alt="Université Rennes 2" width="82px" height="83px" />
        </a>
    </div>
    <div class="col-md-4 page-footer-flex-bottom">
        <?php echo $OUTPUT->standard_end_of_body_html() ?>
    </div>
</footer>
</body>
</html>
