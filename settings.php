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
 * Theme global settings.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$bbst = optional_param('bbst', -1, PARAM_INT);
if ($bbst >= 0) {
    theme_bambuco\local\utils::set_settingup_subtheme($bbst);
}

$subtheme = theme_bambuco\local\utils::settingup_subtheme();

$settings = new admin_category('theme_bambuco', get_string('configtitle', 'theme_bambuco'));

include(dirname(__FILE__) . '/settings/general.php');
include(dirname(__FILE__) . '/settings/advanced.php');
include(dirname(__FILE__) . '/settings/skin.php');

// Not for subthemes.
if (!$subtheme) {
    include(dirname(__FILE__) . '/settings/login.php');
    include(dirname(__FILE__) . '/settings/courses.php');
    include(dirname(__FILE__) . '/settings/multitheme.php');
}

// Manage the subthemes.
$externalpage = new admin_externalpage('theme_bambuco_subthemes', new lang_string('subthemes', 'theme_bambuco'),
new moodle_url("/theme/bambuco/subthemes.php"), 'moodle/site:config');
$settings->add('theme_bambuco', $externalpage);
