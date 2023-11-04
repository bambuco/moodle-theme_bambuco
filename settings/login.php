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
 * Theme login page settings.
 *
 * @package   theme_bambuco
 * @copyright 2023 David Herney Bernal - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_bambuco_login', new lang_string('loginsettings', 'theme_bambuco'));

if ($ADMIN->fulltree) {

    // Login Background image setting.
    $name = 'theme_bambuco/loginbackgroundimage';
    $title = new lang_string('loginbackgroundimage', 'theme_bambuco');
    $description = new lang_string('loginbackgroundimage_desc', 'theme_bambuco');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Layout type.
    $options = [
        'default' => new lang_string('loginformlayout_default', 'theme_bambuco'),
        'toexternal' => new lang_string('loginformlayout_toexternal', 'theme_bambuco')
    ];

    $name = 'theme_bambuco/loginformlayout';
    $title = new lang_string('loginformlayout', 'theme_bambuco');
    $description = new lang_string('loginformlayout_desc', 'theme_bambuco');
    $setting = new admin_setting_configselect($name, $title, $description, 'default', $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_bambuco/loginmorecontent';
    $title = new lang_string('loginmorecontent', 'theme_bambuco');
    $description = new lang_string('loginmorecontent_desc', 'theme_bambuco');
    $setting = new admin_setting_confightmleditor($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

}
$settings->add('theme_bambuco', $page);
