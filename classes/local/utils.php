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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/form/selectwithlink.php');
/**
 * Some util functions.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils {

    /**
     * Settings that the subtheme can override.
     *
     * @var array
     */
    const SUBTHEME_SETTINGS = [
        'preset',
        'backgroundimage',
        'brandcolor',
        'fontfamily',
        'bbcoscsspre',
        'bbcoscss',
        'skin',
    ];

    /**
     * Inherit the maint theme setting.
     */
    const SUBTHEME_INHERIT = 0;

    /**
     * Overwrite the maint theme setting.
     */
    const SUBTHEME_OVERWRITE = 1;

    /**
     * Join the subtheme and theme setting.
     */
    const SUBTHEME_JOIN = 2;

    /**
     * Current subtheme.
     *
     * @var object|null
     */
    public static $subtheme = null;

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
     * Get the subthemes.
     *
     * @return array
     */
    public static function get_subthemes(): array {
        global $DB;

        $subthemes = $DB->get_records('theme_bambuco_subthemes', null, 'name ASC');

        foreach ($subthemes as $subtheme) {
            $subtheme->fullhomeurl = strpos($subtheme->homeurl, '/') === 0 ? new \moodle_url($subtheme->homeurl) :
                                                                            $subtheme->homeurl;
            $subtheme->customsettings = @json_decode($subtheme->customsettings);
        }

        return $subthemes;
    }

    /**
     * Get the subtheme.
     *
     * @param int $id Subtheme id.
     * @param bool $required Whether it is required to exist or null is returned if it does not exist.
     * @return object
     */
    public static function get_subtheme(int $id, bool $required = true): ?object {
        global $DB;

        $exist = $required ? MUST_EXIST : IGNORE_MISSING;
        $subtheme = $DB->get_record('theme_bambuco_subthemes', ['id' => $id], '*', $exist);

        $subtheme->fullhomeurl = strpos($subtheme->homeurl, '/') === 0 ? new \moodle_url($subtheme->homeurl) : $subtheme->homeurl;
        $subtheme->customsettings = @json_decode($subtheme->customsettings);

        return $subtheme;
    }

    /**
     * Get the subtheme by id number.
     *
     * @param string $id Subtheme id number.
     * @return object|null
     */
    public static function get_subthemebyidnumber(string $id): ?object {
        global $DB;

        $subtheme = $DB->get_record('theme_bambuco_subthemes', ['idnumber' => $id]);

        if (!$subtheme) {
            return null;
        }

        $subtheme->fullhomeurl = strpos($subtheme->homeurl, '/') === 0 ? new \moodle_url($subtheme->homeurl) : $subtheme->homeurl;
        $subtheme->customsettings = @json_decode($subtheme->customsettings);

        return $subtheme;
    }

    /**
     * Get the current subtheme.
     *
     * @return object|null|bool
     */
    public static function current_subtheme(): ?object {
        return self::$subtheme;
    }

    /**
     * Set the current subtheme.
     *
     * @param string $subtheme Subtheme id.
     * @return void
     */
    public static function set_subtheme($subtheme) {
        self::$subtheme = $subtheme;
    }

    /**
     * Set the setting up subtheme in the session.
     *
     * @param int|null $id Subtheme id or null to unset.
     * @return void
     */
    public static function set_settingup_subtheme(?int $id): void {
        global $SESSION;

        if (!empty($id)) {
            $SESSION->theme_bambuco_settingupsubtheme = self::get_subtheme($id);
        } else {
            unset($SESSION->theme_bambuco_settingupsubtheme);
        }
    }

    /**
     * Get the setting up subtheme from the session.
     *
     * @return object|null
     */
    public static function settingup_subtheme(): ?object {
        global $SESSION;

        if (!property_exists($SESSION, 'theme_bambuco_settingupsubtheme')) {
            return null;
        }

        return $SESSION->theme_bambuco_settingupsubtheme;
    }

    /**
     * Check if the property is customizable in the subtheme.
     *
     * @param string $key Property name.
     * @param object|null $subtheme Subtheme object.
     * @return bool
     */
    public static function iscustomizable_subtheme(string $key, ?object $subtheme): bool {

        static $settings = [];

        if (isset($settings[$key])) {
            return $settings[$key];
        }

        if (empty($subtheme)) {
            // All parameters are customizable for the default theme.
            $settings[$key] = true;
            return true;
        }

        if (empty($subtheme->customsettings) || !in_array($key, self::SUBTHEME_SETTINGS)) {
            $settings[$key] = false;
            return false;
        }

        if (!property_exists($subtheme->customsettings, $key)) {
            $settings[$key] = false;
            return false;
        }

        $value = $subtheme->customsettings->$key;
        if ($value == self::SUBTHEME_INHERIT) {
            $settings[$key] = false;
            return false;
        }

        $settings[$key] = true;
        return true;
    }

    /**
     * Get the setting key according the current subtheme.
     *
     * @param string $key Setting key.
     * @return string
     */
    public static function subthemekey(string $key): string {

        $subtheme = self::current_subtheme();

        if ($subtheme) {
            $custom = self::iscustomizable_subtheme($key, $subtheme);
            if ($custom) {
                return $key . '_' . $subtheme->id;
            }
        }

        return $key;
    }

    /**
     * Get the setting keys according the current subtheme.
     * Used when both the main and subtheme parameters can be used (join mode).
     *
     * @param string $key Setting key.
     * @return array
     */
    public static function subthemekeys(string $key): array {

        $subtheme = self::current_subtheme();
        $keys = [$key];

        if ($subtheme) {
            $custom = self::iscustomizable_subtheme($key, $subtheme);

            if ($custom) {
                if ($subtheme->customsettings->$key == self::SUBTHEME_OVERWRITE) {
                    return [$key . '_' . $subtheme->id];
                }

                // Join mode.
                $keys[] = $key . '_' . $subtheme->id;
            }
        }

        return $keys;
    }
}
