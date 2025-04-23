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

use theme_bambuco\local\utils;

defined('MOODLE_INTERNAL') || die();

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_bambuco_get_extra_scss($theme) {

    $content = '';
    $keybgimage = utils::subthemekey('backgroundimage');
    $imageurl = $theme->setting_file_url($keybgimage, $keybgimage);

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

    $fontfamilykey = utils::subthemekey('fontfamily');
    $font = !empty($theme->settings->$fontfamilykey) ? $theme->settings->$fontfamilykey : '';
    if (!empty($font)) {
        $content .= ' body { font-family: "' . $font . '"; } ';
    }

    // Always return the background image with the scss when we have it.
    // We need include the $theme->settings->scss because it is included by the parent theme in a bad moment.
    // We need to include it when other styles are already loaded.
    $scsskey = utils::subthemekeys('scss');

    $contentscss = '';
    foreach ($scsskey as $key) {
        if (isset($theme->settings->$key)) {
            $contentscss .= $theme->settings->$key . "\n";
        }
    }

    return $contentscss . $content;
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
function theme_bambuco_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM
            && (
                in_array($filearea, ['logo', 'backgroundimage', 'loginbackgroundimage', 'courseheaderimagefile']) ||
                strpos($filearea, 'backgroundimage_') === 0 // Subtheme bgimage.
            )
        ) {

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
    $preset = utils::subthemekey('preset');
    $filename = $theme->settings->$preset;
    $fs = get_file_storage();

    $custompresents = ['abaco.scss'];
    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if (in_array($filename, $custompresents)) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/bambuco/scss/preset/' . $filename);
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
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_bambuco_get_pre_scss($theme) {

    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        utils::subthemekey('brandcolor') => ['primary'],
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre-scss.
    $scssprekey = utils::subthemekeys('scsspre');
    foreach ($scssprekey as $key) {
        if (isset($theme->settings->$key)) {
            $scss .= $theme->settings->$key . "\n";
        }
    }

    return $scss;
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
        $scssprekey = utils::subthemekeys('scsspre');
        $scsspre = '';
        foreach ($scssprekey as $key) {
            if (isset($theme->settings->$key)) {
                $scsspre .= $theme->settings->$key . ' ';
            }
        }

        $scsskey = utils::subthemekeys('scss');
        $scss = '';
        foreach ($scsskey as $key) {
            if (isset($theme->settings->$key)) {
                $scss .= $theme->settings->$key . ' ';
            }
        }

        if (!empty($scss) || !empty($scss)) {
            $themescss = $scsspre . ' ' . $scss;
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

/**
 * Alter CSS URLs.
 *
 * @param array $urls The URLs to alter.
 */
function theme_bambuco_alter_css_urls(&$urls) {
    global $CFG;

    $subtheme = utils::current_subtheme();
    if (empty($subtheme)) {
        return;
    }

    foreach ($urls as $key => $url) {
        if ($url->param('type') == 'scss') {
            $url->param('bbcost', $subtheme->id);
            $url->param('subtype', 'subtheme_' . $subtheme->id);
        } else if (empty($CFG->themedesignermode) && strpos($url->get_path(), '/theme/styles.php/bambuco/') !== false) {
            $url = (string)$url;
            unset($urls[$key]);
            $url = str_replace('/theme/styles.php/bambuco/', '/theme/bambuco/bbcostyles.php/', $url);
            $url .= '/' . $subtheme->id;
            $url = new moodle_url($url);
            $urls[$key] = $url;
        }

    }
}
