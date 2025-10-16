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
 * Settings to multitheme feature.
 *
 * @package    theme_bambuco
 * @copyright  2025 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_bambuco_multitheme', new lang_string('multitheme', 'theme_bambuco'));

if ($ADMIN->fulltree) {
    // Enable/disable multitheme.
    $name = 'theme_bambuco/multitheme';
    $title = new lang_string('multithemeenabled', 'theme_bambuco');
    $description = new lang_string('multithemeenabled_desc', 'theme_bambuco');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Get user profile fields.
    $userfields = [0 => get_string('none')];
    $customuserfields = $DB->get_records_menu('user_info_field', ['datatype' => 'menu'], 'name', 'id, name');

    foreach ($customuserfields as $k => $v) {
        $userfields[$k] = format_string($v, true);
    }

    $name = 'theme_bambuco/multithemeuserfield';
    $title = new lang_string('multithemeuserfield', 'theme_bambuco');
    $description = new lang_string('multithemeuserfield_desc', 'theme_bambuco');
    $setting = new admin_setting_configselect($name, $title, $description, 0, $userfields);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Get course custom fields.
    $fields = [0 => get_string('none')];

    $sql = "SELECT cf.id, cf.name " .
            " FROM {customfield_field} cf " .
            " INNER JOIN {customfield_category} cc ON cc.id = cf.categoryid AND cc.component = 'core_course'" .
            " WHERE type = 'select' " .
            " ORDER BY cf.name";
    $customfields = $DB->get_records_sql($sql);

    foreach ($customfields as $k => $v) {
        $fields[$k] = format_string($v->name, true);
    }

    $name = 'theme_bambuco/multithemecoursefield';
    $title = new lang_string('multithemecoursefield', 'theme_bambuco');
    $description = new lang_string('multithemecoursefield_desc', 'theme_bambuco');
    $setting = new admin_setting_configselect($name, $title, $description, 0, $fields);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
}

$settings->add('theme_bambuco', $page);
