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
 * @copyright 2023 David Herney - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_bambuco_login', new lang_string('loginsettings', 'theme_bambuco'));

if ($ADMIN->fulltree) {
    if (PHP_VERSION_ID > 80100) {
        // Include ALTCHA in login.
        $name = 'theme_bambuco/usealtcha';
        $title = new lang_string('usealtcha', 'theme_bambuco');
        $description = new lang_string('usealtcha_desc', 'theme_bambuco');
        $setting = new admin_setting_configcheckbox($name, $title, $description, '0');
        $page->add($setting);

        $name = 'theme_bambuco/altchalevel';
        $title = new lang_string('altchalevel', 'theme_bambuco');
        $description = new lang_string('altchalevel_desc', 'theme_bambuco');
        $options = [
            '100000' => new lang_string('altchalevel_1', 'theme_bambuco'),
            '200000' => new lang_string('altchalevel_2', 'theme_bambuco'),
            '500000' => new lang_string('altchalevel_5', 'theme_bambuco'),
            '1000000' => new lang_string('altchalevel_10', 'theme_bambuco'),
        ];
        $setting = new admin_setting_configselect($name, $title, $description, '200000', $options);
        $page->add($setting);

        $name = 'theme_bambuco/altchavalidtime';
        $title = new lang_string('altchavalidtime', 'theme_bambuco');
        $description = new lang_string('altchavalidtime_desc', 'theme_bambuco');
        $options = [
            '10S' => new lang_string('altchavalidtime_10s', 'theme_bambuco'),
            '20S' => new lang_string('altchavalidtime_20s', 'theme_bambuco'),
            '40S' => new lang_string('altchavalidtime_40s', 'theme_bambuco'),
            '1M' => new lang_string('altchavalidtime_1m', 'theme_bambuco'),
            '2M' => new lang_string('altchavalidtime_2m', 'theme_bambuco'),
            '5M' => new lang_string('altchavalidtime_5m', 'theme_bambuco'),
        ];
        $setting = new admin_setting_configselect($name, $title, $description, '20S', $options);
        $page->add($setting);
    }

    // Include signup link in usermenu bar.
    $name = 'theme_bambuco/signuplink';
    $title = new lang_string('signuplink', 'theme_bambuco');
    $description = new lang_string('signuplink_desc', 'theme_bambuco');
    $setting = new admin_setting_configcheckbox($name, $title, $description, '0');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Include signup with externals.
    $name = 'theme_bambuco/signupidentityproviders';
    $title = new lang_string('signupidentityproviders', 'theme_bambuco');
    $description = new lang_string('signupidentityproviders_desc', 'theme_bambuco');
    $setting = new admin_setting_configcheckbox($name, $title, $description, '0');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

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
        'toexternal' => new lang_string('loginformlayout_toexternal', 'theme_bambuco'),
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
