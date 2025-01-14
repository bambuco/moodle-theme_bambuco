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

namespace theme_bambuco\local;

/**
 * Some util functions.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils {

    /**
     * Available theme modes.
     *
     * @var array
     */
    const AVAILABLE_MODES = ['light', 'dark'];

    /**
     * Wrap text in two spans.
     *
     * @param string $text
     * @param bool $removeextra Remove content between parentheses.
     * @return string
     */
    public static function wrap_text(string $text, bool $removeextra = false): string {

        // Only apply to headings that don't already have HTML tags.
        if (strip_tags($text) == $text) {

            if ($removeextra && strpos($text, '(') > 0) {
                // Remove content between parentheses.
                $text = preg_replace('/\([^)]*\)/', '', $text);
            }

            $words = explode(' ', $text);

            // Escribir en una variable la primera mitad de las palabras y en otra variable las restantes.
            $firsthalf = implode(' ', array_slice($words, 0, round(count($words) / 2)));
            $secondhalf = implode(' ', array_slice($words, round(count($words) / 2)));

            $text = \html_writer::tag('span', $firsthalf) . ' ' . \html_writer::tag('span', $secondhalf);
        }

        return $text;
    }

    /**
     * Get the course preview image.
     *
     * @param \stdClass $course Course object.
     * @return string Image url.
     */
    public static function get_courseimage($course): string {
        global $CFG, $OUTPUT, $PAGE;

        $config = get_config('theme_bambuco');

        if (empty($config->courseheaderimage) || $config->courseheaderimage == 'none') {
            return '';
        }

        $courseimage = '';
        if (strpos($config->courseheaderimage, 'overview') !== false) {
            $coursefull = new \core_course_list_element($course);

            foreach ($coursefull->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();

                if ($isimage) {
                    $url = \moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php",
                            '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                            $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);

                    $courseimage = $url;
                    break;
                }
            }
        }

        if (empty($courseimage) && $config->courseheaderimage != 'overviewonly') {

            switch ($config->courseheaderimagetype) {
                case 'generated':
                    $courseimage = $OUTPUT->get_generated_image_for_id($course->id);
                break;
                default:
                    $imageurl = $PAGE->theme->setting_file_url('courseheaderimagefile', 'courseheaderimagefile');
                    if (!empty($imageurl)) {
                        $courseimage = $imageurl;
                    } else {
                        $courseimage = (string)(new \moodle_url($CFG->wwwroot . '/theme/bambuco/pix/bannercourse.png'));
                    }
            }
        }

        return $courseimage;
    }

    /**
     * Define if use custom header in the current page.
     *
     * @return bool
     */
    public static function use_custom_header(): bool {
        global $PAGE;

        $config = get_config('theme_bambuco');

        if (in_array($config->coursesheader, ['default'])) {
            return false;
        }

        $pagetype = $PAGE->pagetype;

        $enabledviews = explode(',', $config->courseheaderview);

        foreach ($enabledviews as $view) {
            if (!empty($view) && strpos($pagetype, $view) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the course footer image.
     *
     * @param \stdClass $course Course object.
     * @return \stdClass Object: {content} or {src, name} properties.
     */
    public static function get_coursefooter($course): ?object {
        global $DB;

        $config = get_config('theme_bambuco');
        $coursefooter = null;

        // Content by category.
        if (!empty($config->contentbycategory) && $course->id != SITEID) {

            $category = $DB->get_record('course_categories', ['id' => $course->category]);

            if ($category) {
                $lines = explode("\n", $config->contentbycategory);

                foreach ($lines as $line) {
                    $options = explode('|', $line);

                    if (count($options) != 2) {
                        continue;
                    }

                    $parentid = $options[0];
                    if ($category->id == $parentid || strpos($category->path, "/{$parentid}/") !== false) {
                        $coursefooter = new \stdClass();

                        if (strpos($options[1], 'http') !== false) {
                            $coursefooter->src = $options[1];
                            $coursefooter->name = $category->name;
                            break;
                        } else {
                            $coursefooter->content = format_text($options[1], FORMAT_HTML,
                                                    ['context' => \context_course::instance($course->id)]);
                            break;
                        }
                    }
                }
            }
        }

        return $coursefooter;
    }

    /**
     * Get the theme mode.
     *
     * @return string
     */
    public static function get_theme_mode(): string {
        global $SESSION;

        $mode = get_user_preferences('theme_bambuco-mode', null);

        if (!$mode) {
            if (property_exists($SESSION, 'theme_bambuco_mode')) {
                $mode = $SESSION->theme_bambuco_mode;
            } else {
                return '';
            }
        }

        if (!in_array($mode, self::AVAILABLE_MODES)) {
            $mode = 'light';
        }

        return $mode;
    }
}
