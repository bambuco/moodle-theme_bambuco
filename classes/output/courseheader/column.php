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

namespace theme_bambuco\output\courseheader;

use renderable;
use renderer_base;
use templatable;
use theme_bambuco\local\utils;

/**
 * Output for the course header based in column presentation.
 *
 * @package    theme_bambuco
 * @copyright  2025 David Herney - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class column implements renderable, templatable {
    /**
     * @var object Course information.
     */
    private $course;

    /**
     * Constructor.
     *
     * @param object $course Course object containing course information.
     */
    public function __construct($course) {
        $this->course = $course;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output The renderer to use for rendering.
     * @return array An array of variables to be used in the template.
     */
    public function export_for_template(renderer_base $output): array {
        global $PAGE, $COURSE;

        $coursebanner = utils::get_courseimage($PAGE->course);

        $defaultvariables = [
            'hasbanner' => !empty($coursebanner),
            'imageurl' => $coursebanner,
            'coursename' => format_string($COURSE->fullname),
        ];

        return $defaultvariables;
    }
}
