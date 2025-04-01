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
 * Manage multitheme subthemes.
 *
 * @package    theme_bambuco
 * @copyright  2025 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$delete = optional_param('delete', '', PARAM_ALPHANUMEXT);
$confirm = optional_param('confirm', '', PARAM_ALPHANUM); // Md5 confirmation hash.

require_login();

admin_externalpage_setup('theme_bambuco_subthemes', '', null, '', ['pagelayout' => 'admin']);
$syscontext = context_system::instance();
$url = new moodle_url('/theme/bambuco/subthemes.php', []);

// Delete an knowlegde base item, after confirmation.
if ($delete && confirm_sesskey()) {

    $subtheme = \theme_bambuco\local\utils::get_subtheme($delete);
    if ($confirm != md5($delete)) {
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('subthemedelete', 'theme_bambuco'));
        $optionsyes = ['delete' => $delete, 'confirm' => md5($delete), 'sesskey' => sesskey()];
        echo $OUTPUT->confirm(get_string('deletecheck', '', "'{$subtheme->name}'"),
                                new moodle_url($url, $optionsyes), $url);
        echo $OUTPUT->footer();
        die;
    } else if (data_submitted()) {

        $DB->delete_records('theme_bambuco_subthemes', ['id' => $delete]);

        $event = \theme_bambuco\event\subtheme_deleted::create([
            'objectid' => $delete,
            'context' => $syscontext,
            'other' => ['idnumber' => $subtheme->idnumber],
        ]);
        $event->trigger();
        $strsubthemedeleted = get_string('subthemedeleted', 'theme_bambuco');
        unset($SESSION->theme_bambuco_settingupsubtheme);
        redirect($url, $strsubthemedeleted, null, \core\output\notification::NOTIFY_SUCCESS);
    }
}

$PAGE->set_url($url);
$PAGE->set_context($syscontext);

$PAGE->set_heading($SITE->fullname);
echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('subthemes', 'theme_bambuco'), 2);

$subthemes = \theme_bambuco\local\utils::get_subthemes();
$data = [
    'baseurl' => $CFG->wwwroot,
    'subthemes' => array_values($subthemes),
    'sesskey' => sesskey(),
];

echo $OUTPUT->render_from_template('theme_bambuco/local/subthemes', $data);

echo $OUTPUT->footer();
