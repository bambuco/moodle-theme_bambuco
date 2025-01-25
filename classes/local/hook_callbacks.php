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
        global $PAGE, $CFG;

        if ($PAGE->theme->name != 'bambuco') {
            return;
        }

        $skin = get_config('theme_bambuco', 'skin');

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
        $font = $PAGE->theme->settings->fontfamily;
        $otherfontfamily = $PAGE->theme->settings->otherfontfamily;

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

            $inpage = \theme_bambuco\local\utils::use_custom_header();
            if ($inpage) {
                $coursebanner = \theme_bambuco\local\utils::get_courseimage($PAGE->course);
                $headers[] = '<style>#page-header { background-image: url("' . $coursebanner . '"); }</style>';
            }
        }

        foreach ($headers as $header) {
            $hook->add_html($header);
        }
    }

}
