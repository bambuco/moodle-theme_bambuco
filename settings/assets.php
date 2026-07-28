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
 * Theme assets settings.
 *
 * @package    theme_bambuco
 * @copyright  2026 David Herney @ BambuCo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/bambuco/lib.php');

$page = new admin_settingpage('theme_bambuco_assets', get_string('assetssettings', 'theme_bambuco'));

if ($ADMIN->fulltree) {
    $name = 'theme_bambuco/assetsfiles';
    $title = get_string('assetsfiles', 'theme_bambuco');
    $description = get_string('assetsfiles_desc', 'theme_bambuco');
    $options = [
        'maxfiles' => 200,
        'subdirs' => 0,
        'accepted_types' => [
            '.png', '.jpg', '.jpeg', '.gif', '.webp', '.svg', '.avif', '.ico',
            '.css', '.js', '.mjs',
            '.mp4', '.webm', '.ogg',
            '.mp3', '.wav', '.aac', '.m4a', '.oga',
            '.pdf',
            '.woff', '.woff2', '.ttf', '.otf', '.eot',
        ],
    ];

    $setting = new admin_setting_configstoredfile($name, $title, $description, 'assets', 0, $options);
    $page->add($setting);

    $page->add(new admin_setting_heading(
        'theme_bambuco/assetsurls',
        get_string('assetsurls', 'theme_bambuco'),
        theme_bambuco_assets_urls_admin_html()
    ));
}

$settings->add('theme_bambuco', $page);
