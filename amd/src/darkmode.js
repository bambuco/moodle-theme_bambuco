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
 * Manage the dark mode.
 *
 * @module     theme_bambuco/darkmode
 * @copyright  2025 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Log from 'core/log';
import Ajax from 'core/ajax';

/**
 * Change the theme mode (dark or light).
 *
 * @method setThemeMode
 * @param {array} stylesheets
 */
export const setThemeMode = (stylesheets) => {
    // Get the theme toggle switch.
    const toggleTheme = document.getElementById('theme-darkmode');

    // Current page dont have the toggle switch.
    if (!toggleTheme) {
        return;
    }

    // Add event listener to the toggle switch.
    toggleTheme.addEventListener('change', function() {
        const isChecked = this.checked;

        if (isChecked) {
            document.body.classList.remove('lightmode');
            document.body.classList.add('darkmode');
            addOrRemoveStylesheet(stylesheets, 'dark');
            saveThemeMode('dark');

        } else {
            document.body.classList.remove('darkmode');
            document.body.classList.add('lightmode');
            addOrRemoveStylesheet(stylesheets, 'light');
            saveThemeMode('light');
        }
    });

};

/**
 * Add or remove the stylesheet depending on the mode.
 *
 * @param {array} stylesheets
 * @param {string} mode
 */
const addOrRemoveStylesheet = (stylesheets, mode) => {
    stylesheets.forEach(stylesheet => {

        if (!stylesheet.url) {
            return;
        }

        if (stylesheet.mode === mode) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = stylesheet.url;
            link.type = 'text/css';
            document.head.appendChild(link);
        } else {
            var link = document.querySelector(`link[href="${stylesheet.url}"]`);
            if (link) {
                link.remove();
            }
        }
    });
};

const saveThemeMode = (mode) => {
    Ajax.call([{
        methodname: 'theme_bambuco_set_mode',
        args: {'mode': mode},
        fail: function(e) {
            Log.debug(e);
        }
    }]);
};
