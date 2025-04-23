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
 * Theme config.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$THEME->name = 'bambuco';
$THEME->sheets = [];
$THEME->editor_sheets = ['tinyeditor'];
$THEME->editor_scss = ['editor'];
$THEME->usefallback = false;
$THEME->scss = function($theme) {
    return theme_bambuco_get_main_scss_content($theme);
};

$THEME->layouts = [
    // Standard layout with blocks.
    'standard' => [
        'file' => 'drawers.php',
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'below', 'bottom'],
        'defaultregion' => 'side-pre',
    ],
    // Main course page.
    'course' => [
        'file' => 'drawers.php',
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'below', 'bottom'],
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    'coursecategory' => [
        'file' => 'drawers.php',
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'below', 'bottom'],
        'defaultregion' => 'side-pre',
    ],
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    // side-pre must be the first because it is the one used as a fake block position in questionnaires.
    'incourse' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre', 'above', 'top', 'intocontent', 'below', 'bottom'],
        'defaultregion' => 'side-pre',
    ],
    // The site home page.
    'frontpage' => [
        'file' => 'frontpage.php',
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'bottom'],
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true],
    ],
    // Server administration scripts.
    'admin' => [
        'file' => 'drawers.php',
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'below', 'bottom'],
        'defaultregion' => 'side-pre',
    ],
    // My courses page.
    'mycourses' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'below', 'bottom'],
        'options' => ['nonavbar' => true],
        'defaultregion' => 'side-pre',
    ],
    // My dashboard page.
    'mydashboard' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'below', 'bottom'],
        'options' => ['nonavbar' => true, 'langmenu' => true],
        'defaultregion' => 'side-pre',
    ],
    // My public page.
    'mypublic' => [
        'file' => 'drawers.php',
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'below', 'bottom'],
        'defaultregion' => 'side-pre',
    ],
    'login' => [
        'file' => 'login.php',
        'regions' => [],
        'options' => ['langmenu' => true],
    ],
    // The pagelayout used for reports.
    'report' => [
        'file' => 'drawers.php',
        'regions' => ['above', 'top', 'intocontent', 'side-pre', 'below', 'bottom'],
        'defaultregion' => 'side-pre',
    ],
];

$THEME->parents = ['boost'];
$THEME->enable_dock = false;
$THEME->extrascsscallback = 'theme_bambuco_get_extra_scss';
$THEME->prescsscallback = 'theme_bambuco_get_pre_scss';

// Only used if SCSS fail: outputlib.php get_css_content function.
$THEME->precompiledcsscallback = 'theme_bambuco_get_precompiled_css';

$THEME->csspostprocess = 'theme_bambuco_css_postprocess';
$THEME->yuicssmodules = [];
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
// By default, all boost theme do not need their titles displayed.
$THEME->activityheaderconfig = [
    'notitle' => true
];
