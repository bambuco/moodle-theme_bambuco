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
 * Overriden theme boost core renderer.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_bambuco\output;

require $CFG->dirroot . '/theme/bambuco/thirdparty/altcha/vendor/autoload.php';

use AltchaOrg\Altcha\ChallengeOptions;
use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\BaseChallengeOptions;

/**
 * Core renderers.
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $SITE, $SESSION;

        $context = $form->export_for_template($this);

        $context->errorformatted = $this->error_text($context->error);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context->logourl = $url;
        $context->sitename = format_string($SITE->fullname, true,
                ['context' => \context_course::instance(SITEID), "escape" => false]);
        $context->wwwroot = (string)(new \moodle_url('/'));

        if ($context->hasidentityproviders) {
            $layout = get_config('theme_bambuco', 'loginformlayout');
            $context->toexternal = ($layout == 'toexternal');
        }

        $usealtcha = get_config('theme_bambuco', 'usealtcha');
        if ($usealtcha) {

            if (!isset($SESSION->bambuco_altcha_key)) {
                $SESSION->bambuco_altcha_key = openssl_random_pseudo_bytes(32);
            }

            $altcha = new Altcha($SESSION->bambuco_altcha_key);

            // Create a new challenge.
            $options = new ChallengeOptions(
                maxNumber: BaseChallengeOptions::DEFAULT_MAX_NUMBER, // The maximum random number.
                expires: (new \DateTimeImmutable())->add(new \DateInterval('PT10S')),
            );

            $strings = [
                'ariaLinkLabel' => get_string('altcha_arialinklabel', 'theme_bambuco'),
                'error' => get_string('altcha_error', 'theme_bambuco'),
                'expired' => get_string('altcha_expired', 'theme_bambuco'),
                'footer' => get_string('altcha_footer', 'theme_bambuco'),
                'label' => get_string('altcha_label', 'theme_bambuco'),
                'verified' => get_string('altcha_verified', 'theme_bambuco'),
                'verifying' => get_string('altcha_verifying', 'theme_bambuco'),
                'waitAlert' => get_string('altcha_waitalert', 'theme_bambuco'),
            ];

            $challenge = $altcha->createChallenge($options);
            $context->altchawidget = (object)[
                'name' => 'bbcoaltcha',
                'maxnumber' => BaseChallengeOptions::DEFAULT_MAX_NUMBER,
                'challengejson' => json_encode($challenge),
                'strings' => json_encode($strings),
            ];
        }

        return $this->render_from_template('core/loginform', $context);
    }

    /**
      * Renders the header bar.
      *
      * @param \context_header $contextheader Header bar object.
      * @return string HTML for the header bar.
      */
    protected function render_context_header(\context_header $contextheader) {
        global $DB;

        if ($contextheader->headinglevel != 1) {
            return parent::render_context_header($contextheader);
        }

        // Course header customization.
        $config = get_config('theme_bambuco');
        $inpage = false;

        $html = parent::render_context_header($contextheader);

        $inpage = \theme_bambuco\utils::use_custom_header();

        if ($inpage) {

            $course = $this->page->course;

            if ($course) {

                if ($config->coursesheader != 'basic') {
                    if ($config->coursesheader == 'none') {
                        return '';
                    }

                    $outputclass = '\\theme_bambuco\\output\\courseheader\\' . $config->coursesheader;
                    $headermanager = new $outputclass($course);
                    $courseheaderdata = $headermanager->export_for_template($this);

                    $html .= $this->render_from_template('theme_bambuco/courseheader_' . $config->coursesheader, $courseheaderdata);
                }

                if (!empty($config->coursemenu)) {
                    $context = $this->page->context;

                    $menudata = [
                        'options' => []
                    ];
                    $options = explode("\n", $config->coursemenu);

                    foreach ($options as $option) {
                        $one = explode('|', $option);

                        if (count($one) != 6) {
                            continue;
                        }

                        if ($one[0] == '*' || has_capability($one[0], $context)) {

                            $item = new \stdClass();
                            $item->target = trim($one[3]);
                            $item->name = trim($one[4]);
                            $item->cssclass = trim($one[5]);

                            if ($one[1] == 'url') {
                                $url = str_replace('{courseid}', $course->id, $one[2]);

                                if (substr($url, 0, 1) == '/') {
                                    $item->url = new \moodle_url($url);
                                } else {
                                    $item->url = $url;
                                }
                            } else if (substr($one[1], 0, 4) == 'mod_') {
                                $modulename = substr($one[1], 4, strlen($one[1]) - 4);

                                if (!empty($one[2])) {
                                    $instance = $DB->get_records($modulename, ['course' => $course->id]);

                                    if (!$instance || count($instance) == 0) {
                                        continue;
                                    }

                                    $instance = reset($instance);

                                    if (empty($item->name) && property_exists($instance, 'name')) {
                                        $item->name = $instance->name;
                                    }

                                    $cm = get_coursemodule_from_instance($modulename, $instance->id, $course->id);

                                    $item->url = new \moodle_url('/mod/' . $modulename . '/view.php', array('id' => $cm->id));

                                } else {
                                    $item->url = new \moodle_url('/mod/' . $modulename . '/index.php', array('id' => $course->id));
                                }
                            }

                            $menudata['options'][] = $item;

                        }
                    }

                    if (!empty($menudata['options'])) {
                        $html .= $this->render_from_template('theme_bambuco/bbcocoursemenu', $menudata);
                    }
                }
            }

        }

        return $html;
    }

    /**
     * Outputs a heading
     *
     * @param string $text The text of the heading
     * @param int $level The level of importance of the heading. Defaulting to 2
     * @param string $classes A space-separated list of CSS classes. Defaulting to null
     * @param string $id An optional ID
     * @return string the HTML to output.
     */
    public function heading($text, $level = 2, $classes = null, $id = null) {
        global $CFG;
        $removeextrainwrap = isset($CFG->theme_bambuco_removeextrainwrap) ? $CFG->theme_bambuco_removeextrainwrap : false;
        $text = \theme_bambuco\utils::wrap_text($text, $removeextrainwrap);
        return parent::heading($text, $level, $classes, $id);
    }


    /**
     * Render the login signup form into a nice template for the theme.
     *
     * @param mform $form
     * @return string
     */
    public function render_login_signup_form($form) {
        global $OUTPUT, $CFG;

        $signupidentityproviders = get_config('theme_bambuco', 'signupidentityproviders');
        $content = parent::render_login_signup_form($form);

        if ($signupidentityproviders && !$CFG->authpreventaccountcreation) {

            $authsequence = get_enabled_auth_plugins(true);
            $providers = \auth_plugin_base::get_identity_providers($authsequence);
            $identityproviders = \auth_plugin_base::prepare_identity_providers_for_output($providers, $OUTPUT);

            $context = [
                'identityproviders' => $identityproviders,
                'hasidentityproviders' => !empty($identityproviders),
            ];

            $content .= $this->render_from_template('theme_bambuco/login_external', $context);
        }

        return $content;
    }

    /**
     * Returns HTML attributes to use within the body tag. This includes an ID and classes.
     *
     * @param string|array $additionalclasses Any additional classes to give the body tag,
     * @return string
     */
    public function body_attributes($additionalclasses = []) {
        global $DB;

        if (!is_array($additionalclasses)) {
            $additionalclasses = explode(' ', $additionalclasses);
        }

        $bodyattributes = ' id="'. $this->body_id() . '"';
        $coursewidthfield = get_config('theme_bambuco', 'coursewidthfield');

        $bodyclasses = $this->body_css_classes($additionalclasses);
        $otherattributes = '';
        if (!empty($coursewidthfield)) {
            $widthvalue = $DB->get_field('customfield_data', 'value', ['fieldid' => $coursewidthfield,
                                                                        'instanceid' => $this->page->course->id]);

            $widthvalue = clean_param($widthvalue, PARAM_ALPHANUMEXT);
            if ($widthvalue) {
                if ($widthvalue == 'unlimitedwidth') {
                    $bodyclasses = str_replace('limitedwidth', '', $bodyclasses);
                } else {
                    $otherattributes = ' style="max-width:' . $widthvalue . ';"';
                }
            }
        }

        $bodyattributes .= ' class="' . $bodyclasses . '" ' . $otherattributes;

        return $bodyattributes;
    }

}
