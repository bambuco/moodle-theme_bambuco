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
 * Subtheme form.
 *
 * @package    theme_bambuco
 * @copyright  2025 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_bambuco\form;

use theme_bambuco\local\utils;

/**
 * Class subtheme
 *
 * @package    theme_bambuco
 * @copyright  2025 David Herney @ BambuCo
 * @license    Commercial
 */
class subtheme extends \moodleform {

    /**
     * Add elements to form
     */
    public function definition() {

        $mform = $this->_form;

        $data = $this->_customdata['data'];

        // Name.
        $options = ['maxlength' => '63', 'size' => '25'];
        $mform->addElement('text', 'name', get_string('subtheme_name', 'theme_bambuco'), $options);
        $mform->addHelpButton('name', 'subtheme_name', 'theme_bambuco');
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required'), 'required');

        // ID number.
        $options = ['maxlength' => '255', 'size' => '25'];
        $mform->addElement('text', 'idnumber', get_string('subtheme_idnumber', 'theme_bambuco'), $options);
        $mform->addHelpButton('idnumber', 'subtheme_idnumber', 'theme_bambuco');
        $mform->setType('idnumber', PARAM_TEXT);
        $mform->addRule('idnumber', get_string('required'), 'required');

        // Home URL.
        $options = ['maxlength' => '511', 'size' => '25'];
        $mform->addElement('text', 'homeurl', get_string('subtheme_homeurl', 'theme_bambuco'), $options);
        $mform->addHelpButton('homeurl', 'subtheme_homeurl', 'theme_bambuco');
        $mform->setType('homeurl', PARAM_TEXT);

        // Enable custom settings.
        $options = [
            utils::SUBTHEME_INHERIT => get_string('subtheme_inherit', 'theme_bambuco'),
            utils::SUBTHEME_OVERWRITE => get_string('subtheme_overwrite', 'theme_bambuco'),
            utils::SUBTHEME_JOIN => get_string('subtheme_join', 'theme_bambuco'),
        ];
        foreach (utils::SUBTHEME_SETTINGS as $setting) {
            $mform->addElement('select', 'setting_' . $setting, get_string($setting, 'theme_bambuco'), $options);
            if (isset($data->customsettings->$setting)) {
                $mform->setDefault('setting_' . $setting, $data->customsettings->$setting);
            }
        }

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();

        // Finally set the current form data.
        $this->set_data($data);

    }
}
