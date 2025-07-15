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

use theme_bambuco\local\utils;

/**
 * Allows plugins to add any elements to the html.
 *
 * @package    theme_bambuco
 * @copyright  2024 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {

    /**
     * Callback to add custom skin.
     *
     * @param \core\hook\output\before_http_headers $hook
     */
    public static function before_http_headers(
        \core\hook\output\before_http_headers $hook,
    ): void {
        global $PAGE, $CFG, $COURSE, $SESSION;

        if ($PAGE->theme->name != 'bambuco') {
            return;
        }

        $config = get_config('theme_bambuco');
        // Check for subtheme in course.
        if (isset($SESSION->theme_bambuco_subthemecourse[$COURSE->id])) {
            if ($SESSION->theme_bambuco_subthemecourse[$COURSE->id] !== false) {
                utils::set_subtheme($SESSION->theme_bambuco_subthemecourse[$COURSE->id]);
            }
        } else if ($COURSE->id != SITEID && !empty($config->multithemecoursefield)) {

            // Load custom course fields.
            $handler = \core_customfield\handler::get_handler('core_course', 'course');
            $datas = $handler->get_instance_data($COURSE->id, true);

            if (isset($datas[$config->multithemecoursefield])) {
                $field = $datas[$config->multithemecoursefield];
            } else {
                $field = null;
            }

            if ($field) {
                $fieldvalue = $field->export_value();

                $subtheme = !empty($fieldvalue) ? utils::get_subthemebyidnumber($fieldvalue) : null;
                if ($subtheme) {
                    $SESSION->theme_bambuco_subthemecourse[$COURSE->id] = $subtheme;
                    utils::set_subtheme($subtheme);
                } else {
                    $SESSION->theme_bambuco_subthemecourse[$COURSE->id] = false;
                }
            }
        }

        $skinkey = utils::subthemekey('skin');
        $skin = get_config('theme_bambuco', $skinkey);

        if (!empty($skin)) {
            $PAGE->requires->css('/theme/bambuco/skin/bootswatch/dist/' . $skin . '/bootstrap.min.css');
            $PAGE->requires->css('/theme/bambuco/skin/fixes/bootswatch.css');

            $fixpath = $CFG->dirroot . '/theme/bambuco/skin/fixes/bootswatch/' . $skin . '/styles.css';
            if (file_exists($fixpath)) {
                $PAGE->requires->css('/theme/bambuco/skin/fixes/bootswatch/' . $skin . '/styles.css');
            }
        }

    }

    /**
     * Callback to add head elements.
     * Load social network metadata.
     *
     * @param \core\hook\output\before_standard_head_html_generation $hook
     */
    public static function before_standard_head_html_generation(
        \core\hook\output\before_standard_head_html_generation $hook,
    ): void {
        global $PAGE;

        if ($PAGE->theme->name != 'bambuco') {
            return;
        }

        $config = get_config('theme_bambuco');

        // Included fonts.
        $fontkey = utils::subthemekey('fontfamily');
        $font = $config->$fontkey;

        $otherfontfamily = $config->otherfontfamily;

        if (empty($font) && empty($otherfontfamily)) {
            return;
        }

        $headers = [];
        $headers[] = '<link rel="preconnect" href="https://fonts.googleapis.com">';
        $headers[] = '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';

        $includefonts = [];
        if (!empty($font)) {
            $includefonts[] = $font;
        }

        if (!empty($otherfontfamily)) {
            $otherfontfamily = explode(',', $otherfontfamily);
        } else {
            $otherfontfamily = [];
        }

        $includefonts = array_merge($includefonts, $otherfontfamily);

        foreach ($includefonts as $font) {
            $font = str_replace(' ', '+', $font);
            $headers[] = '<link href="https://fonts.googleapis.com/css2?family='
                                . $font
                                . ':wght@400;500;600;700&display=swap" rel="stylesheet">';
        }

        // Course header.
        if (!in_array($config->coursesheader, ['none', 'default'])) {

            $inpage = utils::use_custom_header();
            if ($inpage) {
                $coursebanner = utils::get_courseimage($PAGE->course);
                $headers[] = '<style>#page-header { background-image: url("' . $coursebanner . '"); }</style>';
            }
        }

        foreach ($headers as $header) {
            $hook->add_html($header);
        }
    }

    /**
     * Listener for the after_config hook.
     *
     * @param after_config $hook
     */
    public static function after_config(\core\hook\after_config $hook): void {
        global $CFG, $SESSION, $USER, $DB, $PAGE;

        if (during_initial_install() || isset($CFG->upgraderunning)) {
            // Do nothing during installation or upgrade.
            return;
        }

        if ($CFG->theme != 'bambuco') {
            return;
        }

        $config = get_config('theme_bambuco');

        if ($PAGE->pagetype == 'login-index' && !empty($config->usealtcha)) {
            require_once($CFG->dirroot . '/theme/bambuco/thirdparty/altcha/vendor/autoload.php');

            $logintoken = optional_param('logintoken', '', PARAM_TEXT);

            // If not empty it is a login request.
            if (!empty($logintoken)) {
                $bbcoaltcha = optional_param('bbcoaltcha', '', PARAM_TEXT);
                $payload = !empty($bbcoaltcha) ? (array)@json_decode(base64_decode($bbcoaltcha)) : '';

                $altcha = new \AltchaOrg\Altcha\Altcha($SESSION->bambuco_altcha_key ?? '');

                $ok = $altcha->verifySolution($payload, true);

                if (!$ok) {
                    redirect(
                            new \moodle_url('/login/index.php'),
                            get_string('altcha_expired', 'theme_bambuco'),
                            null,
                            \core\output\notification::NOTIFY_ERROR
                    );
                }
            }
        }

        if (empty($config->multitheme)) {
            return;
        }

        // Initialize the session variable for courses subthemes.
        if (!property_exists($SESSION, 'theme_bambuco_subthemecourse')) {
            $SESSION->theme_bambuco_subthemecourse = [
                SITEID => false,
            ];
        }

        $subtheme = null;
        if (defined('NO_MOODLE_COOKIES') && NO_MOODLE_COOKIES) {
            $bbcost = optional_param('bbcost', 0, PARAM_INT);
            if (!empty($bbcost)) {
                $subtheme = utils::get_subtheme($bbcost, false);
                utils::set_subtheme($subtheme);
                return;
            }
        }

        // Not used for guest users.
        // User id is 0 in some AJAX call.
        // It shouldn't happen because NO_MOODLE_COOKIES is already validated, but it is validated here
        // to prevent it from failing if there is a special case.
        if (!isloggedin() || isguestuser() || !$USER || empty($USER->id)) {
            return;
        }

        if (property_exists($SESSION, 'theme_bambuco_subtheme')) {
            if ($SESSION->theme_bambuco_subtheme === false) {
                return;
            }

            utils::set_subtheme($SESSION->theme_bambuco_subtheme);
            $subtheme = utils::current_subtheme();

            if ($PAGE->pagetype == 'site-index' && !empty($subtheme->homeurl)) {
                redirect($subtheme->fullhomeurl);
            }

            return;
        }

        // Check for not logged in.
        if (!empty($config->multithemeuserfield)) {

            $field = $DB->get_record('user_info_data', ['fieldid' => $config->multithemeuserfield, 'userid' => $USER->id]);
            if ($field) {
                $subtheme = utils::get_subthemebyidnumber($field->data);
                if ($subtheme) {
                    $SESSION->theme_bambuco_subtheme = $subtheme;
                    utils::set_subtheme($subtheme);
                    return;
                }
            }

        }

        $SESSION->theme_bambuco_subtheme = false;
        utils::set_subtheme(null);

    }

    /**
     * Listener for the after_course_updated hook.
     *
     * @param \core_course\hook\after_course_updated $hook
     */
    public static function after_course_updated(
        \core_course\hook\after_course_updated $hook,
    ): void {
        global $SESSION;

        // Clean up the session variable subcourse for the updated course.
        if (isset($SESSION->theme_bambuco_subthemecourse[$hook->course->id])) {
            unset($SESSION->theme_bambuco_subthemecourse[$hook->course->id]);
        }
    }
}
