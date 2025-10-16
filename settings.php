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

use tool_customlang\local\mlang\langstring;

defined('MOODLE_INTERNAL') || die();

$bbst = optional_param('bbst', -1, PARAM_INT);
if ($bbst >= 0) {
    theme_bambuco\local\utils::set_settingup_subtheme($bbst);
}

$subtheme = theme_bambuco\local\utils::settingup_subtheme();

// Show the settings page in installation and when exist any setting.
// If not, the settings page will not be shown in the admin tree.
$any = get_config('theme_bambuco', 'preset');
if ($ADMIN->fulltree || !empty($any)) {
    $settings = new admin_category('theme_bambuco', new lang_string('configtitle', 'theme_bambuco'));

    require_once(dirname(__FILE__) . '/settings/general.php');
    require_once(dirname(__FILE__) . '/settings/advanced.php');
    require_once(dirname(__FILE__) . '/settings/skin.php');

    // Not for subthemes.
    if (!$subtheme) {
        require_once(dirname(__FILE__) . '/settings/login.php');
        require_once(dirname(__FILE__) . '/settings/courses.php');
        require_once(dirname(__FILE__) . '/settings/multitheme.php');
    }

    // Manage the subthemes.
    $externalpage = new admin_externalpage('theme_bambuco_subthemes', new lang_string('subthemes', 'theme_bambuco'),
    new moodle_url("/theme/bambuco/subthemes.php"), 'moodle/site:config');
    $settings->add('theme_bambuco', $externalpage);

    if ($ADMIN->fulltree && !$subtheme) {
        $settingsfull = new admin_settingpage('themesettingbambuco', new lang_string('configtitle', 'theme_bambuco'));
        $url = new moodle_url('/');
        $setting = new admin_setting_heading('theme_bambuco/settingsfulldescription',
            new lang_string('choosereadme', 'theme_bambuco'),
            new lang_string('settingsfulldescription', 'theme_bambuco', (string)$url));
        $settingsfull->add($setting);
        $settings->add('theme_bambuco', $settingsfull);
    }
}
