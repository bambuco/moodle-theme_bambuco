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
 * Create or edit subtheme.
 *
 * @package    theme_bambuco
 * @copyright  2025 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$id = optional_param('id', '', PARAM_ALPHANUMEXT);

require_login();

admin_externalpage_setup('theme_bambuco_subthemes', '', null, '', ['pagelayout' => 'report']);
$syscontext = context_system::instance();
$url = new moodle_url('/theme/bambuco/subthemes.php', []);

$PAGE->set_heading($SITE->fullname);

$subtheme = null;
if (!empty($id)) {
    $subtheme = \theme_bambuco\local\utils::get_subtheme($id);
}

$editform = new \theme_bambuco\form\subtheme(null, ['data' => $subtheme]);
if ($editform->is_cancelled()) {
    redirect($url);
} else if ($data = $editform->get_data()) {

    $customsettings = new stdClass();
    foreach (\theme_bambuco\local\utils::SUBTHEME_SETTINGS as $setting) {
        $key = 'setting_' . $setting;
        if (isset($data->$key)) {
            $customsettings->$setting = $data->$key;
        }
    }

    if (!$subtheme) {
        $subtheme = new stdClass();
        $subtheme->name = $data->name;
        $subtheme->idnumber = $data->idnumber;
        $subtheme->homeurl = $data->homeurl;
        $subtheme->customsettings = json_encode($customsettings);
        $id = $DB->insert_record('theme_bambuco_subthemes', $subtheme, true);
        $event = \theme_bambuco\event\subtheme_created::create([
            'objectid' => $id,
            'context' => $syscontext,
        ]);
    } else {
        $subtheme->name = $data->name;
        $subtheme->idnumber = $data->idnumber;
        $subtheme->homeurl = $data->homeurl;
        $subtheme->customsettings = json_encode($customsettings);
        $DB->update_record('theme_bambuco_subthemes', $subtheme);
        $event = \theme_bambuco\event\subtheme_updated::create([
            'objectid' => $subtheme->id,
            'context' => $syscontext,
        ]);
        unset($SESSION->theme_bambuco_settingupsubtheme);
    }

    $event->trigger();

    redirect($url, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('subthemes', 'theme_bambuco'));

$editform->display();

echo $OUTPUT->footer();
