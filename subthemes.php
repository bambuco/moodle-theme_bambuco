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
$clearsubthemecache = optional_param('clearsubthemecache', 0, PARAM_BOOL);
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
        echo $OUTPUT->confirm(
            get_string('deletecheck', '', "'{$subtheme->name}'"),
            new moodle_url($url, $optionsyes),
            $url
        );
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

// Clear only subthemes-related caches, after confirmation.
if ($clearsubthemecache && confirm_sesskey()) {
    if ($confirm != md5('clearsubthemecache')) {
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('subthemesclearcache', 'theme_bambuco'));
        $optionsyes = [
            'clearsubthemecache' => 1,
            'confirm' => md5('clearsubthemecache'),
            'sesskey' => sesskey(),
        ];
        echo $OUTPUT->confirm(
            get_string('subthemesclearcacheconfirm', 'theme_bambuco'),
            new moodle_url($url, $optionsyes),
            $url
        );
        echo $OUTPUT->footer();
        die;
    } else if (data_submitted()) {
        // Purge subtheme postprocessed CSS cache definition.
        $cache = cache::make('theme_bambuco', 'postprocessedcss');
        $cache->purge();

        // Delete subtheme CSS files from theme localcache across all revisions.
        $cssdirs = glob("{$CFG->localcachedir}/theme/*/bambuco/css", GLOB_ONLYDIR);
        foreach ($cssdirs as $cssdir) {
            $subthemecssfiles = glob("{$cssdir}/all*_*.css");
            foreach ($subthemecssfiles as $subthemecssfile) {
                if (preg_match('/^(?:all|all-rtl)(?:_[0-9]+)?(?:-nosvg)?_[0-9]+\\.css$/', basename($subthemecssfile))) {
                    @unlink($subthemecssfile);
                }
            }
        }

        // Delete subtheme fallback CSS files from temp cache.
        $tempcssdir = "{$CFG->tempdir}/theme/bambuco";
        if (is_dir($tempcssdir)) {
            $tempsubthemecssfiles = glob("{$tempcssdir}/all*_*.css");
            foreach ($tempsubthemecssfiles as $tempsubthemecssfile) {
                if (preg_match('/^(?:all|all-rtl)(?:_[0-9]+)?(?:-nosvg)?_[0-9]+\\.css$/', basename($tempsubthemecssfile))) {
                    @unlink($tempsubthemecssfile);
                }
            }
        }

        redirect($url, get_string('subthemescachecleared', 'theme_bambuco'), null, \core\output\notification::NOTIFY_SUCCESS);
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
    'clearsubthemecacheurl' => (new moodle_url($url, ['clearsubthemecache' => 1, 'sesskey' => sesskey()]))->out(false),
];

echo $OUTPUT->render_from_template('theme_bambuco/local/subthemes', $data);

echo $OUTPUT->footer();
