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

/**
 * Output for the course header based in teacher presentation.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class teacher implements renderable, templatable {

    /**
     * @var object Course information.
     */
    private $course;

    /**
     * Constructor.
     *
     * @param string $uniqueid The uniqueid of the block instance.
     * @param string $view The view type.
     */
    public function __construct($course) {

        $this->course = $course;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \core_renderer $output
     * @return array Context variables for the template
     */
    public function export_for_template(renderer_base $output) {
        global $CFG, $DB;

        $defaultvariables = [
            'hasteachers' => false,
        ];

        $course = $this->course;
        if ($course instanceof \stdClass) {
            $course = new \core_course_list_element($course);
        }

        // Course instructors.
        if ($course->has_course_contacts()) {
            $instructors = $course->get_course_contacts();
            if ($instructors && count($instructors) > 0) {

                $teachers = [];
                foreach ($instructors as $key => $instructor) {
                    $teacher = new \stdClass();
                    $teacher->name = $instructor['username'];
                    $teacher->improvedname = \theme_bambuco\local\utils::wrap_text($instructor['username']);

                    $teacherdata = $DB->get_record('user', ['id' => $key]);
                    $teacher->image = $output->user_picture($teacherdata, [
                        'size' => 100,
                        'link' => false
                    ]);

                    $url = $CFG->wwwroot . '/user/profile.php?id=' . $key;
                    $teacher->url = $url;

                    $teachers[] = $teacher;
                }

                $defaultvariables['teachers'] = $teachers;
                $defaultvariables['hasteachers'] = !empty($teachers);
            }
        }

        return $defaultvariables;
    }
}
