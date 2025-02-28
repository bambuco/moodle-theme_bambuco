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
 * This class contains the changepasswordlink webservice functions.
 *
 * @package    theme_bambuco
 * @copyright  2025 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

namespace theme_bambuco\external;

use external_api;
use external_function_parameters;
use external_value;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/login/lib.php');

/**
 * Service implementation.
 *
 * @copyright  2025 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class set_mode extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters(
            [
                'mode' => new external_value(PARAM_TEXT, 'Current mode: dark or light', VALUE_REQUIRED),
            ]
        );
    }

    /**
     * Save the current theme mode.
     *
     * @param string $mode Current theme mode
     * @return bool
     */
    public static function execute(string $mode): bool {
        global $PAGE, $CFG, $SESSION;

        if (!isloggedin() && !empty($CFG->forcelogin)) {
            require_login(null, true);
        }

        $syscontext = \context_system::instance();
        // The self::validate_context($syscontext) is not used because we require save the mode preference in session
        // to unauthenticated user. The security is managed locally.
        $PAGE->set_context($syscontext);

        // Parameter validation.
        $params = self::validate_parameters(
            self::execute_parameters(),
            [
                'mode' => $mode,
            ]
        );

        if (!in_array($params['mode'], \theme_bambuco\local\utils::AVAILABLE_MODES)) {
            return false;
        }

        if (isloggedin()) {
            set_user_preference('theme_bambuco-mode', $params['mode']);
        } else {
            $SESSION->theme_bambuco_mode = $params['mode'];
        }

        return true;

    }

    /**
     * Returns description of method result value.
     *
     * @return external_simple_structure
     */
    public static function execute_returns(): external_value {
        return new external_value(PARAM_BOOL, 'If mode saved');
    }
}
