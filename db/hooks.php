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
 * Hook callbacks
 *
 * @package    theme_bambuco
 * @copyright  2024 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$callbacks = [
    [
        'hook' => \core\hook\after_config::class,
        'callback' => [\theme_bambuco\local\hook_callbacks::class, 'after_config'],
    ],
    [
        'hook' => \core\hook\output\before_http_headers::class,
        'callback' => [\theme_bambuco\local\hook_callbacks::class, 'before_http_headers'],
    ],
    [
        'hook' => \core\hook\output\before_standard_head_html_generation::class,
        'callback' => [\theme_bambuco\local\hook_callbacks::class, 'before_standard_head_html_generation'],
    ],
    [
        'hook' => \core_course\hook\after_course_updated::class,
        'callback' => [\theme_bambuco\local\hook_callbacks::class, 'after_course_updated'],
    ],
];
