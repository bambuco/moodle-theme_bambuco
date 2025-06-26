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
 * Theme skin settings.
 *
 * @package   theme_bambuco
 * @copyright 2023 David Herney Bernal - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_bambuco\local\utils;

defined('MOODLE_INTERNAL') || die();

// Advanced settings.
$page = new admin_settingpage('theme_bambuco_skin', get_string('skinsettings', 'theme_bambuco'));

if ($ADMIN->fulltree) {

    // Manually checked skins that are not compatible with Moodle.
    $notcompatible = ['morph'];
    $skins = [];

    $path = $CFG->dirroot . '/theme/bambuco/skin/bootswatch/dist/';

    $filesinpath = scandir($path);

    if (!is_array($filesinpath)) {
        $filesinpath = [];
    }

    $files = array_diff($filesinpath, ['..', '.']);

    foreach ($files as $file) {

        if (in_array($file, $notcompatible)) {
            continue;
        }

        if (is_dir($path . $file)) {
            $skins[$file] = $file;
        }
    }

    if (!empty($skins)) {
        $subtheme = utils::settingup_subtheme();
        $subthemekey = empty($subtheme) ? '' : '_' . $subtheme->id;

        $skins = ['' => get_string('none')] + $skins;

        if (utils::iscustomizable_subtheme('skin', $subtheme)) {
            // Skin to customize the appearance.
            $setting = new admin_setting_configselect('theme_bambuco/skin' . $subthemekey,
                get_string('skin', 'theme_bambuco'), get_string('skin_desc', 'theme_bambuco'), '', $skins);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
        }

    } else {
        $page->add(new admin_setting_heading('theme_bambuco_skin',
            get_string('skins_none', 'theme_bambuco'), ''));
    }

}

$settings->add('theme_bambuco', $page);
