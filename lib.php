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
 * Theme functions.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require $CFG->dirroot . '/theme/bambuco/thirdparty/altcha/vendor/autoload.php';

use AltchaOrg\Altcha\ChallengeOptions;
use AltchaOrg\Altcha\Altcha;

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_bambuco_get_extra_scss($theme) {

    $content = '';
    $imageurl = $theme->setting_file_url('backgroundimage', 'backgroundimage');

    // Sets the background image, and its settings.
    if (!empty($imageurl)) {
        $content .= '@media (min-width: 768px) {';
        $content .= 'body { ';
        $content .= "background-image: url('$imageurl'); background-size: cover;";
        $content .= ' } }';
    }

    // Sets the login background image.
    $loginbackgroundimageurl = $theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
    if (!empty($loginbackgroundimageurl)) {
        $content .= 'body.pagelayout-login #page { ';
        $content .= "background-image: url('$loginbackgroundimageurl'); background-size: cover;";
        $content .= ' }';
    }

    $font = property_exists($theme->settings, 'fontfamily') ? $theme->settings->fontfamily : '';
    if (!empty($font)) {
        $content .= ' body { font-family: "' . $font . '"; } ';
    }

    // Always return the background image with the scss when we have it.
    // We need include the $theme->settings->scss because it is included by the parent theme in a bad moment.
    // We need to include it when other styles are already loaded.
    return !empty($theme->settings->scss) ? "{$theme->settings->scss}  \n  {$content}" : $content;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_bambuco_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM
            && (in_array($filearea, ['logo', 'backgroundimage', 'loginbackgroundimage', 'courseheaderimagefile']))) {

        $theme = theme_config::load('bambuco');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_bambuco_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_boost', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    $scss .= file_get_contents($CFG->dirroot . '/theme/bambuco/scss/subtheme/default/include.scss');

    return $scss;
}

/**
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_bambuco_get_precompiled_css() {
    global $CFG;

    return file_get_contents($CFG->dirroot . '/theme/boost/style/moodle.css');
}

/**
 * Moodle native lib/navigationlib.php calls this hook allowing us to override UI.
 */
function theme_bambuco_before_http_headers() {
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
 * Include extra fonts.
 *
 * @return string The HTML Meta to insert before the head.
 */
function theme_bambuco_before_standard_html_head() {
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

        $inpage = \theme_bambuco\utils::use_custom_header();
        if ($inpage) {
            $coursebanner = \theme_bambuco\utils::get_courseimage($PAGE->course);
            $headers[] = '<style>#page-header { background-image: url("' . $coursebanner . '"); }</style>';
        }
    }

    return implode("\n", $headers);
}

/**
 * Post processes CSS.
 *
 * This method post processes all of the CSS before it is served for this theme.
 *
 * @param string $css The CSS to process.
 * @return string The processed CSS.
 */
function theme_bambuco_css_postprocess($css) {
    if (strpos($css, '/**EDITOR-STYLES**/') !== false) {
        $theme = theme_config::load('bambuco');

        $themescss = '';
        if (!empty($theme->settings->scsspre) || !empty($theme->settings->scss)) {
            $themescss = $theme->settings->scsspre . ' ' . $theme->settings->scss;
            $compiler = new core_scss();
            $compiler->append_raw_scss($themescss);

            try {
                // Compile!
                $themescss = $compiler->to_css();

            } catch (\Exception $e) {
                $themescss = '';
                debugging('Error while compiling SCSS: ' . $e->getMessage(), DEBUG_DEVELOPER);
            }

            // Try to save memory.
            $compiler = null;
            unset($compiler);

        }

        $css = str_replace('/**EDITOR-STYLES**/', $themescss, $css);
    }
    return $css;
}

function theme_bambuco_after_config() {
    global $CFG, $SESSION, $PAGE;

    if ($CFG->theme != 'bambuco' || $PAGE->pagetype != 'login-index') {
        return;
    }

    $config = get_config('theme_bambuco');

    if (!empty($config->usealtcha)) {
        $logintoken = optional_param('logintoken', '', PARAM_TEXT);

        // If empty it is not a login request.
        if (empty($logintoken)) {
            return;
        }

        $bbcoaltcha = optional_param('bbcoaltcha', '', PARAM_TEXT);
        $payload = !empty($bbcoaltcha) ? (array)@json_decode(base64_decode($bbcoaltcha)) : '';

        $altcha = new Altcha($SESSION->bambuco_altcha_key);

        $ok = $altcha->verifySolution($payload, true);

        if (!$ok) {
            redirect(
                    new moodle_url('/login/index.php'),
                    get_string('altcha_error', 'theme_bambuco'),
                    null,
                    \core\output\notification::NOTIFY_ERROR
            );
        }
    }

}
