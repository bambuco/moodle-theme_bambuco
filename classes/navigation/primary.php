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

namespace theme_bambuco\navigation;

use renderer_base;

/**
 * Overriden primary navigation renderable
 *
 * This file combines primary nav, custom menu, lang menu and
 * usermenu into a standardized format for the frontend
 *
 * @package    theme_bambuco
 * @copyright  2023 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class primary extends \core\navigation\output\primary {
    /**
     * Get/Generate the user menu.
     *
     * This is leveraging the data from user_get_user_navigation_info and the logic in $OUTPUT->user_menu()
     *
     * @param renderer_base $output
     * @return array
     */
    public function get_user_menu(renderer_base $output): array {
        global $CFG;

        $usermenudata = parent::get_user_menu($output);

        $signuplink = get_config('theme_bambuco', 'signuplink');

        if ($signuplink && ($CFG->registerauth == 'email' || !empty($CFG->registerauth))) {
            $usermenudata['usesignuplink'] = true;
            $usermenudata['signupurl'] = new \moodle_url('/login/signup.php');
        }

        return $usermenudata;
    }
}
