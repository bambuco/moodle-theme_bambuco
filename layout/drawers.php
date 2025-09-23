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
 * A drawer based layout for the BambuCo theme.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

$inpopup = optional_param('inpopup', 0, PARAM_BOOL);

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    $blockdraweropen = true;
}

$sitename = format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]);
if ($inpopup) {
    $extraclasses = ['inpopup'];

    $templatecontext = [
        'sitename' => $sitename,
        'output' => $OUTPUT,
        'bodyattributes' => $OUTPUT->body_attributes($extraclasses),
    ];
    echo $OUTPUT->render_from_template('theme_boost/columns1', $templatecontext);

} else {

    $extraclasses = ['uses-drawers'];

    if ($courseindexopen) {
        $extraclasses[] = 'drawer-open-index';
    }

    $blockshtml = $OUTPUT->blocks('side-pre');
    $hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
    if (!$hasblocks) {
        $blockdraweropen = false;
    }

    $blocksabovehtml = $OUTPUT->blocks('above');
    $hasblocksabove = strpos($blocksabovehtml, 'data-block=') !== false;

    $blockstophtml = $OUTPUT->blocks('top');
    $hasblockstop = strpos($blockstophtml, 'data-block=') !== false;

    $blocksbottomhtml = $OUTPUT->blocks('bottom');
    $hasblocksbottom = strpos($blocksbottomhtml, 'data-block=') !== false;

    $blockscontenthtml = $OUTPUT->blocks('intocontent');
    $hasblockscontent = strpos($blockscontenthtml, 'data-block=') !== false;

    $blocksbelowhtml = $OUTPUT->blocks('below');
    $hasblocksbelow = strpos($blocksbelowhtml, 'data-block=') !== false;

    $courseindex = core_course_drawer();
    if (!$courseindex) {
        $courseindexopen = false;
    }

    // Course header customization.
    $config = get_config('theme_bambuco');

    if ($config->coursesheader != 'none') {
        $inpage = \theme_bambuco\local\utils::use_custom_header();

        if ($inpage) {
            $extraclasses[] = 'courseheader-custom';
            $extraclasses[] = 'course-header-' . $config->coursesheader;

            if (!empty($config->coursemenu)) {
                $extraclasses[] = 'course-header-withmenu';
            }

            if (!empty($config->courseheaderlayout) && $config->courseheaderlayout == 'fullwidth') {
                $extraclasses[] = 'course-header-fullwidth';
            }
        }
    }

    // End Course header customization.

    $bodyattributes = $OUTPUT->body_attributes($extraclasses);
    $forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

    $secondarynavigation = false;
    $overflow = '';
    if ($PAGE->has_secondary_navigation()) {
        $tablistnav = $PAGE->has_tablist_secondary_navigation();
        $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
        $secondarynavigation = $moremenu->export_for_template($OUTPUT);
        $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
        if (!is_null($overflowdata)) {
            $overflow = $overflowdata->export_for_template($OUTPUT);
        }
    }

    $primary = new \theme_bambuco\navigation\primary($PAGE);
    $renderer = $PAGE->get_renderer('core');
    $primarymenu = $primary->export_for_template($renderer);
    $buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
    // If the settings menu will be included in the header then don't add it here.
    $regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

    $header = $PAGE->activityheader;
    $headercontent = $header->export_for_template($renderer);

    $templatecontext = [
        'sitename' => $sitename,
        'output' => $OUTPUT,
        'sidepreblocks' => $blockshtml,
        'hasblocks' => $hasblocks,
        'blocksabove' => $blocksabovehtml,
        'hasblocksabove' => $hasblocksabove,
        'blockstop' => $blockstophtml,
        'hasblockstop' => $hasblockstop,
        'blocksbottom' => $blocksbottomhtml,
        'hasblocksbottom' => $hasblocksbottom,
        'blockscontent' => $blockscontenthtml,
        'hasblockscontent' => $hasblockscontent,
        'blocksbelow' => $blocksbelowhtml,
        'hasblocksbelow' => $hasblocksbelow,
        'bodyattributes' => $bodyattributes,
        'courseindexopen' => $courseindexopen,
        'blockdraweropen' => $blockdraweropen,
        'courseindex' => $courseindex,
        'primarymoremenu' => $primarymenu['moremenu'],
        'secondarymoremenu' => $secondarynavigation ?: false,
        'mobileprimarynav' => $primarymenu['mobileprimarynav'],
        'usermenu' => $primarymenu['user'],
        'langmenu' => $primarymenu['lang'],
        'forceblockdraweropen' => $forceblockdraweropen,
        'regionmainsettingsmenu' => $regionmainsettingsmenu,
        'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
        'overflow' => $overflow,
        'headercontent' => $headercontent,
        'addblockbutton' => $addblockbutton,
        'coursefooter' => \theme_bambuco\local\utils::get_coursefooter($PAGE->course),
        'courseheaderover' => $config->coursesheaderposition == 'over',
        'courseheaderintop' => $config->coursesheaderposition == 'top',
        'courseheaderincontent' => $config->coursesheaderposition == 'content',
    ];

    echo $OUTPUT->render_from_template('theme_bambuco/drawers', $templatecontext);
}
