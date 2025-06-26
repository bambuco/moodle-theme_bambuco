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
 * A login page layout for the BambuCo theme.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$bodyattributes = $OUTPUT->body_attributes();

$loginmorecontent = get_config('theme_bambuco', 'loginmorecontent');
$hasmorecontent = false;

if (!empty($loginmorecontent)) {
    $loginmorecontent = format_text($loginmorecontent, FORMAT_HTML, ['noclean' => true]);
    $hasmorecontent = true;
}

$url = $this->get_logo_url();
if ($url) {
    $url = $url->out(false);
}

$templatecontext = [
    'sitename' => format_string($SITE->fullname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'hasmorecontent' => $hasmorecontent,
    'loginmorecontent' => $loginmorecontent,
    'logourl' => $url,
    'wwwroot' => (string)(new moodle_url('/')),
    'gotohome' => !$CFG->forcelogin || $PAGE->pagetype != 'login-index',
];

echo $OUTPUT->render_from_template('theme_bambuco/login', $templatecontext);
