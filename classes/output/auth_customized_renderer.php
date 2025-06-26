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
 * Overriden auth_customized renderer.
 *
 * @package    theme_bambuco
 * @copyright  2024 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_bambuco\output;

/**
 * Plugin auth_customized renderer custom implementation.
 *
 * @package    theme_bambuco
 * @copyright  2024 David Herney Bernal - cirano. https://bambuco.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class auth_customized_renderer extends \auth_customized\output\renderer {

    /**
     * Return the template content for the signup.
     *
     * @param \auth_customized\forms\signup $form The form renderable
     * @return string HTML string
     */
    public function render_signup(\auth_customized\forms\signup $form): string {
        return $this->output->render_login_signup_form($form);
    }

}
